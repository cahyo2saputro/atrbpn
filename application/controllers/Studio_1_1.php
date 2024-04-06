<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_1_1 extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();
			$this->load->view('auth/authorized');
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$user = $this->auth_model->get_userdata();
			cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);
			if($this->input->get('search')){
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->input->get('search'));
				// getuserkelurahan($user['idusr_usr'],$user['level_usr'],$this->input->get('search'));
			}
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = '';

		}

		public function index()
		{
			$cari = $this->input->get('search');
			$hak = $this->input->get('hak');
			$su = $this->input->get('su');
			$file = $this->input->get('file');

			$this->content['data']['title'] 	= " e-Digitalisasi";
			$this->content['data']['subtitle'] 	= array(array("e-digitalisasi","Studio1"),array("Daftar Hak","Studio_1_1/index?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$user = $this->auth_model->get_userdata();

			if($user['level_usr'] != "1"){
					$this->content['filter_kelurahan'] = $this->db->query("SELECT kd_full,nma_kel as nama_kelurahan FROM ms_kelurahan WHERE kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].") ORDER BY nma_kel ASC")->result();
			}else{
					$this->content['filter_kelurahan'] = $this->db->query("SELECT kd_full,nma_kel as nama_kelurahan FROM ms_kelurahan ORDER BY nma_kel ASC")->result();
			}


			$dat['table'] = "tb_hak";
			$dat['join']['table'] = "tb_su";
			$dat['join']['key'] = "no_hak";
			$dat['join']['ref'] = "nohak_su";
      $dat['type'] = "single";
			$dat['column'] = "COUNT(id_studio_1_1) as jumlah";
			if($hak){
				$dat['condition']['no_hak'] = $hak;
			}
			if($cari){
				$dat['condition']['id_kelurahan'] = $cari;
			}
			if($su){
				$dat['like']['nosu_hak'] = $su;
			}
			if($file){
				if($file==1){
					$dat['condition']['file_bt'] = NULL;
				}else if($file==2){
					$dat['condition']['file_su'] = NULL;
				}
			}
			//$dat['cuzcondition'] = 'id_kelurahan = "'.$cari.'" OR no_hak = "'.$cari.'" OR no_su = "'.$cari.'"';
      $hasil = $this->crud_model->get_data($dat);

			$config['base_url'] = base_url().'Studio_1_1/index/';
			$config['total_rows'] = $hasil['jumlah'];
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 10;

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


			$this->content['studio'] = $this->studio_1_1_model->show_data($config['per_page'],$from,$cari,$hak,$su,$file);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] 				= array("studio_1_1/data_studio_1_1");
			$this->load->view('adm',$this->content);
		}

		public function cekbt($kelurahan=NULL)
		{
			$dat['table'] = "tb_hak";
			$dat['join']['table'] = "ms_kelurahan,ms_kecamatan";
			$dat['join']['key'] = "kd_full,kd_kec";
			$dat['join']['ref'] = "id_kelurahan,kdkec_kel";
			if($kelurahan){
				$dat['condition']['id_kelurahan'] = $kelurahan;
			}
			$dat['type'] = "multiple";
			$dat['column'] = "id_studio_1_1,nma_kel,nma_kec,no_hak";

			$hasil = $this->crud_model->get_data($dat);
			foreach ($hasil as $dd) {
				/** CEK BT **/
				$nma_file_bt = cek_berkas(str_replace('.','',$dd['no_hak']),strtoupper($dd['nma_kec']),strtoupper($dd['nma_kel']),'BT');

				$bt = json_decode($nma_file_bt,true);

				if($bt['result']['status']){
					$updatehak = $this->crud_model->update("tb_hak",array('file_bt'=>1),array('id_studio_1_1'=>$dd['id_studio_1_1']));
				}else{
					$updatehak = $this->crud_model->update("tb_hak",array('file_bt'=>NULL),array('id_studio_1_1'=>$dd['id_studio_1_1']));
				}

			}
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_1_1/index/?search=<?php echo $kelurahan; ?>">
			<?php
		}

		public function ceksu($kelurahan=NULL)
		{
			$dat['table'] = "tb_hak";
			$dat['join']['table'] = "ms_kelurahan,ms_kecamatan";
			$dat['join']['key'] = "kd_full,kd_kec";
			$dat['join']['ref'] = "id_kelurahan,kdkec_kel";
			if($kelurahan){
				$dat['condition']['id_kelurahan'] = $kelurahan;
			}
			$dat['type'] = "multiple";
			$dat['column'] = "id_studio_1_1,nma_kel,nma_kec,kd_full,nosu_hak";

			$hasil = $this->crud_model->get_data($dat);
			foreach ($hasil as $dd) {
				if($dd['nosu_hak']!=''){
					$dt = explode('.',$dd['nosu_hak']);
					$dt2 = explode('/',$dt[1]);
					$nosu=$dt2[0];
					$thnsu=$dt2[count($dt2)-1];

					$jenisberkas = substr($dd['nosu_hak'],0,2);

						$nma_file_su = cek_berkas($dd['kd_full']."_".$nosu."_".$thnsu,strtoupper($dd['nma_kec']),strtoupper($dd['nma_kel']),$jenisberkas);

						$su = json_decode($nma_file_su,true);

						if($su['result']['status']){
							$updatehak = $this->crud_model->update("tb_hak",array('file_su'=>1),array('id_studio_1_1'=>$dd['id_studio_1_1']));
						}else{
							$updatehak = $this->crud_model->update("tb_hak",array('file_su'=>NULL),array('id_studio_1_1'=>$dd['id_studio_1_1']));
						}
				}
			}
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_1_1/index/?search=<?php echo $kelurahan; ?>">
			<?php
		}

		public function valid()
		{
			$cari = $this->input->get('search');
			$hak = $this->input->get('hak');
			$su = $this->input->get('su');
			$file = $this->input->get('file');

			$this->content['data']['title'] 	= " e-Validation";
			$this->content['data']['subtitle'] 	= array(array("e-validation","Studio1/validation"),array("Daftar Hak","Studio_1_1/valid?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$user = $this->auth_model->get_userdata();

			if($user['level_usr'] != "1"){
					$this->content['filter_kelurahan'] = $this->db->query("SELECT kd_full,nma_kel as nama_kelurahan FROM ms_kelurahan WHERE kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].") ORDER BY nma_kel ASC")->result();
			}else{
					$this->content['filter_kelurahan'] = $this->db->query("SELECT kd_full,nma_kel as nama_kelurahan FROM ms_kelurahan ORDER BY nma_kel ASC")->result();
			}


			$dat['table'] = "tb_hak";
			$dat['join']['table'] = "tb_su";
			$dat['join']['key'] = "no_hak";
			$dat['join']['ref'] = "nohak_su";
      $dat['type'] = "single";
			$dat['column'] = "COUNT(id_studio_1_1) as jumlah";
			if($hak){
				$dat['condition']['no_hak'] = $hak;
			}
			if($cari){
				$dat['condition']['id_kelurahan'] = $cari;
			}
			if($su){
				$dat['like']['nosu_hak'] = $su;
			}
			if($file){
				if($file==1){
					$dat['condition']['file_bt'] = '';
				}else if($file==2){
					$dat['condition']['file_su'] = '';
				}
			}

      $hasil = $this->crud_model->get_data($dat);

			$config['base_url'] = base_url().'Studio_1_1/valid/';
			$config['total_rows'] = $hasil['jumlah'];
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 10;

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


			$this->content['studio'] = $this->studio_1_1_model->show_data($config['per_page'],$from,$cari,$hak,$su,$file);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] 				= array("studio_1_1/data_studio_1_1");
			$this->load->view('adm',$this->content);
		}


		public function form_studio()
		{
			$status = $this->uri->segment(3);
			if($status){
				$this->content['data']['title'] 	= "Form Edit e-digitalisasi";
				$this->content['data']['subtitle'] 	= array(array("e-digitalisasi","Studio1"),array("Daftar Hak","javascript:history.go(-1)"),array("Edit Hak","Studio_1_1/form_studio/".$status));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all($status);
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all($status);
				$this->content['get_data']			= $this->studio_1_1_model->show_edit_studio($status);

				// GET KELURAHAN
				$user = $this->auth_model->get_userdata();
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['id_kelurahan']);

				$this->content['status']			= "edit";
				$this->content['link']				= base_url().'studio_1_1/edit_studio';
			}else{
				$this->content['data']['title'] 	= "Form Tambah e-digitalisasi";
				$this->content['data']['subtitle'] 	= array(array("e-digitalisasi","Studio1"),array("Daftar Hak","javascript:history.go(-1)"),array("Tambah Hak","Studio_1_1/form_studio"));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all();
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all();

				$this->content['status']			= "tambah";
				$this->content['link']				= base_url().'studio_1_1/tambah_studio';
			}

			$this->content['load'] 				= array("studio_1_1/form_studio_1_1");
			$this->load->view('adm',$this->content);
		}

		public function tambah_studio()
		{
			$id_kelurahan = $this->input->post('id_kelurahan');
			$id_kecamatan = $this->input->post('id_kecamatan');

			if(empty($id_kelurahan)){
				$msg = false;
			}else if(empty($this->input->post('kode_hak'))){
				$msg = false;
			}else if(empty($this->input->post('no_hak'))){
				$msg = false;
			}else if(empty($this->input->post('nib'))){
				$msg = false;
			}else if(empty($this->input->post('jenis_kw_awal'))){
				$msg = false;
			}else if(empty($this->input->post('pemilik_pertama'))){
				$msg = false;
			}else if(empty($this->input->post('pemilik_terakhir'))){
				$msg = false;
			}else{
					$dat['table'] = "ms_kelurahan";
					$dat['type'] = "single";
					$dat['column'] = "kd_full,nma_kel";
					$dat['condition']['kd_kel'] = $id_kelurahan;
					$dat['condition']['kdkec_kel'] = $id_kecamatan;
					$hasil = $this->crud_model->get_data($dat);

    			$kd_hak = $this->input->post('kode_hak');
    			$no_hak = $this->input->post('no_hak');
    			$no_hak_real = "11"."."."26".".".$id_kecamatan.".".$id_kelurahan.".".$kd_hak.".".$no_hak;

    			$array = array(
    					'id_kelurahan'			=> $hasil['kd_full'],
    					'no_hak'				=> $no_hak_real,
    					'kdhak_hak' 			=> $kd_hak,
    					'nib_hak'				=> $this->input->post('nib'),
    					'jenis_kw_awal'			=> $this->input->post('jenis_kw_awal'),
    					'pma_hak'				=> $this->input->post('pemilik_pertama'),
    					'pmi_hak'				=> $this->input->post('pemilik_terakhir'),
    					'status_hak'	=>	'1',
    					'status_bt'		=> '0',
    					'status_su'		=> '0',
    					'status_gu'		=> '0',
							'idusr_hak'		=> $this->content['data']['user']['idusr_usr'],
							'create_at'		=> date("Y-m-d H:i:s")
    			);

    			$simpan = $this->studio_1_1_model->simpan_data_studio($array);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Digitalisasi-'.$hasil['nma_kel'].'-'.$no_hak_real,"Menambahkan Hak dengan rincian ".displayArray($array));

					if($this->input->post('nib')){
						$hak['table'] 	= "tb_nub";
						$hak['type'] 	  = "single";
						$hak['condition']['nohak_nub'] 	  = $no_hak_real;
						$cekhak = $this->crud_model->get_data($hak);

						if($cekhak){
							ceknib($this->input->post('nib'),$cekhak['idnub_nub'],$hasil['kd_full'],1);
						}
					}


			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit_studio()
		{
			$id 	= $this->input->post('id');
			$dd = explode('.',$this->input->post('no_hakfront'));

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['column'] = "kd_full,nma_kel";
			$dat['condition']['kd_kel'] = $dd[3];
			$dat['condition']['kdkec_kel'] = $dd[2];
			$hasil = $this->crud_model->get_data($dat);

			$array 	= array(
					'id_kelurahan'			=> $hasil['kd_full'],
					'no_hak'				=> $this->input->post('no_hakfront').''.$this->input->post('no_hak'),
					'kdhak_hak' 			=> $this->input->post('kode_hak'),
					'nib_hak'				=> $this->input->post('nib'),
					'jenis_kw_awal'			=> $this->input->post('jenis_kw_awal'),
					'pma_hak'				=> $this->input->post('pemilik_pertama'),
					'pmi_hak'				=> $this->input->post('pemilik_terakhir'),
					'idusr_hak'		=> $this->content['data']['user']['idusr_usr'],
			);



			$simpan = $this->studio_1_1_model->edit_data_studio($id,$array);

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Digitalisasi-'.$hasil['nma_kel'].'-'.$this->input->post('no_hak'),"Mengedit Hak dengan rincian ".displayArray($array));

			if($this->input->post('nib')){
				$hak['table'] 	= "tb_nub";
				$hak['type'] 	  = "single";
				$hak['condition']['nohak_nub'] 	  = $this->input->post('no_hakfront').''.$this->input->post('no_hak');
				$cekhak = $this->crud_model->get_data($hak);

				if($cekhak){
					ceknib($this->input->post('nib'),$cekhak['idnub_nub'],$hasil['kd_full'],1);
				}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus_studio($kode_studio)
		{
			$hapus = $this->studio_1_1_model->hapus_data_studio($kode_studio);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Digitalisasi-0-'.$kode_studio,"Menghapus Hak dengan kode ".$kode_studio);

			$hak['table'] 	= "tb_nub";
			$hak['type'] 	  = "single";
			$hak['column']  = "nib_hak,idnub_nub,id_kelurahan";
			$hak['join']['table'] 	= "tb_hak";
			$hak['join']['key'] 	  = "no_hak";
			$hak['join']['ref'] 	  = "nohak_nub";
			$hak['condition']['nohak_nub'] 	  = $kode_studio;
			$cekhak = $this->crud_model->get_data($hak);

			if($cekhak && $cekhak['nib_hak']!=''){
				$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$cekhak['idnub_nub'],'idkel_nib'=>$cekhak['id_kelurahan'],'status_nib'=>1,'nib_nib'=>$cekhak['nib_hak']));
			}

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		function import_studio($kode)
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Data K4";
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Import K4","Studio_1_1/import_studio"));

			if ($this->input->post()) {
					$this->load->library("Excel/PHPExcel");
          $objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
          $c = 6;$no=1;
          // READ IMPORT
          while(!empty($sheetData[$c]["A"])){

              if (!empty($sheetData[$c]["A"])) {

								$dat['table'] 	= "tb_hak";
								$dat['type'] 	  = "single";
								$dat['column']	= "no_hak";
								$dat['condition']['no_hak']	= $sheetData[$c]["B"];
								$exist = $this->crud_model->get_data($dat);

								if(!$exist){
									// INPUT HAK
									$exkel = explode('.',$sheetData[$c]["B"]);

									$kel['table'] 	= "ms_kelurahan";
									$kel['type'] 	= "single";
									$kel['column']	= "kd_full,nma_kel";
									$kel['condition']['kd_kel']	= $exkel[3];
									$kel['condition']['kdkec_kel']	= $exkel[2];
									$kelurahan = $this->crud_model->get_data($kel);

									$import ['no_hak']    			 = $sheetData[$c]["B"];
									$import ['id_kelurahan']     = $kelurahan["kd_full"];

                  $import ['nib_hak']          = str_replace("'", "", $sheetData[$c]["D"]);
									$import ['jenis_kw_awal']    = $sheetData[$c]["G"];
									$import ['pma_hak']    			 = addslashes($sheetData[$c]["H"]);
									$import ['pmi_hak'] 			   = addslashes($sheetData[$c]["I"]);
									$import ['status_hak'] 			   = 1;
									if($sheetData[$c]["C"]){
										$import ['nosu_hak']	       = $sheetData[$c]["C"];
										$import ['status_su'] 			   = 1;
									}else{
										$import ['nosu_hak']	       = NULL;
										$import ['status_su'] 			   = 0;
									}


									if(strtolower($sheetData[$c]['A']) == "hak milik"){
										$import ['kdhak_hak'] = 1;
									}else if(strtolower($sheetData[$c]['A']) == "hak guna usaha"){
										$import ['kdhak_hak'] = 2;
									}else if(strtolower($sheetData[$c]['A']) == "hak guna bangunan"){
										$import ['kdhak_hak'] = 3;
									}else if(strtolower($sheetData[$c]['A']) == "hak pakai"){
										$import ['kdhak_hak'] = 4;
									}else if(strtolower($sheetData[$c]['A']) == "hak pengelolaan"){
										$import ['kdhak_hak'] = 5;
									}else if(strtolower($sheetData[$c]['A']) == "hak tanggungan"){
										$import ['kdhak_hak'] = 6;
									}else if(strtolower($sheetData[$c]['A']) == "hak rumah susun"){
										$import ['kdhak_hak'] = 7;
									}else if(strtolower($sheetData[$c]['A']) == "hak wakaf"){
										$import ['kdhak_hak'] = 8;
									}else{
										$import ['kdhak_hak'] = 0;
									}

									$import ['idusr_hak']         = $user['idusr_usr'];
									$import ['create_at']         = date('Y-m-d H:i:s');

									$inputhak = $this->crud_model->input("tb_hak",$import);

									if($inputhak){
										echo '<span style="color:#2980b9;">NO.'.$no.' HAK '.$sheetData[$c]["B"].' berhasil diinput </span><br>';
										$this->referensi_model->save_logs($user['idusr_usr'],'tb_hak','e Data BPN-'.$kelurahan['nma_kel'].'-'.$import['no_hak'],"Mengimport Hak dengan rincian ".displayArray($import));

										if($import ['nosu_hak']){
											$dd=explode('.',$import ['nosu_hak']);
											$ds=explode('/',$dd[1]);
											$importsu ['nohak_su']    		 = $sheetData[$c]["B"];
											$importsu ['luas_su']     		 = $sheetData[$c]["E"];
											$importsu ['sugs_su']     		 = $dd[0];
											$importsu ['no_su']     		   = $ds[0];
											if(count($ds)==3){
												$importsu ['thn_su']     		   = $ds[2];
											}else{
												$importsu ['thn_su']     		   = $ds[1];
											}
											$importsu ['luaspeta_su']      = $sheetData[$c]["F"];
											$importsu ['status_su'] 			 = 1;
											$importsu ['idusr_usr']        = $user['idusr_usr'];

											$inputsu = $this->crud_model->input("tb_su",$importsu);

											if($inputsu){
												echo '<span style="color:#2980b9;">NO.'.$no.' SU '.$sheetData[$c]["C"].' berhasil diinput </span><br>';
											}else{
												echo '<span style="color:#e74c3c;">NO.'.$no.' SU '.$sheetData[$c]["C"].' gagal diinput </span><br>';
											}
											$this->referensi_model->save_logs($user['idusr_usr'],'tb_su','e Data BPN-'.$kelurahan['nma_kel'].'-'.$import['nosu_hak'],"Mengimport SU dengan rincian ".displayArray($importsu));
										}else{
											echo '<span style="color:#e74c3c;">NO.'.$no.' SU '.$sheetData[$c]["C"].' kosong </span><br>';
										}
									}else{
										echo '<span style="color:#e74c3c;">NO.'.$no.' HAK '.$sheetData[$c]["B"].' gagal diinput </span><br>';
									}
								}else{
									// UPDATE HAK
									$exkel = explode('.',$sheetData[$c]["B"]);

									$kel['table'] 	= "ms_kelurahan";
									$kel['type'] 	= "single";
									$kel['column']	= "kd_full,nma_kel";
									$kel['condition']['kd_kel']	= $exkel[3];
									$kel['condition']['kdkec_kel']	= $exkel[2];
									$kelurahan = $this->crud_model->get_data($kel);

									$import ['no_hak']    			 = $sheetData[$c]["B"];
									$import ['id_kelurahan']     = $kelurahan["kd_full"];
                  $import ['nosu_hak']	       = $sheetData[$c]["C"];
                  $import ['nib_hak']          = str_replace("'", "", $sheetData[$c]["D"]);
									$import ['jenis_kw_awal']    = $sheetData[$c]["G"];
									$import ['pma_hak']    			 = addslashes($sheetData[$c]["H"]);
									$import ['pmi_hak'] 			   = addslashes($sheetData[$c]["I"]);
									$import ['status_hak'] 			   = 1;

									if($sheetData[$c]["C"]){
										$import ['nosu_hak']	       = $sheetData[$c]["C"];
										$import ['status_su'] 			   = 1;
									}else{
										$import ['nosu_hak']	       = NULL;
										$import ['status_su'] 			   = 0;
									}

									if(strtolower($sheetData[$c]['A']) == "hak milik"){
										$import ['kdhak_hak'] = 1;
									}else if(strtolower($sheetData[$c]['A']) == "hak guna usaha"){
										$import ['kdhak_hak'] = 2;
									}else if(strtolower($sheetData[$c]['A']) == "hak guna bangunan"){
										$import ['kdhak_hak'] = 3;
									}else if(strtolower($sheetData[$c]['A']) == "hak pakai"){
										$import ['kdhak_hak'] = 4;
									}else if(strtolower($sheetData[$c]['A']) == "hak pengelolaan"){
										$import ['kdhak_hak'] = 5;
									}else if(strtolower($sheetData[$c]['A']) == "hak tanggungan"){
										$import ['kdhak_hak'] = 6;
									}else if(strtolower($sheetData[$c]['A']) == "hak rumah susun"){
										$import ['kdhak_hak'] = 7;
									}else if(strtolower($sheetData[$c]['A']) == "hak wakaf"){
										$import ['kdhak_hak'] = 8;
									}else{
										$import ['kdhak_hak'] = 0;
									}

									$import ['idusr_hak']         = $user['idusr_usr'];

									$updatehak = $this->crud_model->update("tb_hak",$import,array('no_hak'=>$sheetData[$c]["B"]));

									if($updatehak){
										echo '<span style="color:#16a085;">NO.'.$no.' HAK '.$sheetData[$c]["B"].' berhasil diupdate </span><br>';
										$this->referensi_model->save_logs($user['idusr_usr'],'tb_hak','e Data BPN-'.$kelurahan['nma_kel'].'-'.$import['no_hak'],"Update Hak dengan rincian ".displayArray($import));

										if($import ['nosu_hak']){
											$dd=explode('.',$import ['nosu_hak']);
											$ds=explode('/',$dd[1]);
											$totalds = count($ds);
											$importsu ['nohak_su']    		 = $sheetData[$c]["B"];
											$importsu ['luas_su']     		 = $sheetData[$c]["E"];
											$importsu ['sugs_su']     		 = $dd[0];
											$importsu ['no_su']     		   = $ds[0];
											if($totalds>=3){
												$importsu ['thn_su']     		   = $ds[2];
											}else{
												$importsu ['thn_su']     		   = $ds[1];
											}
											$importsu ['luaspeta_su']      = $sheetData[$c]["F"];
											$importsu ['status_su'] 			 = 1;
											$importsu ['idusr_usr']        = $user['idusr_usr'];

											$su['table'] 	= "tb_su";
											$su['type'] 	= "single";
											$su['column']	= "id_su";
											$su['condition']['nohak_su']	= $sheetData[$c]["B"];
											$datsu = $this->crud_model->get_data($su);

											if($datsu){
												$inputhak = $this->crud_model->update("tb_su",$importsu,array('id_su'=>$datsu['id_su']));
												$this->referensi_model->save_logs($user['idusr_usr'],'tb_su','e Data BPN-'.$kelurahan['nma_kel'].'-'.$importsu['nohak_su'],"Mengimport Update SU dengan rincian ".displayArray($importsu));
												echo '<span style="color:#16a085;">NO.'.$no.' SU '.$sheetData[$c]["C"].' sudah ada di tabel => sukses diupdate</span><br>';
											}else{
												$inputhak = $this->crud_model->input("tb_su",$importsu);
												$this->referensi_model->save_logs($user['idusr_usr'],'tb_hak','e Data BPN-'.$kelurahan['nma_kel'].'-'.$importsu['nohak_su'],"Mengimport SU dengan rincian ".displayArray($importsu));
												echo '<span style="color:#2980b9;">NO.'.$no.' SU '.$sheetData[$c]["C"].' belum ada di tabel => sukses diinput</span><br>';
											}
										}else{
												echo '<span style="color:#e74c3c;">NO.'.$no.' SU '.$sheetData[$c]["C"].' kosong </span><br>';
										}
								}else{
									echo '<span style="color:#e74c3c;">NO.'.$no.' HAK '.$sheetData[$c]["B"].' sudah ada di tabel => gagal diupdate</span><br>';
								}
							}
							$no++;
						}
            $c++;
          }
			}else{
				$this->content['file'] = base_url().'Studio_1_1/sample_k4/'.$kode;
				// $this->content['file'] = base_url().'/filelibrary/k4/sample_k4.xls';
				$this->content['load'] = array("Studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

		function sample_k4($id)
		{
			if ($id) {

				$data ['type']		= "single";
				$data ['table'] 	= "ms_kelurahan";
				$data ['join']['table'] = "ms_kecamatan";
				$data ['join']['key'] 	= "kd_kec";
				$data ['join']['ref']	= "kdkec_kel";
				$data ['condition']['kd_full'] = $id;

				$this->content['desa'] = $this->crud_model->get_data($data);
				$this->load->view('studio2/exportk4',$this->content);
			}
		}

		public function detail_warkah($status_studio)
	    {

				$this->content['get_data']			= $this->studio_1_1_model->show_edit_studio($status_studio);
				$this->content['data']['title'] = "Data Warkah";
				$this->content['data']['subtitle'] = array(array("Data Warkah","Studio_1_1/detail_warkah/".$status_studio));
				// GET KELURAHAN
				$user = $this->auth_model->get_userdata();
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['id_kelurahan']);

				$dat['table'] 	= "tb_warkah";
				$dat['type'] 	  = "single";
				$dat['condition']['nohak_warkah']	= $status_studio;
				$this->content['datas'] = $this->crud_model->get_data($dat);

			$this->content['load'] 				= array("studio_1_1/form_warkah");
			$this->load->view('adm',$this->content);
	    }

		public function cari_desa()
		{
			$id = $this->input->post('id');
			$data = $this->studio_1_1_model->cari_data_desa($id);
			echo json_encode($data);die();
		}

		public function update_buku_tanah($kode,$value)
		{
			$update = $this->studio_1_1_model->update_buku_tanah($kode,$value);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Validasi-0-'.$kode,"Update Buku Tanah dengan kode ".$kode);
			if($update){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function update_entry_su($kode,$value)
		{
			$update = $this->studio_1_1_model->update_entry_su($kode,$value);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Validasi-0-'.$kode,"Update Entri SU dengan kode ".$kode);
			if($update){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function update_su_spasial($kode,$value)
		{
			$update = $this->studio_1_1_model->update_su_spasial($kode,$value);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Validasi-0-'.$kode,"Update SU Spasial dengan kode ".$kode);
			if($update){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function update_bidang_tanah($kode,$value)
		{
			$update = $this->studio_1_1_model->update_bidang_tanah($kode,$value);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_hak','e Validasi-0-'.$kode,"Update Bidang Tanah dengan kode ".$kode);
			if($update){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function migrasisu()
		{
			$hak['type'] = "multiple";
			$hak['table'] = "tb_hak";
			$hak['column'] = "id_studio_1_1,no_hak";
			$hak['condition']['nosu_hak'] = '';
			$hak['limit'] = 100000;
			$hak = $this->crud_model->get_data($hak);

			foreach ($hak as $data) {
				$su['type'] = "single";
				$su['table'] = "tb_su";
				$su['column'] = "sugs_su,no_su,thn_su";
				$su['condition']['nohak_su'] = $data['no_hak'];
				$su = $this->crud_model->get_data($su);

				if($su['no_su']!=''){
					$dataarray = array(
						'nosu_hak' => $su['sugs_su'].'.'.$su['no_su'].'/'.$su['thn_su']
					);
					$simpan = $this->crud_model->update('tb_hak',$dataarray,array('id_studio_1_1'=>$data['id_studio_1_1']));
				}
			}
		}

		public function completenib()
		{
			$hak['type'] = "multiple";
			$hak['table'] = "tb_hak";
			$hak['column'] = "id_studio_1_1,no_hak,nib_hak";
			$hak['cuzcondition'] = 'LENGTH(nib_hak)=4';
			// $hak['limit'] = 100000;
			$hak = $this->crud_model->get_data($hak);

			foreach ($hak as $data) {
				echo $data['no_hak'].'-'.$data['nib_hak'].'<br>';
				// $dataarray = array(
				// 	'nib_hak' => '0'.$data['nib_hak']
				// );
				// $simpan = $this->crud_model->update('tb_hak',$dataarray,array('id_studio_1_1'=>$data['id_studio_1_1']));
			}
		}
	}
 ?>
