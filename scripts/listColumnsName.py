

#list of columns name for Davis station to drop if the all the value are null
list_columns_davis_to_drop = [
                              'hi_temp', 'low_temp','wind_run', 'hi_speed', 'hi_dir',
                              'wind_chill', 'index_heat', 'index_thw', 'index_thsw',
                              'presion_relativa', 'rain', 'lluvia_hora', 'solar_rad',

                              'solar_energy', 'radsolar_max', 'heat_days_d', 'cool_days_d', 'in_dew', 'in_heat',
                              'in_emc', 'in_air_density', 'evapotran', 'wind_samp', 'wind_tx', 'iss_recept','intervalo'

                              ]
#list of columns name for Chinas station to drop if the all the value are null
list_columns_chinas_to_drop = [
                                'temperatura_interna', 'humedad_interna', 'temperatura_externa',
                                'humedad_externa', 'presion_relativa', 'presion_absoluta',
                                'velocidad_viento', 'sensacion_termica', 'rafaga',
                                'direccion_del_viento', 'punto_rocio', 'lluvia_hora',
                                'lluvia_24_horas', 'lluvia_semana', 'lluvia_mes', 'lluvia_total'
                               ]

#dictionary of columns name for converting the original csv file columns name into database columns name
columns_db = {
                'Date': 'fecha_hora', 'Hi_Temp':'hi_temp', 'Low_Temp': 'low_temp', 'Wind_Cod':'wind_cod',
                'Wind_Run':'wind_run', 'Hi_Speed':'hi_speed', 'Hi_Dir':'hi_dir',
                'Wind_Cod_Dom':'wind_cod_dom','Wind_Chill':'wind_chill', 'Heat_Index':'index_heat',
                'THW_Index':'index_thw', 'THSW_Index':'index_thsw', 'Bar':'presion_relativa',
                'Rain':'rain', 'Rain_Rate':'lluvia_hora', 'Solar_Rad.':'solar_rad',
                'Solar_Energy':'solar_energy', 'Hi_Solar_Rad.':'radsolar_max',
                'Hi Solar_Rad.':'radsolar_max', 'UV_Index':'uv_index', 'UV_Dose':'uv_dose',
                'Hi_UV':'uv_max', 'Heat_D-D':'heat_days_d', 'Cool_D-D':'cool_days_d', 'In_Dew':'in_dew',
                'In_Heat':'in_heat', 'In_EMC':'in_emc', 'In_Air_Density':'in_air_density',
                'ET':'evapotran', 'Soil_1_Moist.':'soil_1_moist', 'Soil_2_Moist.':'soil_2_moist',
                'Soil 3_Moist.':'soil_3_moist', 'Leaf_Wet_1':'leaf_wet1', 'Wind_Samp':'wind_samp', 'Wind_Tx':'wind_tx',
                'ISS_Recept':'iss_recept', 'Arc._Int.':'intervalo', 'In_Temp':'temperatura_interna',
                'In_Hum':'humedad_interna', 'Wind_Dir':'direccion_del_viento', 'Wind_Speed':'velocidad_viento',
                'Dew_Pt.':'punto_rocio', 'Out_Hum':'humedad_externa', 'Temp_Out':'temperatura_externa',
                'station_id':'station_id','observation_id':'observation_id', 'Intervalo':'intervalo', 'Fecha/Hora':'fecha_hora',
                'Temperatura Interna(°C)':'temperatura_interna', 'Humedad Interna(%)':'humedad_interna',
                'Temperatura Externa(°C)':'temperatura_externa', 'Humedad Externa(%)':'humedad_externa',
                'Presión Relativa(hpa)':'presion_relativa', 'Presión Absoluta(hpa)':'presion_absoluta',
                'Velocidad del viento(m/s)':'velocidad_viento', 'Sensación Térmica(°C)':'sensacion_termica',
                'Ráfaga(m/s)':'rafaga', 'Dirección del viento':'direccion_del_viento', 'Punto de Rocío(°C)':'punto_rocio',
                'Lluvia hora(mm)':'lluvia_hora', 'Lluvia 24 horas(mm)':'lluvia_24_horas', 'Lluvia semana(mm)':'lluvia_semana',
                'Lluvia mes(mm)':'lluvia_mes', 'Lluvia Total(mm)':'lluvia_total', 'Leaf_Temp_1':'leaf_temp_1',
                'Leaf_Temp_2':'leaf_temp_2', 'Soil_Temp_1':'soil_temp_1', 'Soil_Temp_2':'soil_temp_2'
              }

#all columns name into database for database for uploading the data
list_columns_name = [
                      'fecha_hora', 'hi_temp', 'low_temp', 'wind_cod', 'wind_run', 'hi_speed', 'hi_dir',
                      'wind_cod_dom', 'wind_chill', 'index_heat', 'index_thw', 'index_thsw', 'presion_relativa',
                      'rain', 'solar_rad', 'solar_energy', 'radsolar_max', 'uv_index', 'uv_dose', 'uv_max',
                      'heat_days_d', 'cool_days_d', 'in_dew', 'in_heat', 'in_emc', 'in_air_density', 'evapotran',
                      'soil_1_moist', 'soil_2_moist', 'soil_3_moist','soil_4_moist', 'leaf_wet1', 'leaf_wet2', 'leaf_wet3', 'leaf_wet4',
                      'wind_samp', 'wind_tx', 'iss_recept', 'intervalo',
                      'temperatura_interna', 'humedad_interna', 'punto_rocio', 'humedad_externa', 'temperatura_externa',
                      'station_id', 'observation_id','presion_absoluta', 'velocidad_viento', 'sensacion_termica', 'rafaga', 'direccion_del_viento',
                      'lluvia_hora', 'lluvia_24_horas', 'lluvia_semana', 'lluvia_mes', 'lluvia_total', 'leaf_temp_1',
                      'leaf_temp_2', 'soil_temp_1', 'soil_temp_2', 'soil_temp_3', 'soil_temp_4'
                    ]



#dictionary of columns name for converting the original text file columns name into database columns name
list_columns_davis_text = {
                            'Date':'fecha_hora', 'Time':'time',
                            'Temp_Out':'temperatura_externa', 'Hi_Temp':'hi_temp', 'Low_Temp':'low_temp',
                            'Out_Hum':'humedad_externa', 'Dew_Pt.':'punto_rocio', 'Wind_Speed':'velocidad_viento',
                            'Wind_Dir':'direccion_del_viento', 'Wind_Run':'wind_run', 'Hi_Speed':'hi_speed',
                            'Hi_Dir':'hi_dir', 'Wind_Chill':'wind_chill', 'Heat_Index':'index_heat',
                            'THW_Index':'index_thw', 'THSW_Index':'index_thsw', 'Bar':'presion_relativa',
                            'Rain':'rain', 'Rain_Rate':'lluvia_hora', 'Solar_Rad.':'solar_rad',
                            'Solar_Energy':'solar_energy', 'Hi_Solar_Rad.':'radsolar_max', 'UV_Index':'uv_index',
                            'UV_Dose':'uv_dose', 'Hi_UV':'uv_max', 'Heat_D-D':'heat_days_d', 'Cool_D-D':'cool_days_d',
                            'In_Temp':'temperatura_interna', 'In_Hum':'humedad_interna', 'In_Dew':'in_dew',
                            'In_Heat':'in_heat', 'In_EMC':'in_emc', 'In_Air_Density':'in_air_density',
                            'ET':'evapotran', 'Soil_1_Moist.':'soil_1_moist',
                            'Soil_2_Moist.':'soil_2_moist','Soil_3_Moist.':'soil_3_moist', 'Soil_4_Moist.':'soil_4_moist' ,
                            'Soil_Temp_1':'soil_temp_1','Soil_Temp_2':'soil_temp_2', 'Soil_Temp_3':'soil_temp_3', 'Soil_Temp_4':'soil_temp_4',
                            'Leaf_Wet_1':'leaf_wet1', 'Leaf_Wet_2':'leaf_wet2','Leaf_Wet_3':'leaf_wet3','Leaf_Wet_4':'leaf_wet4','Wind_Samp':'wind_samp',
                            'Wind_Tx':'wind_tx', 'ISS_Recept':'iss_recept', 'Leaf_Temp_1':'leaf_temp_1',
                            'Leaf_Temp_2':'leaf_temp_2','Leaf_Temp_3':'leaf_temp_3','Leaf_Temp_4':'leaf_temp_4', 'Arc._Int.':'intervalo'
                          }

#dictionary of columns name for converting the chinas csv file columns name into database columns name
list_columns_chinas_csv = {
                            'station_id':'station_id', 'Intervalo':'intervalo',
                            'Fecha/Hora':'fecha_hora',
                            'Temperatura Interna(°C)':'temperatura_interna',
                            'Humedad Interna(%)':'humedad_interna',
                            'Temperatura Externa(°C)':'temperatura_externa',
                            'Humedad Externa(%)':'humedad_externa',
                            'Presión Relativa(hpa)':'presion_relativa',
                            'Presión Absoluta(hpa)':'presion_absoluta',
                            'Velocidad del viento(m/s)':'velocidad_viento',
                            'Velocidad del viento(km/h)':'velocidad_viento',
                            'Sensación Térmica(°C)':'sensacion_termica',
                            'Ráfaga(m/s)':'rafaga',
                            'Ráfaga(km/h)':'rafaga',
                            'Dirección del viento':'direccion_del_viento',
                            'Punto de Rocío(°C)':'punto_rocio',
                            'Lluvia hora(mm)':'lluvia_hora',
                            'Lluvia 24 horas(mm)':'lluvia_24_horas',
                            'Lluvia semana(mm)':'lluvia_semana',
                            'Lluvia mes(mm)':'lluvia_mes',
                            'Lluvia Total(mm)':'lluvia_total'
                          }

