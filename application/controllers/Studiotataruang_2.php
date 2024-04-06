<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studiotataruang_2 extends CI_Controller
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
			$this->content['data']['title_page'] = 'Studio 2.2';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Tataruang ".$block['nama_blk']."";
			$this->content['data']['subtitle'] = array(array("e-tataruang","Studiotataruang"),array("Daftar Blok","Studiotataruang_1/index/?search=".$block['idkel_blk']),array("Lahan Pemukiman","tataruang_2/data/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_ptsl";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(id_ptsl) as jumlah";
			$tdata['condition']['idblk_ptsl'] = $block['idblk_blk'];
			$tdata['condition']['publish_ptsl'] = '1';

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Studio_3_2/data/';
			$config['total_rows'] = $t_data['jumlah'];
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

			$dat['table'] = "tb_ptsl";
      $dat['type'] = "multiple";
			$dat['column'] = "tb_ptsl.nub_ptsl,tb_ptsl.idpnt_ptsl,tb_ptsl.idspt_ptsl,tb_ptsl.id_ptsl,tb_ptsl.nosppt_ptsl,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_ptsl.idblk_ptsl";
			$dat['join']['table'] = "tb_penduduk,tb_block";
			$dat['join']['key'] = "idpdk_ptsl,idblk_blk";
			$dat['join']['ref'] = "idpdk_pdk,idblk_ptsl";
			$dat['condition']['idblk_ptsl'] = $block['idblk_blk'];
			$dat['condition']['publish_ptsl'] = '1';
			$dat['orderby']['column'] = 'tb_ptsl.update_at';
			$dat['orderby']['sort'] = 'desc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studiotataruang/data_studio_2");
			$this->load->view('adm',$this->content);
		}

		public function addregister(){
			$user = $this->auth_model->get_userdata();
			if(empty($this->input->get('search'))){
				$idblk = $this->uri->segment(5);
			}else{
				$idblk = $this->input->get('search');
			}

			if ($this->input->post() && $this->input->post('nib_peta')!="") {

				$user = $this->auth_model->get_userdata();

				$dataarray = array(
					'idblk_nub' => $idblk,
					'nohak_nub'   => $this->input->post('nohak_nub'),
					'iddhkp_nub'   => $this->input->post('dhkp'),
					'status_nub'   => '1',
					'publish_nub' => '1',
					'idusr_nub' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);
				$simpan = $this->crud_model->input('tb_nub',$dataarray);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub',$insert_id,"Menambahkan Data Register Studio 3.2 dengan rincian ".displayArray($dataarray));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/register/?search=<?php echo $this->input->get('search'); ?>">
				<?php
			}else if($this->input->post() && $this->input->post('nib_peta')==""){
				?>
				<script>
					alert("NIB belum terdaftar");
				</script>
				<?php
			}

			$block = $this->studio_2_1_model->sr_name_block($idblk);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

				$idkel = $block['idkel_blk'];

				$this->content['data']['title'] = "Studio 3.2 Register ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Tambah Register","Studio_3_2/addregister/?search=".$idblk));

				$this->content['status'] = "register";

				$dhkp['table'] = "tb_dhkp";
	      $dhkp['type'] = "multiple";
				$dhkp['join']['table'] = "tb_block";
				$dhkp['join']['key'] = "idblk_blk";
				$dhkp['join']['ref'] = "idblk_dhkp";
				$dhkp['condition']['idblk_dhkp'] =$idblk;
	      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);

				$nohak['type'] = "multiple";
				$nohak['table'] = "tb_hak";
				$nohak['column'] = "no_hak";
				$nohak['orderby']['column'] = "no_hak";
				$nohak['orderby']['sort'] = "asc";
				$nohak['cuzcondition'] = "status_hak <> 0 AND id_kelurahan = '$idkel' AND no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE publish_nub=1)";
				$this->content['nohak'] = $this->crud_model->get_data($nohak);

				$this->content['block'] = $block;

				$this->content['load'] = array("studio3/form_register");
				$this->load->view('adm',$this->content);
	}

	public function editregister($id,$idblk){
		$user = $this->auth_model->get_userdata();
		if ($this->input->post() && $this->input->post('nib_peta')!="") {

			$user = $this->auth_model->get_userdata();

			$dataarray = array(
				'idblk_nub' => $idblk,
				'nohak_nub'   => $this->input->post('nohak_nub'),
				'iddhkp_nub'   => $this->input->post('dhkp'),
				'idusr_nub' => $user['idusr_usr']
			);
			$simpan = $this->crud_model->update('tb_nub',$dataarray,array('idnub_nub'=>$id));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub',$id,"Mengedit Data Register Studio 3.2 dengan rincian ".displayArray($dataarray));

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/register/?search=<?php echo $idblk; ?>">
			<?php
		}else if($this->input->post() && $this->input->post('nib_peta')==""){
			?>
			<script>
				alert("NIB belum terdaftar");
			</script>
			<?php
		}

			$block = $this->studio_2_1_model->sr_name_block($idblk);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);
			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "Edit Register Pengajuan ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Edit Register","Studio_3_2/editregister/".$id."/".$idblk));

			$this->content['status'] = "Edit register";

			$dnohak['type'] = "single";
			$dnohak['table'] = "tb_nub";
			$dnohak['column'] = "nohak_nub,iddhkp_nub";
			$dnohak['cuzcondition'] = "idnub_nub='$id'";
			$this->content['dnohak'] = $this->crud_model->get_data($dnohak);

			$nohak['type'] = "multiple";
			$nohak['table'] = "tb_hak";
			$nohak['column'] = "no_hak";
			$nohak['orderby']['column'] = "no_hak";
			$nohak['orderby']['sort'] = "asc";
			$nohak['cuzcondition'] = "status_hak <> 0 AND id_kelurahan = '$idkel' AND ( no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE publish_nub=1) OR no_hak='".$this->content['dnohak']['nohak_nub']."')";
			$this->content['nohak'] = $this->crud_model->get_data($nohak);

			$dhkp['table'] = "tb_dhkp";
			$dhkp['type'] = "multiple";
			$dhkp['join']['table'] = "tb_block";
			$dhkp['join']['key'] = "idblk_blk";
			$dhkp['join']['ref'] = "idblk_dhkp";
			$dhkp['condition']['idblk_dhkp'] =$idblk;
			$this->content['dhkp'] = $this->crud_model->get_data($dhkp);

			$this->content['block'] = $block;

			$this->content['load'] = array("studio3/edit_register");
			$this->load->view('adm',$this->content);
}

		public function register()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "Studio 3.2 ".$block['nama_blk']." sudah sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_nub";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(idnub_nub) as jumlah";
			$tdata['condition']['idblk_nub'] = $block['idblk_blk'];
			$tdata['condition']['publish_nub'] = '1';

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studio_3_2/register/';
			$config['total_rows'] = $ttdata['jumlah'];
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

			$dat['table'] = "tb_nub";
			$dat['column'] = "tb_nub.*,tb_hak.nosu_hak,tb_hak.pmi_hak,tb_su.luas_su";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "tb_hak,tb_su";
			$dat['join']['key'] = "no_hak,nohak_su";
			$dat['join']['ref'] = "nohak_nub,no_hak";
			$dat['condition']['idblk_nub'] = $block['idblk_blk'];
			$dat['condition']['publish_nub'] = '1';
			$dat['orderby']['column'] = 'update_at';
			$dat['orderby']['sort'] = 'desc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = ($from);
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio3/dataregister_studio_3_2");
			$this->load->view('adm',$this->content);
		}

		public function dhkp()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "Studio 3.2 DHKP ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("DHKP","Studio_3_2/dhkp/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_dhkp";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(id_dhkp) as jumlah";
			$tdata['condition']['idblk_dhkp'] = $block['idblk_blk'];

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studio_3_2/dhkp/';
			$config['total_rows'] = $ttdata['jumlah'];
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

			$dat['table'] = "tb_dhkp";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "tb_block";
			$dat['join']['key'] = "idblk_blk";
			$dat['join']['ref'] = "idblk_dhkp";
			$dat['condition']['idblk_dhkp'] = $block['idblk_blk'];
			$dat['orderby']['column'] = 'tb_dhkp.update_at';
			$dat['orderby']['sort'] = 'desc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from+1;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio3/datadhkp");
			$this->load->view('adm',$this->content);
		}

		public function k4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "Studio 3.2 K4 ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("K4","Studio_3_2/k4/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_hak";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(no_hak) as jumlah";
			$tdata['condition']['id_kelurahan'] = $block['idkel_blk'];

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studio_3_2/k4/';
			$config['total_rows'] = $ttdata['jumlah'];
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

			$dat['table'] = "tb_hak";
      $dat['type'] = "multiple";
			$dat['column'] = "tb_hak.no_hak,tb_hak.id_kelurahan,tb_hak.nib_hak,tb_hak.nosu_hak,tb_su.luas_su,
												tb_hak.jenis_kw_awal,tb_hak.pmi_hak,tb_hak.buku_tanah,tb_hak.entry_su_tekstual,
												tb_hak.su_spasial,tb_hak.bidang_tanah,tb_block.nama_blk";
			$dat['join']['table'] = "tb_su,tb_nub,tb_block";
			$dat['join']['key'] = "nohak_su,nohak_nub,idblk_blk";
			$dat['join']['ref'] = "no_hak,no_hak,idblk_nub";
			$dat['condition']['id_kelurahan'] = $block['idkel_blk'];
			$dat['orderby']['column'] = 'tb_hak.no_hak';
			$dat['orderby']['sort'] = 'asc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from+1;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio3/datak4");
			$this->load->view('adm',$this->content);
		}

		public function input(){
			$user = $this->auth_model->get_userdata();
			if(empty($this->input->get('search'))){
				$idblk = $this->uri->segment(5);
			}else{
				$idblk = $this->input->get('search');
			}
			$status = $this->uri->segment(3);
			$set_status = $this->uri->segment(4);

			$block = $this->studio_2_1_model->sr_name_block($idblk);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);


			$idkel = $block['idkel_blk'];

			if ($this->input->post()) {

				$datktp['table'] = "tb_penduduk";
	      $datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
				$ktp = $this->crud_model->get_data($datktp);

				$user = $this->auth_model->get_userdata();

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('ktp'),
						'nma_pdk'   => $this->input->post('nama'),
						'ttl_pdk' => $this->input->post('ttl'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'idpeker_pdk' => $this->input->post('pekerjaan'),
						'agm_pdk' => $this->input->post('agama'),
						'almat_pdk' => $this->input->post('alamat'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

				}


				// CEK DATA Saksi
				$saksi['table'] = "tb_saksiptsl";
	      $saksi['type'] = "single";
				$saksi['condition']['idkel_spt'] = $idkel;
				$saksi['orderby']['column'] = 'update_at';
				$saksi['orderby']['sort'] = 'desc';
	      $datasaksi = $this->crud_model->get_data($saksi);

				// CEK DATA PANITIA
				$tim['table'] = "tb_panitia";
	      $tim['type'] = "single";
				$tim['condition']['idusr_pnt'] = $user['idusr_usr'];
				$tim['orderby']['column'] = 'update_at';
				$tim['orderby']['sort'] = 'desc';
	      $dattim = $this->crud_model->get_data($tim);

				// GET NUB
				$datnub['table'] = "tb_ptsl";
	      $datnub['type'] = "single";
				$datnub['column'] = "MAX(nub_ptsl) as maximum";
				$datnub['condition']['idblk_ptsl'] = $idblk;
				$nub = $this->crud_model->get_data($datnub);

				if($nub){
					$dnub=$nub['maximum']+1;
				}else{
					$dnub=1;
				}

				if($datasaksi){
					if($dattim){
						$dataarray = array(
							'idspt_ptsl'=>$datasaksi['idspt_spt'],
							'idpnt_ptsl'=>$dattim['id_pnt'],
							'nub_ptsl' => $dnub,
							'idpdk_ptsl'   => $insert_id,
							'nosppt_ptsl' => $this->input->post('sppt'),
							'iddhkp_ptsl' => $this->input->post('dhkp'),
							'nwpsppt_ptsl' => $this->input->post('nwp'),
							'njop_ptsl' => $this->input->post('njop'),
							'luassppt_ptsl' => $this->input->post('luassppt'),
							'awpsppt_ptsl' => $this->input->post('awp'),
							'aopsppt_ptsl' => $this->input->post('aop'),
							'idguna_ptsl' => $this->input->post('guna'),
							'idblk_ptsl' => $this->input->post('blok'),
							'utara_ptsl' => $this->input->post('utara'),
							'timur_ptsl' => $this->input->post('timur'),
							'selatan_ptsl' => $this->input->post('selatan'),
							'barat_ptsl' => $this->input->post('barat'),
							'desc0_ptsl' => $this->input->post('des0'),
							'desc1_ptsl' => $this->input->post('des1'),
							'desc2_ptsl' => $this->input->post('des2'),
							'desc3_ptsl' => $this->input->post('des3'),
							'desc4_ptsl' => $this->input->post('des4'),
							'dc_ptsl' => $this->input->post('dc'),
							'dpersil_ptsl' => $this->input->post('dpersil'),
							'dklas_ptsl' => $this->input->post('dklas'),
							'dluas_ptsl' => $this->input->post('dluas'),
							'ddari_ptsl' => $this->input->post('ddari'),
							'idkperluan_ptsl' => $this->input->post('dkeperluan'),
							'thn_ptsl' => $this->input->post('dtahun'),
							'note_ptsl' => $this->input->post('note'),
							'thn_risalah' => $this->input->post('thn_risalah'),
							'publish_ptsl' => '1',
							'idusr_ptsl' => $user['idusr_usr'],
							'create_at' => date("Y-m-d H:i:s")
						);
					}else{
						$dataarray = array(
							'idspt_ptsl'=>$datasaksi['idspt_spt'],
							'nub_ptsl' => $dnub,
							'idpdk_ptsl'   => $insert_id,
							'nosppt_ptsl' => $this->input->post('sppt'),
							'iddhkp_ptsl' => $this->input->post('dhkp'),
							'nwpsppt_ptsl' => $this->input->post('nwp'),
							'njop_ptsl' => $this->input->post('njop'),
							'luassppt_ptsl' => $this->input->post('luassppt'),
							'awpsppt_ptsl' => $this->input->post('awp'),
							'aopsppt_ptsl' => $this->input->post('aop'),
							'idguna_ptsl' => $this->input->post('guna'),
							'idblk_ptsl' => $this->input->post('blok'),
							'utara_ptsl' => $this->input->post('utara'),
							'timur_ptsl' => $this->input->post('timur'),
							'selatan_ptsl' => $this->input->post('selatan'),
							'barat_ptsl' => $this->input->post('barat'),
							'desc0_ptsl' => $this->input->post('des0'),
							'desc1_ptsl' => $this->input->post('des1'),
							'desc2_ptsl' => $this->input->post('des2'),
							'desc3_ptsl' => $this->input->post('des3'),
							'desc4_ptsl' => $this->input->post('des4'),
							'dc_ptsl' => $this->input->post('dc'),
							'dpersil_ptsl' => $this->input->post('dpersil'),
							'dklas_ptsl' => $this->input->post('dklas'),
							'dluas_ptsl' => $this->input->post('dluas'),
							'ddari_ptsl' => $this->input->post('ddari'),
							'idkperluan_ptsl' => $this->input->post('dkeperluan'),
							'thn_ptsl' => $this->input->post('dtahun'),
							'note_ptsl' => $this->input->post('note'),
							'thn_risalah' => $this->input->post('thn_risalah'),
							'publish_ptsl' => '1',
							'idusr_ptsl' => $user['idusr_usr'],
							'create_at' => date("Y-m-d H:i:s")
						);
					}

				}else{
					if($dattim){
						$dataarray = array(
							'idpnt_ptsl'=>$dattim['id_pnt'],
							'nub_ptsl' => $dnub,
							'idpdk_ptsl'   => $insert_id,
							'nosppt_ptsl' => $this->input->post('sppt'),
							'iddhkp_ptsl' => $this->input->post('dhkp'),
							'nwpsppt_ptsl' => $this->input->post('nwp'),
							'njop_ptsl' => $this->input->post('njop'),
							'luassppt_ptsl' => $this->input->post('luassppt'),
							'awpsppt_ptsl' => $this->input->post('awp'),
							'aopsppt_ptsl' => $this->input->post('aop'),
							'idguna_ptsl' => $this->input->post('guna'),
							'idblk_ptsl' => $this->input->post('blok'),
							'utara_ptsl' => $this->input->post('utara'),
							'timur_ptsl' => $this->input->post('timur'),
							'selatan_ptsl' => $this->input->post('selatan'),
							'barat_ptsl' => $this->input->post('barat'),
							'desc0_ptsl' => $this->input->post('des0'),
							'desc1_ptsl' => $this->input->post('des1'),
							'desc2_ptsl' => $this->input->post('des2'),
							'desc3_ptsl' => $this->input->post('des3'),
							'desc4_ptsl' => $this->input->post('des4'),
							'dc_ptsl' => $this->input->post('dc'),
							'dpersil_ptsl' => $this->input->post('dpersil'),
							'dklas_ptsl' => $this->input->post('dklas'),
							'dluas_ptsl' => $this->input->post('dluas'),
							'ddari_ptsl' => $this->input->post('ddari'),
							'idkperluan_ptsl' => $this->input->post('dkeperluan'),
							'thn_ptsl' => $this->input->post('dtahun'),
							'note_ptsl' => $this->input->post('note'),
							'thn_risalah' => $this->input->post('thn_risalah'),
							'publish_ptsl' => '1',
							'idusr_ptsl' => $user['idusr_usr'],
							'create_at' => date("Y-m-d H:i:s")
						);
					}else{
						$dataarray = array(
							'nub_ptsl' => $dnub,
							'idpdk_ptsl'   => $insert_id,
							'nosppt_ptsl' => $this->input->post('sppt'),
							'iddhkp_ptsl' => $this->input->post('dhkp'),
							'nwpsppt_ptsl' => $this->input->post('nwp'),
							'njop_ptsl' => $this->input->post('njop'),
							'luassppt_ptsl' => $this->input->post('luassppt'),
							'awpsppt_ptsl' => $this->input->post('awp'),
							'aopsppt_ptsl' => $this->input->post('aop'),
							'idguna_ptsl' => $this->input->post('guna'),
							'idblk_ptsl' => $this->input->post('blok'),
							'utara_ptsl' => $this->input->post('utara'),
							'timur_ptsl' => $this->input->post('timur'),
							'selatan_ptsl' => $this->input->post('selatan'),
							'barat_ptsl' => $this->input->post('barat'),
							'desc0_ptsl' => $this->input->post('des0'),
							'desc1_ptsl' => $this->input->post('des1'),
							'desc2_ptsl' => $this->input->post('des2'),
							'desc3_ptsl' => $this->input->post('des3'),
							'desc4_ptsl' => $this->input->post('des4'),
							'dc_ptsl' => $this->input->post('dc'),
							'dpersil_ptsl' => $this->input->post('dpersil'),
							'dklas_ptsl' => $this->input->post('dklas'),
							'dluas_ptsl' => $this->input->post('dluas'),
							'ddari_ptsl' => $this->input->post('ddari'),
							'idkperluan_ptsl' => $this->input->post('dkeperluan'),
							'thn_ptsl' => $this->input->post('dtahun'),
							'note_ptsl' => $this->input->post('note'),
							'thn_risalah' => $this->input->post('thn_risalah'),
							'publish_ptsl' => '1',
							'idusr_ptsl' => $user['idusr_usr'],
							'create_at' => date("Y-m-d H:i:s")
						);
					}

				}
				$simpan = $this->crud_model->input('tb_ptsl',$dataarray);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$insert_id,"Menambahkan Data PTSL dengan rincian ".displayArray($dataarray));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/data/?search=<?php echo $this->input->get('search'); ?>">
				<?php
			}

			$dat['table'] = "tb_pekerjaan";
      $dat['type'] = "multiple";
			$dat['orderby']['column'] = 'nama_pkr';
			$dat['orderby']['sort'] = 'asc';
      $this->content['pekerjaan'] = $this->crud_model->get_data($dat);

			$dhkp['table'] = "tb_dhkp";
      $dhkp['type'] = "multiple";
			$dhkp['join']['table'] = "tb_block";
			$dhkp['join']['key'] = "idblk_blk";
			$dhkp['join']['ref'] = "idblk_dhkp";
			$dhkp['condition']['idblk_dhkp'] =$idblk;
      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);

				$this->content['data']['title'] = "Tambah Pengajuan ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblk),array("Tambah Pengajuan","Studio_3_2/input/?search=".$idblk));

				$this->content['status'] = "tambah";

				$template['type'] = "single";
				$template['table'] = "tb_ptsl";
				$template['column'] = "desc0_ptsl,desc1_ptsl,desc2_ptsl,desc3_ptsl,desc4_ptsl,thn_risalah";
				$template['condition']['id_ptsl'] = 0;
				$this->content['template'] = $this->crud_model->get_data($template);

				$this->content['block'] = $block;

				$this->content['load'] = array("studio3/form_ptsl");
				$this->load->view('adm',$this->content);
	}

	public function edit($id,$idblk){
		$user = $this->auth_model->get_userdata();
		if ($this->input->post()) {
			$datktp['table'] = "tb_penduduk";
			$datktp['type'] = "single";
			$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
			$ktp = $this->crud_model->get_data($datktp);

			$user = $this->auth_model->get_userdata();

			if(!$ktp){
				$ar = array(
					'noktp_pdk' => $this->input->post('ktp'),
					'nma_pdk'   => $this->input->post('nama'),
					'ttl_pdk' => $this->input->post('ttl'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'idpeker_pdk' => $this->input->post('pekerjaan'),
					'agm_pdk' => $this->input->post('agama'),
					'almat_pdk' => $this->input->post('alamat'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
			}else{
				$ar = array(
					'nma_pdk'   => $this->input->post('nama'),
					'ttl_pdk' => $this->input->post('ttl'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'idpeker_pdk' => $this->input->post('pekerjaan'),
					'agm_pdk' => $this->input->post('agama'),
					'almat_pdk' => $this->input->post('alamat'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
				$insert_id = $ktp['idpdk_pdk'];
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
			}

			$dataarray = array(
				'idpdk_ptsl'   => $insert_id,
				'nosppt_ptsl' => $this->input->post('sppt'),
				'njop_ptsl' => $this->input->post('njop'),
				'iddhkp_ptsl' => $this->input->post('dhkp'),
				'nwpsppt_ptsl' => $this->input->post('nwp'),
				'luassppt_ptsl' => $this->input->post('luassppt'),
				'awpsppt_ptsl' => $this->input->post('awp'),
				'aopsppt_ptsl' => $this->input->post('aop'),
				'idguna_ptsl' => $this->input->post('guna'),
				'idblk_ptsl' => $this->input->post('blok'),
				'utara_ptsl' => $this->input->post('utara'),
				'timur_ptsl' => $this->input->post('timur'),
				'selatan_ptsl' => $this->input->post('selatan'),
				'barat_ptsl' => $this->input->post('barat'),
				'desc0_ptsl' => $this->input->post('des0'),
				'desc1_ptsl' => $this->input->post('des1'),
				'desc2_ptsl' => $this->input->post('des2'),
				'desc3_ptsl' => $this->input->post('des3'),
				'desc4_ptsl' => $this->input->post('des4'),
				'dc_ptsl' => $this->input->post('dc'),
				'dpersil_ptsl' => $this->input->post('dpersil'),
				'dklas_ptsl' => $this->input->post('dklas'),
				'dluas_ptsl' => $this->input->post('dluas'),
				'ddari_ptsl' => $this->input->post('ddari'),
				'idkperluan_ptsl' => $this->input->post('dkeperluan'),
				'thn_ptsl' => $this->input->post('dtahun'),
				'note_ptsl' => $this->input->post('note'),
				'thn_risalah' => $this->input->post('thn_risalah'),
				'publish_ptsl' => '1',
				'idusr_ptsl' => $user['idusr_usr']
			);
			$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$id));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$id,"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/data/?search=<?php echo $idblk; ?>">
			<?php
		}

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$status = $this->uri->segment(3);
		$set_status = $this->uri->segment(4);

		$block = $this->studio_2_1_model->sr_name_block($idblk);
		cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);


			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "Edit Pengajuan ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblk),array("Edit Pengajuan","Studio_3_2/edit/".$id."/".$idblk));

			$this->content['status'] = "edit";

			$template['type'] = "single";
			$template['table'] = "tb_ptsl";
			$template['join']['table']="tb_penduduk";
			$template['join']['key']="idpdk_ptsl";
			$template['join']['ref']="idpdk_pdk";
			$template['condition']['id_ptsl'] = $id;
			$this->content['template'] = $this->crud_model->get_data($template);

			$this->content['block'] = $block;

			$dhkp['table'] = "tb_dhkp";
      $dhkp['type'] = "multiple";
			$dhkp['join']['table'] = "tb_block";
			$dhkp['join']['key'] = "idblk_blk";
			$dhkp['join']['ref'] = "idblk_dhkp";
			$dhkp['condition']['idblk_dhkp'] =$idblk;
      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);

			$this->content['load'] = array("studio3/edit_ptsl");
			$this->load->view('adm',$this->content);
	}

	public function formsaksi($id,$idblok){
		$user = $this->auth_model->get_userdata();
		$ptsl['table'] = "tb_ptsl";
		$ptsl['type'] = "single";
		$ptsl['condition']['id_ptsl'] = $id;
		$datptsl = $this->crud_model->get_data($ptsl);

		if($datptsl && $datptsl['idspt_ptsl']!=0){
			$saksi['table'] = "tb_saksiptsl";
			$saksi['type'] = "single";
			$saksi['condition']['idspt_spt'] = $datptsl['idspt_ptsl'];
			$this->content['saksi'] = $this->crud_model->get_data($saksi);
		}else{
			$saksi['table'] = "tb_saksiptsl";
			$saksi['type'] = "single";
			$saksi['condition']['idptsl_spt'] = $id;
			$this->content['saksi'] = $this->crud_model->get_data($saksi);
		}


		$block = $this->studio_2_1_model->sr_name_block($idblok);
		cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

		$idkel = $block['idkel_blk'];

		if($this->content['saksi']){
			$mode="edit";
			$datktp1['table'] = "tb_penduduk";
			$datktp1['type'] = "single";
			$datktp1['condition']['noktp_pdk'] = $this->content['saksi']['niksp1_spt'];
			$this->content['saksi1'] = $this->crud_model->get_data($datktp1);

			$datktp2['table'] = "tb_penduduk";
			$datktp2['type'] = "single";
			$datktp2['condition']['noktp_pdk'] = $this->content['saksi']['niksp2_spt'];
			$this->content['saksi2'] = $this->crud_model->get_data($datktp2);
		}else{
			$mode="input";
			$this->content['saksi1']=null;
			$this->content['saksi2']=null;
		}

		if ($this->input->post()) {
			$datktp1['table'] = "tb_penduduk";
			$datktp1['type'] = "single";
			$datktp1['condition']['noktp_pdk'] = $this->input->post('niksaksi1');
			$ktp1 = $this->crud_model->get_data($datktp1);

			$user = $this->auth_model->get_userdata();

			if(!$ktp1){
				$ar = array(
					'noktp_pdk' => $this->input->post('niksaksi1'),
					'nma_pdk'   => $this->input->post('namasaksi1'),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi1'),
					'agm_pdk' => $this->input->post('agamasaksi1'),
					'almat_pdk' => $this->input->post('alamatsaksi1'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menambahkan Data Penduduk (Saksi 1) dengan rincian ".displayArray($ar));
			}

			$datktp2['table'] = "tb_penduduk";
			$datktp2['type'] = "single";
			$datktp2['condition']['noktp_pdk'] = $this->input->post('niksaksi2');
			$ktp2 = $this->crud_model->get_data($datktp2);

			if(!$ktp2){
				$ar = array(
					'noktp_pdk' => $this->input->post('niksaksi2'),
					'nma_pdk'   => $this->input->post('namasaksi2'),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi2'),
					'agm_pdk' => $this->input->post('agamasaksi2'),
					'almat_pdk' => $this->input->post('alamatsaksi2'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menambahkan Data Penduduk (Saksi 2) dengan rincian ".displayArray($ar));
			}

			$datsaksi['table'] = "tb_saksiptsl";
			$datsaksi['type'] = "single";
			$datsaksi['condition']['nosurat_spt'] = $this->input->post('nosurat');
			$datsaksi['condition']['tgl_spt'] = $this->input->post('tanggal');
			$datsaksi['condition']['niksp1_spt'] = $this->input->post('niksaksi1');
			$datsaksi['condition']['umur1_spt'] = $this->input->post('umursaksi1');
			$datsaksi['condition']['niksp2_spt'] = $this->input->post('niksaksi2');
			$datsaksi['condition']['umur2_spt'] = $this->input->post('umursaksi2');
			$datsaksi['condition']['kades_spt'] = $this->input->post('kepaladesa');
			$datsaksi['condition']['bpn_spt'] = $this->input->post('satgasbpn');
			$datsaksi['condition']['nipbpn_spt'] = $this->input->post('nipsatgasbpn');
			$datsaksi['condition']['babinsa_spt'] = $this->input->post('satgasbabinsa');
			$datsaksi['condition']['idkel_spt'] = $idkel;
			$datasaksi = $this->crud_model->get_data($datsaksi);

			if(!$datasaksi){
				$dataarray = array(
					'idptsl_spt' => $id,
					'nosurat_spt'   => $this->input->post('nosurat'),
					'tgl_spt'   => date("Y-m-d",strtotime($this->input->post('tanggal'))),
					'niksp1_spt' => $this->input->post('niksaksi1'),
					'umur1_spt' => $this->input->post('umursaksi1'),
					'niksp2_spt' => $this->input->post('niksaksi2'),
					'umur2_spt' => $this->input->post('umursaksi2'),
					'kades_spt' => $this->input->post('kepaladesa'),
					'bpn_spt' => $this->input->post('satgasbpn'),
					'nipbpn_spt' => $this->input->post('nipsatgasbpn'),
					'babinsa_spt' => $this->input->post('satgasbabinsa'),
					'idkel_spt' => $idkel,
					'publish_spt' => '1',
					'idusr_spt' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);
				$simpan = $this->crud_model->input('tb_saksiptsl',$dataarray);
				$idnew=$this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_saksiptsl',$idnew,"Menambahkan Data Saksi dengan rincian ".displayArray($dataarray));

				$datasaksi = array(
					'idspt_ptsl' => $idnew,
					'idusr_ptsl' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_ptsl',$datasaksi,array('id_ptsl'=>$id));
			}

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/data/?search=<?php echo $idblok; ?>">
			<?php
		}

		$block['table'] = "tb_block";
		$block['type'] = "single";
		$block['condition']['idblk_blk'] = $idblok;
		$datblock = $this->crud_model->get_data($block);

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$kec['table'] = "ms_kelurahan";
		$kec['type'] = "single";
		$kec['column'] = "nma_kel,nma_kec";
		$kec['join']['table'] = 'ms_kecamatan';
		$kec['join']['key'] = 'kd_kec';
		$kec['join']['ref'] = 'kdkec_kel';
		$kec['condition']['kd_full'] = $datblock['idkel_blk'];
		$this->content['kecamatan'] = $this->crud_model->get_data($kec);

			$this->content['data']['title'] = "Aplikasi Pengisian Blangko Permohonan";
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblok),array("Form Saksi","Studio_3_2/formsaksi/".$id."/".$idblok));

			$this->content['status'] = "tambah";

			$this->content['load'] = array("studio3/form_saksi");
			$this->load->view('adm',$this->content);
		}

		public function formpanitia($id,$idblok){
			$user = $this->auth_model->get_userdata();
			$ptsl['table'] = "tb_ptsl";
			$ptsl['type'] = "single";
			$ptsl['condition']['id_ptsl'] = $id;
			$datptsl = $this->crud_model->get_data($ptsl);

			if($datptsl){
				$saksi['table'] = "tb_panitia";
				$saksi['type'] = "single";
				$saksi['condition']['id_pnt'] = $datptsl['idpnt_ptsl'];
				$this->content['panitia'] = $this->crud_model->get_data($saksi);
			}else{
				$saksi['table'] = "tb_panitia";
				$saksi['type'] = "single";
				$saksi['condition']['idptsl_pnt'] = $id;
				$this->content['panitia'] = $this->crud_model->get_data($saksi);
			}


			$block = $this->studio_2_1_model->sr_name_block($idblok);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);
			$idkel = $block['idkel_blk'];

			if ($this->input->post()) {

				$user = $this->auth_model->get_userdata();

				$datpanitia['table'] = "tb_panitia";
				$datpanitia['type'] = "single";
				$datpanitia['condition']['tim_pnt'] = $this->input->post('tim');
				$datpanitia['condition']['no_pnt'] = $this->input->post('no');
				$datpanitia['condition']['tgl_pnt'] = date("Y-m-d",strtotime($this->input->post('tgl')));
				$datpanitia['condition']['ketua_pnt'] = $this->input->post('ketua');
				$datpanitia['condition']['nipketua_pnt'] = $this->input->post('nipketua');
				$datpanitia['condition']['wakafis_pnt'] = $this->input->post('fisik');
				$datpanitia['condition']['wakayur_pnt'] = $this->input->post('yuridis');
				$datpanitia['condition']['sekre_pnt'] = $this->input->post('sekre');
				$datapanitia = $this->crud_model->get_data($datpanitia);

				if(!$datapanitia){
					$dataarray = array(
						'tim_pnt' => $this->input->post('tim'),
						'no_pnt' => $this->input->post('no'),
						'tgl_pnt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'ketua_pnt' => $this->input->post('ketua'),
						'nipketua_pnt' => $this->input->post('nipketua'),
						'wakafis_pnt'   => $this->input->post('fisik'),
						'wakayur_pnt'   => $this->input->post('yuridis'),
						'sekre_pnt' => $this->input->post('sekre'),
						'idusr_pnt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);
					$simpan = $this->crud_model->input('tb_panitia',$dataarray);
					$idnew=$this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_panitia',$idnew,"Menambahkan Data Panitia dengan rincian ".displayArray($dataarray));

					$datasaksi = array(
						'idpnt_ptsl' => $idnew,
						'idusr_ptsl' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_ptsl',$datasaksi,array('id_ptsl'=>$id));
				}

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_2/data/?search=<?php echo $idblok; ?>">
				<?php
			}

				$this->content['data']['title'] = "Susunan Panitia";
				$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblok),array("Form Panitia","Studio_3_2/formpanitia/".$id."/".$idblok));

				$this->content['status'] = "tambah";

				$this->content['load'] = array("studio3/form_panitia");
				$this->load->view('adm',$this->content);
			}

		public function delete($kode)
		{
			$ar = array(
				'publish_ptsl' => '0'
			);
			$hapus = $this->crud_model->update('tb_ptsl',$ar,array('id_ptsl'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$kode,"Menghapus Data PTSL dengan kode ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function deleteregister($kode)
		{
			$ar = array(
				'publish_nub' => '0'
			);
			$hapus = $this->crud_model->update('tb_nub',$ar,array('idnub_nub'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub',$kode,"Menghapus Data Register dengan kode ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function export($id,$idblock){

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$id,"Export Data PTSL dengan kode ".$id);

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
			  require(APPPATH .'plugins/fpdf/fpdf.php');

				$ptsl['type']                   = "single";
				$ptsl['table']                  = "tb_ptsl";
				$ptsl['condition']['id_ptsl'] = $id;
				$data                        = $this->crud_model->get_data($ptsl);

				$pdk['table'] = "tb_penduduk";
				$pdk['type'] = "single";
				$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
				$pdk['join']['table'] = "tb_pekerjaan";
				$pdk['join']['key'] = "idpkr_pkr";
				$pdk['join']['ref'] = "idpeker_pdk";
				$datpdk = $this->crud_model->get_data($pdk);

				if($data['idspt_ptsl']==0){
					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idptsl_spt'] = $id;
					$datsaksi = $this->crud_model->get_data($saksi);
				}else{
					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idspt_spt'] = $data['idspt_ptsl'];
					$datsaksi = $this->crud_model->get_data($saksi);
				}

				$saksi1['table'] = "tb_penduduk";
				$saksi1['type'] = "single";
				$saksi1['condition']['noktp_pdk'] = $datsaksi['niksp1_spt'];
				$saksi1['join']['table'] = "tb_pekerjaan";
				$saksi1['join']['key'] = "idpkr_pkr";
				$saksi1['join']['ref'] = "idpeker_pdk";
				$datsaksi1 = $this->crud_model->get_data($saksi1);

				$saksi2['table'] = "tb_penduduk";
				$saksi2['type'] = "single";
				$saksi2['condition']['noktp_pdk'] = $datsaksi['niksp2_spt'];
				$saksi2['join']['table'] = "tb_pekerjaan";
				$saksi2['join']['key'] = "idpkr_pkr";
				$saksi2['join']['ref'] = "idpeker_pdk";
				$datsaksi2 = $this->crud_model->get_data($saksi2);

				$block['table'] = "tb_block";
				$block['type'] = "single";
				$block['condition']['idblk_blk'] = $idblock;
				$datblock = $this->crud_model->get_data($block);

				$kec['table'] = "ms_kelurahan";
				$kec['type'] = "single";
				$kec['column'] = "nma_kel,nma_kec,kd_full";
				$kec['join']['table'] = 'ms_kecamatan';
				$kec['join']['key'] = 'kd_kec';
				$kec['join']['ref'] = 'kdkec_kel';
				$kec['condition']['kd_full'] = $datblock['idkel_blk'];
				$kecamatan = $this->crud_model->get_data($kec);

				$user = $this->auth_model->get_userdata();

			    $pdf = new FPDF('p','mm',array(210,330));
			    $pdf -> AddPage();
			    $pdf -> setDisplayMode ('fullpage');
			    $pdf -> Image("./assets/img/bpn.png",10,15,20);
			    $row = 20;
			    $pdf -> setFont ('Times','',10);
			    $pdf -> setXY(43,$row); $pdf->Cell(0,0,"KEMENTRIAN AGRARIA DAN TATA RUANG /");

			    $row +=4.5;
			    $pdf -> setXY(43,$row); $pdf->Cell(0,0,"BADAN PERTANAHAN NASIONAL REPUBLIK INDONESIA");

			    $row +=4.5;
			    $pdf -> setXY(43,$row); $pdf->Cell(0,0,"KANTOR PERTANAHAN KABUPATEN Semarang");

			    $pdf -> setXY(163,$row); $pdf->Cell(0,0,"Klaster : .......");

					$row +=7.5;
					$pdf -> setFont ('Times','B',12);
					$pdf -> setXY(43,$row); $pdf->Cell(0,0,"INVENTARISASI DAN IDENTIFIKASI PERSERTA PENDAFTARAN");
					$row +=6.5;
					$pdf -> setXY(83,$row); $pdf->Cell(0,0,"TANAH SISTEMATIS LENGKAP");

					$row +=6.5;
					$pdf -> setFont ('Times','',10);
					$pdf -> rect(10, $row, 190, 15);
					$row +=5.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Nomor Urut");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(52,$row); $pdf->Cell(0,0,"........................");
					$row +=5.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Desa/Kecamatan");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']." / ".$kecamatan['nma_kec']);

					// KOLOM 1
					$row +=4;
					$pdf -> rect(10, $row, 190, 70);
					$row +=5.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"I. IDENTIFIKASI SUBYEK");
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1. Perorangan");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"a. Nama");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. Tempat/Tanggal Lahir");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Nomor Identitas");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Alamat");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['almat_pdk']);
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"e. Pekerjaan");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Badan Hukum");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"a. Nama");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. Nomor Akta Pendirian");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Alamat");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Jenis Usaha **");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,"Komersial (nonprofitoriented)/ Keagamaan/ Sosial");

					// KOLOM 2
					$row +=4;
					$pdf -> rect(10, $row, 190, 185);
					$row +=4.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"II. IDENTIFIKASI OBYEK");
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1. Letak Tanah");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"a. Jalan");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. RT/RW");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. No Bidang / NIB");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,"");
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Luas");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['dluas_ptsl']);
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3. Status Tanah yang Dikuasai *");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(80, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah Negara");
					$pdf -> setXY(85,$row); $pdf->Cell(0,0,"Lain-lain ***");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(85, $row, 110, 20);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah Milik Adat");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah Wakaf");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah Hak (HM/HGB/HP)");
					$row +=8.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"4. Penggunaan /Pemanfaatan *");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(80, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Rumah Tinggal");
					$pdf -> setXY(85,$row); $pdf->Cell(0,0,"Tempat Ibadah");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(80, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Pertanian");
					$pdf -> setXY(85,$row); $pdf->Cell(0,0,"Permakaman");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(80, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Toko/Ruko");
					$pdf -> setXY(85,$row); $pdf->Cell(0,0,"Lain-lain ***");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(85, $row, 110, 10);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Kantor");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Pabrik");
					$row +=8.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"5. Bukti Penguasaan *");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Girik/Petok/Pipil/Ketitir/Kartu Penunjukan/Kartu Kavling/Verponding Indonesia **");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Surat Keterangan Garapan/Surat Keterangan Tanah/Surat Jual Beli di Bawah Tangan **");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"IPPEDA/IREDA/PBB**");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Akta Peralihan (Jual Beli/ Hibah/Waris/Tukar Menukar/Pembagian Hak Bersama/Inbreng)**");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Akta Ikrar Wakaf/Pengganti Akta Ikrar Wakaf **");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Surat Pernyataan Penguasaan Pemilikan Tanah");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Sertipikat Hak Atas Tanah Nomor ....");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Lain-Lain ***");
					$row +=4;
					$pdf -> rect(25, $row, 170, 10);

					// HALAMAN 2
					$pdf -> AddPage();
					$row=10;
					$pdf -> rect(10, $row, 190, 42);
					$row +=4.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"6. Status Sengketa");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$pdf -> rect(80, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tidak Ada Sengketa");
					$pdf -> setXY(85,$row); $pdf->Cell(0,0,"Ada Sengketa ***");
					$row +=4;
					$pdf -> rect(20, $row, 175, 20);

					// KOLOM
					$row +=27;
					$pdf -> rect(10, $row, 190, 50);
					$row +=5.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"III. CATATAN TERLAMPIR *");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Identitas Subyek ** (KTP/SIM/Akta Pendirian Badan Hukum/ Lain-lain)");
					$row +=5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Lain-lain ........................... (diisi sesuai yang dilampirkan)");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bukti Penguasaan .................... (diisi sesuai yang dilampirkan)");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bukti Penguasaan** (IMB/Lain-lain*** .......(diisi sesuai yang dilampirkan)");
					$row +=4.5;
					$pdf -> rect(20, $row, 4, 4);
					$row +=2;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bukti Perolehan Hak** (Akta Jual Beli/Akta Tukar Menukar/Akta Ikrar");
					$row +=5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Wakaf/Waris/Lain-lain) Lain-lain..........................................(diisi sesuai yang dilampirkan)");
					$row +=8.5;
					$pdf -> rect(10, $row, 190, 55);
					$row +=4.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Mengetahui,");
					$row +=1.5;
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Semarang , ..........................");
					$row +=5.5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Petugas Pengumpul Data Yuridis");
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Peserta Pendaftaran Tanah Sistematis");
					$row +=30;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,$datsaksi['bpn_spt']);
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);

					$row +=5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"NIP. ".$datsaksi['nipbpn_spt']);

					$row +=20;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Keterangan :");
					$pdf->SetFont('Times','I',10);
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"*     centang jika ada");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"**    coret yang tidak diperlukan");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"***   uraikan secara singkat");


					// SURAT 2
					$pdf -> AddPage();
					$row =8;
					$row +=5;
					$pdf->SetFont('Times','',10);
					$row +=7;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN");

					$row +=10;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan / cap jempol di bawah ini :");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					if($datpdk['agm_pdk']==1){
						$agama='Islam';
					}else if($datpdk['agm_pdk']==2){
						$agama='Kristen';
					}else if($datpdk['agm_pdk']==3){
						$agama='Katholik';
					}else if($datpdk['agm_pdk']==4){
						$agama='Budha';
					}else if($datpdk['agm_pdk']==5){
						$agama='Hindu';
					}
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$agama);

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tgl Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$diff = date_diff(date_create($datpdk['ttg_pdk']), date_create(date('Y-m-d')));
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['almat_pdk']);

					$row +=10;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan dengan sesungguhnya serta dengan itikad baik bahwa saya menguasai/memiliki");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"sebidang tanah yang terletak di :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan / Blok");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$kecamatan['nma_kec']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,'Semarang');
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Penggunaan Tanah");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");

					if($data['idguna_ptsl']==1){
						$guna='Perumahan';
					}else if($data['idguna_ptsl']==2){
						$guna='Pekarangan';
					}else if($data['idguna_ptsl']==3){
						$guna='Sawah';
					}else if($data['idguna_ptsl']==4){
						$guna='Tegalan';
					}else{
						$guna='Tidak terdefinisi';
					}
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$guna);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Luas");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$data['dluas_ptsl'].' m2');

					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"dengan batas-batas tanah sebagai berikut :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Utara");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$data['utara_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Timur");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$data['timur_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Selatan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$data['selatan_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Barat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$data['barat_ptsl']);
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Bahwa bidang tanah tersebut saya kuasai/miliki sejak tahun ".$data['thn_ptsl']." yang sampai saat ini terhadap");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"bidang tanah dimaksud :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Saya kuasai/miliki secara fisik dan terus menerus.");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tidak dijadikan sesuatu hutang.");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tidak dalam keadaan sengketa atau keberatan dari pihak manapun");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bukan merupakan asset Pemerintah/Pemerintah Provinsi/Kabupaten/Kota/Desa/");
					$row +=7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Kelurahan/BUMN/BUMD");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tidak berada/tidak termasuk dalam kawasan hutan");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bea Perolehan Hak Atas Tanah dan Bangunan (BPHTB) yang dikenakan atas perolehan hak");
					$row +=7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"atas tanah yang dimohon, menjadi tanggung jawab saya sepenuhnya dan merupakan pajak");
					$row +=7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"atau bea terhutang bagi saya yang wajib dan akan saya lunasi dengan nilai NJOP ".$data['njop_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.");
					if($data['idkperluan_ptsl']==1){
						$keperluan="Jual Beli";
					}else if($data['idkperluan_ptsl']==2){
						$keperluan="Waris";
					}else if($data['idkperluan_ptsl']==3){
						$keperluan="Hibah";
					}else{
						$keperluan="undefined";
					}
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah tersebut saya peroleh melalui ".$keperluan." maka Pajak Penghasilan (PPh) yang");
					$row +=7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"dikenakan atas perolehan hak atas tanah saya menjadi tanggung jawab sepenuhnya dan");
					$row +=7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"merupakan pajak atau bea terhitung bagi saya dan yang wajib dilunasi oleh saya;");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Di atas tanah yang dikuasai belum pernah diterbitkan sesuatu hak atas tanah/sertipikat");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"9.");
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanah tersebut bukan merupakan harta warisan yang belum terbagi;");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"10.");
					$pdf -> setXY(27,$row); $pdf->Cell(0,0,"Bahwa alat bukti hak atas tanah yang menjadi dasar pendaftaran PTSL ini adalah");
					$row +=7;
					$pdf -> setXY(27,$row); $pdf->Cell(0,0,"betul-betul yang saya punyai dan apabila terdapat bukti pemilikan/penguasaan");
					$row +=7;
					$pdf -> setXY(27,$row); $pdf->Cell(0,0,"atas tanah dimaksud setelah dibuatnya pernyataan ini dan/atau telah diterbitkan");
					$row +=7;
					$pdf -> setXY(27,$row); $pdf->Cell(0,0,"sertipikat maka dinyatakan tidak berlaku.");

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Surat Pernyataan ini saya buat dengan sebenar-benarnya dengan penuh tanggungjawab baik");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"secara perdata maupun secara pidana, dan apabila di kemudian hari terdapat unsur-unsur yang");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"tidak benar dalam surat pernyataan ini maka segala akibat yang timbul menjadi tanggung jawab");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"saya sepenuhnya dan bersedia dituntut sesuai ketentuan hukum yang berlaku, serta tidak akan");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"melibatkan Kementrian Agraria dan Tata Ruang/Badan Pertanahan Nasional, dan saya bersedia");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"atas sertipikat yang saya terima dibatalkan oleh pejabat yang berwenang.");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Demikian Surat Pernyataan ini Saya buat dihadapan saksi saksi :");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1. ");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$datsaksi1['nma_pdk']);
					$pdf->SetFont('Times','',12);

					$pdf -> setXY(105,$row); $pdf->Cell(0,0,"2. ");
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,$datsaksi2['nma_pdk']);
					$pdf->SetFont('Times','',12);

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$datsaksi1['noktp_pdk']);

					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,$datsaksi2['noktp_pdk']);

					if($datsaksi1['agm_pdk']==1){
						$agama1='Islam';
					}else if($datsaksi1['agm_pdk']==2){
						$agama1='Kristen';
					}else if($datsaksi1['agm_pdk']==3){
						$agama1='Katholik';
					}else if($datsaksi1['agm_pdk']==4){
						$agama1='Budha';
					}else if($datsaksi1['agm_pdk']==5){
						$agama1='Hindu';
					}else{
						$agama1='belum terdaftar';
					}

					if($datsaksi2['agm_pdk']==1){
						$agama2='Islam';
					}else if($datsaksi2['agm_pdk']==2){
						$agama2='Kristen';
					}else if($datsaksi2['agm_pdk']==3){
						$agama2='Katholik';
					}else if($datsaksi2['agm_pdk']==4){
						$agama2='Budha';
					}else if($datsaksi2['agm_pdk']==5){
						$agama2='Hindu';
					}else{
						$agama2='belum terdaftar';
					}

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$agama1);

					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,$agama2);

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$datsaksi1['nama_pkr']);

					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,$datsaksi2['nama_pkr']);

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$datsaksi1['almat_pdk']);

					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,$datsaksi2['almat_pdk']);

					$row +=9;
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Dibuat di  : Semarang");
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SAKSI-SAKSI");
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Tanggal   : ".fdate(date("Y-m-d"),'DDMMYYYY'));
					$row +=8;
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan");

					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
					$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".$datsaksi1['nma_pdk']."    )",0,0,'L');
					$pdf->SetFont('Times','I',10);
					$pdf -> setXY(120,$row); $pdf->Cell(50,0,"Materai Rp.6000");
					$pdf->SetFont('Times','',12);
					$row +=30;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
					$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".$datsaksi2['nma_pdk']."    )",0,0,'L');
					$pdf -> setXY(130,$row); $pdf->Cell(50,0,"(    ".$datpdk['nma_pdk']."    )",0,0,'L');
					$row +=20;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"(    ".$datsaksi['kades_spt']."    )",0,0,'C');

					// SURAT 3
					$pdf -> AddPage();
					$row =0;
					$pdf -> Image("./assets/img/Semarang.png",15,5,15);
					$row +=10;
					$pdf->SetFont('Times','B',15);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"PEMERINTAHAN KABUPATEN Semarang",0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"KECAMATAN ".strtoupper($kecamatan['nma_kec']),0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"KELURAHAN ".strtoupper($kecamatan['nma_kel']),0,0,'C');

					$row +=5;
					$pdf -> SetLineWidth(1.5);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=2;
					$pdf -> SetLineWidth(0.5);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=8;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"SURAT KETERANGAN TANAH BEKAS MILIK ADAT",0,0,'C');
					$pdf->SetFont('Times','',12);
					$row +=8;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nomor : ".$datsaksi['nosurat_spt'],0,0,'C');
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini Kepala Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang, dengan");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"ini menerangkan sebagai berikut :");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Bahwa sebidang tanah seluas : ".$data['dluas_ptsl']." M2 yang terletak di Dk/Blok ".$datblock['nama_blk']." Desa ".$kecamatan['nma_kel']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang adalah tanah bekas milik adat sesuai Letter C Desa Nomor ");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." luas ".$data['dluas_ptsl']." m2 yang penggunaannya berupa ".$guna." dengan batas-batasnya.");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Utara");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['utara_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Timur");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['timur_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Selatan");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['selatan_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Barat");
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['barat_ptsl']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"benar-benar dikuasai oleh : ".$datpdk['nma_pdk']." dan secara fisik dikerjakan sendiri secara aktif oleh yang");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"bersangkutan.");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Bahwa riwayat tanah tersebut menurut catatan Buku C Desa ".$kecamatan['nma_kel']." adalah sebagai berikut :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a. ");
					$row -=2;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc0_ptsl']);
					$row +=13;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc1_ptsl']);
					$row +=13;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"c. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc2_ptsl']);
					$row +=18;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"d. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc3_ptsl']);
					$row +=18;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"e. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc4_ptsl']);
					$row +=18;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
					$row -=2.5;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Bahwa tanah tersebut bukan merupakan aset pemerintah/ bukan tanah bengkok atau tanah tanah pihak lain dan tidak termasuk dalam kawasan hutang baik sebagian maupun seluruhnya (baik kepemilikannya maupun batas-batasnya.)");
					$row +=18;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"4.");
					$row -=2.5;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Bahwa tanah tersebut tidak sedang menjadi jaminan suatu hutang dan tidak dalam sengketa dengan pihak lain;");
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"5.");
					$row -=2.5;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Surat Keterangan ini bukan merupakan bukti kepemilikan tanah, tetapi hanya dipergunakan sebagai kelengkapan permohonan pendaftaran hak atas bekas milik adat.");
					$row +=14;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya");
					$row +=17;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Semarang, ".fdate(date('Y-m-d'),'DDMMYYYY'),0,0,'C');
					$row +=5;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
					$row +=5;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=20;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,$datsaksi['kades_spt'],0,0,'C');

					// SURAT 4
					$pdf -> AddPage();
					$row =10;

					$row +=5;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS",0,0,'C');
					$row +=10;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
					$row +=7;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
					$pdf->SetFont('Times','',12);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$agama);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia/ Tgl Lahir");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->MultiCell(0,0,$datpdk['almat_pdk']);
					$row +=3;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Selaku pemilik tanah/pemohon pengukuran tanah bekas adat/yasan C Desa No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." seluas ".$data['dluas_ptsl']." m2, dipergunakan untuk ".$guna." terletak di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang.");
					$row +=18;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini menyatakan sebenar-benarnya :");
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa tanah yang kami mohonkan pengukuran di Kantor Pertanahan Kabupaten Semarang berdasarkan alas hak tersebut diatas tidak dalam jaminan sesuatu hutang, tidak diletakkan sita jaminan dan telah kami pasang tanda-tanda batasnya sesuai ketentuan yang berlaku, berupa Patok Beton.");
					$row +=23;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa pemasangan tanda-tanda tanah serta batas-batas tanah yang secara fisik telah ada di lapangan, sudah disepakati dan disetujui oleh para pemilik tanah yang berbatasan dengan bukti ditanda tanganinya surat pernyataan ini oleh kami pemilik tanah dan para pemilik tanah yang berbatasan.");
					$row +=18;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa dengan dipasangnya tanda-tanda batas tanah serta batas-batas tanah yang telah ada dan sudah disepakati/disetujui, apabila dalam pengukurannya oleh petugas dari Kantor Pertanahan para pemilik tanah yang berbatasan tidak dapat hadir menyaksikan, dengan ini kami pemilik tanah bertindak sebagai penunjuk batas dan para pemilik tanah yang berbatasan telah menyepakati/menyetujui dan tanda tangan persetujuan batas dalam surat pernyataan ini dapat berlaku pula sebagai persetujuan yang sah untuk dokumen Gambar Ukur dan dokumen pengukuran lainnya.");
					$row +=33;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Apabila hasil pengukuran dari Kantor Pertanahan berdasarkan batas tanah yang telah disepakati/disetujui tersebut telah terjadi perbedaan (lebih besar atau lebih kecil) dengan luas yang tertera dalam alas hak/ permohonan kami pemilik tanah maupun para pemilik tanah yang berbatasan menyetujui dan menerima luas hasil pengukuran tersebut tanpa syarat apapun, selanjutnya apabila dikemudian hari ada pihak-pihak yang keberatan/diragukan atas hasil pengukuran tersebut kami akan mempertanggungjawabkan secara hukum baik secara perdata maupun pidana.");
					$row +=33;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa kami menyatakan melepaskan sebagian tanah kami seluas ......... m2. Kepada Negara untuk kepentingan umum/fasilitas umum yang dipergunakan untuk (jalan,saluran,taman............*)");
					$row +=12;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Apabila dikemudian hari ternyata isi surat pernyataan kami ini tidak benar maka kami bersedia menerima sanksi hukum sesuai ketentuan yang berlaku.");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Demikian Surat Pernyataan ini kami buat dengan sebenar-benarnya dengan penuh tanggung jawab tanpa ada tekanan atau paksaan dari manapun juga.");
					$row +=17;
					$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Semarang",0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Yang Menyatakan",0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Pemilik Tanah yang berbatasan,");
					$row +=6;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Utara");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setXY(45,$row); $pdf->Cell(0,5,$data['utara_ptsl']);
					$pdf -> setXY(75,$row); $pdf->Cell(0,5,"(..................)");
					$row +=6;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Timur");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setXY(45,$row); $pdf->Cell(0,5,$data['timur_ptsl']);
					$pdf -> setXY(75,$row); $pdf->Cell(0,5,"(..................)");
					$pdf->SetFont('Times','I',10);
					$pdf -> setXY(125,$row); $pdf->Cell(0,5,"materai 6.000,-");
					$pdf->SetFont('Times','',12);
					$row +=6;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Selatan");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setXY(45,$row); $pdf->Cell(0,5,$data['selatan_ptsl']);
					$pdf -> setXY(75,$row); $pdf->Cell(0,5,"(..................)");
					$row +=6;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Barat");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setXY(45,$row); $pdf->Cell(0,5,$data['barat_ptsl']);
					$pdf -> setXY(75,$row); $pdf->Cell(0,5,"(..................)");
					$pdf -> setXY(120,$row); $pdf->Cell(0,5,$datpdk['nma_pdk'],0,0,'C');
					$row +=20;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Mengetahui",0,0,'C');
					$row +=4;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=20;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,$datsaksi['kades_spt'],0,0,'C');

				$pdf->Output();
			}

			public function exportrisalah($id,$idblock){

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$id,"Export Risalah PTSL dengan kode ".$id);

					define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');

					$ptsl['type']                   = "single";
					$ptsl['table']                  = "tb_ptsl";
					$ptsl['condition']['id_ptsl'] = $id;
					$data                        = $this->crud_model->get_data($ptsl);

					$pdk['table'] = "tb_penduduk";
					$pdk['type'] = "single";
					$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
					$pdk['join']['table'] = "tb_pekerjaan";
					$pdk['join']['key'] = "idpkr_pkr";
					$pdk['join']['ref'] = "idpeker_pdk";
					$datpdk = $this->crud_model->get_data($pdk);

					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idspt_spt'] = $data['idspt_ptsl'];
					$datsaksi = $this->crud_model->get_data($saksi);

					$panitia['table'] = "tb_panitia";
					$panitia['type'] = "single";
					$panitia['condition']['id_pnt'] = $data['idpnt_ptsl'];
					$datpanitia = $this->crud_model->get_data($panitia);

					$saksi1['table'] = "tb_penduduk";
					$saksi1['type'] = "single";
					$saksi1['condition']['noktp_pdk'] = $datsaksi['niksp1_spt'];
					$saksi1['join']['table'] = "tb_pekerjaan";
					$saksi1['join']['key'] = "idpkr_pkr";
					$saksi1['join']['ref'] = "idpeker_pdk";
					$datsaksi1 = $this->crud_model->get_data($saksi1);

					$saksi2['table'] = "tb_penduduk";
					$saksi2['type'] = "single";
					$saksi2['condition']['noktp_pdk'] = $datsaksi['niksp2_spt'];
					$saksi2['join']['table'] = "tb_pekerjaan";
					$saksi2['join']['key'] = "idpkr_pkr";
					$saksi2['join']['ref'] = "idpeker_pdk";
					$datsaksi2 = $this->crud_model->get_data($saksi2);

					$block['table'] = "tb_block";
					$block['type'] = "single";
					$block['condition']['idblk_blk'] = $idblock;
					$datblock = $this->crud_model->get_data($block);

					$kec['table'] = "ms_kelurahan";
					$kec['type'] = "single";
					$kec['column'] = "nma_kel,nma_kec,kd_full";
					$kec['join']['table'] = 'ms_kecamatan';
					$kec['join']['key'] = 'kd_kec';
					$kec['join']['ref'] = 'kdkec_kel';
					$kec['condition']['kd_full'] = $datblock['idkel_blk'];
					$kecamatan = $this->crud_model->get_data($kec);

					$user = $this->auth_model->get_userdata();

				    $pdf = new FPDF('p','mm',array(210,330));
				    $pdf -> AddPage();
				    $pdf -> setDisplayMode ('fullpage');
						// HALAMAN 1
				    $row = 10;
				    $pdf -> setFont ('Times','',7);
				    $pdf -> setXY(20,$row); $pdf->Cell(0,0,"KEMENTRIAN AGRARIA DAN TATA RUANG / PERTANAHAN NASIONAL");
						$row +=4;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"KANTOR PERTANAHAN KABUPATEN Semarang");
						$pdf -> setFont ('Times','I',7);
						$pdf -> setXY(183,$row); $pdf->Cell(0,0,"DI 201");
						$pdf -> setFont ('Times','',14);
						$row +=8;
						$pdf -> setXY(0,$row); $pdf->Cell(0,0,"RISALAH PENELITIAN DATA YURIDIS",0,0,'C');
						$pdf -> setFont ('Times','',10);
						$row +=12;
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
						$pdf -> setXY(100,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']);
						$row +=5;
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,"NIB");
						$pdf -> setXY(100,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(103,$row); $pdf->Cell(0,0,"");
						$row +=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"I.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"IDENTIFIKASI BIDANG TANAH YANG BERKEPENTINGAN");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"BIDANG TANAH");
						$row +=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"LETAK TANAH");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan/Blok :                 / ".$datblock['nama_blk']);
						$pdf -> setXY(102,$row); $pdf->Cell(0,0,"RT/RW : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"YANG BERKEPENTINGAN");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nama");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
						$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Perorangan/Badan Hukum");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"KTP/NIK (Perorangan)");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['almat_pdk']);
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Badan Hukum");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,"Badan Hukum / Pemda Tk.I.II / Desa / Kelurahan / BUMN");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Akta Pendirian");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0," No. ............................ tanggal .......................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Didaftar di Pengadilan Negeri ............................ tanggal ........................ No. .....................");
						$row +=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Perhatian : ");
						$pdf -> setXY(57,$row); $pdf->Cell(0,0,"Bila yang berkepentingan terdiri dari satu (1) orang, sehingga ruag ini tidak muat,");
						$row +=4;
						$pdf -> setXY(57,$row); $pdf->Cell(0,0,"dapat disertai lampiran.");
						$pdf -> setFont ('Times','I',10);
						$pdf -> setXY(92,$row); $pdf->Cell(0,0,"PERHATIAN LIHAT LAMPIRAN");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setFont ('Times','',10);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"II.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"DATA TENTANG PEMILIKAN DAN PENGUASAAN HAK ATAS TANAH");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bukti-Bukti Pemilikan/Penguasaan :");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sertipikat");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"HM / HGU / HP / HPL");
						$pdf -> setFont ('Times','I',10);
						$pdf -> setXY(113,$row); $pdf->Cell(0,0,"( Potensi K4 )");
						$pdf -> setFont ('Times','',10);
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Atas nama");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"............................ No. ............ Tanggal .................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Warisan");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Nama Pewaris");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Meninggal tahun");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$row +=4;
						$pdf -> rect(20, $row, 10,14);
						$pdf -> rect(30, $row, 10,14);
						$pdf -> rect(40, $row, 150,14);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Surat Keterangan Waris");
						$pdf -> setXY(76,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Ada        Tidak ada        (Lengkapi dengan Surat Keterangan Waris)");
						$row -=3;
						$pdf -> rect(86, $row, 5,5);
						$pdf -> rect(107, $row, 5,5);
						$row +=9;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Surat wasiat");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Ada        Tidak ada        (Lengkapi dengan Surat Keterangan Waris)");
						$row -=3;
						$pdf -> rect(86, $row, 5,5);
						$pdf -> rect(107, $row, 5,5);
						$row +=7;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"c.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Hibah/Pemberian");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,$data['ddari_ptsl']);
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Dilakukan dengan");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Surat di bawah tangan / akta PPAT / Lisan");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Tanggal        : .................... No.Akta PPAT : .................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Nama PPAT : .............................................................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"d.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Pembelian");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Dilakukan dengan");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Surat di bawah tangan / Kwitansi / akta PPAT / lisan");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Tanggal      : .......................... No.Akta PPAT : ......................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Nama PPAT : ..................................................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"e.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Pelelangan");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Risalah Lelang : Tahun ................ Tanggal ................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Tempat dan Nama Kantor Lelang : .........................................");

						// HALAMAN 2
						$pdf -> AddPage();
				    $row = 10;
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row+28, 190, $row-4);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"f.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Putusan Pemberian Hak :");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Jabatan Pejabat yang Memutuskan :");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Surat Keputusan No. ....................... Tanggal ........................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Prasyaratnya : Telah Dipenuhi :                 Belum dipenuhi :");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"g.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"pewakafan");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Akta pengganti Ikrar Wakaf No : .....................");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Tanggal : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 10,15);
						$pdf -> rect(40, $row, 150,15);
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Nadzir/Nadzir Sementara     :");
						$pdf -> setFont('Times','I');
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"(Apabila Nadzir sementara, maka mengacu pad aSurat edaran Menteri");
						$row +=4;
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,"ATR/KBPN Nomor 3 Tahun 2018)");
						$row +=3;
						$pdf -> rect(20, $row, 10,18);
						$pdf -> rect(30, $row, 10,18);
						$pdf -> rect(40, $row, 150,18);
						$row +=4;
						$pdf -> setFont('Times','');
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"h.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Lain-Lain sebutkan :");
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"(Apabila bukti kepemilikan/penguasaan tidak lengkap atau tidak ada sama sekali, maka");
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"dilengkapi dengan Surat Pernyataan Penguasaan/Pemilikan bermaterai yang disaksikan 2 (dua)");
						$row +=4;
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"orang saksi)");
						$row +=2;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 80,8);
						$pdf -> rect(110, $row, 80,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bukti Perpajakan");
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Uraian");
						$row +=4;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 80,15);
						$pdf -> rect(110, $row, 80,15);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Patok D/Letter C, Girik, ketikir :");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']);
						$row +=3;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 80,15);
						$pdf -> rect(110, $row, 80,15);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Verponding Indonesia");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
						$row +=4;
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=7;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 80,15);
						$pdf -> rect(110, $row, 80,15);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c.");
						$pdf -> Line(36, $row, 55, $row);
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"IPEDA / PBB / SPPT");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,$data['nosppt_ptsl']);
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=7;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 80,15);
						$pdf -> rect(110, $row, 80,15);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d.");
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Lain-lain sebutkan : bukti dilampirkan");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
						$row +=4;
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=7;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 80,8);
						$pdf -> rect(110, $row, 80,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bukti Terlampir");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Surat pernyataan/keterangan Terlampir");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kenyataan Penguasaan dan penggunaan Tanah :");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a. Pada tahun ".$data['thn_risalah']." dikuasai/dimiliki oleh :");
						$row +=4;
						$pdf -> rect(20, $row, 10,15);
						$pdf -> rect(30, $row, 160,15);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");
						$row +=3;
						$pdf -> rect(20, $row, 10,18);
						$pdf -> rect(30, $row, 160,18);
						$row +=2;
						$pdf -> rect(80, $row, 3,3);
						$pdf -> rect(100, $row, 3,3);
						$pdf -> rect(120, $row, 3,3);
						$pdf -> rect(145, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Sawah :          Ladang:          Kebun :          Kolam ikan :        Perumahan :       ");
						$row +=3;
						$pdf -> rect(80, $row, 3,3);
						$pdf -> rect(110, $row, 3,3);
						$pdf -> rect(155, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Industri :           Perkebunan :             Dikelola Pengembang :");
						$row +=3;
						$pdf -> rect(95, $row, 3,3);
						$pdf -> rect(135, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lapangan Umum :           Pengembalaan Ternak :");
						$row +=4;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lain;lain : ....................... (sebutkan)");
						$row +=3;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bangunan di Atas Tanah :");
						$row +=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=2;
						$pdf -> rect(75, $row, 3,3);
						$pdf -> rect(95, $row, 3,3);
						$pdf -> rect(115, $row, 3,3);
						$pdf -> rect(135, $row, 3,3);
						$pdf -> rect(150, $row, 3,3);
						$row +=2;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a. Jenisnya : Rumah Hunian :         Gedung :       Kantor :       Bengkel :        Toko :");
						$row +=4;
						$pdf -> rect(65, $row, 3,3);
						$pdf -> rect(95, $row, 3,3);
						$row +=2;
						$pdf -> setXY(52,$row); $pdf->Cell(0,0,"Pagar :         Rumah Ibadah :           lain-lain : ......................(sebutkan)");
						$row +=2;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> rect(68, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Tidak ada bangunan :");
						$row +=3;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 80,8);
						$pdf -> rect(110, $row, 80,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"5.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Status Tanahnya :");
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Uraian");
						$row +=4;
						$pdf -> rect(20, $row, 10,19);
						$pdf -> rect(30, $row, 80,19);
						$pdf -> rect(110, $row, 80,19);
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=2;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a. Tanah dengan Hak Adat Perorangan :");
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Hak milik Adat :    V        Hak Gogol :");
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Hak Sanggan :             Hak Yasan :");
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Hak Anggaduh :             Hak Pekulen :");
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(160, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Hak Norowito :           Hak Lain :    ..........(sebutkan)");
						$row +=3;
						$pdf -> rect(20, $row, 10,17);
						$pdf -> rect(30, $row, 80,17);
						$pdf -> rect(110, $row, 80,17);
						$row +=3;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Tanah Negara :");
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"HPL : Pemda Provinsi/Kabupaten/Kota :");
						$row +=2;
						$pdf -> rect(150, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Dikuasai Departemen :");
						$row +=2;
						$pdf -> rect(155, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Dikuasai secara Perorangan :");
						$row +=2;
						$pdf -> rect(145, $row, 3,3);
						$row +=2;
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row+22, 190, $row-15);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");
						$row +=2;
						$pdf -> rect(20, $row, 10,14);
						$pdf -> rect(30, $row, 80,14);
						$pdf -> rect(110, $row, 80,14);
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Tanah bagi Kepentingan Umum :");
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Tanah Kuburan :         Tanah Pangonan : ");
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(170, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Tanah Pasar :              Tanah lapang : ");
						$row +=2;
						$pdf -> rect(140, $row, 3,3);
						$pdf -> rect(175, $row, 3,3);
						$row +=2;
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Tanah Kas Desa :        Lain-lain sebutkan : ");
						$row +=3;
						$pdf -> rect(20, $row, 10,6);
						$pdf -> rect(30, $row, 160,6);
						$row +=3;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d. Lain-lain sebutkan :");

						// HALAMAN 3
						$pdf -> AddPage();
				    $row = 10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"6.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"beban-Beban Atas Tanah : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,10);
						$pdf -> rect(30, $row, 160,10);
						$row+=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"7.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bangunan Kepentingan Umum dan Sosial (Kalau ada uraikan) : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,10);
						$pdf -> rect(30, $row, 160,10);
						$row+=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row+20, 190, $row-40);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"8.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Perkara/Sengketa Atas Tanah : ");
						$row+=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sedang dalam Perkara (kalau ada uraikan) : ");
						$row+=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sedang dalam Sengketa (kalau ada uraikan) : ");
						$row+=4;
						$pdf -> rect(20, $row, 10,30);
						$pdf -> rect(30, $row, 80,30);
						$pdf -> rect(110, $row, 80,30);
						$row +=4;
						$pdf -> setXY(22,$row); $pdf->Cell(0,0,"B.");
						$pdf -> setXY(32,$row); $pdf->Cell(80,0,"YANG MENGUMPULKAN DATA",0,0,'C');
						$pdf -> setXY(112,$row); $pdf->Cell(80,0,"YANG BERKEPENTINGAN/KUASANYA",0,0,'C');
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(80,0,"(SATGAS YURIDIS)",0,0,'C');
						$row +=18;
						if($datsaksi['bpn_spt']!="" && $datsaksi['babinsa_spt']!=""){
								$pdf -> setXY(24,$row); $pdf->Cell(40,0,"( ".$datsaksi['bpn_spt']." )",0,0,'C');
								$pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
						}else if($datsaksi['bpn_spt']!=""){
								$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['bpn_spt']." )",0,0,'C');
						}else if($datsaksi['babinsa_spt']!=""){
								$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
						}else{
							$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
						}

						$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".$datpdk['nma_pdk']." )",0,0,'C');
						$row+=4;
						$pdf -> rect(20, $row, 10,11);
						$pdf -> rect(30, $row, 160,11);
						$row +=4;
						$pdf -> setXY(22,$row); $pdf->Cell(0,0,"III.");
						$row -=3;
						$pdf -> setXY(32,$row); $pdf->MultiCell(155,5,"KESIMPULAN PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP KANTOR PERTANAHAN KABUPATEN Semarang");
						$row+=10;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Berdasarkan pada penilaian atas fakta dan data yang telah dikumpulkan, maka dengan ini disimpulkan bahwa :");
						$row+=6;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"1.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".$datpdk['nma_pdk']);
						$row+=6;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"2.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Status tanahnya adalah :");
						$row+=6;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row+=2;
						$pdf -> rect(73, $row, 3,3);
						$pdf -> rect(88, $row, 3,3);
						$pdf -> rect(105, $row, 3,3);
						$pdf -> rect(130, $row, 3,3);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Tanah Hak : Milik :       HGU :       HGB :        Hak Pakai:");
						$row+=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row+=2;
						$pdf -> rect(98, $row, 3,3);
						$pdf -> rect(123, $row, 3,3);
						$pdf -> rect(143, $row, 3,3);
						$pdf -> rect(165, $row, 3,3);
						$row+=2;
						$pdf -> setFont ('Times','',20);
						$pdf -> setXY(30,$row); $pdf->Cell(0,0,"O");
						$pdf -> setFont ('Times','',10);
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA :  V   Gogol Tetap :       Pekulen :        Andarbeni:");
						$row+=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 10,12);
						$pdf -> rect(40, $row, 150,12);
						$row+=3;
						$pdf -> rect(111, $row, 3,3);
						$pdf -> rect(128, $row, 3,3);
						$pdf -> rect(185, $row, 3,3);
						$row+=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c.");
						$row+=4;
						$pdf -> rect(66, $row, 3,3);
						$pdf -> rect(98, $row, 3,3);
						$row-=6;
						$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"Tanah negara : Dikuasai langsung oleh negara :     BUMN :     Instansi Pemerintah Pemda Tk.I,II :    Badan Otorita :      Desa/Kelurahan:");
						$row+=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 10,8);
						$pdf -> rect(40, $row, 150,8);
						$row+=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");
						$row+=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 10,12);
						$pdf -> rect(40, $row, 150,12);
						$row+=1;
						$pdf -> Line(122, $row+3, 138, $row+3);
						$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu ".$datpdk['nma_pdk']." dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
						$row+=11;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> rect(98, $row, 3,3);
						$pdf -> rect(130, $row, 3,3);
						$row-=1;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :");
						$row+=7;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> rect(86, $row, 3,3);
						$pdf -> rect(115, $row, 3,3);
						$pdf -> rect(138, $row, 3,3);
						$row-=1;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada :");
						$row+=7;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");
						$row+=6;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ");
						$row+=6;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"dibuat di");
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": Semarang");
						$row+=6;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"tanggal");
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,":");
						$row+=6;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Oleh");
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": Panitia Ajudikasi Tim ".$datpanitia['tim_pnt']);
						$row+=6;
						$pdf -> rect(20, $row, 55,30);
						$pdf -> rect(75, $row, 55,30);
						$pdf -> rect(130, $row, 60,30);
						$row+=3;
						$pdf -> setXY(20,$row); $pdf->Cell(55,0,"KETUA PANITIA AJUDIKASI",0,0,'C');
						$pdf -> setXY(75,$row); $pdf->Cell(55,0,"WAKIL KETUA BIDANG FISIK",0,0,'C');
						$pdf -> setXY(130,$row); $pdf->Cell(60,0,"WAKIL KETUA BIDANG YURIDIS",0,0,'C');
						$row+=23;
						$pdf -> setXY(20,$row); $pdf->Cell(55,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
						$pdf -> setXY(75,$row); $pdf->Cell(55,0,"( ".$datpanitia['wakafis_pnt']." )",0,0,'C');
						$pdf -> setXY(130,$row); $pdf->Cell(60,0,"( ".$datpanitia['wakayur_pnt']." )",0,0,'C');
						$row+=4;
						$pdf -> rect(20, $row, 85,30);
						$pdf -> rect(105, $row, 85,30);
						$row+=3;
						$pdf -> setXY(20,$row); $pdf->Cell(85,0,"KEPALA DESA",0,0,'C');
						$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');
						$row+=23;
						$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".$datsaksi['kades_spt']." )",0,0,'C');
						$pdf -> setXY(105,$row); $pdf->Cell(85,0,"( ".$datpanitia['sekre_pnt']." )",0,0,'C');

						// HALAMAN 4
						$pdf -> AddPage();
				    $row = 10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"IV.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"SANGGAHAN / KEBERATAN ");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, 23, 190, 22);
						$pdf -> Line(20, $row+63, 190, $row);
						$pdf -> Line(20, $row+64, 190, $row+64);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Uraian singkat perkara / sengketa / sanggahan :");
						$row +=4;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
						$row -=2;
						$pdf -> setXY(38,$row); $pdf->MultiCell(150,5,"Terdapat perkara/sengketa/sanggahan mengenai batas/pemilikan tanah antara yang berkepentingan dengan (nama) ............................ Gugatan ke Pengadilan telah diajukan / tidak diajukan");
						$row +=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Selama pengumuman ada / tidak ada yang menyanggah");
						$row +=4;
						$pdf -> rect(20, $row, 10,16);
						$pdf -> rect(30, $row, 160,16);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Penyanggah : .............................................");
						$row +=4;
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Alamat : ................................................................");
						$row +=4;
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"..............................................................................");
						$row +=4;
						$pdf -> rect(20, $row, 10,20);
						$pdf -> rect(30, $row, 160,20);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Alasan Penyanggah : ");
						$row +=4;
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Beserta surat buktinya : ................................................................");
						$row +=4;
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,".......................................................................................................");
						$row +=4;
						$pdf ->setFont('Times','i');
						$pdf -> setXY(38,$row); $pdf->Cell(0,0," ( c dan d diisi bila ada yang menyanggah ) ");
						$pdf ->setFont('Times','');
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Penyelesaian perkara/sengketa/sanggahan :");
						$row +=4;
						$pdf -> rect(20, $row, 10,10);
						$pdf -> rect(30, $row, 160,10);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"V.");
						$row -=4;
						$pdf -> setXY(32,$row); $pdf->MultiCell(155,5,"KESIMPULAN AKHIR KETUA PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP");
						$row +=10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"1.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".$datpdk['nma_pdk']);
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");
						$row +=4;
						$pdf -> rect(20, $row, 10,28);
						$pdf -> rect(30, $row, 160,28);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"a.");
						$row -=2;
						$pdf -> Line(128, $row+18, 140, $row+18);
						$pdf -> Line(155, $row+18, 170, $row+18);
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".$datpdk['nma_pdk']." K1)");
						$row +=26;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"b.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP (K1)");
						$row +=6;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"c.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah dalam proses perkara / sengketa dengan Nomor Perkara .......... tanggal ............ (K2)");
						$row +=10;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"d.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3)");
						$row +=10;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"e.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
						$row +=10;
						$pdf -> rect(20, $row, 10,42);
						$pdf -> rect(30, $row, 160,42);
						$row +=4;
						$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Semarang, ...........................");
						$row +=4;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datpanitia['tim_pnt']."",0,0,'C');
						$row +=4;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"KABUPATEN Semarang",0,0,'C');
						$row +=20;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
						$row +=5;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');
						$row +=5;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Catatan :");
						$pdf -> setFont('Times','I');
						$pdf -> setXY(45,$row); $pdf->Cell(0,0,"coret seluruh kata-kata no.3 bila tidak diperlukan");

						// HALAMAN 5
						$pdf -> AddPage();
				    $row = 10;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$pdf -> setFont('Times','');
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"VI.");
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"KEPUTUSAN KETUA PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP");
						$row +=4;
						$pdf -> rect(20, $row, 170,240);
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor ...... Tahun 2018 tentang PendaftaranTanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".fdate($datpanitia['tgl_pnt'],'DDMMYYYY')." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");
						$row +=34;
						$pdf -> setFont('Times','',18);
						$pdf -> setXY(22,$row); $pdf->Cell(0,0,"O");
						$pdf -> setFont('Times','',10);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$row -=3;
						$pdf -> Line(173, $row+8, 185, $row+8);
						$pdf -> Line(35, $row+13, 80, $row+13);
						$pdf -> Line(160, $row+13, 170, $row+13);
						$pdf -> Line(50, $row+18, 163, $row+18);
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ............... Nomor ................. (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");
						$row +=24;
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row, 190, $row);
						$pdf -> Line(20, $row+40, 190, $row);
						$pdf -> Line(20, $row+40, 190, $row+40);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$row -=3;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan Hak Milik/Hak Guna Bangunan / Hak Pakai (K1)");
						$row +=23;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
						$row -=3;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah yang diuraikan pada Risalah Penelitian Data Yuridis ini ada dalam PERKARA/SENGKETA, sehingga proses sertipikatnya ditunda sampai diterbitkan keputusan lembaga Peradilan yang telah mempunyai kekuatan hukum tetap / hasil musyawarah yang menentukan pihak yang berhak/mediasi. (K2)");
						$row +=28;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"Biaya Perolehan tanah dan Bangunan (BPHTB) :");
						$row+=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a. Lunas");
						$row+=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a. Terhutang sesuai dengan Surat pernyataan Nomor ........ tanggal .........");
						$row +=4;
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"5.");
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"Pajak Penghasilan (PPh)");
						$row+=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a. Lunas");
						$row+=4;
						$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a. Terhutang sesuai dengan Surat pernyataan Nomor ........ tanggal .........");
						$row+=4;
						$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Apabila dikemudian hari ternyata ada bukti yang lebih kuat dan sah yang telah dibuktikan sesuai dengan peraturan perundang-undangan, maka keputusan ini dinyatakan tidak berlaku.");
						$row+=14;
						$pdf -> setXY(123,$row); $pdf->Cell(0,0,"ditetapkan di     : Semarang");
						$row+=6;
						$pdf -> setXY(123,$row); $pdf->Cell(0,0,"pada tanggal      : ");
						$row+=16;
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datpanitia['tim_pnt']."",0,0,'C');
						$row+=4;
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"KABUPATEN Semarang",0,0,'C');
						$row+=24;
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
						$row +=5;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');
						$row +=5;
						$row+=6;
						$pdf -> setXY(23,$row); $pdf->Cell(00,0,"Catatan : ");
						$row+=4;
						$pdf -> setFont('Times','I');
						$pdf -> setXY(23,$row); $pdf->Cell(00,0,"- Berikan tanda lingkaran untuk nomor yang dipilih");
						$row+=4;
						$pdf -> setXY(23,$row); $pdf->Cell(00,0,"- Coret semua kata-kata nomor yang tidak dipilih");

						$pdf->Output();
				}

				public function exportrisalaha3($id,$idblock){

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$id,"Export Risalah A3 PTSL dengan kode ".$id);

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');

					$ptsl['type']                   = "single";
					$ptsl['table']                  = "tb_ptsl";
					$ptsl['condition']['id_ptsl'] = $id;
					$data                        = $this->crud_model->get_data($ptsl);

					$pdk['table'] = "tb_penduduk";
					$pdk['type'] = "single";
					$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
					$pdk['join']['table'] = "tb_pekerjaan";
					$pdk['join']['key'] = "idpkr_pkr";
					$pdk['join']['ref'] = "idpeker_pdk";
					$datpdk = $this->crud_model->get_data($pdk);

					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idspt_spt'] = $data['idspt_ptsl'];
					$datsaksi = $this->crud_model->get_data($saksi);

					$panitia['table'] = "tb_panitia";
					$panitia['type'] = "single";
					$panitia['condition']['id_pnt'] = $data['idpnt_ptsl'];
					$datpanitia = $this->crud_model->get_data($panitia);

					$saksi1['table'] = "tb_penduduk";
					$saksi1['type'] = "single";
					$saksi1['condition']['noktp_pdk'] = $datsaksi['niksp1_spt'];
					$saksi1['join']['table'] = "tb_pekerjaan";
					$saksi1['join']['key'] = "idpkr_pkr";
					$saksi1['join']['ref'] = "idpeker_pdk";
					$datsaksi1 = $this->crud_model->get_data($saksi1);

					$saksi2['table'] = "tb_penduduk";
					$saksi2['type'] = "single";
					$saksi2['condition']['noktp_pdk'] = $datsaksi['niksp2_spt'];
					$saksi2['join']['table'] = "tb_pekerjaan";
					$saksi2['join']['key'] = "idpkr_pkr";
					$saksi2['join']['ref'] = "idpeker_pdk";
					$datsaksi2 = $this->crud_model->get_data($saksi2);

					$block['table'] = "tb_block";
					$block['type'] = "single";
					$block['condition']['idblk_blk'] = $idblock;
					$datblock = $this->crud_model->get_data($block);

					$kec['table'] = "ms_kelurahan";
					$kec['type'] = "single";
					$kec['column'] = "nma_kel,nma_kec,kd_full";
					$kec['join']['table'] = 'ms_kecamatan';
					$kec['join']['key'] = 'kd_kec';
					$kec['join']['ref'] = 'kdkec_kel';
					$kec['condition']['kd_full'] = $datblock['idkel_blk'];
					$kecamatan = $this->crud_model->get_data($kec);

					$user = $this->auth_model->get_userdata();

				    $pdf = new FPDF('L','mm','A3',array(210,330));
				    $pdf -> AddPage();
				    $pdf -> setDisplayMode ('fullpage');

						$row = 90;

					$pdf -> setFont ('ARIAL','B',25);
					$pdf -> setXY(0,$row); $pdf->Cell(200,0,"DAFTAR ISIAN 201",0,0,'C');
					$row+=15;
					$pdf -> setFont ('ARIAL','',14);
					$pdf -> setXY(60,$row); $pdf->Cell(100,0,"Desa / Kelurahan",0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(100,0,":",0,0,'L');
					$pdf -> setXY(105,$row); $pdf->Cell(100,0,$kecamatan['nma_kel'],0,0,'L');

					$row+=10;
					$pdf -> setFont ('ARIAL','',14);
					$pdf -> setXY(60,$row); $pdf->Cell(100,0,"NIB",0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(100,0,":",0,0,'L');
					$pdf -> setXY(105,$row); $pdf->Cell(100,0,"",0,0,'L');


						$pdf -> AddPage();
					// HALAMAN 1 & 5
				    $row = 10;
						$row_b = 10;

					$pdf -> setFont ('Times','',7);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"KEMENTRIAN AGRARIA DAN TATA RUANG / PERTANAHAN NASIONAL");

					$row +=4;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"KANTOR PERTANAHAN KABUPATEN Semarang");
					$pdf -> setFont ('Times','I',7);
					$pdf -> setXY(183,$row); $pdf->Cell(0,0,"DI 201");

					$row +=15;
					$pdf -> setFont ('Times','',14);
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,"RISALAH PENELITIAN DATA YURIDIS");

					$pdf -> setFont ('Times','',9);

					$row +=10;
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']);

					$row +=5;
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,"NIB");
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,"");

					$row +=10;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"I.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"IDENTIFIKASI BIDANG TANAH YANG BERKEPENTINGAN");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"BIDANG TANAH");

					$row +=3;
					$pdf -> rect(20, $row, 10,9.5);
					$pdf -> rect(30, $row, 160,9.5);

					$row +=3.5;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"LETAK TANAH");

					$row +=3.5;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan / Blok :                 / ".$datblock['nama_blk']);
					$pdf -> setXY(102,$row); $pdf->Cell(0,0,"RT / RW : ");

					$row +=2.5;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"YANG BERKEPENTINGAN");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
					$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Perorangan/Badan Hukum");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"KTP/NIK (Perorangan)");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['almat_pdk']);

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Badan Hukum");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,"Badan Hukum / Pemda Tk.I.II / Desa / Kelurahan / BUMN");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Akta Pendirian");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0," No. ............................ tanggal .......................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Didaftar di Pengadilan Negeri ............................ tanggal ........................ No. .....................");

					$row +=3;
					$pdf -> rect(20, $row, 10,9);
					$pdf -> rect(30, $row, 160,9);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Perhatian : ");
					$pdf -> setXY(57,$row); $pdf->Cell(0,0,"Bila yang berkepentingan terdiri dari satu (1) orang, sehingga ruag ini tidak muat,");

					$row +=3;
					$pdf -> setXY(57,$row); $pdf->Cell(0,0,"dapat disertai lampiran.");
					$pdf -> setFont ('Times','I',9);
					$pdf -> setXY(92,$row); $pdf->Cell(0,0,"PERHATIAN LIHAT LAMPIRAN");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setFont ('Times','',9);
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"II.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"DATA TENTANG PEMILIKAN DAN PENGUASAAN HAK ATAS TANAH");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bukti-Bukti Pemilikan/Penguasaan :");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sertipikat");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"HM / HGU / HP / HPL");
					$pdf -> setFont ('Times','I',9);
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,"( Potensi K4 )");

					$pdf -> setFont ('Times','',9);

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Atas nama");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"............................ No. ............ Tanggal .................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Warisan");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Nama Pewaris");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Meninggal tahun");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");

					$row +=3;
					$pdf -> rect(20, $row, 10,9);
					$pdf -> rect(30, $row, 10,9);
					$pdf -> rect(40, $row, 150,9);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Surat Keterangan Waris");
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Ada        Tidak ada        (Lengkapi dengan Surat Keterangan Waris)");

					$row -=2;
					$pdf -> rect(86, $row, 3,3);
					$pdf -> rect(105, $row, 3,3);

					$row +=6;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Surat wasiat");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Ada        Tidak ada        (Lengkapi dengan Surat Keterangan Waris)");

					$row -=2;
					$pdf -> rect(86, $row, 3,3);
					$pdf -> rect(105, $row, 3,3);

					$row +=4;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"c.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Hibah/Pemberian");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,$data['ddari_ptsl']);

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Dilakukan dengan");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Surat di bawah tangan / akta PPAT / Lisan");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Tanggal        : .................... No.Akta PPAT : .................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Nama PPAT : .............................................................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"d.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Pembelian");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Dilakukan dengan");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Surat di bawah tangan / Kwitansi / akta PPAT / lisan");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Tanggal      : .......................... No.Akta PPAT : ......................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Nama PPAT : ..................................................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"e.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Pelelangan");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,"Risalah Lelang : Tahun ................ Tanggal ................");

					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Tempat dan Nama Kantor Lelang : .........................................");

					// 5
					$row_b +=3;
					$pdf -> rect(220, $row_b, 10,6);
					$pdf -> rect(230, $row_b, 160,6);

					$row_b += 3;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"VI.");
					$pdf -> setXY(232,$row_b); $pdf->Cell(0,0,"KEPUTUSAN KETUA PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP");

					$row_b += 3;
					$pdf -> rect(220, $row_b, 170,240);

					$row_b += 2;
					$pdf -> setXY(223,$row_b); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor ...... Tahun 2018 tentang PendaftaranTanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".fdate($datpanitia['tgl_pnt'],'DDMMYYYY')." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");


					$row_b += 30;
					$pdf -> setFont('Times','',15);
					$pdf -> setXY(222,$row_b); $pdf->Cell(0,0,"O");
					$pdf -> setFont('Times','',8);
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"1.");

					$row_b -= 3;
					$pdf -> Line(312, $row_b+7.7, 362, $row_b+7.7);
					$pdf -> Line(274.8, $row_b+12.7, 283.2, $row_b+12.7);
					$pdf -> Line(310, $row_b+12.7, 388, $row_b+12.7);
					$pdf -> Line(233.8, $row_b+17.7, 250, $row_b+17.7);

					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ............... Nomor ................. (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");

					$row_b += 24;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"2.");

					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(220, $row_b, 388, $row_b);
					$pdf -> Line(220, $row_b+32, 388, $row_b);
					$pdf -> Line(220, $row_b+32, 388, $row_b+32);
					$pdf -> SetLineWidth(0.1);


					$row_b -=3;
					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan Hak Milik/Hak Guna Bangunan / Hak Pakai (K1)");

					$row_b += 20;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"3.");

					$row_b -=3;
					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah yang diuraikan pada Risalah Penelitian Data Yuridis ini ada dalam PERKARA/SENGKETA, sehingga proses sertipikatnya ditunda sampai diterbitkan keputusan lembaga Peradilan yang telah mempunyai kekuatan hukum tetap / hasil musyawarah yang menentukan pihak yang berhak/mediasi. (K2)");

					$row_b += 23;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"Biaya Perolehan tanah dan Bangunan (BPHTB) :");

					$row_b+=4;
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"a. Lunas");

					$row_b+=4;
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"a. Terhutang sesuai dengan Surat pernyataan Nomor ........ tanggal .........");

					$row_b +=6;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"Pajak Penghasilan (PPh)");

					$row_b+=4;
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"a. Lunas");

					$row_b+=4;
					$pdf -> setXY(233,$row_b); $pdf->Cell(0,0,"a. Terhutang sesuai dengan Surat pernyataan Nomor ........ tanggal .........");

					$row_b+=4;
					$pdf -> setXY(223,$row_b); $pdf->MultiCell(165,5,"Apabila dikemudian hari ternyata ada bukti yang lebih kuat dan sah yang telah dibuktikan sesuai dengan peraturan perundang-undangan, maka keputusan ini dinyatakan tidak berlaku.");

					$row_b+=14;
					$pdf -> setXY(333,$row_b); $pdf->Cell(0,0,"ditetapkan di     : Semarang");

					$row_b+=6;
					$pdf -> setXY(333,$row_b); $pdf->Cell(0,0,"pada tanggal      : ");

					$row_b+=16;
					$pdf -> setXY(333,$row_b); $pdf->Cell(50,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datpanitia['tim_pnt']."",0,0,'C');

					$row_b+=4;
					$pdf -> setXY(333,$row_b); $pdf->Cell(50,0,"KABUPATEN Semarang",0,0,'C');

					$row_b+=24;
					$pdf -> setXY(332,$row_b); $pdf->Cell(50,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');

					$row_b +=5;
					$pdf -> setXY(327,$row_b); $pdf->Cell(60,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');

					$row_b +=38;
					$pdf -> setXY(223,$row_b); $pdf->Cell(00,0,"Catatan : ");

					$row_b+=4;
					$pdf -> setFont('Times','I');
					$pdf -> setXY(223,$row_b); $pdf->Cell(00,0,"- Berikan tanda lingkaran untuk nomor yang dipilih");

					$row_b+=4;
					$pdf -> setXY(223,$row_b); $pdf->Cell(00,0,"- Coret semua kata-kata nomor yang tidak dipilih");


					// HALAMAN 2
					$pdf -> AddPage();

					$row = 10;

					// 2
					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(220, $row+21, 390, $row-2.8);
					$pdf -> SetLineWidth(0.1);

					$pdf -> setXY(233,$row); $pdf->Cell(0,0,"f.");
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"Putusan Pemberian Hak :");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"Jabatan Pejabat yang Memutuskan :");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"Surat Keputusan No. ....................... Tanggal ........................");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"Prasyaratnya : Telah Dipenuhi :                 Belum dipenuhi :");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> setXY(233,$row); $pdf->Cell(0,0,"g.");
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"pewakafan");
					$pdf -> setXY(275,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(278,$row); $pdf->Cell(0,0,"Akta pengganti Ikrar Wakaf No : .....................");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 10,6);
					$pdf -> rect(240, $row, 150,6);

					$row +=3;
					$pdf -> setXY(278,$row); $pdf->Cell(0,0,"Tanggal : ");

					$row +=3;
					$pdf -> rect(220, $row, 10,13);
					$pdf -> rect(230, $row, 10,13);
					$pdf -> rect(240, $row, 150,13);

					$row +=3;
					$pdf -> setXY(278,$row); $pdf->Cell(0,0,"Nadzir/Nadzir Sementara     :");
					$pdf -> setFont('Times','I');

					$row +=3.8;
					$pdf -> setXY(278,$row); $pdf->Cell(0,0,"(Apabila Nadzir sementara, maka mengacu pad aSurat edaran Menteri");

					$row +=4;
					$pdf -> setXY(278,$row); $pdf->Cell(0,0,"ATR/KBPN Nomor 3 Tahun 2018)");

					$row +=2.2;
					$pdf -> rect(220, $row, 10,16);
					$pdf -> rect(230, $row, 10,16);
					$pdf -> rect(240, $row, 150,16);

					$row +=3.8;
					$pdf -> setFont('Times','');
					$pdf -> setXY(233,$row); $pdf->Cell(0,0,"h.");
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"Lain-Lain sebutkan :");

					$row +=3.8;
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"(Apabila bukti kepemilikan/penguasaan tidak lengkap atau tidak ada sama sekali, maka");

					$row +=3.8;
					$pdf -> setXY(242,$row); $pdf->Cell(0,0,"dilengkapi dengan Surat Pernyataan Penguasaan/Pemilikan bermaterai yang disaksikan 2 (dua) orang saksi.");

					$row +=4.9;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 80,6);
					$pdf -> rect(310, $row, 80,6);

					$row +=3;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Bukti Perpajakan");
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Uraian");

					$row +=3;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 80,12);
					$pdf -> rect(310, $row, 80,12);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"Patok D/Letter C, Girik, ketikir :");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");

					$row +=3;
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Tahun/tanggal");

					$row +=3;
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']);

					$row +=3;
					$pdf -> rect(220, $row, 10,10);
					$pdf -> rect(230, $row, 80,10);
					$pdf -> rect(310, $row, 80,10);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"Verponding Indonesia");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");

					$row +=3;
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Tahun/tanggal");

					$row +=4;
					$pdf -> rect(220, $row, 10,10);
					$pdf -> rect(230, $row, 80,10);
					$pdf -> rect(310, $row, 80,10);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"c.");
					$pdf -> Line(236, $row, 255, $row);
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"IPEDA / PBB / SPPT");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");

					$row +=4;
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,$data['nosppt_ptsl']);
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Tahun/tanggal");

					$row +=3;
					$pdf -> rect(220, $row, 10,10);
					$pdf -> rect(230, $row, 80,10);
					$pdf -> rect(310, $row, 80,10);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"d.");
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"Lain-lain sebutkan : bukti dilampirkan");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");

					$row +=3;
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Tahun/tanggal");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 80,6);
					$pdf -> rect(310, $row, 80,6);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Bukti Terlampir");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Surat pernyataan/keterangan Terlampir");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 160,6);

					$row +=3;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Kenyataan Penguasaan dan penggunaan Tanah :");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 160,6);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"a. Pada tahun ".$data['thn_risalah']." dikuasai/dimiliki oleh :");

					$row +=3;
					$pdf -> rect(220, $row, 10,13);
					$pdf -> rect(230, $row, 160,13);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ....................... diperoleh dengan cara ............................");

					$row +=4;
					$pdf -> rect(220, $row, 10,21);
					$pdf -> rect(230, $row, 160,21);

					$row +=3;
					$pdf -> rect(268, $row, 3,3);
					$pdf -> rect(285, $row, 3,3);
					$pdf -> rect(301, $row, 3,3);
					$pdf -> rect(323, $row, 3,3);
					$pdf -> rect(342, $row, 3,3);

					$row +=1;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Sawah :          Ladang:          Kebun :          Kolam ikan :        Perumahan :       ");

					$row +=4;
					$pdf -> rect(270, $row, 3,3);
					$pdf -> rect(292, $row, 3,3);
					$pdf -> rect(328, $row, 3,3);

					$row +=1;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Industri :          Perkebunan :             Dikelola Pengembang :");

					$row +=4;
					$pdf -> rect(280, $row, 3,3);
					$pdf -> rect(315, $row, 3,3);

					$row +=1;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Lapangan Umum :          Pengembalaan Ternak :");

					$row +=4;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Lain;lain : ....................... (sebutkan)");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 160,6);

					$row +=3;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Bangunan di Atas Tanah :");

					$row +=3;
					$pdf -> rect(220, $row, 10,11);
					$pdf -> rect(230, $row, 160,11);

					$row +=2;
					$pdf -> rect(267, $row, 3,3);
					$pdf -> rect(284, $row, 3,3);
					$pdf -> rect(298, $row, 3,3);
					$pdf -> rect(314, $row, 3,3);
					$pdf -> rect(328, $row, 3,3);

					$row +=1.1;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"a. Jenisnya : Rumah Hunian :         Gedung :       Kantor :       Bengkel :        Toko :");

					$row +=4;
					$pdf -> rect(258, $row, 3,3);
					$pdf -> rect(283, $row, 3,3);

					$row +=2;
					$pdf -> setXY(247,$row); $pdf->Cell(0,0,"Pagar :         Rumah Ibadah :           lain-lain : ...................... (sebutkan)");

					$row +=2;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 160,6);

					$row +=2;
					$pdf -> rect(263, $row, 3,3);

					$row +=1;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b. Tidak ada bangunan :");

					$row +=3;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 80,6);
					$pdf -> rect(310, $row, 80,6);

					$row +=3;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Status Tanahnya :");
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Uraian");

					$row +=3;
					$pdf -> rect(220, $row, 10,19);
					$pdf -> rect(230, $row, 80,19);
					$pdf -> rect(310, $row, 80,19);

					$row +=2;
					$pdf -> rect(334.5, $row, 3,3);
					$pdf -> rect(358, $row, 3,3);

					$row +=2;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"a. Tanah dengan Hak Adat Perorangan :");
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Hak milik Adat :    V       Hak Gogol :");

					$row +=2;
					$pdf -> rect(334.5, $row, 3,3);
					$pdf -> rect(358, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Hak Sanggan :                Hak Yasan :");

					$row +=2;
					$pdf -> rect(334.5, $row, 3,3);
					$pdf -> rect(358, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Hak Anggaduh :             Hak Pekulen :");

					$row +=2;
					$pdf -> rect(334.5, $row, 3,3);
					// $pdf -> rect(154.5, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Hak Norowito :               Hak Lain : .......... (sebutkan)");

					$row +=3;
					$pdf -> rect(220, $row, 10,17);
					$pdf -> rect(230, $row, 80,17);
					$pdf -> rect(310, $row, 80,17);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b. Tanah Negara :");
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"HPL : Pemda Provinsi/Kabupaten/Kota :");

					$row +=2;
					$pdf -> rect(344, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Dikuasai Departemen :");

					$row +=2;
					$pdf -> rect(352, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Dikuasai secara Perorangan :");

					$row +=2;
					$pdf -> rect(340, $row, 3,3);

					$row +=2;
					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(220, $row+22, 390, $row-15);
					$pdf -> SetLineWidth(0.1);

					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");

					$row +=2;
					$pdf -> rect(220, $row, 10,14);
					$pdf -> rect(230, $row, 80,14);
					$pdf -> rect(310, $row, 80,14);

					$row +=2;
					$pdf -> rect(333, $row, 3,3);
					$pdf -> rect(360, $row, 3,3);

					$row +=2;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"c. Tanah bagi Kepentingan Umum :");
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Tanah Kuburan :         Tanah Pangonan : ");

					$row +=2;
					$pdf -> rect(333, $row, 3,3);
					$pdf -> rect(360, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Tanah Pasar :              Tanah lapang : ");

					$row +=2;
					$pdf -> rect(333, $row, 3,3);
					$pdf -> rect(360, $row, 3,3);

					$row +=2;
					$pdf -> setXY(312,$row); $pdf->Cell(0,0,"Tanah Kas Desa :        Lain-lain sebutkan : ");

					$row +=2;
					$pdf -> rect(220, $row, 10,6);
					$pdf -> rect(230, $row, 160,6);

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"d. Lain-lain sebutkan :");

					// HALAMAN 3 & 4
					$pdf -> AddPage();

					$row = 10;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"6.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"beban-Beban Atas Tanah : ");

					$row +=3;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=8;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"7.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bangunan Kepentingan Umum dan Sosial (Kalau ada uraikan) : ");

					$row +=3;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=8;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;

					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(20, $row+15, 190, $row-31);
					$pdf -> SetLineWidth(0.1);

					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"8.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Perkara/Sengketa Atas Tanah : ");

					$row+=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sedang dalam Perkara (kalau ada uraikan) : ");

					$row+=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 10,6);
					$pdf -> rect(40, $row, 150,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Sedang dalam Sengketa (kalau ada uraikan) : ");

					$row+=3;
					$pdf -> rect(20, $row, 10,25);
					$pdf -> rect(30, $row, 80,25);
					$pdf -> rect(110, $row, 80,25);

					$row +=3;
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"B.");
					$pdf -> setXY(32,$row); $pdf->Cell(80,0,"YANG MENGUMPULKAN DATA",0,0,'C');
					$pdf -> setXY(112,$row); $pdf->Cell(80,0,"YANG BERKEPENTINGAN/KUASANYA",0,0,'C');

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(80,0,"(SATGAS YURIDIS)",0,0,'C');

					$row +=15;
					if($datsaksi['bpn_spt']!="" && $datsaksi['babinsa_spt']!=""){
							$pdf -> setXY(24,$row); $pdf->Cell(40,0,"( ".$datsaksi['bpn_spt']." )",0,0,'C');
							$pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
					}else if($datsaksi['bpn_spt']!=""){
							$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['bpn_spt']." )",0,0,'C');
					}else if($datsaksi['babinsa_spt']!=""){
							$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
					}else{
						$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datsaksi['babinsa_spt']." )",0,0,'C');
					}
					$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".$datpdk['nma_pdk']." )",0,0,'C');

					$row+=4;
					$pdf -> rect(20, $row, 10,11);
					$pdf -> rect(30, $row, 160,11);

					$row +=4;
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"III.");

					$row -=3;
					$pdf -> setXY(32,$row); $pdf->MultiCell(155,5,"KESIMPULAN PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP KANTOR PERTANAHAN KABUPATEN Semarang");

					$row+=10;
					$pdf -> rect(20, $row, 170,6);

					$row+=1;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Berdasarkan pada penilaian atas fakta dan data yang telah dikumpulkan, maka dengan ini disimpulkan bahwa :");

					$row+=5;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".$datpdk['nma_pdk']);

					$row+=6;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Status tanahnya adalah :");

					$row+=6;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 10,8);
					$pdf -> rect(40, $row, 150,8);

					$row+=2;
					$pdf -> rect(65, $row, 3,3);
					$pdf -> rect(78, $row, 3,3);
					$pdf -> rect(91, $row, 3,3);
					$pdf -> rect(110, $row, 3,3);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Tanah Hak : Milik :       HGU :       HGB :        Hak Pakai:");

					$row+=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 10,8);
					$pdf -> rect(40, $row, 150,8);

					$row+=2;
					$pdf -> rect(98, $row, 3,3);
					$pdf -> rect(123, $row, 3,3);
					$pdf -> rect(143, $row, 3,3);
					$pdf -> rect(165, $row, 3,3);

					$row+=2;
					$pdf -> setFont ('Times','',20);
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,"O");
					$pdf -> setFont ('Times','',10);
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA :  V   Gogol Tetap :       Pekulen :        Andarbeni:");

					$row+=4;
					$pdf -> rect(20, $row, 10,12);
					$pdf -> rect(30, $row, 10,12);
					$pdf -> rect(40, $row, 150,12);

					$row+=3;
					$pdf -> rect(111, $row, 3,3);
					$pdf -> rect(128, $row, 3,3);
					$pdf -> rect(185, $row, 3,3);

					$row+=1;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c.");

					$row+=4;
					$pdf -> rect(66, $row, 3,3);
					$pdf -> rect(98, $row, 3,3);

					$row-=6;
					$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"Tanah negara : Dikuasai langsung oleh negara :     BUMN :     Instansi Pemerintah Pemda Tk.I,II :    Badan Otorita :      Desa/Kelurahan:");

					$row+=10;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 10,8);
					$pdf -> rect(40, $row, 150,8);

					$row+=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");

					$row+=4;
					$pdf -> rect(20, $row, 10,12);
					$pdf -> rect(30, $row, 10,12);
					$pdf -> rect(40, $row, 150,12);

					$row+=1;
					$pdf -> Line(122, $row+3, 138, $row+3);
					$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu ".$datpdk['nma_pdk']." dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");

					$row+=11;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> rect(98, $row, 3,3);
					$pdf -> rect(130, $row, 3,3);

					$row-=1;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :");

					$row+=7;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> rect(86, $row, 3,3);
					$pdf -> rect(115, $row, 3,3);
					$pdf -> rect(138, $row, 3,3);

					$row-=1;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada :");

					$row+=7;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");

					$row+=6;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
					$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ");

					$row+=6;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"dibuat di");
					$pdf -> setXY(52,$row); $pdf->Cell(165,5,": Semarang");

					$row+=6;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"tanggal");
					$pdf -> setXY(52,$row); $pdf->Cell(165,5,":");

					$row+=6;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Oleh");
					$pdf -> setXY(52,$row); $pdf->Cell(165,5,": Panitia Ajudikasi Tim ".$datpanitia['tim_pnt']);

					$row+=6;
					$pdf -> rect(20, $row, 55,30);
					$pdf -> rect(75, $row, 55,30);
					$pdf -> rect(130, $row, 60,30);

					$row+=3;
					$pdf -> setXY(20,$row); $pdf->Cell(55,0,"KETUA PANITIA AJUDIKASI",0,0,'C');
					$pdf -> setXY(75,$row); $pdf->Cell(55,0,"WAKIL KETUA BIDANG FISIK",0,0,'C');
					$pdf -> setXY(130,$row); $pdf->Cell(60,0,"WAKIL KETUA BIDANG YURIDIS",0,0,'C');

					$row+=23;
					$pdf -> setXY(20,$row); $pdf->Cell(55,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
					$pdf -> setXY(75,$row); $pdf->Cell(55,0,"( ".$datpanitia['wakafis_pnt']." )",0,0,'C');
					$pdf -> setXY(130,$row); $pdf->Cell(60,0,"( ".$datpanitia['wakayur_pnt']." )",0,0,'C');

					$row+=4;
					$pdf -> rect(20, $row, 85,30);
					$pdf -> rect(105, $row, 85,30);

					$row+=3;
					$pdf -> setXY(20,$row); $pdf->Cell(85,0,"KEPALA DESA",0,0,'C');
					$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');

					$row+=23;
					$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".$datsaksi['kades_spt']." )",0,0,'C');
					$pdf -> setXY(105,$row); $pdf->Cell(85,0,"( ".$datpanitia['sekre_pnt']." )",0,0,'C');

					// HALAMAN 4
					$row = 10;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"IV.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"SANGGAHAN / KEBERATAN ");

					$row +=4;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(221, 23, 388, 22);
					$pdf -> Line(221, $row+64, 388, $row);
					$pdf -> Line(221, $row+64, 388, $row+64);
					$pdf -> SetLineWidth(0.1);

					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Uraian singkat perkara / sengketa / sanggahan :");

					$row +=4;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 160,12);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"a.");

					$row -=2;
					$pdf -> setXY(238,$row); $pdf->MultiCell(150,5,"Terdapat perkara/sengketa/sanggahan mengenai batas/pemilikan tanah antara yang berkepentingan dengan (nama) ............................ Gugatan ke Pengadilan telah diajukan / tidak diajukan");

					$row +=10;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Selama pengumuman ada / tidak ada yang menyanggah");

					$row +=4;
					$pdf -> rect(220, $row, 10,16);
					$pdf -> rect(230, $row, 160,16);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"c.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Nama Penyanggah : .............................................");

					$row +=4;
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Alamat : ................................................................");

					$row +=4;
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"..............................................................................");

					$row +=4;
					$pdf -> rect(220, $row, 10,20);
					$pdf -> rect(230, $row, 160,20);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"d.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Alasan Penyanggah : ");

					$row +=4;
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Beserta surat buktinya : ................................................................");

					$row +=4;
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,".......................................................................................................");

					$row +=4;
					$pdf ->setFont('Times','i');
					$pdf -> setXY(238,$row); $pdf->Cell(0,0," ( c dan d diisi bila ada yang menyanggah ) ");
					$pdf ->setFont('Times','');

					$row +=4;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Penyelesaian perkara/sengketa/sanggahan :");

					$row +=4;
					$pdf -> rect(220, $row, 10,10);
					$pdf -> rect(230, $row, 160,10);

					$row +=4;
					$pdf -> setXY(223,$row); $pdf->Cell(0,0,"V.");

					$row -=4;
					$pdf -> setXY(232,$row); $pdf->MultiCell(155,5,"KESIMPULAN AKHIR KETUA PANITIA AJUDIKASI PENDAFTARAN TANAH SISTEMATIS LENGKAP");

					$row +=10;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".$datpdk['nma_pdk']);

					$row +=4;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");

					$row +=4;
					$pdf -> rect(220, $row, 10,28);
					$pdf -> rect(230, $row, 160,28);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"a.");

					$row -=2;
					$pdf -> Line(328, $row+18, 340, $row+18);
					$pdf -> Line(355, $row+18, 370, $row+18);
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".$datpdk['nma_pdk']." K1)");

					$row +=26;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"b.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP (K1)");

					$row +=6;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 160,12);

					$row +=4;
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"c.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Tanah dalam proses perkara / sengketa dengan Nomor Perkara .......... tanggal ............ (K2)");

					$row +=10;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 160,12);

					$row +=4;
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"d.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3)");

					$row +=10;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 160,12);

					$row +=4;
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"e.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");

					$row +=10;
					$pdf -> rect(220, $row, 10,42);
					$pdf -> rect(230, $row, 160,42);

					$row +=4;
					$pdf -> setXY(305,$row); $pdf->Cell(0,0,"Semarang, ...........................");

					$row +=4;
					$pdf -> setXY(310,$row); $pdf->Cell(60,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datpanitia['tim_pnt']."",0,0,'C');

					$row +=4;
					$pdf -> setXY(310,$row); $pdf->Cell(60,0,"KABUPATEN Semarang",0,0,'C');

					$row +=20;
					$pdf -> setXY(310,$row); $pdf->Cell(60,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');

					$row +=5;
					$pdf -> setXY(310,$row); $pdf->Cell(60,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');

					$row +=5;
					$pdf -> rect(220, $row, 10,8);
					$pdf -> rect(230, $row, 160,8);

					$row +=4;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"Catatan :");
					$pdf -> setFont('Times','I');
					$pdf -> setXY(245,$row); $pdf->Cell(0,0,"coret seluruh kata-kata no.3 bila tidak diperlukan");

					$pdf->Output();
				}
	}
