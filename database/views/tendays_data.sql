
SELECT

    min(LEFT(`data`.`fecha_hora`, 10)) AS `min_fecha`,
    max(LEFT(`data`.`fecha_hora`,10)) AS `max_fecha`,
    `data`.`id_station` AS `id_station`,
    `stations`.`label` AS `station`,
    
    MAX(`data`.`temperatura_interna`) AS `max_temperatura_interna`,
    MIN(`data`.`temperatura_interna`) AS `min_temperatura_interna`,
    AVG(`data`.`temperatura_interna`) AS `avg_temperatura_interna`,

    MAX(`data`.`humedad_interna`) AS `max_humedad_interna`,
    MIN(`data`.`humedad_interna`) AS `min_humedad_interna`,
    AVG(`data`.`humedad_interna`) AS `avg_humedad_interna`,

    MAX(`data`.`temperatura_externa`) AS `max_temperatura_externa`,
    MIN(`data`.`temperatura_externa`) AS `min_temperatura_externa`,
    AVG(`data`.`temperatura_externa`) AS `avg_temperatura_externa`,

    MAX(`data`.`humedad_externa`) AS `max_humedad_externa`,
    MIN(`data`.`humedad_externa`) AS `min_humedad_externa`,
    AVG(`data`.`humedad_externa`) AS `avg_humedad_externa`,

    MAX(`data`.`presion_relativa`) AS `max_presion_relativa`,
    MIN(`data`.`presion_relativa`) AS `min_presion_relativa`,
    AVG(`data`.`presion_relativa`) AS `avg_presion_relativa`,

    MAX(`data`.`presion_absoluta`) AS `max_presion_absoluta`,
    MIN(`data`.`presion_absoluta`) AS `min_presion_absoluta`,
    AVG(`data`.`presion_absoluta`) AS `avg_presion_absoluta`,

    MAX(`data`.`velocidad_viento`) AS `max_velocidad_viento`,
    MIN(`data`.`velocidad_viento`) AS `min_velocidad_viento`,
    AVG(`data`.`velocidad_viento`) AS `avg_velocidad_viento`,

    MAX(`data`.`sensacion_termica`) AS `max_sensacion_termica`,
    MIN(`data`.`sensacion_termica`) AS `min_sensacion_termica`,
    AVG(`data`.`sensacion_termica`) AS `avg_sensacion_termica` ,

    MAX(`data`.`lluvia_24_horas`) AS `lluvia_24_horas_total`,

    ceil((to_days(`data`.`fecha_hora`) / 10))  as `group_by`

    FROM `data`

    LEFT JOIN `stations` ON `stations`.`id` = `data`.`id_station` GROUP BY `group_by`, `data`.`id_station`;