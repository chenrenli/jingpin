<?php
namespace html;

use \think\View;

class Form {

    private $view     = null;
    private $form     = [];
    private $viewPath = "";
    private static $handle = null;

    /*初始化 常用表单元素属性值*/
    private $required     = false;
    private $options      = [];
    private $defaultValue = "";
    private $placeholder  = "";
    private $checked = "";

    /**
     * 初始化 模板引擎、组件模板目录
     * Form constructor.
     */
    public function __construct(){
        $this->view     = View::instance();
        $this->viewPath = EXTEND_PATH.__NAMESPACE__.DS.'view'.DS.'%s.html';
    }

    private function resetValue()
    {
        $this->defaultValue = "";
        $this->options      = [];
        $this->required     = false;
        $this->placeholder  = "";
        $this->checked  = "";
    }

    /**
     * @param $id
     * @param $title
     */
    public function text($id, $title){
        $this->assgin($id, $title);
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'text') );
        $this->resetValue();
    }

    /**
     * @param $id
     * @param $title
     */
    public function password($id, $title){
        $this->assgin($id, $title);
        $this->view->assign('type', 'password');
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'text') );
        $this->resetValue();
    }

    public function required($required = false)
    {
        $this->required = $required;
        return $this;
    }

    public function defaultValue($defaultValue = "")
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function checked($checked = false)
    {
        $this->checked = $checked;
        return $this;
    }

    public function options( $options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function placeholder( $placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @param $id
     * @param $title
     */
    public function select($id, $title){
        $this->assgin($id, $title);
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'select') );
        $this->resetValue();
    }

    /**
     * @param $id
     * @param $title
     */
    public function textarea($id, $title){
        $this->assgin($id, $title);
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'textarea') );
        $this->resetValue();
    }

    /**
     * @param $id
     * @param $title
     */
    public function checkbox($id, $title){
        $this->assgin($id, $title);
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'checkbox') );
        $this->resetValue();
    }

    public function hidden($id)
    {
        $this->assgin($id);
        $this->form[] = $this->view->fetch( sprintf($this->viewPath, 'hidden') );
        $this->resetValue();
    }

    /**
     * @param $id
     * @param $title
     */
    private function assgin($id, $title=""){
        $this->view->assign('required',  $this->required);
        $this->view->assign('title', $title);
        $this->view->assign('name',  $id);
        $this->view->assign('defaultValue', $this->defaultValue);
        $this->view->assign('options', $this->options);
        $this->view->assign('placeholder', $this->placeholder);
        $this->view->assign('checked', $this->checked);
    }

    public function getFormHtml(){
        return $this->form;
    }

    public function getInstance()
    {

    }
}