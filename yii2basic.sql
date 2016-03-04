-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 05 2016 г., 02:14
-- Версия сервера: 5.6.26
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `yii2basic`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', 1457106545),
('author', '2', 1457106600),
('author', '3', 1457107116),
('author', '4', 1457125148),
('author', '5', 1457130284);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Admin', NULL, NULL, 1456937166, 1456937166),
('author', 1, 'Author', NULL, NULL, 1456937166, 1456937166),
('createComment', 2, 'Create a comment', NULL, NULL, 1456937166, 1456937166),
('createPost', 2, 'Create a post', NULL, NULL, 1456937166, 1456937166),
('createUser', 2, 'Create a user', NULL, NULL, 1456937166, 1456937166),
('deleteComment', 2, 'Delete any comment', NULL, NULL, 1456937166, 1456937166),
('deleteOwnComment', 2, 'Delete own comment', 'isAuthor', NULL, 1456937166, 1456937166),
('deleteOwnPost', 2, 'Delete own post', 'isAuthor', NULL, 1456937166, 1456937166),
('deleteOwnUser', 2, 'Delete own user info', 'isAuthorUserRules', NULL, 1456937166, 1456937166),
('deletePost', 2, 'Delete any post', NULL, NULL, 1456937166, 1456937166),
('deleteUser', 2, 'Delete any user', NULL, NULL, 1456937166, 1456937166),
('readUser', 2, 'Read a user', NULL, NULL, 1456937166, 1456937166),
('unapproved', 1, 'Unapproved', NULL, NULL, 1456937166, 1456937166),
('updateComment', 2, 'Update any comment', NULL, NULL, 1456937166, 1456937166),
('updateOwnComment', 2, 'Update own comment', 'isAuthor', NULL, 1456937166, 1456937166),
('updateOwnPost', 2, 'Update own post', 'isAuthor', NULL, 1456937166, 1456937166),
('updateOwnUser', 2, 'Update own user info', 'isAuthorUserRules', NULL, 1456937166, 1456937166),
('updatePost', 2, 'Update any post', NULL, NULL, 1456937166, 1456937166),
('updateUser', 2, 'Update any user', NULL, NULL, 1456937166, 1456937166);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'author'),
('author', 'createComment'),
('author', 'createPost'),
('admin', 'createUser'),
('admin', 'deleteComment'),
('author', 'deleteOwnComment'),
('author', 'deleteOwnPost'),
('author', 'deleteOwnUser'),
('admin', 'deletePost'),
('admin', 'deleteUser'),
('unapproved', 'readUser'),
('author', 'unapproved'),
('admin', 'updateComment'),
('author', 'updateOwnComment'),
('author', 'updateOwnPost'),
('author', 'updateOwnUser'),
('admin', 'updatePost'),
('admin', 'updateUser');

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_rule`
--

INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
('isAuthor', 'O:19:"app\\rbac\\AuthorRule":3:{s:4:"name";s:8:"isAuthor";s:9:"createdAt";i:1456937166;s:9:"updatedAt";i:1456937166;}', 1456937166, 1456937166),
('isAuthorUserRules', 'O:17:"app\\rbac\\UserRule":3:{s:4:"name";s:17:"isAuthorUserRules";s:9:"createdAt";i:1456937166;s:9:"updatedAt";i:1456937166;}', 1456937166, 1456937166);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  `publish_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `title`, `content`, `author_id`, `post_id`, `publish_date`) VALUES
(2, 'Проверяем комментарии...', 'Hola, amigo!\r\nUpdated\r\nUpdated by Admin', 2, 12, 1457008156),
(5, 'Да что вы вообще несёте?', '<p>Я никогда в жизни не поверю, что КНДР способна на такое!</p>\r\n', 4, 5, 1457078588),
(6, 'Не соглашусь с Вами', '<p>Не согласен с Вашей точки зрения. Мощь севернокорейской армии способна на многое!</p>\n\n<p><em>далее, очевидно, должна быть представлена&nbsp;развёрнутая аргументация с ссылками на источники,&nbsp;почему именно Северная Корея такая по-боевому настроенная прям</em></p>\n', 1, 5, 1457104516),
(7, 'Норм', '<p>А чё, норм парень</p>\r\n', 3, 13, 1457107988),
(8, '[КНДР ваш крым наш] расставь знаки препинания', '<p>Да-да-да, КНДР прямо-таки весь мир под страхом держит.</p>\r\n\r\n<p>Щаз!</p>\r\n\r\n<p>КНДР никогда и не владела ядерной мощью!&nbsp;Да вы хотя бы взгляните на Google Maps:</p>\r\n\r\n<p><img alt="Сравнение ночного освещения Северной и Южной Кореи на Google Maps" src="http://lurkmore.so/images/c/cb/Korean_peninsula_at_night1.jpg" style="height:474px; width:393px" /></p>\r\n\r\n<p>Ну и где вы здесь свет увидели?</p>\r\n\r\n<p><em>...ну и так далее.</em></p>\r\n\r\n<p><em>спойлер: спор был продолжен&nbsp;до полного игнора&nbsp;обеими&nbsp;сторонами всяческих аргументов и доказательной базы, посему был отредактирован администратором</em></p>\r\n', 3, 5, 1457119770);

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `anons` text,
  `content` mediumtext,
  `category_id` int(10) unsigned DEFAULT NULL,
  `author_id` int(10) NOT NULL,
  `publish_status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `publish_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `title`, `anons`, `content`, `category_id`, `author_id`, `publish_status`, `publish_date`) VALUES
(5, 'Вооруженные силы США готовы контратаковать ядерные силы КНДР и уничтожить их в случае необходимости', 'После того как лидер КНДР Ким Чен Ын заявил о том, что ядерные силы страны должны быть готовы в любой момент атаковать противника, Пентагон оставил оценку опасности северокорейского арсенала на прежнем низком уровне...', '<p>Вооруженные силы США готовы контратаковать ядерные силы КНДР и уничтожить их в случае необходимости. Такое заявление сделал чиновник американского минобороны, передает Yahoo! News.</p>\n\n<p>После того как лидер КНДР Ким Чен Ын заявил о том, что ядерные силы страны должны быть готовы в любой момент атаковать противника, Пентагон оставил оценку опасности северокорейского арсенала на прежнем низком уровне.</p>\n\n<p>&laquo;Оценка правительства США не изменилась&raquo;, &mdash; уточнил он. &laquo;Мы не видели северокорейского испытания или демонстрации ее способности к миниатюризации ядерного оружия и оснащения им межконтинентальных баллистических ракет&raquo;, &mdash; подчеркнул чиновник.</p>\n\n<p>В пятницу, 4 марта, Ким Чен Ын заявил, что КНДР пересмотрит свою военную доктрину, чтобы быть готовой наносить превентивные удары в связи с текущей ситуацией, которая является очень опасной для страны.</p>\n\n<p>2 марта Совет Безопасности ООН&nbsp;<a href="https://lenta.ru/news/2016/03/02/bye_dprk/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">одобрил</a>&nbsp;новый пакет беспрецедентно жестких санкций против Северной Кореи.</p>\n\n<p>Новые санкции в отношении КНДР стали ответом на испытание Пхеньяном в январе 2016 года водородной бомбы и пуск ракеты дальнего радиуса действия в феврале. США&nbsp;<a href="https://lenta.ru/news/2016/02/18/obama1/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">ввели</a>&nbsp;новые односторонние санкции против Северной Кореи 18 февраля. Вскоре после голосования Совбеза они обнародовали дополнительные ограничения: под санкции Минфина США попал министр обороны КНДР Пак Ен Сик, а под санкции Госдепартамента &mdash; министерство атомной энергетики КНДР, военная академия, несколько организаций и два физических лица.</p>\n\n<p>В результате запуска ракеты, произведенного 7 февраля, на орбиту был выведен спутник Kwangmyongsong-4 (&laquo;Яркая звезда-4&raquo;), предназначенный для слежения за Землей. В мировом сообществе этот запуск восприняли как нарушение резолюций Совбеза ООН, запрещающих Северной Корее испытания и пуски баллистических ракет.</p>\n', 1, 1, 'publish', 1457071820),
(8, 'fff', 'fff', '<p>fff</p>\r\n', 1, 3, 'draft', 1457109400),
(12, 'Банк России резко снизил курсы доллара и евро', 'Официальный курс доллара, установленный Банком России на 2 марта, составляет 74,05 рубля. Он снижен на 1,85 рубля. Евро при этом подешевел на 2,57 рубля, до 80,53 рубля. Такая информация размещена на сайте регулятора.', '<p>Банк России резко снизил курсы доллара и&nbsp;евро</p>\r\n\r\n<p><img alt="" class="g-picture" src="http://icdn.lenta.ru/images/2016/03/01/12/20160301125911692/pic_acea8e752306980f2c7b2a3bc8c933cc.jpg" style="-webkit-font-smoothing:antialiased; border:0px; box-sizing:border-box; display:inline; float:left; height:240px; max-width:420px; vertical-align:middle; width:360px" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Фото: Антон Белицкий / &laquo;Коммерсантъ&raquo;</p>\r\n\r\n<p>Официальный курс доллара, установленный Банком России на 2 марта, составляет 74,05 рубля. Он снижен на 1,85 рубля. Евро при этом подешевел на 2,57 рубля, до 80,53 рубля. Такая информация размещена на&nbsp;<a href="http://www.cbr.ru/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">сайте</a>&nbsp;регулятора.</p>\r\n\r\n<p>На Московской бирже американская валюта стоит 73,79 рубля (минус 1,38&nbsp;рубля по сравнению с закрытием торговой сессии 29&nbsp;февраля), единая европейская&nbsp;&mdash; 80,15&nbsp;рубля (упала на 1,41 рубля). Нефть марки Brent торгуется около отметки 36,63 доллара за баррель. Все данные &mdash; на 12:55 мск.</p>\r\n\r\n<p>Согласно&nbsp;<a href="http://lenta.ru/news/2016/03/01/vciom/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">последнему</a>&nbsp;социологическому опросу ВЦИОМ, большая часть россиян (57 процентов) предпочитают хранить сбережения в рублях. Тем не менее участники исследования ждут, что доллар в ближайшие три месяца подорожает до 84 рублей, а через год будет стоить 83 рубля.</p>\r\n\r\n<p>Ранее, 20 января, глава Банка России Эльвира Набиуллина заявляла, что текущий курс рубля близок к фундаментально обоснованному. Первый вице-премьер правительства Игорь Шувалов, в свою очередь,&nbsp;<a href="http://lenta.ru/news/2016/01/20/keep_calm/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">посоветовал</a>&nbsp;россиянам обращать меньше внимания на изменение валютных курсов.</p>\r\n', 1, 2, 'publish', 1457105105),
(13, 'Ученые установили личность британского художника Бэнкси', 'Ученые из колледжа Королевы Марии Лондонского университета смогли установить личность граффитиста Бэнкси. Им оказался выпускник публичной школы в Бристоле Робин Каннингем. Исследование авторов опубликовано в Journal of Spatial Science, кратко о нем сообщает издание The Independent.', '<p><img alt="" class="g-picture" src="http://icdn.lenta.ru/images/2016/03/04/09/20160304091737090/pic_aad215f0536a12bd153391ce1dadbd25.jpg" style="-webkit-font-smoothing:antialiased; border:0px; box-sizing:border-box; display:inline; float:left; height:280px; max-width:420px; vertical-align:middle; width:420px" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Фото: Carlo Allegri / Reuters</p>\r\n\r\n<p>Ученые из колледжа Королевы Марии Лондонского университета смогли установить личность граффитиста Бэнкси. Им оказался выпускник публичной школы в Бристоле Робин Каннингем. Исследование авторов опубликовано в Journal of Spatial Science, кратко о нем сообщает издание The Independent.</p>\r\n\r\n<p>В своей работе ученые использовали географическое профилирование, позволяющее вычислить личность человека по местам, которые он наиболее часто посещает. Специалисты проанализировали около 140 мест на улицах Лондона и Бристоля, где можно было заметить присутствие работ художника.</p>\r\n\r\n<p>МАТЕРИАЛЫ ПО ТЕМЕ</p>\r\n\r\n<p><strong>13:24</strong>&nbsp;29 июня 2010</p>\r\n\r\n<p><a class="b-badge js-dh" href="http://lenta.ru/articles/2010/06/29/apostles/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 51, 51); transition: color 0.2s; text-decoration: none; position: relative; display: inline-block;"><img alt="&quot;Апостолы Петр и Павел&quot; кисти Эль Греко" class="g-picture" src="http://icdn.lenta.ru/images/0000/0265/000002657032/tabloid_1358860977.jpg" style="-webkit-font-smoothing:antialiased; border-style:initial; border-width:0px; box-sizing:border-box; display:inline; float:left; height:64px; margin-right:15px; max-width:96px; vertical-align:middle; width:96px" /></a></p>\r\n\r\n<p><a class="js-dh" href="http://lenta.ru/articles/2010/06/29/apostles/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(21, 21, 21); transition: color 0.2s; text-decoration: none;">12 прославленных мужчин</a></p>\r\n\r\n<p>Археологи еще немного прояснили историю культа апостолов</p>\r\n\r\n<p>Выяснилось, что данные места совпадают с барами, футбольными полями и домашними адресами, где часто присутствовал Каннингем. То, что он является загадочным художником, начали подозревать еще в 2008 году. В частности, считалось, что настоящее имя художника &mdash; Роберт или Робин.</p>\r\n\r\n<p>Многими экспертами Бэнкси считается наиболее ярким представителем мирового стрит-арта и политического активизма. Работы художника представляют собой графику, подписанную псевдонимом Бэнкси. Стоимость некоторых работ художника оценивается в миллион долларов.</p>\r\n', 1, 3, 'publish', 1457078524),
(14, 'Nicolay Post', 'Nicolay was here!', '<p>Nicolay, one of the technicians of <strong>General Baker</strong>, was here!</p>\r\n', 1, 3, 'publish', 1457109597),
(15, 'fff', 'fff', '<p>fff</p>\r\n', NULL, 3, 'draft', 1457109492),
(16, '444', '444', '<p>444</p>\r\n', NULL, 1, 'draft', 1457123932),
(17, '333', '333', '<p>333</p>\r\n', NULL, 1, 'draft', 1457123979);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `status` int(4) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password_hash`, `password_reset_token`, `email`, `auth_key`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '$2y$13$yXcv1cmbHqBIi01bEgzTju77xCqswQx4FGEvZ24d27KVnIjJZXG26', NULL, 'admin@yii.local', 'PwrSFFEtXUm7A4jFScN2HQvkJrAKKwh1', 10, 1457106539, 1457106539),
(2, 'Mikhail Lavrentiev', '$2y$13$aE6qEDc2Xo/0Cyxq5Ag7VenvcmmP5BvgS8niERW8wMjsBVDasOmYG', NULL, 'mikhail_lavrentiev@backmeup.cz', 'S4zm8pKokYT9f6vssQQQ47LYtgldoOKx', 10, 1457106600, 1457106600),
(3, 'Nicolay', '$2y$13$ppaVQWWIO6zHprieWYl7cuk6hAfWfSW2pgEifdY0HlOvCgu.Mkx3y', NULL, 'nicolay-was-here@nicolay.info', '8Z7R2AsH6wtppA2J6S_ns3BYGzeVL0mF', 10, 1457107116, 1457124504),
(4, 'NAT Korea User', '$2y$13$q8ShM7LKj2tukxZf4gcEYu0xa0eqlzNG6BTR0HV9NCM66A/JsiM9q', NULL, 'van.shi@nkorea.kp', '2zgiyxsqf9EI1HIKMXfNi7mLyOi219Lt', 10, 1457125148, 1457125251),
(5, 'Must Be Deleted', '$2y$13$pu38ENjM7v15bVkVaXGgkuL2307lY2YL1ojoA48lVrwHCOZxwwQeu', NULL, 'mustbedeleted@yii.local', 'iMJeigQ1LsQ2btGV-Q3GyaanjtEt7L9P', 10, 1457130284, 1457130284);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
