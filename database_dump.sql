-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 15 2016 г., 21:54
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
-- Структура таблицы `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `posts_count` int(10) NOT NULL DEFAULT '0',
  `author_id` int(10) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_blog_blogAuthor_assignment` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `blog`
--

INSERT INTO `blog` (`id`, `name`, `title`, `is_private`, `posts_count`, `author_id`, `created_at`) VALUES
(1, 'News Blog', 'News from the world and, obviously, from Russia', 0, 12, 1, 1457794514),
(13, 'Nicolay Blog', 'This is Nicolay private zone!', 0, 2, 3, 1457842180),
(14, 'Mixa Blog', 'Blog about travelling, cars and so on.', 0, 2, 2, 1457854413),
(17, 'JASON TERRITORY', 'THIS IS JASON STATHAM TERRITORY!', 0, 2, 33, 1457878953);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `blog_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`, `title`, `blog_id`) VALUES
(1, 'North Korea', 'News from North Korea', 1),
(2, 'Russian Banks', 'Russian Banks News', 1),
(3, 'Test', 'Testing some features of this blog', 1),
(4, 'Don''t Know What Is This', 'Category about everything', 13),
(5, 'Crocodiles', 'All info about Crocodiles, rararaaaar', 13);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(10) NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `publish_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post_comment_assignment` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `title`, `content`, `author_id`, `post_id`, `publish_date`) VALUES
(1, 'Проверяем комментарии...', 'Hola, amigo!\r\nUpdated\r\nUpdated by Admin', 2, 12, 1457008156),
(2, 'Да что вы вообще несёте?', '<p>Я никогда в жизни не поверю, что КНДР способна на такое!</p>\r\n', 4, 5, 1457078588),
(3, 'Не соглашусь с Вами', '<p>Не согласен с Вашей точки зрения. Мощь севернокорейской армии способна на многое!</p>\n\n<p><em>далее, очевидно, должна быть представлена&nbsp;развёрнутая аргументация с ссылками на источники,&nbsp;почему именно Северная Корея такая по-боевому настроенная прям</em></p>\n', 1, 5, 1457104516),
(4, 'Норм', '<p>А чё, норм парень</p>\r\n', 3, 13, 1457107988),
(5, '[КНДР ваш крым наш] расставь знаки препинания', '<p>Да-да-да, КНДР прямо-таки весь мир под страхом держит.</p><p>Щаз!</p><p>КНДР никогда и не владела ядерной мощью!&nbsp;Да вы хотя бы взгляните на Google Maps:</p><p><img alt="Сравнение ночного освещения Северной и Южной Кореи на Google Maps" src="http://lurkmore.so/images/c/cb/Korean_peninsula_at_night1.jpg" style="height:474px; width:393px" /></p><p>Ну и где вы, товарищ, здесь свет увидели?</p><p><em>...ну и так далее.</em></p><p><em>спойлер: спор был продолжен&nbsp;до полного игнора&nbsp;обеими&nbsp;сторонами всяческих аргументов и доказательной базы, посему был отредактирован администратором</em></p>', 3, 5, 1457119778),
(6, 'Who are you?', '<p>Nicolay? Who are you?</p>\r\n', 1, 14, 1457349993),
(7, 'hhh', 'hhh\r\nhhh', 33, 14, 1457599296),
(11, 'TIMEZONE TEST', '<p>NOW IS [11:51:10&nbsp;+01:00 GMT]</p>\r\n\r\n<p>Updated by Nicolay in&nbsp;~5 minutes</p>\r\n', 3, 92, 1457779867),
(12, 'Comment Leaving', '<p>Can I leave a comment for you...?</p>\r\n', 2, 92, 1457869421),
(13, 'Why your blog is so private, man', '<p>...</p>\r\n', 2, 14, 1457870692),
(14, 'JASON COMMENT!', '<p>JASON COMMENT!</p>\r\n', 33, 104, 1457879781),
(15, 'JASON COMMENT!', '<p>JASON COMMENT!</p>\r\n', 33, 104, 1457879787);

-- --------------------------------------------------------

--
-- Структура таблицы `earth_countries`
--

CREATE TABLE IF NOT EXISTS `earth_countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_ru` varchar(100) DEFAULT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `code` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `code` (`code`),
  KEY `name_en` (`name_en`),
  KEY `name_ru` (`name_ru`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=248 ;

--
-- Дамп данных таблицы `earth_countries`
--

INSERT INTO `earth_countries` (`id`, `name_ru`, `name_en`, `code`) VALUES
(1, 'Австралия', 'Australia', 'AU'),
(2, 'Малайзия', 'Malaysia', 'MY'),
(3, 'Республика Корея', 'Korea', 'KR'),
(4, 'Китай', 'China', 'CN'),
(5, 'Япония', 'Japan', 'JP'),
(6, 'Индия', 'India', 'IN'),
(7, 'Тайвань', 'Taiwan', 'TW'),
(8, 'Гонконг', 'Hong Kong', 'HK'),
(9, 'Таиланд', 'Thailand', 'TH'),
(11, 'Вьетнам', 'Vietnam', 'VN'),
(12, 'Франция', 'France', 'FR'),
(13, 'Италия', 'Italy', 'IT'),
(14, 'Объединенные Арабские Эмираты', 'United Arab Emirates', 'AE'),
(15, 'Швеция', 'Sweden', 'SE'),
(16, 'Казахстан', 'Kazakhstan', 'KZ'),
(17, 'Португалия', 'Portugal', 'PT'),
(18, 'Греция', 'Greece', 'GR'),
(19, 'Саудовская Аравия', 'Saudi Arabia', 'SA'),
(20, 'Российская Федерация', 'Russian Federation', 'RU'),
(21, 'Великобритания', 'United Kingdom', 'GB'),
(22, 'Дания', 'Denmark', 'DK'),
(23, 'США', 'United States', 'US'),
(24, 'Канада', 'Canada', 'CA'),
(25, 'Мексика', 'Mexico', 'MX'),
(26, 'Бермуды', 'Bermuda', 'BM'),
(27, 'Пуэрто Рико', 'Puerto Rico', 'PR'),
(28, 'Виргинские Острова США', 'Virgin Islands, U.S.', 'VI'),
(29, 'Германия', 'Germany', 'DE'),
(30, 'Иран', 'Iran', 'IR'),
(31, 'Боливия', 'Bolivia', 'BO'),
(32, 'Монтсеррат', 'Montserrat', 'MS'),
(33, 'Нидерланды', 'Netherlands', 'NL'),
(34, 'Израиль', 'Israel', 'IL'),
(35, 'Испания', 'Spain', 'ES'),
(36, 'Багамские острова', 'Bahamas', 'BS'),
(37, 'Сент-Винсент и Гренадины', 'Saint Vincent and the Grenadines', 'VC'),
(38, 'Чили', 'Chile', 'CL'),
(39, 'Новая Каледония', 'New Caledonia', 'NC'),
(40, 'Аргентина', 'Argentina', 'AR'),
(41, 'Доминика', 'Dominica', 'DM'),
(42, 'Сингапур', 'Singapore', 'SG'),
(43, 'Непал', 'Nepal', 'NP'),
(44, 'Филиппины', 'Philippines', 'PH'),
(45, 'Индонезия', 'Indonesia', 'ID'),
(46, 'Пакистан', 'Pakistan', 'PK'),
(47, 'Токелау', 'Tokelau', 'TK'),
(48, 'Новая Зеландия', 'New Zealand', 'NZ'),
(49, 'Камбоджа', 'Cambodia', 'KH'),
(50, 'Макау', 'Macau', 'MO'),
(51, 'Папуа Новая Гвинея', 'Papua New Guinea', 'PG'),
(52, 'Мальдивские острова', 'Maldives', 'MV'),
(53, 'Афганистан', 'Afghanistan', 'AF'),
(54, 'Бангладеш', 'Bangladesh', 'BD'),
(55, 'Ирландия', 'Ireland', 'IE'),
(56, 'Бельгия', 'Belgium', 'BE'),
(57, 'Белиз', 'Belize', 'BZ'),
(58, 'Бразилия', 'Brazil', 'BR'),
(59, 'Швейцария', 'Switzerland', 'CH'),
(60, 'Южно-Африканская Республика', 'South Africa', 'ZA'),
(61, 'Египет', 'Egypt', 'EG'),
(62, 'Нигерия', 'Nigeria', 'NG'),
(63, 'Танзания', 'Tanzania', 'TZ'),
(64, 'Замбия', 'Zambia', 'ZM'),
(65, 'Сенегал', 'Senegal', 'SN'),
(66, 'Гана', 'Ghana', 'GH'),
(67, 'Судан', 'Sudan', 'SD'),
(68, 'Камерун', 'Cameroon', 'CM'),
(69, 'Малави', 'Malawi', 'MW'),
(70, 'Ангола', 'Angola', 'AO'),
(71, 'Кения', 'Kenya', 'KE'),
(72, 'Габон', 'Gabon', 'GA'),
(73, 'Мали', 'Mali', 'ML'),
(74, 'Бенин', 'Benin', 'BJ'),
(75, 'Мадагаскар', 'Madagascar', 'MG'),
(76, 'Чад', 'Chad', 'TD'),
(77, 'Ботсвана', 'Botswana', 'BW'),
(78, 'Ливия', 'Libya', 'LY'),
(79, 'Кабо-Верде', 'Cape Verde', 'CV'),
(80, 'Руанда', 'Rwanda', 'RW'),
(81, 'Мозамбик', 'Mozambique', 'MZ'),
(82, 'Гамбия', 'Gambia', 'GM'),
(83, 'Лесото', 'Lesotho', 'LS'),
(84, 'Маврикий', 'Mauritius', 'MU'),
(85, 'Конго', 'Congo', 'CG'),
(86, 'Уганда', 'Uganda', 'UG'),
(87, 'Буркина Фасо', 'Burkina Faso', 'BF'),
(88, 'Сьерра-Леоне', 'Sierra Leone', 'SL'),
(89, 'Сомали', 'Somalia', 'SO'),
(90, 'Зимбабве', 'Zimbabwe', 'ZW'),
(91, 'Демократическая Республика Конго', 'Democratic Republic Of The Congo', 'CD'),
(92, 'Нигер', 'Niger', 'NE'),
(93, 'Центрально-Африканская Республика', 'Central African Republic', 'CF'),
(94, 'Свазиленд', 'Swaziland', 'SZ'),
(95, 'Того', 'Togo', 'TG'),
(96, 'Гвинея', 'Guinea', 'GN'),
(97, 'Либерия', 'Liberia', 'LR'),
(98, 'Сейшеллы', 'Seychelles', 'SC'),
(99, 'Марокко', 'Morocco', 'MA'),
(100, 'Алжир', 'Algeria', 'DZ'),
(101, 'Мавритания', 'Mauritania', 'MR'),
(102, 'Намибия', 'Namibia', 'NA'),
(103, 'Джибути', 'Djibouti', 'DJ'),
(105, 'Коморские острова', 'Comoros', 'KM'),
(106, 'Реюньон', 'Reunion', 'RE'),
(107, 'Экваториальная Гвинея', 'Equatorial Guinea', 'GQ'),
(108, 'Тунис', 'Tunisia', 'TN'),
(109, 'Турция', 'Turkey', 'TR'),
(110, 'Польша', 'Poland', 'PL'),
(111, 'Латвия', 'Latvia', 'LV'),
(112, 'Украина', 'Ukraine', 'UA'),
(113, 'Беларусь', 'Belarus', 'BY'),
(114, 'Чехия', 'Czech Republic', 'CZ'),
(115, 'Палестина', 'Palestinian Territory', 'PS'),
(116, 'Исландия', 'Iceland', 'IS'),
(117, 'Кипр', 'Cyprus', 'CY'),
(118, 'Венгрия', 'Hungary', 'HU'),
(119, 'Словакия', 'Slovakia', 'SK'),
(120, 'Сербия', 'Serbia', 'RS'),
(121, 'Болгария', 'Bulgaria', 'BG'),
(122, 'Оман', 'Oman', 'OM'),
(123, 'Румыния', 'Romania', 'RO'),
(124, 'Грузия', 'Georgia', 'GE'),
(125, 'Норвегия', 'Norway', 'NO'),
(126, 'Армения', 'Armenia', 'AM'),
(127, 'Австрия', 'Austria', 'AT'),
(128, 'Албания', 'Albania', 'AL'),
(129, 'Словения', 'Slovenia', 'SI'),
(130, 'Панама', 'Panama', 'PA'),
(131, 'Бруней', 'Brunei Darussalam', 'BN'),
(132, 'Шри-Ланка', 'Sri Lanka', 'LK'),
(133, 'Черногория', 'Montenegro', 'ME'),
(134, 'Европейский Союз', 'Europe', 'EU'),
(135, 'Таджикистан', 'Tajikistan', 'TJ'),
(136, 'Ирак', 'Iraq', 'IQ'),
(137, 'Ливан', 'Lebanon', 'LB'),
(138, 'Молдова', 'Moldova', 'MD'),
(139, 'Финляндия', 'Finland', 'FI'),
(140, 'Эстония', 'Estonia', 'EE'),
(141, 'Босния и Герцеговина', 'Bosnia and Herzegovina', 'BA'),
(142, 'Кувейт', 'Kuwait', 'KW'),
(143, 'Аландские острова', 'Aland Islands', 'AX'),
(144, 'Литва', 'Lithuania', 'LT'),
(145, 'Люксембург', 'Luxembourg', 'LU'),
(146, 'Антигуа и Барбуда', 'Antigua and Barbuda', 'AG'),
(147, 'Македония', 'Macedonia', 'MK'),
(148, 'Сан-Марино', 'San Marino', 'SM'),
(149, 'Мальта', 'Malta', 'MT'),
(150, 'Фолклендские острова', 'Falkland Islands', 'FK'),
(151, 'Бахрейн', 'Bahrain', 'BH'),
(152, 'Узбекистан', 'Uzbekistan', 'UZ'),
(153, 'Азербайджан', 'Azerbaijan', 'AZ'),
(154, 'Монако', 'Monaco', 'MC'),
(155, 'Гаити', 'Haiti', 'HT'),
(156, 'Гуам', 'Guam', 'GU'),
(157, 'Ямайка', 'Jamaica', 'JM'),
(158, 'Внешние малые острова США', 'United States Minor Outlying Islands', 'UM'),
(159, 'Микронезия', 'Micronesia', 'FM'),
(160, 'Эквадор', 'Ecuador', 'EC'),
(161, 'Перу', 'Peru', 'PE'),
(162, 'Каймановы острова', 'Cayman Islands', 'KY'),
(163, 'Колумбия', 'Colombia', 'CO'),
(164, 'Гондурас', 'Honduras', 'HN'),
(165, 'Антильские острова', 'Netherlands Antilles', 'AN'),
(166, 'Йемен', 'Yemen', 'YE'),
(167, 'Британские Виргинские острова', 'Virgin Islands, British', 'VG'),
(168, 'Сирия', 'Syria', 'SY'),
(169, 'Никарагуа', 'Nicaragua', 'NI'),
(170, 'Доминиканская республика', 'Dominican Republic', 'DO'),
(171, 'Гренада', 'Grenada', 'GD'),
(172, 'Гватемала', 'Guatemala', 'GT'),
(173, 'Коста-Рика', 'Costa Rica', 'CR'),
(174, 'Сальвадор', 'El Salvador', 'SV'),
(175, 'Венесуэла', 'Venezuela', 'VE'),
(176, 'Барбадос', 'Barbados', 'BB'),
(177, 'Тринидад и Тобаго', 'Trinidad and Tobago', 'TT'),
(178, 'Буве', 'Bouvet Island', 'BV'),
(179, 'Маршалловы острова', 'Marshall Islands', 'MH'),
(180, 'Острова Кука', 'Cook Islands', 'CK'),
(181, 'Гибралтар', 'Gibraltar', 'GI'),
(182, 'Парагвай', 'Paraguay', 'PY'),
(247, 'Южный Судан', 'South Sudan', 'SS'),
(184, 'Самоа', 'Samoa', 'WS'),
(185, 'Сент Китс и Невис', 'Saint Kitts and Nevis', 'KN'),
(186, 'Фиджи', 'Fiji', 'FJ'),
(187, 'Уругвай', 'Uruguay', 'UY'),
(188, 'Северные Марианские острова', 'Northern Mariana Islands', 'MP'),
(189, 'Палау', 'Palau', 'PW'),
(190, 'Катар', 'Qatar', 'QA'),
(191, 'Иордания', 'Jordan', 'JO'),
(192, 'Американское Самоа', 'American Samoa', 'AS'),
(193, 'Туркс и Кейкос', 'Turks and Caicos Islands', 'TC'),
(194, 'Святая Люсия', 'Saint Lucia', 'LC'),
(195, 'Монголия', 'Mongolia', 'MN'),
(196, 'Ватикан', 'Holy See', 'VA'),
(197, 'Арулько', 'Aruba', 'AW'),
(198, 'Гайана', 'Guyana', 'GY'),
(199, 'Суринам', 'Suriname', 'SR'),
(200, 'Остров Мэн', 'Isle of Man', 'IM'),
(201, 'Вануату', 'Vanuatu', 'VU'),
(202, 'Хорватия', 'Croatia', 'HR'),
(203, 'Ангуилья', 'Anguilla', 'AI'),
(204, 'Сен-Пьер и Микелон', 'Saint Pierre and Miquelon', 'PM'),
(205, 'Гваделупа', 'Guadeloupe', 'GP'),
(206, 'Сен-Мартен', 'Saint Martin', 'MF'),
(207, 'Гернси', 'Guernsey', 'GG'),
(208, 'Бурунди', 'Burundi', 'BI'),
(209, 'Туркменистан', 'Turkmenistan', 'TM'),
(210, 'Кыргызстан', 'Kyrgyzstan', 'KG'),
(211, 'Мьянма', 'Myanmar', 'MM'),
(212, 'Бутан', 'Bhutan', 'BT'),
(213, 'Лихтенштейн', 'Liechtenstein', 'LI'),
(214, 'Фарерские острова', 'Faroe Islands', 'FO'),
(215, 'Эфиопия', 'Ethiopia', 'ET'),
(216, 'Мартиника', 'Martinique', 'MQ'),
(217, 'Джерси', 'Jersey', 'JE'),
(218, 'Андорра', 'Andorra', 'AD'),
(219, 'Антарктида', 'Antarctica', 'AQ'),
(220, 'Британская территория в Индийском океане', 'British Indian Ocean Territory', 'IO'),
(221, 'Гренландия', 'Greenland', 'GL'),
(222, 'Гвинея-Бисау', 'Guinea-Bissau', 'GW'),
(223, 'Эритрея', 'Eritrea', 'ER'),
(224, 'Уоллис и Футуна', 'Wallis and Futuna', 'WF'),
(225, 'Французская Полинезия', 'French Polynesia', 'PF'),
(226, 'Куба', 'Cuba', 'CU'),
(227, 'Тонга', 'Tonga', 'TO'),
(228, 'Восточный Тимор', 'Timor-Leste', 'TL'),
(229, 'Сан-Томе и Принсипи', 'Sao Tome and Principe', 'ST'),
(230, 'Французская Гвинея', 'French Guiana', 'GF'),
(231, 'Соломоновы острова', 'Solomon Islands', 'SB'),
(232, 'Тувалу', 'Tuvalu', 'TV'),
(233, 'Кирибати', 'Kiribati', 'KI'),
(234, 'Ниуэ', 'Niue', 'NU'),
(235, 'Норфолк', 'Norfolk Island', 'NF'),
(236, 'Науру', 'Nauru', 'NR'),
(237, 'Майотта', 'Mayotte', 'YT'),
(238, 'Питкэрн', 'Pitcairn Islands', 'PN'),
(239, 'Кот-д''Ивуар', 'Cote D''Ivoire', 'CI'),
(240, 'Лаос', 'Lao', 'LA'),
(241, 'Корейская Народно-Демократическая Республика', 'Democratic People''s Republic of Korea', 'KP'),
(242, 'Свальбард и Ян-Майен', 'Svalbard and Jan Mayen', 'SJ'),
(243, 'Остров Святой Елены', 'Saint Helena', 'SH'),
(244, 'Кокосовые острова', 'Cocos (Keeling) Islands', 'CC'),
(245, 'Западная Сахара', 'Western Sahara', 'EH');

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `anons` text,
  `content` mediumtext,
  `categories` varchar(256) NOT NULL,
  `blog_id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  `comments_count` int(10) NOT NULL DEFAULT '0',
  `publish_status` enum('draft','publish') NOT NULL DEFAULT 'draft',
  `publish_date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_blog_post_assignment` (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `title`, `anons`, `content`, `categories`, `blog_id`, `author_id`, `comments_count`, `publish_status`, `publish_date`) VALUES
(5, 'Вооруженные силы США готовы контратаковать ядерные силы КНДР и уничтожить их в случае необходимости', 'После того как лидер КНДР Ким Чен Ын заявил о том, что ядерные силы страны должны быть готовы в любой момент атаковать противника, Пентагон оставил оценку опасности северокорейского арсенала на прежнем низком уровне...', '<p>Вооруженные силы США готовы контратаковать ядерные силы КНДР и уничтожить их в случае необходимости. Такое заявление сделал чиновник американского минобороны, передает Yahoo! News.</p>\n\n<p>После того как лидер КНДР Ким Чен Ын заявил о том, что ядерные силы страны должны быть готовы в любой момент атаковать противника, Пентагон оставил оценку опасности северокорейского арсенала на прежнем низком уровне.</p>\n\n<p>&laquo;Оценка правительства США не изменилась&raquo;, &mdash; уточнил он. &laquo;Мы не видели северокорейского испытания или демонстрации ее способности к миниатюризации ядерного оружия и оснащения им межконтинентальных баллистических ракет&raquo;, &mdash; подчеркнул чиновник.</p>\n\n<p>В пятницу, 4 марта, Ким Чен Ын заявил, что КНДР пересмотрит свою военную доктрину, чтобы быть готовой наносить превентивные удары в связи с текущей ситуацией, которая является очень опасной для страны.</p>\n\n<p>2 марта Совет Безопасности ООН&nbsp;<a href="https://lenta.ru/news/2016/03/02/bye_dprk/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">одобрил</a>&nbsp;новый пакет беспрецедентно жестких санкций против Северной Кореи.</p>\n\n<p>Новые санкции в отношении КНДР стали ответом на испытание Пхеньяном в январе 2016 года водородной бомбы и пуск ракеты дальнего радиуса действия в феврале. США&nbsp;<a href="https://lenta.ru/news/2016/02/18/obama1/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">ввели</a>&nbsp;новые односторонние санкции против Северной Кореи 18 февраля. Вскоре после голосования Совбеза они обнародовали дополнительные ограничения: под санкции Минфина США попал министр обороны КНДР Пак Ен Сик, а под санкции Госдепартамента &mdash; министерство атомной энергетики КНДР, военная академия, несколько организаций и два физических лица.</p>\n\n<p>В результате запуска ракеты, произведенного 7 февраля, на орбиту был выведен спутник Kwangmyongsong-4 (&laquo;Яркая звезда-4&raquo;), предназначенный для слежения за Землей. В мировом сообществе этот запуск восприняли как нарушение резолюций Совбеза ООН, запрещающих Северной Корее испытания и пуски баллистических ракет.</p>\n', '', 1, 1, 3, 'publish', 1457071820),
(8, 'fff', 'fff', '<p>fff</p>\r\n', '', 13, 3, 0, 'draft', 1457109400),
(12, 'Банк России резко снизил курсы доллара и евро', 'Официальный курс доллара, установленный Банком России на 2 марта, составляет 74,05 рубля. Он снижен на 1,85 рубля. Евро при этом подешевел на 2,57 рубля, до 80,53 рубля. Такая информация размещена на сайте регулятора.', '<p>Банк России резко снизил курсы доллара и&nbsp;евро</p>\r\n\r\n<p><img alt="" class="g-picture" src="http://icdn.lenta.ru/images/2016/03/01/12/20160301125911692/pic_acea8e752306980f2c7b2a3bc8c933cc.jpg" style="-webkit-font-smoothing:antialiased; border:0px; box-sizing:border-box; display:inline; float:left; height:240px; max-width:420px; vertical-align:middle; width:360px" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Фото: Антон Белицкий / &laquo;Коммерсантъ&raquo;</p>\r\n\r\n<p>Официальный курс доллара, установленный Банком России на 2 марта, составляет 74,05 рубля. Он снижен на 1,85 рубля. Евро при этом подешевел на 2,57 рубля, до 80,53 рубля. Такая информация размещена на&nbsp;<a href="http://www.cbr.ru/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">сайте</a>&nbsp;регулятора.</p>\r\n\r\n<p>На Московской бирже американская валюта стоит 73,79 рубля (минус 1,38&nbsp;рубля по сравнению с закрытием торговой сессии 29&nbsp;февраля), единая европейская&nbsp;&mdash; 80,15&nbsp;рубля (упала на 1,41 рубля). Нефть марки Brent торгуется около отметки 36,63 доллара за баррель. Все данные &mdash; на 12:55 мск.</p>\r\n\r\n<p>Согласно&nbsp;<a href="http://lenta.ru/news/2016/03/01/vciom/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">последнему</a>&nbsp;социологическому опросу ВЦИОМ, большая часть россиян (57 процентов) предпочитают хранить сбережения в рублях. Тем не менее участники исследования ждут, что доллар в ближайшие три месяца подорожает до 84 рублей, а через год будет стоить 83 рубля.</p>\r\n\r\n<p>Ранее, 20 января, глава Банка России Эльвира Набиуллина заявляла, что текущий курс рубля близок к фундаментально обоснованному. Первый вице-премьер правительства Игорь Шувалов, в свою очередь,&nbsp;<a href="http://lenta.ru/news/2016/01/20/keep_calm/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 153, 153); transition: color 0.2s; text-decoration: none;" target="_blank">посоветовал</a>&nbsp;россиянам обращать меньше внимания на изменение валютных курсов.</p>\r\n', '', 1, 1, 1, 'publish', 1457105105),
(13, 'Ученые установили личность британского художника Бэнкси', 'Ученые из колледжа Королевы Марии Лондонского университета смогли установить личность граффитиста Бэнкси. Им оказался выпускник публичной школы в Бристоле Робин Каннингем. Исследование авторов опубликовано в Journal of Spatial Science, кратко о нем сообщает издание The Independent.', '<p><img alt="" class="g-picture" src="http://icdn.lenta.ru/images/2016/03/04/09/20160304091737090/pic_aad215f0536a12bd153391ce1dadbd25.jpg" style="-webkit-font-smoothing:antialiased; border:0px; box-sizing:border-box; display:inline; float:left; height:280px; max-width:420px; vertical-align:middle; width:420px" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Фото: Carlo Allegri / Reuters</p>\r\n\r\n<p>Ученые из колледжа Королевы Марии Лондонского университета смогли установить личность граффитиста Бэнкси. Им оказался выпускник публичной школы в Бристоле Робин Каннингем. Исследование авторов опубликовано в Journal of Spatial Science, кратко о нем сообщает издание The Independent.</p>\r\n\r\n<p>В своей работе ученые использовали географическое профилирование, позволяющее вычислить личность человека по местам, которые он наиболее часто посещает. Специалисты проанализировали около 140 мест на улицах Лондона и Бристоля, где можно было заметить присутствие работ художника.</p>\r\n\r\n<p>МАТЕРИАЛЫ ПО ТЕМЕ</p>\r\n\r\n<p><strong>13:24</strong>&nbsp;29 июня 2010</p>\r\n\r\n<p><a class="b-badge js-dh" href="http://lenta.ru/articles/2010/06/29/apostles/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(204, 51, 51); transition: color 0.2s; text-decoration: none; position: relative; display: inline-block;"><img alt="&quot;Апостолы Петр и Павел&quot; кисти Эль Греко" class="g-picture" src="http://icdn.lenta.ru/images/0000/0265/000002657032/tabloid_1358860977.jpg" style="-webkit-font-smoothing:antialiased; border-style:initial; border-width:0px; box-sizing:border-box; display:inline; float:left; height:64px; margin-right:15px; max-width:96px; vertical-align:middle; width:96px" /></a></p>\r\n\r\n<p><a class="js-dh" href="http://lenta.ru/articles/2010/06/29/apostles/" style="-webkit-font-smoothing: antialiased; box-sizing: border-box; color: rgb(21, 21, 21); transition: color 0.2s; text-decoration: none;">12 прославленных мужчин</a></p>\r\n\r\n<p>Археологи еще немного прояснили историю культа апостолов</p>\r\n\r\n<p>Выяснилось, что данные места совпадают с барами, футбольными полями и домашними адресами, где часто присутствовал Каннингем. То, что он является загадочным художником, начали подозревать еще в 2008 году. В частности, считалось, что настоящее имя художника &mdash; Роберт или Робин.</p>\r\n\r\n<p>Многими экспертами Бэнкси считается наиболее ярким представителем мирового стрит-арта и политического активизма. Работы художника представляют собой графику, подписанную псевдонимом Бэнкси. Стоимость некоторых работ художника оценивается в миллион долларов.</p>\r\n', '', 1, 1, 1, 'publish', 1457078524),
(14, 'Nicolay Post', 'Nicolay was here!', '<p>Nicolay, one of the technicians of <strong>General Baker</strong>, was here!</p>\r\n\r\n<p><em>Updated by Admin</em></p>\r\n', '', 13, 3, 2, 'publish', 1457109597),
(15, 'fff', 'fff', '<p>fff</p>\r\n', '', 13, 3, 0, 'draft', 1457109492),
(92, 'TIMEZONE TEST', '...', '<p>NOW IS [16:49:00 +06:00 GMT]</p>\r\n', '', 1, 1, 2, 'publish', 1457779739),
(94, 'Testing Blog Posting', 'Testing Blog Posting', '<p>Testing Blog Posting</p>\r\n', '', 1, 1, 0, 'publish', 1457847028),
(95, 'Testing Blog Posting', 'Testing Blog Posting', '<p>Testing Blog Posting</p>\r\n', '', 1, 1, 0, 'publish', 1457847149),
(96, 'Testing Blog Posting', 'Testing Blog Posting', '<p>Testing Blog Posting</p>\r\n', '', 1, 1, 0, 'publish', 1457847160),
(97, 'Testing Blog Posting', 'Testing Blog Posting', '<p>Testing Blog Posting</p>\r\n', '', 1, 1, 0, 'publish', 1457847234),
(98, 'Mixa New Post', 'This Is MIXA!', '<p>ddd</p>\r\n', '', 14, 2, 0, 'publish', 1457868733),
(99, 'Two Problems In Russia', 'Two Problems In Russia', '<p>There are 2 problems in Russia:<span style="color:#FF8C00"> fools</span> and <span style="color:#FF0000">roads</span>.</p>\r\n\r\n<p><img alt="" src="http://s017.radikal.ru/i422/1603/0b/995addc47cb4.jpg" style="height:381px; width:460px" /></p>\r\n', '', 14, 2, 0, 'publish', 1457870898),
(104, 'JASON POST', 'JASON POST', '<p>JASON POST</p>\r\n', '', 17, 33, 2, 'publish', 1457878993),
(105, 'JASON POST', 'JASON POST', '<p>JASON POST</p>\r\n', '', 17, 33, 0, 'publish', 1457879006),
(106, 'PRIVATE JASON POST', 'PRIVATE JASON POST', '<p>PRIVATE JASON POST</p>\r\n', '', 17, 33, 0, 'draft', 1457879040),
(107, 'PRIVATE POST', 'PRIVATE POST', '<p>PRIVATE POST</p>\r\n', '', 13, 3, 0, 'draft', 1457880506),
(112, 'dddd', 'dddd', '<p>dddd</p>\r\n', '1,2,3', 1, 1, 0, 'publish', 1457964765),
(114, 'I don''t know how to post, lol', '...', '<p><img alt="Krokodil Gena" src="http://s019.radikal.ru/i623/1603/f5/4b01503f10d1.jpg" style="height:581px; width:539px" /></p>\r\n', '4,5', 13, 3, 0, 'publish', 1457966372),
(117, 'TEST POST, MUST BE DELETED', 'TEST POST, MUST BE DELETED', '<p>TEST POST, MUST BE DELETED</p>\r\n', '', 1, 1, 0, 'publish', 1457993516),
(118, 'TEST POST', 'TEST POST', '<p>TEST POST</p>\r\n', '', 1, 1, 0, 'publish', 1457993671),
(119, 'fff', 'fff', '<p>fff</p>\r\n', '1', 1, 1, 0, 'publish', 1458020265);

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
  `show_email` tinyint(1) DEFAULT '0',
  `auth_key` varchar(255) NOT NULL,
  `status` int(4) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `timezone` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `sex` enum('Male','Female','Not Set') DEFAULT 'Not Set',
  `country_id` int(10) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `about` text,
  `last_login` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `password_hash`, `password_reset_token`, `email`, `show_email`, `auth_key`, `status`, `created_at`, `updated_at`, `timezone`, `full_name`, `sex`, `country_id`, `city`, `about`, `last_login`) VALUES
(1, 'Admin', '$2y$13$yXcv1cmbHqBIi01bEgzTju77xCqswQx4FGEvZ24d27KVnIjJZXG26', NULL, 'hatand@bk.ru', 0, 'PwrSFFEtXUm7A4jFScN2HQvkJrAKKwh1', 10, 1457106539, 1458064151, 'Asia/Novosibirsk', 'Igor Kharchenko', 'Male', 20, '', '<p>Hello to everyone! I am the admin of this blog.</p>\r\n\r\n<p><img alt="Roman Bukin" src="https://icdn.lenta.ru/images/0000/0110/000001107466/pic_1358563301.jpg" style="height:255px; width:340px" /></p>\r\n', 1458064150),
(2, 'Mikhail_Lavrentiev', '$2y$13$aE6qEDc2Xo/0Cyxq5Ag7VenvcmmP5BvgS8niERW8wMjsBVDasOmYG', NULL, 'mikhail_lavrentiev@backmeup.cz', 0, 'S4zm8pKokYT9f6vssQQQ47LYtgldoOKx', 10, 1457106600, 1457873392, 'Europe/Moscow', NULL, 'Not Set', NULL, '', NULL, 1457873392),
(3, 'Nicolay', '$2y$13$ppaVQWWIO6zHprieWYl7cuk6hAfWfSW2pgEifdY0HlOvCgu.Mkx3y', NULL, 'nicolay-was-here@nicolay.info', 1, '8Z7R2AsH6wtppA2J6S_ns3BYGzeVL0mF', 10, 1457107116, 1457965420, 'Europe/Amsterdam', 'Nicolay!!11', 'Not Set', 20, 'Kurgan', '<p>I&#39;m Nicolay, one of the technicians of <strong>G</strong>eneral <strong>B</strong>a<strong>k</strong>er!</p>\r\n', 1457965420),
(4, 'NAT_Korea_User', '$2y$13$q8ShM7LKj2tukxZf4gcEYu0xa0eqlzNG6BTR0HV9NCM66A/JsiM9q', NULL, 'van.shi@nkorea.kp', 0, '2zgiyxsqf9EI1HIKMXfNi7mLyOi219Lt', 10, 1457125148, 1457550159, 'Europe/Moscow', NULL, 'Not Set', NULL, '', NULL, 1457550159),
(6, 'Must_Be_Deleted', '$2y$13$.0psxj6CwefyG41WFLQNXeWfZMLatNI5fWsTAfEuJtLZkNOuBIk9.', NULL, 'must_be_deleted@yii.local', 0, 'HbDkrYQgQe276Qd2hMV1DpLzzUnrvQVb', 10, 1457296163, 1457550176, 'Europe/Moscow', NULL, 'Not Set', NULL, '', NULL, 1457550176),
(33, 'Seryoga_Vasilyev', '$2y$13$SfWJIqGArFVfCZUyYxer4.xGBBt.JgrMpzbB7b3pPSXk9zNzz1RqS', NULL, 'seryoga_vasilyev-890@lenta.ru', 1, 'I1Dp0xu6nATtmZc6E2PCscLKZx09clMc', 10, 1457551828, 1457877714, 'Europe/Moscow', 'Sergey Vasyliev', 'Male', 20, 'Magadan', '<p>I am like Jason Statham: the same restless and love weapon&nbsp;:D</p>\r\n\r\n<p><img alt="" src="https://s-media-cache-ak0.pinimg.com/236x/19/dc/9e/19dc9e49e7e0dd7f6709d342a81a9806.jpg" style="height:328px; width:236px" /></p>\r\n', 1457877714),
(34, 'fff', '$2y$13$P4TbaBwCqFR9CU4V4KBO1.Mz58cCUFOo/PGXCmwjTVx0hGE97BmQ.', NULL, 'fff@ggg.ggg', 0, 'Ij9j3zL8ZLGHGAlhveR6BHdY5ft4hfAD', 10, 1457771991, 1457772810, 'Antarctica/Casey', '', 'Not Set', NULL, '', '', 1457771990);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `FK_blog_blogAuthor_assignment` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_author` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_blog_post_assignment` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
