<?php

class Dashboard extends CI_Controller{
    public function __construct(){
        parent::__construct();

        $this->M_login->session_check();
    }

    public function index(){
        // $data['content'] = 'admin/v_dashboard'; //file view yang akan di load ada didalam folder views
        $data['content'] = 'admin/pegawai/v_pegawai'; //file view yang akan di load ada didalam folder views

        $strQuery = "SELECT id,
                        name,
                        coalesce(number, '') as number,
                        education,
                        phone_number
                        from employee";

        $query = $this->db->query($strQuery);
        $data['employee'] = $query->result_array();

        $this->template->display($data);
    }
}

?>