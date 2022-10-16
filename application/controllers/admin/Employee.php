<?php

class Employee extends CI_Controller{
    public function __construct(){
        parent::__construct();

        // untuk mengecek apakah session masih berlaku
        $this->M_login->session_check();
    }

    // meload view untuk menampilkan daftar pegawai
    public function index(){
        $access = $this->session->userdata('access');

        $data['title'] = 'Daftar Pegawai';
        if($access !== 'admin'){
            redirect('admin/employee/form/'.$this->session->userdata('employeeId'));
        }else{
            $data['content'] = 'admin/pegawai/v_pegawai'; //file view yang akan di load ada didalam folder views
        }

        $this->template->display($data);
    }

    /*
        meload view untuk menampilkan data detail dari pegawai
        atau bisa untuk menambah pegawai baru
    */

    public function form($id = ''){
        $data['title'] = 'Form Pegawai';
        $data['content'] = 'admin/pegawai/v_pegawai_form'; //file view yang akan di load ada didalam folder views
        $data['employeeData'] = '';
        $data['id'] = $id;

        /*
            jika id tidak kosong maka ambil data pegawai sesuai id
            id didapatkan dari table di list v_pegawai
            jika yang di click tombol tambah, maka idnya kosong


        */
        if($id !== ''){
            $strQuery = "SELECT id,
                            name,
                            number,
                            birth_date as \"birthDate\",
                            gender,
                            education,
                            address,
                            phone_number as \"phoneNumber\",
                            level
                            from employee
                            where id = $id
                        ";

            $query = $this->db->query($strQuery);
            /* Data yang didapat dikirim ke view dengan index 'employeeData' */
            $data['employeeData'] = json_encode($query->row());
        }

        $this->template->display($data);
    }

    /*
        fungsi untuk mengambil data pegawai yang ada
        diambil dari file dalam folder view
        admin/pegawai/v_pegawai
    */
    public function dataPut(){
        if($this->input->is_ajax_request()){
            // cek akses user yang login
            $access = $this->session->userdata('userAccess');
            $strQuery = "SELECT a.id,
                        a.name,
                        coalesce(number, '') as number,
                        coalesce(education, '') as education,
                        coalesce(phone_number, '') as phone_number
                        from employee a
                        left join user b on a.id = b.employee__id";

            if($access !== 'admin'){
                $strQuery .=  " WHERE b.id = ".$this->session->userdata('userId');
            }

            // print_r($strQuery); exit;

            $query = $this->db->query($strQuery);
            $result = $query->result_array();

            echo json_encode($result);
        }
    }

    public function generateId(){
        if($this->input->is_ajax_request()){
            // mengambil jumlah pegawai yang sudah ada
            $strQuery = "SELECT count(id) as count from employee";
            $query = $this->db->query($strQuery);
            $count = (int)$query->row('count') + 1;

            if($count < 9){
                $digit = '00'.$count;
            }else if($count > 9 && $count <= 99){
                $digit = '0'.$count;
            }else{
                $digit = $count;
            }

            // date untuk mengambil tahun sekarang
            $employeeId = date('Y').$digit;

            $result = [
                'status' => 'success',
                'id' => $employeeId
            ];

            echo json_encode($result);
        }
    }

    /*
        Fungsi untuk menyimpan pegawai
        data dikirim dari admin/pegawai/v_pegawai_form
        hanya bisa digunakan untuk insert
    */
    public function saveData(){
        if($this->input->is_ajax_request()){
            $dbDebug = $this->db->db_debug;
            $this->db->db_debug = false;

            $id = $this->input->post('id');
            $checkId = $this->db->query("SELECT id from employee where id = ".$id);

            if($checkId->num_rows() > 0 && $this->input->post('method') === 'add'){
                $newId = $this->generateId();
                $id = json_decode($newId);
                $id = $id['id'];
            }

            /*
                menyiapkan data untuk disimpan
                data diambil dengan menggunakan $this->input->post(namafield)
                nama field diambil dari attribute 'name' di html
            */
            $data = [
                'id' => $id,
                'name' => $this->input->post('name'),
                'number' => trim($this->input->post('number')) === "" ? null : $this->input->post('number'),
                'gender' => $this->input->post('gender'),
                'birth_date' => $this->input->post('birthDate'),
                'education' => $this->input->post('education'),
                'phone_number' => $this->input->post('phoneNumber'),
                'level' => $this->input->post('level'),
                'address' => trim($this->input->post('address')) === "" ? null : $this->input->post('address')
            ];

            try{
                if($this->input->post('method') === 'add'){
                    $this->db->insert('employee', $data);
                }else{
                    $this->db->where(array('id' => $id));
                    $this->db->update('employee', $data);
                }

                $dbError = $this->db->error();

                if($dbError['message'] !== ''){
                    throw new Exception($dbError['message']);
                    exit;
                }

                $returnValue = [
                    'status' => 'success',
                    'message' => 'Sukses menyimpan pegawai'
                ];
            }catch(Exception $e){
                $returnValue = [
                    'status' => 'error',
                    'message' => 'Kesalahan saat menyimpan pegawai!',
                    'errorThrown' => $e->getMessage()
                ];
            }

            $this->db->db_debug = $dbDebug;
            echo json_encode($returnValue);
        }
    }
}

?>