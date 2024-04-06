<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class password extends CI_Controller {

	var $userdata = NULL;
	public function __construct (){
		parent::__construct();
		if(isset($this->session->userdata['smt_member'])){
			$this->content['data']['user'] = $this->auth_model->get_userdata();
		}
        date_default_timezone_set('Asia/Jakarta');
				$this->content['data']['title'] = 'Password';
        $this->content['data']['subtitle'] = array(array("Password","Password"));
        $this->load->view('auth/authorized');
	}

	public function index(){
		if(!isset($this->session->smt_member)){
      header("location:".base_url()."auth/");
    }else {
			$this->content['messages'] 	= null;
			$this->content['biodata'] = $this->auth_model->get_userdata();

			$this->form_validation->set_rules('old','Passowrd Lama','required');
			$this->form_validation->set_rules('new','Password Baru','required');
			$this->form_validation->set_rules('retype','Ulangi Password Baru','required');

			if($this->form_validation->run() === TRUE){
				$old = $this->input->post("old");
				$new = $this->input->post("new");
				$retype = $this->input->post("retype");
				if(enkripsi_pass($old) == $this->content['biodata']['pasid_usr']){
					if($new == $retype){
						$this->crud_model->update("ms_users",array("pasid_usr"=>enkripsi_pass($new)),array("usrid_usr"=>$this->content['biodata']['usrid_usr']));
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_users','usrid_usr',"mengubah password");

							$this->content['messages'] = notification("success","Berhasil!","Selamat, Password Berhasil diPerbaharui");
					}
					else {
						$this->content['messages'] = notification("warning","Salah!","Password Baru Anda Tidak Sama");
					}
				}
				else {
					$this->content['messages'] = notification("warning","Salah!","Password Lama Anda Salah");
				}
			}
			$this->content['load'] 		= array("profile/password");
			$this->load->view('adm',$this->content);
		}
	}

}?>
