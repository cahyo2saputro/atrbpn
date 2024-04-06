<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_5_2 extends CI_Controller
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

		public function edit($id,$idblk,$direct){
			$user = $this->auth_model->get_userdata();
			$block = $this->studio_2_1_model->sr_name_block($idblk);

			$template['type'] = "single";
			$template['table'] = "tb_ptsl";
			$template['join']['table']="tb_penduduk";
			$template['join']['key']="idpdk_ptsl";
			$template['join']['ref']="idpdk_pdk";
			$template['condition']['id_ptsl'] = $id;
			$this->content['template'] = $this->crud_model->get_data($template);

			$berkas['type'] = "multiple";
			$berkas['table'] = "tb_ptslberkas";
			$berkas['condition']['idptsl_pbk'] = $id;
			$this->content['berkas'] = $this->crud_model->get_data($berkas);

			$spt['type'] = "multiple";
			$spt['table'] = "tb_ptsldhkp";
			$spt['join']['table']="tb_dhkp";
			$spt['join']['key']="id_dhkp";
			$spt['join']['ref']="iddhkp_ptsl";
			$spt['condition']['idptsl_ptsl'] = $id;
			$this->content['sppt'] = $this->crud_model->get_data($spt);

			if ($this->input->post()) {

				$user = $this->auth_model->get_userdata();
				$dataarray = array(
					'luasfisik_ptsl'   => $this->input->post('luas'),
					'idusr_ptsl' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$id));

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Pengukuran-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Edit Luas PTSL dengan rincian ".displayArray($dataarray));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_5_2/data/<?=$direct;?>?search=<?php echo $idblk; ?>">
				<?php
			}

			$dat['table'] = "tb_pekerjaan";
			$dat['type'] = "multiple";
			$dat['orderby']['column'] = 'nama_pkr';
			$dat['orderby']['sort'] = 'asc';
			$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

			$status = $this->uri->segment(3);
			$set_status = $this->uri->segment(4);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);


				$idkel = $block['idkel_blk'];

				$this->content['data']['title'] = "e-Pengukuran : Edit Pengukuran Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-Pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_5_2/data/?search=".$idblk),array("Edit Pengajuan","Studio_5_2/edit/".$id."/".$idblk));

				$this->content['status'] = "edit";

				$this->content['block'] = $block;

				$dhkp['table'] = "tb_dhkp";
	      $dhkp['type'] = "multiple";
				$dhkp['join']['table'] = "tb_block";
				$dhkp['join']['key'] = "idblk_blk";
				$dhkp['join']['ref'] = "idblk_dhkp";
				$dhkp['condition']['idblk_dhkp'] =$idblk;
	      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);

				$this->content['load'] = array("studio5/edit_ptsl");
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

		public function data()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('NUB','nub_ptsl'),array('Nama','nma_pdk'),array('No. KTP','noktp_pdk'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Pengukuran : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Belum Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_5_2/data/?search=".$cari));

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

			$config['base_url'] = base_url().'Studio_5_2/data/';
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
			$dat['column'] = "tb_ptsl.nub_ptsl,tb_ptsl.luasfisik_ptsl,tb_ptsl.id_ptsl,tb_dhkp.nosppt_dhkp,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_ptsl.idblk_ptsl";
			$dat['join']['table'] = "tb_penduduk,tb_ptsldhkp,tb_dhkp,tb_block";
			$dat['join']['key'] = "idpdk_ptsl,idptsl_ptsl,id_dhkp,idblk_blk";
			$dat['join']['ref'] = "idpdk_pdk,id_ptsl,iddhkp_ptsl,idblk_ptsl";
			$dat['condition']['idblk_ptsl'] = $block['idblk_blk'];
			$dat['condition']['publish_ptsl'] = '1';
			$dat['orderby']['column'] = 'tb_ptsl.update_at';
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

			$this->content['load'] = array("studio5/data_studio_5_2");
			$this->load->view('adm',$this->content);
		}

		public function simpan_luas($id)
		{
			$user = $this->auth_model->get_userdata();

			$dataarray = array(
				'luasfisik_ptsl'   => $this->input->post('luas_ptsl'),
				'idusr_ptsl' => $user['idusr_usr']
			);
			$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$id));

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Pengukuran-0-'.$id,"Edit Luas PTSL dengan rincian ".displayArray($dataarray));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function addregister(){
			$user = $this->auth_model->get_userdata();


			if(empty($this->input->get('search'))){
				$idblk = $this->uri->segment(5);
			}else{
				$idblk = $this->input->get('search');
			}
			$block = $this->studio_2_1_model->sr_name_block($idblk);

			if ($this->input->post() && $this->input->post('nib_peta')!="") {

				$user = $this->auth_model->get_userdata();

				$dhkp = $this->input->post('dhkp');

				foreach ($dhkp as $dd) {
					$dataarray = array(
						'idblk_nub' => $idblk,
						'nohak_nub'   => $this->input->post('nohak_nub'),
						'iddhkp_nub'   => $dd,
						'status_nub'   => '1',
						'publish_nub' => '1',
						'idusr_nub' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);
					$simpan = $this->crud_model->input('tb_nub',$dataarray);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Pengukuran-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));
				}

				$hak['table'] 	= "tb_hak";
				$hak['type'] 	  = "single";
				$hak['column'] 	  = "nib_hak,id_kelurahan";
				$hak['condition']['no_hak'] 	  = $this->input->post('nohak_nub');
				$cekhak = $this->crud_model->get_data($hak);
				$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekhak['id_kelurahan'],'nib_nib'=>$cekhak['nib_hak'],'idref_nib'=>$insert_id,'status_nib'=>1));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_5_2/register/?search=<?php echo $this->input->get('search'); ?>">
				<?php
			}else if($this->input->post() && $this->input->post('nib_peta')==""){
				?>
				<script>
					alert("NIB belum terdaftar");
				</script>
				<?php
			}

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

				$idkel = $block['idkel_blk'];

				$this->content['data']['title'] = "e-Pengukuran : Register Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_5_2/register/?search=".$idblk),array("Tambah Register","Studio_5_2/addregister/?search=".$idblk));

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
				$nohak['cuzcondition'] = "no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE nohak_nub=no_hak AND publish_nub=1) AND status_hak <> 0 AND id_kelurahan = '$idkel'";
				$this->content['nohak'] = $this->crud_model->get_data($nohak);

				$this->content['block'] = $block;

				$this->content['load'] = array("studio3/form_register");
				$this->load->view('adm',$this->content);
	}

	public function addnop($hak,$idblk){
		$user = $this->auth_model->get_userdata();
		$block = $this->studio_2_1_model->sr_name_block($idblk);

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
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Pengukuran-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_5_2/register/?search=<?php echo $idblk; ?>">
			<?php
		}else if($this->input->post() && $this->input->post('nib_peta')==""){
			?>
			<script>
				alert("NIB belum terdaftar");
			</script>
			<?php
		}


		cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-Pengukuran : Tambah NOP ".$block['nma_kel']." Blok ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_5_2/register/?search=".$idblk),array("Tambah NOP","Studio_6_2/addnop/".$hak."/".$idblk));

			$this->content['status'] = "register";

			$dhkp['table'] = "tb_dhkp";
			$dhkp['type'] = "multiple";
			$dhkp['join']['table'] = "tb_block";
			$dhkp['join']['key'] = "idblk_blk";
			$dhkp['join']['ref'] = "idblk_dhkp";
			$dhkp['condition']['idblk_dhkp'] =$idblk;
			$this->content['dhkp'] = $this->crud_model->get_data($dhkp);

			$nohak['type'] = "single";
			$nohak['table'] = "tb_hak";
			$nohak['column'] = "no_hak";
			$nohak['condition']['no_hak'] = $hak;
			$this->content['nohak'] = $this->crud_model->get_data($nohak);

			$nub['type'] = "multiple";
			$nub['table'] = "tb_nub";
			$nub['column'] = "idkel_blk,nama_blk,nosppt_dhkp";
			$nub['join']['table'] = "tb_block,tb_dhkp";
			$nub['join']['key'] = "idblk_blk,id_dhkp";
			$nub['join']['ref'] = "idblk_nub,iddhkp_nub";
			$nub['condition']['nohak_nub'] = $hak;
			$this->content['nub'] = $this->crud_model->get_data($nub);

			$this->content['block'] = $block;

			$this->content['load'] = array("studio3/form_nop");
			$this->load->view('adm',$this->content);
}

	public function editregister($id,$idblk){
		$user = $this->auth_model->get_userdata();
		$block = $this->studio_2_1_model->sr_name_block($idblk);

		$dnohak['type'] = "single";
		$dnohak['table'] = "tb_nub";
		$dnohak['column'] = "nohak_nub,iddhkp_nub";
		$dnohak['cuzcondition'] = "idnub_nub='$id'";
		$this->content['dnohak'] = $this->crud_model->get_data($dnohak);

		$spt['type'] = "multiple";
		$spt['table'] = "tb_nub";
		$spt['join']['table'] = "tb_dhkp";
		$spt['join']['key'] = "id_dhkp";
		$spt['join']['ref'] = "iddhkp_nub";
		$spt['condition']['nohak_nub'] = $this->content['dnohak']['nohak_nub'];
		$this->content['sppt'] = $this->crud_model->get_data($spt);

		if ($this->input->post() && $this->input->post('nib_peta')!="") {

			$delete = $this->crud_model->delete('tb_nub',array('nohak_nub'=>$this->content['dnohak']['nohak_nub']));

			foreach ($this->content['sppt'] as $del) {
				$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$del['idnub_nub'],'status_nib'=>1));
			}

			$user = $this->auth_model->get_userdata();

			$dhkp = $this->input->post('dhkp');

			foreach ($dhkp as $dd) {
				$dataarray = array(
					'idblk_nub' => $idblk,
					'nohak_nub'   => $this->input->post('nohak_nub'),
					'iddhkp_nub'   => $dd,
					'status_nub'   => '1',
					'publish_nub' => '1',
					'idusr_nub' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);
				$simpan = $this->crud_model->input('tb_nub',$dataarray);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Pengukuran-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));
			}

			$hak['table'] 	= "tb_hak";
			$hak['type'] 	  = "single";
			$hak['column'] 	  = "nib_hak,id_kelurahan";
			$hak['condition']['no_hak'] 	  = $this->input->post('nohak_nub');
			$cekhak = $this->crud_model->get_data($hak);
			$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekhak['id_kelurahan'],'nib_nib'=>$cekhak['nib_hak'],'idref_nib'=>$insert_id,'status_nib'=>1));

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_5_2/register/?search=<?php echo $idblk; ?>">
			<?php
		}else if($this->input->post() && $this->input->post('nib_peta')==""){
			?>
			<script>
				alert("NIB belum terdaftar");
			</script>
			<?php
		}

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);
			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-Pengukuran : Edit Register Pengajuan Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_5_2/register/?search=".$idblk),array("Edit Register","Studio_5_2/editregister/".$id."/".$idblk));

			$this->content['status'] = "Edit register";

			$nohak['type'] = "multiple";
			$nohak['table'] = "tb_hak";
			$nohak['column'] = "no_hak";
			$nohak['orderby']['column'] = "no_hak";
			$nohak['orderby']['sort'] = "asc";
			$nohak['cuzcondition'] = "(no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE nohak_nub=no_hak) OR no_hak='".$this->content['dnohak']['nohak_nub']."') AND status_hak <> 0 AND id_kelurahan = '$idkel'";
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

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			$this->content['data']['title'] = "e-Pengukuran : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Sudah Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_5_2/register/?search=".$cari));

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


			$config['base_url'] = base_url().'Studio_5_2/register/';
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

			$this->content['load'] = array("studio3/dataregister_studio_3_2");
			$this->load->view('adm',$this->content);
		}

		public function updateregister(){
			$user = $this->auth_model->get_userdata();

			if(empty($this->input->get('search'))){
				$idblk = $this->uri->segment(5);
			}else{
				$idblk = $this->input->get('search');
			}
			$block = $this->studio_2_1_model->sr_name_block($idblk);

			$this->content['data']['title'] = "e-Panitia Desa : Register Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Update Register","Studio_3_2/updateregister/?search=".$idblk));

			$this->content['regis'] = $this->studio3_model->show_unregister($block['idblk_blk'],'data',$block['idkel_blk']);
				$i=0;
				foreach ($this->content['regis'] as $dd) {
					$statusdata[$i]['nib']=$dd->nib_ptsl;

					if($dd->iddhkp_ptsl && $dd->no_hak){
						// nop dan k4
						// 	CEK DATA nub
						$nub['table'] = "tb_nub";
						$nub['type'] = "single";
						$nub['condition']['nohak_nub'] = $dd->no_hak;
						$nub['condition']['iddhkp_nub']= $dd->iddhkp_ptsl;
						$exist = $this->crud_model->get_data($nub);

						$statusdata[$i]['nohak']=$dd->no_hak;

						if(!$exist){
							$dataarray = array(
								'idblk_nub' => $block['idblk_blk'],
								'nohak_nub'   => $dd->no_hak,
								'iddhkp_nub'   => $dd->iddhkp_ptsl,
								'status_nub'   => '1',
								'publish_nub' => '1',
								'idusr_nub' => $user['idusr_usr'],
								'create_at' => date("Y-m-d H:i:s")
							);
							$simpan = $this->crud_model->input('tb_nub',$dataarray);
							$insert_id = $this->db->insert_id();
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register dari Update dengan rincian ".displayArray($dataarray));

							$ptsl['table'] = "tb_ptsldhkp";
							$ptsl['column'] = "idkel_blk,nama_blk,nosppt_dhkp";
							$ptsl['join']['table'] = "tb_dhkp,tb_block";
							$ptsl['join']['ref']   = "id_dhkp,idblk_blk";
							$ptsl['join']['key']   = "iddhkp_ptsl,idblk_dhkp";
							$ptsl['type'] = "single";
							$ptsl['condition']['iddhkp_ptsl']= $dd->iddhkp_ptsl;
							$ptsl['condition']['idptsl_ptsl'] = $dd->id_ptsl;
							$dhkpptsl = $this->crud_model->get_data($ptsl);

							$statusdata[$i]['nop'] = createkodebpkad($dhkpptsl['idkel_blk']).''.$dhkpptsl['nama_blk'].''.$dhkpptsl['nosppt_dhkp'];
							$statusdata[$i]['status'] = '<span class="btn btn-primary">Data Sudah sertipikat berhasil ditambahkan</span>';
						}else{
						  $nub['column'] = "idkel_blk,nama_blk,nosppt_dhkp";
							$nub['join']['table'] = "tb_dhkp,tb_block";
							$nub['join']['ref']   = "iddhkp_nub,idblk_dhkp";
							$nub['join']['key']   = "id_dhkp,idblk_blk";
							$exist = $this->crud_model->get_data($nub);
							$statusdata[$i]['nop'] = createkodebpkad($exist['idkel_blk']).''.$exist['nama_blk'].''.$exist['nosppt_dhkp'];

							$statusdata[$i]['status'] = '<span class="btn btn-info">Data sudah ada di Sudah Sertipikat</span>';
						}
					}else if($dd->iddhkp_ptsl && !$dd->no_hak){
						// nop saja
						$dhkp['table'] = "tb_dhkp";
						$dhkp['type'] = "single";
						$dhkp['column'] = "idkel_blk,nama_blk,nosppt_dhkp";
						$dhkp['join']['table'] = "tb_block";
						$dhkp['join']['key']   = "idblk_blk";
						$dhkp['join']['ref']   = "idblk_dhkp";
						$dhkp['condition']['id_dhkp']= $dd->iddhkp_ptsl;
						$dhkpdata = $this->crud_model->get_data($dhkp);

						$statusdata[$i]['nohak']='-';
						$statusdata[$i]['nop'] = createkodebpkad($dhkpdata['idkel_blk']).''.$dhkpdata['nama_blk'].''.$dhkpdata['nosppt_dhkp'];
						$statusdata[$i]['status'] = '<span class="btn btn-warning">No Hak (k4) belum tersedia</span>';
					}else{
						$statusdata[$i]['nohak']='-';
						$statusdata[$i]['nop'] = '-';
						$statusdata[$i]['status'] = '<span class="btn btn-danger">NOP belum diinput</span>';
					}
					$i++;
				}

				$this->content['status'] = $statusdata;
				$this->content['load'] = array("studio3/status_update");
				$this->load->view('adm',$this->content);
		}

		public function dhkp()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama Wajib Pajak','nama_dhkp'),array('No Hak','nohak_nub'),array('NOP','nosppt_dhkp'),array('Belum Link NUB/NIB','disconect'),array('Sudah Link NUB/NIB','connect'));

			$this->content['data']['title'] = "e-Pengukuran : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." DHKP";
			$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("DHKP","Studio_5_2/dhkp/?search=".$cari));

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
				}else if($this->input->get('param')=='disconect'){
					$tdata['cuzcondition'] = 'id_dhkp NOT IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) AND id_dhkp NOT IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else if($this->input->get('param')=='connect'){
					$tdata['cuzcondition'] = 'id_dhkp IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) OR id_dhkp IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else{
					$tdata['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}else{
				if($this->input->get('param')=='disconect'){
					$tdata['cuzcondition'] = 'id_dhkp NOT IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) AND id_dhkp NOT IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else if($this->input->get('param')=='connect'){
					$tdata['cuzcondition'] = 'id_dhkp IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) OR id_dhkp IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}
			}

			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Studio_5_2/dhkp/';
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
			$dat['orderby']['column'] = 'tb_dhkp.nosppt_dhkp';
			$dat['orderby']['sort'] = 'asc';
			if($this->input->get('nilai')){
				if($this->input->get('param')=='nama_dhkp'){
					$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				}else if($this->input->get('param')=='nosppt_dhkp'){
					$cek = substr($this->input->get('nilai'),13,5);
					$dat['condition'][$this->input->get('param')] = $cek;
				}else if($this->input->get('param')=='disconect'){
					$dat['cuzcondition'] = 'id_dhkp NOT IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) AND id_dhkp NOT IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else if($this->input->get('param')=='connect'){
					$dat['cuzcondition'] = 'id_dhkp IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) OR id_dhkp IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else{
					$dat['condition'][$this->input->get('param')] = $this->input->get('nilai');
				}
			}else{
				if($this->input->get('param')=='disconect'){
					$dat['cuzcondition'] = 'id_dhkp NOT IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) AND id_dhkp NOT IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
				}else if($this->input->get('param')=='connect'){
					$dat['cuzcondition'] = 'id_dhkp IN (SELECT iddhkp_nub FROM tb_nub where publish_nub=1) OR id_dhkp IN (SELECT iddhkp_ptsl FROM tb_ptsldhkp)';
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

		public function k4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Pengukuran : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." K4";
			$this->content['data']['subtitle'] = array(array("e-pengukuran","Studio5"),array("Daftar Blok","Studio_5_1/index/?search=".$block['idkel_blk']),array("K4","Studio_5_2/k4/?search=".$cari));

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


			$config['base_url'] = base_url().'Studio_5_2/k4/';
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
												tb_hak.su_spasial,tb_hak.bidang_tanah,tb_block.nama_blk,tb_dhkp.nosppt_dhkp,tb_dhkp.nama_dhkp,tb_nub.publish_nub,count(nohak_nub) as jml";
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

		public function deleteregister($kode)
		{
			$ar = array(
				'publish_nub' => '0'
			);
			$hapus = $this->crud_model->update('tb_nub',$ar,array('idnub_nub'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Pengukuran-0-'.$kode,"Menghapus Data Register dengan kode ".$kode);

			$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$cekhak['idnub_nub'],'status_nib'=>1));

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

	}
