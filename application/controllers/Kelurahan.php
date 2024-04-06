<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Kelurahan extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();

			$this->load->database();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Kelurahan';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			$this->content['data']['title'] = "Data Kelurahan";
			$this->content['data']['subtitle'] = array(array("Kelurahan","Kelurahan"));

      $this->content['kel']     =  $this->db->query("SELECT a.nma_kel, a.kd_full, a.kdpbb_kel , b.nma_kec ,a.id_kel FROM ms_kelurahan a LEFT JOIN ms_kecamatan b ON a.kdkec_kel = b.kd_kec")->result_array();

			$this->content['load'] = array("kelurahan/data_kelurahan");

			$this->load->view('adm',$this->content);
		}

		public function form_kelurahan()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			$status = $this->uri->segment(3);
			if($status){
				$this->content['data']['title'] 	= "Form Edit Kelurahan";
				$this->content['data']['subtitle'] 	= array(array("Kelurahan","Kelurahan"),array("Edit Kelurahan","Kelurahan/form_kelurahan/".$status));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all();
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all();
				$this->content['get_data']			= $this->kelurahan_kw_model->show_edit_kelurahan($status);

				$this->content['status']			= "edit";
			}else{
				$this->content['data']['title'] 	= "Form Tambah Kelurahan";
				$this->content['data']['subtitle'] 	= array(array("Kelurahan","Kelurahan"),array("Tambah Kelurahan","Kelurahan/form_kelurahan"));

				$this->content['kecamatan']			= $this->studio_1_1_model->data_kecamatan_add_all();
				$this->content['kelurahan']			= $this->studio_1_1_model->data_kelurahan_add_all();

				$this->content['status']			= "tambah";
			}


			$this->content['load'] 				= array("kelurahan/form_kelurahan");
			$this->load->view('adm',$this->content);
		}

		public function tambah_kelurahan()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			$kdkec_kel = $this->input->post('kdkec_kel');
			$kd_kel = $this->input->post('kd_kel');
			$kd_pbb = $this->input->post('kd_pbb');
			$kd_full = "1107".$kdkec_kel.$kd_kel;

			$array = array(
					'kd_kel' => $kd_kel,
					'kdkec_kel' => $kdkec_kel,
					'kdpbb_kel' => $kd_pbb,
					'nma_kel' 	   => $this->input->post('nma_kel'),
					'kd_full' => $kd_full
			);

			$simpan = $this->kelurahan_kw_model->simpan_data_kelurahan($array);

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_kelurahan',$kd_kel,"Menambahkan Kelurahan dengan rincian ".displayArray($array));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit_kelurahan()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			$id 	= $this->input->post('id');
			$kdkec_kel = $this->input->post('kdkec_kel');
			$kd_kel = $this->input->post('kd_kel');
			$kd_pbb = $this->input->post('kd_pbb');
			$kd_full = "1107".$kdkec_kel.$kd_kel;

			$array = array(
					'kd_kel' => $kd_kel,
					'kdkec_kel' => $kdkec_kel,
					'kdpbb_kel' => $kd_pbb,
					'nma_kel' 	   => $this->input->post('nma_kel'),
					'kd_full' => $kd_full
			);

			$simpan = $this->kelurahan_kw_model->edit_data_kelurahan($id,$array);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_kelurahan',$kd_kel,"Mengedit Kelurahan dengan rincian ".displayArray($array));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus_kelurahan($kode)
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			$hapus = $this->kelurahan_kw_model->hapus_data_kelurahan($kode);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_kelurahan',$kode,"Mengahapus Kelurahan dengan rincian ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function cari_desa()
		{
			$id = $this->input->post('id');
			$data = $this->studio_1_1_model->cari_data_desa($id);
			echo json_encode($data);die();
		}

		public function cekkelurahan()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			$kecamatan = $_GET['kecamatan'];

			$dat['table'] = "ms_kelurahan";
      $dat['type'] = "multiple";
			if($kecamatan!=0){
					if($level_usr != "1" && $level_usr != "3"){
						$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$usr['idusr_usr'].") AND kdkec_kel=".$kecamatan;
					}else{
						$dat['condition']['kdkec_kel'] = $kecamatan;
					}
					$key='kd_kel';
			}else{
				if($level_usr != "1" && $level_usr != "3"){
					$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$usr['idusr_usr'].")";
				}
				  $key='kd_full';
			}
			$dat['orderby']['column'] = 'nma_kel';
			$dat['orderby']['sort'] = 'asc';
      $hasil = $this->crud_model->get_data($dat);
			?>
			<option value='0'>Semua Kelurahan</option>
			<?php
			foreach ($hasil as $dd) {
				?><option value='<?= $dd[$key];?>'><?= $dd['nma_kel']?></option><?php
			}

		}

		public function getkelurahan()
		{
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			$kecamatan = $_GET['kecamatan'];
			$kelurahan = $_GET['kdkel'];

			$dat['table'] = "ms_kelurahan";
      $dat['type'] = "multiple";
			if($kecamatan!=0){
					if($level_usr != "1" && $level_usr != "3"){
						$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$usr['idusr_usr'].") AND kdkec_kel=".$kecamatan;
					}else{
						$dat['condition']['kdkec_kel'] = $kecamatan;
					}
					$key='kd_kel';
			}else{
				if($level_usr != "1" && $level_usr != "3"){
					$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$usr['idusr_usr'].")";
				}
				  $key='kd_full';
			}
			$dat['orderby']['column'] = 'nma_kel';
			$dat['orderby']['sort'] = 'asc';
      $hasil = $this->crud_model->get_data($dat);
			?>
			<option value='0'>Semua Kelurahan</option>
			<?php
			foreach ($hasil as $dd) {
				if($dd[$key]==$kelurahan){
					$cek='selected';
				}else{
					$cek='';
				}
				?><option value='<?= $dd[$key];?>' <?php echo $cek;?>><?= $dd['nma_kel']?></option><?php
			}

		}

		public function getkode()
		{
			$usr = $this->auth_model->get_userdata();
			$kelurahan = $_GET['kelurahan'];
			$kecamatan = $_GET['kecamatan'];
			$kodehak 	 = $_GET['kodehak'];

			if($kecamatan && $kodehak){
					echo '11.26.'.$kecamatan.'.'.$kelurahan.'.'.$kodehak.'.';
			}
		}

		public function getfullkode()
		{
			$usr = $this->auth_model->get_userdata();
			$kelurahan = $_GET['kelurahan'];
			$kecamatan = $_GET['kecamatan'];

			$dat['table'] = "ms_kelurahan";
      $dat['type'] = "single";
			$dat['condition']['kdkec_kel'] = $kecamatan;
			$dat['condition']['kd_kel'] = $kelurahan;
			$hasil = $this->crud_model->get_data($dat);
			echo $hasil['kd_full'];

		}

		function import()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Kelurahan";
			$this->content['data']['subtitle'] = array(array("Kelurahan","Kelurahan"),array("Import Kelurahan","Kelurahan/import"));

			if ($this->input->post()) {
					$this->load->library("NExcel/PHPExcel");
          $objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
          $c = 2;$no=1;
          // READ IMPORT
          while(!empty($sheetData[$c]["A"])){
              if (!empty($sheetData[$c]["A"])) {
								$dkec = $sheetData[$c]["A"];
								$kec = explode(" ", $dkec);

								if(count($kec)==3){
									$kecamatan = $kec[1].' '.$kec[2];
								}else if(count($kec)==2){
									$kecamatan = $kec[1];
								}

								// CEK KECAMATAN
								$dk['table'] 	= "ms_kecamatan";
								$dk['type'] 	  = "single";
								$dk['condition']['nma_kec']	= $kecamatan;
								$exist1 = $this->crud_model->get_data($dk);

								$kode = substr($sheetData[$c]["B"],4,2);
								if(!$exist1){
									echo $kec[1].'- kode kec :'.$kode.' BELUM ADA<br>';
									$import ['kd_kec']	         = $kode;
									$import ['nma_kec']	         = $kecamatan;
									$import ['create_at']	       = date('Y-m-d H:i:s');
                  $inputhak = $this->crud_model->input("ms_kecamatan",$import);
								}else{
									echo $kec[1].'- kode kec :'.$kode.' SUDAH ADA<br>';
								}


								$dat['table'] 	= "ms_kelurahan";
								$dat['type'] 	  = "single";
								// $dat['column']	= "id_kel";
								$dat['condition']['kdkec_kel']	    = $kode;
								$dat['condition']['nma_kel']	= $sheetData[$c]["C"];
								$exist = $this->crud_model->get_data($dat);

								$kode1 = substr($sheetData[$c]["B"],6,2);

								if(!$exist){
									echo $sheetData[$c]["C"].'- kode kel :'.$kode1.'- kode full'.$sheetData[$c]["B"].'- BELUM ADA<br>';

									$import2 ['kd_kel']	           = $kode1;
									$import2 ['kdkec_kel']	       = $kode;
									$import2 ['nma_kel']	         = $sheetData[$c]["C"];
									$import2 ['type_kel']	         = 1;
									$import2 ['kd_full']	         = $sheetData[$c]["B"];
                  $inputhak2 = $this->crud_model->input("ms_kelurahan",$import2);
								}else{
									echo $sheetData[$c]["C"].'- kode kel :'.$kode1.'- kode full'.$sheetData[$c]["B"].'- SUDAH ADA<br>';
								}

								}

              $c++;
              $no++;
          }
				}else{
				$this->content['load'] = array("studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

		function importpbb()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Kelurahan";
			$this->content['data']['subtitle'] = array(array("Kelurahan","Kelurahan"),array("Import Kelurahan","Kelurahan/import"));

			if ($this->input->post()) {
					$this->load->library("NExcel/PHPExcel");
          $objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
          $c = 2;$no=1;
          // READ IMPORT
          while(!empty($sheetData[$c]["A"])){
              if (!empty($sheetData[$c]["A"])) {
								if(!empty($sheetData[$c]["B"])){

									$dkec = $sheetData[$c]["A"];
									$kec = explode(" - ", $dkec);

									$dkel = $sheetData[$c]["B"];
									$kel = explode(" - ", $dkel);
									$kelurahan = str_replace(" ", "", $kel[1]);

									$dat['table'] 	= "ms_kelurahan";
									$dat['type'] 	  = "single";
									$dat['join']['table'] = "ms_kecamatan";
									$dat['join']['key'] = "kd_kec";
									$dat['join']['ref'] = "kdkec_kel";
									$dat['condition']['UPPER(nma_kec)']	= $kec[1];
									$dat['condition']['UPPER(nma_kel)']	= $kelurahan;
									$exist = $this->crud_model->get_data($dat);

									if($exist){
										echo $no.'. '.$kec[1].'- '.$kel[1].'/'.$kelurahan.' - kode PBB :'.$kel[0].'- DATA ADA<br>';

										$import2 ['kdpbb_kel']	           = $kel[0];
										$inputhak2 = $this->crud_model->update("ms_kelurahan",$import2,array('id_kel'=>$exist['id_kel']));
										if($inputhak2){
											echo '<span style="color:#00ff00">Data Berhasil diupdate</span>';
										}else{
											echo '<span style="color:#ff0000">Data Gagal diupdate</span>';
										}
									}else{
										echo $no.'. '.$kec[1].'- '.$kel[1].'/'.$kelurahan.' - kode PBB :'.$kel[0].'- DATA BELUM ADA<br>';
									}
									$no++;
								}
							}

              $c++;

          }
				}else{
				$this->content['load'] = array("studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}
	}
 ?>
