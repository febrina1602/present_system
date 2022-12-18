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
                                                time_in as \"timeIn\",
                                                time_out as \"timeOut\",
                                                shift_start as \"shiftStart\",
                                                shift_end as \"shiftEnd\"
                                                from attendance a
                                                left join user b on a.employee__id = b.employee__id
                                                where date = '".date('Y-m-d')."'
                                                and b.id = '".$this->session->userdata('userId')."'");

        $data['todayPresence'] = null;
        if($getTodayPresence->num_rows() > 0){
            $data['todayPresence'] = $getTodayPresence->row();
        }


        $this->template->display($data);
    }

    public function form($functionkey = '1'){  
        $data['title'] = 'Presensi';
        $data['content'] = 'admin/presensi/v_presensi_form';
        $data['functionkey'] = $functionkey;

        $this->template->display($data);
    }
}

?>