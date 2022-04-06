library(dotenv)
library(RMySQL)
library(tidyverse)
library(openxlsx)

args <- commandArgs(TRUE)

selected_station <- args[1]
selected_year <- args[2]
selected_variable <- args[3]

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

selected_station_label <- as.character(
                            stations_table %>%
                            filter(id==selected_station) %>%
                            select(2)
                            )

daily_met_data_table <- tbl(con, "daily_met_data")

data <- daily_met_data_table %>%
        mutate(
            day = sql(DAY(fecha)),
            month = sql(MONTH(fecha)),
            year = sql(YEAR(fecha))
        ) %>%
        filter(station_id == selected_station & year == selected_year) %>%
        select(day, month, selected_variable) %>%
        collect()

senamhi_daily <- data %>%
                arrange(month) %>%
                complete(month = 1:12) %>%
                pivot_wider(names_from = "month", values_from = 3) %>%
                arrange(day) %>%
                complete(day = 1:31) %>%
                filter(!is.na(day)) %>%
                rename(
                    "DÍA" = day, ENE = `1`, FEB = `2`, MAR = `3`, ABR = `4`, MAY = `5`, JUN = `6`,
                    JUL = `7`, AGO = `8`, SEP = `9`, OCT = `10`, NOV = `11`, DIC = `12`
                )

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

selected_variable_label <- as.character(data.frame(variable,label) %>%
                                        filter(variable==selected_variable) %>%
                                        select(2))

criterios <- c("ID de la estación", "Estación", "Agregación", "Año", "Variable")
valor <- c(selected_station, selected_station_label, "Senamhi Diario", selected_year, selected_variable_label)
metadata <- data.frame(criterios, valor)

sheets <- list("Metadatos" = metadata, "Datos" = senamhi_daily)
write.xlsx(sheets, file = "senamhi_daily.xlsx")