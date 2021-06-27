## PHP News Parser

Задание:

```text
Спарсить (программно) первые 15 новостей с rbk.ru (блок, откуда брать новости показан на скриншоте) и вставить в базу данных (составить структуру самому) или в файл. Вывести все новости, сократив текст до 200 символов в качестве описания, со ссылкой на полную новость с кнопкой подробнее. На полной новости выводить картинку если есть в новости.
Было бы плюсом: возможность расширения функционала парсинга для добавления дополнительных новостных ресурсов.
```

Запрос на создание таблицы новостей:

```sql
CREATE TABLE `news` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NULL DEFAULT NULL,
    `image_url` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `published_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id` (`id`)
) COLLATE='utf8_general_ci';
```
