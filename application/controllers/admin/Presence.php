<?php

class Presence extends CI_Controller{
    public function __construct()
    {
        parent::__construct();

        $this->M_login->session_check();
    }

    public function index(){
        $data['title'] = 'Riwayat Presensi';
        $data['content'] = 'admin/presensi/v_presensi';

        $getTodayPresence = $this->db->query("SELECT
                                                time_in,
                                                time_out,
                                                shift_start,
                                                shift_end
                                                from attendance a
                                                left join user b on a.id = b.employee__id
                                                where date = '".date('Y-m-d')."'
                                                and b.id = '".$this->session->userdata('userId')."'");

        $data['todayPresence'] = null;
        if($getTodayPresence->num_rows() > 0){
            $data['todayPresence'] = $getTodayPresence->row();
        }


        $this->template->display($data);
    }
}

?>