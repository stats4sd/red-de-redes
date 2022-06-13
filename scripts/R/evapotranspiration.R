library(dotenv)
library(RMySQL)
library(tidyverse)
library(Evapotranspiration)
data("constants")

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD")
)

met_data_table <- tbl(con, "met_data")

data <- met_data_table %>%
          filter(is.na(evapotran)) %>%
          mutate(
            Hour = sql(HOUR(fecha_hora)),
            Day = sql(DAY(fecha_hora)),
            Month = sql(MONTH(fecha_hora)),
            Year = sql(YEAR(fecha_hora))
          ) %>%
          collect()

#format of processed data - to be updated
processed_data <- data %>%
                    rename(Station.Number = station_id,
                           Temp = temperatura_externa,
                           Tdew = punto_rocio,
                           RH = humedad_externa,
                           uz = velocidad_viento,
                           Rs = solar_rad
                           ) %>%
                    select(Station.Number, Year, Month, Day, Hour, Temp, Tdew, RH, uz, Rs)

#constants for each station - to be updated
constants_1 <- constants
constants_2 <- constants
constants_3 <- constants
constants_4 <- constants
constants_5 <- constants
constants_6 <- constants
constants_7 <- constants 
constants_8 <- constants
constants_9 <- constants
constants_10 <- constants
constants_11 <- constants
constants_12 <- constants
constants_13 <- constants
constants_14 <- constants
constants_15 <- constants
constants_16 <- constants
constants_17 <- constants
constants_18 <- constants

constants_list <- list(constants_1=constants_1,
                       constants_2=constants_2,
                       constants_3=constants_3,
                       constants_4=constants_4,
                       constants_5=constants_5,
                       constants_6=constants_6,
                       constants_7=constants_7,
                       constants_8=constants_8,
                       constants_9=constants_9,
                       constants_10=constants_10,
                       constants_11=constants_11,
                       constants_12=constants_12,
                       constants_13=constants_13,
                       constants_14=constants_14,
                       constants_15=constants_15,
                       constants_16=constants_16,
                       constants_17=constants_17,
                       constants_18=constants_18)

stations <- unique(data$station_id)

for(i in 1:length(stations)) {
  
  station_data <- as.list(processed_data %>%
                          filter(station.Number==i)
                  )
  
  x <- stations[i]
  constants <- constants_list[[x]]

  results <- ET.PenmanMonteith(station_data,
                               constants,
                               ts="daily",
                               solar="sunshine hours",
                               wind="yes",
                               crop = "short",
                               message="yes",
                               AdditionalStats="yes",
                               save.csv="no")
  
  evapo_results <- data.frame(results$ET.Daily) %>%
    rownames_to_column("fecha") %>%
    rename("evapo"="results.ET.Daily")
  
  for(row in 1:nrow(evapo_results)) {
    query <- paste0("UPDATE daily_met_data SET evapotran = ", evapo_results$evapo[row], " WHERE station_id = '", stations[i], "' AND fecha = '", evapo_results$fecha[row], "';")
    dbSendStatement(con, query)
  }
  
}