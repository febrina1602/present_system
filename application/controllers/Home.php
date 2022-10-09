<?php

class Home extends CI_Controller{
    public function index(){
        $this->load->view('v_login');
    }

    public function authCheck(){
        if($this->input->is_ajax_request()){
            // mengambil data yang dikirim dari view
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // query untuk check username dan password
            $this->db->select("id, username, access");
            $this->db->from("user");
            $this->db->where("username = '$username' and password = '".md5($password)."'");
            // querynya dijalankan
            $check = $this->db->get();
            // jika ada user yang username dan passwordnya cocok
            if($check->num_rows() > 0){
                $dataUser = [
                    'userId' => $check->row('id'),
                    'username' => $check->row('username'),
                    'userAccess' => $check->row('access'),
                    'loginStat' => true
                ];

                // set session
                $this->session->set_userdata($dataUser);

                $return_value = [
                    'status' => 'success',
                    'message' => 'Berhasil Login!'
                ];
            }else{
                $return_value = [
                    'status' => 'error',
                    'message' => 'Username atau password salah!'
                ];
            }

            // mengembalikan data ke view
            echo json_encode($return_value);
        }
    }
}

?>