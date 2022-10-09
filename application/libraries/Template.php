<?php

class Template{
    protected $_ci;
    public function __construct()
    {
        $this->_ci =& get_instance();
    }

    public function display($data){

        $this->_ci->load->view('admin/layouts/base_view', $data);
    }
}

?>