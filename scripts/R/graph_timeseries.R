library(dotenv)
library(RMySQL)
library(tidyverse)

args <- commandArgs(TRUE)
selected_station <- args[1]
selected_start_year <- args[2]
selected_end_year <- args[3]
selected_variable_type <- args[4]

selected_variables <- ifelse(selected_variable_type=="temperatura_interna", "max_temperatura_interna,min_temperatura_interna,avg_temperatura_interna",
                             ifelse(selected_variable_type=="temperatura_externa", "max_temperatura_externa,min_temperatura_externa,avg_temperatura_externa",
                                    ifelse(selected_variable_type=="humedad_interna", "max_humedad_interna,min_humedad_interna,avg_humedad_interna",
                                           ifelse(selected_variable_type=="humedad_externa", "max_humedad_externa,min_humedad_externa,avg_humedad_externa",
                                                  ifelse(selected_variable_type=="presion_relativa", "max_presion_relativa,min_presion_relativa,avg_presion_relativa",
                                                         ifelse(selected_variable_type=="presion_absoluta", "max_presion_absoluta,min_presion_absoluta,avg_presion_absoluta",
                                                                ifelse(selected_variable_type=="velocidad_viento", "max_velocidad_viento,min_velocidad_viento,avg_velocidad_viento",
                                                                       ifelse(selected_variable_type=="sensacion_termica", "max_sensacion_termica,min_sensacion_termica,avg_sensacion_termica",
                                                                              ifelse(selected_variable_type=="lluvia_24_horas_total","lluvia_24_horas_total",NA)))))))))

selected_variables <- as.vector(strsplit(selected_variables, ",")[[1]])

unit <- ifelse(selected_variable_type=="temperatura_interna", "°C",
               ifelse(selected_variable_type=="temperatura_externa", "°C",
                      ifelse(selected_variable_type=="humedad_interna", "%",
                             ifelse(selected_variable_type=="humedad_externa", "%",
                                    ifelse(selected_variable_type=="presion_relativa", "hPa",
                                           ifelse(selected_variable_type=="presion_absoluta", "hPa",
                                                  ifelse(selected_variable_type=="velocidad_viento", "m/s",
                                                         ifelse(selected_variable_type=="sensacion_termica", "°C",
                                                                ifelse(selected_variable_type=="lluvia_24_horas_total","mm",NA)))))))))

title_text <- ifelse(selected_variable_type=="temperatura_interna", "Temperatura Interna (°C)",
               ifelse(selected_variable_type=="temperatura_externa", "Temperatura Externa (°C)",
                      ifelse(selected_variable_type=="humedad_interna", "Humedad Interna %",
                             ifelse(selected_variable_type=="humedad_externa", "Humedad Externa %",
                                    ifelse(selected_variable_type=="presion_relativa", "Presion Relativa (hPa)",
                                           ifelse(selected_variable_type=="presion_absoluta", "Presion Relativa (hPa)",
                                                  ifelse(selected_variable_type=="velocidad_viento", "Velocidad Viento (m/s)",
                                                         ifelse(selected_variable_type=="sensacion_termica", "Sensacion Termica (°C)",
                                                                ifelse(selected_variable_type=="lluvia_24_horas_total","Precipitación Diaria (mm)",NA)))))))))

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD"),
)

stations_table <- tbl(con, "stations") %>%
                    select(1,3) %>%
                    collect()

Encoding(stations_table$label) <- "UTF-8"
stations_table$id <- as.character(stations_table$id)

selected_station_label <- as.character(
                            stations_table %>%
                              filter(id==selected_station) %>%
                              select(2)
                            )

title_text <- paste0(selected_station_label, ": ", title_text)

daily_met_data_table <- tbl(con, "daily_met_data")

data <- daily_met_data_table %>%
          mutate(year=sql(YEAR(fecha))) %>%
          filter(station_id==selected_station & year>=selected_start_year & year<=selected_end_year) %>%
          select(station_id, fecha, all_of(selected_variables)) %>%
          collect()

data <- data %>%
          mutate(fecha=as.Date(data$fecha))

if(selected_variable_type=="lluvia_24_horas_total"){
  
  png (filename = "grafico_series_temporales", width = 1000 , height = 200 , units = "px")
  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = lluvia_24_horas_total) , color ="#FA8275", size = 0.3) +
    labs(x ="Año", y = unit, title = title_text) +
    theme_minimal() +
    theme(legend.position = "bottom") +
    scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
    theme(plot.title = element_text(hjust = 0.5), plot.subtitle = element_text(hjust = 0.5), axis.text.x = element_text(angle = 0)) +
    theme(legend.position = "bottom")
  dev.off()
  
  
} else {
  
  colnames(data)[3] <- "variable_max"
  colnames(data)[4] <- "variable_min"
  colnames(data)[5] <- "variable_avg"
  
  png (filename = "grafico_series_temporales.png ", width = 1000 , height = 200 , units = "px")
  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = variable_max) , color ="#FA8275", size = 0.3) +
    geom_line(aes(y = variable_min) , color ="#69A4FF", size = 0.3) +
    geom_line(aes(y = variable_avg) , color ="#00C32F", size = 0.3) +
    labs(x ="Año", y = unit, title = title_text) +
    theme_minimal() +
    theme(legend.position = "bottom") +
    scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
    theme(plot.title = element_text(hjust = 0.5), plot.subtitle = element_text(hjust = 0.5), axis.text.x = element_text(angle = 0)) +
    theme(legend.position = "bottom")
  dev.off()
  
}