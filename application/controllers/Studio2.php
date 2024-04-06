<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio2 extends CI_Controller
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
			$this->content['data']['title_page'] = '';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = " E-Data";
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$cari = $this->input->get('filter');
			$carikelurahan=$this->input->get('filterkelurahan');

			if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
				$idusr = $user['idusr_usr'];
				$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
			}else{
				$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
			}


			if($cari!=0 && $carikelurahan==0){
				if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
					$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].") AND kdkec_kel='.$cari.'";
				}else{
					$dat['condition']['kdkec_kel'] = $cari;
				}
			}else if($cari!=0 && $carikelurahan!=0){
				$dat['condition']['kdkec_kel'] = $cari;
				$dat['condition']['kd_kel'] = $carikelurahan;
			}else if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
				$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].")";
			}else if($cari==0 && $carikelurahan!=0){
				$dat['condition']['kd_full'] = $carikelurahan;
			}
			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['column'] = "COUNT(kdkec_kel) as jumlah";
			$hasil = $this->crud_model->get_data($dat);
			$t_data=$hasil['jumlah'];

			$config['base_url'] = base_url().'Studio2/index/';
			$config['total_rows'] = $t_data;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 10;
			$config['uri_segment'] = 3;

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


			$this->content['studio'] = $this->studio2_model->show_kecamatan($config['per_page'],$from,$cari,$carikelurahan);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio2/data_studio_2");
			$this->load->view('adm',$this->content);
		}

		public function BPPKAD()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = " E-Data BPPKAD";
			$this->content['data']['subtitle'] = array(array("e-data BPPKAD","Studio2/BPPKAD"));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$cari = $this->input->get('filter');
			$carikelurahan=$this->input->get('filterkelurahan');

			if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
				$idusr = $user['idusr_usr'];
				$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
			}else{
				$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
			}


			if($cari!=0 && $carikelurahan==0){
				if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
					$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].") AND kdkec_kel='.$cari.'";
				}else{
					$dat['condition']['kdkec_kel'] = $cari;
				}
			}else if($cari!=0 && $carikelurahan!=0){
				$dat['condition']['kdkec_kel'] = $cari;
				$dat['condition']['kd_kel'] = $carikelurahan;
			}else if($user['level_usr'] != "1" && $user['level_usr'] != "3"){
				$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].")";
			}else if($cari==0 && $carikelurahan!=0){
				$dat['condition']['kd_full'] = $carikelurahan;
			}
			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['column'] = "COUNT(kdkec_kel) as jumlah";
			$hasil = $this->crud_model->get_data($dat);
			$t_data=$hasil['jumlah'];

			$config['base_url'] = base_url().'Studio2/bppkad/';
			$config['total_rows'] = $t_data;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 10;
			$config['uri_segment'] = 3;

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


			$this->content['studio'] = $this->studio2_model->show_kecamatan($config['per_page'],$from,$cari,$carikelurahan);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio2/data_studiobppkad_2");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_analog($id)
		{
			$user = $this->auth_model->get_userdata();

			$count = count($_FILES['analog_pal']['name']);

			for($i=0;$i<$count;$i++){
					$_FILES['file']['name'] = $_FILES['analog_pal']['name'][$i];
          $_FILES['file']['type'] = $_FILES['analog_pal']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['analog_pal']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['analog_pal']['error'][$i];
          $_FILES['file']['size'] = $_FILES['analog_pal']['size'][$i];

					$file = explode(".",$_FILES["analog_pal"]["name"][$i]);
	        $sum = count($file);
					$nmfile1 					= "ANALOG_".$id."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
					$config1['upload_path']		= './PETA/PETA_ANALOG/';
					$config1['allowed_types']	= '*';
					$this->upload->initialize($config1);

					$uploads 				= $this->upload->do_upload('file');
					$data1					= $this->upload->data();
					$nama_upload_analog 		= $data1['file_name'];
					$description = $this->input->post('desc_pal');

					// UPLOAD DIFFERENT SERVER
					// $fileName = 'image.jpg';

					//File path at local server
					// $source = './PETA/PETA_ANALOG/'.$nama_upload_analog;
					//
					// //Load codeigniter FTP class
					// $this->load->library('ftp');
					//
					// //FTP configuration
					// $ftp_config['hostname'] = 'sg.oceanet.co.id';
					// $ftp_config['username'] = 'oceanet';
					// $ftp_config['password'] = 'oc34n3t';
					// $ftp_config['debug']    = TRUE;
					//
					// //Connect to the remote server
					// $this->ftp->connect($ftp_config);
					//
					// //File upload path of remote server
					// $destination = '/BACKUP/'.$nama_upload_analog;
					//
					// //Upload file to the remote server
					// $this->ftp->upload($source, ".".$destination);
					//
					// //Close FTP connection
					// $this->ftp->close();

					if($data1){
						$ar = array(
							'idkel_pal' => $id,
							'analog_pal' => $nama_upload_analog,
							'desc_pal' => $description[$i],
							'idusr_pal' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);

						$simpan = $this->crud_model->input('tb_petaanalog',$ar);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_petaanalog','e Walidata-0-'.$id,"Add PETA ANALOG dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_peta_kerja($id)
		{
			$user = $this->auth_model->get_userdata();
			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id);
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

			  $nmfile 					= "PETA_KERJA"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU_TANAH/';
				$config['upload_path']		= './PETA/PETA_KERJA/';
    		//$config['upload_path']		= $image_path;
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('petkerja_pt');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if (empty($qw['idpet_pt'])) {
	    		$ar = array(
	    			'idkel_pt' => $id,
	    			'petkerja_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
	    		);

	    		$simpan = $this->studio2_model->simpan($ar);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$insert_id,"Simpan Peta Kerja dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
	    			'petkerja_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr'],
	    		);

	    		$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$qw['idpet_pt'],"Edit Peta Kerja dengan rincian ".displayArray($ar));
    		}


    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_penduduk($id)
		{
			$user = $this->auth_model->get_userdata();
			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id);
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

			  $nmfile 					= "PENDUDUK"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 			
			$config['upload_path']		= './DATA/PENDUDUK/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('petkkp_pt');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if (empty($qw['idpet_pt'])) {
	    		$ar = array(
	    			'idkel_pt' => $id,
	    			'penduduk_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
	    		);

	    		$simpan = $this->studio2_model->simpan($ar);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$insert_id,"Simpan Data Penduduk dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
	    			'penduduk_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr'],
	    		);

	    		$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$qw['idpet_pt'],"Edit Data Penduduk dengan rincian ".displayArray($ar));
    		}


    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_peta_tataruang($id)
		{
			$user = $this->auth_model->get_userdata();


			  $nmfile 					= "PETA_TATARUANG_".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
				$config['upload_path']		= './PETA/PETA_TATARUANG/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('pettataruang_pt');
    		$data					  = $this->upload->data();
    		$nama_upload 	  = $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if (empty($qw['idpet_pt'])) {
	    		$ar = array(
	    			'idkel_pt' => $id,
	    			'pettr_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
	    		);

	    		$simpan = $this->studio2_model->simpan($ar);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Peta Tata Ruang dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
	    			'pettr_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr'],
	    		);

	    		$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Peta Tata Ruang dengan rincian ".displayArray($ar));
    		}


    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_peta_tataruangonline($id)
		{
			$user = $this->auth_model->get_userdata();


			  $nmfile 					= "PETA_TATARUANGONLINE_".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
				$config['upload_path']		= './PETA/PETA_TATARUANGONLINE/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('pettataruang_pt');
    		$data					  = $this->upload->data();
    		$nama_upload 	  = $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if (empty($qw['idpet_pt'])) {
	    		$ar = array(
	    			'idkel_pt' => $id,
	    			'pettronline_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
	    		);

	    		$simpan = $this->studio2_model->simpan($ar);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Peta TR ONLINE dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
	    			'pettronline_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr'],
	    		);

	    		$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Peta TR ONLINE dengan rincian ".displayArray($ar));
    		}


    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_peta_kkp($id)
		{
			$user = $this->auth_model->get_userdata();

			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id);
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

				$nmfile 					= "PETA_KKP"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU_TANAH/';
    		$config['upload_path']		= './PETA/PETA_KKP/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('petkkp_pt');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if (empty($qw['idpet_pt'])) {
	    		$ar = array(
	    			'idkel_pt' => $id,
	    			'petkkp_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
	    		);

	    		$simpan = $this->studio2_model->simpan($ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$insert_id,"Simpan Peta KKP dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
	    			'petkkp_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr']
	    		);

	    		$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-'.$nma_kel.'-'.$qw['idpet_pt'],"Edit Peta KKP dengan rincian ".displayArray($ar));
    		}


    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_peta_online($id)
		{
				$user = $this->auth_model->get_userdata();

				$nmfile 					= "PETA_ONLINE_".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		$config['upload_path']		= './PETA/PETA_ONLINE/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('petkkp_pt');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if(empty($qw['idpet_pt'])){
    			$ar = array(
	    			'idkel_pt' => $id,
	    			'petonline_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
    			);
    			$simpan = $this->studio2_model->simpan($ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Peta Online dengan rincian ".displayArray($ar));

    		}else{
    			$ar = array(
    				'petonline_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr']
    			);
    			$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Peta Online dengan rincian ".displayArray($ar));
    		}

    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_dhkp($id)
		{
			$user = $this->auth_model->get_userdata();

				$nmfile 					= "DHKP".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		$config['upload_path']		= './filelibrary/dhkp/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('dhkp');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if(empty($qw['idpet_pt']) && $upload){
    			$ar = array(
	    			'idkel_pt' => $id,
	    			'datadhk_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
    			);
    			$simpan = $this->studio2_model->simpan($ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Data DHKP dengan rincian ".displayArray($ar));
    		}else if($upload){
    			$ar = array(
    				'datadhk_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr']
    			);
    			$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Data DHKP dengan rincian ".displayArray($ar));
    		}

    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_di208($id)
		{
			$user = $this->auth_model->get_userdata();

				$nmfile 					= "DI208".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		$config['upload_path']		= './filelibrary/di208/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('dhkp');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if(empty($qw['idpet_pt']) && $upload){
    			$ar = array(
	    			'idkel_pt' => $id,
	    			'datadi208_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
    			);
    			$simpan = $this->studio2_model->simpan($ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Data DI 208 dengan rincian ".displayArray($ar));
    		}else if($upload){
    			$ar = array(
    				'datadi208_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr']
    			);
    			$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Data DI 208 dengan rincian ".displayArray($ar));
    		}

    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function simpan_k4($id)
		{
			$user = $this->auth_model->get_userdata();

				$nmfile 					= "K4".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config['file_name'] 		= $nmfile; 				//nama yang terupload nantinya
    		$config['upload_path']		= './filelibrary/k4/';
    		$config['allowed_types']	= '*';
    		$this->upload->initialize($config);

    		$upload 				= $this->upload->do_upload('datak4');
    		$data					= $this->upload->data();
    		$nama_upload 			= $data['file_name'];

    		$qw = $this->db->query("SELECT idpet_pt FROM tb_peta WHERE idkel_pt = '$id'")->row_array();

    		if(empty($qw['idpet_pt']) && $upload){
    			$ar = array(
	    			'idkel_pt' => $id,
	    			'datak4_pt' => $nama_upload,
	    			'status_pt' => '1',
						'idusr_pt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
    			);
    			$simpan = $this->studio2_model->simpan($ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$insert_id,"Simpan Data K4 dengan rincian ".displayArray($ar));
    		}else if($upload){
    			$ar = array(
    				'datak4_pt' => $nama_upload,
						'idusr_pt' => $user['idusr_usr']
    			);
    			$simpan = $this->studio2_model->edit($qw['idpet_pt'],$ar);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_peta','e Data BPN-0-'.$qw['idpet_pt'],"Edit Data K4 dengan rincian ".displayArray($ar));
    		}

    		if($simpan){
    			$msg = true;
    		}
    		echo json_encode($msg);die();
		}

		public function petaonline($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['condition']['kd_full'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$this->content['desa'] = $hasil;

			$peta['table'] = "tb_peta";
			$peta['type'] = "single";
			$peta['condition']['idkel_pt'] = $cari;
			$this->content['peta'] = $this->crud_model->get_data($peta);

			$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-data BPPKAD","Studio2/bppkad"),array("Peta Online ".$hasil['nma_kel'],"Studio2/petaonline/".$cari));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->content['load'] = array("studio2/peta");
			$this->load->view('adm',$this->content);
		}

		public function datanib($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['condition']['kd_full'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$peta['table'] = "tb_peta";
			$peta['type'] = "single";
			$peta['condition']['idkel_pt'] = $cari;
			$peta = $this->crud_model->get_data($peta);

			$this->content['data']['title'] = "Data NIB ".$hasil['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-data BPPKAD","Studio2/bppkad"),array("Peta NIB ".$hasil['nma_kel'],"Studio2/datanib/".$cari));

			$from = $from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

			$str = file_get_contents(base_url().'/PETA/PETA_ONLINE/'.$peta['petonline_pt']);

			$array = json_decode($str, true);

			if($this->input->get('nib')){
				$jmrow = 1;
			}else{
				$jmrow = count($array['features']);
			}

			$config['base_url'] = base_url().'Studio2/datanib/'.$cari.'/';
			$config['total_rows'] = $jmrow;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 10;
			$config['uri_segment'] = 4;

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

			$data = array();
			if($this->input->get('nib')){
				for($i=0;$i<count($array['features']);$i++){
					if(!empty($array['features'][$i]['properties']['NIB']) && $array['features'][$i]['properties']['NIB']==$this->input->get('nib')){
							array_push($data, $array['features'][$i]['properties']['NIB']);
					}
				}
			}else{
				for($i=$from;$i<($from+10);$i++){
					if(!empty($array['features'][$i]['properties']['NIB'])){
							array_push($data, $array['features'][$i]['properties']['NIB']);
					}
				}
			}


			$this->content['studio'] = $data;

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio2/datanib");
			$this->load->view('adm',$this->content);
		}

		public function exportdatanib($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['join']['table'] = "ms_kecamatan";
			$dat['join']['key'] = "kd_kec";
			$dat['join']['ref'] = "kdkec_kel";
			$dat['type'] = "single";
			$dat['condition']['kd_full'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$peta['table'] = "tb_peta";
			$peta['type'] = "single";
			$peta['condition']['idkel_pt'] = $cari;
			$peta = $this->crud_model->get_data($peta);


			$str = file_get_contents(base_url().'/PETA/PETA_ONLINE/'.$peta['petonline_pt']);

			$array = json_decode($str, true);


			$data = array();

				for($i=0;$i<count($array['features']);$i++){
					if(!empty($array['features'][$i]['properties']['NIB'])){
							array_push($data, $array['features'][$i]['properties']['NIB']);
					}
				}

			$this->content['studio'] = $data;
			$this->content['kelurahan'] = $hasil;

			$this->load->view('studio2/export',$this->content);
		}

		public function cari_peta_ptsl($id)
		{
			$data = $this->studio_2_1_model->sr_peta_ptsl($id);
			echo json_encode($data);die();
		}

		public function repairblock()
		{
			$dat['table'] = "tb_block";
      $dat['type'] = "multiple";
			$dat['column'] = "idblk_blk,nama_blk";
      $hasil = $this->crud_model->get_data($dat);

			foreach($hasil as $dd){
				$ex=explode(' ',$dd['nama_blk']);
				$newname=str_pad($ex[1],3,'0',STR_PAD_LEFT);
				$dataarray = array(
					'nama_blk' => $newname
				);
				$simpan = $this->crud_model->update('tb_block',$dataarray,array('idblk_blk'=>$dd['idblk_blk']));
			}
		}

		function import_ptsl($kode)
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Data DHKP";
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Import DHKP","Studio2/import_ptsl/".$kode));

			if ($this->input->post()) {
					$this->load->library("Excel/PHPExcel");
					$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
					//$objPHPExcel = PHPExcel_IOFactory::load("./filelibrary/dhkp/".$kode);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$c = 7;$no=1;
					// READ IMPORT
					while(!empty($sheetData[$c]["A"])){

							if (!empty($sheetData[$c]["A"])) {

								$nop = str_replace(".","",$sheetData[$c]["A"]);

								$nop = str_replace(" ","",$nop);

								$kecamatan = substr($nop,4,3);
								$kelurahan = substr($nop,7,3);
								$blok = substr($nop,10,3);
								$nop = substr($nop,13,5);

								$dat['table'] 	= "ms_kelurahan";
								$dat['type'] 	  = "single";
								$dat['join']['table'] = "ms_kecamatan";
								$dat['join']['key'] 	= "kd_kec";
								$dat['join']['ref']	= "kdkec_kel";
								$dat['condition']['kdpbb_kec']	= $kecamatan;
								$dat['condition']['kdpbb_kel']	= $kelurahan;
								$exist = $this->crud_model->get_data($dat);

								if($exist){

									$block['table'] 	= "tb_block";
									$block['type'] 	  = "single";
									$block['condition']['nama_blk']	= $blok;
									$block['condition']['idkel_blk']	= $exist['kd_full'];
									$block['condition']['status_blk']	= 1;
									$cekblok = $this->crud_model->get_data($block);

									if(!$cekblok){

										//INPUT BLOK DULU BARU PENDUDUK DAN PTSL
										$datblok ['idkel_blk']    			 = $exist["kd_full"];
										$datblok ['nama_blk']    			   = $blok;
										$datblok ['status_blk']    			 = 1;
										$datblok ['idusr_blk']    			 = $user['idusr_usr'];
										$datblok ['create_at']         = date('Y-m-d H:i:s');

										$inputblok = $this->crud_model->input("tb_block",$datblok);
										$insert_blok = $this->db->insert_id();
										if($inputblok){
											echo "<span style='color:#27ae60'>Data Blok ".$blok." berhasil diinput</span><br>";
										}else{
											echo "<span style='color:#c0392b'>Data Blok ".$blok." gagal diinput</span><br>";
										}
									}else{
										echo "<span style='color:#c0392b'>Data Blok ".$blok." sudah ada</span><br>";
										$insert_blok = $cekblok['idblk_blk'];
									}

									//CEK DHKP
									$cekdhkp['table'] 	= "tb_dhkp";
									$cekdhkp['type'] 	  = "single";
									$cekdhkp['condition']['idblk_dhkp']	  = $insert_blok;
									$cekdhkp['condition']['nosppt_dhkp']	= $nop;
									$dcek = $this->crud_model->get_data($cekdhkp);

									if(!$dcek){
										//INPUT DHKP
										$datptsl ['idblk_dhkp']    			   = $insert_blok;
										$datptsl ['nama_dhkp']  	  			 = $sheetData[$c]["D"];
										$datptsl ['nosppt_dhkp']    			 = $nop;
										$datptsl ['awpsppt_dhkp']    			 = $sheetData[$c]["E"];
										$datptsl ['aopsppt_dhkp']    			 = $sheetData[$c]["F"];
										$datptsl ['luassppt_dhkp']    		 = $sheetData[$c]["G"];
										$datptsl ['luasbangunan_dhkp']     = $sheetData[$c]["H"];
										$datptsl ['njopsppt_dhkp']    		 = $sheetData[$c]["J"];
										$datptsl ['tahun_dhkp']    				 = $sheetData[$c]["I"];
										$datptsl ['create_at']     	       = date('Y-m-d H:i:s');

										$inputptsl = $this->crud_model->input("tb_dhkp",$datptsl);

										if($inputptsl){
											echo "<span style='color:#27ae60'>no ".$no.". Data DHKP dengan NOP ".$sheetData[$c]["A"]." berhasil diinput</span><br>";
										}else{
											echo "<span style='color:#c0392b'>no ".$no.". Data DHKP dengan NOP ".$sheetData[$c]["A"]." gagal diinput</span><br>";
										}
									}else{
										$updatptsl ['nama_dhkp']  	  			 = $sheetData[$c]["D"];
										$updatptsl ['awpsppt_dhkp']    			 = $sheetData[$c]["E"];
										$updatptsl ['aopsppt_dhkp']    			 = $sheetData[$c]["F"];
										$updatptsl ['luassppt_dhkp']    		 = $sheetData[$c]["G"];
										$updatptsl ['luasbangunan_dhkp']     = $sheetData[$c]["H"];
										$updatptsl ['njopsppt_dhkp']    		 = $sheetData[$c]["J"];
										$updatptsl ['tahun_dhkp']    				 = $sheetData[$c]["I"];
										$updateptsl = $this->crud_model->update('tb_dhkp',$updatptsl,array('idblk_dhkp'=>$insert_blok,'nosppt_dhkp'=>$nop));
										echo "<span style='color:#d35400'>no ".$no.". Data DHKP dengan NOP ".$sheetData[$c]["A"]." sudah diupdate </span><br>";
									}

									}
								}else{
									echo "<span style='color:#c0392b'>Data NOP ".$sheetData[$c]["A"]." gagal diinput, Kelurahan tidak tersedia</span><br>";
								}

							$c++;
							$no++;
					}
				}else{
				$this->content['file'] = base_url().'Studio2/sample_dhkp/'.$kode;
				$this->content['load'] = array("Studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

		function import_di208($kode)
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Data DI 208";
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Import DI 208","Studio2/import_di208/".$kode));

			if ($this->input->post()) {
					$this->load->library("Excel/PHPExcel");
					$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$c = 2;$no=1;
					// READ IMPORT
					while(!empty($sheetData[$c]["A"])){

							if (!empty($sheetData[$c]["A"])) {

    						$nomorhak =  substr_replace(substr_replace(substr_replace(substr_replace(substr_replace($sheetData[$c]["S"], '.', 9,0), '.', 8,0), '.', 6,0), '.', 4,0), '.', 2,0);
								$nodif = $sheetData[$c]["O"];

								// CEK WARKAH
								$dat['table'] 	= "tb_warkah";
								$dat['type'] 	  = "single";
								$dat['condition']['nohak_warkah']	= $nomorhak;
								$dat['condition']['nodifferensi_warkah']	= $nodif;
								$exist = $this->crud_model->get_data($dat);

								if(!$exist){

										$datwarkah ['nohak_warkah']    			   = $nomorhak;
										$datwarkah ['nodifferensi_warkah']  	 = $nodif;
										$datwarkah ['pemohon_warkah']  	 = $sheetData[$c]["C"];
										$datwarkah ['alamat_warkah']  	 = $sheetData[$c]["D"];
										$datwarkah ['pelepashak_warkah']  	 = $sheetData[$c]["G"];
										$datwarkah ['tanggalhak_warkah']  	 = date("Y-m-d", strtotime($sheetData[$c]["H"]));
										$datwarkah ['seri_warkah']  	 = $sheetData[$c]["N"];
										$datwarkah ['ket_warkah']  	 = $sheetData[$c]["P"];
										$datwarkah ['tahun_warkah']  	 = date("Y", strtotime($sheetData[$c]["Q"]));
										$datwarkah ['bulan_warkah']  	 = date("m", strtotime($sheetData[$c]["R"]));

										$inputwarkah = $this->crud_model->input("tb_warkah",$datwarkah);

										if($inputwarkah){
											echo "<span style='color:#27ae60'>Data Warkah ".$nomorhak." dengan NO Differensi ".$nodif." berhasil diinput</span><br>";
										}else{
											echo "<span style='color:#c0392b'>Data Warkah ".$nomorhak." dengan NO Differensi ".$nodif." gagal diinput</span><br>";
										}
									}else{
										echo "<span style='color:#e67e22'>Nomor Differensi ".$sheetData[$c]["S"]." sudah ada di system</span><br>";
									}

									// CEK NOMOR HAK
									$dath['table'] 	= "tb_hak";
									$dath['type'] 	  = "single";
									$dath['condition']['no_hak']	= $nomorhak;
									$existhak = $this->crud_model->get_data($dath);

									if($existhak){
										$dathak ['pma_hak']    = $sheetData[$c]["I"];
										$dathak ['nosu_hak']   = $sheetData[$c]["L"];
										$dathak ['nib_hak']  	 = $sheetData[$c]["K"];

										$updatehak = $this->crud_model->update("tb_hak",$dathak,array('no_hak'=>$nomorhak));

										if($updatehak){
											echo "<span style='color:#27ae60'>Nomor Hak ".$nomorhak." berhasil diupdate</span><br>";
										}else{
											echo "<span style='color:#c0392b'>Nomor Hak ".$nomorhak." gagal diupdate</span><br>";
										}

									}else{
										echo "<span style='color:#c0392b'>Data Hak ".$nomorhak." belum ada dan tidak di diinput</span><br>";
									}

								}else{
									echo "<span style='color:#c0392b'>Data Warkah ".$sheetData[$c]["S"]." gagal diinput</span><br>";
								}

							$c++;
							$no++;
					}
				}else{

				$qw = $this->db->query("SELECT datadi208_pt FROM tb_peta WHERE idkel_pt = '$kode'")->row_array();

				if(!empty($qw['datadi208_pt'])){
						$this->content['sample'] = '../filelibrary/di208/'.$qw['datadi208_pt'];
				}else{
					$this->content['sample']='';
				}


				$this->content['file'] = base_url().'Studio2/sample_dhkp/'.$kode;
				$this->content['load'] = array("Studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

		function import_penduduk()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Data Penduduk";
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Import Data Penduduk","Studio2/import_penduduk"));

			if ($this->input->post()) {
					$this->load->library("Excel/PHPExcel");
					$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$c = 2;$no=1;

					// READ IMPORT
					$this->db->trans_start();
					while(!empty($sheetData[$c]["B"])){

							if (!empty($sheetData[$c]["B"])) {

								$datktp['table'] = "tb_penduduk";
					      		$datktp['type'] = "single";
								$datktp['condition']['noktp_pdk'] = str_replace("'","",$sheetData[$c]["B"]);
								$ktp = $this->crud_model->get_data($datktp);

								if($ktp){
									echo $ktp['idpdk_pdk'].'<br>';
								}else{
									echo $sheetData[$c]["B"].' belum ada<br>';
								}

								if(strtolower($sheetData[$c]["M"])=='islam'){
									$agama=1;
								}else if(strtolower($sheetData[$c]["M"])=='kristen'){
									$agama=2;
								}else if(strtolower($sheetData[$c]["M"])=='katholik'){
									$agama=3;
								}else if(strtolower($sheetData[$c]["M"])=='budha'){
									$agama=4;
								}else if(strtolower($sheetData[$c]["M"])=='hindu'){
									$agama=5;
								}else{
									$agama=0;
								}

								$datkerja['table'] = "tb_pekerjaan";
					     		$datkerja['type'] = "single";
								$datkerja['like']['nama_pkr'] = ucfirst($sheetData[$c]["N"]);
								$kerja = $this->crud_model->get_data($datkerja);

								if($kerja){
									$pekerjaan = $kerja['idpkr_pkr'];
								}else{
									$pekerjaan = 89;
								}

								if(!$ktp){
									$ar = array(
										'noktp_pdk' => str_replace("'","",$sheetData[$c]["B"]),
										'nma_pdk'   => addslashes($sheetData[$c]["D"]),
										'nokk_pdk'   => $sheetData[$c]["C"],
										'ttl_pdk' => $sheetData[$c]["K"],
										'ttg_pdk' => date("Y-m-d",strtotime($sheetData[$c]["L"])),
										'agm_pdk' => $agama,
										'almat_pdk' => $sheetData[$c]["E"],
										'idpeker_pdk' => $pekerjaan,
										'rt_pdk' => $sheetData[$c]["F"],
										'rw_pdk' => $sheetData[$c]["G"],
										'kel_pdk' => $sheetData[$c]["H"],
										'kec_pdk' => $sheetData[$c]["I"],
										'kab_pdk' => $sheetData[$c]["J"],
										'notelp_pdk' => $sheetData[$c]["O"],
										'publish_pdk' => '1',
										'idusr_pdk' => $user['idusr_usr'],
										'create_at' => date('Y-m-d H:i:s')
									);

									// $ar = array(
									// 	'noktp_pdk' => '1112242142142121321',
									// 	'nma_pdk'   => 'Cahyo',
									// 	'nokk_pdk'   => '1112242142142121321',
									// 	'ttl_pdk' => 'Semarang',
									// 	'ttg_pdk' => date("Y-m-d",strtotime('12-12-1992')),
									// 	'agm_pdk' => 1,
									// 	'almat_pdk' => 'alamat',
									// 	'rt_pdk' => '01',
									// 	'rw_pdk' => '02',
									// 	'publish_pdk' => '1',
									// 	'idusr_pdk' => $user['idusr_usr'],
									// 	'create_at' => date('Y-m-d H:i:s')
									// );
									
									$simpan = $this->crud_model->input('tb_penduduk',$ar);

									if($simpan){
										echo "<span style='color:#27ae60'>".$no.".NIK ".$sheetData[$c]["B"]." berhasil diinput</span><br>";
									}else{
										echo "<span style='color:#c0392b'>".$no.".NIK ".$sheetData[$c]["B"]." gagal diinput</span><br>";
									}

									$insert_id = $this->db->insert_id();
									$this->referensi_model->save_logs($user['idusr_usr'],'tb_penduduk','e Data-<br>-'.$insert_id,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

								}else{
									$ar = array(
										'nma_pdk'   => addslashes($sheetData[$c]["D"]),
										'nokk_pdk'   => $sheetData[$c]["C"],
										'ttl_pdk' => $sheetData[$c]["K"],
										'ttg_pdk' => date("Y-m-d",strtotime($sheetData[$c]["L"])),
										'agm_pdk' => $agama,
										'almat_pdk' => $sheetData[$c]["E"],
										'idpeker_pdk' => $pekerjaan,
										'rt_pdk' => $sheetData[$c]["F"],
										'rw_pdk' => $sheetData[$c]["G"],
										'kel_pdk' => $sheetData[$c]["H"],
										'kec_pdk' => $sheetData[$c]["I"],
										'kab_pdk' => $sheetData[$c]["J"],
										'notelp_pdk' => $sheetData[$c]["O"],
										'publish_pdk' => '1',
										'idusr_pdk' => $user['idusr_usr']
									);
									$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp['noktp_pdk']));

									if($simpan){
										echo "<span style='color:#27ae60'>".$no.".NIK ".$sheetData[$c]["B"]." berhasil diupdate</span><br>";
									}else{
										echo "<span style='color:#c0392b'>".$no.".NIK ".$sheetData[$c]["B"]." gagal diupdate</span><br>";
									}

									$insert_id = $ktp['idpdk_pdk'];

									$this->referensi_model->save_logs($user['idusr_usr'],'tb_penduduk','e Data-<br>-'.$insert_id,"Update Data Penduduk dengan rincian ".displayArray($ar));

								}
							$c++;
							$no++;
					}

				}
				$this->db->trans_complete();
			}else{

				$this->content['file'] = base_url().'/filelibrary/sample_data_penduduk.xls';
				$this->content['load'] = array("Studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

		function sample_dhkp($id)
		{
			if ($id) {

				$data ['type']		= "single";
				$data ['table'] 	= "ms_kelurahan";
				$data ['join']['table'] = "ms_kecamatan";
				$data ['join']['key'] 	= "kd_kec";
				$data ['join']['ref']	= "kdkec_kel";
				$data ['condition']['kd_full'] = $id;

				$this->content['desa'] = $this->crud_model->get_data($data);
				$this->load->view('studio2/exportdhkp',$this->content);
			}
		}
	}
