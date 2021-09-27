


INSERT INTO `meteobridge` (`fecha_hora`, `temperatura_interna`, `humedad_externa`, `id_station`) VALUES ( '[YYYY]-[MM]-[DD] [hh]:[mm]:[ss]', '[th0temp-act]', '[th0hum-act]', 1)

INSERT INTO `meteobridge` (`fecha_hora`, `temperatura_interna`,` humedad_externa`, `id_station`)
VALUES ( '[YYYY]-[MM]-[DD] [hh]:[mm]:[ss]',' [th0temp-act]', '[th0hum-act]', '[mbsystem-stationnum]')

# final queries
INSERT INTO met_data (`fecha_hora`, `hardware_id`,`intervalo`,`meteobridge_latitude`,`meteobridge_longitude`,`meteobridge_altitude`, `meteobridge`,`temperatura_externa`, `humedad_externa`, `punto_rocio`,`temperatura_interna`, `humedad_interna`, `index_heat`, `presion_relativa`, `velocidad_viento`, `wind_chill`, `lluvia_hora`, `lluvia_total`,`uv_index`, `solar_rad`, `evapotran`)
VALUES ('[YYYY]-[MM]-[DD] [hh]:[mm]:[ss]', '[mbsystem-station]',15 ,'[mbsystem-latitude]','[mbsystem-longitude]','[mbsystem-altitude]', 1, '[th0temp-act]', '[th0hum-act]', '[th0dew-act]', '[thb0temp-act]', '[thb0hum-act]', '[th0heatindex-act]', '[thb0press-act]', '[wind0wind-act]', '[wind0chill-act]', '[rain0rate-act]', '[rain0total-act]','[uv0index-act]', '[sol0rad-act]', '[sol0evo-act]')
