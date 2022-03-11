library(dotenv)
library(RMySQL)
library(tidyverse)

args <- commandArgs(TRUE)
selected_aggregation <- args[1]
selected_station <- args[2]
selected_station <- as.vector(strsplit(selected_station, ",")[[1]])
selected_start_year <- args[3]
selected_end_year <- ifelse(selected_aggregation=="senamhi_daily", selected_start_year, args[4])
selected_variable <- args[5]

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
stations_table$id <- as.character(stations_table$id)

daily_met_data_table <- tbl(con, "daily_met_data")

if(selected_aggregation%in%c("senamhi_daily", "senamhi_monthly")){
    
    data <- daily_met_data_table %>%
              mutate(year=sql(YEAR(fecha))) %>%
              filter(station_id%in%selected_station) %>%
              filter(year>=selected_start_year & year<=selected_end_year) %>%
              select(station_id, fecha, all_of(selected_variable)) %>%
              collect()
    
    data <- data %>%
              mutate(station_id=as.character(data$station_id),
                     fecha=as.Date(data$fecha)) %>%
              left_join(stations_table, by=c("station_id"="id")) %>%
              rename(station=label)
    
    colnames(data)[3] <- "variable"
    data$variable_na <- is.na(data$variable)


    if(selected_aggregation=="senamhi_daily"){
    
        png(filename="inventario.png", width = 1000, height = 500, units = "px")
        data %>%
          ggplot(aes(x = fecha, y = station, fill = variable_na)) +
          geom_raster(alpha = 0.9) +
          scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
          scale_x_date(date_breaks = "1 month", date_labels = "%m") +
          labs(x = "Mes", y = "Estaciones", title = paste0("Inventario de datos: ", selected_variable)) +
          theme_minimal() +
          theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle=0)) +
          theme(legend.position = "bottom")
        dev.off ()
    
    } else {
    
        png(filename="inventario.png", width = 1000, height = 500, units = "px")
        data %>%
          ggplot(aes(x = fecha, y = station, fill = variable_na)) +
          geom_raster(alpha = 0.9) +
          scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
          scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
          labs(x = "Año", y = "Estaciones", title = paste0("Inventario de datos: ", selected_variable)) +
          theme_minimal() +
          theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle=0)) +
          theme(legend.position = "bottom")
        dev.off ()
    }

} else {
  
    data <- daily_met_data_table %>%
              mutate(year=sql(YEAR(fecha))) %>%
              filter(station_id%in%selected_station) %>%
              filter(year>=selected_start_year & year<=selected_end_year) %>%
              select(station_id, fecha) %>%
              collect()
    
    data <- data %>%
              mutate(station_id=as.character(data$station_id),
                     fecha=as.Date(data$fecha)) %>%
              left_join(stations_table, by=c("station_id"="id")) %>%
              rename(station=label)
    
    data$data_na <- is.na(data$station)
    
    png(filename="inventario.png", width = 1000, height = 500, units = "px")
    data %>%
      ggplot(aes(x = fecha, y = station, fill = data_na)) +
      geom_raster(alpha = 0.9) +
      scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
      scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
      labs(x = "Año", y = "Estaciones", title = "Inventario de datos") +
      theme_minimal() +
      theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle=0)) +
      theme(legend.position = "bottom")
    dev.off ()
    
}
