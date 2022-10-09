<?php

class Employee extends CI_Controller{
    public function index(){
        $strQuery = "SELECT id,
                        name,
                        coalesce(number, '') as number,
                        education,
                        phone_number
                        from employee";

        $query = $this->db->query($strQuery);
        $data['employee'] = $query->result_array();

        $this->load->view('admin/pegawai/v_pegawai', $data);
    }

    public function form($prm_id = ''){
        $this->load->view('admin/pegawai/v_pegawai_form');
    }
}

?>