library(dotenv)
library(RMySQL)
library(tidyverse)
library(Evapotranspiration)

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD")
)

daily_met_data_table <- tbl(con, "daily_met_data")

data <- daily_met_data_table %>%
          filter(is.na(evapo)) %>%
          collect()

stations <- unique(data$station_id)

for(i in 1:length(stations)) {
  
  # update evapotranspiration calculation here
  data_updated <- data %>% mutate(evapo=1)
  
  for(row in 1:nrow(data_updated)) {
    query <- paste0("UPDATE daily_met_data SET evapo = ", data_updated$evapo[row], " WHERE station_id = '", stations[i], "' AND fecha = '", data_updated$fecha[row], "';")
    dbSendStatement(con, query)
  }
  
}