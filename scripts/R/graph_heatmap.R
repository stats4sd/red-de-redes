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

# Chart shows month "01" of next year when end date on or after 15 Dec of this year.
# Tried to set end date as 31 December of this year, data between 31 December 00:00:00 and 23:59:59 are not included.
# In order to show data on 31 December, we need to set end data as 1 Jan of next year.
date_start <- as.Date(paste0(selected_start_year,"-1-1"))
date_end <- as.Date(paste0(strtoi(selected_end_year) + 1,"-1-1"))


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
stations_table$id <- as.character(stations_table$id)

daily_met_data_table <- tbl(con, "daily_met_data") %>%
    filter(actual_no_of_records != 0)


if(selected_aggregation%in%c("senamhi_daily", "senamhi_monthly")){

    variable <- c("max_temperatura_interna", "min_temperatura_interna", "avg_temperatura_interna",
                "max_temperatura_externa","min_temperatura_externa", "avg_temperatura_externa",
                "max_humedad_interna", "min_humedad_interna", "avg_humedad_interna",
                "max_humedad_externa", "min_humedad_externa", "avg_humedad_externa",
                "max_presion_relativa", "min_presion_relativa", "avg_presion_relativa",
                "max_presion_absoluta", "min_presion_absoluta", "avg_presion_absoluta",
                "max_velocidad_viento", "min_velocidad_viento", "avg_velocidad_viento",
                "max_sensacion_termica", "min_sensacion_termica","avg_sensacion_termica",
                "lluvia_24_horas_total")

    label <- c("Temperatura M\U00E1xima Interna (\u00b0C)", "Temperatura M\U00EDnima Interna (\u00b0C)", "Temperatura Media Interna (\u00b0C)",
             "Temperatura M\U00E1xima Externa (\u00b0C)", "Temperatura M\U00EDnima Interna (\u00b0C)", "Temperatura Media Interna (\u00b0C)",
             "Humedad M\U00E1xima Interna %", "Humedad M\U00EDnima Interna %", "Humedad Media Interna %",
             "Humedad M\U00E1xima Externa %", "Humedad M\U00EDnima Externa %", "Humedad Media Externa %",
             "Presi\U00F3n Relativa M\U00E1xima (hPa)", "Presi\U00F3n Relativa M\U00EDnima (hPa)", "Presi\U00F3n Relativa Media (hPa)",
             "Presi\U00F3n Absoluta M\U00E1xima (hPa)", "Presi\U00F3n Absoluta M\U00EDnima (hPa)", "Presi\U00F3n Absoluta Media (hPa)",
             "Velocidad Viento M\U00E1xima (m/s)", "Velocidad Viento M\U00EDnima (m/s)", "Velocidad Viento Media (m/s)",
             "Sensaci\U00F3n T\U00E9rmica M\U00E1xima (\u00b0C)", "Sensaci\U00F3n T\U00E9rmica M\U00EDnima (\u00b0C)", "Sensaci\U00F3n T\U00E9rmica Media (\u00b0C)",
             "Precipitaci\U00F3n Diaria (mm)")

    selected_variable_label <- data.frame(variable,label) %>%
                                filter(variable==selected_variable) %>%
                                select(2)

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

}

png(filename="inventario.png", width = 1000, height = 500, units = "px")

if(selected_aggregation=="senamhi_daily"){

    data %>%
        ggplot(aes(x = fecha, y = station, fill = variable_na)) +
        geom_raster(alpha = 0.9) +
        scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
        scale_x_date(date_breaks = "1 month", date_labels = "%m", limits = c(date_start,date_end)) +
        labs(x = "Mes", y = "Estaciones", title = paste("Inventario de Datos \nPar\U00E1metro Meteorol\U00F3gico:", selected_variable_label, "\nA\U00f1o:", selected_start_year)) +
        theme_minimal() +
        theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
        theme(legend.position = "bottom")

} else if (selected_aggregation=="senamhi_monthly" && selected_start_year==selected_end_year){

    data %>%
        ggplot(aes(x = fecha, y = station, fill = variable_na)) +
        geom_raster(alpha = 0.9) +
        scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
        scale_x_date(date_breaks = "1 month", date_labels = "%m", limits = c(date_start,date_end)) +
        labs(x = "Mes", y = "Estaciones", title = paste("Inventario de Datos \nPar\U00E1metro Meteorol\U00F3gico:", selected_variable_label, "\nA\U00f1o:", selected_start_year)) +
        theme_minimal() +
        theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
        theme(legend.position = "bottom")

} else if (selected_aggregation=="senamhi_monthly" && selected_start_year!=selected_end_year){

    data %>%
      ggplot(aes(x = fecha, y = station, fill = variable_na)) +
      geom_raster(alpha = 0.9) +
      scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
      scale_x_date(date_breaks = "1 year", date_labels = "%Y", limits = c(date_start,date_end)) +
      labs(x = "A\U00f1o", y = "Estaciones", title = paste("Inventario de Datos \nPar\U00E1metro Meteorol\U00F3gico:", selected_variable_label)) +
      theme_minimal() +
      theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
      theme(legend.position = "bottom")

} else if (selected_aggregation%in%c("daily_data", "tendays_data", "monthly_data", "yearly_data") && selected_start_year==selected_end_year){

  data %>%
    ggplot(aes(x = fecha, y = factor(station, levels = rev(levels(factor(station)))), fill = data_na)) +
    geom_raster(alpha = 0.9) +
    scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
    scale_x_date(date_breaks = "1 month", date_labels = "%m", limits = c(date_start,date_end)) +
    labs(x = "Mes", y = "Estaciones", title = paste("Inventario de Datos\nA\U00f1o:", selected_start_year)) +
    theme_minimal() +
    theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(size = 12), axis.title = element_text(size = 12), legend.text=element_text(size = 12)) +
    theme(legend.position = "bottom")

} else {

    data %>%
        ggplot(aes(x = fecha, y = factor(station, levels = rev(levels(factor(station)))), fill = data_na)) +
        geom_raster(alpha = 0.9) +
        scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
        scale_x_date(date_breaks = "1 year", date_labels = "%Y", limits = c(date_start,date_end)) +
        labs(x = "A\U00f1o", y = "Estaciones", title = "Inventario de Datos") +
        theme_minimal() +
        theme(plot.title = element_text(hjust = 0, size = 15, margin=margin(0,0,30,0)), axis.text = element_text(angle=0)) +
        theme(legend.position = "bottom")

}

dev.off()
