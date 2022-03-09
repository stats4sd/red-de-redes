library(dotenv)
library(RMySQL)
library(tidyverse)

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
    dbname = Sys.getenv("DB_DATABASE"),
    host = Sys.getenv("DB_HOST"),
    port = as.integer(Sys.getenv("DB_PORT")),
    user = Sys.getenv("DB_USERNAME"),
    password = Sys.getenv("DB_PASSWORD")
)

daily_met_data_table <- tbl(con, "daily_met_data")

args <- commandArgs(TRUE)

selected_station <- args[1]
selected_year <- args[2]
selected_variable <- args[3]

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

write.csv(senamhi_daily, file = "senamhi_daily.csv", row.names = FALSE)