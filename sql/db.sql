CREATE TABLE IF NOT EXISTS `mc_linkedcat` (
    `id_linked` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id_cat` int(7) DEFAULT NULL,
    `module_linked` varchar(25) NOT NULL DEFAULT 'category',
    `id_module` int(11) DEFAULT NULL,
    `order_linked` smallint(5) unsigned NOT NULL,
    PRIMARY KEY (`id_linked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'linkedcat';