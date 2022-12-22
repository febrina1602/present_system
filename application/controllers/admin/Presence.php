<?php

class Presence extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->M_login->session_check();
    }

    public function index()
    {
        $data['title'] = 'Presensi';
        $data['content'] = 'admin/presensi/v_presensi';

        $this->template->display($data);
    }

    public function form($functionkey = '1')
    {
        $data['title'] = 'Presensi';
        $data['content'] = 'admin/presensi/v_presensi_form';
        $data['functionkey'] = $functionkey;
        $data['radius'] = (int)$this->db->query("select value from global_configuration where name = 'attendance_radius'")->row('value');

        $this->template->display($data);
    }

    public function dataPut()
    {
        if ($this->input->is_ajax_request()) {
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
                            where employee__id = '" . $this->session->userdata('employeeId') . "'
                            and date between str_to_date('$start', '%Y-%m-%d') and str_to_date('$end', '%Y-%m-%d')
                        ";

            $query = $this->db->query($strQuery);
            $returnValue = $query->result_array();

            echo json_encode($returnValue);
        }
    }

    public function saveAttendance()
    {
        if ($this->input->is_ajax_request()) {
            $dbDebug = $this->db->db_debug;
            $this->db->db_debug = false;

            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');

            $filename = $_FILES['photo']['name'];
            $filetype = $_FILES['photo']['type'];
            $fileExtension = explode('/', $filetype);
            $filetmp = $_FILES['photo']['tmp_name'];

            $uploadDirectory = 'assets/images/attendance/';
            $newFilename = 'attendance-' . $this->session->userdata('employeeId') . '-' . $currentDate . '-' . $this->input->post('functionKey') . '.' . $fileExtension[1];
            move_uploaded_file($filetmp, $uploadDirectory . $newFilename);

            $getShift = $this->db->query("SELECT * from employee_shift a join shift b on a.shift__id = b.id where employee__id = '" . $this->session->userdata('employeeId') . "' and activated = 'Yes' ");

            if ($getShift->num_rows() > 0) {
                $shift = $getShift->row();

                $data = [
                    'employee__id' => $this->session->userdata('employeeId'),
                    'date' => $currentDate,
                    'shift_start' => $shift->time_start,
                    'shift_end' => $shift->time_end,
                    'attachment' => $newFilename,
                    'status' => 'Hadir'
                ];

                $checkAttendance = $this->db->query("SELECT time_in, time_out from attendance where date = '$currentDate' and employee__id = " . $this->session->userdata('employeeId'));

                try {
                    if ($checkAttendance->num_rows() > 0) {
                        if ((int)$this->input->post('functionKey') === 1) {
                            $currentTimeIn = $checkAttendance->row('time_in');

                            if (!is_null($currentTimeIn) && $currentTime < $currentTimeIn) {
                                $data['time_in'] = $currentTime;
                                $data['longitude_in'] = $this->input->post('longitude_in');
                                $data['latitude_in'] = $this->input->post('latitude_in');
                            } else {
                                $data['time_in'] = $currentTime;
                                $data['longitude_in'] = $this->input->post('longitude_in');
                                $data['latitude_in'] = $this->input->post('latitude_in');
                            }
                        } elseif ((int)$this->input->post('functionKey') === 2) {
                            $currentTimeOut = $checkAttendance->row('time_out');
                            if (!is_null($currentTimeOut) && $currentTime > $currentTimeOut) {
                                $data['time_out'] = $currentTime;
                                $data['longitude_out'] = $this->input->post('longitude_out');
                                $data['latitude_out'] = $this->input->post('latitude_out');
                            } else {
                                $data['time_out'] = $currentTime;
                                $data['longitude_out'] = $this->input->post('longitude_out');
                                $data['latitude_out'] = $this->input->post('latitude_out');
                            }
                        }

                        // print_r($data); exit;

                        $this->db->trans_start(FALSE);

                        $this->db->where(array('employee__id' => $this->session->userdata('employeeId'), 'date' => $currentDate));
                        $this->db->update('attendance', $data);

                        $this->db->trans_complete();

                        $dbError = $this->db->error();
                        if ((int)$dbError['code'] !== 0) {
                            throw new Exception('Database Error: ' . $dbError['code'] . ', message: ' . $dbError['message']);
                            return;
                        }
                    } else {
                        if ((int)$this->input->post('functionKey') === 1) {
                            $currentTimeIn = $checkAttendance->row('time_in');

                            if ($currentTime < $currentTimeIn) {
                                $data['time_in'] = $currentTime;
                                $data['longitude_in'] = $this->input->post('longitude_in');
                                $data['latitude_in'] = $this->input->post('latitude_in');
                            }
                        } elseif ((int)$this->input->post('functionKey') === 2) {
                            $currentTimeOut = $checkAttendance->row('time_out');
                            if ($currentTime > $currentTimeOut) {
                                $data['time_out'] = $currentTime;
                                $data['longitude_out'] = $this->input->post('longitude_out');
                                $data['latitude_out'] = $this->input->post('latitude_out');
                            }
                        }

                        $this->db->trans_start(FALSE);

                        $this->db->insert('attendance', $data);

                        $this->db->trans_complete();

                        $dbError = $this->db->error();
                        if ((int)$dbError['code'] !== 0) {
                            throw new Exception('Database Error: ' . $dbError['code'] . ', message: ' . $dbError['message']);
                            return;
                        }
                    }

                    $returnValue = [
                        'status' => 'success',
                        'message' => 'Absensi anda berhasil disimpan!'
                    ];
                } catch (Exception $e) {
                    $this->db->db_debug = $dbDebug;

                    $returnValue = [
                        'status' => 'success',
                        'message' => 'ada sesuatu yang salah!',
                        'error' => $e->getMessage()
                    ];
                }
            } else {
                $returnValue = [
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki shift!'
                ];
            }

            $this->db->db_debug = $dbDebug;

            echo json_encode($returnValue);
        }
    }

    function getTimeZoneFromIpAddress($prm)
    {
        //print_r($prm); exit;

        $clientsIpAddress = $prm;

        $clientInformation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $clientsIpAddress));
        $clientsLatitude = $clientInformation['geoplugin_latitude'];
        $clientsLongitude = $clientInformation['geoplugin_longitude'];
        $clientsCountryCode = $clientInformation['geoplugin_countryCode'];

        $timeZone = $this->get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode);

        $dtz = new DateTimeZone($timeZone);
        $time_in_sofia = new DateTime('now', $dtz);
        $offset = $dtz->getOffset($time_in_sofia) / 3600;

        $joko = ($offset < 0 ? $offset : $offset);
        echo $joko;
    }

    function get_nearest_timezone($cur_lat, $cur_long, $country_code = '')
    {
        $timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
            : DateTimeZone::listIdentifiers();

        if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

            $time_zone = '';
            $tz_distance = 0;

            //only one identifier?
            if (count($timezone_ids) == 1) {
                $time_zone = $timezone_ids[0];
            } else {

                foreach ($timezone_ids as $timezone_id) {
                    $timezone = new DateTimeZone($timezone_id);
                    $location = $timezone->getLocation();
                    $tz_lat   = $location['latitude'];
                    $tz_long  = $location['longitude'];

                    $theta    = $cur_long - $tz_long;
                    $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
                        + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                    $distance = acos($distance);
                    $distance = abs(rad2deg($distance));
                    // echo '<br />'.$timezone_id.' '.$distance;

                    if (!$time_zone || $tz_distance > $distance) {
                        $time_zone   = $timezone_id;
                        $tz_distance = $distance;
                    }
                }
            }
            return  $time_zone;
        }
        return 'unknown';
    }

    public function getTime() {
        if ($this->access_rights['ADD'] OR $this->access_rights['EDIT']) {
            $time =
                '<span id="Day">' . date('d') . '</span> '
                . '<span id="TextMonth">' . date('F') . '</span> '
                . '<span id="Month" style="display:none;">' . date('m') . '</span> '
                . '<span id="Year">' . date('Y') . '</span> , '
                . '<span id="Hours">' . date('H') . '</span>:'
                . '<span id="Minutes">' . date('i') . '</span>:'
                . '<span id="Seconds">' . date('s') . '</span>'
            ;
            echo $time;
        }
        else {
            redirect('admin/desktop');
        }

    }
}
