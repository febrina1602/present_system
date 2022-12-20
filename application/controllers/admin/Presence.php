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
                                                and b.id = '".$this->session->userdata('employeeId')."'");

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

    public function saveAttendance(){
        if($this->input->is_ajax_request()){
            $dbDebug = $this->db->db_debug;
            $this->db->db_debug = false;

            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $filename = $_FILES['photo']['name'];
            $filetype = $_FILES['photo']['type'];
            $fileExtension = explode('/', $filetype);
            $filetmp = $_FILES['photo']['tmp_name'];

            $uploadDirectory = 'assets/images/attendance/';
            $newFilename = 'attendance-'.$this->session->userdata('employeeId').'-'.$this->input->post('functionKey').'.'.$fileExtension[1];
            move_uploaded_file($filetmp, $uploadDirectory.$newFilename);

            $getShift = $this->db->query("SELECT * from employee_shift a join shift b on a.shift__id = b.id where employee__id = '".$this->session->userdata('employeeId')."' and activated = 'Yes' ");
            
            if($getShift->num_rows() > 0){
                $shift = $getShift->row();

                $data = [
                    'employee__id' => $this->session->userdata('employeeId'),
                    'date' => $currentDate,
                    'time_in' => null,
                    'time_out' => null,
                    'shift_start' => $shift->time_start,
                    'shift_end' => $shift->time_end,
                    'attachment' => $newFilename,
                    'status' => 'Hadir'
                ];
    
                $checkAttendance = $this->db->query("SELECT time_in, time_out from attendance where date = '$currentDate' and employee__id = ".$this->session->userdata('employeeId'));
    
                try{
                    if($checkAttendance->num_rows() > 0){
                        if((int)$this->input->post('functionKey') === 1){
                            $currentTimeIn = $checkAttendance->row('time_in');
            
                            if($currentTime < $currentTimeIn){
                                $data['time_in'] = $currentTime;
                                $data['longitude_in'] = $this->input->post('longitude_in');
                                $data['latitude_in'] = $this->input->post('latitude_in');
                            }
                        }elseif((int)$this->input->post('functionKey') === 2){
                            $currentTimeOut = $checkAttendance->row('time_out');
                            if($currentTime > $currentTimeOut){
                                $data['time_out'] = $currentTime;
                                $data['longitude_out'] = $this->input->post('longitude_out');
                                $data['latitude_out'] = $this->input->post('latitude_out');
                            }
                        }

                        $this->db->trans_start(FALSE);

                        $this->db->where(array('employee__id' => $this->session->userdata('employeeId'), 'date' => $currentDate));
                        $this->db->update('attendance', $data);

                        $this->db->trans_complete();

                        $dbError = $this->db->error();
                        if((int)$dbError['code'] !== 0){
                            throw new Exception('Database Error: '.$dbError['code'].', message: '.$dbError['message']);
                            return;
                        }
        
                    }else{
                        if((int)$this->input->post('functionKey') === 1){
                            $currentTimeIn = $checkAttendance->row('time_in');
            
                            if($currentTime < $currentTimeIn){
                                $data['time_in'] = $currentTime;
                                $data['longitude_in'] = $this->input->post('longitude_in');
                                $data['latitude_in'] = $this->input->post('latitude_in');
                            }
                        }elseif((int)$this->input->post('functionKey') === 2){
                            $currentTimeOut = $checkAttendance->row('time_out');
                            if($currentTime > $currentTimeOut){
                                $data['time_out'] = $currentTime;
                                $data['longitude_out'] = $this->input->post('longitude_out');
                                $data['latitude_out'] = $this->input->post('latitude_out');
                            }
                        }

                        $this->db->trans_start(FALSE);
        
                        $this->db->insert('attendance', $data);

                        $this->db->trans_complete();

                        $dbError = $this->db->error();
                        if((int)$dbError['code'] !== 0){
                            throw new Exception('Database Error: '.$dbError['code'].', message: '.$dbError['message']);
                            return;
                        }
                    }

                    $returnValue = [
                        'status' => 'success',
                        'message' => 'Absensi anda berhasil disimpan!'
                    ];
                }catch(Exception $e){
                    $this->db->db_debug = $dbDebug;

                    $returnValue = [
                        'status' => 'success',
                        'message' => 'ada sesuatu yang salah!',
                        'error' => $e->getMessage()
                    ];
                }
            }else{
                $returnValue = [
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki shift!'
                ];
            }

            $this->db->db_debug = $dbDebug;
            
            echo json_encode($returnValue);
        }
    }
}

?>