BEGIN;
CREATE TABLE `php_film` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(128) NOT NULL UNIQUE,
    `poster` varchar(200) NOT NULL,
    `producers` varchar(1024) NOT NULL,
    `directors` varchar(1024) NOT NULL,
    `actors` varchar(1024) NOT NULL
)
;
CREATE TABLE `php_cinema` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `name` varchar(128) NOT NULL,
    `address` varchar(1024) NOT NULL,
    `phone` varchar(32) NOT NULL UNIQUE
)
;
CREATE TABLE `php_scene` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `date` date NOT NULL,
    `time_fr` integer NOT NULL,
    `time_to` integer NOT NULL,
    `cinema_id` integer NOT NULL,
    `film_id` integer NOT NULL,
    UNIQUE (`cinema_id`, `film_id`, `date`, `time_fr`)
)
;
ALTER TABLE `php_scene` ADD CONSTRAINT `film_id_refs_id_66a68a47` FOREIGN KEY (`film_id`) REFERENCES `php_film` (`id`);
ALTER TABLE `php_scene` ADD CONSTRAINT `cinema_id_refs_id_90b0a099` FOREIGN KEY (`cinema_id`) REFERENCES `php_cinema` (`id`);
CREATE TABLE `php_user_attends` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_id` integer NOT NULL,
    `scene_id` integer NOT NULL,
    UNIQUE (`user_id`, `scene_id`)
)
;
ALTER TABLE `php_user_attends` ADD CONSTRAINT `scene_id_refs_id_63a2a306` FOREIGN KEY (`scene_id`) REFERENCES `php_scene` (`id`);
CREATE TABLE `php_user` (
    `id` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `username` varchar(64) NOT NULL UNIQUE,
    `password` varchar(64) NOT NULL
)
;
ALTER TABLE `php_user_attends` ADD CONSTRAINT `user_id_refs_id_d0a8a68a` FOREIGN KEY (`user_id`) REFERENCES `php_user` (`id`);
CREATE INDEX `php_scene_719fc6bf` ON `php_scene` (`cinema_id`);
CREATE INDEX `php_scene_e85f03de` ON `php_scene` (`film_id`);

COMMIT;
