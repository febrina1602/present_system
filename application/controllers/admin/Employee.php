<?php

class Employee extends CI_Controller{
    public function index(){
        $data['content'] = 'admin/pegawai/v_pegawai';

        $this->template->display($data);
    }

    public function form($prm_id = ''){
        $data['content'] = 'admin/pegawai/v_pegawai_form';

        $this->template->display($data);
    }

    public function dataPut(){
        if($this->input->is_ajax_request()){
            $strQuery = "SELECT id,
                        name,
                        coalesce(number, '') as number,
                        coalesce(education, '') as education,
                        coalesce(phone_number, '') as phone_number
                        from employee";

            $query = $this->db->query($strQuery);
            $result = $query->result_array();

            echo json_encode($result);
        }
    }
}

?>