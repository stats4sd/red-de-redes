library(dotenv)
library(RMySQL)
library(tidyverse)
library(openxlsx)

args <- commandArgs(TRUE)

selected_station <- args[1]
selected_start_year <- args[2]
selected_end_year <- args[3]
selected_variable <- args[4]

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
    dbname = Sys.getenv("DB_DATABASE"),
    host = Sys.getenv("DB_HOST"),
    port = as.integer(Sys.getenv("DB_PORT")),
    user = Sys.getenv("DB_USERNAME"),
    password = Sys.getenv("DB_PASSWORD")
)

dbSendQuery(con, "set character set 'utf8mb4'")

stations_table <- tbl(con, "stations") %>%
    select(1,3) %>%
    collect()

Encoding(stations_table$label) <- "UTF-8"

selected_station_label <- as.character(
    stations_table %>%
        filter(id==selected_station) %>%
        select(2)
)

monthly_met_data_table <- tbl(con, "monthly_met_data")

data <- monthly_met_data_table %>%
    mutate(
        year = substring(as.character(year_and_month), 1, 4),
        month = substring(as.character(year_and_month), 5, 6)
    ) %>%
    filter(station_id == selected_station) %>%
    select(month, year, selected_variable) %>%
    collect()

data <- data %>%
        mutate(
            year = as.numeric(year),
            month = as.numeric(month)
        ) %>%
        filter(year >= selected_start_year & year <= selected_end_year)

senamhi_monthly <- data %>%
    arrange(month) %>%
    complete(month = 1:12) %>%
    pivot_wider(names_from = "month", values_from = 3) %>%
    complete(year = selected_start_year:selected_end_year) %>%
    filter(!is.na(year)) %>%
    rename(
      "AÑO" = year, ENE = `1`, FEB = `2`, MAR = `3`, ABR = `4`, MAY = `5`, JUN = `6`,
      JUL = `7`, AGO = `8`, SEP = `9`, OCT = `10`, NOV = `11`, DIC = `12`
    )
    
senamhi_details <- paste(selected_station_label, selected_variable)
    
write.xlsx(senamhi_monthly, "senamhi_monthly.xlsx", sheetname = senamhi_details)