<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Detail_su extends CI_Controller
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

		public function data_su($kode)
	    {
	      $data           = $this->detail_su_model->show_su($kode);
	      $hasil          = array();
	      $result         = array();
	      $nomor          = 0;
	      foreach ($data as $data) {
	        $nomor        = $nomor + 1;

	        if($data->su_gs == 'GS'){
	        	$su_gs = "<button class='btn btn-sm btn-warning'>GS</button>";
	        }else if($data->su_gs == 'SU'){
	        	$su_gs = "<button class='btn btn-sm btn-success'>SU</button>";
	        }

	        if($data->fisik	== 'Ada'){
	        	$fisik	= "<p class='fa fa-check'></p>";
	        }else if($data->fisik == 'Tidak'){
	        	$fisik = "<p class='fa fa-close'></p>";
	        }

	        $hasil[]      = array(
	            'no'        => $nomor,
	            'sertifikat'	=> $data->no_sertifikat,
	            'kelurahan'		=> $data->nama_kelurahan,
	            'su_gs'			=> $su_gs,
	            'no_su'			=> $data->no_su,
	            'th_su'			=> $data->thn_su,
	            'no_lemari'		=> $data->no_lemari,
	            'no_rak'		=> $data->no_rak,
	            'no_buku_album'	=> $data->no_buku_album,
	            'fisik'			=> $fisik,
	            'action'		=> '<div class="btn-group">
									<button id="btn-edit-su" type="button" class="btn btn-warning btn-sm"
												data-id_bt="' 		. $data->id_detail_su 	. '"
												data-no_hak="'		.$data->no_hak 			. '"
									><i class="fa fa-edit"></i></button>
									<button id="btn-hapus-su" type="button" class="btn btn-danger btn-sm"
												data-id="' 		. $data->id_detail_su 	. '"
												data-nama="' 	. $data->no_sertifikat 	. '"
									><i class="fa fa-trash-o"></i></button>
									</div>'
	          );
	      }
	      $result         = array (
	          'aaData'      => $hasil
	        );
	      echo json_encode($result);die();
	    }

		public function form_su()
	    {
	    	$status_studio = $this->uri->segment(3);
	    	if(empty($_GET['id'])){
	    		$this->content['status'] 			= "tambah";
	    		$this->content['link']				= base_url().'detail_su/tambah_data_su';

	    		$this->content['data']['title'] 	= "Form Tambah SU";
					$this->content['data']['subtitle'] 	= array(array("",""));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all($status_studio);
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($status_studio);
				$this->content['get_data']			= $this->studio_1_1_model->show_edit_studio($status_studio);

	    	}else{
	    		$this->content['status']			= "edit";
				$this->content['link']				= base_url().'detail_su/edit_data_su';

				$this->content['data']['title'] 	= "Form Edit SU";
				$this->content['data']['subtitle'] 	= array(array("",""));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all($_GET['id']);
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($_GET['id']);
				$this->content['get_data']			= $this->detail_su_model->show_edit_su($_GET['id']);
	    	}

				// GET KELURAHAN
				$user = $this->auth_model->get_userdata();
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['id_kelurahan']);

	    	$this->content['sugs'] 				= $this->detail_su_model->show_sugs();
			$this->content['load'] 				= array("studio_1_1/form_su");
			$this->load->view('adm',$this->content);
	    }

	    public function tambah_data_su()
		{
			$jenis_file = $this->input->post('jenis_upload');
			$nohak = $this->input->post('no_hak');
			$no_su = $this->input->post('no_su');

			$id_kelurahan = $this->input->post('id_kelurahan_real');
			$thn_su = $this->input->post('thn_su');

			$usr = $this->auth_model->get_userdata();
			$id_usr = $usr['idusr_usr'];


			if(empty($nohak)){
				$msg = false;
			}else if(empty($this->input->post('su_gs'))){
				$msg = false;
			}else if(empty($this->input->post('no_su'))){
				$msg = false;
			}else if(empty($this->input->post('thn_su'))){
				$msg = false;
			}else if(empty($this->input->post('luas_su'))){
				$msg = false;
			}else if(empty($this->input->post('produk_su'))){
				$msg = false;
			}else if(empty($this->input->post('luaspeta_su'))){
				$msg = false;
			}else if(empty($this->input->post('no_lemari'))){
				$msg = false;
			}else if(empty($this->input->post('no_rak'))){
				$msg = false;
			}else if(empty($this->input->post('no_buku_album'))){
				$msg = false;
			}else if(empty($this->input->post('fisik'))){
				$msg = false;
			}else{

    			$array = array(
    					'nohak_su'				=> $nohak,
    					'sugs_su'				=> $this->input->post('su_gs'),
    					'no_su'					=> $this->input->post('no_su'),
    					'thn_su'				=> $this->input->post('thn_su'),
    					'luas_su'				=> $this->input->post('luas_su'),
    					'produk_su'				=> $this->input->post('produk_su'),
    					'luaspeta_su'			=> $this->input->post('luaspeta_su'),
    					'nolem_su'				=> $this->input->post('no_lemari'),
    					'norak_su'				=> $this->input->post('no_rak'),
    					'nobalb_su'				=> $this->input->post('no_buku_album'),
    					'fisik_su'				=> $this->input->post('fisik'),
    					'status_su'	=>	'1',
    					'idusr_usr' => $id_usr
    			);

    			$simpan = $this->detail_su_model->simpan_data_su($array,$nohak);

    			$refile = $id_kelurahan.'_'.$no_su.'_'.$thn_su;

    			$sr_su = $this->detail_su_model->sr_su($nohak);
    			$id_su = $sr_su['id_su'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'Tambah SU','id : '.$id_su.' no hak :'.$nohak,"Menambahkan SU dengan rincian ".displayArray($array));

    			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kelurahan);
    			$nma_kel = $sr_kel['nma_kel'];
    			$nma_kec = $sr_kel['nma_kec'];

    			if($jenis_file == "gambar"){
    				$nmfile 					= "SU"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= './digitalisasi/';
    				$config['allowed_types']	= 'jpg|png';
    				$config['max_size']			= 20000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['gambar']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('gambar');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					$array = array(
    						'id_su'					=> $id_su,
    						'jenis_su'					=> $jenis_file,
    						'doc_su'					=> $nama_upload,
    						'page_su'					=> $this->input->post('posisi_page'),
    						'statup_su'	=> '1'
    					);

    					$simpan = $this->detail_su_model->simpan_data_upload_su($array);
    				}
    			}else if($jenis_file == "pdf"){
						$directories = "./digitalisasi/".strtoupper($nma_kec)."/".strtoupper($nma_kel)."/SURAT_UKUR";

						if (!file_exists($directories)) {
  						mkdir('./digitalisasi/'.strtoupper($nma_kec).'/'.strtoupper($nma_kel).'/SURAT_UKUR',0777, true);
						}

    				$nmfile 					= "SU"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= $directories;
    				$config['allowed_types']	= 'pdf';
    				$config['max_size']			= 20000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['pdf']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('pdf');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					// $array = array(
    					// 	'id_su'					=> $id_su,
    					// 	'jenis_su'					=> $jenis_file,
    					// 	'doc_su'					=> $nama_upload,
    					// 	'page_su'					=> $this->input->post('posisi_page'),
    					// 	'statup_su'	=> '1'
    					// );
							//
    					// $simpan = $this->detail_su_model->simpan_data_upload_su($array);
    				}
    			}
			}
			$this->referensi_model->save_logs($id_usr,'tambah SU','id : '.$id_su.' no hak :'.$nohak,"menambah digitalisasi SU dengan rincian ".print_r($array));
			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		 public function edit_data_su()
		{
			$kode = $this->input->post('id_detail_su');
			$jenis_file = $this->input->post('jenis_upload');
			$nohak = $this->input->post('no_hak');
			$thn_su = $this->input->post('thn_su');
			$no_su = $this->input->post('no_su');

			$id_kelurahan = $this->input->post('id_kelurahan_real');

			$usr = $this->auth_model->get_userdata();
			$id_usr = $usr['idusr_usr'];

			$array = array(
					'nohak_su'				=> $nohak,
					'sugs_su'				=> $this->input->post('su_gs'),
					'no_su'					=> $this->input->post('no_su'),
					'thn_su'				=> $this->input->post('thn_su'),
					'luas_su'				=> $this->input->post('luas_su'),
					'produk_su'				=> $this->input->post('produk_su'),
					'luaspeta_su'			=> $this->input->post('luaspeta_su'),
					'nolem_su'				=> $this->input->post('no_lemari'),
					'norak_su'				=> $this->input->post('no_rak'),
					'nobalb_su'				=> $this->input->post('no_buku_album'),
					'fisik_su'				=> $this->input->post('fisik'),
					'status_su'	=>	'1',
					'idusr_usr' => $id_usr
			);

			$simpan = $this->detail_su_model->edit_data_su($kode,$array);

    			$refile = $id_kelurahan.'_'.$no_su.'_'.$thn_su;

    			$sr_su = $this->detail_su_model->sr_su($nohak);
    			$id_su = $sr_su['id_su'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'Edit SU','id : '.$id_su.' no hak :'.$nohak,"Mengedit SU dengan rincian ".displayArray($array));

    			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kelurahan);
    			$nma_kel = $sr_kel['nma_kel'];
    			$nma_kec = $sr_kel['nma_kec'];

    			if($jenis_file == "gambar"){
    				$nmfile 					= "SU"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= './digitalisasi/';
    				$config['allowed_types']	= 'jpg|png';
    				$config['max_size']			= 20000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['gambar']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('gambar');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					$array = array(
    						'id_su'					=> $id_su,
    						'jenis_su'					=> $jenis_file,
    						'doc_su'					=> $nama_upload,
    						'page_su'					=> $this->input->post('posisi_page'),
    						'statup_su'	=> '1'
    					);

    					$simpan = $this->detail_su_model->simpan_data_upload_su($array);
    				}
    			}else if($jenis_file == "pdf"){
						$directories = "./digitalisasi/".strtoupper($nma_kec)."/".strtoupper($nma_kel)."/SURAT_UKUR";

						if (!file_exists($directories)) {
  						mkdir('./digitalisasi/'.strtoupper($nma_kec).'/'.strtoupper($nma_kel).'/SURAT_UKUR',0777, true);
						}
    				$nmfile 					= "SU"."_".$refile; 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= $directories;
    				$config['allowed_types']	= 'pdf';
    				$config['max_size']			= 20000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['pdf']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('pdf');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					// $array = array(
    					// 	'id_su'					=> $id_su,
    					// 	'jenis_su'					=> $jenis_file,
    					// 	'doc_su'					=> $nama_upload,
    					// 	'page_su'					=> $this->input->post('posisi_page'),
    					// 	'statup_su'	=> '1'
    					// );
							//
    					// $simpan = $this->detail_su_model->simpan_data_upload_su($array);
    				}
    			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus_data_su($kode)
		{
			$hapus = $this->detail_su_model->hapus_data_su($kode);
			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function print_su()
		{
			$page 	= 'print_su'.time();
			$mpdf 	= new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf']);
			$data 	= $this->load->view('studio_1_1/print_su', [], TRUE);
			$mpdf->WriteHTML($data);
			$mpdf->Output($page.".pdf","I");
		}
	}
 ?>
