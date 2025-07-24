CREATE DATABASE `test179`;

CREATE TABLE `post` (
	`id` BIGINT UNSIGNED NOT null AUTO_INCREMENT COMMENT 'ID'
	, `userId` BIGINT UNSIGNED NOT null COMMENT 'ID пользователя'
	, `title` VARCHAR(300) NOT null
	, `body` TEXT NOT null
	, `sessionId` CHAR(40) CHARACTER SET latin1 NOT null COMMENT 'ID сессии'

	, PRIMARY KEY (`id`)
	, INDEX(`sessionId`)
	, INDEX(`userId`)
) COMMENT='Пост';

CREATE TABLE `comment` (
	`id` BIGINT UNSIGNED NOT null AUTO_INCREMENT COMMENT 'ID'
	, `postId` BIGINT UNSIGNED NOT null COMMENT 'ID поста'
	, `name` VARCHAR(300) NOT null COMMENT 'имя'
	, `email` VARCHAR(300) CHARACTER SET latin1 NOT null COMMENT 'e-mail'
	, `body` TEXT NOT NULL
	, `sessionId` CHAR(40) CHARACTER SET latin1 NOT null COMMENT 'ID сессии'

	, PRIMARY KEY (`id`)
	, INDEX(`postId`)
	, INDEX(`sessionId`)
	, FULLTEXT(`body`)
	, CONSTRAINT `fk_comment_post` FOREIGN KEY (`postId`) REFERENCES `post`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) COMMENT 'комментарий';