INSERT INTO grupo(`categoria_id`,`nombre`,`clasifica_oro`,`created_at`,`updated_at`,`estado`) VALUES (2,'Triangular 1 Oro',2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Borrador');
INSERT INTO grupo(`categoria_id`,`nombre`,`clasifica_oro`,`created_at`,`updated_at`,`estado`) VALUES (2,'Triangular 2 Oro',2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Borrador');
INSERT INTO grupo(`categoria_id`,`nombre`,`clasifica_oro`,`clasifica_plata`,`created_at`,`updated_at`,`estado`) VALUES (2,'Triangular 1 Plata',0,2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Borrador');
INSERT INTO grupo(`categoria_id`,`nombre`,`clasifica_oro`,`clasifica_plata`,`created_at`,`updated_at`,`estado`) VALUES (2,'Triangular 2 Plata',0,2,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'Borrador');
#Partidos Triangular 1 Oro
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,57,13);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (57,5,6,1,2,'Partido 1 Triangular 1 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,58,13);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (58,7,5,2,1,'Partido 2 Triangular 1 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,59,13);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (59,6,7,2,2,'Partido 3 Triangular 1 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
#Partidos Triangular 2 Oro
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,60,14);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (60,6,7,1,1,'Partido 1 Triangular 2 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,61,14);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (61,5,6,2,1,'Partido 2 Triangular 2 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,62,14);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (62,7,5,1,2,'Partido 3 Triangular 2 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
#Partido 3y4 Oro
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,63);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (63,13,14,2,2,'Partido 3y4 Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
#Partido Final Oro
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,64);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (64,13,14,1,1,'Partido Final Oro', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

#Partidos Triangular 1 Plata
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,65,15);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (65,5,6,3,4,'Partido 1 Triangular 1 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,66,15);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (66,7,5,4,3,'Partido 2 Triangular 1 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,67,15);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (67,6,7,4,4,'Partido 3 Triangular 1 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
#Partidos Triangular 2 Plata
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,68,16);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (68,7,6,3,3,'Partido 1 Triangular 2 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,69,16);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (69,6,4,3,4,'Partido 2 Triangular 2 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`,`grupo_id`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,70,16);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (70,5,7,4,3,'Partido 3 Triangular 2 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

#Partido 3y4 Plata
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,71);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (71,15,16,2,2,'Partido 3y4 Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
#Partido Final Plata
INSERT INTO partido(`categoria_id`,`estado`,`tipo`,`created_at`,`updated_at`,`numero`) VALUES (2,'Borrador','Eliminatorio',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,72);
INSERT INTO `partido_config`(`partido_id`, `grupo_equipo1_id`, `grupo_equipo2_id`, `posicion_equipo1`, `posicion_equipo2`, `nombre`, `created_at`, `updated_at`) VALUES (72,15,16,1,1,'Partido Final Plata', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);