<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_3_2 extends CI_Controller
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

			$this->content['data']['title'] = "e-Panitia Desa : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Belum Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$cari));

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
			$dat['column'] = "tb_ptsl.nub_ptsl,tb_ptsl.id_ptsl,tb_dhkp.nosppt_dhkp,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_ptsl.idblk_ptsl";
			$dat['join']['table'] = "tb_penduduk,tb_ptsldhkp,tb_dhkp,tb_block";
			$dat['join']['key'] = "idpdk_ptsl,idptsl_ptsl,id_dhkp,idblk_blk";
			$dat['join']['ref'] = "idpdk_pdk,id_ptsl,iddhkp_ptsl,idblk_ptsl";
			$dat['condition']['idblk_ptsl'] = $block['idblk_blk'];
			$dat['condition']['publish_ptsl'] = '1';
			$dat['orderby']['column'] = 'tb_ptsl.update_at';
			$dat['orderby']['sort'] = 'desc';
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

			$this->content['load'] = array("studio3/data_studio_3_2");
			$this->load->view('adm',$this->content);
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
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));
				}

				$hak['table'] 	= "tb_hak";
				$hak['type'] 	  = "single";
				$hak['column'] 	  = "nib_hak,id_kelurahan";
				$hak['condition']['no_hak'] 	  = $this->input->post('nohak_nub');
				$cekhak = $this->crud_model->get_data($hak);
				$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekhak['id_kelurahan'],'nib_nib'=>$cekhak['nib_hak'],'idref_nib'=>$insert_id,'status_nib'=>1));

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

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

				$idkel = $block['idkel_blk'];

				$this->content['data']['title'] = "e-Panitia Desa : Register Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Tambah Register","Studio_3_2/addregister/?search=".$idblk));

				$this->content['status'] = "register";

				$dhkp['table'] = "tb_dhkp";
	      $dhkp['type'] = "multiple";
				$dhkp['column'] = "id_dhkp,idkel_blk,nosppt_dhkp";
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
				// $nohak['cuzcondition'] = "no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE nohak_nub=no_hak AND publish_nub=1) AND status_hak <> 0 AND id_kelurahan = '$idkel'";
				$nohak['cuzcondition'] = "status_hak <> 0 AND id_kelurahan = '$idkel'";
				$this->content['nohak'] = $this->crud_model->get_data($nohak);

				$this->content['block'] = $block;

				$this->content['load'] = array("studio3/form_register");
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
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));



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

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-Panitia Desa : Tambah NOP ".$block['nma_kel']." Blok ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Tambah NOP","Studio_3_2/addnop/".$hak."/".$idblk));

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
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Register  dengan rincian ".displayArray($dataarray));
			}

			$hak['table'] 	= "tb_hak";
			$hak['type'] 	  = "single";
			$hak['column'] 	  = "nib_hak,id_kelurahan";
			$hak['condition']['no_hak'] 	  = $this->input->post('nohak_nub');
			$cekhak = $this->crud_model->get_data($hak);
			$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekhak['id_kelurahan'],'nib_nib'=>$cekhak['nib_hak'],'idref_nib'=>$insert_id,'status_nib'=>1));


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


			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);
			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-Panitia Desa : Edit Register Pengajuan Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$idblk),array("Edit Register","Studio_3_2/editregister/".$id."/".$idblk));

			$this->content['status'] = "Edit register";

			$nohak['type'] = "multiple";
			$nohak['table'] = "tb_hak";
			$nohak['column'] = "no_hak";
			$nohak['orderby']['column'] = "no_hak";
			$nohak['orderby']['sort'] = "asc";
			// $nohak['cuzcondition'] = "(no_hak NOT IN (SELECT nohak_nub FROM tb_nub WHERE nohak_nub=no_hak) OR no_hak='".$this->content['dnohak']['nohak_nub']."') AND status_hak <> 0 AND id_kelurahan = '$idkel'";
			$nohak['cuzcondition'] = "status_hak <> 0 AND id_kelurahan = '$idkel'";
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

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Panitia Desa : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Sudah Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","Studio_3_2/register/?search=".$cari));

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

		public function dhkp()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama Wajib Pajak','nama_dhkp'),array('No Hak','nohak_nub'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Panitia Desa : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." DHKP";
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("DHKP","Studio_3_2/dhkp/?search=".$cari));

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

		public function k4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Panitia Desa : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." K4";
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("K4","Studio_3_2/k4/?search=".$cari));

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

				$this->db->trans_start();
				$datktp['table'] = "tb_penduduk";
	      		$datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
				$ktp = $this->crud_model->get_data($datktp);

				$user = $this->auth_model->get_userdata();

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('ktp'),
						'nma_pdk'   => addslashes($this->input->post('nama')),
						'ttl_pdk' => $this->input->post('ttl'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'idpeker_pdk' => $this->input->post('pekerjaan'),
						'agm_pdk' => $this->input->post('agama'),
						'almat_pdk' => $this->input->post('alamat'),
						'rt_pdk' => $this->input->post('rt'),
						'rw_pdk' => $this->input->post('rw'),
						'kel_pdk' => $this->input->post('kel'),
						'kec_pdk' => $this->input->post('kec'),
						'kab_pdk' => $this->input->post('kab'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

				}else{
					$ar = array(
						'nma_pdk'   => addslashes($this->input->post('nama')),
						'ttl_pdk' => $this->input->post('ttl'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'idpeker_pdk' => $this->input->post('pekerjaan'),
						'agm_pdk' => $this->input->post('agama'),
						'almat_pdk' => $this->input->post('alamat'),
						'rt_pdk' => $this->input->post('rt'),
						'rw_pdk' => $this->input->post('rw'),
						'kel_pdk' => $this->input->post('kel'),
						'kec_pdk' => $this->input->post('kec'),
						'kab_pdk' => $this->input->post('kab'),
						'idusr_pdk' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp['noktp_pdk']));
					$insert_id = $ktp['idpdk_pdk'];

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Update Data Penduduk dengan rincian ".displayArray($ar));

				}

				// GET NUB
				$datnub['table'] = "tb_ptsl";
	      		$datnub['type'] = "single";
				$datnub['column'] = "MAX(nub_ptsl) as maximum";
				$datnub['condition']['idblk_ptsl'] = $idblk;
				$datnub['condition']['publish_ptsl'] = 1;
				$nub = $this->crud_model->get_data($datnub);

				if($nub){
					$dnub=$nub['maximum']+1;
				}else{
					$dnub=1;
				}

					$dataarray = array(
						'nub_ptsl' => $dnub,
						'idpdk_ptsl'   => $insert_id,
						'idguna_ptsl' => $this->input->post('guna'),
						'idmanfaat_ptsl' => $this->input->post('manfaat'),
						'idblk_ptsl' => $this->input->post('blok'),
						'utara_ptsl' => addslashes($this->input->post('utara')),
						'timur_ptsl' => addslashes($this->input->post('timur')),
						'selatan_ptsl' => addslashes($this->input->post('selatan')),
						'barat_ptsl' => addslashes($this->input->post('barat')),
						'desc0_ptsl' => $this->input->post('des0'),
						'desc1_ptsl' => $this->input->post('des1'),
						'idkperluan_ptsl' => $this->input->post('dkeperluan'),
						'thn_ptsl' => $this->input->post('dtahun'),
						'desc2_ptsl' => $this->input->post('des2'),
						'idkperluan2_ptsl' => $this->input->post('dkeperluan2'),
						'thn2_ptsl' => $this->input->post('dtahun2'),
						'desc3_ptsl' => $this->input->post('des3'),
						'idkperluan3_ptsl' => $this->input->post('dkeperluan3'),
						'thn3_ptsl' => $this->input->post('dtahun3'),
						'desc4_ptsl' => $this->input->post('des4'),
						'idkperluan4_ptsl' => $this->input->post('dkeperluan4'),
						'thn4_ptsl' => $this->input->post('dtahun4'),
						'desc5_ptsl' => $this->input->post('des5'),
						'idkperluan5_ptsl' => $this->input->post('dkeperluan5'),
						'thn5_ptsl' => $this->input->post('dtahun5'),
						'dc_ptsl' => $this->input->post('dc'),
						'dpersil_ptsl' => $this->input->post('dpersil'),
						'dklas_ptsl' => $this->input->post('dklas'),
						'dluas_ptsl' => $this->input->post('dluas'),
						'ddari_ptsl' => $this->input->post('ddari'),
						'note_ptsl' => $this->input->post('note'),
						'hak_ptsl' => $this->input->post('jenishak'),
						'thn_risalah' => $this->input->post('thn_risalah'),
						'publish_ptsl' => '1',
						'idusr_ptsl' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);

				$simpan = $this->crud_model->input('tb_ptsl',$dataarray);
				$insert_id = $this->db->insert_id();

				$dhkp = $this->input->post('dhkp');
				foreach ($dhkp as $dd) {
					$datptsl ['idptsl_ptsl'] = $insert_id;
					$datptsl ['iddhkp_ptsl'] = $dd;

					$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
				}

				if(isset($_FILES['berkas'])){
					$count = count($_FILES['berkas']['name']);
					for($i=0;$i<$count;$i++){
							$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
							$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
							$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

							$file = explode(".",$_FILES["berkas"]["name"][$i]);
			        		$sum = count($file);
							$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
							$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
							$config1['upload_path']		= './DATA/BERKAS/';
							$config1['allowed_types']	= '*';
							$this->upload->initialize($config1);
							$uploads 				= $this->upload->do_upload('file');
							$data1					= $this->upload->data();
							$nama_upload 		= $data1['file_name'];

							if($data1){
								$ar = array(
									'idptsl_pbk' => $insert_id,
									'berkas_pbk' => $nama_upload
								);
								$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
								$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Add Berkas dengan rincian ".displayArray($ar));
							}
					}
				}

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data PTSL dengan rincian ".displayArray($dataarray));
				$this->db->trans_complete();

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

				$this->content['data']['title'] = "e-Panitia Desa : Tambah Pengajuan Kelurahan ".$block['nma_kel']." : ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblk),array("Tambah Pengajuan","Studio_3_2/input/?search=".$idblk));

				$this->content['status'] = "tambah";

				$template['type'] = "single";
				$template['table'] = "tb_ptsl";
				$template['column'] = "desc0_ptsl,desc1_ptsl,desc2_ptsl,desc3_ptsl,desc4_ptsl,desc5_ptsl,thn_risalah";
				$template['condition']['id_ptsl'] = 0;
				$this->content['template'] = $this->crud_model->get_data($template);

				$this->content['block'] = $block;

				$this->content['load'] = array("studio3/form_ptsl");
				$this->load->view('adm',$this->content);
	}

	public function edit($id,$idblk){
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
			$this->db->trans_start();
			$datktp['table'] = "tb_penduduk";
			$datktp['type'] = "single";
			$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
			$ktp = $this->crud_model->get_data($datktp);

			$user = $this->auth_model->get_userdata();

			if(!$ktp){
				$ar = array(
					'noktp_pdk' => $this->input->post('ktp'),
					'nma_pdk'   => addslashes($this->input->post('nama')),
					'ttl_pdk' => $this->input->post('ttl'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'idpeker_pdk' => $this->input->post('pekerjaan'),
					'agm_pdk' => $this->input->post('agama'),
					'almat_pdk' => $this->input->post('alamat'),
					'rt_pdk' => $this->input->post('rt'),
					'rw_pdk' => $this->input->post('rw'),
					'kel_pdk' => $this->input->post('kel'),
					'kec_pdk' => $this->input->post('kec'),
					'kab_pdk' => $this->input->post('kab'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
			}else{
				$ar = array(
					'nma_pdk'   => addslashes($this->input->post('nama')),
					'ttl_pdk' => $this->input->post('ttl'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
					'idpeker_pdk' => $this->input->post('pekerjaan'),
					'agm_pdk' => $this->input->post('agama'),
					'almat_pdk' => $this->input->post('alamat'),
					'rt_pdk' => $this->input->post('rt'),
					'rw_pdk' => $this->input->post('rw'),
					'kel_pdk' => $this->input->post('kel'),
					'kec_pdk' => $this->input->post('kec'),
					'kab_pdk' => $this->input->post('kab'),
					'idusr_pdk' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
				$insert_id = $ktp['idpdk_pdk'];
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
			}

			$dataarray = array(
				'idpdk_ptsl'   => $insert_id,
				'idguna_ptsl' => $this->input->post('guna'),
				'idmanfaat_ptsl' => $this->input->post('manfaat'),
				'idblk_ptsl' => $this->input->post('blok'),
				'utara_ptsl' => addslashes($this->input->post('utara')),
				'timur_ptsl' => addslashes($this->input->post('timur')),
				'selatan_ptsl' => addslashes($this->input->post('selatan')),
				'barat_ptsl' => addslashes($this->input->post('barat')),
				'desc0_ptsl' => $this->input->post('des0'),
				'desc1_ptsl' => $this->input->post('des1'),
				'idkperluan_ptsl' => $this->input->post('dkeperluan'),
				'thn_ptsl' => $this->input->post('dtahun'),
				'desc2_ptsl' => $this->input->post('des2'),
				'idkperluan2_ptsl' => $this->input->post('dkeperluan2'),
				'thn2_ptsl' => $this->input->post('dtahun2'),
				'desc3_ptsl' => $this->input->post('des3'),
				'idkperluan3_ptsl' => $this->input->post('dkeperluan3'),
				'thn3_ptsl' => $this->input->post('dtahun3'),
				'desc4_ptsl' => $this->input->post('des4'),
				'idkperluan4_ptsl' => $this->input->post('dkeperluan4'),
				'thn4_ptsl' => $this->input->post('dtahun4'),
				'desc5_ptsl' => $this->input->post('des5'),
				'idkperluan5_ptsl' => $this->input->post('dkeperluan5'),
				'thn5_ptsl' => $this->input->post('dtahun5'),
				'dc_ptsl' => $this->input->post('dc'),
				'dpersil_ptsl' => $this->input->post('dpersil'),
				'dklas_ptsl' => $this->input->post('dklas'),
				'dluas_ptsl' => $this->input->post('dluas'),
				'ddari_ptsl' => $this->input->post('ddari'),
				'note_ptsl' => $this->input->post('note'),
				'hak_ptsl' => $this->input->post('jenishak'),
				'thn_risalah' => $this->input->post('thn_risalah'),
				'publish_ptsl' => '1',
				'idusr_ptsl' => $user['idusr_usr']
			);
			$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$id));

			$delete = $this->crud_model->delete('tb_ptsldhkp',array('idptsl_ptsl'=>$id));

			$dhkp = $this->input->post('dhkp');
			foreach ($dhkp as $dd) {
				$datptsl ['idptsl_ptsl'] = $id;
				$datptsl ['iddhkp_ptsl'] = $dd;

				$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
			}

			if(isset($_FILES['berkas'])){
				$count = count($_FILES['berkas']['name']);
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
						$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
						$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
						$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

						$file = explode(".",$_FILES["berkas"]["name"][$i]);
						$sum = count($file);
						$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './DATA/BERKAS/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data1					= $this->upload->data();
						$nama_upload 		= $data1['file_name'];

						if($data1){
							$ar = array(
								'idptsl_pbk' => $id,
								'berkas_pbk' => $nama_upload
							);
							$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Add Berkas dengan rincian ".displayArray($ar));
						}
				}
			}

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));

			$this->db->trans_complete();
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

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);


			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-Panitia Desa : Edit Pengajuan Kelurahan ".$block['nma_kel']." : ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","Studio_3_2/data/?search=".$idblk),array("Edit Pengajuan","Studio_3_2/edit/".$id."/".$idblk));

			$this->content['status'] = "edit";

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

	public function formsaksi($idblok){
		$user = $this->auth_model->get_userdata();

		$saksi['table'] = "tb_saksiptsl";
		$saksi['type'] = "single";
		$saksi['condition']['idblk_spt'] = $idblok;
		$this->content['saksi'] = $this->crud_model->get_data($saksi);

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
					'nma_pdk'   => addslashes($this->input->post('namasaksi1')),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi1'),
					'agm_pdk' => $this->input->post('agamasaksi1'),
					'almat_pdk' => $this->input->post('alamatsaksi1'),
					'ttl_pdk' => $this->input->post('lahirsaksi1'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi1'))),
					'rt_pdk' => $this->input->post('rt1'),
					'rw_pdk' => $this->input->post('rw1'),
					'kel_pdk' => $this->input->post('kel1'),
					'kec_pdk' => $this->input->post('kec1'),
					'kab_pdk' => $this->input->post('kab1'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Penduduk (Saksi 1) dengan rincian ".displayArray($ar));
			}else{
				$ar = array(
					'nma_pdk'   => addslashes($this->input->post('namasaksi1')),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi1'),
					'agm_pdk' => $this->input->post('agamasaksi1'),
					'ttl_pdk' => $this->input->post('lahirsaksi1'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi1'))),
					'almat_pdk' => $this->input->post('alamatsaksi1'),
					'rt_pdk' => $this->input->post('rt1'),
					'rw_pdk' => $this->input->post('rw1'),
					'kel_pdk' => $this->input->post('kel1'),
					'kec_pdk' => $this->input->post('kec1'),
					'kab_pdk' => $this->input->post('kab1'),
					'idusr_pdk' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp1['noktp_pdk']));
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$ktp1['idpdk_pdk'],"Mengubah Data Penduduk (Saksi 1) dengan rincian ".displayArray($ar));
			}

			$datktp2['table'] = "tb_penduduk";
			$datktp2['type'] = "single";
			$datktp2['condition']['noktp_pdk'] = $this->input->post('niksaksi2');
			$ktp2 = $this->crud_model->get_data($datktp2);

			if(!$ktp2){
				$ar = array(
					'noktp_pdk' => $this->input->post('niksaksi2'),
					'nma_pdk'   => addslashes($this->input->post('namasaksi2')),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi2'),
					'agm_pdk' => $this->input->post('agamasaksi2'),
					'ttl_pdk' => $this->input->post('lahirsaksi2'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi2'))),
					'almat_pdk' => $this->input->post('alamatsaksi2'),
					'rt_pdk' => $this->input->post('rt2'),
					'rw_pdk' => $this->input->post('rw2'),
					'kel_pdk' => $this->input->post('kel2'),
					'kec_pdk' => $this->input->post('kec2'),
					'kab_pdk' => $this->input->post('kab2'),
					'publish_pdk' => '1',
					'idusr_pdk' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_penduduk',$ar);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Penduduk (Saksi 2) dengan rincian ".displayArray($ar));
			}else{
				$ar = array(
					'nma_pdk'   => addslashes($this->input->post('namasaksi2')),
					'idpeker_pdk' => $this->input->post('pekerjaansaksi2'),
					'agm_pdk' => $this->input->post('agamasaksi2'),
					'ttl_pdk' => $this->input->post('lahirsaksi2'),
					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi2'))),
					'almat_pdk' => $this->input->post('alamatsaksi2'),
					'rt_pdk' => $this->input->post('rt2'),
					'rw_pdk' => $this->input->post('rw2'),
					'kel_pdk' => $this->input->post('kel2'),
					'kec_pdk' => $this->input->post('kec2'),
					'kab_pdk' => $this->input->post('kab2'),
					'idusr_pdk' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp2['noktp_pdk']));
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$ktp2['idpdk_pdk'],"Mengubah Data Penduduk (Saksi 2) dengan rincian ".displayArray($ar));
			}

			if(!$this->content['saksi']){
				$dataarray = array(
					'idblk_spt' => $idblok,
					'nosurat_spt'   => $this->input->post('nosurat'),
					'tgl_spt'   => date("Y-m-d",strtotime($this->input->post('tanggal'))),
					'niksp1_spt' => $this->input->post('niksaksi1'),
					'niksp2_spt' => $this->input->post('niksaksi2'),
					'kades_spt' => addslashes($this->input->post('kepaladesa')),
					'idkel_spt' => $idkel,
					'publish_spt' => '1',
					'idusr_spt' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);
				$simpan = $this->crud_model->input('tb_saksiptsl',$dataarray);
				$idnew=$this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_saksiptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$idnew,"Menambahkan Data Saksi dengan rincian ".displayArray($dataarray));

			}else{
				$dataarray = array(
					'nosurat_spt'   => $this->input->post('nosurat'),
					'tgl_spt'   => date("Y-m-d",strtotime($this->input->post('tanggal'))),
					'niksp1_spt' => $this->input->post('niksaksi1'),
					'niksp2_spt' => $this->input->post('niksaksi2'),
					'kades_spt' => addslashes($this->input->post('kepaladesa')),
					'idkel_spt' => $idkel,
					'publish_spt' => '1',
					'idusr_spt' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_saksiptsl',$dataarray,array('idspt_spt'=>$this->content['saksi']['idspt_spt']));

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_saksiptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$this->content['saksi']['idspt_spt'],"Edit Data Saksi dengan rincian ".displayArray($dataarray));
			}

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio_3_1/index/?search=<?php echo $idkel; ?>">
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

			$this->content['data']['title'] = "e-Panitia Desa : Form Saksi Blok ".$datblock['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$block['idkel_blk']),array("Form Saksi","Studio_3_2/formsaksi/".$idblok));

			$this->content['status'] = "tambah";

			$this->content['load'] = array("studio3/form_saksi");
			$this->load->view('adm',$this->content);
		}

		public function formpanitia($idkel){
			$user = $this->auth_model->get_userdata();

			$saksi['table'] = "tb_panitia";
			$saksi['type'] = "single";
			$saksi['condition']['idkel_pnt'] = $idkel;
			$this->content['panitia'] = $this->crud_model->get_data($saksi);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$idkel);

			if ($this->input->post()) {

				$user = $this->auth_model->get_userdata();

				if(!$this->content['panitia']){
					$dataarray = array(
						'idkel_pnt' => $idkel,
						'tim_pnt' => $this->input->post('tim'),
						'no_pnt' => $this->input->post('no'),
						'tgl_pnt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'ketua_pnt' => $this->input->post('ketua'),
						'nipketua_pnt' => $this->input->post('nipketua'),
						'wakafis_pnt'   => $this->input->post('fisik'),
						'wakayur_pnt'   => $this->input->post('yuridis'),
						'sekre_pnt' => $this->input->post('sekre'),
						'bpn_pnt' => $this->input->post('satgasbpn'),
						'nipbpn_pnt' => $this->input->post('nipsatgasbpn'),
						'babinsa_pnt' => $this->input->post('satgasbabinsa'),
						'idusr_pnt' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);
					$simpan = $this->crud_model->input('tb_panitia',$dataarray);
					$idnew=$this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_panitia','e Panitia Desa-'.$idkel.'-'.$idnew,"Menambahkan Data Panitia dengan rincian ".displayArray($dataarray));

				}else{
					$dataarray = array(
						'tim_pnt' => $this->input->post('tim'),
						'no_pnt' => $this->input->post('no'),
						'tgl_pnt' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'ketua_pnt' => $this->input->post('ketua'),
						'nipketua_pnt' => $this->input->post('nipketua'),
						'wakafis_pnt'   => $this->input->post('fisik'),
						'wakayur_pnt'   => $this->input->post('yuridis'),
						'sekre_pnt' => $this->input->post('sekre'),
						'bpn_pnt' => $this->input->post('satgasbpn'),
						'nipbpn_pnt' => $this->input->post('nipsatgasbpn'),
						'babinsa_pnt' => $this->input->post('satgasbabinsa'),
						'idusr_pnt' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_panitia',$dataarray,array('id_pnt'=>$this->content['panitia']['id_pnt']));
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_panitia','e Panitia Desa-'.$idkel.'-'.$this->content['panitia']['id_pnt'],"mengubah Data Panitia dengan rincian ".displayArray($dataarray));

				}

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>studio3">
				<?php
			}

				$this->content['data']['title'] = "Susunan Panitia";
				$this->content['data']['subtitle'] = array(array("e-Desa","Studio3"),array("Form Panitia","Studio_3_2/formpanitia/".$idkel));

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
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-0-'.$kode,"Menghapus Data PTSL dengan kode ".$kode);

			$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$kode,'status_nib'=>0));

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
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_nub','e Panitia Desa-0-'.$kode,"Menghapus Data Register dengan kode ".$kode);

			$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$kode,'status_nib'=>1));

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function export($id,$idblock){

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
			  require(APPPATH .'plugins/fpdf/fpdf.php');

				$ptsl['type']                   = "single";
				$ptsl['table']                  = "tb_ptsl";
				$ptsl['condition']['id_ptsl'] = $id;
				$data                        = $this->crud_model->get_data($ptsl);

				if($data['thn5_ptsl']!=0){
					$thnptsl = $data['thn5_ptsl'];
				}else if($data['thn4_ptsl']!=0){
					$thnptsl = $data['thn4_ptsl'];
				}else if($data['thn3_ptsl']!=0){
					$thnptsl = $data['thn3_ptsl'];
				}else if($data['thn2_ptsl']!=0){
					$thnptsl = $data['thn2_ptsl'];
				}else{
					$thnptsl = $data['thn_ptsl'];
				}

				$pdk['table'] = "tb_penduduk";
				$pdk['type'] = "single";
				$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
				$pdk['join']['table'] = "tb_pekerjaan";
				$pdk['join']['key'] = "idpkr_pkr";
				$pdk['join']['ref'] = "idpeker_pdk";
				$datpdk = $this->crud_model->get_data($pdk);

				$block['table'] = "tb_block";
				$block['type'] = "single";
				$block['condition']['idblk_blk'] = $idblock;
				$datblock = $this->crud_model->get_data($block);

				$saksi['table'] = "tb_saksiptsl";
				$saksi['type'] = "single";
				$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
				$datsaksi = $this->crud_model->get_data($saksi);

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

				$kec['table'] = "ms_kelurahan";
				$kec['type'] = "single";
				$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
				$kec['join']['table'] = 'ms_kecamatan';
				$kec['join']['key'] = 'kd_kec';
				$kec['join']['ref'] = 'kdkec_kel';
				$kec['condition']['kd_full'] = $datblock['idkel_blk'];
				$kecamatan = $this->crud_model->get_data($kec);

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Data PTSL dengan kode ".$id);

				$panitia['type']                   = "single";
				$panitia['table']                  = "tb_panitia";
				$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
				$datapanitia                       = $this->crud_model->get_data($panitia);

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

				$dnop['type']                   = "multiple";
				$dnop['table']                  = "tb_ptsldhkp";
				$dnop['join']['table'] 				 = "tb_dhkp";
				$dnop['join']['key'] 					 = "id_dhkp";
				$dnop['join']['ref'] 					 = "iddhkp_ptsl";
				$dnop['condition']['idptsl_ptsl'] = $id;
				$datanop                        = $this->crud_model->get_data($dnop);

				$rent='';$aop='';
				foreach ($datanop as $dno) {
					$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
					$aop = $dno['aopsppt_dhkp'];
					$rent .= $nop.', ';
				}

				$birthdate = new DateTime($datpdk['ttg_pdk']);
		    $today= new DateTime(date("Y-m-d"));
		    $age = $birthdate->diff($today)->y;

				$user = $this->auth_model->get_userdata();

			    $pdf = new FPDF('p','mm',array(210,330));
			    $pdf -> AddPage();
			    $pdf -> setDisplayMode ('fullpage');
					$row = 15;
					$pdf -> setFont ('Times','B',11);
					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,$row); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
					$row +=10;
					$pdf -> setFont ('Times','B',13);
					$pdf -> setXY(10,$row); $pdf->Cell(0,0,"FORMULIR PENDAFTARAN",0,0,'C');

					$row +=17;
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']),0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$agama,0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tanggal Lahir",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$tgllahir = '';
					if($datpdk['ttg_pdk']){
						$tgllahir = fdate($datpdk['ttg_pdk'],'DDMMYYYY');
					}
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$age.' Tahun / '.$tgllahir,0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(120,5,$datpdk['almat_pdk']);

					$row +=13;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dalam hal ini bertindak untuk diri sendiri / selaku kuasa :",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tanggal Lahir",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');

					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Berdasarkan Surat Kuasa Nomor : .................................................. Tanggal ...............................");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini mengajukan :",0,0,'L');
					$row +=10;
					$pdf -> setFont ('Times','B',11);
					$pdf -> setXY(10,$row); $pdf->Cell(0,0,"Pendaftaran Konversi/Penegasan Hak/Pengakuan Hak/Pemberian Hak *",0,0,'C');
					$row +=10;
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Atas bidang tanah :",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Terletak di Blok",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datblock['nama_blk'],0,0,'L');
					$pdf -> setXY(93,$row); $pdf->Cell(0,0,"No SPPT : ".$rent,0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kelurahan/Desa",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kel'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kec'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"Semarang",0,0,'L');
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Untuk melengkapi permohonan dimaksud bersama ini kami lampirkan :",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.  Foto Copy KTP",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.  Foto Copy Kartu Keluarga",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.  Foto Copy SPPT PBB Tahun berjalan",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4.  Foto Copy Letter C Desa",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5.  Surat Pernyataan Penguasaan Fisik Bidang Tanah",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6.  Surat Keterangan Tanah Bekas Milik Adat ",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.  Surat Pernyataan Pemasangan Tanda Batas",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8.   .................................................................................................................................................",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"9.   .................................................................................................................................................",0,0,'L');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"10. ..................................................................................................................................................",0,0,'L');
					$row +=15;
					$pdf -> setXY(140,$row); $pdf->Cell(0,0,"Semarang, ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'L');
					$row +=5;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Hormat Kami,",0,0,'C');
					$row +=30;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']),0,0,'C');

					$pdf -> AddPage();
					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
					if($kecamatan['type_kel']==1){
						$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Desa/Kecamatan");
					}else{
						$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Kelurahan/Kecamatan");
					}
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']." / ".$kecamatan['nma_kec']);

					// KOLOM 1
					$row +=4;
					$pdf -> rect(10, $row, 190, 73);
					$row +=5.5;
					$pdf -> setXY(12,$row); $pdf->Cell(0,0,"I. IDENTIFIKASI SUBYEK");
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1. Perorangan");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"a. Nama");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. Tempat/Tanggal Lahir");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$tgllahir = '';
					if($datpdk['ttg_pdk']){
						$tgllahir = fdate($datpdk['ttg_pdk'],'DDMMYYYY');
					}
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk']." / ".$tgllahir,'DDMMYYYY');
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Nomor Identitas");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Alamat");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row -=2;
					$pdf -> setXY(73,$row); $pdf->MultiCell(0,4,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
					$row +=10.5;
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
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$aop);
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. RT/RW");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$row +=5.5;
					$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. No Bidang / NIB");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
					$row +=5.5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Luas");
					$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['luasfisik_ptsl']);
					$pdf -> setXY(80,$row); $pdf->Cell(0,0,"m");
					$pdf -> setFont ('Times','',8);
					$pdf -> setXY(83,$row-1.5); $pdf->Cell(0,0,"2");
					$pdf -> setFont ('Times','',10);
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
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Semarang , ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
					$row +=5.5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Petugas Pengumpul Data Yuridis");
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Peserta Pendaftaran Tanah Sistematis");
					$row +=30;
					$pdf -> setXY(18,$row); $pdf->Cell(50,0,$datapanitia['bpn_pnt'],0,0,'C');
					$pdf -> setXY(125,$row); $pdf->Cell(50,0,stripslashes($datpdk['nma_pdk']),0,0,'C');

					$row +=5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"NIP. ".$datapanitia['nipbpn_pnt']);

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
					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$agama);

					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tgl Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					if($datpdk['ttg_pdk']){
						$diff = date_diff(date_create($datpdk['ttg_pdk']), date_create(date('Y-m-d')));
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					}


					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$row -=2;
					$pdf -> setXY(62,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);

					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan dengan sesungguhnya serta dengan itikad baik bahwa saya menguasai/memiliki");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"sebidang tanah yang terletak di :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan / Blok");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
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
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idguna_ptsl'],'guna'));
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pemanfaatan Tanah");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idmanfaat_ptsl'],'manfaat'));
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
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Bahwa bidang tanah tersebut saya kuasai/miliki sejak tahun ".$thnptsl." yang sampai saat ini terhadap");
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
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"atau bea terhutang bagi saya yang wajib dan akan saya lunasi dengan nilai NJOP Rp. ......................");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.");
					if($data['idkperluan_ptsl']==1){
						$keperluan="Jual Beli";
					}else if($data['idkperluan_ptsl']==2){
						$keperluan="Waris";
					}else if($data['idkperluan_ptsl']==3){
						$keperluan="Hibah";
					}else if($data['idkperluan_ptsl']==4){
						$keperluan="Wakaf";
					}else if($data['idkperluan_ptsl']==5){
						$keperluan="Tukar Menukar";
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
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,stripslashes($datsaksi1['nma_pdk']));
					$pdf->SetFont('Times','',12);

					$pdf -> setXY(105,$row); $pdf->Cell(0,0,"2. ");
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(138,$row); $pdf->Cell(0,0,stripslashes($datsaksi2['nma_pdk']));
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
					$row -=3;
					$pdf -> setXY(48,$row); $pdf->MultiCell(50,5,$datsaksi1['almat_pdk']);
					$row +=3;
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
					$row -=2;
					$pdf -> setXY(138,$row); $pdf->MultiCell(0,5,$datsaksi2['almat_pdk']);

					$row +=23;
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Dibuat di  : Semarang");
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SAKSI-SAKSI");
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Tanggal   : ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
					$row +=8;
					$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan");

					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
					$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi1['nma_pdk'])."    )",0,0,'L');
					$pdf->SetFont('Times','I',10);
					$pdf -> setXY(120,$row); $pdf->Cell(50,0,"Materai Rp.6000");
					$pdf->SetFont('Times','',12);
					$row +=30;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
					$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi2['nma_pdk'])."    )",0,0,'L');
					$pdf -> setXY(125,$row-15); $pdf->Cell(50,0,"(    ".stripslashes($datpdk['nma_pdk'])."    )",0,0,'C');
					$row +=20;
					// $pdf -> setXY(20,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
					// $row +=5;
					// $pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					// $row +=25;
					// $pdf -> setXY(20,$row); $pdf->Cell(0,0,"(    ".stripslashes($datsaksi['kades_spt'])."    )",0,0,'C');

					// SURAT 3
					$pdf -> AddPage();
					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
					if($kecamatan['type_kel']==1){
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"DESA ".strtoupper($kecamatan['nma_kel']),0,0,'C');
					}else{
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"KELURAHAN ".strtoupper($kecamatan['nma_kel']),0,0,'C');
					}


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
					$row +=3;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"benar-benar dikuasai oleh : ".stripslashes($datpdk['nma_pdk'])." dan secara fisik dikerjakan sendiri secara aktif oleh yang bersangkutan.");
					$row +=13;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Bahwa riwayat tanah tersebut menurut catatan Buku C Desa ".$kecamatan['nma_kel']." adalah sebagai berikut :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a. ");
					$row -=2;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc0_ptsl']);
					$row +=18;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc1_ptsl']);
					$row +=18;
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
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Semarang, ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
					$row +=5;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
					$row +=5;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=20;
					$pdf -> setXY(150,$row); $pdf->Cell(0,0,stripslashes($datsaksi['kades_spt']),0,0,'C');

					// SURAT 4
					$pdf -> AddPage();
					$pdf -> rect(165, 10, 35, 10);
					$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
					$row =10;

					$row +=14;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS",0,0,'C');
					$row +=10;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
					$row +=5;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$pdf->SetFont('Times','',11);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$agama);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia/ Tgl Lahir");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$tgllahir = '';
					if($datpdk['ttg_pdk']){
						$pdf -> setXY(53,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					}

					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
					$row -=2;
					$pdf -> setXY(53,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Selaku pemilik tanah/pemohon pengukuran tanah bekas adat/yasan C Desa No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." seluas ".$data['dluas_ptsl']." m2, dipergunakan untuk ".$guna." terletak di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang.");
					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini menyatakan sebenar-benarnya :");
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
					$row -=2.5;
					$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa tanah yang kami mohonkan pengukuran di Kantor Pertanahan Kabupaten Semarang berdasarkan alas hak tersebut diatas tidak dalam jaminan sesuatu hutang, tidak diletakkan sita jaminan dan telah kami pasang tanda-tanda batasnya sesuai ketentuan yang berlaku, berupa Patok Beton.");
					$row +=18;
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
					$row +=13;
					$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Semarang ,".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Yang Menyatakan",0,0,'C');
					$row +=5;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Pemilik Tanah yang berbatasan,");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Utara");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setFont ('Times','',9);
					$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['utara_ptsl']);
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Timur");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setFont ('Times','',9);
					$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['timur_ptsl']);
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
					$pdf->SetFont('Times','I',10);
					$pdf -> setXY(125,$row-13); $pdf->Cell(0,5,"materai 6.000,-");
					$pdf->SetFont('Times','',11);
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Selatan");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setFont ('Times','',9);
					$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['selatan_ptsl']);
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Barat");
					$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
					$pdf -> setFont ('Times','',9);
					$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['barat_ptsl']);
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
					$pdf -> setXY(120,$row-20); $pdf->Cell(0,5,stripslashes($datpdk['nma_pdk']),0,0,'C');
					$row +=10;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Mengetahui",0,0,'C');
					$row +=4;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(0,$row); $pdf->Cell(0,5,stripslashes($datsaksi['kades_spt']),0,0,'C');

				$pdf->Output();
			}

			public function exportberkas($id,$idblock){

					define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');
					$guna='';

					$ptslberkas['type']                   = "multiple";
					$ptslberkas['table']                  = "tb_ptslberkas";
					$ptslberkas['condition']['idptsl_pbk'] = $id;
					$databerkas                           = $this->crud_model->get_data($ptslberkas);

					$user = $this->auth_model->get_userdata();

				    $pdf = new FPDF('p','mm',array(210,330));

				    $pdf -> setDisplayMode ('fullpage');

						if($databerkas){
							foreach ($databerkas as $dd) {
								$path_parts = pathinfo("./DATA/BERKAS/".$dd['berkas_pbk']);
								if($path_parts['extension']=='png' || $path_parts['extension']=='jpg' || $path_parts['extension']=='jpeg'){
									$pdf -> AddPage();
									$pdf -> Image("./DATA/BERKAS/".$dd['berkas_pbk'],10,15,190);
								}
							}
						}

					$pdf->Output();
				}

			public function exportk1($id,$idblock){

					define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');

					$ptsl['type']                   = "single";
					$ptsl['table']                  = "tb_ptsl";
					$ptsl['condition']['id_ptsl'] = $id;
					$data                        = $this->crud_model->get_data($ptsl);

					if($data['thn5_ptsl']!=0){
						$thnptsl = $data['thn5_ptsl'];
					}else if($data['thn4_ptsl']!=0){
						$thnptsl = $data['thn4_ptsl'];
					}else if($data['thn3_ptsl']!=0){
						$thnptsl = $data['thn3_ptsl'];
					}else if($data['thn2_ptsl']!=0){
						$thnptsl = $data['thn2_ptsl'];
					}else{
						$thnptsl = $data['thn_ptsl'];
					}

					$pdk['table'] = "tb_penduduk";
					$pdk['type'] = "single";
					$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
					$pdk['join']['table'] = "tb_pekerjaan";
					$pdk['join']['key'] = "idpkr_pkr";
					$pdk['join']['ref'] = "idpeker_pdk";
					$datpdk = $this->crud_model->get_data($pdk);

					$block['table'] = "tb_block";
					$block['type'] = "single";
					$block['condition']['idblk_blk'] = $idblock;
					$datblock = $this->crud_model->get_data($block);

					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
					$datsaksi = $this->crud_model->get_data($saksi);

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

					$kec['table'] = "ms_kelurahan";
					$kec['type'] = "single";
					$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
					$kec['join']['table'] = 'ms_kecamatan';
					$kec['join']['key'] = 'kd_kec';
					$kec['join']['ref'] = 'kdkec_kel';
					$kec['condition']['kd_full'] = $datblock['idkel_blk'];
					$kecamatan = $this->crud_model->get_data($kec);

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Data PTSL dengan kode ".$id);

					$panitia['type']                   = "single";
					$panitia['table']                  = "tb_panitia";
					$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
					$datapanitia                       = $this->crud_model->get_data($panitia);

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

					$dnop['type']                   = "multiple";
					$dnop['table']                  = "tb_ptsldhkp";
					$dnop['join']['table'] 				 = "tb_dhkp";
					$dnop['join']['key'] 					 = "id_dhkp";
					$dnop['join']['ref'] 					 = "iddhkp_ptsl";
					$dnop['condition']['idptsl_ptsl'] = $id;
					$datanop                        = $this->crud_model->get_data($dnop);

					$rent='';$aop='';
					foreach ($datanop as $dno) {
						$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
						$aop = $dno['aopsppt_dhkp'];
						$rent .= $nop.', ';
					}

					$birthdate = new DateTime($datpdk['ttg_pdk']);
			    $today= new DateTime(date("Y-m-d"));
			    $age = $birthdate->diff($today)->y;

					$user = $this->auth_model->get_userdata();

				    $pdf = new FPDF('p','mm',array(210,330));
				    $pdf -> AddPage();
				    $pdf -> setDisplayMode ('fullpage');
						$row = 15;
						$pdf -> setFont ('Times','B',11);
						$pdf -> rect(165, 10, 35, 10);
						$pdf -> setXY(170,$row); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
						$row +=10;
						$pdf -> setFont ('Times','B',13);
						$pdf -> setXY(10,$row); $pdf->Cell(0,0,"FORMULIR PENDAFTARAN",0,0,'C');

						$row +=17;
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :",0,0,'L');
						$row += 10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']),0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk'],0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$agama,0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tanggal Lahir",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						if($datpdk['ttg_pdk']){
							$pdf -> setXY(63,$row); $pdf->Cell(0,0,$age.' Tahun / '.fdate($datpdk['ttg_pdk'],'DDMMYYYY'),0,0,'L');
						}

						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr'],0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row-2); $pdf->MultiCell(120,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);

						$row +=13;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dalam hal ini bertindak untuk diri sendiri / selaku kuasa :",0,0,'L');
						$row += 10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tanggal Lahir",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"...........................................................................................................",0,0,'L');

						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Berdasarkan Surat Kuasa Nomor : .................................................. Tanggal ...............................");
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini mengajukan :",0,0,'L');
						$row +=10;
						$pdf -> setFont ('Times','B',11);
						$pdf -> setXY(10,$row); $pdf->Cell(0,0,"Pendaftaran Konversi/Penegasan Hak/Pengakuan Hak/Pemberian Hak *",0,0,'C');
						$row +=10;
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Atas bidang tanah :",0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Terletak di Blok",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datblock['nama_blk'],0,0,'L');
						$pdf -> setXY(93,$row); $pdf->Cell(0,0,"No SPPT : ".$rent,0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kelurahan/Desa",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kel'],0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kec'],0,0,'L');
						$row += 5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
						$pdf -> setXY(63,$row); $pdf->Cell(0,0,"Semarang",0,0,'L');
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Untuk melengkapi permohonan dimaksud bersama ini kami lampirkan :",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.  Foto Copy KTP",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.  Foto Copy Kartu Keluarga",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.  Foto Copy SPPT PBB Tahun berjalan",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4.  Foto Copy Letter C Desa",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5.  Surat Pernyataan Penguasaan Fisik Bidang Tanah",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6.  Surat Keterangan Tanah Bekas Milik Adat ",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.  Surat Pernyataan Pemasangan Tanda Batas",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8.   .................................................................................................................................................",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"9.   .................................................................................................................................................",0,0,'L');
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"10. ..................................................................................................................................................",0,0,'L');
						$row +=15;
						$pdf -> setXY(140,$row); $pdf->Cell(0,0,"Semarang, ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'L');
						$row +=5;
						$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Hormat Kami,",0,0,'C');
						$row +=30;
						$pdf -> setXY(130,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']),0,0,'C');

						$pdf -> AddPage();
						$pdf -> rect(165, 10, 35, 10);
						$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
						if($kecamatan['type_kel']==1){
							$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Desa/Kecamatan");
						}else{
							$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Kelurahan/Kecamatan");
						}
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']." / ".$kecamatan['nma_kec']);

						// KOLOM 1
						$row +=4;
						$pdf -> rect(10, $row, 190, 73);
						$row +=5.5;
						$pdf -> setXY(12,$row); $pdf->Cell(0,0,"I. IDENTIFIKASI SUBYEK");
						$row +=5.5;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1. Perorangan");
						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"a. Nama");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(73,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. Tempat/Tanggal Lahir");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						if($datpdk['ttg_pdk']){
							$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						}

						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Nomor Identitas");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Alamat");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$row -=2;
						$pdf -> setXY(73,$row); $pdf->MultiCell(0,4,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
						$row +=10.5;
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
						$pdf -> setXY(73,$row); $pdf->Cell(0,0,$aop);
						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. RT/RW");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$row +=5.5;
						$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. No Bidang / NIB");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(73,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
						$row +=5.5;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Luas");
						$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['luasfisik_ptsl']);
						$pdf -> setXY(80,$row); $pdf->Cell(0,0,"m");
						$pdf -> setFont ('Times','',8);
						$pdf -> setXY(83,$row-1.5); $pdf->Cell(0,0,"2");
						$pdf -> setFont ('Times','',10);
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
						$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Semarang , ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
						$row +=5.5;
						$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Petugas Pengumpul Data Yuridis");
						$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Peserta Pendaftaran Tanah Sistematis");
						$row +=30;
						$pdf -> setXY(18,$row); $pdf->Cell(50,0,$datapanitia['bpn_pnt'],0,0,'C');
						$pdf -> setXY(125,$row); $pdf->Cell(50,0,stripslashes($datpdk['nma_pdk']),0,0,'C');

						$row +=5;
						$pdf -> setXY(18,$row); $pdf->Cell(0,0,"NIP. ".$datapanitia['nipbpn_pnt']);

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
						$pdf -> rect(165, 10, 35, 10);
						$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,$agama);

						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tgl Lahir");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						if($datpdk['ttg_pdk']){
							$diff = date_diff(date_create($datpdk['ttg_pdk']), date_create(date('Y-m-d')));
							$pdf -> setXY(62,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						}


						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$row -=2;
						$pdf -> setXY(62,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);

						$row +=10;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan dengan sesungguhnya serta dengan itikad baik bahwa saya menguasai/memiliki");
						$row +=6;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"sebidang tanah yang terletak di :");
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan / Blok");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
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
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idguna_ptsl'],'guna'));
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pemanfaatan Tanah");
						$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idmanfaat_ptsl'],'manfaat'));
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
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Bahwa bidang tanah tersebut saya kuasai/miliki sejak tahun ".$thnptsl." yang sampai saat ini terhadap");
						$row +=6;
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
						$pdf -> setXY(25,$row); $pdf->Cell(0,0,"atau bea terhutang bagi saya yang wajib dan akan saya lunasi dengan nilai NJOP Rp. ......................");
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.");
						if($data['idkperluan_ptsl']==1){
							$keperluan="Jual Beli";
						}else if($data['idkperluan_ptsl']==2){
							$keperluan="Waris";
						}else if($data['idkperluan_ptsl']==3){
							$keperluan="Hibah";
						}else if($data['idkperluan_ptsl']==4){
							$keperluan="Wakaf";
						}else if($data['idkperluan_ptsl']==5){
							$keperluan="Tukar Menukar";
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
						$row +=6;
						$pdf -> setXY(27,$row); $pdf->Cell(0,0,"betul-betul yang saya punyai dan apabila terdapat bukti pemilikan/penguasaan");
						$row +=6;
						$pdf -> setXY(27,$row); $pdf->Cell(0,0,"atas tanah dimaksud setelah dibuatnya pernyataan ini dan/atau telah diterbitkan");
						$row +=6;
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
						$pdf -> setXY(48,$row); $pdf->Cell(0,0,stripslashes($datsaksi1['nma_pdk']));
						$pdf->SetFont('Times','',12);

						$pdf -> setXY(105,$row); $pdf->Cell(0,0,"2. ");
						$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Nama");
						$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
						$pdf->SetFont('Times','B',12);
						$pdf -> setXY(138,$row); $pdf->Cell(0,0,stripslashes($datsaksi2['nma_pdk']));
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
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia");
						$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");

						$from = new DateTime($datsaksi1['ttg_pdk']);
						$to   = new DateTime($datsaksi['tgl_spt']);
						$pdf -> setXY(48,$row); $pdf->Cell(0,0,$from->diff($to)->y);

						$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Usia");
						$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
						$from = new DateTime($datsaksi2['ttg_pdk']);
						$to   = new DateTime($datsaksi['tgl_spt']);
						$pdf -> setXY(138,$row); $pdf->Cell(0,0,$from->diff($to)->y);

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
						$row -=3;
						$pdf -> setXY(48,$row); $pdf->MultiCell(50,5,$datsaksi1['almat_pdk'].' RT.'.$datsaksi1['rt_pdk'].' RW.'.$datsaksi1['rw_pdk'].' '.$datsaksi1['kel_pdk'].' Kec.'.$datsaksi1['kec_pdk'].' Kab.'.$datsaksi1['kab_pdk']);
						$row +=3;
						$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Alamat");
						$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
						$row -=2;
						$pdf -> setXY(138,$row); $pdf->MultiCell(0,5,$datsaksi2['almat_pdk'].' RT.'.$datsaksi2['rt_pdk'].' RW.'.$datsaksi2['rw_pdk'].' '.$datsaksi2['kel_pdk'].' Kec.'.$datsaksi2['kec_pdk'].' Kab.'.$datsaksi2['kab_pdk']);

						$row +=23;
						$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Dibuat di  : Semarang");
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SAKSI-SAKSI");
						$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Tanggal   : ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
						$row +=8;
						$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan");

						$row +=15;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
						$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi1['nma_pdk'])."    )",0,0,'L');
						$pdf->SetFont('Times','I',10);
						$pdf -> setXY(120,$row); $pdf->Cell(50,0,"Materai");
						$pdf->SetFont('Times','',12);
						$row +=30;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
						$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi2['nma_pdk'])."    )",0,0,'L');
						$pdf -> setXY(125,$row-15); $pdf->Cell(50,0,"(    ".stripslashes($datpdk['nma_pdk'])."    )",0,0,'C');
						$row +=20;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
						$row +=5;
						if($kecamatan['type_kel']==1){
							$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
						}else{
							$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Lurah ".$kecamatan['nma_kel'],0,0,'C');
						}
						$row +=25;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"(    ".stripslashes($datsaksi['kades_spt'])."    )",0,0,'C');

						// SURAT 3
						$pdf -> AddPage();
						$pdf -> rect(165, 10, 35, 10);
						$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
						if($kecamatan['type_kel']==1){
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"DESA ".strtoupper($kecamatan['nma_kel']),0,0,'C');
						}else{
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"KELURAHAN ".strtoupper($kecamatan['nma_kel']),0,0,'C');
						}


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
						if($kecamatan['type_kel']==1){
							$desa = 'Kepala Desa';
						}else{
							$desa = 'Lurah';
						}
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini ".$desa." ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang,");
						$row +=7;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"dengan ini menerangkan sebagai berikut :");
						$row +=7;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Bahwa sebidang tanah seluas : ".$data['dluas_ptsl']." M2 yang terletak di Dk/Blok ".$datblock['nama_blk']." Desa ".$kecamatan['nma_kel']);
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang adalah tanah bekas milik adat sesuai Letter C Desa Nomor ");
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." luas ".$data['dluas_ptsl']." m2 tanah ".status($data['idguna_ptsl'],'guna')." yang dimanfaatkan untuk ".status($data['idmanfaat_ptsl'],'manfaat'));
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"dengan batas-batasnya.");
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
						$row +=3;
						$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"benar-benar dikuasai oleh : ".stripslashes($datpdk['nma_pdk'])." dan secara fisik dikerjakan sendiri secara aktif oleh yang bersangkutan.");
						$row +=13;
						$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Bahwa riwayat tanah tersebut menurut catatan Buku C Desa ".$kecamatan['nma_kel']." adalah sebagai berikut :");
						$row +=7;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a. ");
						$row -=2;
						$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc0_ptsl']);
						$row +=18;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b. ");
						$row -=2.5;
						$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,$data['desc1_ptsl']);
						$row +=18;
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
						$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Semarang, ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
						$row +=5;
						$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
						$row +=5;
						$pdf -> setXY(150,$row); $pdf->Cell(0,0,$desa." ".$kecamatan['nma_kel'],0,0,'C');
						$row +=20;
						$pdf -> setXY(150,$row); $pdf->Cell(0,0,stripslashes($datsaksi['kades_spt']),0,0,'C');

						// SURAT 4
						$pdf -> AddPage();
						$pdf -> rect(165, 10, 35, 10);
						$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
						$row =10;

						$row +=14;
						$pdf->SetFont('Times','B',14);
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS",0,0,'C');
						$row +=10;
						$pdf->SetFont('Times','',12);
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
						$row +=5;
						$pdf->SetFont('Times','',11);
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$pdf->SetFont('Times','B',11);
						$pdf -> setXY(53,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
						$pdf->SetFont('Times','',11);
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(53,$row); $pdf->Cell(0,0,$agama);
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia/ Tgl Lahir");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						if($datpdk['ttg_pdk']){
							$pdf -> setXY(53,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						}

						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
						$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
						$row -=2;
						$pdf -> setXY(53,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Selaku pemilik tanah/pemohon pengukuran tanah bekas adat/yasan C Desa No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." seluas ".$data['dluas_ptsl']." m2, tanah ".status($data['idguna_ptsl'],'guna')." dimanfaatkan untuk ".status($data['idmanfaat_ptsl'],'manfaat')." terletak di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang.");
						$row +=16;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini menyatakan sebenar-benarnya :");
						$row +=5;
						$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
						$row -=2.5;
						$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa tanah yang kami mohonkan pengukuran di Kantor Pertanahan Kabupaten Semarang berdasarkan alas hak tersebut diatas tidak dalam jaminan sesuatu hutang, tidak diletakkan sita jaminan dan telah kami pasang tanda-tanda batasnya sesuai ketentuan yang berlaku, berupa Patok Beton.");
						$row +=18;
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
						$row +=13;
						$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Semarang ,".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
						$row +=5;
						$pdf->SetFont('Times','B',11);
						$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Yang Menyatakan",0,0,'C');
						$row +=5;
						$pdf->SetFont('Times','',11);
						$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Pemilik Tanah yang berbatasan,");
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Utara");
						$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
						$pdf -> setFont ('Times','',9);
						$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['utara_ptsl']);
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Timur");
						$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
						$pdf -> setFont ('Times','',9);
						$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['timur_ptsl']);
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
						$pdf->SetFont('Times','I',10);
						$pdf -> setXY(125,$row-13); $pdf->Cell(0,5,"materai");
						$pdf->SetFont('Times','',11);
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Selatan");
						$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
						$pdf -> setFont ('Times','',9);
						$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['selatan_ptsl']);
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
						$row +=10;
						$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Barat");
						$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
						$pdf -> setFont ('Times','',9);
						$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['barat_ptsl']);
						$pdf -> setFont ('Times','',11);
						$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
						$pdf -> setXY(120,$row-20); $pdf->Cell(0,5,stripslashes($datpdk['nma_pdk']),0,0,'C');
						$row +=10;
						$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Mengetahui",0,0,'C');
						$row +=4;
						$pdf -> setXY(0,$row); $pdf->Cell(0,5,$desa." ".$kecamatan['nma_kel'],0,0,'C');
						$row +=25;
						$pdf -> setXY(0,$row); $pdf->Cell(0,5,stripslashes($datsaksi['kades_spt']),0,0,'C');

						$pdf -> AddPage();
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
						$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
						$pdf -> setXY(32,$row); $pdf->Cell(0,0," Jalan/Blok :".$aop." / Blok ".$datblock['nama_blk']);
						// $pdf -> setXY(102,$row); $pdf->Cell(0,0,"RT/RW : ");
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
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
						$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Perorangan/Badan Hukum");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"KTP/NIK (Perorangan)");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						if($datpdk['ttg_pdk']){
							$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						}

						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
						$row +=4;
						$pdf -> rect(20, $row, 10,11);
						$pdf -> rect(30, $row, 160,11);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$row -=3;
						$pdf -> setXY(75,$row); $pdf->MultiCell(100,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
						$row +=10;
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
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,'');//$data['ddari_ptsl']
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
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']." m2");
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
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");
						$row +=4;
						$rent='';
						foreach ($datanop as $dno) {
							$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
							$rent .= $nop.', ';
						}
						$pdf -> setXY(35,$row-2); $pdf->MultiCell(75,4,$rent);
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
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=3;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=3;
						$pdf -> rect(82, $row, 3,3);
						$pdf -> rect(110, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Pertanian :       Non Pertanian : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,18);
						$pdf -> rect(30, $row, 160,18);
						$row +=2;
						$pdf -> rect(80, $row, 3,3);
						$pdf -> rect(112, $row, 3,3);
						$pdf -> rect(158, $row, 3,3);
						$pdf -> rect(185, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d. Pemanfaatan tanah : Sawah :          Ladang/Tegalan:          Kebun/Kebun Campuran :          Kolam ikan : ");
						$row +=3;
						$pdf -> rect(84, $row, 3,3);
						$pdf -> rect(103, $row, 3,3);
						$pdf -> rect(130, $row, 3,3);
						$pdf -> rect(175, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Perumahan :      Industri :           Perkebunan :             Dikelola Pengembang :");
						$row +=3;
						$pdf -> rect(95, $row, 3,3);
						$pdf -> rect(135, $row, 3,3);
						$pdf -> rect(155, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lapangan Umum :           Pengembalaan Ternak :         Jalan : ");
						$row +=3;
						$pdf -> rect(95, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"tidak dimanfaatkan :           Lain-lain : ....................... (sebutkan)");
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
						$pdf -> Line(20, $row+2, 190, $row-15);
						$pdf -> SetLineWidth(0.1);
						$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");
						// HALAMAN 3
						$pdf -> AddPage();
				    $row = 10;
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
						$row +=3;
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
						$pdf -> Line(20, $row+20, 190, $row-60);
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
						// if($datpanitia['bpn_pnt']!="" && $datpanitia['babinsa_pnt']!=""){
								$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datapanitia['bpn_pnt']." )",0,0,'C');
								// $pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }else if($datpanitia['bpn_pnt']!=""){
						// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
						// }else if($datpanitia['babinsa_pnt']!=""){
						// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }else{
						// 	$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }

						$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".stripslashes($datpdk['nma_pdk'])." )",0,0,'C');
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
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".stripslashes($datpdk['nma_pdk']));
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
						$pdf -> Line(171, $row+3, 187, $row+3);
						$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu                                                                     dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
						$pdf -> setXY(100,$row); $pdf->MultiCell(145,5,stripslashes($datpdk['nma_pdk']));
						$row+=11;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> rect(98, $row, 3,3);
						$pdf -> rect(130, $row, 3,3);
						$row-=1;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :   V");
						$row+=7;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row+=2;
						$pdf -> rect(86, $row, 3,3);
						$pdf -> rect(115, $row, 3,3);
						$pdf -> rect(138, $row, 3,3);
						$row-=1;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada : ");
						$row+=7;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");
						$row+=6;
						$pdf -> rect(20, $row, 170,8);
						$row+=2;
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ".$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": Panitia Ajudikasi Tim ".$datapanitia['tim_pnt']);
						$row+=6;
						$pdf -> rect(20, $row, 55,27);
						$pdf -> rect(75, $row, 55,27);
						$pdf -> rect(130, $row, 60,27);
						$row+=3;
						$pdf -> setXY(20,$row); $pdf->Cell(55,0,"KETUA PANITIA AJUDIKASI",0,0,'C');
						$pdf -> setXY(75,$row); $pdf->Cell(55,0,"WAKIL KETUA BIDANG FISIK",0,0,'C');
						$pdf -> setXY(130,$row); $pdf->Cell(60,0,"WAKIL KETUA BIDANG YURIDIS",0,0,'C');
						$row+=20;
						$pdf -> setXY(20,$row); $pdf->Cell(55,0,"( ".$datapanitia['ketua_pnt']." )",0,0,'C');
						$pdf -> setXY(75,$row); $pdf->Cell(55,0,"( ".$datapanitia['wakafis_pnt']." )",0,0,'C');
						$pdf -> setXY(130,$row); $pdf->Cell(60,0,"( ".$datapanitia['wakayur_pnt']." )",0,0,'C');
						$row+=4;
						$pdf -> rect(20, $row, 85,30);
						$pdf -> rect(105, $row, 85,30);
						$row+=3;
						$pdf -> setXY(20,$row); $pdf->Cell(85,0,$desa." ".$kecamatan['nma_kel'],0,0,'C');
						$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');
						$row+=18;
						$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".stripslashes($datsaksi['kades_spt'])." )",0,0,'C');
						$pdf -> setXY(105,$row); $pdf->Cell(85,0,"( ".$datapanitia['sekre_pnt']." )",0,0,'C');

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
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row-28, 190, $row-28);
						$pdf -> Line(20, $row+36, 190, $row-28);
						$pdf -> Line(20, $row+36, 190, $row+36);
						$pdf -> SetLineWidth(0.1);
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
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".stripslashes($datpdk['nma_pdk']));
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");
						$row +=4;
						$pdf -> rect(20, $row, 10,32);
						$pdf -> rect(30, $row, 160,32);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"a.");
						$row -=2;
						// $pdf -> Line(128, $row+18, 140, $row+18);
						// $pdf -> Line(155, $row+18, 170, $row+18);
						// $pdf -> SetLineWidth(0.5);
						// $pdf -> Line(100, $row+18, 185, $row+18);
						// $pdf -> Line(100, $row+52, 185, $row+18);
						// $pdf -> Line(100, $row+52, 150, $row+52);
						// $pdf -> SetLineWidth(0.1);
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".stripslashes($datpdk['nma_pdk'])." K1)");
						$row +=30;
						$pdf -> rect(20, $row, 10,12);
						$pdf -> rect(30, $row, 160,12);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"b.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP .................. (K1)");
						$row +=10;
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
						// $pdf -> setFont('Times','',25);
						// $pdf -> setXY(88,$row); $pdf->Cell(0,0,"O");
						// $pdf -> setFont('Times','',10);
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"d.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3.1 , K3.2)");
						$row +=10;
						// $pdf -> rect(20, $row, 10,12);
						// $pdf -> rect(30, $row, 160,12);
						// $row +=4;
						// $pdf -> setXY(90,$row); $pdf->Cell(0,0,"e.");
						// $row -=2;
						// $pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
						// $row +=10;
						$pdf -> rect(20, $row, 10,42);
						$pdf -> rect(30, $row, 160,42);
						$row +=4;
						$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Semarang, ...........................");
						$row +=4;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datapanitia['tim_pnt']."",0,0,'C');
						$row +=4;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"KABUPATEN Semarang",0,0,'C');
						$row +=20;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"( ".$datapanitia['ketua_pnt']." )",0,0,'C');
						$row +=5;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"NIP : ".$datapanitia['nipketua_pnt'],0,0,'C');
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
						$tglpanitia = '';
						if($datapanitia){
							$tglpanitia = fdate($datapanitia['tgl_pnt'],'DDMMYYYY');
						}
						$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor 6 Tahun 2018 tentang PendaftaranTanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".$tglpanitia." Nomor. ".$datapanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");
						$row +=34;
						// $pdf -> setFont('Times','',18);
						// $pdf -> setXY(22,$row); $pdf->Cell(0,0,"O");
						$pdf -> setFont('Times','',10);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$row -=3;
						// $pdf -> Line(173, $row+8, 185, $row+8);
						// $pdf -> Line(35, $row+13, 105, $row+13);
						// $pdf -> Line(43, $row+21, 53, $row+21);
						// $pdf -> Line(34, $row+26, 52, $row+26);
						// $pdf -> Line(87, $row+21, 185, $row+21);
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal .......................... Nomor ............................ (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak ".stripslashes($datpdk['nma_pdk']));
						$row +=18;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");
						$row +=14;
						// $pdf -> SetLineWidth(0.5);
						// $pdf -> Line(20, $row, 190, $row);
						// $pdf -> Line(20, $row+40, 190, $row);
						// $pdf -> Line(20, $row+40, 190, $row+40);
						// $pdf -> SetLineWidth(0.1);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$row -=3;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan ".status($data['hak_ptsl'],'hak')." (K1)");
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
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"KETUA PANITIA AJUDIKASI PTSL TIM ".$datapanitia['tim_pnt']."",0,0,'C');
						$row+=4;
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"KABUPATEN Semarang",0,0,'C');
						$row+=24;
						$pdf -> setXY(123,$row); $pdf->Cell(50,0,"( ".$datapanitia['ketua_pnt']." )",0,0,'C');
						$row +=5;
						$pdf -> setXY(115,$row); $pdf->Cell(60,0,"NIP : ".$datapanitia['nipketua_pnt'],0,0,'C');
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

			public function exportrisalah($id,$idblock){

					define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');

					$ptsl['type']                   = "single";
					$ptsl['table']                  = "tb_ptsl";
					$ptsl['condition']['id_ptsl'] = $id;
					$data                        = $this->crud_model->get_data($ptsl);

					$dnop['type']                   = "multiple";
					$dnop['table']                  = "tb_ptsldhkp";
					$dnop['join']['table'] 				 = "tb_dhkp";
					$dnop['join']['key'] 					 = "id_dhkp";
					$dnop['join']['ref'] 					 = "iddhkp_ptsl";
					$dnop['condition']['idptsl_ptsl'] = $id;
					$datanop                        = $this->crud_model->get_data($dnop);

					$pdk['table'] = "tb_penduduk";
					$pdk['type'] = "single";
					$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
					$pdk['join']['table'] = "tb_pekerjaan";
					$pdk['join']['key'] = "idpkr_pkr";
					$pdk['join']['ref'] = "idpeker_pdk";
					$datpdk = $this->crud_model->get_data($pdk);

					$block['table'] = "tb_block";
					$block['type'] = "single";
					$block['condition']['idblk_blk'] = $idblock;
					$datblock = $this->crud_model->get_data($block);

					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
					$datsaksi = $this->crud_model->get_data($saksi);

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

					$kec['table'] = "ms_kelurahan";
					$kec['type'] = "single";
					$kec['column'] = "nma_kel,nma_kec,kd_full";
					$kec['join']['table'] = 'ms_kecamatan';
					$kec['join']['key'] = 'kd_kec';
					$kec['join']['ref'] = 'kdkec_kel';
					$kec['condition']['kd_full'] = $datblock['idkel_blk'];
					$kecamatan = $this->crud_model->get_data($kec);
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Risalah PTSL dengan kode ".$id);

					$panitia['table'] = "tb_panitia";
					$panitia['type'] = "single";
					$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
					$datpanitia = $this->crud_model->get_data($panitia);

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
						$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
						$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Perorangan/Badan Hukum");
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"KTP/NIK (Perorangan)");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
						if($datpdk['ttg_pdk']){
							$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
						}

						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
						$row +=4;
						$pdf -> rect(20, $row, 10,11);
						$pdf -> rect(30, $row, 160,11);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
						$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
						$row -=3;
						$pdf -> setXY(75,$row); $pdf->MultiCell(100,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
						$row +=10;
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
						$pdf -> setXY(78,$row); $pdf->Cell(0,0,'');//$data['ddari_ptsl']
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
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
						$row +=4;
						$pdf -> setXY(35,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']." m2");
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
						$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");
						$row +=4;
						$rent='';
						foreach ($datanop as $dno) {
							$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
							$rent .= $nop.', ';
						}
						$pdf -> setXY(35,$row-2); $pdf->MultiCell(75,4,$rent);
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
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
						$row +=3;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=3;
						$pdf -> rect(82, $row, 3,3);
						$pdf -> rect(110, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Pertanian :       Non Pertanian : ");
						$row +=4;
						$pdf -> rect(20, $row, 10,18);
						$pdf -> rect(30, $row, 160,18);
						$row +=2;
						$pdf -> rect(80, $row, 3,3);
						$pdf -> rect(112, $row, 3,3);
						$pdf -> rect(158, $row, 3,3);
						$pdf -> rect(185, $row, 3,3);
						$row +=1;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d. Pemanfaatan tanah : Sawah :          Ladang/Tegalan:          Kebun/Kebun Campuran :          Kolam ikan : ");
						$row +=3;
						$pdf -> rect(84, $row, 3,3);
						$pdf -> rect(103, $row, 3,3);
						$pdf -> rect(130, $row, 3,3);
						$pdf -> rect(175, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Perumahan :      Industri :           Perkebunan :             Dikelola Pengembang :");
						$row +=3;
						$pdf -> rect(95, $row, 3,3);
						$pdf -> rect(135, $row, 3,3);
						$pdf -> rect(155, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lapangan Umum :           Pengembalaan Ternak :         Jalan :");
						$row +=3;
						$pdf -> rect(95, $row, 3,3);
						$row +=1;
						$pdf -> setXY(64,$row); $pdf->Cell(0,0,"tidak dimanfaatkan :       Lain-lain : ....................... (sebutkan)");
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
						$pdf -> AddPage();
				    $row = 10;
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
						$row +=3;
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
						// if($datpanitia['bpn_pnt']!="" && $datpanitia['babinsa_pnt']!=""){
								$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
								// $pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }else if($datpanitia['bpn_pnt']!=""){
						// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
						// }else if($datpanitia['babinsa_pnt']!=""){
						// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }else{
						// 	$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
						// }

						$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".stripslashes($datpdk['nma_pdk'])." )",0,0,'C');
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
						$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".stripslashes($datpdk['nma_pdk']));
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
						$pdf -> setFont ('Times','',10);
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
						$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA :      Gogol Tetap :       Pekulen :        Andarbeni:");
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
						$pdf -> Line(171, $row+3, 187, $row+3);
						$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu                                                                     dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
						$pdf -> setXY(100,$row); $pdf->MultiCell(145,5,stripslashes($datpdk['nma_pdk']));
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
						$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ".$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
						$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".stripslashes($datsaksi['kades_spt'])." )",0,0,'C');
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
						$pdf -> SetLineWidth(0.5);
						$pdf -> Line(20, $row-28, 190, $row-28);
						$pdf -> Line(20, $row+36, 190, $row-28);
						$pdf -> Line(20, $row+36, 190, $row+36);
						$pdf -> SetLineWidth(0.1);
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
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".stripslashes($datpdk['nma_pdk']));
						$row +=4;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");
						$row +=4;
						$pdf -> rect(20, $row, 10,32);
						$pdf -> rect(30, $row, 160,32);
						$row +=4;
						$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3.");
						$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"a.");
						$row -=2;
						// $pdf -> Line(128, $row+18, 140, $row+18);
						// $pdf -> Line(155, $row+18, 170, $row+18);
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".stripslashes($datpdk['nma_pdk'])." K1)");
						$row +=30;
						$pdf -> rect(20, $row, 10,8);
						$pdf -> rect(30, $row, 160,8);
						$row +=4;
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"b.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP..............(K1)");
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
						$pdf -> setFont('Times','',25);
						$pdf -> setXY(88,$row); $pdf->Cell(0,0,"O");
						$pdf -> setFont('Times','',10);
						$pdf -> setXY(90,$row); $pdf->Cell(0,0,"d.");
						$row -=2;
						$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3.1, K3.2)");
						$row +=10;
						// $pdf -> rect(20, $row, 10,12);
						// $pdf -> rect(30, $row, 160,12);
						// $row +=4;
						// $pdf -> setXY(90,$row); $pdf->Cell(0,0,"e.");
						// $row -=2;
						// $pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
						// $row +=10;
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
						$tglpanitia = '';
						if($datpanitia){
							$tglpanitia = fdate($datpanitia['tgl_pnt'],'DDMMYYYY');
						}
						$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor ...... Tahun 2018 tentang PendaftaranTanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".$tglpanitia." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");
						$row +=34;
						// $pdf -> setFont('Times','',18);
						// $pdf -> setXY(22,$row); $pdf->Cell(0,0,"O");
						$pdf -> setFont('Times','',10);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
						$row -=3;
						// $pdf -> Line(173, $row+8, 185, $row+8);
						// $pdf -> Line(35, $row+13, 80, $row+13);
						// $pdf -> Line(43, $row+18, 53, $row+18);
						// $pdf -> Line(34, $row+23, 52, $row+23);
						// $pdf -> Line(87, $row+18, 185, $row+18);
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ............... Nomor ................. (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak ".stripslashes($datpdk['nma_pdk']));
						$row +=15;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");
						$row +=14;
						// $pdf -> SetLineWidth(0.5);
						// $pdf -> Line(20, $row, 190, $row);
						// $pdf -> Line(20, $row+40, 190, $row);
						// $pdf -> Line(20, $row+40, 190, $row+40);
						// $pdf -> SetLineWidth(0.1);
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
						$row -=3;
						$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan ".status($data['hak_ptsl'],'hak')." (K1)");
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

				public function exportk31($id,$idblock){

						define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
					  require(APPPATH .'plugins/fpdf/fpdf.php');

						$ptsl['type']                   = "single";
						$ptsl['table']                  = "tb_ptsl";
						$ptsl['condition']['id_ptsl'] = $id;
						$data                        = $this->crud_model->get_data($ptsl);

						$dnop['type']                   = "multiple";
						$dnop['table']                  = "tb_ptsldhkp";
						$dnop['join']['table'] 				 = "tb_dhkp";
						$dnop['join']['key'] 					 = "id_dhkp";
						$dnop['join']['ref'] 					 = "iddhkp_ptsl";
						$dnop['condition']['idptsl_ptsl'] = $id;
						$datanop                        = $this->crud_model->get_data($dnop);

						$pdk['table'] = "tb_penduduk";
						$pdk['type'] = "single";
						$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
						$pdk['join']['table'] = "tb_pekerjaan";
						$pdk['join']['key'] = "idpkr_pkr";
						$pdk['join']['ref'] = "idpeker_pdk";
						$datpdk = $this->crud_model->get_data($pdk);

						$block['table'] = "tb_block";
						$block['type'] = "single";
						$block['condition']['idblk_blk'] = $idblock;
						$datblock = $this->crud_model->get_data($block);

						$saksi['table'] = "tb_saksiptsl";
						$saksi['type'] = "single";
						$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
						$datsaksi = $this->crud_model->get_data($saksi);

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

						$kec['table'] = "ms_kelurahan";
						$kec['type'] = "single";
						$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
						$kec['join']['table'] = 'ms_kecamatan';
						$kec['join']['key'] = 'kd_kec';
						$kec['join']['ref'] = 'kdkec_kel';
						$kec['condition']['kd_full'] = $datblock['idkel_blk'];
						$kecamatan = $this->crud_model->get_data($kec);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Risalah PTSL dengan kode ".$id);

						if($kecamatan['type_kel']==1){
							$desa = 'Desa';
						}else{
							$desa = 'Kelurahan';
						}

						$panitia['table'] = "tb_panitia";
						$panitia['type'] = "single";
						$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
						$datpanitia = $this->crud_model->get_data($panitia);

						$rent='';$aop='';
						foreach ($datanop as $dno) {
							$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
							$aop = $dno['aopsppt_dhkp'];
							$rent .= $nop.', ';
						}

						$user = $this->auth_model->get_userdata();

					    $pdf = new FPDF('p','mm',array(210,330));

					    $pdf -> AddPage();
					    $pdf -> setDisplayMode ('fullpage');
							$pdf -> setFont ('Times','B',12);

							$pdf -> rect(165, 10, 35, 10);
							$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
							if($kecamatan['type_kel']==1){
								$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Desa/Kecamatan");
							}else{
								$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Kelurahan/Kecamatan");
							}

							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']." / ".$kecamatan['nma_kec']);

							// KOLOM 1
							$row +=4;
							$pdf -> rect(10, $row, 190, 73);
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
							if($datpdk['ttg_pdk']){
								$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
							}

							$row +=5.5;
							$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Nomor Identitas");
							$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
							$row +=5.5;
							$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Alamat");
							$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
							$row -=2;
							$pdf -> setXY(73,$row); $pdf->MultiCell(0,4,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
							$row +=10.5;
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
							$pdf -> setXY(73,$row); $pdf->Cell(0,0,$aop);
							$row +=5.5;
							$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. RT/RW");
							$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
							$row +=5.5;
							$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. No Bidang / NIB");
							$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(73,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
							$row +=5.5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Luas");
							$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['luasfisik_ptsl']);
							$pdf -> setXY(80,$row); $pdf->Cell(0,0,"m");
							$pdf -> setFont ('Times','',8);
							$pdf -> setXY(83,$row-1.5); $pdf->Cell(0,0,"2");
							$pdf -> setFont ('Times','',10);
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
							$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Semarang , ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
							$row +=5.5;
							$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Petugas Pengumpul Data Yuridis");
							$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Peserta Pendaftaran Tanah Sistematis");
							$row +=30;
							$pdf -> setXY(18,$row); $pdf->Cell(50,0,$datpanitia['bpn_pnt'],0,0,'C');
							$pdf -> setXY(125,$row); $pdf->Cell(50,0,$datpdk['nma_pdk'],0,0,'C');

							$row +=5;
							$pdf -> setXY(18,$row); $pdf->Cell(0,0,"NIP. ".$datpanitia['nipbpn_pnt']);

							$row +=20;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Keterangan :");
							$pdf->SetFont('Times','I',10);
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"*     centang jika ada");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"**    coret yang tidak diperlukan");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"***   uraikan secara singkat");


							$pdf -> AddPage();
					    $pdf -> Image("./assets/img/bpn.png",10,14,35);
					    $row = 18;
					    $pdf -> setFont ('Times','B',17);
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"KEMENTRIAN AGRARIA DAN TATA RUANG /",0,0,'C');

					    $row +=5.5;
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"BADAN PERTANAHAN NASIONAL",0,0,'C');
							$row +=5.5;
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"REPUBLIK INDONESIA",0,0,'C');
							$pdf -> setFont ('Times','B',15);
					    $row +=5.5;
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"KANTOR PERTANAHAN KABUPATEN Semarang",0,0,'C');

							$row +=5.5;
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"PROVINSI JAWA TENGAH",0,0,'C');

							$pdf -> setFont ('Times','',10);
					    $row +=5.5;
					    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"Jalan Kesatrian no 1 Semarang - 54111 telp.: (0275) 321139 E-mail :kab-Semarang@bpn.go.id",0,0,'C');

							$row +=5.5;
							$pdf -> SetLineWidth(1.5);
							$pdf -> setXY(15,$row);
							$pdf->Cell(0,0,"",1,1,'C');
							$row +=2;
							$pdf -> SetLineWidth(0.5);
							$pdf -> setXY(15,$row);
							$pdf->Cell(0,0,"",1,1,'C');

							$pdf -> setFont ('Times','',11);
					    $row +=8;
					    $pdf -> setXY(12,$row); $pdf->Cell(185,0,"Semarang, .....................................",0,0,'R');
							$row +=5;
					    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nomor");
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
							$row +=5;
					    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Sifat");
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(37,$row); $pdf->Cell(0,0,"Penting");
							$row +=5;
					    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Lampiran");
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(37,$row); $pdf->Cell(0,0,"-");
							$row +=5;
					    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Hal");
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(37,$row-2); $pdf->MultiCell(110,5,"Pemberitahuan Penyelesaian Pendaftaran Tanah Sistematis Lengkap ( PTSL ) Tahun 2020",0,'L');
							$row +=15;
					    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Kepada Yth.");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama ".stripslashes($datpdk['nma_pdk']));
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Alamat ".$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Di -");
							$row +=5;
							$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten Semarang");

							$row +=8;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Sehubungan penyelesaian kegiatan Pendaftaran Tanah Sistematis Lengkap tahun 2020 di ".$desa);
							$row +=5;
							$pdf -> setXY(15,$row-3); $pdf->MultiCell(190,5,$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec'].", kami sampaikan bahwa pendaftaran bidang tanah Saudara dengan :");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nomor Berkas");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Fisik");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['noberkas_ptsl']);
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Yuridis");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['noberkasyrd_ptsl']);
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"NIB");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Letak Tanah");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Blok");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
							$pdf -> setXY(80,$row); $pdf->Cell(0,0,"NOP");
							$pdf -> setXY(90,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(92,$row); $pdf->Cell(0,0,$rent);
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Desa");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']);
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Kecamatan");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kec']);
							$row +=5;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"NUB");
							$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['nub_ptsl']);
							$row +=8;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Belum dapat diterbitkan Buku Tanah dan Sertipikat Hak Atas Tanah karena saudara belum memenuhi");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a. Surat Penyataan Penguasaan Fisik Bidang Tanah dan Surat pernyataan pemasangan tanda batas;");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b. Surat pernyataan terhutang BPHTB dan/atau PPh bagi obyek bidang tanah PTSL yang terkena PPh dan BPHTB;");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c. Dokumen bukti kepemilikan atas bidang tanah yang Saudara punyai;");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d. Surat Keterangan Kepala Desa.");
							$row +=8;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Dalam rangka menindaklanjuti penerbitan Buku Tanah dan Sertipikat Hak Atas Tanah, diminta Saudara");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"untuk segera memenuhi persyaratan sebagaimana tersebut diatas, melalui Panitia Desa setempat.");
							$row +=8;
							$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Apabila pemenuhan surat-surat sebagaimana tersebut diatas dilakukan setelah masa Program PTSL");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"selesai, maka pemenuhan syarat-syarat tersebut didaftarkan ke Kantor Pertanahan Kabupaten Semarang dengan");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"membayar Biaya Penerimaan Negara Bukan Pajak (PNBP) sesuai dengan peraturan perundang-undangan yang");
							$row +=5;
							$pdf -> setXY(15,$row); $pdf->Cell(0,0,"berlaku.");
							$row +=8;
							$pdf -> setXY(45,$row); $pdf->Cell(0,0,"Demikian pemberitahuan ini untuk menjadi maklum.");
							$row +=8;
							$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Semarang, ...........................");
							$row +=5;
							$pdf -> setXY(110,$row); $pdf->Cell(90,0,"Ketua Panitia Ajudikai PTSL Tim ".$datpanitia['tim_pnt']."",0,0,'C');
							$row +=5;
							$pdf -> setXY(110,$row); $pdf->Cell(90,0,"Kabupaten Semarang",0,0,'C');
							$row +=25;
							$pdf -> setXY(110,$row); $pdf->Cell(90,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
							$row +=5;
							$pdf -> setXY(110,$row); $pdf->Cell(90,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');
							$row +=15;
							$pdf -> setFont ('Helvetica','I',12);
							$pdf -> setXY(10,$row); $pdf->Cell(190,0,"Melayani, Profesional, Terpercaya",0,0,'C');


							$pdf -> addpage();
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
							$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan/Blok : ".$aop." / Blok ".$datblock['nama_blk']);
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
							if($datpdk['ttg_pdk']){
								$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
							}

							$row +=4;
							$pdf -> rect(20, $row, 10,8);
							$pdf -> rect(30, $row, 160,8);
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
							$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
							$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
							$row +=4;
							$pdf -> rect(20, $row, 10,11);
							$pdf -> rect(30, $row, 160,11);
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
							$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
							$row -=3;
							$pdf -> setXY(75,$row); $pdf->MultiCell(100,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
							$row +=10;
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
							$pdf -> setXY(78,$row); $pdf->Cell(0,0,'');//$data['ddari_ptsl']
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
							$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
							$row +=4;
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
							$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
							$row +=4;
							$pdf -> setXY(35,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']." m2");
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
							$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");
							$row +=4;
							$rent='';
							foreach ($datanop as $dno) {
								$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
								$rent .= $nop.', ';
							}
							$pdf -> setXY(35,$row-2); $pdf->MultiCell(75,4,$rent);
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
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
							$row +=3;
							$pdf -> rect(20, $row, 10,8);
							$pdf -> rect(30, $row, 160,8);
							$row +=3;
							$pdf -> rect(82, $row, 3,3);
							$pdf -> rect(110, $row, 3,3);
							$row +=1;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Pertanian :       Non Pertanian : ");
							$row +=4;
							$pdf -> rect(20, $row, 10,18);
							$pdf -> rect(30, $row, 160,18);
							$row +=2;
							$pdf -> rect(80, $row, 3,3);
							$pdf -> rect(112, $row, 3,3);
							$pdf -> rect(158, $row, 3,3);
							$pdf -> rect(185, $row, 3,3);
							$row +=1;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d. Pemanfaatan tanah : Sawah :          Ladang/Tegalan:          Kebun/Kebun Campuran :          Kolam ikan : ");
							$row +=3;
							$pdf -> rect(84, $row, 3,3);
							$pdf -> rect(103, $row, 3,3);
							$pdf -> rect(130, $row, 3,3);
							$pdf -> rect(175, $row, 3,3);
							$row +=1;
							$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Perumahan :      Industri :           Perkebunan :             Dikelola Pengembang :");
							$row +=3;
							$pdf -> rect(95, $row, 3,3);
							$pdf -> rect(135, $row, 3,3);
							$pdf -> rect(155, $row, 3,3);
							$row +=1;
							$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lapangan Umum :           Pengembalaan Ternak :         Jalan : ");
							$row +=3;
							$pdf -> rect(95, $row, 3,3);
							$row +=1;
							$pdf -> setXY(64,$row); $pdf->Cell(0,0,"tidak dimanfaatkan :         Lain-lain : ....................... (sebutkan)");
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
							$pdf -> Line(20, $row+2, 190, $row-15);
							$pdf -> SetLineWidth(0.1);
							$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");

							$pdf -> AddPage();
					    $row = 10;
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
							$row +=3;
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
							$pdf -> Line(20, $row+20, 190, $row-60);
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
							// if($datpanitia['bpn_pnt']!="" && $datpanitia['babinsa_pnt']!=""){
									$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
									// $pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
							// }else if($datpanitia['bpn_pnt']!=""){
							// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
							// }else if($datpanitia['babinsa_pnt']!=""){
							// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
							// }else{
							// 	$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
							// }

							$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".stripslashes($datpdk['nma_pdk'])." )",0,0,'C');
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
							$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".stripslashes($datpdk['nma_pdk']));
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
							$pdf -> setFont ('Times','',10);
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
							$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA : V    Gogol Tetap :       Pekulen :        Andarbeni:");
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
							$pdf -> Line(171, $row+3, 187, $row+3);
							$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu                                                                     dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
							$pdf -> setXY(100,$row); $pdf->MultiCell(145,5,$datpdk['nma_pdk']);
							$row+=11;
							$pdf -> rect(20, $row, 10,8);
							$pdf -> rect(30, $row, 160,8);
							$row+=2;
							$pdf -> rect(98, $row, 3,3);
							$pdf -> rect(130, $row, 3,3);
							$row-=1;
							$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
							$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :  V");
							$row+=7;
							$pdf -> rect(20, $row, 10,8);
							$pdf -> rect(30, $row, 160,8);
							$row+=2;
							$pdf -> rect(86, $row, 3,3);
							$pdf -> rect(115, $row, 3,3);
							$pdf -> rect(138, $row, 3,3);
							$row-=1;
							$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
							$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada : ");
							$row+=7;
							$pdf -> rect(20, $row, 170,8);
							$row+=2;
							$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");
							$row+=6;
							$pdf -> rect(20, $row, 170,8);
							$row+=2;
							$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
							$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ".$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
							$pdf -> rect(20, $row, 55,27);
							$pdf -> rect(75, $row, 55,27);
							$pdf -> rect(130, $row, 60,27);
							$row+=3;
							$pdf -> setXY(20,$row); $pdf->Cell(55,0,"KETUA PANITIA AJUDIKASI",0,0,'C');
							$pdf -> setXY(75,$row); $pdf->Cell(55,0,"WAKIL KETUA BIDANG FISIK",0,0,'C');
							$pdf -> setXY(130,$row); $pdf->Cell(60,0,"WAKIL KETUA BIDANG YURIDIS",0,0,'C');
							$row+=20;
							$pdf -> setXY(20,$row); $pdf->Cell(55,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
							$pdf -> setXY(75,$row); $pdf->Cell(55,0,"( ".$datpanitia['wakafis_pnt']." )",0,0,'C');
							$pdf -> setXY(130,$row); $pdf->Cell(60,0,"( ".$datpanitia['wakayur_pnt']." )",0,0,'C');
							$row+=4;
							$pdf -> rect(20, $row, 85,30);
							$pdf -> rect(105, $row, 85,30);
							$row+=3;
							if($kecamatan['type_kel']==1){
								$desa = 'Kepala Desa';
							}else{
								$desa = 'Lurah';
							}
							$pdf -> setXY(20,$row); $pdf->Cell(85,0,$desa.' '.$kecamatan['nma_kel'],0,0,'C');
							$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');
							$row+=18;
							$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".stripslashes($datsaksi['kades_spt'])." )",0,0,'C');
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
							$pdf -> SetLineWidth(0.5);
							$pdf -> Line(20, $row-28, 190, $row-28);
							$pdf -> Line(20, $row+36, 190, $row-28);
							$pdf -> Line(20, $row+36, 190, $row+36);
							$pdf -> SetLineWidth(0.1);
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
							$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".stripslashes($datpdk['nma_pdk']));
							$row +=4;
							$pdf -> rect(20, $row, 10,8);
							$pdf -> rect(30, $row, 160,8);
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2.");
							$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");
							$row +=4;
							$pdf -> rect(20, $row, 10,32);
							$pdf -> rect(30, $row, 160,32);
							$row +=4;
							$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3.");
							$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
							$pdf -> setXY(90,$row); $pdf->Cell(0,0,"a.");
							$row -=2;
							// $pdf -> Line(128, $row+18, 140, $row+18);
							// $pdf -> Line(155, $row+18, 170, $row+18);
							$pdf -> SetLineWidth(0.5);
							$pdf -> Line(100, $row+18, 185, $row+18);
							$pdf -> Line(100, $row+52, 185, $row+18);
							$pdf -> Line(100, $row+52, 150, $row+52);
							$pdf -> SetLineWidth(0.1);
							$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".stripslashes($datpdk['nma_pdk'])." K1)");
							$row +=30;
							$pdf -> rect(20, $row, 10,12);
							$pdf -> rect(30, $row, 160,12);
							$row +=4;
							$pdf -> setXY(90,$row); $pdf->Cell(0,0,"b.");
							$row -=2;
							$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP ..................... (K1)");
							$row +=10;
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
							$pdf -> setFont('Times','',25);
							$pdf -> setXY(88,$row); $pdf->Cell(0,0,"O");
							$pdf -> setFont('Times','',10);
							$pdf -> setXY(90,$row); $pdf->Cell(0,0,"d.");
							$row -=2;
							$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3.1, K3.2)");
							$row +=10;
							// $pdf -> rect(20, $row, 10,12);
							// $pdf -> rect(30, $row, 160,12);
							// $row +=4;
							// $pdf -> setXY(90,$row); $pdf->Cell(0,0,"e.");
							// $row -=2;
							// $pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
							// $row +=10;
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
							$tglpanitia = '';
							if($datpanitia){
								$tglpanitia = fdate($datpanitia['tgl_pnt'],'DDMMYYYY');
							}
							$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor 6 Tahun 2018 tentang Pendaftaran Tanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".$tglpanitia." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");
							$row +=34;
							// $pdf -> setFont('Times','',18);
							// $pdf -> setXY(22,$row); $pdf->Cell(0,0,"O");
							$pdf -> setFont('Times','',10);
							$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
							$row -=3;
							// $pdf -> Line(173, $row+8, 185, $row+8);
							// $pdf -> Line(38, $row+13, 107, $row+13);
							// $pdf -> Line(43, $row+21, 53, $row+21);
							// $pdf -> Line(34, $row+26, 52, $row+26);
							// $pdf -> Line(87, $row+21, 185, $row+21);
							$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ................................ Nomor .................................. (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak ".stripslashes($datpdk['nma_pdk']));
							$row +=18;
							$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");
							$row +=14;
							// $pdf -> SetLineWidth(0.5);
							// $pdf -> Line(20, $row, 190, $row);
							// $pdf -> Line(20, $row+40, 190, $row);
							// $pdf -> Line(20, $row+40, 190, $row+40);
							// $pdf -> SetLineWidth(0.1);
							$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
							$row -=3;
							$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan ".status($data['hak_ptsl'],'hak')." (K1)");
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

					public function exportk31new($id,$idblock){

							define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
						  require(APPPATH .'plugins/fpdf/fpdf.php');

							$ptsl['type']                   = "single";
							$ptsl['table']                  = "tb_ptsl";
							$ptsl['condition']['id_ptsl'] = $id;
							$data                        = $this->crud_model->get_data($ptsl);

							if($data['thn5_ptsl']!=0){
								$thnptsl = $data['thn5_ptsl'];
							}else if($data['thn4_ptsl']!=0){
								$thnptsl = $data['thn4_ptsl'];
							}else if($data['thn3_ptsl']!=0){
								$thnptsl = $data['thn3_ptsl'];
							}else if($data['thn2_ptsl']!=0){
								$thnptsl = $data['thn2_ptsl'];
							}else{
								$thnptsl = $data['thn_ptsl'];
							}
							

							$dnop['type']                   = "multiple";
							$dnop['table']                  = "tb_ptsldhkp";
							$dnop['join']['table'] 				 = "tb_dhkp";
							$dnop['join']['key'] 					 = "id_dhkp";
							$dnop['join']['ref'] 					 = "iddhkp_ptsl";
							$dnop['condition']['idptsl_ptsl'] = $id;
							$datanop                        = $this->crud_model->get_data($dnop);

							$pdk['table'] = "tb_penduduk";
							$pdk['type'] = "single";
							$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
							$pdk['join']['table'] = "tb_pekerjaan";
							$pdk['join']['key'] = "idpkr_pkr";
							$pdk['join']['ref'] = "idpeker_pdk";
							$datpdk = $this->crud_model->get_data($pdk);

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

							$block['table'] = "tb_block";
							$block['type'] = "single";
							$block['condition']['idblk_blk'] = $idblock;
							$datblock = $this->crud_model->get_data($block);

							$saksi['table'] = "tb_saksiptsl";
							$saksi['type'] = "single";
							$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
							$datsaksi = $this->crud_model->get_data($saksi);

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

							$kec['table'] = "ms_kelurahan";
							$kec['type'] = "single";
							$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
							$kec['join']['table'] = 'ms_kecamatan';
							$kec['join']['key'] = 'kd_kec';
							$kec['join']['ref'] = 'kdkec_kel';
							$kec['condition']['kd_full'] = $datblock['idkel_blk'];
							$kecamatan = $this->crud_model->get_data($kec);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Risalah PTSL dengan kode ".$id);

							if($kecamatan['type_kel']==1){
								$desa = 'Desa';
							}else{
								$desa = 'Kelurahan';
							}

							$panitia['table'] = "tb_panitia";
							$panitia['type'] = "single";
							$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
							$datpanitia = $this->crud_model->get_data($panitia);

							$rent='';$aop='';
							foreach ($datanop as $dno) {
								$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
								$aop = $dno['aopsppt_dhkp'];
								$rent .= $nop.', ';
							}

							$user = $this->auth_model->get_userdata();

						    $pdf = new FPDF('p','mm',array(210,330));

						    $pdf -> AddPage();
						    $pdf -> setDisplayMode ('fullpage');
								$pdf -> setFont ('Times','B',12);

								$pdf -> rect(165, 10, 35, 10);
								$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
								if($kecamatan['type_kel']==1){
									$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Desa/Kecamatan");
								}else{
									$pdf -> setXY(12,$row); $pdf->Cell(0,0,"Kelurahan/Kecamatan");
								}

								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']." / ".$kecamatan['nma_kec']);

								// KOLOM 1
								$row +=4;
								$pdf -> rect(10, $row, 190, 73);
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
								if($datpdk['ttg_pdk']){
									$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
								}

								$row +=5.5;
								$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. Nomor Identitas");
								$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
								$row +=5.5;
								$pdf -> setXY(19,$row); $pdf->Cell(0,0,"d. Alamat");
								$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
								$row -=2;
								$pdf -> setXY(73,$row); $pdf->MultiCell(0,4,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
								$row +=10.5;
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
								$pdf -> setXY(73,$row); $pdf->Cell(0,0,$aop);
								$row +=5.5;
								$pdf -> setXY(19,$row); $pdf->Cell(0,0,"b. RT/RW");
								$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
								$row +=5.5;
								$pdf -> setXY(19,$row); $pdf->Cell(0,0,"c. No Bidang / NIB");
								$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(73,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
								$row +=5.5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2. Luas");
								$pdf -> setXY(69,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['luasfisik_ptsl']);
								$pdf -> setXY(80,$row); $pdf->Cell(0,0,"m");
								$pdf -> setFont ('Times','',8);
								$pdf -> setXY(83,$row-1.5); $pdf->Cell(0,0,"2");
								$pdf -> setFont ('Times','',10);
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
								$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Semarang , ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
								$row +=5.5;
								$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Petugas Pengumpul Data Yuridis");
								$pdf -> setXY(125,$row); $pdf->Cell(0,0,"Peserta Pendaftaran Tanah Sistematis");
								$row +=30;
								$pdf -> setXY(18,$row); $pdf->Cell(50,0,$datpanitia['bpn_pnt'],0,0,'C');
								$pdf -> setXY(125,$row); $pdf->Cell(50,0,$datpdk['nma_pdk'],0,0,'C');

								$row +=5;
								$pdf -> setXY(18,$row); $pdf->Cell(0,0,"NIP. ".$datpanitia['nipbpn_pnt']);

								$row +=20;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Keterangan :");
								$pdf->SetFont('Times','I',10);
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"*     centang jika ada");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"**    coret yang tidak diperlukan");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"***   uraikan secara singkat");


								$pdf -> AddPage();
						    $pdf -> Image("./assets/img/bpn.png",10,14,35);
						    $row = 18;
						    $pdf -> setFont ('Times','B',17);
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"KEMENTRIAN AGRARIA DAN TATA RUANG /",0,0,'C');

						    $row +=5.5;
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"BADAN PERTANAHAN NASIONAL",0,0,'C');
								$row +=5.5;
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"REPUBLIK INDONESIA",0,0,'C');
								$pdf -> setFont ('Times','B',15);
						    $row +=5.5;
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"KANTOR PERTANAHAN KABUPATEN Semarang",0,0,'C');

								$row +=5.5;
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"PROVINSI JAWA TENGAH",0,0,'C');

								$pdf -> setFont ('Times','',10);
						    $row +=5.5;
						    $pdf -> setXY(43,$row); $pdf->Cell(150,0,"Jalan Kesatrian no 1 Semarang - 54111 telp.: (0275) 321139 E-mail :kab-Semarang@bpn.go.id",0,0,'C');

								$row +=5.5;
								$pdf -> SetLineWidth(1.5);
								$pdf -> setXY(15,$row);
								$pdf->Cell(0,0,"",1,1,'C');
								$row +=2;
								$pdf -> SetLineWidth(0.5);
								$pdf -> setXY(15,$row);
								$pdf->Cell(0,0,"",1,1,'C');

								$pdf -> setFont ('Times','',11);
						    $row +=8;
						    $pdf -> setXY(12,$row); $pdf->Cell(185,0,"Semarang, .....................................",0,0,'R');
								$row +=5;
						    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nomor");
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
								$row +=5;
						    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Sifat");
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(37,$row); $pdf->Cell(0,0,"Penting");
								$row +=5;
						    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Lampiran");
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(37,$row); $pdf->Cell(0,0,"-");
								$row +=5;
						    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Hal");
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(37,$row-2); $pdf->MultiCell(110,5,"Pemberitahuan Penyelesaian Pendaftaran Tanah Sistematis Lengkap ( PTSL ) Tahun 2021",0,'L');
								$row +=15;
						    $pdf -> setXY(15,$row); $pdf->Cell(0,0,"Kepada Yth.");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama ".stripslashes($datpdk['nma_pdk']));
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Alamat ".$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Di -");
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten Semarang");

								$row +=8;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Sehubungan penyelesaian kegiatan Pendaftaran Tanah Sistematis Lengkap tahun 2021 di ".$desa);
								$row +=5;
								$pdf -> setXY(15,$row-3); $pdf->MultiCell(190,5,$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec'].", kami sampaikan bahwa pendaftaran bidang tanah Saudara dengan :");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nomor Berkas");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Fisik");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['noberkas_ptsl']);
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Yuridis");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['noberkasyrd_ptsl']);
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"NIB");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Letak Tanah");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Blok");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
								$pdf -> setXY(80,$row); $pdf->Cell(0,0,"NOP");
								$pdf -> setXY(90,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(92,$row); $pdf->Cell(0,0,$rent);
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Desa");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kel']);
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Kecamatan");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$kecamatan['nma_kec']);
								$row +=5;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"NUB");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(52,$row); $pdf->Cell(0,0,$data['nub_ptsl']);
								$row +=8;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Belum dapat diterbitkan Buku Tanah dan Sertipikat Hak Atas Tanah karena saudara belum memenuhi");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a. Surat Penyataan Penguasaan Fisik Bidang Tanah dan Surat pernyataan pemasangan tanda batas;");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b. Surat pernyataan terhutang BPHTB dan/atau PPh bagi obyek bidang tanah PTSL yang terkena PPh dan BPHTB;");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c. Dokumen bukti kepemilikan atas bidang tanah yang Saudara punyai;");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d. Surat Keterangan Kepala Desa.");
								$row +=8;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Dalam rangka menindaklanjuti penerbitan Buku Tanah dan Sertipikat Hak Atas Tanah, diminta Saudara");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"untuk segera memenuhi persyaratan sebagaimana tersebut diatas, melalui Panitia Desa setempat.");
								$row +=8;
								$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Apabila pemenuhan surat-surat sebagaimana tersebut diatas dilakukan setelah masa Program PTSL");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"selesai, maka pemenuhan syarat-syarat tersebut didaftarkan ke Kantor Pertanahan Kabupaten Semarang dengan");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"membayar Biaya Penerimaan Negara Bukan Pajak (PNBP) sesuai dengan peraturan perundang-undangan yang");
								$row +=5;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"berlaku.");
								$row +=8;
								$pdf -> setXY(45,$row); $pdf->Cell(0,0,"Demikian pemberitahuan ini untuk menjadi maklum.");
								$row +=8;
								$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Semarang, ...........................");
								$row +=5;
								$pdf -> setXY(110,$row); $pdf->Cell(90,0,"Ketua Panitia Ajudikai PTSL Tim ".$datpanitia['tim_pnt']."",0,0,'C');
								$row +=5;
								$pdf -> setXY(110,$row); $pdf->Cell(90,0,"Kabupaten Semarang",0,0,'C');
								$row +=25;
								$pdf -> setXY(110,$row); $pdf->Cell(90,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
								$row +=5;
								$pdf -> setXY(110,$row); $pdf->Cell(90,0,"NIP : ".$datpanitia['nipketua_pnt'],0,0,'C');
								$row +=15;
								$pdf -> setFont ('Helvetica','I',12);
								$pdf -> setXY(10,$row); $pdf->Cell(190,0,"Melayani, Profesional, Terpercaya",0,0,'C');

								// SURAT 2
								$pdf -> AddPage();
								$pdf -> rect(165, 10, 35, 10);
								$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
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
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,$agama);

								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia / Tgl Lahir");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								if($datpdk['ttg_pdk']){
									$diff = date_diff(date_create($datpdk['ttg_pdk']), date_create(date('Y-m-d')));
									$pdf -> setXY(62,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
								}


								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$row -=2;
								$pdf -> setXY(62,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);

								$row +=10;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan dengan sesungguhnya serta dengan itikad baik bahwa saya menguasai/memiliki");
								$row +=6;
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"sebidang tanah yang terletak di :");
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan / Blok");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,$datblock['nama_blk']);
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa / Kelurahan");
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
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idguna_ptsl'],'guna'));
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pemanfaatan Tanah");
								$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(62,$row); $pdf->Cell(0,0,status($data['idmanfaat_ptsl'],'manfaat'));
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
								$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Bahwa bidang tanah tersebut saya kuasai/miliki sejak tahun ".$thnptsl." yang sampai saat ini terhadap");
								$row +=6;
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
								$pdf -> setXY(25,$row); $pdf->Cell(0,0,"atau bea terhutang bagi saya yang wajib dan akan saya lunasi dengan nilai NJOP Rp. ......................");
								$row +=7;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7.");
								if($data['idkperluan_ptsl']==1){
									$keperluan="Jual Beli";
								}else if($data['idkperluan_ptsl']==2){
									$keperluan="Waris";
								}else if($data['idkperluan_ptsl']==3){
									$keperluan="Hibah";
								}else if($data['idkperluan_ptsl']==4){
									$keperluan="Wakaf";
								}else if($data['idkperluan_ptsl']==5){
									$keperluan="Tukar Menukar";
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
								$row +=6;
								$pdf -> setXY(27,$row); $pdf->Cell(0,0,"betul-betul yang saya punyai dan apabila terdapat bukti pemilikan/penguasaan");
								$row +=6;
								$pdf -> setXY(27,$row); $pdf->Cell(0,0,"atas tanah dimaksud setelah dibuatnya pernyataan ini dan/atau telah diterbitkan");
								$row +=6;
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
								$pdf -> setXY(48,$row); $pdf->Cell(0,0,stripslashes($datsaksi1['nma_pdk']));
								$pdf->SetFont('Times','',12);

								$pdf -> setXY(105,$row); $pdf->Cell(0,0,"2. ");
								$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Nama");
								$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
								$pdf->SetFont('Times','B',12);
								$pdf -> setXY(138,$row); $pdf->Cell(0,0,stripslashes($datsaksi2['nma_pdk']));
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
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia");
								$pdf -> setXY(45,$row); $pdf->Cell(0,0,":");

								$from = new DateTime($datsaksi1['ttg_pdk']);
								$to   = new DateTime($datsaksi['tgl_spt']);
								$pdf -> setXY(48,$row); $pdf->Cell(0,0,$from->diff($to)->y);

								$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Usia");
								$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
								$from = new DateTime($datsaksi2['ttg_pdk']);
								$to   = new DateTime($datsaksi['tgl_spt']);
								$pdf -> setXY(138,$row); $pdf->Cell(0,0,$from->diff($to)->y);

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
								$row -=3;
								$pdf -> setXY(48,$row); $pdf->MultiCell(50,5,$datsaksi1['almat_pdk'].' RT.'.$datsaksi1['rt_pdk'].' RW.'.$datsaksi1['rw_pdk'].' '.$datsaksi1['kel_pdk'].' Kec.'.$datsaksi1['kec_pdk'].' Kab.'.$datsaksi1['kab_pdk']);
								$row +=3;
								$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Alamat");
								$pdf -> setXY(135,$row); $pdf->Cell(0,0,":");
								$row -=2;
								$pdf -> setXY(138,$row); $pdf->MultiCell(0,5,$datsaksi2['almat_pdk'].' RT.'.$datsaksi2['rt_pdk'].' RW.'.$datsaksi2['rw_pdk'].' '.$datsaksi2['kel_pdk'].' Kec.'.$datsaksi2['kec_pdk'].' Kab.'.$datsaksi2['kab_pdk']);

								$row +=23;
								$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Dibuat di  : Semarang");
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SAKSI-SAKSI");
								$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Tanggal   : ".fdate($datsaksi['tgl_spt'],'DDMMYYYY'));
								$row +=8;
								$pdf -> setXY(120,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan");

								$row +=15;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
								$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi1['nma_pdk'])."    )",0,0,'L');
								$pdf->SetFont('Times','I',10);
								$pdf -> setXY(120,$row); $pdf->Cell(50,0,"Materai");
								$pdf->SetFont('Times','',12);
								$row +=30;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
								$pdf -> setXY(25,$row); $pdf->Cell(50,0,"(    ".stripslashes($datsaksi2['nma_pdk'])."    )",0,0,'L');
								$pdf -> setXY(125,$row-15); $pdf->Cell(50,0,"(    ".stripslashes($datpdk['nma_pdk'])."    )",0,0,'C');
								$row +=20;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'C');
								$row +=5;
								if($kecamatan['type_kel']==1){
									$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
								}else{
									$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Lurah ".$kecamatan['nma_kel'],0,0,'C');
								}
								$row +=25;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"(    ".stripslashes($datsaksi['kades_spt'])."    )",0,0,'C');

								// SURAT 4
								$pdf -> AddPage();
								$pdf -> rect(165, 10, 35, 10);
								$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
								$row =10;

								$row +=14;
								$pdf->SetFont('Times','B',14);
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS",0,0,'C');
								$row +=10;
								$pdf->SetFont('Times','',12);
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
								$row +=5;
								$pdf->SetFont('Times','',11);
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf->SetFont('Times','B',11);
								$pdf -> setXY(53,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
								$pdf->SetFont('Times','',11);
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(53,$row); $pdf->Cell(0,0,$agama);
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia/ Tgl Lahir");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								if($datpdk['ttg_pdk']){
									$pdf -> setXY(53,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
								}

								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
								$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
								$row -=2;
								$pdf -> setXY(53,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
								$row +=10;
								$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Selaku pemilik tanah/pemohon pengukuran tanah bekas adat/yasan C Desa No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." seluas ".$data['dluas_ptsl']." m2, tanah ".status($data['idguna_ptsl'],'guna')." dimanfaatkan untuk ".status($data['idmanfaat_ptsl'],'manfaat')." terletak di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang.");
								$row +=16;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini menyatakan sebenar-benarnya :");
								$row +=5;
								$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
								$row -=2.5;
								$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa tanah yang kami mohonkan pengukuran di Kantor Pertanahan Kabupaten Semarang berdasarkan alas hak tersebut diatas tidak dalam jaminan sesuatu hutang, tidak diletakkan sita jaminan dan telah kami pasang tanda-tanda batasnya sesuai ketentuan yang berlaku, berupa Patok Beton.");
								$row +=18;
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
								$row +=13;
								$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Semarang ,".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
								$row +=5;
								$pdf->SetFont('Times','B',11);
								$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Yang Menyatakan",0,0,'C');
								$row +=5;
								$pdf->SetFont('Times','',11);
								$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Pemilik Tanah yang berbatasan,");
								$row +=10;
								$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Utara");
								$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
								$pdf -> setFont ('Times','',9);
								$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['utara_ptsl']);
								$pdf -> setFont ('Times','',11);
								$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
								$row +=10;
								$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Timur");
								$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
								$pdf -> setFont ('Times','',9);
								$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['timur_ptsl']);
								$pdf -> setFont ('Times','',11);
								$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
								$pdf->SetFont('Times','I',10);
								$pdf -> setXY(125,$row-13); $pdf->Cell(0,5,"materai");
								$pdf->SetFont('Times','',11);
								$row +=10;
								$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Selatan");
								$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
								$pdf -> setFont ('Times','',9);
								$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['selatan_ptsl']);
								$pdf -> setFont ('Times','',11);
								$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
								$row +=10;
								$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Barat");
								$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
								$pdf -> setFont ('Times','',9);
								$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['barat_ptsl']);
								$pdf -> setFont ('Times','',11);
								$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
								$pdf -> setXY(120,$row-20); $pdf->Cell(0,5,stripslashes($datpdk['nma_pdk']),0,0,'C');
								$row +=10;
								$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Mengetahui",0,0,'C');
								$row +=4;
								if($kecamatan['type_kel']==1){
									$desa = 'Kepala Desa';
								}else{
									$desa = 'Lurah';
								}
								$pdf -> setXY(0,$row); $pdf->Cell(0,5,$desa." ".$kecamatan['nma_kel'],0,0,'C');
								$row +=25;
								$pdf -> setXY(0,$row); $pdf->Cell(0,5,stripslashes($datsaksi['kades_spt']),0,0,'C');


								$pdf -> addpage();
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
								$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan/Blok : ".$aop." / Blok ".$datblock['nama_blk']);
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
								if($datpdk['ttg_pdk']){
									$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
								}

								$row +=4;
								$pdf -> rect(20, $row, 10,8);
								$pdf -> rect(30, $row, 160,8);
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
								$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
								$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
								$row +=4;
								$pdf -> rect(20, $row, 10,11);
								$pdf -> rect(30, $row, 160,11);
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
								$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
								$row -=3;
								$pdf -> setXY(75,$row); $pdf->MultiCell(100,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);
								$row +=10;
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
								$pdf -> setXY(78,$row); $pdf->Cell(0,0,'');//$data['ddari_ptsl']
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
								$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");
								$row +=4;
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
								$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Tahun/tanggal");
								$row +=4;
								$pdf -> setXY(35,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']." m2");
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
								$pdf -> setXY(111,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");
								$row +=4;
								$rent='';
								foreach ($datanop as $dno) {
									$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
									$rent .= $nop.', ';
								}
								$pdf -> setXY(35,$row-2); $pdf->MultiCell(75,4,$rent);
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
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ............................................................. diperoleh dengan cara ..............");
								$row +=3;
								$pdf -> rect(20, $row, 10,8);
								$pdf -> rect(30, $row, 160,8);
								$row +=3;
								$pdf -> rect(82, $row, 3,3);
								$pdf -> rect(110, $row, 3,3);
								$row +=1;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Pertanian :       Non Pertanian : ");
								$row +=4;
								$pdf -> rect(20, $row, 10,18);
								$pdf -> rect(30, $row, 160,18);
								$row +=2;
								$pdf -> rect(80, $row, 3,3);
								$pdf -> rect(112, $row, 3,3);
								$pdf -> rect(158, $row, 3,3);
								$pdf -> rect(185, $row, 3,3);
								$row +=1;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"d. Pemanfaatan tanah : Sawah :          Ladang/Tegalan:          Kebun/Kebun Campuran :          Kolam ikan : ");
								$row +=3;
								$pdf -> rect(84, $row, 3,3);
								$pdf -> rect(103, $row, 3,3);
								$pdf -> rect(130, $row, 3,3);
								$pdf -> rect(175, $row, 3,3);
								$row +=1;
								$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Perumahan :      Industri :           Perkebunan :             Dikelola Pengembang :");
								$row +=3;
								$pdf -> rect(95, $row, 3,3);
								$pdf -> rect(135, $row, 3,3);
								$pdf -> rect(155, $row, 3,3);
								$row +=1;
								$pdf -> setXY(64,$row); $pdf->Cell(0,0,"Lapangan Umum :           Pengembalaan Ternak :         Jalan : ");
								$row +=3;
								$pdf -> rect(95, $row, 3,3);
								$row +=1;
								$pdf -> setXY(64,$row); $pdf->Cell(0,0,"tidak dimanfaatkan :          Lain-lain : ....................... (sebutkan)");
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
								$pdf -> Line(20, $row+2, 190, $row-15);
								$pdf -> SetLineWidth(0.1);
								$pdf -> setXY(112,$row); $pdf->Cell(0,0,"Lain-lain sebutkan :");

								$pdf -> AddPage();
						    $row = 10;
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
								$row +=3;
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
								$pdf -> Line(20, $row+20, 190, $row-60);
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
								// if($datpanitia['bpn_pnt']!="" && $datpanitia['babinsa_pnt']!=""){
										$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
										// $pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
								// }else if($datpanitia['bpn_pnt']!=""){
								// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
								// }else if($datpanitia['babinsa_pnt']!=""){
								// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
								// }else{
								// 	$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
								// }

								$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".stripslashes($datpdk['nma_pdk'])." )",0,0,'C');
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
								$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".stripslashes($datpdk['nma_pdk']));
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
								$pdf -> setFont ('Times','',10);
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
								$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA : V    Gogol Tetap :       Pekulen :        Andarbeni:");
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
								$pdf -> Line(171, $row+3, 187, $row+3);
								$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu                                                                     dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
								$pdf -> setXY(100,$row); $pdf->MultiCell(145,5,$datpdk['nma_pdk']);
								$row+=11;
								$pdf -> rect(20, $row, 10,8);
								$pdf -> rect(30, $row, 160,8);
								$row+=2;
								$pdf -> rect(98, $row, 3,3);
								$pdf -> rect(130, $row, 3,3);
								$row-=1;
								$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
								$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :  V");
								$row+=7;
								$pdf -> rect(20, $row, 10,8);
								$pdf -> rect(30, $row, 160,8);
								$row+=2;
								$pdf -> rect(86, $row, 3,3);
								$pdf -> rect(115, $row, 3,3);
								$pdf -> rect(138, $row, 3,3);
								$row-=1;
								$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
								$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada : ");
								$row+=7;
								$pdf -> rect(20, $row, 170,8);
								$row+=2;
								$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");
								$row+=6;
								$pdf -> rect(20, $row, 170,8);
								$row+=2;
								$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
								$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ".$kecamatan['kd_full'].''.$data['nib_ptsl']);
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
								$pdf -> rect(20, $row, 55,27);
								$pdf -> rect(75, $row, 55,27);
								$pdf -> rect(130, $row, 60,27);
								$row+=3;
								$pdf -> setXY(20,$row); $pdf->Cell(55,0,"KETUA PANITIA AJUDIKASI",0,0,'C');
								$pdf -> setXY(75,$row); $pdf->Cell(55,0,"WAKIL KETUA BIDANG FISIK",0,0,'C');
								$pdf -> setXY(130,$row); $pdf->Cell(60,0,"WAKIL KETUA BIDANG YURIDIS",0,0,'C');
								$row+=20;
								$pdf -> setXY(20,$row); $pdf->Cell(55,0,"( ".$datpanitia['ketua_pnt']." )",0,0,'C');
								$pdf -> setXY(75,$row); $pdf->Cell(55,0,"( ".$datpanitia['wakafis_pnt']." )",0,0,'C');
								$pdf -> setXY(130,$row); $pdf->Cell(60,0,"( ".$datpanitia['wakayur_pnt']." )",0,0,'C');
								$row+=4;
								$pdf -> rect(20, $row, 85,30);
								$pdf -> rect(105, $row, 85,30);
								$row+=3;
								if($kecamatan['type_kel']==1){
									$desa = 'Kepala Desa';
								}else{
									$desa = 'Lurah';
								}
								$pdf -> setXY(20,$row); $pdf->Cell(85,0,$desa.' '.$kecamatan['nma_kel'],0,0,'C');
								$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');
								$row+=18;
								$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".stripslashes($datsaksi['kades_spt'])." )",0,0,'C');
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
								$pdf -> SetLineWidth(0.5);
								$pdf -> Line(20, $row-28, 190, $row-28);
								$pdf -> Line(20, $row+36, 190, $row-28);
								$pdf -> Line(20, $row+36, 190, $row+36);
								$pdf -> SetLineWidth(0.1);
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
								$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".stripslashes($datpdk['nma_pdk']));
								$row +=4;
								$pdf -> rect(20, $row, 10,8);
								$pdf -> rect(30, $row, 160,8);
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"2.");
								$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Status Tanah : Tanah Milik/Tanah Negara");
								$row +=4;
								$pdf -> rect(20, $row, 10,32);
								$pdf -> rect(30, $row, 160,32);
								$row +=4;
								$pdf -> setXY(32,$row); $pdf->Cell(0,0,"3.");
								$pdf -> setXY(38,$row); $pdf->Cell(0,0,"Pertimbangan dalam hal status :");
								$pdf -> setXY(90,$row); $pdf->Cell(0,0,"a.");
								$row -=2;
								// $pdf -> Line(128, $row+18, 140, $row+18);
								// $pdf -> Line(155, $row+18, 170, $row+18);
								$pdf -> SetLineWidth(0.5);
								$pdf -> Line(100, $row+18, 185, $row+18);
								$pdf -> Line(100, $row+52, 185, $row+18);
								$pdf -> Line(100, $row+52, 150, $row+52);
								$pdf -> SetLineWidth(0.1);
								$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".stripslashes($datpdk['nma_pdk'])." K1)");
								$row +=30;
								$pdf -> rect(20, $row, 10,12);
								$pdf -> rect(30, $row, 160,12);
								$row +=4;
								$pdf -> setXY(90,$row); $pdf->Cell(0,0,"b.");
								$row -=2;
								$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP ..................... (K1)");
								$row +=10;
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
								$pdf -> setFont('Times','',25);
								$pdf -> setXY(88,$row); $pdf->Cell(0,0,"O");
								$pdf -> setFont('Times','',10);
								$pdf -> setXY(90,$row); $pdf->Cell(0,0,"d.");
								$row -=2;
								$pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3.1, K3.2)");
								$row +=10;
								// $pdf -> rect(20, $row, 10,12);
								// $pdf -> rect(30, $row, 160,12);
								// $row +=4;
								// $pdf -> setXY(90,$row); $pdf->Cell(0,0,"e.");
								// $row -=2;
								// $pdf -> setXY(98,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
								// $row +=10;
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
								$tglpanitia = '';
								if($datpanitia){
									$tglpanitia = fdate($datpanitia['tgl_pnt'],'DDMMYYYY');
								}
								$pdf -> setXY(23,$row); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor 6 Tahun 2018 tentang Pendaftaran Tanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".$tglpanitia." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");
								$row +=34;
								// $pdf -> setFont('Times','',18);
								// $pdf -> setXY(22,$row); $pdf->Cell(0,0,"O");
								$pdf -> setFont('Times','',10);
								$pdf -> setXY(23,$row); $pdf->Cell(0,0,"1.");
								$row -=3;
								// $pdf -> Line(173, $row+8, 185, $row+8);
								// $pdf -> Line(38, $row+13, 107, $row+13);
								// $pdf -> Line(43, $row+21, 53, $row+21);
								// $pdf -> Line(34, $row+26, 52, $row+26);
								// $pdf -> Line(87, $row+21, 185, $row+21);
								$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ................................ Nomor .................................. (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak ".stripslashes($datpdk['nma_pdk']));
								$row +=18;
								$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");
								$row +=14;
								// $pdf -> SetLineWidth(0.5);
								// $pdf -> Line(20, $row, 190, $row);
								// $pdf -> Line(20, $row+40, 190, $row);
								// $pdf -> Line(20, $row+40, 190, $row+40);
								// $pdf -> SetLineWidth(0.1);
								$pdf -> setXY(23,$row); $pdf->Cell(0,0,"2.");
								$row -=3;
								$pdf -> setXY(33,$row); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan ".status($data['hak_ptsl'],'hak')." (K1)");
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

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				  require(APPPATH .'plugins/fpdf/fpdf.php');

					$ptsl['type']                   = "single";
					$ptsl['table']                  = "tb_ptsl";
					$ptsl['condition']['id_ptsl'] = $id;
					$data                        = $this->crud_model->get_data($ptsl);

					$dnop['type']                   = "multiple";
					$dnop['table']                  = "tb_ptsldhkp";
					$dnop['join']['table'] 				 = "tb_dhkp";
					$dnop['join']['key'] 					 = "id_dhkp";
					$dnop['join']['ref'] 					 = "iddhkp_ptsl";
					$dnop['condition']['idptsl_ptsl'] = $id;
					$datanop                        = $this->crud_model->get_data($dnop);

					$block['table'] = "tb_block";
					$block['type'] = "single";
					$block['condition']['idblk_blk'] = $idblock;
					$datblock = $this->crud_model->get_data($block);

					$rent='';$aop='';
					foreach ($datanop as $dno) {
						$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
						$aop = $dno['aopsppt_dhkp'];
						$rent .= $nop.', ';
					}

					$pdk['table'] = "tb_penduduk";
					$pdk['type'] = "single";
					$pdk['condition']['idpdk_pdk'] = $data['idpdk_ptsl'];
					$pdk['join']['table'] = "tb_pekerjaan";
					$pdk['join']['key'] = "idpkr_pkr";
					$pdk['join']['ref'] = "idpeker_pdk";
					$datpdk = $this->crud_model->get_data($pdk);

					$saksi['table'] = "tb_saksiptsl";
					$saksi['type'] = "single";
					$saksi['condition']['idblk_spt'] = $datblock['idblk_blk'];
					$datsaksi = $this->crud_model->get_data($saksi);

					$kec['table'] = "ms_kelurahan";
					$kec['type'] = "single";
					$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
					$kec['join']['table'] = 'ms_kecamatan';
					$kec['join']['key'] = 'kd_kec';
					$kec['join']['ref'] = 'kdkec_kel';
					$kec['condition']['kd_full'] = $datblock['idkel_blk'];
					$kecamatan = $this->crud_model->get_data($kec);

					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$kecamatan['nma_kel'].'-'.$id,"Export Risalah A3 PTSL dengan kode ".$id);

					$panitia['table'] = "tb_panitia";
					$panitia['type'] = "single";
					$panitia['condition']['idkel_pnt'] = $kecamatan['kd_full'];
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

					if($kecamatan['type_kel']==1){
						$desa = 'Kepala Desa';
					}else{
						$desa = 'Lurah';
					}

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
					$pdf -> setXY(105,$row); $pdf->Cell(100,0,$kecamatan['kd_full'].''.$data['nib_ptsl'],0,0,'L');


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
					$pdf -> setXY(103,$row); $pdf->Cell(0,0,$kecamatan['kd_full'].''.$data['nib_ptsl']);

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
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Jalan / Blok : ".$aop." / Blok ".$datblock['nama_blk']);

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
					if($datpdk['ttg_pdk']){
						$pdf -> setXY(142,$row); $pdf->Cell(0,0,"Tgl. Lahir :".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					}


					$row +=3;
					$pdf -> rect(20, $row, 10,6);
					$pdf -> rect(30, $row, 160,6);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);

					$row +=3;
					$pdf -> rect(20, $row, 10,10);
					$pdf -> rect(30, $row, 160,10);

					$row +=3;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Tempat tinggal");
					$pdf -> setXY(72,$row); $pdf->Cell(0,0,":");
					$row -=3;
					$pdf -> setXY(75,$row); $pdf->MultiCell(110,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk'].' Kab.'.$datpdk['kab_pdk']);

					$row +=10;
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
					$pdf -> setXY(78,$row); $pdf->Cell(0,0,'');

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
					$tglpanitia = '';
					if($datpanitia){
						$tglpanitia = fdate($datpanitia['tgl_pnt'],'DDMMYYYY');
					}
					$pdf -> setXY(223,$row_b); $pdf->MultiCell(165,5,"Mengingat Instruksi Presiden Nomor 2 Tahun 2018 tentang Percepatan Pendaftaran Tanah Sistematis Lengkap jo. Peraturan Menteri Agraria dan Tata Ruang/kepala Badan Pertanahan Nasional Nomor 6 Tahun 2018 tentang PendaftaranTanah Sistematis Lengkap dan Surat keputusan Kepala Kantor Pertanahan Kabupaten Semarang tanggal ".$tglpanitia." Nomor. ".$datpanitia['no_pnt']." tentang Penetapan Lokasi PTSL di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." , Kabupaten Semarang serta memperhatikan kesimpulan Panitia Ajudikasi PTSL yang tercantum dalam RISALAH PENELITIAN DATA YURIDIS, maka :");


					$row_b += 33;
					// $pdf -> setFont('Times','',15);
					// $pdf -> setXY(222,$row_b); $pdf->Cell(0,0,"O");
					$pdf -> setFont('Times','',8);
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"1.");

					$row_b -= 3;
					// $pdf -> Line(328, $row_b+7.7, 375, $row_b+7.7);
					// $pdf -> Line(275, $row_b+17, 370, $row_b+17);
					// $pdf -> Line(240.8, $row_b+17, 249, $row_b+17);

					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara pengesahan pengumuman data fisik dan data yuridis tanggal ......................... Nomor ........................... (D.I. 202), Hak atas tanah ini ditegaskan konversinya menjadi Hak Milik / diakui sebagai Hak Milik dengan pemegang hak ".stripslashes($datpdk['nma_pdk']));
					$row_b += 14;
					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"tanpa/dengan catatan ada keberatan (tidak ada keberatan/sedang diproses di Pengadilan dengan / tanpa sita jaminan (K1)");

					$row_b += 10;
					$pdf -> setXY(223,$row_b); $pdf->Cell(0,0,"2.");

					// $pdf -> SetLineWidth(0.5);
					// $pdf -> Line(220, $row_b, 388, $row_b);
					// $pdf -> Line(220, $row_b+32, 388, $row_b);
					// $pdf -> Line(220, $row_b+32, 388, $row_b+32);
					// $pdf -> SetLineWidth(0.1);


					$row_b -=3;
					$pdf -> setXY(233,$row_b); $pdf->MultiCell(155,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan Pengumuman data fisik dan data yuridis tanggal ................ Nomor .....(D.I.202), bidang tanah ini statusnya adalah TANAH NEGARA. Kepada yang menempati/menguasai, nama .................. dapat / tidak dapat diusulkan untuk diberikan ".status($data['hak_ptsl'],'hak')." (K1)");

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
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan :");

					$row +=3;
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"Pajak Hasil Bumi");
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Tahun/tanggal");

					$row +=3;
					$pdf -> setXY(235,$row); $pdf->Cell(0,0,"C.No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." Luas ".$data['dluas_ptsl']." m2");

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
					$pdf -> setXY(311,$row); $pdf->Cell(0,0,"Kantor yang menerbitkan : BPPKAD Kab. Semarang");

					$row +=4;
					$rent='';
					foreach ($datanop as $dno) {
						$nop = createkodebpkad($datblock['idkel_blk']).''.$datblock['nama_blk'].''.$dno['nosppt_dhkp'];
						$rent .= $nop.', ';
					}
					$pdf -> setXY(235,$row-3); $pdf->MultiCell(55,3,$rent);
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
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"b. Berikut pada tahun ........... Oleh ..................................................................... diperoleh dengan cara .............");

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ..................................................................... diperoleh dengan cara .............");

					$row +=3;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"    Berikut pada tahun ........... Oleh ..................................................................... diperoleh dengan cara .............");

					$row +=4;

					$pdf -> rect(220, $row, 10,7);
					$pdf -> rect(230, $row, 160,7);
					$row +=3;
					$pdf -> rect(271, $row, 3,3);
					$pdf -> rect(295, $row, 3,3);
					$row +=1;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"c. Penggunaan tanah : Pertanian :       Non Pertanian : ");
					$row +=3;
					$pdf -> rect(220, $row, 10,23);
					$pdf -> rect(230, $row, 160,23);
					$row +=5;
					$pdf -> rect(268, $row, 3,3);
					$pdf -> rect(297, $row, 3,3);
					$pdf -> rect(333, $row, 3,3);
					$pdf -> rect(354, $row, 3,3);
					$pdf -> rect(373, $row, 3,3);

					$row +=1;
					$pdf -> setXY(232,$row); $pdf->Cell(0,0,"d. Pemanfaatan tanah : Sawah :          Ladang/Tegalan:          Kebun/Kebun Campuran :          Kolam ikan :        Perumahan :       ");

					$row +=4;
					$pdf -> rect(270, $row, 3,3);
					$pdf -> rect(292, $row, 3,3);
					$pdf -> rect(328, $row, 3,3);

					$row +=1;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Industri :          Perkebunan :             Dikelola Pengembang :");

					$row +=4;
					$pdf -> rect(280, $row, 3,3);
					$pdf -> rect(315, $row, 3,3);
					$pdf -> rect(330, $row, 3,3);

					$row +=1;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Lapangan Umum :          Pengembalaan Ternak :           Jalan :    ");

					$row +=3;
					$pdf -> rect(285, $row, 3,3);
					$row +=1;
					$pdf -> setXY(257,$row); $pdf->Cell(0,0,"Tidak Dimanfaatkan :              Lain-lain : ....................... (sebutkan)");

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
					// if($datpanitia['bpn_pnt']!="" && $datpanitia['babinsa_pnt']!=""){
							$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
					// 		$pdf -> setXY(70,$row); $pdf->Cell(40,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
					// }else if($datpanitia['bpn_pnt']!=""){
					// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['bpn_pnt']." )",0,0,'C');
					// }else if($datpanitia['babinsa_pnt']!=""){
					// 		$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
					// }else{
					// 	$pdf -> setXY(32,$row); $pdf->Cell(80,0,"( ".$datpanitia['babinsa_pnt']." )",0,0,'C');
					// }
					$pdf -> setXY(112,$row); $pdf->Cell(80,0,"( ".stripslashes($datpdk['nma_pdk'])." )",0,0,'C');

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
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pemiliknya/yang menguasai tanah adalah : ".stripslashes($datpdk['nma_pdk']));

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
					$pdf -> rect(85, $row, 3,3);
					$pdf -> rect(105, $row, 3,3);
					$pdf -> rect(120, $row, 3,3);
					$pdf -> rect(140, $row, 3,3);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(42,$row); $pdf->Cell(0,0,"Bekas tanah adat perorangan : HMA :V   Gogol Tetap :       Pekulen :        Andarbeni:");

					$row+=4;
					$pdf -> rect(20, $row, 10,12);
					$pdf -> rect(30, $row, 10,12);
					$pdf -> rect(40, $row, 150,12);

					$row+=3;
					$pdf -> rect(98, $row, 3,3);
					$pdf -> rect(113, $row, 3,3);
					$pdf -> rect(160, $row, 3,3);
					$pdf -> rect(182, $row, 3,3);

					$row+=1;
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"c.");

					$row+=4;
					$pdf -> rect(65, $row, 3,3);

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
					$pdf -> Line(155, $row+3, 167, $row+3);
					$pdf -> setXY(42,$row); $pdf->MultiCell(145,5,"kepada yang memiliki/menguasai, yaitu                                                                        dapat/tidak dapat diusulkan untuk diberikan Hak Milik/HGB/HP");
					$pdf -> setXY(88,$row); $pdf->MultiCell(145,5,$datpdk['nma_pdk']);

					$row+=11;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> rect(84, $row, 3,3);
					$pdf -> rect(110, $row, 3,3);

					$row-=1;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"3.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Pembebanan atas tanah : Sedang diagunkan :      Tidak diagunkan :  V");

					$row+=7;
					$pdf -> rect(20, $row, 10,8);
					$pdf -> rect(30, $row, 160,8);

					$row+=2;
					$pdf -> rect(75, $row, 3,3);
					$pdf -> rect(98, $row, 3,3);
					$pdf -> rect(117, $row, 3,3);

					$row-=1;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"4.");
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"Alat bukti yang diajukan : Lengkap :       Tidak Lengkap :       Tidak ada : ");

					$row+=7;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(22,$row); $pdf->Cell(165,5,"Demikian kesimpulan risalah penelitian data yuridis bidang tanah dengan :");

					$row+=6;
					$pdf -> rect(20, $row, 170,8);

					$row+=2;
					$pdf -> setXY(32,$row); $pdf->Cell(165,5,"NIB");
					$pdf -> setXY(52,$row); $pdf->Cell(165,5,": ".$kecamatan['kd_full'].''.$data['nib_ptsl']);

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

					$pdf -> setXY(20,$row); $pdf->Cell(85,0,$desa.' '.$kecamatan['nma_kel'],0,0,'C');
					$pdf -> setXY(105,$row); $pdf->Cell(85,0,"SEKRETARIS",0,0,'C');

					$row+=23;
					$pdf -> setXY(20,$row); $pdf->Cell(85,0,"( ".stripslashes($datsaksi['kades_spt'])." )",0,0,'C');
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
					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(221, $row-28, 389, $row-28);
					$pdf -> Line(221, $row+36, 389, $row-28);
					$pdf -> Line(221, $row+36, 389, $row+36);
					$pdf -> SetLineWidth(0.1);

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
					$pdf -> setXY(238,$row); $pdf->Cell(0,0,"Nama Pemilik / yang berkepentingan : ".stripslashes($datpdk['nma_pdk']));

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
					// $pdf -> Line(350, $row+13, 360, $row+13);
					// $pdf -> Line(372, $row+13, 383, $row+13);
					$pdf -> SetLineWidth(0.5);
					$pdf -> Line(325, $row+13, 385, $row+13);
					$pdf -> Line(300, $row+48, 385, $row+13);
					$pdf -> Line(300, $row+48, 330, $row+48);
					$pdf -> SetLineWidth(0.1);
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Berdasarkan data fisik dan data yuridis yang disahkan dengan Berita Acara Pengesahan pengumuman data fisik dan data yuridis tanggal ................... Nomor .................... hak atas tanah ini ditegaskan/diakui konversinya menjadi Hak Milik dengan Pemegang haknya ".stripslashes($datpdk['nma_pdk'])." K1)");

					$row +=26;
					$pdf -> rect(220, $row, 10,12);
					$pdf -> rect(230, $row, 160,12);

					$row +=4;
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"b.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Diproses melalui pemberian hak berupa HM/HGB/HP..............(K1)");

					$row +=10;
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
					$pdf -> setFont('Times','',25);
					$pdf -> setXY(288,$row); $pdf->Cell(0,0,"O");
					$pdf -> setFont('Times','',10);
					$pdf -> setXY(290,$row); $pdf->Cell(0,0,"d.");

					$row -=2;
					$pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Tanah belum dapat dibukukan dan diterbitkan sertipikat sehingga dibuat dalam daftar tanah (K3.1,K3.2)");

					$row +=10;
					// $pdf -> rect(220, $row, 10,12);
					// $pdf -> rect(230, $row, 160,12);
					//
					// $row +=4;
					// $pdf -> setXY(290,$row); $pdf->Cell(0,0,"e.");
					//
					// $row -=2;
					// $pdf -> setXY(298,$row); $pdf->MultiCell(88,5,"Dilakukan peningkatan kualitas data/Bidang tanah sudah terpetakan (K4)");
					//
					// $row +=10;
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
