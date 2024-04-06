<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio4 extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();

			$this->load->database();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$user = $this->auth_model->get_userdata();
			$level_usr = $user['level_usr'];
			cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Administrasi';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];

			$this->content['data']['title'] = "Data Administrasi";
			$this->content['data']['subtitle'] = array(array("Administrasi","Studio4"));

			$dat['table'] = "tb_administrasi";
      $dat['type']  = "multiple";
			$dat['join']['table']  = "ms_administrasi";
			$dat['join']['key']    = "id_adt";
			$dat['join']['ref']    = "id_adm";
			$dat['condition']['publish_adt']    = "1";
			if($this->input->get('no')){
				$dat['condition']['no_adt']    = $this->input->get('no');
			}

			if($this->input->get('perihal')){
				$dat['like']['perihal_adt']    = $this->input->get('perihal');
			}

			if($this->input->get('bulan')){
				$dat['like']['tanggal_adt']    = $this->input->get('bulan');
			}
			$this->content['administrasi'] = $this->crud_model->get_data($dat);

			$this->content['load'] = array("administrasi/data");

			$this->load->view('adm',$this->content);
		}

		public function form()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];

			$status = $this->uri->segment(3);
			if($status){
				$this->content['data']['title'] 	= "Form Edit Administrasi";
				$this->content['data']['subtitle'] 	= array(array("Administrasi","Studio4"),array("Edit Administrasi","Studio4/form/".$status));

				$dat['table'] = "ms_administrasi";
	      $dat['type']  = "multiple";
				$this->content['administrasi'] = $this->crud_model->get_data($dat);

				$sel['table'] = "tb_administrasi";
	      $sel['type']  = "single";
				$sel['condition']['id_adt']  = $status;
				$this->content['get_data'] = $this->crud_model->get_data($sel);
				$this->content['link'] = base_url()."studio4/edit/".$status;
				$this->content['status']			= "edit";
			}else{
				$this->content['data']['title'] 	= "Form Tambah Administrasi";
				$this->content['data']['subtitle'] 	= array(array("Administrasi","Studio4"),array("Tambah Administrasi","Studio4/form"));

				$dat['table'] = "ms_administrasi";
	      $dat['type']  = "multiple";
				$this->content['administrasi'] = $this->crud_model->get_data($dat);
				$this->content['link'] = base_url()."studio4/tambah";
				$this->content['status']			= "tambah";
			}


			$this->content['load'] 				= array("administrasi/form");
			$this->load->view('adm',$this->content);
		}

		public function tambah()
		{
			$user = $this->auth_model->get_userdata();

			$file = explode(".",$_FILES["img"]["name"]);
			$sum = count($file);

			$nmfile1 					= "TU_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
			$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
			$config1['upload_path']		= './DATA/SURAT/';
			$config1['allowed_types']	= '*';
			$this->upload->initialize($config1);
			$uploads 				= $this->upload->do_upload('img');
			$data1					= $this->upload->data();
			if($data1){
				$image=$data1['file_name'];
			}else{
				$image='';
			}

			$ar = array(
				'idadm_adt' => $this->input->post('kategori'),
				'no_adt'   => $this->input->post('nosurat'),
				'perihal_adt' => $this->input->post('perihal'),
				'tanggal_adt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
				'ket_adt' => $this->input->post('ket'),
				'image_adt' => $image,
				'publish_adt' => '1',
				'idusr_adt' => $user['idusr_usr'],
				'create_at' => date('Y-m-d H:i:s')
			);
			$simpan = $this->crud_model->input('tb_administrasi',$ar);

			$insert_id = $this->db->insert_id();

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_administrasi','e Administrasi-0-'.$insert_id,"Menambahkan Administrasi dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit($kode)
		{
			$user = $this->auth_model->get_userdata();

			if(!empty($_FILES['img']['name'])){

				$file = explode(".",$_FILES["img"]["name"]);
				$sum = count($file);

				$nmfile1 					= "TU_".time().".".$file[$sum-1];	//nama file saya beri nama langsung dan diikuti fungsi time
				$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
				$config1['upload_path']		= './DATA/SURAT/';
				$config1['allowed_types']	= '*';
				$this->upload->initialize($config1);
				$uploads 				= $this->upload->do_upload('img');
				$data1					= $this->upload->data();
				if($data1){
					$image=$data1['file_name'];
				}else{
					$image='';
				}

				$ar = array(
					'idadm_adt' => $this->input->post('kategori'),
					'no_adt'   => $this->input->post('nosurat'),
					'perihal_adt' => $this->input->post('perihal'),
					'tanggal_adt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'ket_adt' => $this->input->post('ket'),
					'image_adt' => $image,
					'idusr_adt' => $user['idusr_usr']
				);
			}else{
				$ar = array(
					'idadm_adt' => $this->input->post('kategori'),
					'no_adt'   => $this->input->post('nosurat'),
					'perihal_adt' => $this->input->post('perihal'),
					'tanggal_adt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'ket_adt' => $this->input->post('ket'),
					'idusr_adt' => $user['idusr_usr']
				);
			}

			$simpan = $this->crud_model->update('tb_administrasi',$ar,array('id_adt'=>$kode));

			$insert_id = $this->db->insert_id();

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_administrasi','e Administrasi-0-'.$kode,"Mengubah Administrasi dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus($kode)
		{
			$ar = array(
				'publish_adt' => '0'
			);
			$hapus = $this->crud_model->update('tb_administrasi',$ar,array('id_adt'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_administrasi','e Administrasi-0-'.$kode,"Menghapus Data Administrasi dengan kode ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}
	}
 ?>
