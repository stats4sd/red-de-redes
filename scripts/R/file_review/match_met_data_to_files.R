library('RMySQL')
library('dplyr')
library('dbplyr')
library('data.table')
library('stringi')

############################################
# SETUP
############################################
source('../init.R')

met_data <- tbl(con, 'met_data')
files <- tbl(con, 'files') %>%
    filter(is_success == 1 &
               new_records_count > 0 &
               station_id != 10) %>%
    collect()

## function to parse the 2-line headers of the .txt files into the correct format
two_headers_are_not_better_than_one <- function(df) {
    names(df) <- paste(
        trimws(df[1,]),
        trimws(df[2,]), sep = "_"
    )

    # remove trailing spaces and _ from column names
    names(df) <- trimws(names(df), which = "both", whitespace = "[_]")
    names(df) <- gsub(" ", "_", names(df))

    df <- df[-1,]
    df <- df[-1,]
    return(df)
}

## Function to:
##  - open a met_data file
##  - parse the headers based on the expected format (using .txt or .csv)
##  - convert the date-time into a timestamp
##  - returns the data frame containing all file data (plus formatted timestamp)
open_and_format_file <- function(checking_file_name) {
    if (endsWith(checking_file_name, ".txt")) {
        ## maybe don't need to read it all in again, but it's perhaps better than keeping everything in memory?
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = FALSE, sep = "\t")
        checking_file <- two_headers_are_not_better_than_one(checking_file)

        ## some files (I think just one?) has time in format "12:15 a"
        # TODO: handle 1621874199_1621873265_Serrano2_mayo_2021.txt

        checking_file <- checking_file %>%
            # find the timestamp
            mutate(date_time = paste(Date, Time, sep = " ")) %>%
            mutate(timestamp = as.POSIXct(date_time, format = "%d/%m/%y %H:%M", tz = "America/Anguilla"))
    }

    if (endsWith(checking_file_name, ".csv")) {
        # manually create headers so we can deal with accented characters
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = TRUE)

        ## some files have a.m and p.m...
        if (endsWith(checking_file[1, "Fecha.Hora"], ".m.")) {
            checking_file <- checking_file %>%
                # remove punctuation to allow as.POSIXct to parse 12 hour clock
                mutate("Fecha.Hora" = gsub(".m.", "m", Fecha.Hora)) %>%
                mutate("Fecha.Hora" = gsub(".m.", "m", Fecha.Hora)) %>%
                mutate(timestamp = as.POSIXct(Fecha.Hora, format = "%d/%m/%Y %I:%M:%S %p", tz = "America/Anguilla"))
        } else {
            checking_file <- checking_file %>%
                mutate(timestamp = as.POSIXct(Fecha.Hora, format = "%d/%m/%Y %H:%M:%S", tz = "America/Anguilla"))
        }
    }

    return(checking_file)
}

## Get the full list of text files to process
txt_files <- files$name

## import column maps from Python scripts
txt_columns <- jsonlite::read_json('../txt_column_list.json')
csv_columns <- jsonlite::read_json('../csv_column_list.json')

## column headers in R replace brackets, degrees symbol, percentages and slashes with dots when importing from csv files, so make sure the column headers here match:
names(csv_columns) <- gsub('[()/°% ]', '..', names(csv_columns))

## Create a data table to hold every file and the various counts we will generate
count_checks <- data.table(
    matrix(nrow = length(txt_files), ncol = 4)
)
count_checks <- setNames(count_checks, c("file", "count_to_check", "expected_count", "actual_count"))
count_checks$file <- txt_files

############################################################
# PROCESS TEXT FILES
############################################################
for (checking_file_name in txt_files) {

    # Get the database entry for the current file and extract station and expected file count
    file_from_files_table <- files %>% filter(name == checking_file_name)
    station_id <- file_from_files_table$station_id
    expected_file_count <- file_from_files_table$new_records_count

    ## We expect file_from_files_table to have a single record. If it doesn't, throw error:
    if (count(file_from_files_table) > 1) {
        stop(paste('There are multiple database entries for the file with filename =', checking_file_name))
    }

    checking_file <- open_and_format_file(checking_file_name)


    timestamps <- format(checking_file$timestamp)

    # suppress MySQL warnings in console
    suppressWarnings(
        # get all database met_data records that match the current file's station_id and timestamps
        file_met_data <- tbl(con, 'met_data') %>%
            filter(fecha_hora %in% timestamps & station_id == !!station_id) %>%
            collect()
    )

    ## Some files have duplicated records **inside** the file! So the 'expected_record_count' may not be what is inside the database.
    ## (When there are duplicate timestamps inside the same file, the importer will quietly ignore the duplicates due to the database constraints when importing).
    ## E.g: 1593100253_ejemplo1_01.txt has 8640 unique records, and 1358 records duplicated - these are exact duplicates, not just duplicated by timestamp! So the importer correctly discarded them, but we need to update the files table with the correct 'expected' number of entries. (I think the 'ejemplo1_01' text files are the only ones with duplicate entries within the same file)
    deduped_checking_file <- checking_file[!duplicated(checking_file),]
    deduped_checking_file <- deduped_checking_file %>%
        # check that the timestamp is found inside the met_data
        ## use as.character to remove potentially conflicting timezone info
        mutate(found = as.character(timestamp) %in% as.character(file_met_data$fecha_hora))

    ## At this point, deduped_checking_file should have the same number of records as files_met_data.
    ### Reasons why it might not: If some of the entries from this file were overwritten by entries from another file
    count_check <- (count(deduped_checking_file) - count(file_met_data))$n

    count_checks <- count_checks %>%
        mutate(count_to_check = ifelse(file == checking_file_name, as.integer(count_check), count_to_check)) %>%
        mutate(expected_count = ifelse(file == checking_file_name, as.integer(expected_file_count), expected_count)) %>%
        mutate(actual_count = ifelse(file == checking_file_name, count(file_met_data), actual_count))

    print('---------------------------------------------------------')
    print('###')
    print(paste0('Entry count check for ', checking_file_name, ' - Number of records found in the file vs number of records found in database = ', count_check))
    print(paste0('Expected new entry count:', expected_file_count))
    print(paste0('Actual new entry count:', count(file_met_data)))

    if (expected_file_count != count(file_met_data)) {
        print(paste0('This mistmatch means it is likely that some of the entries already existed from other files.'))
    }

    # if count_check is 0, update met_data with file_id
    if (count_check == 0) {

        # a sepearate update query for entries with existing file_ids (JSON_MERGE_PATCH appears to fail when one argument is null
        query <- paste(
            "UPDATE met_data set file_ids_for_sorting =
            CASE
                WHEN file_ids_for_sorting IS NOT NULL
                THEN JSON_MERGE_PRESERVE(file_ids_for_sorting, '{\"id\": ", file_from_files_table$id, "}')
                ELSE
                    '{\"id\": ", file_from_files_table$id, "}'
                END
                WHERE id in (", paste(file_met_data$id, collapse = ","), ");"
        )

        dbSendQuery(con, query)

    }


}

