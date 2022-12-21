<?php

class History extends CI_Controller{
    public function __construct()
    {
        parent::__construct();

        $this->M_login->session_check();
    }

    public function index(){
        $data['title'] = 'Riwayat';
        $data['content'] = 'admin/presensi/v_history';

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

    public function dataPut(){
        if($this->input->is_ajax_request()){
            $start = $this->input->post('startDate');
            $end = $this->input->post('endDate');

            $strQuery = "SELECT
                            date,
                            coalesce(time_in, '') as \"timeIn\",
                            coalesce(time_out, '') as \"timeOut\",
                            coalesce(shift_start, '') as \"shiftIn\",
                            coalesce(shift_end, '') as \"shiftOut\",
                            status
                            from attendance
                            where employee__id = '".$this->session->userdata('employeeId')."'
                            and date between str_to_date('$start', '%Y-%m-%d') and str_to_date('$end', '%Y-%m-%d')
                        ";

            $query = $this->db->query($strQuery);
            $returnValue = $query->result_array();

            echo json_encode($returnValue);
        }
    }
}

?>