CREATE TABLE IF NOT EXISTS `intra_enkel_ronde` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(32) NOT NULL,
  `einddatum` DATE NOT NULL,
  `startdatum` DATE NOT NULL,  
  `aangemaakt` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `intra_enkel_poule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(32) NOT NULL,
  `ronde_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `intra_enkel_poule_spelers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poule_id` int(11) NOT NULL,
  `speler_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `intra_enkel_wedstrijd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poule_id` int(11) NOT NULL,
  `spelerThuis_id` int(11) NOT NULL,
  `spelerThuis_handicap` int(11) NOT NULL,
  `spelerThuis_set1` int(11) NOT NULL,
  `spelerThuis_set2` int(11) NOT NULL,
  `spelerThuis_set3` int(11) NOT NULL,
  `spelerThuis_punten` int(11) NOT NULL,
  `spelerUit_id` int(11) NOT NULL,
  `spelerUit_handicap` int(11) NOT NULL,
  `spelerUit_set1` int(11) NOT NULL,
  `spelerUit_set2` int(11) NOT NULL,
  `spelerUit_set3` int(11) NOT NULL,
  `spelerUit_punten` int(11) NOT NULL,
  `ingevuld_door` varchar(32) NOT NULL,
  `ingevuld` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poule_id` (`poule_id`),
  KEY `spelerThuis` (`spelerThuis`),
  KEY `spelerUit` (`spelerUit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `intra_enkel_wedstrijd`
	ADD CONSTRAINT `uitslagenPouleFK` FOREIGN KEY (`poule_id`) REFERENCES `intra_enkel_poule` (`id`) ON DELETE NO ACTION,
	ADD CONSTRAINT `spelerThuisFK` FOREIGN KEY (`spelerThuis_id`) REFERENCES `intra_spelers` (`id`) ON DELETE NO ACTION,
	ADD CONSTRAINT `spelerUitFK` FOREIGN KEY (`spelerUit_id`) REFERENCES `intra_spelers` (`id`) ON DELETE NO ACTION;
	
ALTER TABLE `intra_enkel_poule_spelers`
		ADD CONSTRAINT `pouleFK` FOREIGN KEY (`poule_id`) REFERENCES `intra_enkel_poule` (`id`) ON DELETE NO ACTION,
		ADD CONSTRAINT `spelerFK` FOREIGN KEY (`speler_id`) REFERENCES `intra_spelers` (`id`) ON DELETE NO ACTION;

ALTER TABLE `intra_enkel_poule`
		ADD CONSTRAINT `rondeFK` FOREIGN KEY (`ronde_id`) REFERENCES `intra_enkel_ronde` (`id`) ON DELETE NO ACTION;

		
		