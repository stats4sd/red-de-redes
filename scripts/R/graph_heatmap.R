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

stations_table <- tbl(con, "stations") %>%
                  select(1,3) %>%
                  collect()

daily_met_data_table <- tbl(con, "daily_met_data")

args <- commandArgs(TRUE)
selected_station <- args[1]
selected_start_year <- args[2]
selected_end_year <- args[3]
selected_variable <- args[4]

if(selected_variable == "fecha")  {
    data <- daily_met_data_table %>%
          mutate(month=sql(MONTH(fecha)),
                  year=sql(YEAR(fecha))) %>%
          filter(station_id==selected_station & year>=selected_start_year & year<=selected_end_year) %>%
          select(station_id, fecha) %>%
          mutate(fecha_var = fecha) %>%
          collect()
}else {

    data <- daily_met_data_table %>%
            mutate(month=sql(MONTH(fecha)),
                    year=sql(YEAR(fecha))) %>%
            filter(station_id==selected_station & year>=selected_start_year & year<=selected_end_year) %>%
            select(station_id, fecha, selected_variable) %>%
            collect()
}

data$station_id <- as.factor(data$station_id)
data$fecha <- as.Date(data$fecha)

colnames(data)[3] <- "variable"

data$variable_na <- is.na(data$variable)

png(filename="inventario.png", width = 1000, height = 500, units = "px")
data %>%
  ggplot(aes(x = fecha, y = station_id, fill = variable_na)) +
  geom_raster(alpha = 0.9) +
  scale_fill_manual(name = "", values = c("#556FBF", "darkgray"), labels = c("Presente", "Faltante")) +
  scale_x_date(date_breaks = "1 year", date_labels = "%Y") +
  labs(x = "Año", y = "Estaciones", title = selected_variable) +
  theme_minimal() +
  theme(plot.title = element_text(hjust = 0.5), axis.text.x = element_text(angle=0)) +
  theme(legend.position = "bottom")
dev.off ()
