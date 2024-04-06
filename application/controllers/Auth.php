<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* ------- DONE --------*/
class Auth extends CI_Controller {

	public function __construct (){
		parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
				$this->load->helper('captcha');

				$vals = array(
			        // 'word'          => 'RANDOM WORDS',
			        'img_path'      => './capimg/',
					'img_url'       => base_url('capimg/'),
					'font_path'	 	=> base_url('capimg/Roboto-Light.ttf'),
			        'img_width'	 	=> 200,
		            'img_height' 	=> 80,
		            'border' 		=> 0,
								'word_length'   => 4,
						'font_size'     => '50px',
						'colors'        => array(
			                'background' => array(255, 255, 255),
			                'border' => array(255, 255, 255),
			                'text' => array(0, 0, 0),
			                'grid' => array(230, 230, 230))
				);

				$this->cap = create_captcha($vals);
	}

	public function index (){
		$data['messages'] = NULL;
		$data['capca'] 		= $this->cap['image'];
		$data['text_capca'] = $this->cap['word'];
		$this->load->view('auth/login_form',$data);

	}

	public function process (){
		$data['messages'] = NULL;
		$capca_valid 	= $this->input->post('capca_valid');
		$capca 			= $this->input->post('capca');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if($this->form_validation->run()){
			if ($capca === $capca) {
				$data = array(
					'userid' => $this->db->escape_str($this->input->post('username', TRUE)),
					'passid' => $this->db->escape_str($this->input->post('password', TRUE)),
					'source' => $this->db->escape_str($this->input->post('source', TRUE))
				);
				$result = $this->auth_model->authorizing($data);

				if($result==TRUE){
					$session_data = array(
						'userid' => $data['userid'],
						'source' => $data['source']
					);
					$this->session->set_userdata('smt_member',$session_data);
					$this->referensi_model->save_logs($result['idusr_usr'],'login',$data['userid'],"Berhasil Masuk ke Sistem");
					$user = $this->auth_model->get_userdata();

					$arrayakses = array();

					if($user['level_usr'] == '1'){
						$this->session->set_userdata('menu',$arrayakses);
						header("location:".base_url('home'));
					}else if($user['level_usr'] == '2'){
						$menu['type'] = "multiple";
	          $menu['table'] = "tb_userrole";
	          $menu['condition']['idusr_role'] = $user['idusr_usr'];

	          $datmenu = $this->crud_model->get_data($menu);

	          foreach($datmenu as $dd){
	            array_push($arrayakses,$dd['idmenu_role']);
						}
						$this->session->set_userdata('menu',$arrayakses);

						header("location:".base_url('home'));
					}else if($user['level_usr'] == '3'){
						$this->session->set_userdata('menu',$arrayakses);
						header("location:".base_url('home'));
					}else if($user['level_usr'] == '4'){
						$this->session->set_userdata('menu',$arrayakses);
						header("location:".base_url('Reportppat'));
					}else if($user['level_usr'] == '7'){
						$menu['type'] = "multiple";
	          $menu['table'] = "tb_levelrole";
	          $menu['condition']['idlvl_role'] = $user['typeusr_reg'];

	          $datmenu = $this->crud_model->get_data($menu);

	          foreach($datmenu as $dd){
	            array_push($arrayakses,$dd['idmenu_role']);
						}
						$this->session->set_userdata('menu',$arrayakses);
						header("location:".base_url('Publics/permohonandesa'));
					}

				}
				else {
					$this->referensi_model->save_logs($result['idusr_usr'],'login','0',"Mencoba masuk ke Sistem dengan username <b>$data[userid]</b>");
					$this->session->set_flashdata('message', 'Invalid Username or Password');
					$data['capca'] 		= $this->cap['image'];
					$data['text_capca'] = $this->cap['word'];
					$this->load->view('auth/login_form',$data);
				}
			}else{
				$this->session->set_flashdata('message', 'Salah Captca!');
				$data['capca'] 		= $this->cap['image'];
				$data['text_capca'] = $this->cap['word'];
				$this->load->view('auth/login_form',$data);
			}

		}
		else {
			if(isset($this->session->userdata['smt_member'])){
				header("location:".base_url('home'));
			}
			else{
				$data['capca'] 		= $this->cap['image'];
				$data['text_capca'] = $this->cap['word'];
				$this->load->view('auth/login_form',$data);
			}
		}

	}

	public function logout (){
		$sess_array = array('userid' => '');
		// SAVE LOG
		$data = $this->auth_model->get_userdata();
		$this->referensi_model->save_logs($data['idusr_usr'],'auth',$data['usrid_usr'],'Logout Sistem');

		$this->session->unset_userdata('smt_member', $sess_array);
		$this->session->unset_userdata('status_member', $sess_array);
		$this->session->unset_userdata('menu', $sess_array);


		$data['messages'] = '<p style="text-align:center; color:#FFFFFF;">Successfully Logout</p>';
		$data['capca'] 		= $this->cap['image'];
		$data['text_capca'] = $this->cap['word'];
		$this->load->view('auth/login_form', $data);
	}

	public function checkauth (){
		print_r($_GET);
  if( !empty( $_GET['req'] ) )
    {
      // check if user is logged

      if(!empty($this->session->userdata['smt_member']))
      {
        $url = $_GET['req'];
      //   $ptype=1; // tracking the type of file is being requested
      //   if (strpos($url, 'report_problem') !== false) {
      //       $pdf_name = md5(time()).'.png';
      //       $ptype=2;
      //   }elseif(strpos($url, 'Signature') !== false) {
      //       $filename = "signature.zip";
      //       $ptype=3;
      //   }else{
      //       $pdf_name = md5(time()).'.pdf';
      //   }
      //   $pdf_file = $_SERVER['DOCUMENT_ROOT'].$url;
      //   if( file_exists( $pdf_file ) )
      //   {
			//
      //       if($ptype == 2){
      //           header('Content-Type: image/png');
      //           echo file_get_contents($pdf_file);
      //       }elseif($ptype == 3){
      //           //echo $filename.'<br> '.$pdf_file; die;
      //           header("Pragma: public");
      //           header("Expires: 0");
      //           header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      //           header("Cache-Control: public");
      //           header("Content-Description: File Transfer");
      //           header("Content-type: application/octet-stream");
      //           header("Content-Disposition: attachment; filename=\"".$filename."\"");
      //           header("Content-Transfer-Encoding: binary");
      //           header("Content-Length: ".filesize($pdf_file));
      //           ob_end_flush();
      //           @readfile($pdf_file);
			//
      //       }else{
      //           header('Content-Type: application/pdf');
      //           echo file_get_contents($pdf_file);
      //       }
      //       //echo file_get_contents($pdf_file);
      //   }else{
      //       redirect('My404');
      //   }
      	}else{
        redirect('home');
        }
    }
	}

}
