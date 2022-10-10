<?php

class Attendance extends CI_Controller{
    public function index(){
        $checkEmployee = $this->db->query("select * from employee");
        $getShift = $this->db->query("SELECT * from shift limit 1");

        foreach($checkEmployee->result() as $row){
            echo 'Processing employee: '.$row->name."\n";
            $checkAttendance = $this->db->query("SELECT id from attendance where date = '".date('Y-m-d')."' and employee__id = $row->id");

            if($checkAttendance->num_rows() > 0){
                // nothing to do
            }else{
                $data = [
                    'employee__id' => $row->id,
                    'date' => date('Y-m-d'),
                    'time_in' => null,
                    'time_out' => null,
                    'shift_start' => $getShift->row('time_start'),
                    'shift_end' => $getShift->row('time_end'),
                    'latitude' => null,
                    'longitude' => null,
                    'attachment' => null,
                    'status' => 'Alpha'
                ];

                $this->db->insert('attendance', $data);
            }
        }
    }
}

?>