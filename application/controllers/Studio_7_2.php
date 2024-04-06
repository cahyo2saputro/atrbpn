<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_7_2 extends CI_Controller
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

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			// SEARCHING
			$this->content['data']['param'] = array(array('NUB','nub_ptsl'),array('Nama','nma_pdk'),array('No. KTP','noktp_pdk'),array('NOP','nosppt_dhkp'),array('No Berkas Fisik','noberkas_ptsl'),array('No Berkas Yuridis','noberkasyrd_ptsl'),array('NIB','nib_ptsl'));

			$this->content['data']['title'] = "e-Yuridis : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Belum Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-Yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","studio_7_2/data/?search=".$cari));

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
					}else if($this->input->get('param')=='nib_ptsl'){
						$cek = substr($this->input->get('nilai'),8,5);
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

			$config['base_url'] = base_url().'Studio_7_2/data/';
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
			$dat['column'] = "tb_ptsl.nib_ptsl,tb_ptsl.noberkas_ptsl,tb_ptsl.noberkasyrd_ptsl,tb_ptsl.klaster_ptsl,tb_ptsl.nub_ptsl,tb_ptsl.luasfisik_ptsl,tb_ptsl.id_ptsl,tb_dhkp.nosppt_dhkp,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_ptsl.idblk_ptsl,
								(select count(id_pbk) from tb_ptslberkas where berkas_pbk like '%%.pdf' and idptsl_pbk=id_ptsl) as pdf,
								(select count(id_pbk) from tb_ptslberkas where berkas_pbk not like '%%.pdf' and idptsl_pbk=id_ptsl) as gambar,
								";
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
				}else if($this->input->get('param')=='nib_ptsl'){
					$cek = substr($this->input->get('nilai'),8,5);
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
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

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
						'idusr_pdk' => $user['idusr_usr'],
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
					$insert_id = $ktp['idpdk_pdk'];
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
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
					'utara_ptsl' => $this->input->post('utara'),
					'timur_ptsl' => $this->input->post('timur'),
					'selatan_ptsl' => $this->input->post('selatan'),
					'barat_ptsl' => $this->input->post('barat'),
					'desc0_ptsl' => $this->input->post('des0'),
					'desc1_ptsl' => $this->input->post('des1'),
					'idkperluan_ptsl' => $this->input->post('dkeperluan'),
					'thn_ptsl' => $this->input->post('dtahun'),
					'atasnama_ptsl' => $this->input->post('dnama'),
					'desc2_ptsl' => $this->input->post('des2'),
					'idkperluan2_ptsl' => $this->input->post('dkeperluan2'),
					'atasnama2_ptsl' => $this->input->post('dnama2'),
					'thn2_ptsl' => $this->input->post('dtahun2'),
					'desc3_ptsl' => $this->input->post('des3'),
					'idkperluan3_ptsl' => $this->input->post('dkeperluan3'),
					'thn3_ptsl' => $this->input->post('dtahun3'),
					'atasnama3_ptsl' => $this->input->post('dnama3'),
					'desc4_ptsl' => $this->input->post('des4'),
					'idkperluan4_ptsl' => $this->input->post('dkeperluan4'),
					'thn4_ptsl' => $this->input->post('dtahun4'),
					'atasnama4_ptsl' => $this->input->post('dnama4'),
					'desc5_ptsl' => $this->input->post('des5'),
					'idkperluan5_ptsl' => $this->input->post('dkeperluan5'),
					'thn5_ptsl' => $this->input->post('dtahun5'),
					'atasnama5_ptsl' => $this->input->post('dnama5'),
					'dc_ptsl' => $this->input->post('dc'),
					'dc1_ptsl' => $this->input->post('dc1'),
					'dc2_ptsl' => $this->input->post('dc2'),
					'dc3_ptsl' => $this->input->post('dc3'),
					'dc4_ptsl' => $this->input->post('dc4'),
					'dc5_ptsl' => $this->input->post('dc5'),
					'dpersil_ptsl' => $this->input->post('dpersil'),
					'dklas_ptsl' => $this->input->post('dklas'),
					'dluas_ptsl' => $this->input->post('dluas'),
					'dluas1_ptsl' => $this->input->post('dluas1'),
					'dluas2_ptsl' => $this->input->post('dluas2'),
					'dluas3_ptsl' => $this->input->post('dluas3'),
					'dluas4_ptsl' => $this->input->post('dluas4'),
					'dluas5_ptsl' => $this->input->post('dluas5'),
					'ddari_ptsl' => $this->input->post('ddari'),
					'note_ptsl' => $this->input->post('note'),
					'luasfisik_ptsl' => $this->input->post('luas'),
					'noberkas_ptsl' => $this->input->post('noberkas'),
					'hak_ptsl' => $this->input->post('jenishak'),
					'nib_ptsl' => $this->input->post('nib'),
					'klaster_ptsl' => $this->input->post('seleksik1'),
					'noberkasyrd_ptsl' => $this->input->post('noberkasyuridis'),
					'thn_risalah' => $this->input->post('thn_risalah'),
					'nm_pewaris' => $this->input->post('pewaris'),
					'thn_meninggal' => $this->input->post('meninggal'),
					'srt_ket_waris' => $this->input->post('waris'),
					'srt_wasiat' => $this->input->post('wasiat'),
					'tgl_hibah' => $this->input->post('tglhibah'),
					'nmr_hibah' => $this->input->post('nohibah'),
					'ppat_hibah' => $this->input->post('namahibah'),
					'tgl_beli' => $this->input->post('tglbeli'),
					'nmr_beli' => $this->input->post('nobeli'),
					'ppat_beli' => $this->input->post('namabeli'),
					'publish_ptsl' => '1',
					'idusr_ptsl' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);

				$simpan = $this->crud_model->input('tb_ptsl',$dataarray);
				$insert_id = $this->db->insert_id();

				$cekblockz['table'] 	= "tb_block";
				$cekblockz['type'] 	  = "single";
				$cekblockz['column'] 	= "idkel_blk";
				$cekblockz['condition']['idblk_blk'] 	  = $idblk;
				$cekbl = $this->crud_model->get_data($cekblockz);
				if($this->input->post('nib')){
						$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekbl['idkel_blk'],'nib_nib'=>$this->input->post('nib'),'idref_nib'=>$insert_id,'status_nib'=>0));
				}

				$dhkp = $this->input->post('dhkp');
				foreach ($dhkp as $dd) {
					if($dd!=null && $dd!=0){
						$datptsl ['idptsl_ptsl'] = $insert_id;
						$datptsl ['iddhkp_ptsl'] = $dd;

						$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
					}
				}

				if(isset($_FILES['berkas'])){
					$nama_upload = $this->input->post('berkasname');
					$count = count($_FILES['berkas']['name']);
					for($i=0;$i<$count;$i++){
				// 			$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
		        //   $_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
		        //   $_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
		        //   $_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
		        //   $_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

				// 			$file = explode(".",$_FILES["berkas"]["name"][$i]);
			    //     		$sum = count($file);
				// 			$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
				// 			$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
				// 			$config1['upload_path']		= './DATA/BERKAS/';
				// 			$config1['allowed_types']	= '*';
				// 			$this->upload->initialize($config1);
				// 			$uploads 				= $this->upload->do_upload('file');
				// 			$data1					= $this->upload->data();
				// 			$nama_upload 		= $data1['file_name'];

				// 			if($data1){
								$ar = array(
									'idptsl_pbk' => $insert_id,
									'berkas_pbk' => $nama_upload[$i]
								);
								$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
								$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Add Berkas dengan rincian ".displayArray($ar));
							// }
					}
				}

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data PTSL dengan rincian ".displayArray($dataarray));
				$this->db->trans_complete();

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $this->input->get('search'); ?>">
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

			$this->content['data']['title'] = "e-Yuridis : Tambah Pengajuan Kelurahan ".$block['nma_kel']." : ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","studio_7_2/data/?search=".$idblk),array("Tambah Pengajuan","studio_7_2/input/?search=".$idblk));

			$this->content['status'] = "tambah";

			$template['type'] = "single";
			$template['table'] = "tb_ptsl";
			$template['column'] = "desc0_ptsl,atasnama_ptsl,atasnama2_ptsl,atasnama3_ptsl,atasnama4_ptsl,atasnama5_ptsl,dc_ptsl,dc2_ptsl,dc3_ptsl,dc4_ptsl,dc5_ptsl,dluas_ptsl,dluas2_ptsl,dluas3_ptsl,dluas4_ptsl,dluas5_ptsl,desc1_ptsl,desc2_ptsl,desc3_ptsl,desc4_ptsl,desc5_ptsl,thn_risalah";
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
						'notelp_pdk' => $this->input->post('notelp'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);
					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
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
						'notelp_pdk' => $this->input->post('notelp'),
						'idusr_pdk' => $user['idusr_usr']
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
					$insert_id = $ktp['idpdk_pdk'];
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
				}

				$dataarray = array(
					'idpdk_ptsl'   => $insert_id,
					'idguna_ptsl' => $this->input->post('guna'),
					'idmanfaat_ptsl' => $this->input->post('manfaat'),
					'idblk_ptsl' => $this->input->post('blok'),
					'utara_ptsl' => $this->input->post('utara'),
					'timur_ptsl' => $this->input->post('timur'),
					'selatan_ptsl' => $this->input->post('selatan'),
					'barat_ptsl' => $this->input->post('barat'),
					'desc0_ptsl' => $this->input->post('des0'),
					'desc1_ptsl' => $this->input->post('des1'),
					'idkperluan_ptsl' => $this->input->post('dkeperluan'),
					'thn_ptsl' => $this->input->post('dtahun'),
					'atasnama_ptsl' => $this->input->post('dnama'),
					'desc2_ptsl' => $this->input->post('des2'),
					'idkperluan2_ptsl' => $this->input->post('dkeperluan2'),
					'thn2_ptsl' => $this->input->post('dtahun2'),
					'atasnama2_ptsl' => $this->input->post('dnama2'),
					'desc3_ptsl' => $this->input->post('des3'),
					'idkperluan3_ptsl' => $this->input->post('dkeperluan3'),
					'thn3_ptsl' => $this->input->post('dtahun3'),
					'atasnama3_ptsl' => $this->input->post('dnama3'),
					'desc4_ptsl' => $this->input->post('des4'),
					'idkperluan4_ptsl' => $this->input->post('dkeperluan4'),
					'thn4_ptsl' => $this->input->post('dtahun4'),
					'atasnama4_ptsl' => $this->input->post('dnama4'),
					'desc5_ptsl' => $this->input->post('des5'),
					'idkperluan5_ptsl' => $this->input->post('dkeperluan5'),
					'thn5_ptsl' => $this->input->post('dtahun5'),
					'atasnama5_ptsl' => $this->input->post('dnama5'),
					'dc_ptsl' => $this->input->post('dc'),
					'dc2_ptsl' => $this->input->post('dc2'),
					'dc3_ptsl' => $this->input->post('dc3'),
					'dc4_ptsl' => $this->input->post('dc4'),
					'dc5_ptsl' => $this->input->post('dc5'),
					'dpersil_ptsl' => $this->input->post('dpersil'),
					'dklas_ptsl' => $this->input->post('dklas'),
					'dluas_ptsl' => $this->input->post('dluas'),
					'dluas1_ptsl' => $this->input->post('dluas1'),
					'dluas2_ptsl' => $this->input->post('dluas2'),
					'dluas3_ptsl' => $this->input->post('dluas3'),
					'dluas4_ptsl' => $this->input->post('dluas4'),
					'dluas5_ptsl' => $this->input->post('dluas5'),
					'ddari_ptsl' => $this->input->post('ddari'),
					'note_ptsl' => $this->input->post('note'),
					'luasfisik_ptsl' => $this->input->post('luas'),
					'noberkas_ptsl' => $this->input->post('noberkas'),
					'hak_ptsl' => $this->input->post('jenishak'),
					'nib_ptsl' => $this->input->post('nib'),
					'klaster_ptsl' => $this->input->post('seleksik1'),
					'noberkasyrd_ptsl' => $this->input->post('noberkasyuridis'),
					'thn_risalah' => $this->input->post('thn_risalah'),
					'nm_pewaris' => $this->input->post('pewaris'),
					'thn_meninggal' => $this->input->post('meninggal'),
					'srt_ket_waris' => $this->input->post('waris'),
					'srt_wasiat' => $this->input->post('wasiat'),
					'tgl_hibah' => $this->input->post('tglhibah'),
					'nmr_hibah' => $this->input->post('nohibah'),
					'ppat_hibah' => $this->input->post('namahibah'),
					'tgl_beli' => $this->input->post('tglbeli'),
					'nmr_beli' => $this->input->post('nobeli'),
					'ppat_beli' => $this->input->post('namabeli'),
					'publish_ptsl' => '1',
					'idusr_ptsl' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$id));

				$cekblockz['table'] 	= "tb_block";
				$cekblockz['type'] 	  = "single";
				$cekblockz['column'] 	= "idkel_blk";
				$cekblockz['condition']['idblk_blk'] 	  = $idblk;
				$cekbl = $this->crud_model->get_data($cekblockz);
				if($this->input->post('nib')){
					ceknib($this->input->post('nib'),$id,$cekbl['idkel_blk'],0);
				}

				$delete = $this->crud_model->delete('tb_ptsldhkp',array('idptsl_ptsl'=>$id));

				$dhkp = $this->input->post('dhkp');
				foreach ($dhkp as $dd) {
					if($dd!=null && $dd!=0){
						$datptsl ['idptsl_ptsl'] = $id;
						$datptsl ['iddhkp_ptsl'] = $dd;

						$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
					}
				}

				// if(isset($_FILES['berkas'])){
				// 	$count = count($_FILES['berkas']['name']);
				// 	for($i=0;$i<$count;$i++){
				// 			$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
				// 			$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
				// 			$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
				// 			$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
				// 			$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

				// 			$file = explode(".",$_FILES["berkas"]["name"][$i]);
				// 			$sum = count($file);
				// 			$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
				// 			$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
				// 			$config1['upload_path']		= './DATA/BERKAS/';
				// 			$config1['allowed_types']	= '*';
				// 			$this->upload->initialize($config1);
				// 			$uploads 				= $this->upload->do_upload('file');
				// 			$data1					= $this->upload->data();
				// 			$nama_upload 		= $data1['file_name'];

				// 			if($data1){
				// 				$ar = array(
				// 					'idptsl_pbk' => $id,
				// 					'berkas_pbk' => $nama_upload
				// 				);
				// 				$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
				// 				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Add Berkas dengan rincian ".displayArray($ar));
				// 			}
				// 	}
				// }

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));
				$this->db->trans_complete();

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk; ?>">
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

				$this->content['data']['title'] = "e-Yuridis : Edit Pengajuan Kelurahan ".$block['nma_kel']." : ".$block['nama_blk'];
				$this->content['data']['subtitle'] = array(array("e-yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("Belum Sertipikat","studio_7_2/data/?search=".$idblk),array("Edit Pengajuan","studio_7_2/edit/".$id."/".$idblk));

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

		public function delete($kode)
		{
			$ar = array(
				'publish_ptsl' => '0'
			);
			$hapus = $this->crud_model->update('tb_ptsl',$ar,array('id_ptsl'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Yuridis-0-'.$kode,"Menghapus Data PTSL dengan kode ".$kode);

			$hapus = $this->crud_model->delete("tb_nib",array('idref_nib'=>$kode,'status_nib'=>0));

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function register()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			$this->content['data']['title'] = "e-Yuridis : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." Sudah Sertipikat";
			$this->content['data']['subtitle'] = array(array("e-yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("Sudah Sertipikat","studio_7_2/register/?search=".$cari));

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


			$config['base_url'] = base_url().'Studio_7_2/register/';
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

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama Wajib Pajak','nama_dhkp'),array('No Hak','nohak_nub'),array('NOP','nosppt_dhkp'),array('Belum Link NUB/NIB','disconect'),array('Sudah Link NUB/NIB','connect'));

			$this->content['data']['title'] = "e-Yuridis : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." DHKP";
			$this->content['data']['subtitle'] = array(array("e-yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("DHKP","studio_7_2/dhkp/?search=".$cari));

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


			$config['base_url'] = base_url().'studio_7_2/dhkp/';
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

		public function k4()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Pemilik Awal','pma_hak'),array('Pemilik Akhir','pmi_hak'),array('No hak','no_hak'),array('No SU','nosu_hak'),array('NIB','nib_hak'),array('NOP','nosppt_dhkp'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "e-Yuridis : Kelurahan ".$block['nma_kel']." Blok ".$block['nama_blk']." K4";
			$this->content['data']['subtitle'] = array(array("e-yuridis","studio7"),array("Daftar Blok","studio_7_1/index/?search=".$block['idkel_blk']),array("K4","studio_7_2/k4/?search=".$cari));

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


			$config['base_url'] = base_url().'studio_7_2/k4/';
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

	}
