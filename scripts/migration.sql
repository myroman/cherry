-- hermes

CREATE TABLE IF NOT EXISTS `oc_hermes_parcelshops` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `createdate` datetime NOT NULL,
 `modifydate` datetime DEFAULT NULL,
 `updateTimestamp` varchar(50) COLLATE utf8_bin NOT NULL,
 `parcelShopCode` varchar(100) COLLATE utf8_bin NOT NULL,
 `parcelShopName` varchar(100) CHARACTER SET utf8 NOT NULL,
 `address` varchar(500) CHARACTER SET utf8 NOT NULL,
 `city` varchar(100) CHARACTER SET utf8 NOT NULL,
 `addressnotes` varchar(1000) CHARACTER SET utf8 NOT NULL,
 `maxParcelOverallSize` decimal(8,2) DEFAULT NULL,
 `maxParcelValue` decimal(8,2) DEFAULT NULL,
 `maxParcelWeight` decimal(8,2) DEFAULT NULL,
 `paymentType` varchar(100) COLLATE utf8_bin DEFAULT NULL,
 `region` varchar(100) CHARACTER SET utf8 NOT NULL,
 `schedulejson` varchar(15000) COLLATE utf8_bin NOT NULL,
 `services` varchar(200) COLLATE utf8_bin NOT NULL,
 `zipcode` varchar(10) COLLATE utf8_bin DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=876 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `oc_hermes_price` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `createdate` datetime NOT NULL,
 `parcelShopCode` varchar(100) COLLATE utf8_bin NOT NULL,
 `price` decimal(8,2) DEFAULT NULL,
 `currencyCode` varchar(3) COLLATE utf8_bin DEFAULT NULL,
 `error` bit(1) DEFAULT NULL,
 `errorMsg` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
 `errorCat` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2246 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- only russia & us
UPDATE `oc_country` SET status=0 WHERE `iso_code_2` != 'RU' AND `iso_code_2` != 'US';
UPDATE `oc_country` SET `name`='Российская Федерация' where country_id=176;

DELETE FROM `oc_product`;

update `oc_language` set `status`=0;
INSERT INTO `oc_language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`) 
VALUES
(2, 'Русский', 'ru', 'ru_ru,russian', 'ru.png', 'russian', 1, 1);

update `oc_currency` set `status`=0 where `code` != 'RUR';
INSERT INTO `oc_currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`) VALUES
(4, 'Russian ruble', 'RUR', '', '', '2', 1.00000000, 1, '2019-05-22 07:05:37');

-- information
INSERT INTO `oc_information` (`information_id`, `bottom`, `sort_order`, `status`) VALUES
(3, 1, 3, 1),
(4, 1, 1, 1),
(5, 1, 4, 1),
(6, 1, 2, 1),
(7, 1, 5, 1),
(8, 1, 6, 1),
(9, 1, 7, 1);


INSERT INTO `oc_information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
(4, 2, 'О продукте', '&lt;p&gt;\r\n    О продукте&lt;/p&gt;\r\n', 'О продукте', '', ''),
(5, 2, 'Частые вопросы', '&lt;p&gt;\r\n    Частые вопросы&lt;/p&gt;\r\n', 'Частые вопросы', '', ''),
(3, 2, 'Испытания', '&lt;p&gt;\r\n    Испытания&lt;/p&gt;\r\n', 'Испытания', '', ''),
(6, 2, 'Применение', '&lt;p&gt;\r\n    Применение&lt;/p&gt;\r\n', 'Применение', '', ''),
(7, 2, 'Полезная инфа', '&lt;p&gt;Полезная инфа&lt;br&gt;&lt;/p&gt;', 'Полезная инфа', '', ''),
(8, 2, 'Первый Легион', '&lt;p&gt;Первый Легион&lt;br&gt;&lt;/p&gt;', 'Первый Легион', '', ''),
(9, 2, 'Контакты', '&lt;p&gt;Контакты&lt;br&gt;&lt;/p&gt;', 'Контакты', '', '');

INSERT INTO `oc_information_to_layout` (`information_id`, `store_id`, `layout_id`) VALUES
(4, 0, 0),
(6, 0, 0),
(3, 0, 0),
(5, 0, 0),
(7, 0, 0),
(8, 0, 0),
(9, 0, 0);

INSERT INTO `oc_information_to_store` (`information_id`, `store_id`) VALUES
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0);