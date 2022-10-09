<?php

class M_login extends CI_Model{
    public function session_check(){
        @session_start();
		if ($this->session->userdata('loginStat'))
		{
			// 
		}
		else
		{
			$this->_logout();

			die ('<span style="font: 9pt Sans, Verdana; font-weight: bold;">Keluar..</span><script>location.replace("'.site_url('/').'");</script>');
		}
    }

    public function _logout(){
        $this->session->sess_destroy();
    }
}

?>