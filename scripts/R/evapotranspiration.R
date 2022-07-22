library(dotenv)
library(RMySQL)
library(tidyverse)
library(jsonlite)
library(Evapotranspiration)
data("defaultconstants")

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD")
)


## Get daily data to find where ET needs to be calculated

daily_data_table <- tbl(con, "daily_met_data")

daily_data <- daily_data_table %>%
                filter(is.na(evapotran)) %>%
                mutate(
                  Day = sql(DAY(fecha)),
                  Month = sql(MONTH(fecha)),
                  Year = sql(YEAR(fecha))
                ) %>%
                select(station_id, Day, Month, Year) %>%
                collect() %>%
                mutate(station_date = paste0(station_id, "-", Day, "-", Month, "-", Year))

station_dates <- daily_data$station_date


## Get the required sub daily data and process for calculation

met_data_table <- tbl(con, "met_data")

data <- met_data_table %>%
          mutate(
            Hour = sql(HOUR(fecha_hora)),
            Day = sql(DAY(fecha_hora)),
            Month = sql(MONTH(fecha_hora)),
            Year = sql(YEAR(fecha_hora)),
            station_date = paste0(station_id, "-", Day, "-", Month, "-", Year)
          ) %>%
          filter(station_date %in% station_dates) %>%
          select(station_id, Hour, Day, Month, Year, temperatura_externa,
                 punto_rocio, humedad_externa, velocidad_viento, solar_rad) %>%
          collect()

tmax <- data %>% 
          group_by(station_id, Year, Month, Day) %>%
          summarise(Tmax=max(temperatura_externa))

tmin <- data %>% 
          group_by(station_id, Year, Month, Day) %>%
          summarise(Tmin=min(temperatura_externa))

rhmax <- data %>% 
          group_by(station_id, Year, Month, Day) %>%
          summarise(RHmax=max(humedad_externa))

rhmin <- data %>% 
          group_by(station_id, Year, Month, Day) %>%
          summarise(RHmin=max(humedad_externa))

processed_data <- data %>%
                    left_join(tmax, by=c("station_id", "Year", "Month", "Day")) %>%
                    left_join(tmin, by=c("station_id", "Year", "Month", "Day")) %>%
                    left_join(rhmax, by=c("station_id", "Year", "Month", "Day")) %>%
                    left_join(rhmin, by=c("station_id", "Year", "Month", "Day")) %>%
                    rename(Station.Number = station_id,
                           Temp = temperatura_externa,
                           Tdew = punto_rocio,
                           RH = humedad_externa,
                           uz = velocidad_viento,
                           Rs = solar_rad
                    ) %>%
                    drop_na()


## Get station data

stations <- unique(processed_data$station_id)

stations_table <- tbl(con, "stations")

stations_constants <- stations_table %>%
                        filter(id %in% stations) %>%
                        select(id, latitude, altitude, constants) %>%
                        collect() %>%
                        mutate(lat_rad = latitude*pi/180) %>%
                        rename(lat = latitude, Elev = altitude, station_id = id)


## Loop through stations, calculate ET, send results to database

for(i in 1:length(stations)) {
  
  station_data <- as.list(processed_data %>%
                            filter(Station.Number==i))
  
  station_constants <- stations_constants %>%
                          filter(Station.Number==i)
  
  station_constants_list <- as.list(as.data.frame(fromJSON(stations_constants$constants)) %>%
                             add_column(lat=station_constants$lat,
                                        lat_rad=station_constants$lat_rad,
                                        Elev=station_constants$Elev) %>%
                             lapply(as.numeric))
  
  constants <- c(station_constants_list, defaultconstants)                       
  
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