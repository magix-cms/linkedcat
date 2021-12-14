<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category plugin
 * @package linkedcat
 * @copyright MAGIX CMS Copyright (c) 2011 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @create 09-12-2021
 * @Update 13-12-2021
 * @author Aurélien Gérits <aurelien@magix-cms.com>
 * @name plugins_linkedcat_public
 */
class plugins_linkedcat_public extends plugins_linkedcat_db
{
    /**
     * @var object
     */
    protected
        $template,
        $data,
        $imagesComponent;

    /**
     * @var string
     */
    protected
        $lang,$modelCatalog,$dbCatalog;

    /**
     * plugins_banner_public constructor.
     * @param frontend_model_template|null $t
     */
    public function __construct($t = null)
    {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->lang = $this->template->lang;
        $this->modelCatalog = new frontend_model_catalog($this->template);
        $this->dbCatalog = new frontend_db_catalog();
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return false|null|array
     */
    private function getItems(string $type, $id = null, $context = null, $assign = true)
    {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getLinkedcat($params = []): array
    {
        $modelSystem = new frontend_model_core($this->template);
        $current = $modelSystem->setCurrentId();
        $cat_arr = array();
        $cats = $this->getItems('catId',array('module_linked' => $params['controller'] ,'id_module' => $current['controller']['id']),'one',false);
        $order = $this->getItems('order',array('module_linked' => $params['controller'] ,'id_module' => $current['controller']['id']),'all',false);

        if($cats['ids'] != null) {
            $data = $this->modelCatalog->getData(array(
                'context' => 'category',
                'select' => explode(',', $cats['ids']),
                'deepness' => 1
            ), $current);
            $msc = $this->data->parseData($data, $this->modelCatalog, $current);
            $cat_arr = array();
            foreach ($msc as $page) {
                $cat_arr[$page['id']] = $page;
            }
        }

        // *** Mix categories to fit the sectors order
        $arr = array();
        foreach ($order as $item) {
            $ref = array();
            $ref = $cat_arr;
            $arr[] = $ref[$item['id_cat']];
        }
        return $arr;
    }
}