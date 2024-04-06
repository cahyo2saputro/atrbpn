<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class biodata extends CI_Controller {

	var $userdata = NULL;

	public function __construct (){

		parent::__construct();
		if(isset($this->session->userdata['smt_member'])){
			$this->content['data']['user'] = $this->auth_model->get_userdata();
		}
        date_default_timezone_set('Asia/Jakarta');
				$this->content['data']['title_page'] = 'Biodata';
        $this->load->view('auth/authorized');

	}


	public function index(){
		$this->content['data']['title'] = "Biodata";
    $this->content['data']['subtitle'] = array(array("Biodata","Biodata"));

		$this->content['data']['messages'] 	= null;
		$this->content['data']['biodata'] = $this->auth_model->get_userdata();
		$this->content['load'] 		= array("profile/biodata");

		$user = $this->auth_model->get_userdata();


		$usr['type']              = "single";

        $usr['table']					= "ms_users";
        $usr['condition']['idusr_usr']	= $user['idusr_usr'];

        $this->content['biodata']     = $this->crud_model->get_data($usr);

		$this->content['data']['biodata'] = $this->auth_model->get_userdata();
		$this->load->view('adm',$this->content);
	}

	public function update(){


		$nama 	= $this->input->post("nama");
		$nip 	= $this->input->post("nip");

		$user = $this->auth_model->get_userdata();

		$dataupload['nip_usr']	=	$nip;
		$dataupload['name_usr']	=	$nama;
		if(!empty($_FILES['foto']['name'])){
			$nmfile2 					= $nama."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
    		$config2['upload_path']		= './images/USER/';
    		$config2['allowed_types']	= '*';
    		$this->upload->initialize($config2);
    		$upload2 				= $this->upload->do_upload('foto');
    		$data2					= $this->upload->data();
    		$dataupload['foto_usr'] 		= $data2['file_name'];
		}

        $this->crud_model->update('ms_users',$dataupload,array("idusr_usr"=>$user['idusr_usr']));
        $this->referensi_model->save_logs($this->session->smt_member['userid'],"ms_users",$user['idusr_usr'],"Merubah data user dengan rincian : ".displayArray($dataupload));
        redirect("biodata",'refresh');
	}
}
?>
