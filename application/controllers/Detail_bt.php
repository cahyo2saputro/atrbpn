<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Detail_bt extends CI_Controller
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
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Studio 1.1';
			$this->load->view('auth/authorized');
		}

	    public function form_bt()
		{
			$user = $this->auth_model->get_userdata();
			$status_studio  = $this->uri->segment(3);
			if(empty($_GET['id'])){
				$this->content['status']			= "tambah";
				$this->content['link']				= base_url().'detail_bt/tambah_data_bt';

		    	$this->content['data']['title'] 	= "Form Tambah BT";
				$this->content['data']['subtitle'] 	= array(array("",""));

				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($status_studio);
				$this->content['get_data']			= $this->studio_1_1_model->show_edit_studio($status_studio);
			}else{

				$this->content['status']			= "edit";
				$this->content['link']				= base_url().'detail_bt/edit_data_bt';

				$this->content['data']['title'] 	= "Form Edit BT";
				$this->content['data']['subtitle'] 	= array(array("",""));

				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($_GET['id']);
				$this->content['get_data']			= $this->detail_bt_model->show_edit_bt($_GET['id']);

			}
			// GET KELURAHAN
			$user = $this->auth_model->get_userdata();
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['id_kelurahan']);

			$this->content['load'] 				= array("studio_1_1/form_bt");
			$this->load->view('adm',$this->content);
		}

		public function tambah_data_bt()
		{
			$jenis_file = $this->input->post('jenis_upload');
			$nohak = $this->input->post('no_hak');

			$id_kelurahan = $this->input->post('id_kelurahan_real');

			$usr = $this->auth_model->get_userdata();
			$id_usr = $usr['idusr_usr'];

			if(empty($nohak)){
				$msg = false;

			}else if(empty($this->input->post('no_sertifikat'))){
				$msg = false;

			}else if(empty($this->input->post('no_lemari'))){
				$msg = false;

			}else if(empty($this->input->post('no_rak'))){
				$msg = false;

			}else if(empty($this->input->post('no_buku_album'))){
				$msg = false;

			}else if(empty($this->input->post('fisik'))){
				$msg = false;

			}else if(empty($this->input->post('validasi'))){
				$msg = false;

			}else{
    			$array = array(
    					'nosertif_bt'			=> $this->input->post('no_sertifikat'),
    					'nohak_bt'				=> $nohak,
    					'nolem_bt'				=> $this->input->post('no_lemari'),
    					'norak_bt'				=> $this->input->post('no_rak'),
    					'nobalb_bt'			=> $this->input->post('no_buku_album'),
    					'fisik_bt'					=> $this->input->post('fisik'),
    					'validasi_bt'				=> $this->input->post('validasi'),
    					'status_bt'	=>	'1',
    					'idusr_usr' => $id_usr
    			);

    			$bt = $this->detail_bt_model->update_bt($nohak);

    			$simpan = $this->detail_bt_model->simpan_data_bt($array,$nohak);

    		  $sr_bt =  $this->detail_bt_model->sr_bt($nohak);
    			$id_bt = $sr_bt['id_bt'];

					$this->referensi_model->save_logs($id_usr,'input BT','id : '.$id_bt.' no hak :'.$this->input->post('no_hak'),"menambahkan BT dengan rincian ".displayArray($array));

    			$sr = $this->detail_bt_model->sr_hak($nohak);
    			$refile = $sr['no_hak'];

    			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kelurahan);
    			$nma_kel = $sr_kel['nma_kel'];
    			$nma_kec = $sr_kel['nma_kec'];

    			if($jenis_file == "gambar"){

    				$nmfile 					= "BT"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU_TANAH/';
    				$config['upload_path']		= './digitalisasi/';
    				$config['allowed_types']	= 'jpg|png';
    				$config['max_size']			= 1000000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['gambar']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('gambar');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					$array = array(
    						'id_bt'					=> $id_bt,
    						'jenis_bt'					=> $jenis_file,
    						'doc_bt'					=> $nama_upload,
    						'page_bt'					=> $this->input->post('posisi_page'),
    						'statup_bt'	=> '1'
    					);

    					$simpan = $this->detail_bt_model->simpan_data_upload_bt($array);
    				}
    			}else if($jenis_file == "pdf"){

						$directories = "./digitalisasi/".strtoupper($nma_kec)."/".strtoupper($nma_kel)."/BUKU_TANAH";

						if (!file_exists($directories)) {
  						mkdir('./digitalisasi/'.strtoupper($nma_kec).'/'.strtoupper($nma_kel).'/BUKU_TANAH',0777, true);
						}

    				$nmfile 					= "BT"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= $directories;
    				$config['allowed_types']	= 'pdf';
    				$config['max_size']			= 1000000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['pdf']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('pdf');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					// $array = array(
    					// 	'id_bt'					=> $id_bt,
    					// 	'jenis_bt'					=> $jenis_file,
    					// 	'doc_bt'					=> $nama_upload,
    					// 	'page_bt'					=> $this->input->post('posisi_page'),
    					// 	'statup_bt'	=> '1'
    					// );
							//
    					// $simpan = $this->detail_bt_model->simpan_data_upload_bt($array);
    				}
    			}

			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit_data_bt()
		{
			$kode = $this->input->post('id_bt');
			$jenis_file = $this->input->post('jenis_upload');
			$nohak = $this->input->post('no_hak');

			$id_kelurahan = $this->input->post('id_kelurahan_real');

			$usr = $this->auth_model->get_userdata();
			$id_usr = $usr['idusr_usr'];

			$array = array(
					'nosertif_bt'			=> $this->input->post('no_sertifikat'),
					'nohak_bt'				=> $nohak,
					'nolem_bt'				=> $this->input->post('no_lemari'),
					'norak_bt'				=> $this->input->post('no_rak'),
					'nobalb_bt'			=> $this->input->post('no_buku_album'),
					'fisik_bt'					=> $this->input->post('fisik'),
					'validasi_bt'				=> $this->input->post('validasi'),
					'idusr_usr' => $id_usr
			);

			$simpan = $this->detail_bt_model->edit_data_bt($kode,$array);


			$sr_bt = $this->detail_bt_model->sr_bt($nohak);
			$id_bt = $sr_bt['id_bt'];

			$this->referensi_model->save_logs($id_usr,'edit BT','id : '.$id_bt.' no hak :'.$this->input->post('no_hak'),"mengedit digitalisasi BT dengan rincian ".displayArray($array));

			$sr = $this->detail_bt_model->sr_hak($nohak);
    			$refile = $sr['no_hak'];

    			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kelurahan);
    			$nma_kel = $sr_kel['nma_kel'];
    			$nma_kec = $sr_kel['nma_kec'];

    			if($jenis_file == "gambar"){

    				$nmfile 					= "BT"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
    				$config['upload_path']		= './digitalisasi/';
    				$config['allowed_types']	= 'jpg|png';
    				$config['max_size']			= 1000000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['gambar']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('gambar');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					$array = array(
    						'id_bt'					=> $id_bt,
    						'jenis_bt'					=> $jenis_file,
    						'doc_bt'					=> $nama_upload,
    						'page_bt'					=> $this->input->post('posisi_page'),
    						'statup_bt'	=> '1'
    					);

    					$simpan = $this->detail_bt_model->simpan_data_upload_bt($array);
    				}
    			}else if($jenis_file == "pdf"){

    				$nmfile 					= "BT"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$directories = "./digitalisasi/".strtoupper($nma_kec)."/".strtoupper($nma_kel)."/BUKU_TANAH";

						if (!file_exists($directories)) {
  						mkdir('./digitalisasi/'.strtoupper($nma_kec).'/'.strtoupper($nma_kel).'/BUKU_TANAH',0777, true);
						}

						$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= $directories;
    				$config['allowed_types']	= 'pdf';
    				$config['max_size']			= 1000000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['pdf']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('pdf');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					// $array = array(
    					// 	'id_bt'					=> $id_bt,
    					// 	'jenis_bt'					=> $jenis_file,
    					// 	'doc_bt'					=> $nama_upload,
    					// 	'page_bt'					=> $this->input->post('posisi_page'),
    					// 	'statup_bt'	=> '1'
    					// );
							//
    					// $simpan = $this->detail_bt_model->simpan_data_upload_bt($array);
    				}
    			}


			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus_data_bt($kode)
		{
			$hapus = $this->detail_bt_model->hapus_data_bt($kode);
			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function print_bt()
		{
			$page 	= 'print_bt'.time();
			$mpdf 	= new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf']);
			$data 	= $this->load->view('studio_1_1/print_bt', [], TRUE);
			$mpdf->WriteHTML($data);
			$mpdf->Output($page.".pdf","I");

		}
	}
 ?>
