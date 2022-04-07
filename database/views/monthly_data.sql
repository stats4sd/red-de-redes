SELECT

    LEFT(`met_data`.`fecha_hora`,7) as `fecha`,
    `met_data`.`station_id` AS `station_id`,
    `stations`.`label` AS `station`,


    MAX(`met_data`.`temperatura_interna`) AS `max_temperatura_interna`,
    MIN(`met_data`.`temperatura_interna`) AS `min_temperatura_interna`,
    AVG(`met_data`.`temperatura_interna`) AS `avg_temperatura_interna`,

    MAX(`met_data`.`humedad_interna`) AS `max_humedad_interna`,
    MIN(`met_data`.`humedad_interna`) AS `min_humedad_interna`,
    AVG(`met_data`.`humedad_interna`) AS `avg_humedad_interna`,

    MAX(`met_data`.`temperatura_externa`) AS `max_temperatura_externa`,
    MIN(`met_data`.`temperatura_externa`) AS `min_temperatura_externa`,
    AVG(`met_data`.`temperatura_externa`) AS `avg_temperatura_externa`,

    MAX(`met_data`.`humedad_externa`) AS `max_humedad_externa`,
    MIN(`met_data`.`humedad_externa`) AS `min_humedad_externa`,
    AVG(`met_data`.`humedad_externa`) AS `avg_humedad_externa`,

    MAX(`met_data`.`presion_relativa`) AS `max_presion_relativa`,
    MIN(`met_data`.`presion_relativa`) AS `min_presion_relativa`,
    AVG(`met_data`.`presion_relativa`) AS `avg_presion_relativa`,

    MAX(`met_data`.`presion_absoluta`) AS `max_presion_absoluta`,
    MIN(`met_data`.`presion_absoluta`) AS `min_presion_absoluta`,
    AVG(`met_data`.`presion_absoluta`) AS `avg_presion_absoluta`,

    MAX(`met_data`.`velocidad_viento`) AS `max_velocidad_viento`,
    MIN(`met_data`.`velocidad_viento`) AS `min_velocidad_viento`,
    AVG(`met_data`.`velocidad_viento`) AS `avg_velocidad_viento`,

    MAX(`met_data`.`sensacion_termica`) AS `max_sensacion_termica`,
    MIN(`met_data`.`sensacion_termica`) AS `min_sensacion_termica`,
    AVG(`met_data`.`sensacion_termica`) AS `avg_sensacion_termica` ,

    MAX(`met_data`.`lluvia_24_horas`) AS `lluvia_24_horas_total`

FROM `met_data`
LEFT JOIN `stations` ON `stations`.`id` = `met_data`.`station_id`
WHERE met_data.station_id is not null
GROUP BY `fecha`,`met_data`.`station_id`;
