<?php

class Dashboard extends CI_Controller{
    public function __construct(){
        parent::__construct();

        // untuk mengecek apakah session masih berlaku
        $this->M_login->session_check();
    }

    public function index(){
        $data['content'] = 'admin/v_dashboard'; //file view yang akan di load ada didalam folder views

        $this->template->display($data);
    }

    public function logout(){
        $this->M_login->_logout();

        redirect('/');
    }
}

?>