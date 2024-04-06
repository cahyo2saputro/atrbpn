<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Detail_gu extends CI_Controller
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

		public function data_gu($kode)
	    {
	      $data           = $this->detail_gu_model->show_gu($kode);
	      $count_document = $this->detail_gu_model->count_doc($kode);
	      $hasil          = array();
	      $result         = array();
	      $nomor          = 0;
	      foreach ($data as $data) {
	        $nomor        = $nomor + 1;

	        if($count_document > 0){
	        	$document = "<p class='fa fa-check'></p>";
	        }else{
	        	$document = "<p class='fa fa-close'></p>";
	        }

	        if($data->fisik	== 'Ada'){
	        	$fisik	= "<p class='fa fa-check'></p>";
	        }else if($data->fisik == 'Tidak'){
	        	$fisik = "<p class='fa fa-close'></p>";
	        }

	        $hasil[]      = array(
	            'no'        		=> $nomor,
	            'th_gu'				=> $data->thn_gu,
	            'no_gu'				=> $data->no_gu,
	            'no_su'				=> '<div class="btn-group">
	            							<button class="btn btn-sm" style="background-color: #34495e;color:#fff;">'.$data->thn_su.'</button>
	            							<button class="btn btn-sm" style="background-color: transparent;color:#000;">'.$data->no_su_1.'</button>
	            							<button class="btn btn-sm" style="background-color: transparent;color:#000;">'.$data->no_su_2.'</button>
	            						</div>',
	            'nama_kelurahan'	=> $data->nama_kelurahan,
	            'no_lemari'			=> $data->no_lemari,
	            'no_rak'			=> $data->no_rak,
	            'no_buku_album'		=> $data->no_buku_album,
	            'fisik'				=> $fisik,
	            'document'			=> $document,
	            'action'			=> '<div class="btn-group">
											<button id="btn-edit-gu" type="button" class="btn btn-warning btn-sm"
														data-id_bt="' 		. $data->id_detail_gu 	. '"
											><i class="fa fa-edit"></i></button>
											<button id="btn-hapus-gu" type="button" class="btn btn-danger btn-sm"
														data-id="' 		. $data->id_detail_gu 	. '"
														data-no_gu="' 	. $data->no_gu 	. '"
											><i class="fa fa-trash-o"></i></button>
										</div>'
	          );
	      }
	      $result         = array (
	          'aaData'      => $hasil
	        );
	      echo json_encode($result);die();
	    }

		 public function form_gu()
		{
			$status_studio  = $this->uri->segment(3);
			if(empty($_GET['id'])){
				$this->content['status']			= "tambah";
				$this->content['link']				= base_url().'detail_gu/tambah_data_gu';

		    	$this->content['data']['title'] 	= "Form Tambah GU";
				$this->content['data']['subtitle'] 	= array(array("",""));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all($status_studio);
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($status_studio);
				$this->content['get_data']			= $this->detail_su_model->show_edit_su($status_studio);
			}else{
				$this->content['status']			= "edit";
				$this->content['link']				= base_url().'detail_gu/edit_data_gu';

				$this->content['data']['title'] 	= "Form Edit GU";
				$this->content['data']['subtitle'] 	= array(array("",""));

				/*$this->content['kecamatan']			= $this->detail_gu_model->data_kecamatan($_GET['id']);*/
				$this->content['kelurahan']			= $this->detail_gu_model->data_kelurahan($_GET['id']);
				$this->content['get_data']			= $this->detail_gu_model->show_edit_gu($_GET['id']);
			}
			// GET KELURAHAN
			$user = $this->auth_model->get_userdata();
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['id_kelurahan']);

			$this->content['load'] 				= array("studio_1_1/form_gu");
			$this->load->view('adm',$this->content);
		}

		public function tambah_data_gu()
		{
			$no_gu = $this->input->post('no_gu');
			$jenis_file = $this->input->post('jenis_upload');

			$id_kelurahan = $this->input->post('id_kelurahan_real');
			/*$id_kecamatan = $this->input->post('id_kecamatan_real');*/

			$usr = $this->auth_model->get_userdata();
			$id_usr = $usr['idusr_usr'];

			if(empty($no_gu)){
				$msg = false;
			}else if(empty($this->input->post('thn_gu'))){
				$msg = false;
			}else if(empty($this->input->post('no_su_1'))){
				$msg = false;
			}else if(empty($this->input->post('no_su_2'))){
				$msg = false;
			}else if(empty($this->input->post('thn_su'))){
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
    					'thn_gu'				=> $this->input->post('thn_gu'),
    					'no_gu'					=> $no_gu,
    					'nosu1_gu'				=> $this->input->post('no_su_1'),
    					'nosu2_gu'				=> $this->input->post('no_su_2'),
    					'thnsu_gu'				=> $this->input->post('thn_su'),
    					/*'nib'					=> $this->input->post('nib'),*/
    					'nolem_gu'				=> $this->input->post('no_lemari'),
    					'norak_gu'				=> $this->input->post('no_rak'),
    					'nobalb_gu'				=> $this->input->post('no_buku_album'),
    					'fisik_gu'				=> $this->input->post('fisik'),
    					'status_gu'	=>	'1',
    					'idusr_usr'	=> $id_usr
    			);

    			$simpan = $this->detail_gu_model->simpan_data_gu($array);

    		    $sr_gu = $this->detail_gu_model->sr_gu($no_gu);
    			$id_gu = $sr_gu['id_gu'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'input GU','id : '.$id_gu.' no SU 1 :'.$this->input->post('no_su_1'),"menambahkan GU dengan rincian ".displayArray($array));

    			if($jenis_file == "gambar"){
    				$nmfile 					= "GU"."_"."4"."_"."54"."_".$id_kelurahan."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    				$config['upload_path']		= './images/document/';
    				$config['allowed_types']	= 'jpg|png';
    				$config['max_size']			= 20000;
    				$this->upload->initialize($config);

    				if (empty($_FILES['foto']['name'])) {

    				} else {
    					$upload 				= $this->upload->do_upload('foto');
    					$data					= $this->upload->data();
    					$nama_upload 			= $data['file_name'];

    					$array = array(
    						'id_gu'					=> $id_gu,
    						'jenis_gu'					=> $jenis_file,
    						'doc_gu'					=> $nama_upload,
    						'page_gu'					=> $this->input->post('posisi_page'),
    						'statup_gu'	=> '1'
    					);

    					$simpan = $this->detail_gu_model->simpan_data_upload_gu($array);
    				}
    			}else if($jenis_file == "camera"){
    				$image = $this->input->post('url_camera');//pengambilan url camera data
    				$image = str_replace('data:image/jpeg;base64,','', $image);//penggunaan url camera data
    				$image = base64_decode($image);//perubahan array pada url camera data menjadi base64
    				$filename = 'GU'.'_'.'4'.'_'.'54'.'_'.$id_kelurahan.'_'.$no_gu.'_'.time().'.png';
    				file_put_contents(FCPATH.'./images/document/'.$filename,$image);//disimpan menjadi gambar dan di taruh
    				$array = array(
    						'id_gu' 			=> $id_gu,
    						'jenis_gu'					=> $jenis_file,
    						'doc_gu'					=> $filename,
    						'page_gu'					=> $this->input->post('posisi_page'),
    						'statup_gu'	=> '1'
    					);

    				$simpan = $this->detail_gu_model->simpan_data_upload_gu($array);
    			}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit_data_gu()
		{
			$no_gu = $this->input->post('no_gu');
			$jenis_file = $this->input->post('jenis_upload');
			$kode = $this->input->post('id_detail_gu');

			$id_kelurahan = $this->input->post('id_kelurahan_real');
		/*	$id_kecamatan = $this->input->post('id_kecamatan_real');*/

			$array = array(
					'thn_gu'				=> $this->input->post('thn_gu'),
					'no_gu'					=> $no_gu,
					'nosu1_gu'				=> $this->input->post('no_su_1'),
					'nosu2_gu'				=> $this->input->post('no_su_2'),
					'thnsu_gu'				=> $this->input->post('thn_su'),
					/*'nib'					=> $this->input->post('nib'),*/
					'nolem_gu'				=> $this->input->post('no_lemari'),
					'norak_gu'				=> $this->input->post('no_rak'),
					'nobalb_gu'				=> $this->input->post('no_buku_album'),
					'fisik_gu'				=> $this->input->post('fisik'),
					'status_gu'	=>	'1'
			);

			$simpan = $this->detail_gu_model->edit_data_gu($kode,$array);

			$sr_gu = $this->detail_gu_model->sr_gu($no_gu);
			$id_gu = $sr_gu['id_gu'];

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'edit GU','id : '.$id_gu.' no SU 1 :'.$this->input->post('no_su_1'),"Mengedit GU dengan rincian ".displayArray($array));

			if($jenis_file == "gambar"){
				$nmfile 					= "GU"."_"."4"."_"."54"."_".$id_kelurahan."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
				$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
				$config['upload_path']		= './images/document/';
				$config['allowed_types']	= 'jpg|png';
				$config['max_size']			= 20000;
				$this->upload->initialize($config);

				if (empty($_FILES['foto']['name'])) {

				} else {
					$upload 				= $this->upload->do_upload('foto');
					$data					= $this->upload->data();
					$nama_upload 			= $data['file_name'];

					$array = array(
						'id_gu'					=> $id_gu,
						'jenis_gu'					=> $jenis_file,
						'doc_gu'					=> $nama_upload,
						'page_gu'					=> $this->input->post('posisi_page'),
						'statup_gu'	=> '1'
					);

					$simpan = $this->detail_gu_model->simpan_data_upload_gu($array);
				}
			}else if($jenis_file == "camera"){
				$image = $this->input->post('url_camera');//pengambilan url camera data
				$image = str_replace('data:image/jpeg;base64,','', $image);//penggunaan url camera data
				$image = base64_decode($image);//perubahan array pada url camera data menjadi base64
				$filename =  'GU'.'_'.'4'.'_'.'54'.'_'.$id_kelurahan.'_'.$no_gu.'_'.time().'.png';
				file_put_contents(FCPATH.'./images/document/'.$filename,$image);//disimpan menjadi gambar dan di taruh
				$array = array(
						'id_gu' 				=> $id_gu,
						'jenis_gu'					=> $jenis_file,
						'doc_gu'					=> $filename,
						'page_gu'					=> $this->input->post('posisi_page'),
						'statup_gu'	=> '1'
					);

				$simpan = $this->detail_gu_model->simpan_data_upload_gu($array);
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus_data_gu($kode)
		{
			$hapus = $this->detail_gu_model->hapus_data_gu($kode);
			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function print_gu()
		{
			$page 	= 'print_gu'.time();
			$mpdf 	= new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf']);
			$data 	= $this->load->view('studio_1_1/print_gu', [], TRUE);
			$mpdf->WriteHTML($data);
			$mpdf->Output($page.".pdf","I");

		}
	}
 ?>
