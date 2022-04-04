library(dotenv)
library(RMySQL)
library(tidyverse)

args <- commandArgs(TRUE)
selected_station <- args[1]
selected_start_year <- args[2]
selected_end_year <- args[3]
selected_variable_type <- args[4]

date_start <- as.Date(paste0(selected_start_year,"-1-1"))
date_end <- as.Date(paste0(selected_end_year,"-12-31"))

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

unit <- ifelse(selected_variable_type=="temperatura_interna", "\u00b0C",
          ifelse(selected_variable_type=="temperatura_externa", "\u00b0C",
          ifelse(selected_variable_type=="humedad_interna", "%",
          ifelse(selected_variable_type=="humedad_externa", "%",
          ifelse(selected_variable_type=="presion_relativa", "hPa",
          ifelse(selected_variable_type=="presion_absoluta", "hPa",
          ifelse(selected_variable_type=="velocidad_viento", "m/s",
          ifelse(selected_variable_type=="sensacion_termica", "\u00b0C",
          ifelse(selected_variable_type=="lluvia_24_horas_total","mm",NA)))))))))

title_text <- ifelse(selected_variable_type=="temperatura_interna", "Temperatura Interna (\u00b0C)",
               ifelse(selected_variable_type=="temperatura_externa", "Temperatura Externa (\u00b0C)",
               ifelse(selected_variable_type=="humedad_interna", "Humedad Interna %",
               ifelse(selected_variable_type=="humedad_externa", "Humedad Externa %",
               ifelse(selected_variable_type=="presion_relativa", "Presi\U00F3n Relativa (hPa)",
               ifelse(selected_variable_type=="presion_absoluta", "Presi\U00F3n Relativa (hPa)",
               ifelse(selected_variable_type=="velocidad_viento", "Velocidad Viento (m/s)",
               ifelse(selected_variable_type=="sensacion_termica", "Sensaci\U00F3n T\U00E9rmica (\u00b0C)",
               ifelse(selected_variable_type=="lluvia_24_horas_total","Precipitaci\U00F3n Diaria (mm)",NA)))))))))

dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD"),
)
dbSendQuery(con, "set character set 'utf8mb4'")

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

title_text <- paste("Estaci\U00F3n:", selected_station_label, "\nPar\U00E1metro Meteorol\U00F3gico:", title_text)

daily_met_data_table <- tbl(con, "daily_met_data")

data <- daily_met_data_table %>%
          mutate(year=sql(YEAR(fecha))) %>%
          filter(station_id==selected_station & year>=selected_start_year & year<=selected_end_year) %>%
          select(station_id, fecha, all_of(selected_variables)) %>%
          collect()

data <- data %>%
          mutate(fecha=as.Date(data$fecha)) %>%
          complete(fecha = seq.Date(date_start, date_end, by="day"))
          

if(selected_variable_type!="lluvia_24_horas_total"){
  
  colnames(data)[3] <- "variable_max"
  colnames(data)[4] <- "variable_min"
  colnames(data)[5] <- "variable_avg"

}

png (filename = "grafico_series_temporales.png", width = 1000 , height = 400 , units = "px")

if(selected_variable_type=="lluvia_24_horas_total"  && selected_start_year==selected_end_year){
  
  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = lluvia_24_horas_total) , color ="#00AFBB", size = 1.2) +
    labs(x ="Mes", y = unit, title = paste(title_text, "\nA\U00f1o:", selected_start_year)) +
    theme_minimal() +
    scale_x_date(date_breaks = "1 month", minor_breaks = "1 month", date_labels = "%m", expand = c(0,1)) +
    theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12))

} else if (selected_variable_type=="lluvia_24_horas_total" && selected_start_year!=selected_end_year) {
  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = lluvia_24_horas_total) , color ="#00AFBB", size = 1.2) +
    labs(x ="A\U00f1o", y = unit, title = title_text) +
    theme_minimal() +
    scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
    theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 11), axis.title = element_text(size = 12))
  
} else if (selected_variable_type!="lluvia_24_horas_total" && selected_start_year==selected_end_year) {

  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = variable_max, color="m\U00E1xima"), size = 1.2) +
    geom_line(aes(y = variable_avg, color="media"), size = 1.2) +
    geom_line(aes(y = variable_min, color="m\U00EDnima"), size = 1.2) +
    scale_color_manual(name = "", values = c("m\U00E1xima" = "#FA8275", "media"="#00AFBB", "m\U00EDnima"="#00C32F"))+
    labs(x ="Mes", y = unit, title = paste(title_text,"\nA\U00f1o:", selected_start_year)) +
    theme_minimal() +
    scale_x_date(date_breaks = "1 month", minor_breaks = "1 month", date_labels = "%m", expand = c(0,0)) +
    theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
    theme(legend.position = "right")
  
} else {
  
  data %>%
    ggplot(aes(x = fecha)) +
    geom_line(aes(y = variable_max, color="m\U00E1xima"), size = 1.2) +
    geom_line(aes(y = variable_avg, color="media"), size = 1.2) +
    geom_line(aes(y = variable_min, color="m\U00EDnima"), size = 1.2) +
    scale_color_manual(name = "", values = c("m\U00E1xima" = "#FA8275", "media"="#00AFBB", "m\U00EDnima"="#00C32F"))+
    labs(x ="A\U00f1o", y = unit, title = title_text) +
    theme_minimal() +
    scale_x_date(date_breaks = "1 year", date_labels = "%Y", expand = c(0,1)) +
    theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 11), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
    theme(legend.position = "right")
  
}

dev.off()