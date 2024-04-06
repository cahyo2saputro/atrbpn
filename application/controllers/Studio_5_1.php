<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_5_1 extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$user = $this->auth_model->get_userdata();
			cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);
			if($this->input->get('search')){
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->input->get('search'));
			}
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = '';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$cari = $this->input->get('search');

			$kelurahan['type'] = "single";
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['condition']['kd_full'] = $cari;

			$nma_kel = $this->crud_model->get_data($kelurahan);

			$this->content['data']['title'] = "e-Pengukuran ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-Pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studio_5_1/index/';
			$config['total_rows'] = $t_data;
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 30;

			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['first_link'] = 'Awal';
			$config['last_link'] = 'Akhir';
			$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_open'] = '<li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_open'] = '<li>';


			$this->pagination->initialize($config);


			$this->content['block'] = $this->studio_3_1_model->show_data_5($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio5/data_studio_5_1");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_ukur($id)
		{
			$user = $this->auth_model->get_userdata();
			$count = count($_FILES['petukur_blk']['name']);

			for($i=0;$i<$count;$i++){
					$_FILES['file']['name'] = $_FILES['petukur_blk']['name'][$i];
          $_FILES['file']['type'] = $_FILES['petukur_blk']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['petukur_blk']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['petukur_blk']['error'][$i];
          $_FILES['file']['size'] = $_FILES['petukur_blk']['size'][$i];

					$file = explode(".",$_FILES["petukur_blk"]["name"][$i]);
	        $sum = count($file);
					$nmfile1 					= "UKUR_".$id."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
					$config1['upload_path']		= './PETA/PETA_UKUR/';
					$config1['allowed_types']	= '*';
					$this->upload->initialize($config1);

					$uploads 				= $this->upload->do_upload('file');
					$data1					= $this->upload->data();
					$nama_upload_ukur 		= $data1['file_name'];
					$description = $this->input->post('desc_blk');

					if($data1){
						$ar = array(
							'idbuk_blk' => $id,
							'petukur_blk' => $nama_upload_ukur,
							'desc_blk' => $description[$i],
							'idusr_blk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_blockukur',$ar);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_blockukur','e Pengukuran-0-'.$id,"Add Peta Ukur dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_gu($id)
		{
			$user = $this->auth_model->get_userdata();

			$count = count($_FILES['gu_blk']['name']);

			for($i=0;$i<$count;$i++){
					$_FILES['file']['name'] = $_FILES['gu_blk']['name'][$i];
          $_FILES['file']['type'] = $_FILES['gu_blk']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['gu_blk']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['gu_blk']['error'][$i];
          $_FILES['file']['size'] = $_FILES['gu_blk']['size'][$i];

					$file = explode(".",$_FILES["gu_blk"]["name"][$i]);
	        $sum = count($file);
					$nmfile1 					= "GU_".$id."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
					$config1['upload_path']		= './DATA/GU/';
					$config1['allowed_types']	= '*';
					$this->upload->initialize($config1);

					$uploads 				= $this->upload->do_upload('file');
					$data1					= $this->upload->data();
					$nama_upload_gu 		= $data1['file_name'];
					$description = $this->input->post('desc_blk');

					if($data1){
						$ar = array(
							'idbgu_blk' => $id,
							'gu_blk' => $nama_upload_gu,
							'desc_blk' => $description[$i],
							'idusr_blk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_blockgu',$ar);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_blockgu','e Pengukuran-0-'.$id,"Add GU dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_datmen($id)
		{
			$user = $this->auth_model->get_userdata();

			$count = count($_FILES['datmen_blk']['name']);

			for($i=0;$i<$count;$i++){
					$_FILES['file']['name'] = $_FILES['datmen_blk']['name'][$i];
          $_FILES['file']['type'] = $_FILES['datmen_blk']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['datmen_blk']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['datmen_blk']['error'][$i];
          $_FILES['file']['size'] = $_FILES['datmen_blk']['size'][$i];

					$file = explode(".",$_FILES["datmen_blk"]["name"][$i]);
	        $sum = count($file);
					$nmfile1 					= "DM_".$id."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
					$config1['upload_path']		= './DATA/DATA_MENTAH/';
					$config1['allowed_types']	= '*';
					$this->upload->initialize($config1);

					$uploads 						= $this->upload->do_upload('file');
					$data1							= $this->upload->data();
					$nama_upload_datmen = $data1['file_name'];
					$description = $this->input->post('desc_blk');

					if($data1){
						$ar = array(
							'idbdm_blk' => $id,
							'datmen_blk' => $nama_upload_datmen,
							'desc_blk' => $description[$i],
							'idusr_blk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_blockdatmen',$ar);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_blockdatmen','e Pengukuran-0-'.$id,"Add Data Mentah dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		function migrasi_block(){
			$dat['table'] 	= "tb_block";
			$dat['type'] 	  = "multiple";
			$loop = $this->crud_model->get_data($dat);

			foreach ($loop as $data) {
					if($data['petptsl_blk']){
						$ar = array(
							'idbpt_blk' => $data['idblk_blk'],
							'petptsl_blk' => $data['petptsl_blk']
						);
						$simpan = $this->crud_model->input('tb_blockptsl',$ar);
					}
					if($data['petukur_blk']){
						$ar = array(
							'idbuk_blk' => $data['idblk_blk'],
							'petukur_blk' => $data['petukur_blk']
						);
						$simpan = $this->crud_model->input('tb_blockukur',$ar);
					}
					if($data['gu_blk']){
						$ar = array(
							'idbgu_blk' => $data['idblk_blk'],
							'gu_blk' => $data['gu_blk']
						);
						$simpan = $this->crud_model->input('tb_blockgu',$ar);
					}
					if($data['datmen_blk']){
						$ar = array(
							'idbdm_blk' => $data['idblk_blk'],
							'datmen_blk' => $data['datmen_blk']
						);
						$simpan = $this->crud_model->input('tb_blockdatmen',$ar);
					}
			}
		}

		public function petaonline($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['join']['table'] = "tb_block";
			$dat['join']['key'] = "kd_full";
			$dat['join']['ref'] = "idkel_blk";
			$dat['condition']['idblk_blk'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$this->content['peta'] = $hasil;

			$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$hasil['kd_full']),array("Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'],"Studio_5_1/petaonline/".$cari));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->content['load'] = array("studio6/peta");
			$this->load->view('adm',$this->content);
		}

	}
