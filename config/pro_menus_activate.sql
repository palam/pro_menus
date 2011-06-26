CREATE TABLE IF NOT EXISTS `pro_menus_link_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(10) unsigned NOT NULL,
  `selected_if` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
