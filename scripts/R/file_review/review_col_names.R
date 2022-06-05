library('RMySQL')
library('dplyr')
library('dbplyr')
library('data.table')
library('stringi')

### Script to process all files referenced in files database table and extract the column headers used.

source('init.R')

two_headers_are_not_better_than_one <- function(df) {
    names(df) <- paste(
        trimws(df[1,]),
        trimws(df[2,]), sep = "_"
    )

    # remove trailing spaces and _ from column names
    names(df) <- trimws(names(df), which = "both", whitespace = "[_]")
    names(df) <- gsub(" ", "_", names(df))

    df <- df[-1,]
    return(df)
}

txt_files <- dbGetQuery(con, 'SELECT name from files where station_id != "10" and is_success = 1')$name

## Get the complete list of variable names
col_names <- NULL
for (checking_file_name in txt_files) {

    if (endsWith(checking_file_name, ".txt")) {

        ## all txt files (should) have 2 column headers
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = FALSE, sep = "\t")
        checking_file <- two_headers_are_not_better_than_one(checking_file)
    }
    if (endsWith(checking_file_name, ".csv")) {
        # manually create headers so we can deal with accented characters
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = TRUE)

    }

    col_names <- c(
        col_names,
        setdiff(names(checking_file), col_names)
    )
}

col_summary <- data.table(
    matrix(nrow = length(txt_files), ncol = length(col_names) + 1)
)

col_summary <- setNames(col_summary, c("file", col_names))
col_summary$file <- txt_files


for (checking_file_name in txt_files) {

    if (endsWith(checking_file_name, ".txt")) {
        ## maybe don't need to read it all in again, but it's perhaps better than keeping everything in memory?
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = FALSE, sep = "\t")
        checking_file <- two_headers_are_not_better_than_one(checking_file)
    }

    if (endsWith(checking_file_name, ".csv")) {
        # manually create headers so we can deal with accented characters
        checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file_name), header = TRUE)
    }

    for (col_name in col_names) {
        if (col_name %in% names(checking_file)) {
            col_summary[col_summary$file == checking_file_name, col_name] <- TRUE
        } else {
            col_summary[col_summary$file == checking_file_name, col_name] <- FALSE
        }
    }

}

## Export set of files with each column used
write.csv(col_summary, "col_summary.csv", row.names = FALSE)



