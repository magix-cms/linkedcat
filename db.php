<?php
class plugins_linkedcat_db
{
    /**
     * @param $config
     * @param bool $params
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config, $params = false)
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';
        $dateFormat = new component_format_date();

        if ($config['context'] === 'all') {
            switch ($config['type']) {
                case 'pages':
                    $limit = '';
                    if ($config['offset']) {
                        $limit = ' LIMIT 0, ' . $config['offset'];
                        if (isset($config['page']) && $config['page'] > 1) {
                            $limit = ' LIMIT ' . (($config['page'] - 1) * $config['offset']) . ', ' . $config['offset'];
                        }
                    }

                    $sql = "" . $limit;

                    if (isset($config['search'])) {
                        $cond = '';
                        if (is_array($config['search']) && !empty($config['search'])) {
                            $nbc = 0;
                            foreach ($config['search'] as $key => $q) {
                                if ($q !== '') {
                                    $cond .= 'AND ';
                                    //$p = 'p' . $nbc;
                                    //$cond .= !$nbc ? ' WHERE ' : 'AND ';
                                    switch ($key) {
                                        case 'id_attr':
                                            $cond .= ' p.' . $key . ' = ' . $q . ' ';
                                            break;
                                        case 'type_attr':
                                            $cond .= ' c.' . $key . ' = ' . '"'.$q .'"'. ' ';
                                            break;
                                        case 'date_register':
                                            $q = $dateFormat->date_to_db_format($q);
                                            $cond .= " p." . $key . " LIKE CONCAT('%', " . $q . ", '%') ";
                                            break;
                                    }
                                    //$params[$p] = $q;
                                    $nbc++;
                                }
                            }

                            $sql = "
                            $cond
                            " . $limit;
                        }
                    }
                    break;
                case 'pagesPublishedSelect':
                    $sql = "SELECT p.id_parent,p.id_cat, c.name_cat , ca.name_cat AS parent_cat
							FROM mc_catalog_cat AS p
								JOIN mc_catalog_cat_content AS c USING ( id_cat )
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								LEFT JOIN mc_catalog_cat AS pa ON ( p.id_parent = pa.id_cat )
								LEFT JOIN mc_catalog_cat_content AS ca ON ( pa.id_cat = ca.id_cat ) 
								WHERE c.id_lang = :default_lang
								AND c.published_cat = 1
								GROUP BY p.id_cat 
							ORDER BY p.id_cat DESC";
                    break;
                case 'linkedcat':
                    $sql = "SELECT lc.id_linked, p.id_cat, c.name_cat
							FROM mc_linkedcat AS lc
							    JOIN mc_catalog_cat AS p on (p.id_cat = lc.id_cat)
								JOIN mc_catalog_cat_content AS c ON (p.id_cat = c.id_cat)
								JOIN mc_lang AS lang ON ( c.id_lang = lang.id_lang )
								WHERE c.id_lang = :default_lang AND lc.id_module = :id_module AND lc.module_linked = :module_linked
							ORDER BY lc.order_linked DESC";
                    break;
            }

            return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
        } elseif ($config['context'] === 'one') {
            switch ($config['type']) {

            }

            return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function insert($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {

        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->insert($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function update($config,$params = array())
    {
        if (!is_array($config)) return '$config must be an array';

        $sql = '';

        switch ($config['type']) {

        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->update($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
    /**
     * @param $config
     * @param array $params
     * @return bool|string
     */
    public function delete($config, $params = array())
    {
        if (!is_array($config)) return '$config must be an array';
        $sql = '';

        switch ($config['type']) {
        }

        if($sql === '') return 'Unknown request asked';

        try {
            component_routing_db::layer()->delete($sql,$params);
            return true;
        }
        catch (Exception $e) {
            return 'Exception reçue : '.$e->getMessage();
        }
    }
}