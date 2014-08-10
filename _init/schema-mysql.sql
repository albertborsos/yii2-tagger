-- Create syntax for TABLE 'ext_tagger_assigns'
CREATE TABLE `ext_tagger_assigns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) unsigned NOT NULL COMMENT 'Cimke',
  `model_class` varchar(160) NOT NULL DEFAULT '' COMMENT 'Osztály',
  `model_id` int(11) NOT NULL COMMENT 'Példány',
  `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
  `status` int(11) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `ext_tagger_assigns_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `ext_tagger_tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'ext_tagger_tags'
CREATE TABLE `ext_tagger_tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(160) DEFAULT NULL COMMENT 'Cimke',
  `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;