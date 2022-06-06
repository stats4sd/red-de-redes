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

met_data_table <- tbl(con, "met_data")

data <- met_data_table %>%
          filter(is.na(evapo)) %>%
          mutate(
            Hour = sql(HOUR(fecha_hora)),
            Day = sql(DAY(fecha_hora)),
            Month = sql(MONTH(fecha_hora)),
            Year = sql(YEAR(fecha_hora))
          ) %>%
          collect()

data2 <- data %>%
          rename(Station.Number = station_id,
                 Temp = temperatura_externa,
                 Tdew = punto_rocio,
                 RH = humedad_externa,
                 uz = velocidad_viento,
                 Rs = solar_rad
          ) %>%
          select(id, Station.Number, Year, Month, Day, Hour, Temp, Tdew, RH, uz, Rs)

stations <- unique(data2$station_id)

for(i in 1:length(stations)) {
  
  # update evapotranspiration calculation here...

  
  evapo_results <- data.frame(results$ET.Daily) %>%
    rownames_to_column("fecha") %>%
    rename("evapo"="results.ET.Daily")
  
  for(row in 1:nrow(evapo_results)) {
    query <- paste0("UPDATE daily_met_data SET evapo = ", evapo_results$evapo[row], " WHERE station_id = '", stations[i], "' AND fecha = '", evapo_results$fecha[row], "';")
    dbSendStatement(con, query)
  }
  
}