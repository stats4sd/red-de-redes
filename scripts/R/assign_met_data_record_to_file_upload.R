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
files <- tbl(con, 'files') %>% collect()

files_to_check <- files %>%
    filter(is_success == 1 & new_records_count > 0) %>%
    select('name')

# for(file in files_to_check$name) {
 file <- "1611584532_En_Nov2020_Iคacamaya_03.txt"
file_from_files_table <- files %>% filter(name == file)
station_id <- file_from_files_table$station_id
expected_file_count <- file_from_files_table$new_records_count

    if(grep('.txt', file)) {
        ## all txt files have 2 column headers
        file_data <- read.csv(paste0('../../storage/app/public/rawfiles/', file), header = FALSE, sep = "\t")
        file_data <- two_headers_are_not_better_than_one(file_data)
        file_data <- file_data[-1,]

        file_data <- file_data %>%
            # find the timestamp
            mutate(date_time = paste(Date, Time, sep = " ")) %>%
            mutate(timestamp = as.POSIXct(date_time,format = "%d/%m/%y %H:%M"))  %>%
            mutate(station_id = station_id)

        timestamps  <- format(file_data$timestamp)

        file_met_data <- met_data %>% filter(fecha_hora %in% timestamps & station_id == file_station_id) %>% collect()

    }
}
#}
