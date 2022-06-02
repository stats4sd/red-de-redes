library('RMySQL')
library('dplyr')
library('dbplyr')
library('data.table')

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

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD"),
)
dbSendQuery(con, "set character set 'utf8mb4'")

met_data <- tbl(con, 'met_data')

txt_files <- list.files('../../storage/app/public/rawfiles/', pattern = '*.txt')


## Get the complete list of variable names
col_names <- NULL
for (checking_file in txt_files) {

    ## all txt files have 2 column headers
    checking_file <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file), header = FALSE, sep = "\t")
    checking_file <- two_headers_are_not_better_than_one(checking_file)

    # add any *new* col names to the complete list
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


for (checking_file in txt_files) {

    ## maybe don't need to read it all in again, but it's perhaps better than keeping everything in memory?
    checking_df <- read.csv(paste0('../../storage/app/public/rawfiles/', checking_file), header = FALSE, sep = "\t")
    checking_df <- two_headers_are_not_better_than_one(checking_df)

    for (col_name in col_names) {
        if (col_name %in% names(checking_df)) {
            col_summary[col_summary$file == checking_file, col_name] <- TRUE
        } else {
            col_summary[col_summary$file == checking_file, col_name] <- FALSE
        }
    }
}

write.csv(col_summary, "col_summary.csv", row.names = FALSE)


########################################################################
######### Checking filenames in db vs actual files
files_db <- tbl(con, 'files') %>% collect()

check <- list.files('../../storage/app/public/rawfiles/')

## merge check into files_db

files_db <- files_db %>%
    mutate(found = name %in% check)

names_to_check <- files_db %>%
    filter(found == FALSE) %>%
    filter(station_id != 10) %>%
    select('name')
########################################################################
