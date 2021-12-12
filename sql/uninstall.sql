TRUNCATE TABLE `mc_linkedcat`;
DROP TABLE `mc_linkedcat`;

DELETE FROM `mc_plugins` WHERE `name` = 'linkedcat';

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'linkedcat'
);