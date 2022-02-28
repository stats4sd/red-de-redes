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

monthly_met_data_table <- tbl(con, "monthly_met_data")

selected_station <- 1
selected_start_year <- "2016"
selected_end_year <- "2020"
selected_variable <- "max_temperatura_interna"

data <- monthly_met_data_table %>%
          mutate(month=sql(MONTH(fecha)),
                 year=sql(YEAR(fecha))) %>%
          filter(station_id==selected_station & year>=selected_start_year & year<=selected_end_year) %>%
          select(month, year, selected_variable) %>%
          collect()

senamhi_monthly <- data %>%
                    arrange(month) %>%
                    complete(month=1:12) %>%
                    pivot_wider(names_from = "month", values_from = 3) %>%
                    rename("AÑO"=year, ENE=`1`, FEB=`2`, MAR=`3`, ABR=`4`, MAY=`5`, JUN=`6`,
                           JUL=`7`, AGO=`8`, SEP=`9`, OCT=`10`, NOV=`11`, DIC=`12`)

write.csv(senamhi_monthly, file="senamhi_monthly.csv", row.names = FALSE)