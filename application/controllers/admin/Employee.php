<?php

class Employee extends CI_Controller{
    public function index(){
        $data['content'] = 'admin/pegawai/v_pegawai';

        $this->template->display($data);
    }

    public function form($prm_id = ''){
        $this->load->view('admin/pegawai/v_pegawai_form');
    }

    public function dataPut(){
        if($this->input->is_ajax_request()){
            $strQuery = "SELECT id,
                        name,
                        coalesce(number, '') as number,
                        education,
                        phone_number
                        from employee";

            $query = $this->db->query($strQuery);
            $result = $query->result_array();

            echo json_encode($result);
        }
    }
}

?>