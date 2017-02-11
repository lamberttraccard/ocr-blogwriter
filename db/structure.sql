SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `episodes`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_episode`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `episodes` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `subtitle` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `comments` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `content` TEXT NOT NULL,
  `episode_id` INTEGER NOT NULL,
  `user_id` INTEGER NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` INTEGER NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(88) NOT NULL,
  `salt` VARCHAR(23) NOT NULL,
  `role` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `user_episode` (
  `user_id` INTEGER NOT NULL,
  `episode_id` INTEGER NOT NULL,
  `view` BOOLEAN NOT NULL,
  `created_at` TIMESTAMP NOT NULL
);

ALTER TABLE `comments` ADD FOREIGN KEY (`episode_id`) REFERENCES `episodes`(`id`);
ALTER TABLE `comments` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);
ALTER TABLE `user_episode` ADD FOREIGN KEY (`episode_id`) REFERENCES `episodes`(`id`);
ALTER TABLE `user_episode` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);
