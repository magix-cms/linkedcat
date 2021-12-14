<?php
class plugins_linkedcat_admin extends plugins_linkedcat_db
{
    private $hidden;
    public $edit, $action, $tabs, $search, $plugin, $controller;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent, $modelPlugins, $routingUrl, $makeFiles, $finder, $plugins;
    public $id_attr, $id_attr_va, $content, $pages, $img, $iso, $del_img, $ajax, $tableaction,
        $tableform, $offset, $name_img, $cats_id, $id_pages,$linkedcat,$linkedData;
    /**
     * frontend_controller_home constructor.
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->modelPlugins = new backend_model_plugins();
        $this->routingUrl = new component_routing_url();

        if(http_request::isGet('plugin')){
            $this->plugin = $formClean->simpleClean($_GET['plugin']);
        }
        // --- ADD or EDIT
        // --- GET
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isGet('ajax')) $this->ajax = $formClean->simpleClean($_GET['ajax']);
        if (http_request::isGet('offset')) $this->offset = intval($formClean->simpleClean($_GET['offset']));

        if (http_request::isGet('tableaction')) {
            $this->tableaction = $formClean->simpleClean($_GET['tableaction']);
            $this->tableform = new backend_controller_tableform($this,$this->template);
        }
        if (http_request::isGet('id')) $this->id_pages = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_pages = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('cats_id')) $this->cats_id = $formClean->simpleClean($_POST['cats_id']);
        if (http_request::isPost('linkedData')) $this->linkedData = $formClean->arrayClean($_POST['linkedData']);
        if (http_request::isPost('category')) $this->linkedcat = $formClean->arrayClean($_POST['category']);
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @param boolean $pagination
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }
    /**
     * Update data
     * @param $data
     * @throws Exception
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'cat':
                parent::insert(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param $data
     * @throws Exception
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'order':
                parent::update(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     * @throws Exception
     */
    private function del($data)
    {
        switch($data['type']){
            case 'delCat':
                parent::delete(
                    array(
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }
    /**
     * @param $type
     */
    protected function order(){
        for ($i = 0; $i < count($this->linkedcat); $i++) {
            $this->upd(['type' => 'order', 'data' => ['id_linked' => $this->linkedcat[$i], 'order_linked' => $i]]);
        }
    }
    /**
     * @throws Exception
     */
    public function run()
    {
        if (isset($this->tableaction)) {
            $this->tableform->run();
        } elseif (isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->cats_id)){
                        $this->add(array(
                                'type' => 'cat',
                                'data' => array(
                                    'id_cat' => $this->cats_id,
                                    'module' => $this->linkedData['module_linked'],
                                    'id_module' => $this->linkedData['id_module']
                                )
                            )
                        );
                        $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                        $this->getItems('lastCat', array('default_lang' => $defaultLanguage['id_lang'],'module_linked' => $this->linkedData['module_linked'],
                            'id_module' => $this->linkedData['id_module']), 'one', 'row');

                        $display = $this->template->fetch('loop/linkedcat.tpl');
                        $this->message->json_post_response(true, 'add', $display);

                    }
                    break;
                case 'delete':
                    $this->del(
                        array(
                            'type' => 'delCat',
                            'data' => array(
                                'id' => $this->id_pages
                            )
                        )
                    );
                    break;
                case 'order':
                    if (isset($this->linkedcat) && is_array($this->linkedcat)) {
                        $this->order();
                    }
                    break;
            }
        }
    }
}
