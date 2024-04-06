<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studioip4t_2 extends CI_Controller
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

		public function data()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('NUB','nub_ptsl'),array('Nama','nma_pdk'),array('No. KTP','noktp_pdk'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "IP4T : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Belum Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studioip4t_2/data/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			if($this->input->get('nilai')){
				$tdata['table'] = "tb_ptsl";
	      $tdata['type'] = "single";
				$tdata['join']['table'] = "tb_penduduk,tb_ptsldhkp,tb_dhkp";
				$tdata['join']['key'] = "idpdk_ptsl,idptsl_ptsl,id_dhkp";
				$tdata['join']['ref'] = "idpdk_pdk,id_ptsl,iddhkp_ptsl";
				$tdata['column'] = "COUNT(id_ptsl) as jumlah";
				$tdata['condition']['idblk_ptsl'] = $block['idblk_blk'];
				$tdata['condition']['publish_ptsl'] = '1';

				if($this->input->get('nilai')){
					if($this->input->get('param')=='nosppt_dhkp'){
						$cek = substr($this->input->get('nilai'),13,5);
						$tdata['condition'][$this->input->get('param')] = $cek;
					}else if($this->input->get('param')=='nma_pdk'){
						$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
					}else{
						$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
					}
				}
			}else{
				$tdata['table'] = "tb_ptsl";
	      $tdata['type'] = "single";
				$tdata['column'] = "COUNT(id_ptsl) as jumlah";
				$tdata['condition']['idblk_ptsl'] = $block['idblk_blk'];
				$tdata['condition']['publish_ptsl'] = '1';
			}

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Studioip4t_2/data/';
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
			$dat['column'] = "tb_ptsl.nub_ptsl,tb_ptsl.id_ptsl,tb_dhkp.nosppt_dhkp,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_ptsl.idblk_ptsl";
			$dat['join']['table'] = "tb_penduduk,tb_ptsldhkp,tb_dhkp,tb_block";
			$dat['join']['key'] = "idpdk_ptsl,idptsl_ptsl,id_dhkp,idblk_blk";
			$dat['join']['ref'] = "idpdk_pdk,id_ptsl,iddhkp_ptsl,idblk_ptsl";
			$dat['condition']['idblk_ptsl'] = $block['idblk_blk'];
			$dat['condition']['publish_ptsl'] = '1';
			$dat['orderby']['column'] = 'nosppt_dhkp';
			$dat['orderby']['sort'] = 'asc';
			$dat['groupby']         = 'nub_ptsl';

			if($this->input->get('nilai')){
				if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='nma_pdk'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studioip4t/data_studio_2");
			$this->load->view('adm',$this->content);
		}

		public function editip4t($id,$idblk){
			$user = $this->auth_model->get_userdata();
			$block = $this->studio_2_1_model->sr_name_block($idblk);

			$ip4t['table'] = "tb_ip4t";
			$ip4t['type'] = "single";
			$ip4t['condition']['iddhkp_ip4t'] =$id;
			$this->content['ip4t'] = $this->crud_model->get_data($ip4t);

			if ($this->input->post()) {

				$this->db->trans_start();

				// CEK KTP 1
				$datktp['table'] = "tb_penduduk";
	      $datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('ktp1');
				$ktp = $this->crud_model->get_data($datktp);

				$user = $this->auth_model->get_userdata();

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('ktp1'),
						'nma_pdk'   => addslashes($this->input->post('nama1')),
						'ttl_pdk' => $this->input->post('ttl1'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl1'))),
						'idpeker_pdk' => $this->input->post('pekerjaan1'),
						'agm_pdk' => $this->input->post('agama1'),
						'almat_pdk' => $this->input->post('alamat1'),
						'rt_pdk' => $this->input->post('rt1'),
						'rw_pdk' => $this->input->post('rw1'),
						'kel_pdk' => $this->input->post('kel1'),
						'kec_pdk' => $this->input->post('kec1'),
						'kab_pdk' => $this->input->post('kab1'),
						'anggota_pdk' => $this->input->post('anggota1'),
						'domisili_pdk' => $this->input->post('domisili1'),
						'status_pdk' => $this->input->post('status1'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);

					$nik1 = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$nik1,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

				}else{
					$ar = array(
						'nma_pdk'   => addslashes($this->input->post('nama1')),
						'ttl_pdk' => $this->input->post('ttl1'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl1'))),
						'idpeker_pdk' => $this->input->post('pekerjaan1'),
						'agm_pdk' => $this->input->post('agama1'),
						'almat_pdk' => $this->input->post('alamat1'),
						'rt_pdk' => $this->input->post('rt1'),
						'rw_pdk' => $this->input->post('rw1'),
						'kel_pdk' => $this->input->post('kel1'),
						'kec_pdk' => $this->input->post('kec1'),
						'kab_pdk' => $this->input->post('kab1'),
						'anggota_pdk' => $this->input->post('anggota1'),
						'domisili_pdk' => $this->input->post('domisili1'),
						'status_pdk' => $this->input->post('status1'),
						'idusr_pdk' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp['noktp_pdk']));
					$nik1 = $ktp['idpdk_pdk'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$nik1,"Update Data Penduduk dengan rincian ".displayArray($ar));
				}

				// CEK KTP 2
				$datktp['table'] = "tb_penduduk";
	      $datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('ktp2');
				$ktp = $this->crud_model->get_data($datktp);

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('ktp2'),
						'nma_pdk'   => addslashes($this->input->post('nama2')),
						'ttl_pdk' => $this->input->post('ttl2'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl2'))),
						'idpeker_pdk' => $this->input->post('pekerjaan2'),
						'agm_pdk' => $this->input->post('agama2'),
						'almat_pdk' => $this->input->post('alamat2'),
						'rt_pdk' => $this->input->post('rt2'),
						'rw_pdk' => $this->input->post('rw2'),
						'kel_pdk' => $this->input->post('kel2'),
						'kec_pdk' => $this->input->post('kec2'),
						'kab_pdk' => $this->input->post('kab2'),
						'anggota_pdk' => $this->input->post('anggota2'),
						'domisili_pdk' => $this->input->post('domisili2'),
						'status_pdk' => $this->input->post('status2'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);

					$nik2 = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$nik2,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

				}else{
					$ar = array(
						'nma_pdk'   => addslashes($this->input->post('nama2')),
						'ttl_pdk' => $this->input->post('ttl2'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl2'))),
						'idpeker_pdk' => $this->input->post('pekerjaan2'),
						'agm_pdk' => $this->input->post('agama2'),
						'almat_pdk' => $this->input->post('alamat2'),
						'rt_pdk' => $this->input->post('rt2'),
						'rw_pdk' => $this->input->post('rw2'),
						'kel_pdk' => $this->input->post('kel2'),
						'kec_pdk' => $this->input->post('kec2'),
						'kab_pdk' => $this->input->post('kab2'),
						'anggota_pdk' => $this->input->post('anggota2'),
						'domisili_pdk' => $this->input->post('domisili2'),
						'status_pdk' => $this->input->post('status2'),
						'idusr_pdk' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp['noktp_pdk']));
					$nik2 = $ktp['idpdk_pdk'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$nik2,"Update Data Penduduk dengan rincian ".displayArray($ar));
				}

				if($this->content['ip4t']){

					// EDIT IP4T
					$ar = array(
						'nis_ip4t' => $this->input->post('nis'),
						'a1nik_ip4t' => $nik1,
						'a2nik_ip4t' => $nik2,
						'a1miliktanah_ip4t' => $this->input->post('a1tahun'),
						'a2kuasatanah_ip4t' => $this->input->post('a2tahun'),
						'bkuasatanah_ip4t' => $this->input->post('kuasatanah'),
						'bolehtanah_ip4t' => $this->input->post('perolehantanah'),
						'bmiliktanah_ip4t' => $this->input->post('pemilikantanah'),
						'bgunatanah_ip4t' => $this->input->post('gunabidang'),
						'bjenismanfaat1_ip4t' => $this->input->post('bidang1'),
						'bjenismanfaat2_ip4t' => $this->input->post('bidang2'),
						'bjenismanfaat3_ip4t' => $this->input->post('bidang3'),
						'bjenismanfaat4_ip4t' => $this->input->post('bidang4'),
						'bjenismanfaat5_ip4t' => $this->input->post('bidang5'),
						'bjenismanfaat6_ip4t' => $this->input->post('bidang6'),
						'bindikasi_ip4t' => $this->input->post('indikasitanah'),
						'bsengketa_ip4t' => $this->input->post('sengketa'),
						'bpotensi_ip4t' => $this->input->post('potensi'),
						'csertif_ip4t' => $this->input->post('sertifikan'),
						'cpotensi_ip4t' => $this->input->post('potensiakses'),
						'cbantuanjenis_ip4t' => $this->input->post('jenisbantuan'),
						'cbantuandari_ip4t' => $this->input->post('dari'),
						'cbantuantanggal_ip4t' => date("Y-m-d",strtotime($this->input->post('tanggal'))),
						'cpendapatanbelum_ip4t' => $this->input->post('pendapatana'),
						'cpendapatansudah_ip4t' => $this->input->post('pendapatanb'),
						'idusr_ip4t' => $user['idusr_usr']
					);

					$simpan = $this->crud_model->update('tb_ip4t',$ar,array('iddhkp_ip4t'=>$id));
					$insert_id = $id;

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ip4t','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Update Data IP4T dengan rincian ".displayArray($ar));

				}else{

					// INPUT IP4T
					$ar = array(
						'iddhkp_ip4t' => $id,
						'a1nik_ip4t' => $nik1,
						'a2nik_ip4t' => $nik2,
						'nis_ip4t' => $this->input->post('nis'),
						'a1miliktanah_ip4t' => $this->input->post('a1tahun'),
						'a2kuasatanah_ip4t' => $this->input->post('a2tahun'),
						'bkuasatanah_ip4t' => $this->input->post('kuasatanah'),
						'bolehtanah_ip4t' => $this->input->post('perolehantanah'),
						'bmiliktanah_ip4t' => $this->input->post('pemilikantanah'),
						'bgunatanah_ip4t' => $this->input->post('gunabidang'),
						'bjenismanfaat1_ip4t' => $this->input->post('bidang1'),
						'bjenismanfaat2_ip4t' => $this->input->post('bidang2'),
						'bjenismanfaat3_ip4t' => $this->input->post('bidang3'),
						'bjenismanfaat4_ip4t' => $this->input->post('bidang4'),
						'bjenismanfaat5_ip4t' => $this->input->post('bidang5'),
						'bjenismanfaat6_ip4t' => $this->input->post('bidang6'),
						'bindikasi_ip4t' => $this->input->post('indikasitanah'),
						'bsengketa_ip4t' => $this->input->post('sengketa'),
						'bpotensi_ip4t' => $this->input->post('potensi'),
						'csertif_ip4t' => $this->input->post('sertifikan'),
						'cpotensi_ip4t' => $this->input->post('potensiakses'),
						'cbantuanjenis_ip4t' => $this->input->post('jenisbantuan'),
						'cbantuandari_ip4t' => $this->input->post('dari'),
						'cbantuantanggal_ip4t' => date("Y-m-d",strtotime($this->input->post('tanggal'))),
						'cpendapatanbelum_ip4t' => $this->input->post('pendapatana'),
						'cpendapatansudah_ip4t' => $this->input->post('pendapatanb'),
						'create_at' => date('Y-m-d H:i:s'),
						'idusr_ip4t' => $user['idusr_usr']
					);

					$simpan = $this->crud_model->input('tb_ip4t',$ar);
					$insert_id = $this->db->insert_id();

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ip4t','IP4T-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Input Data IP4T dengan rincian ".displayArray($ar));
				}

				$this->db->trans_complete();
				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studioip4t_2/ip4t/?search=<?php echo $idblk; ?>">
				<?php
			}

				cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);
				$idkel = $block['idkel_blk'];

				$this->content['data']['title'] = "IP4T : Edit IP4T ";
				$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("IP4T","Studioip4t_2/ip4t/?search=".$idblk),array("Edit IP4T","Studioip4t_2/editip4t/".$id."/".$idblk));

				$this->content['status'] = "Edit IP4T";

				$dhkp['table'] = "tb_dhkp";
				$dhkp['type'] = "single";
				$dhkp['column'] = "nosppt_dhkp,nib_hak,luassppt_dhkp";
				$dhkp['join']['table'] = "tb_nub,tb_hak";
				$dhkp['join']['key'] = "iddhkp_nub,no_hak";
				$dhkp['join']['ref'] = "id_dhkp,nohak_nub";
				$dhkp['condition']['id_dhkp'] =$id;
				$this->content['dhkp'] = $this->crud_model->get_data($dhkp);

				$ptsl['table'] = "tb_ptsldhkp";
				$ptsl['type'] = "single";
				$ptsl['column'] = "nib_ptsl,luasfisik_ptsl,idpdk_ptsl";
				$ptsl['join']['table'] = "tb_ptsl";
				$ptsl['join']['key'] = "id_ptsl";
				$ptsl['join']['ref'] = "idptsl_ptsl";
				$ptsl['condition']['iddhkp_ptsl'] =$id;
				$this->content['ptsl'] = $this->crud_model->get_data($ptsl);

				$b['table'] = "tb_penduduk";
				$b['type']  = "single";
				$b['condition']['idpdk_pdk'] =$this->content['ptsl']['idpdk_ptsl'];
				$this->content['b'] = $this->crud_model->get_data($b);

				$a1['table'] = "tb_penduduk";
				$a1['type']  = "single";
				$a1['condition']['idpdk_pdk'] =$this->content['ip4t']['a1nik_ip4t'];
				$this->content['a1'] = $this->crud_model->get_data($a1);

				$a2['table'] = "tb_penduduk";
				$a2['type']  = "single";
				$a2['condition']['idpdk_pdk'] =$this->content['ip4t']['a2nik_ip4t'];
				$this->content['a2'] = $this->crud_model->get_data($a2);

				$dat['table'] = "tb_pekerjaan";
	      $dat['type'] = "multiple";
				$dat['orderby']['column'] = 'nama_pkr';
				$dat['orderby']['sort'] = 'asc';
	      $this->content['pekerjaan'] = $this->crud_model->get_data($dat);

				$kel['table'] = "ms_kelurahan";
				$kel['join']['table'] = "tb_block,ms_kecamatan";
				$kel['join']['key'] = "idkel_blk,kd_kec";
				$kel['join']['ref'] = "kd_full,kdkec_kel";
	      $kel['type'] = "single";
				$kel['column'] = "nma_kel,nma_kec";
				$kel['condition']['idblk_blk'] = $block['idblk_blk'];
	      $this->content['kelurahan'] = $this->crud_model->get_data($kel);

				$this->content['block'] = $block;

				$this->content['load'] = array("studioip4t/edit_ip4t");
				$this->load->view('adm',$this->content);
		}

		public function register()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "IP4T : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Sudah Sertipikat";
			$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studioip4t_2/register/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			if($this->input->get('nilai')){
				$tdata['table'] = "tb_nub";
	      $tdata['type'] = "single";
				$tdata['join']['table'] = "tb_hak,tb_su,tb_dhkp,tb_block";
				$tdata['join']['key'] = "no_hak,nohak_su,id_dhkp,idblk_blk";
				$tdata['join']['ref'] = "nohak_nub,no_hak,iddhkp_nub,idblk_nub";
				$tdata['column'] = "COUNT(idnub_nub) as jumlah";
				$tdata['condition']['idblk_nub'] = $block['idblk_blk'];
				$tdata['condition']['publish_nub'] = '1';

				if($this->input->get('nilai')){
					if($this->input->get('param')=='pmi_hak' || $this->input->get('param')=='pma_hak'){
						$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
					}else if($this->input->get('param')=='nib_hak'){
						$cek = substr($this->input->get('nilai'),8,5);
						$tdata['condition'][$this->input->get('param')] = $cek;
					}else if($this->input->get('param')=='nosppt_dhkp'){
						$cek = substr($this->input->get('nilai'),13,5);
						$tdata['condition'][$this->input->get('param')] = $cek;
					}else{
						$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
					}
				}
			}else{
				$tdata['table'] = "tb_nub";
	      $tdata['type'] = "single";
				$tdata['column'] = "COUNT(idnub_nub) as jumlah";
				$tdata['condition']['idblk_nub'] = $block['idblk_blk'];
				$tdata['condition']['publish_nub'] = '1';
			}

			$ttdata = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Studioip4t_2/register/';
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
			$dat['column'] = "tb_nub.*,tb_hak.nosu_hak,tb_hak.pmi_hak,
												tb_hak.pma_hak,tb_hak.nib_hak,
												tb_dhkp.nosppt_dhkp,tb_hak.id_kelurahan,tb_block.nama_blk";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "tb_hak,tb_su,tb_dhkp,tb_block";
			$dat['join']['key'] = "no_hak,nohak_su,id_dhkp,idblk_blk";
			$dat['join']['ref'] = "nohak_nub,no_hak,iddhkp_nub,idblk_nub";
			$dat['condition']['idblk_nub'] = $block['idblk_blk'];
			$dat['condition']['publish_nub'] = '1';
			$dat['orderby']['column'] = 'update_at';
			$dat['orderby']['sort'] = 'desc';
			$dat['groupby'] = 'nohak_nub';

			if($this->input->get('nilai')){
				if($this->input->get('param')=='pmi_hak' || $this->input->get('param')=='pma_hak'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nib_hak'){
					$cek = substr($this->input->get('nilai'),8,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$cekblock = substr($this->input->get('nilai'),10,3);
					$dat['condition'][$this->input->get('param')] = $cek;
					$dat['condition']['nama_blk'] = $cekblock;
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = ($from);
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			// DATA UNREG
			$this->content['data_unreg'] = $this->studio3_model->show_unregister($block['idblk_blk'],'count');

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studioip4t/dataregister_studioip4t");
			$this->load->view('adm',$this->content);
		}

		public function dhkp()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama Wajib Pajak','nama_dhkp'),array('No Hak','nohak_nub'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "IP4T : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." DHKP";
			$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("DHKP","Studioip4t_2/dhkp/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_dhkp";
			$tdata['join']['table'] = "tb_nub";
			$tdata['join']['key'] = "iddhkp_nub";
			$tdata['join']['ref'] = "id_dhkp";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(id_dhkp) as jumlah";
			$tdata['condition']['idblk_dhkp'] = $block['idblk_blk'];

			if($this->input->get('nilai')){
				if($this->input->get('param')=='nama_dhkp'){
					$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$tdata['condition'][$this->input->get('param')] = $cek;
				}else{
					$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studioip4t_2/dhkp/';
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
			$dat['column'] = "tb_dhkp.*,tb_nub.nohak_nub,tb_block.idkel_blk,tb_block.nama_blk";
			$dat['join']['table'] = "tb_block,tb_nub";
			$dat['join']['key'] = "idblk_blk,iddhkp_nub";
			$dat['join']['ref'] = "idblk_dhkp,id_dhkp";
			$dat['condition']['idblk_dhkp'] = $block['idblk_blk'];
			$dat['orderby']['column'] = 'tb_dhkp.update_at';
			$dat['orderby']['sort'] = 'desc';

			if($this->input->get('nilai')){
				if($this->input->get('param')=='nama_dhkp'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

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

		public function ip4t()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama Wajib Pajak','nama_dhkp'),array('No Hak','nohak_nub'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "IP4T : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." DHKP";
			$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("DHKP","Studioip4t_2/dhkp/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_dhkp";
			$tdata['join']['table'] = "tb_nub";
			$tdata['join']['key'] = "iddhkp_nub";
			$tdata['join']['ref'] = "id_dhkp";
      $tdata['type'] = "single";
			$tdata['column'] = "COUNT(id_dhkp) as jumlah";
			$tdata['condition']['idblk_dhkp'] = $block['idblk_blk'];

			if($this->input->get('nilai')){
				if($this->input->get('param')=='nama_dhkp'){
					$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$tdata['condition'][$this->input->get('param')] = $cek;
				}else{
					$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studioip4t_2/ip4t/';
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
			$dat['column'] = "tb_dhkp.*,tb_block.idkel_blk,tb_block.nama_blk,
												tb_ip4t.id_ip4t,tb_ip4t.nis_ip4t,tb_ip4t.a1nik_ip4t,tb_ip4t.a2nik_ip4t";
			$dat['join']['table'] = "tb_block,tb_ip4t";
			$dat['join']['key'] = "idblk_blk,iddhkp_ip4t";
			$dat['join']['ref'] = "idblk_dhkp,id_dhkp";
			$dat['condition']['idblk_dhkp'] = $block['idblk_blk'];
			$dat['orderby']['column'] = 'nosppt_dhkp';
			$dat['orderby']['sort'] = 'asc';

			if($this->input->get('nilai')){
				if($this->input->get('param')=='nama_dhkp'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from+1;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studioip4t/dataip4t");
			$this->load->view('adm',$this->content);
		}

		public function k4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "IP4T : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." K4";
			$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$block['idkel_blk']),array("K4","Studioip4t_2/k4/?search=".$cari));

			$this->content['idblk'] = $block['idblk_blk'];

			$desa['table'] = "ms_kecamatan";
			$desa['type']  = "single";
			$desa['column']  = "nma_kec,nma_kel";
			$desa['join']['table'] = "ms_kelurahan";
			$desa['join']['key'] = "kdkec_kel";
			$desa['join']['ref'] = "kd_kec";
			$desa['condition']['kd_full'] = $block['idkel_blk'];
			$this->content['desa'] = $this->crud_model->get_data($desa);

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			if($this->input->get('nilai')){

				$tdata['table'] = "tb_hak";
				$tdata['join']['table'] = "tb_su,tb_nub,tb_block,tb_dhkp";
				$tdata['join']['key'] = "nohak_su,nohak_nub,idblk_blk,id_dhkp";
				$tdata['join']['ref'] = "no_hak,no_hak,idblk_nub,iddhkp_nub";
	      $tdata['type'] = "single";
				$tdata['column'] = "COUNT(no_hak) as jumlah";
				$tdata['condition']['id_kelurahan'] = $block['idkel_blk'];

				if($this->input->get('param')=='pmi_hak' || $this->input->get('param')=='pma_hak'){
					$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nib_hak'){
					$cek = substr($this->input->get('nilai'),8,5);
					$tdata['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$tdata['condition'][$this->input->get('param')] = $cek;
				}else{
					$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}else{
				$tdata['table'] = "tb_hak";
	      $tdata['type'] = "single";
				$tdata['column'] = "COUNT(no_hak) as jumlah";
				$tdata['condition']['id_kelurahan'] = $block['idkel_blk'];
			}

			if($this->input->get('link') && $this->input->get('link')==1){
				$tdata['cuzcondition'] = 'no_hak NOT IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==2){
				$tdata['cuzcondition'] = 'no_hak IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==3){
				$tdata['cuzcondition'] = "((buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial= '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual != '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR jenis_kw_awal='KW4' OR jenis_kw_awal='KW5' OR jenis_kw_awal='KW6')";
			}

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studioip4t_2/k4/';
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
												tb_hak.jenis_kw_awal,tb_hak.pma_hak,tb_hak.pmi_hak,tb_hak.buku_tanah,tb_hak.entry_su_tekstual,
												tb_hak.su_spasial,tb_hak.bidang_tanah,tb_block.nama_blk,tb_dhkp.nosppt_dhkp,tb_nub.publish_nub,count(nohak_nub) as jml";
			$dat['join']['table'] = "tb_su,tb_nub,tb_block,tb_dhkp";
			$dat['join']['key'] = "nohak_su,nohak_nub,idblk_blk,id_dhkp";
			$dat['join']['ref'] = "no_hak,no_hak,idblk_nub,iddhkp_nub";

			if($this->input->get('nilai')){
				if($this->input->get('param')=='pmi_hak' || $this->input->get('param')=='pma_hak'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nib_hak'){
					$cek = substr($this->input->get('nilai'),8,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$cekblock = substr($this->input->get('nilai'),10,3);
					$dat['condition'][$this->input->get('param')] = $cek;
					$dat['condition']['nama_blk'] = $cekblock;
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			if($this->input->get('link') && $this->input->get('link')==1){
				$dat['cuzcondition'] = 'no_hak NOT IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==2){
				$dat['cuzcondition'] = 'no_hak IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==3){
				$dat['cuzcondition'] = "((buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial= '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual != '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR jenis_kw_awal='KW4' OR jenis_kw_awal='KW5' OR jenis_kw_awal='KW6')";
			}

			$dat['condition']['id_kelurahan'] = $block['idkel_blk'];
			$dat['orderby']['column'] = 'tb_hak.no_hak';
			$dat['orderby']['sort'] = 'asc';
			$dat['groupby']         = 'no_hak';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio3/datak4");
			$this->load->view('adm',$this->content);
		}

		public function exportk4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			$dat['table'] = "tb_hak";
      $dat['type'] = "multiple";
			$dat['column'] = "tb_hak.no_hak,tb_hak.id_kelurahan,tb_hak.nib_hak,tb_hak.nosu_hak,tb_su.luas_su,
												tb_hak.jenis_kw_awal,tb_hak.pma_hak,tb_hak.pmi_hak,tb_hak.buku_tanah,tb_hak.entry_su_tekstual,
												tb_hak.su_spasial,tb_hak.bidang_tanah,tb_block.nama_blk,tb_dhkp.nosppt_dhkp,tb_nub.publish_nub,count(nohak_nub) as jml";
			$dat['join']['table'] = "tb_su,tb_nub,tb_block,tb_dhkp";
			$dat['join']['key'] = "nohak_su,nohak_nub,idblk_blk,id_dhkp";
			$dat['join']['ref'] = "no_hak,no_hak,idblk_nub,iddhkp_nub";

			if($this->input->get('nilai')){
				if($this->input->get('param')=='pmi_hak' || $this->input->get('param')=='pma_hak'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nib_hak'){
					$cek = substr($this->input->get('nilai'),8,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$cekblock = substr($this->input->get('nilai'),10,3);
					$dat['condition'][$this->input->get('param')] = $cek;
					$dat['condition']['nama_blk'] = $cekblock;
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}

			if($this->input->get('link') && $this->input->get('link')==1){
				$dat['cuzcondition'] = 'no_hak NOT IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==2){
				$dat['cuzcondition'] = 'no_hak IN (SELECT nohak_nub FROM tb_nub)';
			}else if($this->input->get('link') && $this->input->get('link')==3){
				$dat['cuzcondition'] = "((buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial= '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual = '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR (buku_tanah= '1' AND entry_su_tekstual != '1' AND su_spasial != '1' AND bidang_tanah != '1')
																	OR jenis_kw_awal='KW4' OR jenis_kw_awal='KW5' OR jenis_kw_awal='KW6')";
			}

			$dat['condition']['id_kelurahan'] = $block['idkel_blk'];
			$dat['orderby']['column'] = 'tb_hak.no_hak';
			$dat['orderby']['sort'] = 'asc';
			$dat['groupby']         = 'no_hak';



      $this->content['studio'] = $this->crud_model->get_data($dat);

			$kec ['type'] 	= "single";
			$kec ['table']	= "ms_kelurahan";
			$kec ['column']	= "nma_kel,nma_kec";
			$kec ['join']['table']	= "ms_kecamatan";
			$kec ['join']['key']	= "kdkec_kel";
			$kec ['join']['ref']	= "kd_kec";
			$kec ['condition']['kd_full'] = $block['idkel_blk'];
			$this->content['desa'] = $this->crud_model->get_data($kec);


			$this->load->view('studio3/exportdatak4',$this->content);
		}

		public function exportdi208(){

				$block = $this->input->get('search');

				$kec['table'] = "ms_kelurahan";
		    $kec['type'] = "single";
		    $kec['column'] = "nma_kel,nma_kec,kd_full,type_kel,nama_blk";
		    $kec['join']['table'] = 'ms_kecamatan,tb_block';
		    $kec['join']['key'] = 'kd_kec,idkel_blk';
		    $kec['join']['ref'] = 'kdkec_kel,kd_full';
		    $kec['condition']['idblk_blk'] = $block;
		    $this->content['kecamatan'] = $this->crud_model->get_data($kec);

				$ip4t['type']                   = "multiple";
				$ip4t['table']                  = "tb_ip4t";
				$ip4t['join']['table'] 					= "tb_dhkp";
				$ip4t['join']['key'] 						= "id_dhkp";
				$ip4t['join']['ref'] 						= "iddhkp_ip4t";
				$ip4t['condition']['idblk_dhkp'] 				= $block;
				$this->content['dataip4t']      = $this->crud_model->get_data($ip4t);


				$this->load->view('studioip4t/exportdi208',$this->content);

		}

		public function export($ids){
				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				require(APPPATH .'plugins/fpdf/fpdf.php');

				$ip4t['type']                   = "single";
				$ip4t['table']                  = "tb_ip4t";
				$ip4t['condition']['iddhkp_ip4t'] = $ids;
				$dataip4t                       = $this->crud_model->get_data($ip4t);

				$pemilik['table'] = "tb_penduduk";
				$pemilik['type'] = "single";
				$pemilik['condition']['idpdk_pdk'] = $dataip4t['a1nik_ip4t'];
				$pemilik['join']['table'] = "tb_pekerjaan";
				$pemilik['join']['key'] = "idpkr_pkr";
				$pemilik['join']['ref'] = "idpeker_pdk";
				$datapemilik = $this->crud_model->get_data($pemilik);

				$penguasaan['table'] = "tb_penduduk";
				$penguasaan['type'] = "single";
				$penguasaan['condition']['idpdk_pdk'] = $dataip4t['a2nik_ip4t'];
				$penguasaan['join']['table'] = "tb_pekerjaan";
				$penguasaan['join']['key'] = "idpkr_pkr";
				$penguasaan['join']['ref'] = "idpeker_pdk";
				$datapenguasaan = $this->crud_model->get_data($penguasaan);

				$dnop['type']                   = "single";
				$dnop['table']                  = "tb_dhkp";
				$dnop['join']['table']          = "tb_block,tb_nub,tb_hak,tb_ptsldhkp,tb_ptsl";
				$dnop['join']['key']            = "idblk_blk,iddhkp_nub,no_hak,iddhkp_ptsl,id_ptsl";
				$dnop['join']['ref']            = "idblk_dhkp,id_dhkp,nohak_nub,id_dhkp,idptsl_ptsl";
				$dnop['column'] 								= "idkel_blk,nama_blk,nosppt_dhkp,nib_hak,nib_ptsl,nub_ptsl,idpdk_ptsl
																						,luasfisik_ptsl,luassppt_dhkp";
				$dnop['condition']['id_dhkp']   = $ids;
				$datanop                        = $this->crud_model->get_data($dnop);

				$pdk['table'] = "tb_penduduk";
				$pdk['type'] = "single";
				$pdk['condition']['idpdk_pdk'] = $datanop['idpdk_ptsl'];
				$datapenduduk = $this->crud_model->get_data($pdk);

				$nop = createkodebpkad($datanop['idkel_blk']).''.$datanop['nama_blk'].''.$datanop['nosppt_dhkp'];

				##################################################

				// $kec['table'] = "ms_kelurahan";
				// $kec['type'] = "single";
				// $kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
				// $kec['join']['table'] = 'ms_kecamatan';
				// $kec['join']['key'] = 'kd_kec';
				// $kec['join']['ref'] = 'kdkec_kel';
				// $kec['condition']['kd_full'] = $datblock['idkel_blk'];
				// $kecamatan = $this->crud_model->get_data($kec);
				// $this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Risalah PTSL dengan kode ".$id);
				//
				// if($kecamatan['type_kel']==1){
				// 	$desa = 'Desa';
				// }else{
				// 	$desa = 'Kelurahan';
				// }

					$pdf = new FPDF('p','mm',array(210,330));

					$pdf -> AddPage();
					$pdf -> setDisplayMode ('fullpage');
					$pdf -> setFont ('Times','B',12);

					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,15); $pdf->Cell(0,0,$datanop['nub_ptsl'].' / Block '.$datanop['nama_blk'],0,0,'L');
					$pdf -> Image("./assets/img/bpn.png",90,15,20);
					// HALAMAN 1
					$row = 10;
					$pdf -> setFont ('Times','B',12);
					$row +=30;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"INVENTARISASI PENGUASAAN, PEMILIKAN, PENGGUNAAN",0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"DAN PEMANFAATAN TANAH (IP 4T)",0,0,'C');
					$row +=5;
					$pdf -> SetLineWidth(1.5);
					$pdf -> setXY(20,$row);
					$pdf->Cell(170,0,"",1,1,'C');
					$row +=2;
					$pdf -> SetLineWidth(0.5);
					$pdf -> setXY(15,$row);
					$pdf -> setFont ('Times','',10);

					$pdf -> rect(155, $row, 35, 13);
					$row +=4;
					$pdf -> setXY(170,$row+2); $pdf->Cell(0,0,$dataip4t['nis_ip4t'],0,0,'L');
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Nomor Inventarisasi");
					$row +=5;
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"NIS");
					$row +=5;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);
					$row +=4;
					$pdf->Cell(0,0,"",1,1,'C');
					$pdf -> setFont ('Times','B',10);
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"A1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"A. TERKAIT DENGAN SUBYEK");
					$row +=4;
					$pdf -> setFont ('Times','',10);
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pemilikan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nama Pemilik");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['nma_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['almat_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"RT / RW");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['rt_pdk'].'/'.$datapemilik['rw_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['kel_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kecamatan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['kec_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kabupaten");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['kab_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nomor KTP");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['noktp_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['nama_pkr']);

					$birthdate = new DateTime($datapemilik['ttg_pdk']);
			    $today= new DateTime(date("Y-m-d"));
			    $age = $birthdate->diff($today)->y;

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Umur");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$age.' tahun');

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Status Perkawinan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,status($datapemilik['status_pdk'],'statusnikah'));

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"6.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jumlah Anggota Keluarga");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapemilik['anggota_pdk'].' Orang');

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"7.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Domisili saat ini");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,status($datapemilik['domisili_pdk'],'domisili'));

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"8.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Memiliki tanah sejak tahun");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$dataip4t['a1miliktanah_ip4t']);

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);
					$row +=4;
					$pdf->Cell(0,0,"",1,1,'C');
					$pdf -> setFont ('Times','B',10);
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"A2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"PENGUASAAN ***");
					$row +=4;
					$pdf -> setFont ('Times','',10);
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nama Yang Menguasai");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['nma_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['almat_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"RT / RW");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['rt_pdk'].'/'.$datapenguasaan['rw_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['kel_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kecamatan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['kec_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kabupaten");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['kab_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nomor KTP");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['noktp_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['nama_pkr']);

					$birthdate = new DateTime($datapenguasaan['ttg_pdk']);
			    $today= new DateTime(date("Y-m-d"));
			    $age = $birthdate->diff($today)->y;

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Umur");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$age.' tahun');

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Status Perkawinan");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,status($datapenguasaan['status_pdk'],'statusnikah'));

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"6.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jumlah Anggota Keluarga");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$datapenguasaan['anggota_pdk'].' Orang');

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"7.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Domisili saat ini");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,status($datapenguasaan['domisili_pdk'],'domisili'));

					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 55,8);
					$pdf -> rect(85, $row, 9,8);
					$pdf -> rect(94, $row, 96,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"8.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Menguasai tanah sejak tahun");
					$pdf -> setXY(88,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(97,$row); $pdf->Cell(0,0,$dataip4t['a2kuasatanah_ip4t']);
					$pdf -> setFont ('Times','I',8);
					$row +=7;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'*) jika tidak diketahui boleh diisi dengan "No Name (NN)"');
					$row +=4;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'**) isi tabel dan beri check list (v) pada kotak yang tersedia sesuai dengan kondisi saat ini');
					$row +=4;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'***) tidak perlu diisi jika pemilik dan yang menguasai tanah adalah subyek yang sama');
					$pdf -> setFont ('Times','',10);

					$pdf -> AddPage();
					$row =20;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);
					$row +=4;
					$pdf -> setFont ('Times','B',10);
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"B");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"TERKAIT DENGAN OBYEK");
					$pdf -> setFont ('Times','',10);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"NIB / Nomor Identifikasi Bidang (bila ada)");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					if($datanop['nib_hak']){
						$nib = $datanop['nib_hak'];
					}else if($datanop['nib_ptsl']){
						$nib = $datanop['nib_ptsl'];
					}else{
						$nib = '';
					}
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$nib);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Nomor Pajak Bumi Bangunan (PBB)");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$nop);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$datapenduduk['almat_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"RT /RW");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$datapenduduk['rt_pdk'].'/'.$datapenduduk['rw_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$datapenduduk['kel_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kecamatan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$datapenduduk['kec_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Kabupaten");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$datapenduduk['kab_pdk']);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Luas Tanah");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					if($datanop['luasfisik_ptsl']){
						$luas = $datanop['luasfisik_ptsl'];
					}else if($datanop['luassppt_dhkp']){
						$luas = $datanop['luassppt_dhkp'];
					}else{
						$luas = '';
					}
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,$luas.' m2');
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"5.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Penguasaan Tanah");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bkuasatanah_ip4t'],'kuasatanah'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"6.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Perolehan Tanah");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bolehtanah_ip4t'],'olehtanah'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"7.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pemilikan Tanah");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bmiliktanah_ip4t'],'miliktanah'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"8.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Penggunaan Bidang Tanah Saat ini");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bgunatanah_ip4t'],'gunabidang'));
					$row +=4;
					$pdf -> rect(20, $row, 10,56);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"9.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jenis Pemanfaatan Bidang Tanah saat ini");
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"1 = Untuk Pemanfaatan Tempat Tinggal");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat1_ip4t'],'manfaat1'));
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2 = Untuk Kegiatan Produksi Pertanian");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat2_ip4t'],'manfaat2'));
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3 = Untuk Kegiatan Ekonomi / Perdagangan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat3_ip4t'],'manfaat3'));
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"4 = Untuk Usaha Jasa");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat4_ip4t'],'manfaat4'));
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"5 = Untuk Fasos / Fasum");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat5_ip4t'],'manfaat5'));
					$row +=4;
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"6 = Tidak ada pemanfaatan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bjenismanfaat6_ip4t'],'manfaat6'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"10.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Indikasi Tanah Terlantar");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bindikasi_ip4t'],'indikasi'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"11.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Sengketa, konflik dan perkara pertanahan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bsengketa_ip4t'],'sengketa'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"12.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Potensi Tanah Landeform");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['bpotensi_ip4t'],'potensi'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);
					$row +=4;
					$pdf -> setFont ('Times','B',10);
					$pdf -> setXY(22,$row); $pdf->Cell(0,0,"C");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"TERKAIT DENGAN AKSES");
					$pdf -> setFont ('Times','',10);
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Sertifikan pernah dijaminkan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['csertif_ip4t'],'sertif'));
					$row +=4;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 70,8);
					$pdf -> rect(100, $row, 9,8);
					$pdf -> rect(109, $row, 81,8);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Potensi akses ***");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,status($dataip4t['cpotensi_ip4t'],'potensiakses'));
					$row +=4;
					$pdf -> rect(20, $row, 10,20);
					$pdf -> rect(30, $row, 70,20);
					$pdf -> rect(100, $row, 9,20);
					$pdf -> rect(109, $row, 81,20);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Bantuan yang pernah diterima");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,'Jenis Bantuan : '.$dataip4t['cbantuanjenis_ip4t']);
					$row +=5;
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,'Dari                : '.$dataip4t['cbantuandari_ip4t']);
					$row +=5;
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,'Tanggal          : '.fdate($dataip4t['cbantuantanggal_ip4t'],'DDMMYYYY'));
					$row +=6;
					$pdf -> rect(20, $row, 10,20);
					$pdf -> rect(30, $row, 70,20);
					$pdf -> rect(100, $row, 9,20);
					$pdf -> rect(109, $row, 81,20);
					$row +=4;
					$pdf -> setXY(23,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pendapatan");
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,":");
					$row +=5;
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"a. Sebelum menerima sertifikat");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,'Rp  : '.rupiah($dataip4t['cpendapatanbelum_ip4t']));
					$row +=5;
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"b. Sesudah menerima sertipikat (saat ini)");
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,'Rp  : '.rupiah($dataip4t['cpendapatansudah_ip4t']));
					$pdf -> setFont ('Times','I',8);
					$row +=10;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'****) dapat diisi lebih dari satu pilihan');
					$row +=4;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'*****) harap dikonversi dalam bentuk rupiah berupa hasil panen, informasi ini digunakan untuk mengetahui kolerasi pendapatan sebelum');
					$row +=4;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,'dan sesudah penerimaan sertipikat');
					$row +=3;
					$pdf -> setFont ('Times','',10);
					$pdf -> setXY(140,$row); $pdf->Cell(0,0,"Semarang,");
					$row +=6;
					$pdf -> setFont ('Times','B',10);
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"PEMBERI KETERANGAN");
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,"PETUGAS INVENTARISASI");
					$row +=20;
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,"Penguasa");
					$pdf -> setXY(134,$row); $pdf->Cell(0,0,"FARID RAHMAN");

					$pdf->Output();
			}

	}
