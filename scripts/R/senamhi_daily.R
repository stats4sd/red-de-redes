library(dotenv)
library(RMySQL)
library(tidyverse)
library(writexl)

args <- commandArgs(TRUE)

selected_station <- args[1]
selected_year <- args[2]
selected_variable <- args[3]

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
    dbname = Sys.getenv("DB_DATABASE"),
    host = Sys.getenv("DB_HOST"),
    port = as.integer(Sys.getenv("DB_PORT")),
    user = Sys.getenv("DB_USERNAME"),
    password = Sys.getenv("DB_PASSWORD")
)

stations_table <- tbl(con, "stations") %>%
                    select(1,3) %>%
                    collect()

Encoding(stations_table$label) <- "UTF-8"

selected_station_label <- as.character(
                            stations_table %>%
                            filter(id==selected_station) %>%
                            select(2)
                            )
    
daily_met_data_table <- tbl(con, "daily_met_data")

data <- daily_met_data_table %>%
        mutate(
            day = sql(DAY(fecha)),
            month = sql(MONTH(fecha)),
            year = sql(YEAR(fecha))
        ) %>%
        filter(station_id == selected_station & year == selected_year) %>%
        select(day, month, selected_variable) %>%
        collect()

senamhi_daily <- data %>%
                arrange(month) %>%
                complete(month = 1:12) %>%
                pivot_wider(names_from = "month", values_from = 3) %>%
                arrange(day) %>%
                complete(day = 1:31) %>%
                filter(!is.na(day)) %>%
                rename(
                    "DÍA" = day, ENE = `1`, FEB = `2`, MAR = `3`, ABR = `4`, MAY = `5`, JUN = `6`,
                    JUL = `7`, AGO = `8`, SEP = `9`, OCT = `10`, NOV = `11`, DIC = `12`
                )

senamhi_details <- paste(selected_station_label, selected_year, selected_variable)
write_xlsx(setNames(list(senamhi_daily), senamhi_details), "senamhi_daily.xlsx")
