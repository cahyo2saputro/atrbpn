<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Studio3 extends CI_Controller
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
		$this->content['data']['title_page'] = 'Studio 2';
		$this->load->view('auth/authorized');
	}

	public function index()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "e-Panita Desa";
		$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		if($user['level_usr'] != "1"){
			$idusr = $user['idusr_usr'];
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
		}else{
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
		}

		if($cari!=0 && $carikelurahan==0){
			if($user['level_usr'] != "1"){
				$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].") AND kdkec_kel='.$cari.'";
			}else{
				$dat['condition']['kdkec_kel'] = $cari;
			}
		}else if($cari!=0 && $carikelurahan!=0){
			$dat['condition']['kdkec_kel'] = $cari;
			$dat['condition']['kd_kel'] = $carikelurahan;
		}else if($user['level_usr'] != "1"){
			$dat['cuzcondition'] = "kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr'].")";
		}else if($cari==0 && $carikelurahan!=0){
			$dat['condition']['kd_full'] = $carikelurahan;
		}

		$dat['table'] = "ms_kelurahan";
		$dat['type'] = "single";
		$dat['column'] = "COUNT(kdkec_kel) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Studio3/index/';
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

		$this->content['studio'] = $this->studio3_model->show_kecamatan($config['per_page'],$from,$cari,$carikelurahan);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("studio3/data_studio_3");
		$this->load->view('adm',$this->content);
	}

	function target()
	{
		$this->content['data']['title'] = "Target PBT";
		$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("input target PBT","Studio3/target?target=".$this->input->get('target')));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] 	= "tb_targetuser";
		$dat['type'] 	= "multiple";
		$dat['column']	= "target_tgt,tahun_tgt";
		$dat['condition']['kdfull_tgt'] = $this->input->get('target');
		// $dat['condition']['tahun_tgt']	= date('Y');
		$dat['orderby']['column'] 	= "create_at";
		$dat['orderby']['sort']		= "DESC";
		$this->content['target'] = $this->crud_model->get_data($dat);

		if ($this->input->post()) {

			$data = $this->input->post();
			$this->crud_model->delete('tb_targetuser',array('kdfull_tgt'=>$data['kdfull']));

			$count = count($this->input->post('tahun'));

			for($i=0;$i<$count;$i++){
					if($data['tahun'][$i]!=''){
						$data_input ['idusr_tgt']	= $this->content['data']['user']['idusr_usr'];
						$data_input ['kdfull_tgt']	= $data['kdfull'];
						$data_input ['tahun_tgt'] 	= $data['tahun'][$i];
						$data_input ['target_tgt']	= $data['target'][$i];

						$input = $this->crud_model->input('tb_targetuser', $data_input);
					}
			}


			if ($input) {
				// save log
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_targetuser','e Pengukuran-0-'.$insert_id,"input data target user dengan rincian ".displayArray($data_input));
				?><meta http-equiv="refresh" content="1;<?php echo base_url();?>studio3"><?php
			}
		}

		$this->content['load'] = array("studio3/form_target");
		$this->load->view('adm',$this->content);
	}

	function dashboard()
	{
		$this->content['data']['title'] = " Dashboard Progress PTSL Panitia Desa";
		$this->content['data']['subtitle'] = array(array("daftar kompetitif","Studio3/dashboard"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		// $dat['table'] = "tb_targetuser";
		// $dat['type'] = "single";
		// $dat['column'] = "COUNT(kdfull_tgt) as jumlah";
		// $dat['groupby'] = "kdfull_tgt";
		// $hasil = $this->crud_model->get_data($dat);
		// $t_data=$hasil['jumlah'];

		// $config['base_url'] = base_url().'Studio3/dashboard/';
		// $config['total_rows'] = $t_data;
		// $config['reuse_query_string'] = TRUE;
		$config['per_page'] = 50;
		// $config['uri_segment'] = 3;
		//
		// $config['next_link'] = 'Selanjutnya';
		// $config['prev_link'] = 'Sebelumnya';
		// $config['first_link'] = 'Awal';
		// $config['last_link'] = 'Akhir';
		// $config['full_tag_open'] = '<ul class="pagination">';
		// $config['full_tag_close'] = '</ul>';
		// $config['num_tag_open'] = '<li>';
		// $config['num_tag_close'] = '</li>';
		// $config['cur_tag_open'] = '<li class="active"><a href="#">';
		// $config['cur_tag_close'] = '</a></li>';
		// $config['prev_tag_open'] = '<li>';
		// $config['prev_tag_close'] = '</li>';
		// $config['next_tag_open'] = '<li>';
		// $config['next_tag_close'] = '</li>';
		// $config['last_tag_open'] = '<li>';
		// $config['last_tag_open'] = '<li>';
		// $config['first_tag_open'] = '<li>';
		// $config['first_tag_open'] = '<li>';
		//
		// $this->pagination->initialize($config);
		if($this->input->get('tahun')){
			$tahun=$this->input->get('tahun');
		}else{
			$tahun=date('Y');
		}

		$this->content['studio'] = $this->studio3_model->get_rank($config['per_page'],$from,$cari,$carikelurahan,$tahun);
		//$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("studio3/dashboard");
		$this->load->view('adm',$this->content);
	}

	function export()
	{
		$id = $this->input->get('search');
		if ($id) {
			$kec ['type'] 	= "single";
			$kec ['table']	= "ms_kelurahan";
			$kec ['column']	= "nma_kel,nma_kec";
			$kec ['join']['table']	= "ms_kecamatan";
			$kec ['join']['key']	= "kdkec_kel";
			$kec ['join']['ref']	= "kd_kec";
			$kec ['condition']['kd_full'] = $id;
			$this->content['desa'] = $this->crud_model->get_data($kec);

			$data ['type']		= "multiple";
			$data ['table'] 	= "ms_kelurahan";
			$data ['column']	= "nma_kel,nma_pdk,nama_blk,ttg_pdk,ttl_pdk";
			$data ['join']['table'] = "tb_block,tb_ptsl,tb_penduduk";
			$data ['join']['key'] 	= "idkel_blk,idblk_ptsl,noktp_ptsl";
			$data ['join']['ref']	= "kd_full,idblk_blk,noktp_pdk";
			$data ['condition']['kd_full'] = $id;

			$this->content['dat'] = $this->crud_model->get_data($data);
			$this->load->view('studio3/export',$this->content);
		}
	}

	function migrasi_nop(){
		$dat['table'] 	= "tb_ptsl";
		$dat['type'] 	  = "multiple";
		$dat['column'] 	= "id_ptsl,iddhkp_ptsl";
		$loop = $this->crud_model->get_data($dat);

		foreach ($loop as $data) {
			$exist['table'] 	= "tb_ptsldhkp";
			$exist['type'] 	  = "single";
			$exist['condition']['idptsl_ptsl'] 	  = $data['id_ptsl'];
			$exist['condition']['iddhkp_ptsl'] 	  = $data['iddhkp_ptsl'];
			$datex = $this->crud_model->get_data($exist);

			if(!$datex){
				$datptsl ['idptsl_ptsl'] = $data['id_ptsl'];
				$datptsl ['iddhkp_ptsl'] = $data['iddhkp_ptsl'];

				$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
			}
		}

	}

	function migrasi_berkas(){
		$dat['table'] 	= "tb_ptsl";
		$dat['type'] 	  = "multiple";
		$dat['column'] 	= "id_ptsl,berkas_ptsl";
		$loop = $this->crud_model->get_data($dat);

		foreach ($loop as $data) {
			$exist['table'] 	= "tb_ptslberkas";
			$exist['type'] 	  = "single";
			$exist['condition']['idptsl_pbk'] 	  = $data['id_ptsl'];
			$exist['condition']['berkas_pbk'] 	  = $data['berkas_ptsl'];
			$datex = $this->crud_model->get_data($exist);

			if(!$datex && $data['berkas_ptsl']!=''){
				$datptsl ['idptsl_pbk'] = $data['id_ptsl'];
				$datptsl ['berkas_pbk'] = $data['berkas_ptsl'];

				$inputdhkp = $this->crud_model->input("tb_ptslberkas",$datptsl);
			}
		}

	}

	public function grafik(){
		$tahun=date('Y');
		$this->content['data']['title'] = " Grafik Progress PTSL";
		$this->content['data']['subtitle'] = array(array("Grafik Progress PTSL","home"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->content['graphic'] = $this->studio3_model->graphic($tahun);

		$this->content['user'] = $this->auth_model->get_userdata();

		$this->content['load'] = array("studio3/grafik");
		$this->load->view('adm',$this->content);

	}

	function migrasi_status(){
		$dat['table'] 	= "tb_ptsl";
		$dat['type'] 	  = "multiple";
		$dat['join']['table'] 	= "tb_block";
		$dat['join']['key'] 	  = "idblk_ptsl";
		$dat['join']['ref'] 	  = "idblk_blk";
		$dat['cuzcondition'] 	  = "nib_ptsl!='' AND id_ptsl!=0 AND idkel_blk!='' AND publish_ptsl=1";
		$dat['column'] 	= "id_ptsl,nib_ptsl,idkel_blk";
		$loop = $this->crud_model->get_data($dat);

		foreach ($loop as $data) {
				$exist['table'] 	= "tb_nib";
				$exist['type'] 	  = "single";
				$exist['condition']['idref_nib'] 	  = $data['id_ptsl'];
				$exist['condition']['nib_nib'] 	    = $data['nib_ptsl'];
				$exist['condition']['idkel_nib'] 	  = $data['idkel_blk'];
				$exist['condition']['status_nib'] 	= 0;
				$datex = $this->crud_model->get_data($exist);

				if(!$datex && $data['nib_ptsl']!=''){
					$datptsl ['idref_nib']  = $data['id_ptsl'];
					$datptsl ['nib_nib']    = $data['nib_ptsl'];
					$datptsl ['idkel_nib']  = $data['idkel_blk'];
					$datptsl ['status_nib'] = 0;

					$inputptsl = $this->crud_model->input("tb_nib",$datptsl);
				}
		}

		$hak['table'] 	= "tb_nub";
		$hak['type'] 	  = "multiple";
		$hak['join']['table'] 	= "tb_hak";
		$hak['join']['key'] 	  = "nohak_nub";
		$hak['join']['ref'] 	  = "no_hak";
		$hak['cuzcondition'] 	  = "nib_hak!='' AND publish_nub=1";
		$hak['column'] 	= "idnub_nub,nib_hak,id_kelurahan";
		$loophak = $this->crud_model->get_data($hak);

		foreach ($loophak as $data) {
			$existhak['table'] 	  = "tb_nib";
			$existhak['type'] 	  = "single";
			$existhak['condition']['idref_nib'] 	  = $data['idnub_nub'];
			$existhak['condition']['nib_nib'] 	    = $data['nib_hak'];
			$existhak['condition']['idkel_nib'] 	  = $data['id_kelurahan'];
			$existhak['condition']['status_nib'] 	  = 1;
			$datex = $this->crud_model->get_data($existhak);

			if(!$datex && $data['nib_hak']!=''){
				$datnub ['idref_nib']  = $data['idnub_nub'];
				$datnub ['nib_nib']    = $data['nib_hak'];
				$datnub ['idkel_nib']  = $data['id_kelurahan'];
				$datnub ['status_nib'] = 1;

				$inputnub = $this->crud_model->input("tb_nib",$datnub);
			}
		}

	}

	function nopdhkp($id){
		$dat['table'] 	= "tb_ptsl";
		$dat['type'] 	  = "multiple";
		$dat['column'] 	= "id_ptsl,nosppt_ptsl,idblk_ptsl";
		$dat['join']['table'] 	= "tb_block";
		$dat['join']['key'] 	  = "idblk_blk";
		$dat['join']['ref'] 	  = "idblk_ptsl";
		$dat['condition']['idkel_blk']= $id;
		$dat['condition']['publish_ptsl']= 1;

		$loop = $this->crud_model->get_data($dat);
		$no=1;
		foreach ($loop as $data) {
			$exist['table'] 	= "tb_dhkp";
			$exist['type'] 	  = "single";
			$exist['condition']['nosppt_dhkp'] 	  = $data['nosppt_ptsl'];
			$exist['condition']['idblk_dhkp'] 	  = $data['idblk_ptsl'];
			$datex = $this->crud_model->get_data($exist);

			$ptsldhkp['table'] 	= "tb_ptsldhkp";
			$ptsldhkp['type'] 	= "single";
			$ptsldhkp['condition']['idptsl_ptsl'] 	  = $data['id_ptsl'];
			$ptsldhkp['condition']['iddhkp_ptsl'] 	  = 0;
			$datadhkp = $this->crud_model->get_data($ptsldhkp);

			if($datex && $datadhkp){
				echo $no.'='.$datex['id_dhkp'].'-'.$datex['nosppt_dhkp'].'-'.$datex['idblk_dhkp'].'='.$data['nosppt_ptsl'].'-'.$data['idblk_ptsl'].'--'.$datadhkp['idptsl_ptsl'].'<br>';
				$update = $this->crud_model->update("tb_ptsldhkp",array('iddhkp_ptsl'=>$datex['id_dhkp']),array('idptsl_ptsl'=>$datadhkp['idptsl_ptsl']));
			}else if($datex){
				echo $no.'='.$data['id_ptsl'].'=> data relasi tidak tersedia<br>';
			}else if($datadhkp){
				echo $no.'='.$data['id_ptsl'].'=> dhkp tidak tersedia<br>';
			}
			$no++;
		}

	}

	public function petaonline($cari){
		$user = $this->auth_model->get_userdata();

		$dat['table'] = "ms_kelurahan";
		$dat['type'] = "single";
		$dat['condition']['kd_full'] = $cari;
		$hasil = $this->crud_model->get_data($dat);

		$desa['table'] = "tb_block";
		$desa['type'] = "multiple";
		$desa['condition']['idkel_blk'] = $cari;
		$this->content['blok'] = $this->crud_model->get_data($desa);

		$this->content['peta'] = $hasil;

		$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'];
		$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"),array("Peta Online ".$hasil['nma_kel'],"Studio3/petaonline/".$cari));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->content['load'] = array("studio6/petadesa");
		$this->load->view('adm',$this->content);
	}

	function import_ptsl()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Import Data DHKP";
		$this->content['data']['subtitle'] = array(array("Import Data DHKP"));

		if ($this->input->post()) {
				$this->load->library("Excel/PHPExcel");
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$c = 2;$no=1;
				// READ IMPORT
				while(!empty($sheetData[$c]["A"])){

						if (!empty($sheetData[$c]["A"])) {

							$nop = $sheetData[$c]["B"];

							$kecamatan = substr($nop,4,2);
							$kelurahan = substr($nop,7,3);
							$blok = substr($nop,10,3);
							$nop = substr($nop,13,5);

							$dat['table'] 	= "ms_kelurahan";
							$dat['type'] 	  = "single";
							$dat['condition']['kdkec_kel']	= $kecamatan;
							$dat['condition']['kdpbb_kel']	= $kelurahan;
							$exist = $this->crud_model->get_data($dat);

							if($exist){

								$block['table'] 	= "tb_block";
								$block['type'] 	  = "single";
								$block['condition']['nama_blk']	= $blok;
								$block['condition']['idkel_blk']	= $exist['kd_full'];
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
										echo "<span style='color:#c0392b'>Data Blok ".$blok." tidak diinput</span><br>";
									}
								}else{
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
									$datptsl ['nama_dhkp']  	  			 = $sheetData[$c]["C"];
									$datptsl ['nosppt_dhkp']    			 = $nop;
									$datptsl ['awpsppt_dhkp']    			 = $sheetData[$c]["D"];
									$datptsl ['aopsppt_dhkp']    			 = $sheetData[$c]["E"];
									$datptsl ['luassppt_dhkp']    		 = $sheetData[$c]["F"];
									$datptsl ['njopsppt_dhkp']    			   = $sheetData[$c]["H"];
									$datptsl ['create_at']     	     = date('Y-m-d H:i:s');

									$inputptsl = $this->crud_model->input("tb_dhkp",$datptsl);

									if($inputptsl){
										echo "<span style='color:#27ae60'>Data DHKP dengan NOP ".$nop." berhasil diinput</span><br>";
									}else{
										echo "<span style='color:#c0392b'>Data DHKP dengan NOP ".$nop." gagal diinput</span><br>";
									}
								}else{
									echo "<span style='color:#d35400'>Data DHKP dengan NOP ".$nop." sudah ada di table</span><br>";
								}



								}
							}else{
								echo "<span style='color:#c0392b'>Data NOP ".$sheetData[$c]["B"]." gagal diinput, Kelurahan tidak tersedia</span><br>";
							}

						$c++;
						$no++;
				}
			}else{
			$this->content['load'] = array("studio1/form_import");
			$this->load->view('adm',$this->content);
		}
	}

}
