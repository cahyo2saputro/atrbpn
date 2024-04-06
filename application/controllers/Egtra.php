<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Egtra extends CI_Controller
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
		$this->content['data']['title_page'] = 'e-GTRA';
		$this->load->view('auth/authorized');
	}

	public function index()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "e-Aset Reform";
		$this->content['data']['subtitle'] = array(array("e-Aset Reform","egtra"));

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

		$config['base_url'] = base_url().'egtra/index/';
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

		$this->content['studio'] = $this->studio3_model->show_gtra($config['per_page'],$from,$cari,$carikelurahan);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("studiogtra/data_studio");
		$this->load->view('adm',$this->content);
	}

	public function simpan_peta($id)
	{
		$user = $this->auth_model->get_userdata();

		$msdusun['type'] = "single";
		$msdusun['table'] = "ms_dusun";
		$msdusun['condition']['id_dsn'] = $id;
		$dusun = $this->crud_model->get_data($msdusun);

		$nmfile2 									= $dusun['name_dsn']."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
		$config2['upload_path']		= './PETA/PETA_DUSUN/';
		$config2['allowed_types']	= '*';
		$this->upload->initialize($config2);
		$upload2 				= $this->upload->do_upload('petblk_blk');
		$data2					= $this->upload->data();
		$nama_upload_dsn 		= $data2['file_name'];

		$ar = array(
				'peta_dsn' => $nama_upload_dsn
		);

		$simpan = $this->crud_model->update('ms_dusun',$ar,array('id_dsn'=>$id));

		if($simpan){
			$msg = true;
		}
		echo json_encode($msg);die();
	}

	public function list()
	{
		$cari = $this->input->get('search');

		$kelurahan['type'] = "single";
		$kelurahan['table'] = "ms_kelurahan";
		$kelurahan['condition']['kd_full'] = $cari;

		$nma_kel = $this->crud_model->get_data($kelurahan);

		$this->content['data']['title'] = "e-aset reform Desa/Kelurahan ".$nma_kel['nma_kel'];
		$this->content['data']['subtitle'] = array(array("e-aset reform Desa","egtra"),array("Daftar Dusun","egtra/list/?search=".$cari));

		$dus['table'] = "ms_dusun";
		$dus['type'] = "multiple";
		$dus['column'] = "*,
										(SELECT COUNT(id_gtra) FROM tb_gtra WHERE iddsn_gtra=id_dsn) as jml_oby,
										(SELECT COUNT(DISTINCT(idpdk_gtra)) FROM tb_gtra WHERE iddsn_gtra=id_dsn) as jml_sby,
										(SELECT COUNT(DISTINCT(nokk_pdk)) FROM tb_gtra,tb_penduduk WHERE idpdk_gtra=idpdk_pdk AND iddsn_gtra=id_dsn AND idpdk_gtra!=111) as jml_kksby,
										(SELECT COUNT(id_gtra) FROM tb_gtra WHERE iddsn_gtra=id_dsn AND idpdk_gtra=111) as jml_lain
											";
		$dus['condition']['kdkel_dsn'] =$cari;
		$this->content['block'] = $this->crud_model->get_data($dus);

		$this->content['load'] = array("studiogtra/data_studio_list");
		$this->load->view('adm',$this->content);
	}

	public function data($id=null)
	{
		$user = $this->auth_model->get_userdata();
		$cari = $this->input->get('search');
		$block = $this->studio_2_1_model->sr_name_block($cari);

		$des['table'] = "ms_dusun";
		$des['type']  = "single";
		$des['condition']['kdkel_dsn'] = $cari;
		$nmdusun = $this->crud_model->get_data($des);

		// SEARCHING
		$this->content['data']['param'] = array(array('NUB','nubdsn_gtra'),array('NUB A','nub_gtra'),array('NO PPPTR','ppptr_gtra'),array('Nama','nma_pdk'),array('No. KTP','noktp_pdk'),array('NOP','nosppt_dhkp'));

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$nmdusun['kdkel_dsn']);

		$this->content['data']['title'] = "e-gtra Desa : Kelurahan ".$block['nma_kel']." Dusun ".$nmdusun['name_dsn']." Belum Sertipikat";
		$this->content['data']['subtitle'] = array(array("e-gtra","egtra"),array("Daftar Dusun","egtra/list/?search=".$cari),array("Belum Sertipikat","egtra/data/".$id."?search=".$cari));

		$this->content['idblk'] = $block['idblk_blk'];

		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		if($this->input->get('nilai')){
			$tdata['table'] = "tb_gtra";
			$tdata['type'] = "single";
			$tdata['join']['table'] = "tb_penduduk,tb_gtradhkp,tb_dhkp";
			$tdata['join']['key'] = "idpdk_gtra,idgtra_gtra,id_dhkp";
			$tdata['join']['ref'] = "idpdk_pdk,id_gtra,iddhkp_gtra";
			$tdata['column'] = "COUNT(id_gtra) as jumlah";
			$tdata['condition']['iddsn_gtra'] = $id;
			$tdata['condition']['publish_gtra'] = '1';

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
			$tdata['table'] = "tb_gtra";
			$tdata['type'] = "single";
			$tdata['column'] = "COUNT(id_gtra) as jumlah";
			$tdata['condition']['iddsn_gtra'] = $id;
			$tdata['condition']['publish_gtra'] = '1';
		}

		$t_data = $this->crud_model->get_data($tdata);

		$config['base_url'] = base_url().'egtra/data/'.$id.'/';
		$config['total_rows'] = $t_data['jumlah'];
		$config['uri_segment'] = 4;
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

		$dat['table'] = "tb_gtra";
		$dat['type'] = "multiple";
		$dat['column'] = "tb_gtra.nubdsn_gtra,tb_gtra.nub_gtra,tb_gtra.iddsn_gtra,tb_gtra.ppptr_gtra,tb_gtra.id_gtra,tb_dhkp.nosppt_dhkp,tb_penduduk.noktp_pdk,tb_penduduk.nma_pdk,tb_block.idkel_blk,tb_block.nama_blk,tb_gtra.idblk_gtra";
		$dat['join']['table'] = "tb_penduduk,tb_gtradhkp,tb_dhkp,tb_block";
		$dat['join']['key'] = "idpdk_gtra,idgtra_gtra,id_dhkp,idblk_blk";
		$dat['join']['ref'] = "idpdk_pdk,id_gtra,iddhkp_gtra,idblk_gtra";
		$dat['condition']['iddsn_gtra'] = $id;
		$dat['condition']['publish_gtra'] = '1';
		$dat['orderby']['column'] = 'tb_gtra.update_at';
		$dat['orderby']['sort'] = 'desc';
		$dat['groupby']         = 'nub_gtra';

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

		$this->content['load'] = array("studiogtra/data_studio_data");
		$this->load->view('adm',$this->content);
	}

	public function exportxls($id)
	{
		$user = $this->auth_model->get_userdata();
		$cari = $this->input->get('search');
		$block = $this->studio_2_1_model->sr_name_block($cari);

		$kec ['type'] 	= "single";
		$kec ['table']	= "ms_dusun";
		$kec ['join']['table']	= "ms_kelurahan,ms_kecamatan";
		$kec ['join']['key']	= "kd_full,kdkec_kel";
		$kec ['join']['ref']	= "kdkel_dsn,kd_kec";
		$kec ['condition']['id_dsn'] = $id;
		$this->content['desa'] = $this->crud_model->get_data($kec);

		$dat['table'] = "tb_gtra";
		$dat['type'] = "multiple";
		$dat['join']['table'] = "tb_penduduk,tb_pekerjaan,tb_block,ms_dusun";
		$dat['join']['key'] = "idpdk_gtra,idpkr_pkr,idblk_blk,id_dsn";
		$dat['join']['ref'] = "idpdk_pdk,idpeker_pdk,idblk_gtra,iddsn_gtra";
		$dat['condition']['iddsn_gtra'] = $id;
		$dat['condition']['publish_gtra'] = '1';
		$dat['orderby']['column'] = 'tb_gtra.update_at';
		$dat['orderby']['sort'] = 'desc';
		$dat['groupby']         = 'nub_gtra';

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

		$this->content['studio'] = $this->crud_model->get_data($dat);

		$this->load->view('studiogtra/exportxls',$this->content);
	}

	public function input($id){
		$user = $this->auth_model->get_userdata();
		if(empty($this->input->get('search'))){
			$idblk = $this->uri->segment(5);
		}else{
			$idblk = $this->input->get('search');
		}
		$status = $this->uri->segment(3);
		$set_status = $this->uri->segment(4);

		$block = $this->studio_2_1_model->sr_name_block($idblk);
		cekkelurahan($user['idusr_usr'],$user['level_usr'],$idblk);


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
					'domkel_pdk' => $this->input->post('keldom'),
					'domkec_pdk' => $this->input->post('kecdom'),
					'domkab_pdk' => $this->input->post('kabdom'),
					'nokk_pdk' => $this->input->post('kk'),
					'pasangan_pdk' => $this->input->post('pasangan'),
					'penghasilan_pdk' => $this->input->post('penghasilan'),
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
					'domkel_pdk' => $this->input->post('keldom'),
					'domkec_pdk' => $this->input->post('kecdom'),
					'domkab_pdk' => $this->input->post('kabdom'),
					'nokk_pdk' => $this->input->post('kk'),
					'pasangan_pdk' => $this->input->post('pasangan'),
					'penghasilan_pdk' => $this->input->post('penghasilan'),
					'idusr_pdk' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk'=>$ktp['noktp_pdk']));
				$insert_id = $ktp['idpdk_pdk'];

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e gtra Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Update Data Penduduk dengan rincian ".displayArray($ar));

			}

			$anak = $this->input->post('anak');
			if($anak){
				foreach ($anak as $dd) {
					$datanak ['idpdk_ank'] = $insert_id;
					$datanak ['nama_ank'] = $dd;

					$inputdhkp = $this->crud_model->input("tb_anak",$datanak);
				}
			}

			// GET NUB
			$datnub['table'] = "tb_gtra";
			$datnub['type'] = "single";
			$datnub['join']['table'] = "ms_dusun";
			$datnub['join']['key'] = "id_dsn";
			$datnub['join']['ref'] = "iddsn_gtra";
			$datnub['column'] = "MAX(nub_gtra) as maximum";
			$datnub['condition']['kdkel_dsn'] = $idblk;
			$datnub['condition']['publish_gtra'] = 1;
			$nub = $this->crud_model->get_data($datnub);

			if($nub){
				$dnub=$nub['maximum']+1;
			}else{
				$dnub=1;
			}

			$nubdsn['table'] = "tb_gtra";
			$nubdsn['type'] = "single";
			$nubdsn['join']['table'] = "ms_dusun";
			$nubdsn['join']['key'] = "id_dsn";
			$nubdsn['join']['ref'] = "iddsn_gtra";
			$nubdsn['column'] = "MAX(nubdsn_gtra) as maximum";
			$nubdsn['condition']['id_dsn'] = $id;
			$nubdsn['condition']['publish_gtra'] = 1;
			$dnubdsn = $this->crud_model->get_data($nubdsn);

			if($dnubdsn){
				$nnubdsn=$dnubdsn['maximum']+1;
			}else{
				$nnubdsn=1;
			}

			$nama_sketsa = '';
			if(isset($_FILES['skets'])){
						$_FILES['file']['name'] = $_FILES['skets']['name'];
						$_FILES['file']['type'] = $_FILES['skets']['type'];
						$_FILES['file']['tmp_name'] = $_FILES['skets']['tmp_name'];
						$_FILES['file']['error'] = $_FILES['skets']['error'];
						$_FILES['file']['size'] = $_FILES['skets']['size'];

						$file = explode(".",$_FILES["skets"]["name"]);
						$sum = count($file);
						$nmfile1 					= "Skets_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './Skets/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data4					= $this->upload->data();
						if($uploads){
								$nama_sketsa 		= $nmfile1;
						}
			}


				$dataarray = array(
					'nub_gtra' => $dnub,
					'nubdsn_gtra' => $nnubdsn,
					'idpdk_gtra'   => $insert_id,
					'idblk_gtra' => $this->input->post('blok'),
					'iddsn_gtra' => $this->input->post('dusun'),
					'nib_gtra' => $this->input->post('nib'),
					'ppptr_gtra' => $this->input->post('p3tr'),
					'utara_gtra' => $this->input->post('utara'),
					'timur_gtra' => $this->input->post('timur'),
					'selatan_gtra' => $this->input->post('selatan'),
					'barat_gtra' => $this->input->post('barat'),
					'tanahdasar_gtra' => $this->input->post('dasargarap'),
					'tanahsumber_gtra' => $this->input->post('sumbertanah'),
					'luastanah_gtra' => $this->input->post('luas'),
					'gunatanah_gtra' => $this->input->post('gunatanah'),
					'manfaattanah_gtra' => $this->input->post('manfaattanah'),
					'nilaitanah_gtra' => $this->input->post('nilai'),
					'kuasacara_gtra' => $this->input->post('kuasacara'),
					'tanamankuasa_gtra' => $this->input->post('tanaman'),
					'gunakuasa_gtra' => $this->input->post('gunakuasa'),
					'tahunkuasa_gtra' => $this->input->post('tahungarap'),
					'dasarkuasa_gtra' => $this->input->post('dasarkuasa'),
					'laintanahluas_gtra' => $this->input->post('tanahluas'),
					'laintanahbidang_gtra' => $this->input->post('tanahbidang'),
					'lainletak_gtra' => $this->input->post('alamatletak'),
					'laindesa_gtra' => $this->input->post('desa'),
					'lainkecamatan_gtra' => $this->input->post('kecamatan'),
					'lainkabupaten_gtra' => $this->input->post('kabupaten'),
					'lainluasgarap_gtra' => $this->input->post('garapluas'),
					'lainbidanggarap_gtra' => $this->input->post('garapbidang'),
					'skets_gtra' => $nama_sketsa,
					'publish_gtra' => '1',
					'idusr_gtra' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);

			$simpan = $this->crud_model->input('tb_gtra',$dataarray);
			$insert_id = $this->db->insert_id();

			$dhkp = $this->input->post('dhkp');
			foreach ($dhkp as $dd) {
				$datptsl ['idgtra_gtra'] = $insert_id;
				$datptsl ['iddhkp_gtra'] = $dd;

				if($datptsl ['iddhkp_gtra']){
						$inputdhkp = $this->crud_model->input("tb_gtradhkp",$datptsl);
				}
			}

			if(isset($_FILES['berkas'])){

				$des['table'] = "ms_dusun";
				$des['type']  = "single";
				$des['condition']['id_dsn'] = $this->input->post('dusun');
				$nmdusun = $this->crud_model->get_data($des);

				$count = count($_FILES['berkas']['name']);
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
						$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
						$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
						$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

						$file = explode(".",$_FILES["berkas"]["name"][$i]);
						$sum = count($file);
						//$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$nmfile1 =  str_replace(" ","_",$nmdusun['name_dsn']).'_'.$this->input->post('p3tr').'_'.time().''.rand(0,100).'.'.$file[$sum-1];
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './DATA/BERKASGTRA/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data1					= $this->upload->data();
						$nama_upload 		= $data1['file_name'];

						if($data1){
							$ar = array(
								'idgtra_pbk' => $insert_id,
								'berkas_pbk' => $nama_upload
							);
							$simpan = $this->crud_model->input('tb_gtraberkas',$ar);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_gtraberkas','e GTRA-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Add Berkas dengan rincian ".displayArray($ar));
						}
				}
			}

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Menambahkan Data PTSL dengan rincian ".displayArray($dataarray));
			$this->db->trans_complete();

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>egtra/data/<?=$id?>?search=<?php echo $this->input->get('search'); ?>">
			<?php
		}

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$dus['table'] = "ms_dusun";
		$dus['type'] = "multiple";
		$dus['condition']['id_dsn'] =$id;
		$this->content['dusun'] = $this->crud_model->get_data($dus);

		$dhkp['table'] = "tb_dhkp";
		$dhkp['type'] = "multiple";
		$dhkp['join']['table'] = "tb_block";
		$dhkp['join']['key'] = "idblk_blk";
		$dhkp['join']['ref'] = "idblk_dhkp";
		$dhkp['condition']['idkel_blk'] =$idblk;
		$this->content['dhkp'] = $this->crud_model->get_data($dhkp);
		$this->content['template'] = NULL;
		$this->content['sppt'] = NULL;
		$this->content['berkas'] = NULL;
		$this->content['anak'] = NULL;

			$this->content['data']['title'] = "e-gtra Desa : Tambah GTRA Kelurahan ".$block['nma_kel']." : ".$block['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-gtra","egtra"),array("Daftar Dusun","egtra/list/?search=".$idblk),array("Belum Sertipikat","egtra/data/".$id."?search=".$idblk),array("Tambah Pengajuan","egtra/input/".$id."?search=".$idblk));

			$this->content['status'] = "tambah";

			$this->content['block'] = $block;

			$this->content['load'] = array("studiogtra/form_gtra");
			$this->load->view('adm',$this->content);
}

	public function edit($id,$idblk,$page=null){
		$user = $this->auth_model->get_userdata();
		$block = $this->studio_2_1_model->sr_name_block($idblk);

		$template['type'] = "single";
		$template['table'] = "tb_gtra";
		$template['join']['table']="tb_penduduk";
		$template['join']['key']="idpdk_gtra";
		$template['join']['ref']="idpdk_pdk";
		$template['condition']['id_gtra'] = $id;
		$this->content['template'] = $this->crud_model->get_data($template);

		$berkas['type'] = "multiple";
		$berkas['table'] = "tb_gtraberkas";
		$berkas['condition']['idgtra_pbk'] = $id;
		$this->content['berkas'] = $this->crud_model->get_data($berkas);

		$anak['type'] = "multiple";
		$anak['table'] = "tb_anak";
		$anak['condition']['idpdk_ank'] = $this->content['template']['idpdk_pdk'];
		$this->content['anak'] = $this->crud_model->get_data($anak);

		$spt['type'] = "multiple";
		$spt['table'] = "tb_gtradhkp";
		$spt['join']['table']="tb_dhkp";
		$spt['join']['key']="id_dhkp";
		$spt['join']['ref']="iddhkp_gtra";
		$spt['condition']['idgtra_gtra'] = $id;
		$this->content['sppt'] = $this->crud_model->get_data($spt);

		$des['table'] = "ms_dusun";
		$des['type'] = "single";
		$des['join']['table']="ms_kelurahan";
		$des['join']['key']="kd_full";
		$des['join']['ref']="kdkel_dsn";
		$des['condition']['id_dsn'] = $idblk;
		$nmdusun = $this->crud_model->get_data($des);

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
					'domkel_pdk' => $this->input->post('keldom'),
					'domkec_pdk' => $this->input->post('kecdom'),
					'domkab_pdk' => $this->input->post('kabdom'),
					'nokk_pdk' => $this->input->post('kk'),
					'pasangan_pdk' => $this->input->post('pasangan'),
					'penghasilan_pdk' => $this->input->post('penghasilan'),
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
					'domkel_pdk' => $this->input->post('keldom'),
					'domkec_pdk' => $this->input->post('kecdom'),
					'domkab_pdk' => $this->input->post('kabdom'),
					'nokk_pdk' => $this->input->post('kk'),
					'pasangan_pdk' => $this->input->post('pasangan'),
					'penghasilan_pdk' => $this->input->post('penghasilan'),
					'idusr_pdk' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
				$insert_id = $ktp['idpdk_pdk'];
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
			}

			$nama_sketsa = $this->content['template']['skets_gtra'];
			if(isset($_FILES['skets'])){
						$_FILES['file']['name'] = $_FILES['skets']['name'];
						$_FILES['file']['type'] = $_FILES['skets']['type'];
						$_FILES['file']['tmp_name'] = $_FILES['skets']['tmp_name'];
						$_FILES['file']['error'] = $_FILES['skets']['error'];
						$_FILES['file']['size'] = $_FILES['skets']['size'];

						$file = explode(".",$_FILES["skets"]["name"]);
						$sum = count($file);
						$nmfile1 					= "Skets_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './Skets/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data4					= $this->upload->data();
						if($uploads){
								$nama_sketsa 		= $nmfile1;
						}
			}

			$dataarray = array(
				'idpdk_gtra'   => $insert_id,
				'nib_gtra' => $this->input->post('nib'),
				'iddsn_gtra' => $this->input->post('dusun'),
				'ppptr_gtra' => $this->input->post('p3tr'),
				'idblk_gtra' => $this->input->post('blok'),
				'utara_gtra' => $this->input->post('utara'),
				'timur_gtra' => $this->input->post('timur'),
				'selatan_gtra' => $this->input->post('selatan'),
				'barat_gtra' => $this->input->post('barat'),
				'tanahdasar_gtra' => $this->input->post('dasargarap'),
				'tanahsumber_gtra' => $this->input->post('sumbertanah'),
				'luastanah_gtra' => $this->input->post('luas'),
				'gunatanah_gtra' => $this->input->post('gunatanah'),
				'manfaattanah_gtra' => $this->input->post('manfaattanah'),
				'nilaitanah_gtra' => $this->input->post('nilai'),
				'kuasacara_gtra' => $this->input->post('kuasacara'),
				'tanamankuasa_gtra' => $this->input->post('tanaman'),
				'gunakuasa_gtra' => $this->input->post('gunakuasa'),
				'tahunkuasa_gtra' => $this->input->post('tahungarap'),
				'dasarkuasa_gtra' => $this->input->post('dasarkuasa'),
				'laintanahluas_gtra' => $this->input->post('tanahluas'),
				'laintanahbidang_gtra' => $this->input->post('tanahbidang'),
				'lainletak_gtra' => $this->input->post('alamatletak'),
				'laindesa_gtra' => $this->input->post('desa'),
				'lainkecamatan_gtra' => $this->input->post('kecamatan'),
				'lainkabupaten_gtra' => $this->input->post('kabupaten'),
				'lainluasgarap_gtra' => $this->input->post('garapluas'),
				'lainbidanggarap_gtra' => $this->input->post('garapbidang'),
				'skets_gtra' => $nama_sketsa,
				'publish_gtra' => '1',
				'idusr_gtra' => $user['idusr_usr']
			);
			$simpan = $this->crud_model->update('tb_gtra',$dataarray,array('id_gtra'=>$id));

			$delete = $this->crud_model->delete('tb_anak',array('idpdk_ank'=>$insert_id));

			$anak = $this->input->post('anak');
			if($anak){
				foreach ($anak as $dd) {
					$datanak ['idpdk_ank'] = $insert_id;
					$datanak ['nama_ank'] = $dd;

					$inputanak = $this->crud_model->input("tb_anak",$datanak);
				}
			}

			$delete = $this->crud_model->delete('tb_gtradhkp',array('idgtra_gtra'=>$id));

			$dhkp = $this->input->post('dhkp');
				foreach ($dhkp as $dd) {
					$datptsl ['idgtra_gtra'] = $id;
					$datptsl ['iddhkp_gtra'] = $dd;
					if($datptsl ['iddhkp_gtra']){
							$inputdhkp = $this->crud_model->input("tb_gtradhkp",$datptsl);
					}
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
						//$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$nmfile1 = str_replace(" ","_",$nmdusun['name_dsn']).'_'.$this->input->post('p3tr').'_'.time().''.rand(0,100).'.'.$file[$sum-1];
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './DATA/BERKASGTRA/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data1					= $this->upload->data();
						$nama_upload 		= $data1['file_name'];

						if($data1){
							$ar = array(
								'idgtra_pbk' => $id,
								'berkas_pbk' => $nama_upload
							);
							$simpan = $this->crud_model->input('tb_gtraberkas',$ar);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Add Berkas dengan rincian ".displayArray($ar));
						}
				}
			}

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl','e Panitia Desa-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$id,"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));

			$this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>egtra/data/<?php echo $idblk; ?>/<?=$page?>?search=<?php echo $nmdusun['kd_full']; ?>">
			<?php
		}

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$status = $this->uri->segment(3);
		$set_status = $this->uri->segment(4);

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$nmdusun['kdkel_dsn']);

			$idkel = $block['idkel_blk'];

			$this->content['data']['title'] = "e-aset reform Desa : Edit Pengajuan Desa ".$nmdusun['nma_kel']." : ".$nmdusun['name_dsn'];
			$this->content['data']['subtitle'] = array(array("e-aset reform","Studio3"),array("Daftar Dusun","egtra/list/".$idblk."?search=".$nmdusun['kdkel_dsn']),array("Belum Sertipikat","egtra/data/".$idblk."/".$page."?search=".$nmdusun['kdkel_dsn']),array("Edit Pengajuan","egtra/edit/".$id."/".$idblk."/".$page));

			$this->content['status'] = "edit";

			$this->content['block'] = $block;

			$dhkp['table'] = "tb_dhkp";
			$dhkp['type'] = "multiple";
			$dhkp['join']['table'] = "tb_block";
			$dhkp['join']['key'] = "idblk_blk";
			$dhkp['join']['ref'] = "idblk_dhkp";
			$dhkp['condition']['idkel_blk'] =$nmdusun['kd_full'];
			$this->content['dhkp'] = $this->crud_model->get_data($dhkp);

			$dus['table'] = "ms_dusun";
			$dus['type'] = "multiple";
			$dus['condition']['id_dsn'] =$idblk;
			$this->content['dusun'] = $this->crud_model->get_data($dus);

			$this->content['load'] = array("studiogtra/form_gtra");
			$this->load->view('adm',$this->content);
	}

	public function form_desa($id){
		$user = $this->auth_model->get_userdata();

		$kec['table'] = "ms_kelurahan";
		$kec['type'] = "single";
		$kec['column'] = "nma_kel,nma_kec,kd_full,type_kel";
		$kec['join']['table'] = 'ms_kecamatan';
		$kec['join']['key'] = 'kd_kec';
		$kec['join']['ref'] = 'kdkec_kel';
		$kec['condition']['kd_full'] = $id;
		$this->content['kecamatan'] = $this->crud_model->get_data($kec);

		if ($this->input->post()) {
			// $this->db->trans_start();

				$dataarray = array(
					'kepala_kel' => $this->input->post('kades'),
					'sekre_kel' => $this->input->post('sekre'),
				);
				$simpan = $this->crud_model->update('ms_kelurahan',$dataarray,array('kd_full'=>$id));
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_kelurahan','e gtra-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$id,"Mengedit Data Kepala desa dan sekretaris dengan rincian ".displayArray($dataarray));

				// $this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>egtra">
			<?php
		}

		$template['type'] = "single";
		$template['table'] = "ms_kelurahan";
		$template['condition']['kd_full'] = $id;
		$this->content['template'] = $this->crud_model->get_data($template);

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$id);

			$idkel = $id;

			$this->content['data']['title'] = "e-GTRA Desa : Form Kepala Desa Kelurahan ".$this->content['kecamatan']['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-gtra","egtra"),array("Form Kepala Desa","egtra/form_desa/".$id));

			$this->content['load'] = array("studiogtra/form_desa");
			$this->load->view('adm',$this->content);
	}

	public function form_saksi($id){
		$user = $this->auth_model->get_userdata();
		$search = $this->input->get('search');

		$template['type'] = "single";
		$template['table'] = "tb_saksigtra";
		$template['condition']['iddsn_sgt'] = $id;
		$this->content['template'] = $this->crud_model->get_data($template);

		$kec['table'] = "ms_kelurahan";
		$kec['type'] = "single";
		$kec['column'] = "nma_kel,name_dsn,nma_kec,kd_full,type_kel";
		$kec['join']['table'] = 'ms_dusun,ms_kecamatan';
		$kec['join']['key'] = 'kdkel_dsn,kd_kec';
		$kec['join']['ref'] = 'kd_full,kdkec_kel';
		$kec['condition']['id_dsn'] = $id;
		$this->content['kecamatan'] = $this->crud_model->get_data($kec);

		if ($this->input->post()) {
			// $this->db->trans_start();

			// CEK DATA SAKSI
			if(!$this->content['template']){
				$dataarray = array(
					'iddsn_sgt' => $id,
					'niksp1_sgt' => $this->input->post('niksaksi1'),
					'niksp2_sgt' => $this->input->post('niksaksi2'),
					'publish_sgt' => '1',
					'idusr_sgt' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('tb_saksigtra',$dataarray);
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_saksigtra','e gtra-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$id,"Menginput Data Saksi GTRA dengan rincian ".displayArray($dataarray));
			}else{
				$dataarray = array(
					'iddsn_sgt' => $id,
					'niksp1_sgt' => $this->input->post('niksaksi1'),
					'niksp2_sgt' => $this->input->post('niksaksi2'),
					'publish_sgt' => '1',
					'idusr_sgt' => $user['idusr_usr'],
					'create_at' => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->update('tb_saksigtra',$dataarray,array('idsgt_sgt'=>$this->content['template']['idsgt_sgt']));
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_saksigtra','e gtra-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$id,"Mengedit Data Saksi GTRA dengan rincian ".displayArray($dataarray));

				// SAKSI 1
				$datktp['table'] = "tb_penduduk";
				$datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('niksaksi1');
				$ktp = $this->crud_model->get_data($datktp);

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('niksaksi1'),
						'nma_pdk'   => addslashes($this->input->post('namasaksi1')),
						'ttl_pdk' => $this->input->post('lahirsaksi1'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi1'))),
						'idpeker_pdk' => $this->input->post('pekerjaansaksi1'),
						'agm_pdk' => $this->input->post('agamasaksi1'),
						'almat_pdk' => $this->input->post('alamatsaksi1'),
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
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
				}else{
					$ar = array(
						'nma_pdk'   => addslashes($this->input->post('namasaksi1')),
						'ttl_pdk' => $this->input->post('lahirsaksi1'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi1'))),
						'idpeker_pdk' => $this->input->post('pekerjaansaksi1'),
						'agm_pdk' => $this->input->post('agamasaksi1'),
						'almat_pdk' => $this->input->post('alamatsaksi1'),
						'rt_pdk' => $this->input->post('rt1'),
						'rw_pdk' => $this->input->post('rw1'),
						'kel_pdk' => $this->input->post('kel1'),
						'kec_pdk' => $this->input->post('kec1'),
						'kab_pdk' => $this->input->post('kab1'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('niksaksi1')));
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e GTRA Desa-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$this->input->post('niksaksi1'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
				}

				// SAKSI 2
				$datktp['table'] = "tb_penduduk";
				$datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('niksaksi2');
				$ktp = $this->crud_model->get_data($datktp);

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('niksaksi2'),
						'nma_pdk'   => addslashes($this->input->post('namasaksi2')),
						'ttl_pdk' => $this->input->post('lahirsaksi2'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi2'))),
						'idpeker_pdk' => $this->input->post('pekerjaansaksi2'),
						'agm_pdk' => $this->input->post('agamasaksi2'),
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
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Panitia Desa-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
				}else{
					$ar = array(
						'nma_pdk'   => addslashes($this->input->post('namasaksi2')),
						'ttl_pdk' => $this->input->post('lahirsaksi2'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tanggalsaksi2'))),
						'idpeker_pdk' => $this->input->post('pekerjaansaksi2'),
						'agm_pdk' => $this->input->post('agamasaksi2'),
						'almat_pdk' => $this->input->post('alamatsaksi2'),
						'rt_pdk' => $this->input->post('rt2'),
						'rw_pdk' => $this->input->post('rw2'),
						'kel_pdk' => $this->input->post('kel2'),
						'kec_pdk' => $this->input->post('kec2'),
						'kab_pdk' => $this->input->post('kab2'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
					);
					$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('niksaksi2')));
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e GTRA Desa-'.$this->content['kecamatan']['nma_kel'].'<br>-'.$this->input->post('niksaksi2'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
				}

				// $this->db->trans_complete();
			}
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>egtra/list/?search=<?php echo $search;?>">
			<?php
		}

		$template['type'] = "single";
		$template['table'] = "tb_saksigtra";
		$template['condition']['iddsn_sgt'] = $id;
		$this->content['template'] = $this->crud_model->get_data($template);

		$saksi1['type'] = "single";
		$saksi1['table'] = "tb_penduduk";
		$saksi1['condition']['noktp_pdk'] = $this->content['template']['niksp1_sgt'];
		$this->content['saksi1'] = $this->crud_model->get_data($saksi1);

		$saksi2['type'] = "single";
		$saksi2['table'] = "tb_penduduk";
		$saksi2['condition']['noktp_pdk'] = $this->content['template']['niksp2_sgt'];
		$this->content['saksi2'] = $this->crud_model->get_data($saksi2);

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['kecamatan']['kd_full']);

			$idkel = $id;

			$this->content['data']['title'] = "e-GTRA Desa : Form Saksi Dusun ".$this->content['kecamatan']['name_dsn'];
			$this->content['data']['subtitle'] = array(array("e-gtra","egtra"),array("Daftar Dusun","egtra/list/?search=".$this->content['kecamatan']['kd_full']),array("Form saksi","egtra/form_saksi/".$id.'?search='.$this->content['kecamatan']['kd_full']));

			$this->content['load'] = array("studiogtra/form_saksi");
			$this->load->view('adm',$this->content);
	}

	public function export2($id,$iddsn){

			define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
			require(APPPATH .'plugins/fpdf/fpdf.php');

			$ptsl['type']                   = "single";
			$ptsl['table']                  = "tb_gtra";
			$ptsl['condition']['id_gtra'] = $id;
			$data                        = $this->crud_model->get_data($ptsl);

			$pdk['table'] = "tb_penduduk";
			$pdk['type'] = "single";
			$pdk['condition']['idpdk_pdk'] = $data['idpdk_gtra'];
			$pdk['join']['table'] = "tb_pekerjaan";
			$pdk['join']['key'] = "idpkr_pkr";
			$pdk['join']['ref'] = "idpeker_pdk";
			$datpdk = $this->crud_model->get_data($pdk);

			$anak['table'] = "tb_anak";
			$anak['type'] = "multiple";
			$anak['condition']['idpdk_ank'] = $data['idpdk_gtra'];
			$datanak = $this->crud_model->get_data($anak);

			$kec['table'] = "ms_kelurahan";
			$kec['type'] = "single";
			$kec['column'] = "nma_kel,id_dsn,name_dsn,nma_kec,kd_full,type_kel,kepala_kel,sekre_kel";
			$kec['join']['table'] = 'ms_dusun,ms_kecamatan';
			$kec['join']['key'] = 'kdkel_dsn,kd_kec';
			$kec['join']['ref'] = 'kd_full,kdkec_kel';
			$kec['condition']['id_dsn'] = $iddsn;
			$kecamatan = $this->crud_model->get_data($kec);

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
			$dnop['table']                  = "tb_gtradhkp";
			$dnop['join']['table'] 				 = "tb_dhkp,tb_block";
			$dnop['join']['key'] 					 = "id_dhkp,idblk_blk";
			$dnop['join']['ref'] 					 = "iddhkp_gtra,idblk_dhkp";
			$dnop['condition']['idgtra_gtra'] = $id;
			$datanop                        = $this->crud_model->get_data($dnop);

			$rent='';$aop='';
			foreach ($datanop as $dno) {
				$nop = createkodebpkad($dno['idkel_blk']).''.$dno['nama_blk'].''.$dno['nosppt_dhkp'];
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
				$pdf -> setFont ('Times','B',13);
				$pdf -> setXY(10,$row); $pdf->Cell(0,0,"DATA INVENTARISASI DAN IDENTIFIKASI SUBYEK DAN OBYEK",0,0,'C');
				$row +=10;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=20;
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(125,$row); $pdf->Cell(0,0,"No. Urut :",0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> rect(145, 40, 55, 10);
				$pdf -> setXY(150,$row); $pdf->Cell(0,0,$data['nubdsn_gtra'].' / '.$data['ppptr_gtra'].' / '.$kecamatan['name_dsn'],0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"I. LETAK BIDANG TANAH :",0,0,'L');
				$row += 15;
				$pdf -> setFont ('Times','',11);
				$pdf -> SetLineWidth(0.25);
				$pdf -> rect(18, 55, 90, 20);
				$pdf -> rect(18, 75, 90, 38);
				$pdf -> rect(108, 55, 92, 58);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa/Kelurahan",0,0,'L');
				$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Sket Bidang Tanah",0,0,'C');
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kel'],0,0,'L');

				$row += 5;
				if($data['skets_gtra']){
					  $array = array('png','jpg','jpeg');
						$ext = explode(".",$data['skets_gtra']);
						if(in_array($ext[1], $array)){
							$pdf -> Image("./Skets/".$data['skets_gtra'],110,$row,80);
						}
				}

				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"U",0,0,'C');
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kec'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kab/Kota",0,0,'L');

				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"^",0,0,'C');
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"|",0,0,'C');
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,'Semarang',0,0,'L');
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Batas Bidang Tanah",0,0,'L');
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
				$pdf -> setXY(35,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(38,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
				$pdf -> setXY(35,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(38,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
				$pdf -> setXY(35,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(38,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
				$pdf -> setXY(35,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(38,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');


				$row +=23;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"II. TANAH GARAPAN (Obyek)",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Status Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": Tanah Negara",0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Dasar Penggarapan Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$row += 7;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='SKT'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='SK Pelepasan Kawasan Hutan'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"a. SKT",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"g. SK Pelepasan Kawasan Hutan",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='SIM'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='SK Pelepasan HGU'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"b. SIM",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"h. SK Pelepasan HGU",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='Tol Lama'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='SK Tanah Terlantar'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"c. TOL Lama",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"i. SK Tanah Terlantar",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='Surat Oper Alih Garapan'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='SK Pelepasan Aset'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"d. Surat Oper Alih Garapan",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"j. SK Pelepasan Aset",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='Surat Pernyataan Penguasaan Tanah'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='Lainnya'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"e. Surat Pernyataan Penguasaan Tanah",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"k. Lainnya ..........................(sebutkan)",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahdasar_gtra']=='Surat Izin Membuka Hutan'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahdasar_gtra']=='Tidak Ada'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"f. Surat Izin Membuka Hutan",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"l. Tidak Ada",0,0,'L');

				$row += 7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. Sumber Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$row += 7;
				$pdf -> setFont ('Times','',20);
				if($data['tanahsumber_gtra']=='Tol Lama'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahsumber_gtra']=='Tanah Terlantar'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"a. Tol Lama",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"d. Tanah Terlantar",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahsumber_gtra']=='Bekas HGU'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahsumber_gtra']=='Tanah Adat'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"b. Bekas HGU",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"e. Tanah Adat",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['tanahsumber_gtra']=='Pelepasan Kawasan Hutan'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['tanahsumber_gtra']=='Tanah Negara Lainnya'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"c. Pelepasan Kawasan Hutan",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"f. Tanah Negara Lainnya",0,0,'L');
				$row += 7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Luas ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['luastanah_gtra'].' m2',0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. Penggunaan Tanah ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$row += 7;
				$pdf -> setFont ('Times','',20);
				if($data['gunatanah_gtra']=='Lahan Sawah'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['gunatanah_gtra']=='Pemukiman'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"a. Lahan Sawah",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"d. Pemukiman",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['gunatanah_gtra']=='Tegalan'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['gunatanah_gtra']=='Lainnya'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"b. Lahan Tambak",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"e. Lainnya ..........................(sebutkan)",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['gunatanah_gtra']=='Lahan Kering'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"c. Lahan Kering",0,0,'L');
				$row += 7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. Pemanfaatan Tanah ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$row += 7;
				$pdf -> setFont ('Times','',20);
				if($data['manfaattanah_gtra']=='Sawah .......x Padi'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['manfaattanah_gtra']=='Rumah Tinggal'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"a. Sawah .......x Padi",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"d. Rumah Tinggal",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['manfaattanah_gtra']=='Tegalan'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($data['manfaattanah_gtra']=='Lainnya'){
					$pdf -> setXY(102.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"b. Tegalan",0,0,'L');
				$pdf -> setXY(104,$row); $pdf->Cell(0,0,"e. Lainnya ..........................(sebutkan)",0,0,'L');
				$row += 5;
				$pdf -> setFont ('Times','',20);
				if($data['manfaattanah_gtra']=='Kebun Campuran'){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"c. Kebun Campuran",0,0,'L');
				$row += 7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7. Nilai Tanah * saat ini Rp ".$data['nilaitanah_gtra'].' /per m2',0,0,'L');

				$pdf -> AddPage();
				$row =23;

				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"III. PENGGARAP (Subyek)",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Nama",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['nma_pdk'],0,0,'L');
				$row += 5;
				$from = new DateTime($datpdk['ttg_pdk']);
				$to   = new DateTime();
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Tempat/Tanggal lahir/Umur",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['ttl_pdk'].' / '.fdate($datpdk['ttg_pdk'],'DDMMYYYY').' / '.$from->diff($to)->y.' Tahun',0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. NIK",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['noktp_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Nomor Kartu Keluarga ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['nokk_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. Jumlah Keluarga yang masih menjadi tanggungan ",0,0,'L');
				$row += 5;
				$pdf -> setXY(25,$row); $pdf->Cell(0,0,"a. Nama Istri/Suami ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['pasangan_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(25,$row); $pdf->Cell(0,0,"a. Nama Anak/Usia ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$noa=1;
				foreach ($datanak as $dl) {
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$noa.". ".$dl['nama_ank'],0,0,'L');
					$row += 5;
					$noa++;
				}
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. Alamat KTP",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Desa '.$datpdk['kel_pdk'].' Kecamatan '.$datpdk['kec_pdk'].' Kab/Kota '.$datpdk['kab_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7. Alamat Domisili",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Desa/Kelurahan '.$datpdk['domkel_pdk'].' Kecamatan '.$datpdk['domkec_pdk'].' Kab/Kota '.$datpdk['domkab_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8. Pekerjaan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': ',0,0,'L');
				$row += 7;

				$pdf -> setFont ('Times','',20);
				if($datpdk['idpeker_pdk']==9){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==91){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==92){
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"a. Petani",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"h. Penggarap Lahan Budidaya",0,0,'L');
				$pdf -> setXY(144,$row); $pdf->Cell(0,0,"o.",0,0,'L');
				$pdf -> setXY(148,$row-2); $pdf->MultiCell(45,5,"Pekerja sector informal yang tidak memiliki tanah",0,'L');
				$row += 12;
				$pdf -> setFont ('Times','',20);

				if($datpdk['idpeker_pdk']==90){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==93){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==94){
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}

				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"b. Petani Penggarap",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"i. Petambak Garam Kecil",0,0,'L');
				$pdf -> setXY(144,$row); $pdf->Cell(0,0,"p.",0,0,'L');
				$pdf -> setXY(148,$row-2); $pdf->MultiCell(45,5,"Pegawai tidak tetap yang tidak memiliki tanah",0,'L');
				$row += 12;
				$pdf -> setFont ('Times','',20);

				if($datpdk['idpeker_pdk']==20){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==95){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==96){
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}

				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"c. Buruh Tani",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"i. Penggarap Tambah Garap",0,0,'L');
				$pdf -> setXY(144,$row); $pdf->Cell(0,0,"q.",0,0,'L');
				$pdf -> setXY(148,$row-2); $pdf->MultiCell(45,5,"Pegawai swasta yang tidak memiliki tanah",0,'L');
				$row += 12;
				$pdf -> setFont ('Times','',20);
				if($datpdk['idpeker_pdk']==11){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==97){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==98){
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"d. Nelayan Kecil",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"k. Guru Honorer",0,0,'L');
				$pdf -> setXY(144,$row); $pdf->Cell(0,0,"r.",0,0,'L');
				$pdf -> setXY(148,$row-2); $pdf->MultiCell(45,5,"PNS paling tinggi golongan III yang tidak memiliki tanah",0,'L');
				$row += 17;
				$pdf -> setFont ('Times','',20);
				if($datpdk['idpeker_pdk']==99){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==100){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==101){
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"e. Nelayan Tradisional",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"i.",0,0,'L');
				$pdf -> setXY(88,$row-2); $pdf->MultiCell(45,5,"Pekerja Harian Lepas yang tidak memiliki tanah",0,'L');
				$pdf -> setXY(144,$row); $pdf->Cell(0,0,"s.",0,0,'L');
				$pdf -> setXY(148,$row-2); $pdf->MultiCell(45,5,"TNI/Polri berpangkat paling tinggi Letda/Ipda yang tidak memiliki tanah",0,'L');
				$row += 17;
				$pdf -> setFont ('Times','',20);
				$arraypekerjaan = array(9,91,92,90,93,94,20,95,96,11,97,98,99,100,101,21,102,103,104);
				if($datpdk['idpeker_pdk']==21){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==102){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if (!in_array($datpdk['idpeker_pdk'], $arraypekerjaan)) {
					$pdf -> setXY(142.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"f. Nelayan Buruh",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"m.",0,0,'L');
				$pdf -> setXY(88,$row-2); $pdf->MultiCell(45,5,"Buruh yang tidak memiliki tanah",0,'L');
				if (!in_array($datpdk['idpeker_pdk'], $arraypekerjaan)) {
					$pdf -> setXY(144,$row); $pdf->Cell(0,0,"t. ".$datpdk['nama_pkr'],0,0,'L');
				}

				$row += 12;
				$pdf -> setFont ('Times','',20);
				if($datpdk['idpeker_pdk']==103){
					$pdf -> setXY(22.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				if($datpdk['idpeker_pdk']==104){
					$pdf -> setXY(82.5,$row+0.5); $pdf->Cell(0,0,"O",0,0,'L');
				}
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"f. Pembudidaya Ikan",0,0,'L');
				$pdf -> setXY(84,$row); $pdf->Cell(0,0,"m.",0,0,'L');
				$pdf -> setXY(88,$row-2); $pdf->MultiCell(45,5,"Pedagang Informal yang tidak memiliki tanah",0,'L');

				$row += 12;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8. Penghasilan Perbulan",0,0,'L');

				if($datpdk['penghasilan_pdk']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$datpdk['penghasilan_pdk'],0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': ',0,0,'L');
				}


				$pdf -> AddPage();
				$row =23;

				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"IV. PENGUASAAN TANAH GARAPAN",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Cara Pengusahaan",0,0,'L');
				if($data['kuasacara_gtra']==2){
					$pdf -> setXY(73,$row);
					$pdf->Cell(11,0,"",1,1,'L');
				}else if($data['kuasacara_gtra']==1){
					$pdf -> setXY(86,$row);
					$pdf->Cell(17,0,"",1,1,'L');
				}

				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.status(1,'kuasacara').'/'.status(2,'kuasacara'),0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Tanaman Dominan yang ada",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['tanamankuasa_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Peruntukan & Penggunaan Tanah saat ini",0,'L');

				if($data['gunakuasa_gtra']==1){
					$pdf -> setXY(89,$row);
					$pdf->Cell(22,0,"",1,1,'L');
					$pdf -> setXY(112,$row);
					$pdf->Cell(13,0,"",1,1,'L');
				}else if($data['gunakuasa_gtra']==2){
					$pdf -> setXY(73,$row);
					$pdf->Cell(15,0,"",1,1,'L');
					$pdf -> setXY(112,$row);
					$pdf->Cell(13,0,"",1,1,'L');
				}else if($data['gunakuasa_gtra']==3){
					$pdf -> setXY(73,$row);
					$pdf->Cell(15,0,"",1,1,'L');
					$pdf -> setXY(89,$row);
					$pdf->Cell(22,0,"",1,1,'L');
				}

				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.status(1,'gunakuasa').'/'.status(2,'gunakuasa').'/'.status(3,'gunakuasa'),0,0,'L');
				$row += 10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Tahun Penggarapan",0,0,'L');
				if($data['tahunkuasa_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['tahunkuasa_gtra'],0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': ',0,0,'L');
				}

				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Dasar Penguasaan tanah garapan",0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['dasarkuasa_gtra'],0,0,'L');

				$row += 15;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"V. LAIN-LAIN",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Tanah yang telah dimiliki",0,0,'L');

				if($data['lainluasgarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = '.$data['lainluasgarap_gtra'].' m2',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = ',0,0,'L');
				}

				$row += 5;

				if($data['lainbidanggarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = '.$data['lainbidanggarap_gtra'].' bidang',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = ',0,0,'L');
				}

				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Terletak di",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainletak_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Desa / Kelurahan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['laindesa_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainkecamatan_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainkabupaten_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Luas Tanah yang digarap + luas Tanah telah dimiliki",0,'L');
				if($data['lainluasgarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = '.$data['lainluasgarap_gtra'].' m2',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = ',0,0,'L');
				}

				$row += 5;

				if($data['lainbidanggarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = '.$data['lainbidanggarap_gtra'].' bidang',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = ',0,0,'L');
				}

				$row += 30;
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,"Petugas",0,0,'L');
				$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Penggarap Tanah",0,0,'C');
				$row += 30;
				$pdf -> setXY(45,$row); $pdf->Cell(0,0,"......................",0,0,'L');
				$pdf -> setXY(90,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PENGUASAAN FISIK BIDANG TANAH",0,'C');
				$row +=15;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=8;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nomor KTP");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa saya dengan etika baik telah menguasai sebidang tanah yang terletak di :");
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainletak_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['laindesa_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkecamatan_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkabupaten_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Status Tanah");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,"Tanah Negara Bebas");
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dipergunakan untuk");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,status($data['gunakuasa_gtra'],'gunakuasa'));
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Batas-batas tanah :");
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['utara_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['timur_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['selatan_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['barat_gtra']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Bidang tanah tersebut saya peroleh dari : '.$data['tanahsumber_gtra'].' Sejak tahun 1990 yang berasal dari tanah negara dengan cara Hibah, yang sampai saat ini saya kuasai secara terus menerus, tidak dijadikan / menjadi jaminan sesuatu hutang, tidak dalam sengketa, serta belum pernah diterbitkan Sertipikatnya.');
				$row +=15;
				$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Surat pernyataan ini saya buat dengan sebenarnya dengan pernah tanggung jawab dan saya bersedia untuk mengangkat sumpah bila diperlukan. Apabila ternyata ini tidak benar, saya bersedia dituntut di hadapan pihak-pihak yang berwenang, dengan saksi :');
				$row +=20;

				$namasaksi1 = '';$tglsaksi1='';$pkrsaksi1='';$alamatsaksi1='';
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp1_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi1 = $this->crud_model->get_data($pdk);

				if($datsaksi1){
					$namasaksi1 = $datsaksi1['nma_pdk'];
					$tglsaksi1=fdate($datsaksi1['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi1=$datsaksi1['nama_pkr'];
					$alamatsaksi1=$datsaksi1['almat_pdk'].' RT.'.$datsaksi1['rt_pdk'].' RW.'.$datsaksi1['rw_pdk'].' Desa '.$datsaksi1['kel_pdk'].' Kecamatan '.$datsaksi1['kec_pdk'].' Kabupaten '.$datsaksi1['kab_pdk'];
				}

				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$alamatsaksi1);

				$namasaksi2 = '';$tglsaksi2='';$pkrsaksi2='';$alamatsaksi2='';$kades=$kecamatan['kepala_kel'];
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp2_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi2 = $this->crud_model->get_data($pdk);

				if($datsaksi2){
					$namasaksi2 = $datsaksi2['nma_pdk'];
					$tglsaksi2=fdate($datsaksi2['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi2=$datsaksi2['nama_pkr'];
					$alamatsaksi2=$datsaksi2['almat_pdk'].' RT.'.$datsaksi2['rt_pdk'].' RW.'.$datsaksi2['rw_pdk'].' Desa '.$datsaksi2['kel_pdk'].' Kecamatan '.$datsaksi2['kec_pdk'].' Kabupaten '.$datsaksi2['kab_pdk'];
				}

				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$alamatsaksi2);

				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Saksi - saksi :");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang membuat pernyataan,",0,0,'C');
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ".$namasaksi1." (..........)");
				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',12);
				$row +=15;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ".$namasaksi2." (..........)");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
				$row +=5;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Mengetahui,",0,0,'C');
				$row +=5;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
				$row +=25;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,$kades,0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS DAN LUAS TANAH",0,'C');
				$row +=15;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=8;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->MultiCell(170,5,"Pemilik / Penggarap Tanah Negara Luas +- ".$data['luastanah_gtra'].' m2, terletak di Desa '.$kecamatan['nma_kel'].', Kecamatan '.$kecamatan['nma_kec'].' Kabupaten Semarang.');
				$row +=12;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan, jika perlu kami mengangkat sumpah, bahwa :");
				$row +=6;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tanda batas tanah yang kami mohonkan sertipikatnya di Kantor pertanahan Kabupaten Semarang, telah benar-benar kami pasang sesuai dengan PMNA / KBPN No. 3 / 1997 berupa Tugu Beton / Pipa / Besi / Kayu Jati *)......................");
				$pdf -> setXY(170,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(18,0,"",1,1,'L');
				$pdf -> setXY(20,$row+10.5);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(18,0,"",1,1,'L');
				$row +=16;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Pemasangan tanda batas tersebut disaksikan dan disetujui oleh para pemilik tanah yang berbatasan dengan menanda tangani Surat Pernyataan ini, serta disaksikan oleh Kepala Desa/ Lurah / Pamong / RT / RW *) setempat");
				$pdf -> setXY(120,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(30,0,"",1,1,'L');
				$pdf -> setXY(170,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(15,0,"",1,1,'L');
				$row +=16;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Dengan dipasangnya tanda batas tersebut, apabila ternyata :");
				$row +=6;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a.");
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Terdapat perbedaan luas dengan bukti pemilikan / penguasaan tersebut di atas dengan luas hasil pengukuran Petugas Kantor Pertanahan Kabupaten Semarang, maka kami menyetujui dan tidak mempermasalahkannya.");
				$row +=16;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b.");
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Dikemudian hari ada yang merasa dirugikan, kami sanggup mengembalikan luas tanah seperti semula tanpa menuntut ganti rugi.");
				$row +=12;
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini kami buat dengan sungguh-sungguh dan dipergunakan sebagai lampiran");
				$row +=5;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"permohonan pengukuran. Apabila isi dari pernyataan ini ternyata tidak benar, kami bersedia dituntut dimuka hakim baik secara pidana maupun secara perdata, karena memberi pernyataan palsu.");
				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=9;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Persetujuan Pemilik Tanah yang berbatasan");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"PEMOHON,",0,0,'C');
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"yang sanggup diangkat sumpah :");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['utara_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['timur_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['selatan_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['barat_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");

				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row-16); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
				$row +=15;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Saksi pemasangan Tanda Batas",0,0,'L');
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"MENGETAHUI,",0,0,'C');
				$row +=5;
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
				$row +=25;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,$namasaksi1,0,0,'L');
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,$kades,0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN",0,0,'C');
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"PESERTA REDISTRIBUSI TANAH OBYEK LANDREFORM",0,0,'C');
				$row +=10;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat, Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk'].','.fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat Tinggal");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa :");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Memiliki pekerjaan/profesi di KTP adalah sebagai ".$datpdk['nama_pkr']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Telah berusia lebih dari 18 (delapan belas) tahun dan belum / sudah *) menikah.");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tidak memiliki/menguasai tanah yang melebihi ketentuan maksimum dan tanah absentee");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Akan mengusahakan secara aktif tanah obyek landreform yang akan diberikan kepada saya.");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"e.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Bersedia menjadi calon penerima redistribusi tanah obyek landreform dan akan memenuhi seluruh kewajiban, serta mematuhi segaal hal yang telah ditetapkan sesuai ketentuan yang berlaku.");
				$row +=12;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Disamping hal di atas, saya juga memiliki pekerjaan sebagai PETANI dan mengusahakan tanah pertanian.");
				$row +=11;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Apabila di kemudian hari ternyata saya melakukan kebohongan dan pernyataan saya ini dan saya dinyatakan melanggar ketentuan landreform, maka saya bersedia untuk mengembalikan tanah obyek landreform yang telah saya kuasai/miliki kepada negara dan membayar seluruh biaya yang diakibatkannya.");
				$row +=21;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini saya buat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.");
				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=9;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'L');
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan,",0,0,'C');
				$row +=5;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'L');
				$row +=10;
				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',11);
				$row +=15;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,$kades,0,0,'L');
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

			$pdf->Output();
		}

	public function export($id,$iddsn){

			define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
			require(APPPATH .'plugins/fpdf/fpdf.php');

			$ptsl['type']                   = "single";
			$ptsl['table']                  = "tb_gtra";
			$ptsl['condition']['id_gtra'] = $id;
			$data                        = $this->crud_model->get_data($ptsl);

			$pdk['table'] = "tb_penduduk";
			$pdk['type'] = "single";
			$pdk['condition']['idpdk_pdk'] = $data['idpdk_gtra'];
			$pdk['join']['table'] = "tb_pekerjaan";
			$pdk['join']['key'] = "idpkr_pkr";
			$pdk['join']['ref'] = "idpeker_pdk";
			$datpdk = $this->crud_model->get_data($pdk);

			$anak['table'] = "tb_anak";
			$anak['type'] = "multiple";
			$anak['condition']['idpdk_ank'] = $data['idpdk_gtra'];
			$datanak = $this->crud_model->get_data($anak);

			$kec['table'] = "ms_kelurahan";
			$kec['type'] = "single";
			$kec['column'] = "nma_kel,id_dsn,name_dsn,nma_kec,kd_full,type_kel,kepala_kel,sekre_kel";
			$kec['join']['table'] = 'ms_dusun,ms_kecamatan';
			$kec['join']['key'] = 'kdkel_dsn,kd_kec';
			$kec['join']['ref'] = 'kd_full,kdkec_kel';
			$kec['condition']['id_dsn'] = $iddsn;
			$kecamatan = $this->crud_model->get_data($kec);

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
			$dnop['table']                  = "tb_gtradhkp";
			$dnop['join']['table'] 				 = "tb_dhkp,tb_block";
			$dnop['join']['key'] 					 = "id_dhkp,idblk_blk";
			$dnop['join']['ref'] 					 = "iddhkp_gtra,idblk_dhkp";
			$dnop['condition']['idgtra_gtra'] = $id;
			$datanop                        = $this->crud_model->get_data($dnop);

			$rent='';$aop='';
			foreach ($datanop as $dno) {
				$nop = createkodebpkad($dno['idkel_blk']).''.$dno['nama_blk'].''.$dno['nosppt_dhkp'];
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
				$pdf -> setFont ('Times','B',13);
				$pdf -> setXY(10,$row); $pdf->Cell(0,0,"DATA INVENTARISASI DAN IDENTIFIKASI SUBYEK DAN OBYEK",0,0,'C');
				$row +=10;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=20;
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(125,$row); $pdf->Cell(0,0,"No. Urut :",0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> rect(145, 40, 55, 10);
				$pdf -> setXY(150,$row); $pdf->Cell(0,0,$data['nubdsn_gtra'].' / '.$data['ppptr_gtra'].' / '.$kecamatan['name_dsn'],0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"I. LETAK BIDANG TANAH :",0,0,'L');
				$row += 15;
				$pdf -> setFont ('Times','',11);
				$pdf -> SetLineWidth(0.25);
				$pdf -> rect(18, 55, 90, 20);
				$pdf -> rect(18, 75, 90, 38);
				$pdf -> rect(108, 55, 92, 58);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa/Kelurahan",0,0,'L');
				$pdf -> setXY(110,$row); $pdf->Cell(0,0,"Sket Bidang Tanah",0,0,'C');
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kel'],0,0,'L');

				$row += 5;
				if($data['skets_gtra']){
					$array = array('png','jpg','jpeg');
					$ext = explode(".",$data['skets_gtra']);
					if(in_array($ext[1], $array)){
						$pdf -> Image("./Skets/".$data['skets_gtra'],110,$row,80);
					}
				}

				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"U",0,0,'C');
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$kecamatan['nma_kec'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kab/Kota",0,0,'L');

				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"^",0,0,'C');
				$pdf -> setXY(190,$row); $pdf->Cell(0,0,"|",0,0,'C');
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,'Semarang',0,0,'L');
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Batas Bidang Tanah",0,0,'L');
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
				$pdf -> setXY(36,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(39,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
				$pdf -> setXY(36,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(39,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
				$pdf -> setXY(36,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(39,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
				$pdf -> setXY(36,$row); $pdf->Cell(0,0,":",0,0,'L');
				$pdf -> setXY(39,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');


				$row +=23;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"II. TANAH GARAPAN (Obyek)",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Status Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": Tanah Negara",0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Dasar Penggarapan Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['tanahdasar_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. Sumber Tanah",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['tanahsumber_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Luas ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['luastanah_gtra'].' m2',0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. Penggunaan Tanah ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['gunatanah_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. Pemanfaatan Tanah ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$data['manfaattanah_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7. Nilai Tanah * saat ini Rp ".$data['nilaitanah_gtra'].' /per m2',0,0,'L');

				$row +=13;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"III. PENGGARAP (Subyek)",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Nama",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['nma_pdk'],0,0,'L');
				$row += 5;
				$from = new DateTime($datpdk['ttg_pdk']);
				$to   = new DateTime();
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Tempat/Tanggal lahir/Umur",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['ttl_pdk'].' / '.fdate($datpdk['ttg_pdk'],'DDMMYYYY').' / '.$from->diff($to)->y.' Tahun',0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. NIK",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['noktp_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Nomor Kartu Keluarga ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['nokk_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. Jumlah Keluarga yang masih menjadi tanggungan ",0,0,'L');
				$row += 5;
				$pdf -> setXY(25,$row); $pdf->Cell(0,0,"a. Nama Istri/Suami ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ".$datpdk['pasangan_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(25,$row); $pdf->Cell(0,0,"a. Nama Anak/Usia ",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,": ",0,0,'L');
				$noa=1;
				foreach ($datanak as $dl) {
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,$noa.". ".$dl['nama_ank'],0,0,'L');
					$row += 5;
					$noa++;
				}
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. Alamat KTP",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Desa '.$datpdk['kel_pdk'].' Kecamatan '.$datpdk['kec_pdk'].' Kab/Kota '.$datpdk['kab_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7. Alamat Domisili",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Desa/Kelurahan '.$datpdk['domkel_pdk'].' Kecamatan '.$datpdk['domkec_pdk'].' Kab/Kota '.$datpdk['domkab_pdk'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8. Pekerjaan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$datpdk['nama_pkr'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"8. Penghasilan Perbulan",0,0,'L');

				if($datpdk['penghasilan_pdk']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$datpdk['penghasilan_pdk'],0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': ',0,0,'L');
				}


				$row +=13;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"IV. PENGUASAAN TANAH GARAPAN",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Cara Pengusahaan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.status($data['kuasacara_gtra'],'kuasacara'),0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Tanaman Dominan yang ada",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['tanamankuasa_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Peruntukan & Penggunaan Tanah saat ini",0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.status($data['gunakuasa_gtra'],'gunakuasa'),0,0,'L');
				$row += 10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Tahun Penggarapan",0,0,'L');
				if($data['tahunkuasa_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['tahunkuasa_gtra'],0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': ',0,0,'L');
				}

				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Dasar Penguasaan tanah garapan",0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['dasarkuasa_gtra'],0,0,'L');

				$pdf -> AddPage();
				$row =23;
				$pdf -> setFont ('Times','B',11);
				$pdf -> setXY(13,$row); $pdf->Cell(0,0,"V. LAIN-LAIN",0,0,'L');
				$pdf -> setFont ('Times','',11);
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Tanah yang telah dimiliki",0,0,'L');

				if($data['lainluasgarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = '.$data['lainluasgarap_gtra'].' m2',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = ',0,0,'L');
				}

				$row += 5;

				if($data['lainbidanggarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = '.$data['lainbidanggarap_gtra'].' bidang',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = ',0,0,'L');
				}

				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Terletak di",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainletak_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Desa / Kelurahan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['laindesa_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainkecamatan_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(24,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,': '.$data['lainkabupaten_gtra'],0,0,'L');
				$row += 5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ",0,0,'L');
				$pdf -> setXY(24,$row-2); $pdf->MultiCell(50,5,"Luas Tanah yang digarap + luas Tanah telah dimiliki",0,'L');
				if($data['lainluasgarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = '.$data['lainluasgarap_gtra'].' m2',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Luas      = ',0,0,'L');
				}

				$row += 5;

				if($data['lainbidanggarap_gtra']){
						$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = '.$data['lainbidanggarap_gtra'].' bidang',0,0,'L');
				}else{
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,': Bidang  = ',0,0,'L');
				}

				$row += 30;
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,"Petugas",0,0,'L');
				$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Penggarap Tanah",0,0,'C');
				$row += 30;
				$pdf -> setXY(45,$row); $pdf->Cell(0,0,"......................",0,0,'L');
				$pdf -> setXY(90,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PENGUASAAN FISIK BIDANG TANAH",0,'C');
				$row +=15;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=8;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nomor KTP");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa saya dengan etika baik telah menguasai sebidang tanah yang terletak di :");
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainletak_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['laindesa_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkecamatan_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkabupaten_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Status Tanah");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,"Tanah Negara Bebas");
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dipergunakan untuk");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,status($data['gunakuasa_gtra'],'gunakuasa'));
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Batas-batas tanah :");
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['utara_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['timur_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['selatan_gtra']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['barat_gtra']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Bidang tanah tersebut saya peroleh dari : '.$data['tanahsumber_gtra'].' Sejak tahun 1990 yang berasal dari tanah negara dengan cara Hibah, yang sampai saat ini saya kuasai secara terus menerus, tidak dijadikan / menjadi jaminan sesuatu hutang, tidak dalam sengketa, serta belum pernah diterbitkan Sertipikatnya.');
				$row +=15;
				$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Surat pernyataan ini saya buat dengan sebenarnya dengan pernah tanggung jawab dan saya bersedia untuk mengangkat sumpah bila diperlukan. Apabila ternyata ini tidak benar, saya bersedia dituntut di hadapan pihak-pihak yang berwenang, dengan saksi :');
				$row +=20;

				$namasaksi1 = '';$tglsaksi1='';$pkrsaksi1='';$alamatsaksi1='';
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp1_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi1 = $this->crud_model->get_data($pdk);

				if($datsaksi1){
					$namasaksi1 = $datsaksi1['nma_pdk'];
					$tglsaksi1=fdate($datsaksi1['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi1=$datsaksi1['nama_pkr'];
					$alamatsaksi1=$datsaksi1['almat_pdk'].' RT.'.$datsaksi1['rt_pdk'].' RW.'.$datsaksi1['rw_pdk'].' Desa '.$datsaksi1['kel_pdk'].' Kecamatan '.$datsaksi1['kec_pdk'].' Kabupaten '.$datsaksi1['kab_pdk'];
				}

				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi1);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$alamatsaksi1);

				$namasaksi2 = '';$tglsaksi2='';$pkrsaksi2='';$alamatsaksi2='';$kades=$kecamatan['kepala_kel'];
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp2_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi2 = $this->crud_model->get_data($pdk);

				if($datsaksi2){
					$namasaksi2 = $datsaksi2['nma_pdk'];
					$tglsaksi2=fdate($datsaksi2['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi2=$datsaksi2['nama_pkr'];
					$alamatsaksi2=$datsaksi2['almat_pdk'].' RT.'.$datsaksi2['rt_pdk'].' RW.'.$datsaksi2['rw_pdk'].' Desa '.$datsaksi2['kel_pdk'].' Kecamatan '.$datsaksi2['kec_pdk'].' Kabupaten '.$datsaksi2['kab_pdk'];
				}

				$row +=10;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi2);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$alamatsaksi2);

				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Saksi - saksi :");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang membuat pernyataan,",0,0,'C');
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ".$namasaksi1." (..........)");
				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',12);
				$row +=15;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ".$namasaksi2." (..........)");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
				$row +=5;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Mengetahui,",0,0,'C');
				$row +=5;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
				$row +=25;
				$pdf -> setXY(0,$row); $pdf->Cell(0,0,$kades,0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS DAN LUAS TANAH",0,'C');
				$row +=15;
				$pdf -> SetLineWidth(1);
				$pdf -> setXY(15,$row);
				$pdf->Cell(0,0,"",1,1,'C');
				$row +=8;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=10;
				$pdf -> setXY(15,$row); $pdf->MultiCell(170,5,"Pemilik / Penggarap Tanah Negara Luas +- ".$data['luastanah_gtra'].' m2, terletak di Desa '.$kecamatan['nma_kel'].', Kecamatan '.$kecamatan['nma_kec'].' Kabupaten Semarang.');
				$row +=12;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan, jika perlu kami mengangkat sumpah, bahwa :");
				$row +=6;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tanda batas tanah yang kami mohonkan sertipikatnya di Kantor pertanahan Kabupaten Semarang, telah benar-benar kami pasang sesuai dengan PMNA / KBPN No. 3 / 1997 berupa Tugu Beton / Pipa / Besi / Kayu Jati *)......................");
				$pdf -> setXY(170,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(18,0,"",1,1,'L');
				$pdf -> setXY(20,$row+10.5);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(18,0,"",1,1,'L');
				$row +=16;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Pemasangan tanda batas tersebut disaksikan dan disetujui oleh para pemilik tanah yang berbatasan dengan menanda tangani Surat Pernyataan ini, serta disaksikan oleh Kepala Desa/ Lurah / Pamong / RT / RW *) setempat");
				$pdf -> setXY(120,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(30,0,"",1,1,'L');
				$pdf -> setXY(170,$row+6);
				$pdf -> SetLineWidth(0.5);
				$pdf->Cell(15,0,"",1,1,'L');
				$row +=16;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Dengan dipasangnya tanda batas tersebut, apabila ternyata :");
				$row +=6;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a.");
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Terdapat perbedaan luas dengan bukti pemilikan / penguasaan tersebut di atas dengan luas hasil pengukuran Petugas Kantor Pertanahan Kabupaten Semarang, maka kami menyetujui dan tidak mempermasalahkannya.");
				$row +=16;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b.");
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Dikemudian hari ada yang merasa dirugikan, kami sanggup mengembalikan luas tanah seperti semula tanpa menuntut ganti rugi.");
				$row +=12;
				$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini kami buat dengan sungguh-sungguh dan dipergunakan sebagai lampiran");
				$row +=5;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"permohonan pengukuran. Apabila isi dari pernyataan ini ternyata tidak benar, kami bersedia dituntut dimuka hakim baik secara pidana maupun secara perdata, karena memberi pernyataan palsu.");
				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=9;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Persetujuan Pemilik Tanah yang berbatasan");
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"PEMOHON,",0,0,'C');
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"yang sanggup diangkat sumpah :");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['utara_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['timur_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['selatan_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat ");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['barat_gtra']);
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");

				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row-16); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
				$row +=15;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Saksi pemasangan Tanda Batas",0,0,'L');
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"MENGETAHUI,",0,0,'C');
				$row +=5;
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
				$row +=25;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,$namasaksi1,0,0,'L');
				$pdf -> setXY(100,$row); $pdf->Cell(0,0,$kades,0,0,'C');

				$pdf -> AddPage();
				$row =20;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN",0,0,'C');
				$row +=7;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"PESERTA REDISTRIBUSI TANAH OBYEK LANDREFORM",0,0,'C');
				$row +=10;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=7;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
				$row +=5;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat, Tanggal Lahir");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk'].','.fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat Tinggal");
				$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa :");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Memiliki pekerjaan/profesi di KTP adalah sebagai ".$datpdk['nama_pkr']);
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Telah berusia lebih dari 18 (delapan belas) tahun dan belum / sudah *) menikah.");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tidak memiliki/menguasai tanah yang melebihi ketentuan maksimum dan tanah absentee");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Akan mengusahakan secara aktif tanah obyek landreform yang akan diberikan kepada saya.");
				$row +=7;
				$pdf -> setXY(15,$row); $pdf->Cell(0,0,"e.");
				$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Bersedia menjadi calon penerima redistribusi tanah obyek landreform dan akan memenuhi seluruh kewajiban, serta mematuhi segaal hal yang telah ditetapkan sesuai ketentuan yang berlaku.");
				$row +=12;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Disamping hal di atas, saya juga memiliki pekerjaan sebagai PETANI dan mengusahakan tanah pertanian.");
				$row +=11;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Apabila di kemudian hari ternyata saya melakukan kebohongan dan pernyataan saya ini dan saya dinyatakan melanggar ketentuan landreform, maka saya bersedia untuk mengembalikan tanah obyek landreform yang telah saya kuasai/miliki kepada negara dan membayar seluruh biaya yang diakibatkannya.");
				$row +=21;
				$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini saya buat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.");
				$row +=10;
				$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
				$row +=9;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'L');
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan,",0,0,'C');
				$row +=5;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'L');
				$row +=10;
				$pdf->SetFont('Times','',7);
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
				$pdf->SetFont('Times','',11);
				$row +=15;
				$pdf -> setXY(30,$row); $pdf->Cell(0,0,$kades,0,0,'L');
				$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

			$pdf->Output();
		}

		public function exporttwo($id,$iddsn){

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				require(APPPATH .'plugins/fpdf/fpdf.php');

				$ptsl['type']                   = "single";
				$ptsl['table']                  = "tb_gtra";
				$ptsl['condition']['id_gtra'] = $id;
				$data                        = $this->crud_model->get_data($ptsl);

				$pdka['table'] = "tb_penduduk";
				$pdka['type'] = "single";
				$pdka['condition']['idpdk_pdk'] = $data['idpdk_gtra'];
				$pdka['join']['table'] = "tb_pekerjaan";
				$pdka['join']['key'] = "idpkr_pkr";
				$pdka['join']['ref'] = "idpeker_pdk";
				$datpdk = $this->crud_model->get_data($pdka);

				$anak['table'] = "tb_anak";
				$anak['type'] = "multiple";
				$anak['condition']['idpdk_ank'] = $data['idpdk_gtra'];
				$datanak = $this->crud_model->get_data($anak);


				$kec['table'] = "ms_kelurahan";
				$kec['type'] = "single";
				$kec['column'] = "nma_kel,id_dsn,name_dsn,nma_kec,kd_full,type_kel,kepala_kel,sekre_kel";
				$kec['join']['table'] = 'ms_dusun,ms_kecamatan';
				$kec['join']['key'] = 'kdkel_dsn,kd_kec';
				$kec['join']['ref'] = 'kd_full,kdkec_kel';
				$kec['condition']['id_dsn'] = $iddsn;
				$kecamatan = $this->crud_model->get_data($kec);

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
				$dnop['table']                  = "tb_gtradhkp";
				$dnop['join']['table'] 				 = "tb_dhkp,tb_block";
				$dnop['join']['key'] 					 = "id_dhkp,idblk_blk";
				$dnop['join']['ref'] 					 = "iddhkp_gtra,idblk_dhkp";
				$dnop['condition']['idgtra_gtra'] = $id;
				$datanop                        = $this->crud_model->get_data($dnop);

				$rent='';$aop='';
				foreach ($datanop as $dno) {
					$nop = createkodebpkad($dno['idkel_blk']).''.$dno['nama_blk'].''.$dno['nosppt_dhkp'];
					$aop = $dno['aopsppt_dhkp'];
					$rent .= $nop.', ';
				}

				$birthdate = new DateTime($datpdk['ttg_pdk']);
				$today= new DateTime(date("Y-m-d"));
				$age = $birthdate->diff($today)->y;

				$namasaksi1 = '';$tglsaksi1='';$pkrsaksi1='';$alamatsaksi1='';
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp1_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi1 = $this->crud_model->get_data($pdk);

				if($datsaksi1){
					$namasaksi1 = $datsaksi1['nma_pdk'];
					$tglsaksi1=fdate($datsaksi1['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi1=$datsaksi1['nama_pkr'];
					$alamatsaksi1=$datsaksi1['almat_pdk'].' RT.'.$datsaksi1['rt_pdk'].' RW.'.$datsaksi1['rw_pdk'].' Desa '.$datsaksi1['kel_pdk'].' Kecamatan '.$datsaksi1['kec_pdk'].' Kabupaten '.$datsaksi1['kab_pdk'];
				}

				$namasaksi2 = '';$tglsaksi2='';$pkrsaksi2='';$alamatsaksi2='';$kades=$kecamatan['kepala_kel'];
				$pdk['table'] = "tb_penduduk";
				$pdk['type']  = "single";
				$pdk['condition']['iddsn_sgt'] = $kecamatan['id_dsn'];
				$pdk['join']['table'] = "tb_saksigtra,tb_pekerjaan";
				$pdk['join']['key']   = "niksp2_sgt,idpkr_pkr";
				$pdk['join']['ref']   = "noktp_pdk,idpeker_pdk";
				$datsaksi2 = $this->crud_model->get_data($pdk);

				if($datsaksi2){
					$namasaksi2 = $datsaksi2['nma_pdk'];
					$tglsaksi2=fdate($datsaksi2['ttg_pdk'],'DDMMYYYY');
					$pkrsaksi2=$datsaksi2['nama_pkr'];
					$alamatsaksi2=$datsaksi2['almat_pdk'].' RT.'.$datsaksi2['rt_pdk'].' RW.'.$datsaksi2['rw_pdk'].' Desa '.$datsaksi2['kel_pdk'].' Kecamatan '.$datsaksi2['kec_pdk'].' Kabupaten '.$datsaksi2['kab_pdk'];
				}

				$user = $this->auth_model->get_userdata();

					$pdf = new FPDF('p','mm',array(210,330));
					$pdf -> AddPage();
					$pdf -> setDisplayMode ('fullpage');
					$row = 15;
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Kepada :",0,0,'C');
					$row += 7;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Yth.    Bapak Kepala Kantor Pertanahan",0,0,'C');
					$row += 5;
					$pdf -> setXY(122,$row); $pdf->Cell(0,0," Kabupaten Semarang",0,0,'L');
					$row += 7;
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,"di",0,0,'L');
					$row += 5;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"UNGARAN",0,0,'C');
					$row += 5;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Dengan Hormat,",0,0,'L');
					$row += 5;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :",0,0,'L');
					$row += 7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanggal Lahir",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'),0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Nomor KTP",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row-2); $pdf->MultiCell(150,5,$datpdk['almat_pdk'].' Desa '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk']);
					$row += 12;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Dalam hal ini bertindak untuk dan atas nama diri sendiri / selaku kuasa dari :",0,0,'L');
					$pdf -> SetLineWidth(0.3);
					$pdf -> setXY(105,$row);
					$pdf->Cell(25,0,"",1,1,'C');
					$pdf -> setXY(105,$row+1);
					$pdf->Cell(25,0,"",1,1,'C');
					$row += 7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Nama",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Tanggal Lahir",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Nomor KTP",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 7;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Berdasarkan Surat Kuasa Nomor :           -            tanggal :         -          dengan ini",0,0,'L');
					$row += 5;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"mengajukan permohonan :",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. Pengukuran",0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"8. Pemecahan / Penggabungan Hak",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. Konversi / Pendaftaran Hak",0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"9. Pendaftaran Hak Tanggungan",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. Pendaftaran Hak Milik Sarusun",0,0,'L');
					$pdf -> setXY(98,$row); $pdf->Cell(0,0,"10. Roya atas Hak Tanggungan",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. Pendaftaran Hak Wakaf",0,0,'L');
					$pdf -> setXY(98,$row); $pdf->Cell(0,0,"11. Penerbitan Sertipikat Pengganti",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. Pendaftaran Peralihan Hak",0,0,'L');
					$pdf -> setXY(98,$row); $pdf->Cell(0,0,"12. Surat Keterangan Pendaftaran Tanah",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. Pendaftaran Pemindahan Hak",0,0,'L');
					$pdf -> setXY(98,$row); $pdf->Cell(0,0,"13. Pengecekan Sertipikat",0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"7. Pendaftaran Perubahan Hak",0,0,'L');
					$pdf -> setXY(98,$row); $pdf->Cell(0,0,"14. Pencatatan ...........................",0,0,'L');
					$row += 7;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Atas bidang tanah hak / tanah negara :",0,0,'L');
					$row += 7;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Terletak di Dusun",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainletak_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Desa",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['laindesa_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkecamatan_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkabupaten_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Bukti Hak",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['tanahdasar_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Luas",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['luastanah_gtra'].' m2',0,0,'L');
					$row += 7;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Untuk Melengkapi permohonan dimaksud, bersama ini kami lampirkan :",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"1. Foto Copy KTP dan KK",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"2. Foto Copy SPPT PBB",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"3. Surat Keterangan Tanah",0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"4. BPHTB",0,0,'L');
					$row += 30;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Hormat Kami,",0,0,'C');
					$row += 30;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

					$pdf -> AddPage();
					$row = 15;
					$pdf -> setFont ('Times','',11);
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Kepada :",0,0,'C');
					$row += 7;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Yth.    Bapak Kepala Kantor Pertanahan",0,0,'C');
					$row += 5;
					$pdf -> setXY(122,$row); $pdf->Cell(0,0," Kabupaten Semarang",0,0,'L');
					$row += 7;
					$pdf -> setXY(112,$row); $pdf->Cell(0,0,"di",0,0,'L');
					$row += 5;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"UNGARAN",0,0,'C');
					$row += 10;
					if(strlen($datpdk['almat_pdk'])>40){
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini ".$datpdk['nma_pdk']." bertempat tinggal di ".substr($datpdk['almat_pdk'],0,40),0,0,'L');
						$row += 5;
						$pdf -> setXY(13,$row-2); $pdf->MultiCell(180,5,substr($datpdk['almat_pdk'],40,strlen($datpdk['almat_pdk']))." RT ".$datpdk['rt_pdk']." RW ".$datpdk['rw_pdk']." Desa ".$datpdk['kel_pdk']." Kec. ".$datpdk['kec_pdk'].", dalam hal ini bertindak untuk dan atas nama diri sendiri,");
						$row += 10;
					}else{
						$pdf -> setXY(23,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini ".$datpdk['nma_pdk']." bertempat tinggal di ".$datpdk['almat_pdk']." RT ".$datpdk['rt_pdk']." RW ".$datpdk['rw_pdk'],0,0,'L');
						$row += 5;
						$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Desa ".$datpdk['kel_pdk']." Kec. ".$datpdk['kec_pdk'].", dalam hal ini bertindak untuk dan atas nama diri sendiri,",0,0,'L');
						$row += 5;
					}

					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"dengan ini mengajukan permohonan pendaftaran hak tanah dengan keterangan sebagai berikut :",0,0,'L');
					$row += 7;
					$pdf -> setFont ('Times','U',11);
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"A. MENGENAI DIRI PEMOHON",0,0,'L');
					$pdf -> setFont ('Times','',11);
					$row += 7;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Nama dan tanggal lahir",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'),0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Tempat Tinggal",0,0,'L');
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(140,5,$datpdk['almat_pdk'].' RT.'.$datpdk['rt_pdk'].' RW.'.$datpdk['rw_pdk'].' Desa '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk']);
					$row += 10;
					$pdf -> setFont ('Times','U',11);
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"B. MENGENAI TANAH",0,0,'L');
					$pdf -> setFont ('Times','',11);
					$row += 7;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Tgl. No. dan SK Pemberian Hak",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Jenis Hak",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,"Hak Milik",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Letak di jalan/dusun",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk']);
					$row += 10;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Desa",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['kel_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['kec_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$datpdk['kab_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Luas",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,$data['luastanah_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Batas - batas Tanah",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(43,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(43,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"Tanah dipergunakan untuk",0,0,'L');
					$pdf -> setXY(70,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,status($data['gunakuasa_gtra'],'gunakuasa'),0,0,'L');
					$row += 10;
					$pdf -> setFont ('Times','U',11);
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"C. SURAT-SURAT YANG DILAMPIRKAN :",0,0,'L');
					$pdf -> setFont ('Times','',11);
					$row += 7;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"1. Pernyataan Penguasaan Fisik Bidang Tanah",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"2. Surat Keterangan Tanah",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"3. Pernyataan Pemasangan Tanda Batas",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"4. Foto Copy Kartu Tanda Penduduk (KTP)",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"5. SPPT PBB Tahun 2019",0,0,'L');
					$row += 5;
					$pdf -> setXY(18,$row); $pdf->Cell(0,0,"6. Surat Setoran Bea Perolehan Hak Atas Tanah dan Bangunan",0,0,'L');
					$row += 30;
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Kab.Semarang, ..................",0,0,'C');
					$row += 5;
					$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'L');
					$row += 5;
					$pdf -> setXY(26,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Pemohon",0,0,'C');
					$row += 30;
					$pdf -> setXY(26,$row); $pdf->Cell(40,0,$kades,0,0,'C');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PENGUASAAN FISIK BIDANG TANAH",0,'C');
					$row +=15;
					$pdf -> SetLineWidth(1);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=8;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
					$row +=7;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nomor KTP");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa saya dengan etika baik telah menguasai sebidang tanah yang terletak di :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Jalan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainletak_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['laindesa_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkecamatan_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['lainkabupaten_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Status Tanah");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,"Tanah Negara Bebas");
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dipergunakan untuk");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,status($data['gunakuasa_gtra'],'gunakuasa'));
					$row +=10;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Batas-batas tanah :");
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['utara_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['timur_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['selatan_gtra']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$data['barat_gtra']);
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Bidang tanah tersebut saya peroleh dari : '.$data['tanahsumber_gtra'].' Sejak tahun 1990 yang berasal dari tanah negara dengan cara Hibah, yang sampai saat ini saya kuasai secara terus menerus, tidak dijadikan / menjadi jaminan sesuatu hutang, tidak dalam sengketa, serta belum pernah diterbitkan Sertipikatnya.');
					$row +=15;
					$pdf -> setXY(15,$row); $pdf->MultiCell(0,5,'Surat pernyataan ini saya buat dengan sebenarnya dengan pernah tanggung jawab dan saya bersedia untuk mengangkat sumpah bila diperlukan. Apabila ternyata ini tidak benar, saya bersedia dituntut di hadapan pihak-pihak yang berwenang, dengan saksi :');
					$row +=20;

					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi1);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi1);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi1);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$alamatsaksi1);

					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$namasaksi2);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tgl. Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$tglsaksi2);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$pkrsaksi2);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$alamatsaksi2);

					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Saksi - saksi :");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang membuat pernyataan,",0,0,'C');
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ".$namasaksi1." (..........)");
					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',12);
					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ".$namasaksi2." (..........)");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Mengetahui,",0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,$kades,0,0,'C');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS DAN LUAS TANAH",0,'C');
					$row +=15;
					$pdf -> SetLineWidth(1);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=8;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
					$row +=7;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanggal Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
					$row +=10;
					$pdf -> setXY(15,$row); $pdf->MultiCell(170,5,"Pemilik / Penggarap Tanah Negara Luas +- ".$data['luastanah_gtra'].' m2, terletak di Desa '.$kecamatan['nma_kel'].', Kecamatan '.$kecamatan['nma_kec'].' Kabupaten Semarang.');
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan, jika perlu kami mengangkat sumpah, bahwa :");
					$row +=6;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tanda batas tanah yang kami mohonkan sertipikatnya di Kantor pertanahan Kabupaten Semarang, telah benar-benar kami pasang sesuai dengan PMNA / KBPN No. 3 / 1997 berupa Tugu Beton / Pipa / Besi / Kayu Jati *)......................");
					$pdf -> setXY(170,$row+6);
					$pdf -> SetLineWidth(0.5);
					$pdf->Cell(18,0,"",1,1,'L');
					$pdf -> setXY(20,$row+10.5);
					$pdf -> SetLineWidth(0.5);
					$pdf->Cell(18,0,"",1,1,'L');
					$row +=16;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Pemasangan tanda batas tersebut disaksikan dan disetujui oleh para pemilik tanah yang berbatasan dengan menanda tangani Surat Pernyataan ini, serta disaksikan oleh Kepala Desa/ Lurah / Pamong / RT / RW *) setempat");
					$pdf -> setXY(120,$row+6);
					$pdf -> SetLineWidth(0.5);
					$pdf->Cell(30,0,"",1,1,'L');
					$pdf -> setXY(170,$row+6);
					$pdf -> SetLineWidth(0.5);
					$pdf->Cell(15,0,"",1,1,'L');
					$row +=16;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Dengan dipasangnya tanda batas tersebut, apabila ternyata :");
					$row +=6;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Terdapat perbedaan luas dengan bukti pemilikan / penguasaan tersebut di atas dengan luas hasil pengukuran Petugas Kantor Pertanahan Kabupaten Semarang, maka kami menyetujui dan tidak mempermasalahkannya.");
					$row +=16;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Dikemudian hari ada yang merasa dirugikan, kami sanggup mengembalikan luas tanah seperti semula tanpa menuntut ganti rugi.");
					$row +=12;
					$pdf -> setXY(25,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini kami buat dengan sungguh-sungguh dan dipergunakan sebagai lampiran");
					$row +=5;
					$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"permohonan pengukuran. Apabila isi dari pernyataan ini ternyata tidak benar, kami bersedia dituntut dimuka hakim baik secara pidana maupun secara perdata, karena memberi pernyataan palsu.");
					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=9;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Persetujuan Pemilik Tanah yang berbatasan");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"PEMOHON,",0,0,'C');
					$row +=5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"yang sanggup diangkat sumpah :");
					$row +=8;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Utara ");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['utara_gtra']);
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
					$row +=8;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Timur ");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['timur_gtra']);
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
					$row +=8;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Selatan ");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['selatan_gtra']);
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");
					$row +=8;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Sebelah Barat ");
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,": ".$data['barat_gtra']);
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"(                    )");

					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row-16); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
					$row +=15;
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Saksi pemasangan Tanda Batas",0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"MENGETAHUI,",0,0,'C');
					$row +=5;
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,$namasaksi1,0,0,'L');
					$pdf -> setXY(100,$row); $pdf->Cell(0,0,$kades,0,0,'C');

					$pdf -> AddPage();
					$row =20;
					$pdf -> Image("./assets/img/kabsem.jpeg",15,15,25);
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"PEMERINTAH KABUPATEN SEMARANG",0,0,'C');
					$row+=7;
					$pdf->SetFont('Times','B',16);
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"KECAMATAN ".strtoupper($kecamatan['nma_kec']),0,0,'C');
					$row+=7;
					$pdf->SetFont('Times','B',18);
					$pdf -> setXY(40,$row); $pdf->Cell(0,0,"DESA ".strtoupper($kecamatan['nma_kel']),0,0,'C');
					$row +=7;
					$pdf -> SetLineWidth(0.5);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=1;
					$pdf -> SetLineWidth(1);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$row +=8;
					$pdf->SetFont('Times','U',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"SURAT KETERANGAN TANAH",0,0,'C');
					$pdf->SetFont('Times','',12);
					$row +=5;
					$pdf -> setXY(83,$row); $pdf->Cell(0,0,"Nomor :");
					$row +=10;
					$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini Kepala Desa ".$kecamatan['nma_kel'].", Kecamatan ".$kecamatan['nma_kec'].",");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Kabupaten Semarang, dngan ini menerangkan bahwa :");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Sebidang tanah yang dikuasai oleh ".$datpdk['nma_pdk']." seluas +- ".$data['luastanah_gtra']." m2 terletak di Desa ".$data['laindesa_gtra']." Kecamatan ".$data['lainkecamatan_gtra']." Kabupaten Semarang Provinsi Jawa Tengah dengan batas sebelah Utara ".$data['utara_gtra'].", Timur ".$data['timur_gtra'].", Selatan ".$data['selatan_gtra'].", Barat ".$data['barat_gtra'].", adalah Tanah Negara Obyek landreform");
					$row +=16;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Bahwa tanah tersebut berasal dari ".$data['tanahsumber_gtra']." dengan cara Hibah pada tahun 1990.");
					$row +=6;
					$pdf -> setXY(69,$row);
					$pdf->Cell(8,0,"",1,1,'L');
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Bahwa diatas tanah tersebut telah / tidak *) ada bangunannya.");
					$row +=6;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"4");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Bahwa tanah tersebut dipergunakan untuk Pertanian.");
					$row +=6;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"5");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Bahwa tanah tersebut di atas tidak menjadi sengketa dengan pihak lain.");
					$row +=6;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"6");
					$pdf -> setXY(20,$row-2.5); $pdf->MultiCell(170,5,"Bahwa tanah tersebut belum pernah diterbitkan sertipikat Hak Atas Tanah.");
					$row +=10;
					$pdf -> setXY(35,$row); $pdf->Cell(0,0,"Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan seperlunya.");
					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=9;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"KEPALA DESA ".strtoupper($kecamatan['nma_kel']),0,0,'C');
					$row +=5;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"KECAMATAN ".strtoupper($kecamatan['nma_kec']),0,0,'C');
					$row +=5;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"KABUPATEN SEMARANG",0,0,'C');
					$row +=25;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$kades,0,0,'C');
					$row +=10;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"CATATAN :",0,0,'L');
					$row +=6;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"*) Coret yang tidak perlu.",0,0,'L');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN",0,0,'C');
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"PESERTA REDISTRIBUSI TANAH OBYEK LANDREFORM",0,0,'C');
					$row +=10;
					$pdf->SetFont('Times','',12);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
					$row +=7;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat, Tanggal Lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['ttl_pdk'].','.fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tempat Tinggal");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini menyatakan bahwa :");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"a.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Memiliki pekerjaan/profesi di KTP adalah sebagai ".$datpdk['nama_pkr']);
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"b.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Telah berusia lebih dari 18 (delapan belas) tahun dan belum / sudah *) menikah.");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"c.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Tidak memiliki/menguasai tanah yang melebihi ketentuan maksimum dan tanah absentee");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"d.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Akan mengusahakan secara aktif tanah obyek landreform yang akan diberikan kepada saya.");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"e.");
					$pdf -> setXY(20,$row-2); $pdf->MultiCell(170,5,"Bersedia menjadi calon penerima redistribusi tanah obyek landreform dan akan memenuhi seluruh kewajiban, serta mematuhi segaal hal yang telah ditetapkan sesuai ketentuan yang berlaku.");
					$row +=12;
					$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Disamping hal di atas, saya juga memiliki pekerjaan sebagai PETANI dan mengusahakan tanah pertanian.");
					$row +=11;
					$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Apabila di kemudian hari ternyata saya melakukan kebohongan dan pernyataan saya ini dan saya dinyatakan melanggar ketentuan landreform, maka saya bersedia untuk mengembalikan tanah obyek landreform yang telah saya kuasai/miliki kepada negara dan membayar seluruh biaya yang diakibatkannya.");
					$row +=21;
					$pdf -> setXY(15,$row-2); $pdf->MultiCell(165,5,"Demikian Surat Pernyataan ini saya buat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.");
					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=9;
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Mengetahui",0,0,'L');
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Yang Membuat Pernyataan,",0,0,'C');
					$row +=5;
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'L');
					$row +=10;
					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',11);
					$row +=15;
					$pdf -> setXY(30,$row); $pdf->Cell(0,0,$kades,0,0,'L');
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','B',14);
					$pdf -> setXY(50,$row); $pdf->MultiCell(110,5,"IDENTIFIKASI SUBYEK DAN OBYEK TANAH NEGARA OBYEK LANDREFORM",0,'C');
					$row +=15;
					$pdf -> SetLineWidth(1);
					$pdf -> setXY(15,$row);
					$pdf->Cell(0,0,"",1,1,'C');
					$pdf->SetFont('Times','',12);
					$row += 7;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"I.   LETAK TANAH",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa ".$kecamatan['nma_kel'].", Kecamatan ".$kecamatan['nma_kec'].", Kabupaten Semarang",0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"II.  DATA SUBYEK (PENGGARAP)",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Nama dan Tanggal Lahir",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']." / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'),0,0,'L');
					$row += 5;
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Jumlah keluarga yang masih menjadi tanggungan :",0,0,'L');
					$row += 5;
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"- Nama Istri / Suami ",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$datpdk['pasangan_pdk'],0,0,'L');
					$row += 5;
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"- Nama Anak ",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					foreach ($datanak as $dn) {
						$anak= explode('/',$dn['nama_ank']);
						if(count($anak)>1){
								$pdf -> setXY(76,$row); $pdf->Cell(0,0,'- '.$anak[0],0,0,'L');
								$pdf -> setXY(123,$row); $pdf->Cell(0,0,'Umur : '.$anak[1].' thn',0,0,'L');
						}else{
								$pdf -> setXY(76,$row); $pdf->Cell(0,0,'- '.$dn['nama_ank'],0,0,'L');
						}
						$row += 5;
					}
					$row += 7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Alamat",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$datpdk['kel_pdk'].' Kec.'.$datpdk['kec_pdk']);
					$row += 12;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Pekerjaan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$datpdk['nama_pkr'],0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"III. DATA OBYEK",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Status Tanah",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,'Tanah Negara',0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Luas Tanah",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['luastanah_gtra'].' m2',0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Batas-batas",0,0,'L');
					$row += 5;
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');

					$pdf -> setXY(108,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(128,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
					$pdf -> setXY(45,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(48,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');

					$pdf -> setXY(108,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
					$pdf -> setXY(125,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(128,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"IV. PENGUASAAN TANAH NEGARA GARAPAN",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Cara Pengusahaan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,status($data['kuasacara_gtra'],'kuasacara'),0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Tanaman yang dominan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['tanamankuasa_gtra'],0,0,'L');
					$row += 5;
					if($data['gunakuasa_gtra']==1){
						$pdf -> setXY(97,$row);
						$pdf->Cell(22,0,"",1,1,'L');
						$pdf -> setXY(125,$row);
						$pdf->Cell(13,0,"",1,1,'L');
					}else if($data['gunakuasa_gtra']==2){
						$pdf -> setXY(77,$row);
						$pdf->Cell(15,0,"",1,1,'L');
						$pdf -> setXY(125,$row);
						$pdf->Cell(13,0,"",1,1,'L');
					}else if($data['gunakuasa_gtra']==3){
						$pdf -> setXY(77,$row);
						$pdf->Cell(15,0,"",1,1,'L');
						$pdf -> setXY(97,$row);
						$pdf->Cell(22,0,"",1,1,'L');
					}
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Penggunaan saat ini",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,'Pertanian / Non Pertanian / lain-lain',0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Lama penggarapan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['tahunkuasa_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5.",0,0,'L');
					$pdf -> setXY(28,$row); $pdf->Cell(0,0,"Dasar Penguasaan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['dasarkuasa_gtra'],0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"V.   LAIN-LAIN",0,0,'L');
					$row += 10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tanah yang telah dimiliki",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['laintanahluas_gtra'].' m2',0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Terletak di Desa",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['laindesa_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kecamatan",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['lainkecamatan_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Kabupaten",0,0,'L');
					$pdf -> setXY(73,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(76,$row); $pdf->Cell(0,0,$data['lainkabupaten_gtra'],0,0,'L');
					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=9;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Penggarap Tanah,",0,0,'C');
					$row +=15;
					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',11);
					$row +=15;
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"Petugas Inventaris :",0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"1.                   (          )",0,0,'L');
					$row += 10;
					$pdf -> setXY(13,$row); $pdf->Cell(0,0,"2.                   (          )",0,0,'L');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','UB',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN HIBAH",0,0,'C');
					$row +=20;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan / cap di bawah ini saya:");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tanggal lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini ");
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"menyatakan sebenarnya (jika diperlukan sanggup diangkat sumpah)",0,0,'L');
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(148,$row); $pdf->Cell(0,0,"bahwa : ");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Saya memiliki sebidang tanah GG / P2 (Tanah Obyek Landreform) seluas ".$data['luastanah_gtra']." m2 yang terletak di ".$data['lainletak_gtra']);
					$row +=10;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Dusun ".$kecamatan['name_dsn'].' Desa '.$data['laindesa_gtra'].', Kecamatan '.$data['lainkecamatan_gtra'].', Kabupaten '.$data['lainkabupaten_gtra']);
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Tanah tersebut pada butir (1) di atas seluruhnya / sebagian seluas ".$data['luastanah_gtra']." m2 dengan");
					$row +=5;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"batas-batas :");
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
					$row +=7;
					$pdf->SetFont('Times','IB',11);
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Sejak tahun 1990 telah dihibahkan kepada saya dari :");
					$pdf->SetFont('Times','',11);
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Nama");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Umur");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Pekerjaan");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Alamat");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=10;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Saya menjamin tanah tersebut tidak sengketa, bebas dari sitaan dan tidak terikat sebagai Jaminan.");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Segala hak dan kewajiban atas tanah tersebut telah beralih menjadi hak dan kewajiban dari Penerima hibah, termasuk memohon sertipikat atas tanah dimaksud atas nama penerima Hibah.");
					$row +=15;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Demikian ");
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Surat Pernyataan Hibah");
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(75,$row); $pdf->Cell(0,0,"ini dibuat untuk melengkapi pengajuan permohonan sertipikat hak atas");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"tanah melalui Redistribusi Tanah Obyek Landreform di Kantor Pertanahan Kabupaten Semarang.");

					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Saksi - saksi :");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Penerima hibah,",0,0,'C');
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ".$namasaksi1." (..........)");
					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',12);
					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ".$namasaksi2." (..........)");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Mengetahui,",0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,$kades,0,0,'C');

					$pdf -> AddPage();
					$row =20;
					$pdf->SetFont('Times','B',12);
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN GANTI RUGI GARAPAN",0,0,'C');
					$row +=20;
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Yang bertanda tangan / cap di bawah ini saya:");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Nama");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,stripslashes($datpdk['nma_pdk']));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Tanggal lahir");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Pekerjaan");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Alamat");
					$pdf -> setXY(60,$row); $pdf->Cell(0,0,":");
					$pdf -> setXY(63,$row-2); $pdf->MultiCell(130,5,$datpdk['almat_pdk'].' RT '.$datpdk['rt_pdk'].' RW '.$datpdk['rw_pdk'].' Desa '.$kecamatan['nma_kel'].' Kec.'.$kecamatan['nma_kec']);
					$row +=12;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Dengan ini ");
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(33,$row); $pdf->Cell(0,0,"menyatakan sebenarnya (jika diperlukan sanggup diangkat sumpah)",0,0,'L');
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(148,$row); $pdf->Cell(0,0,"bahwa : ");
					$row +=7;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"1.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Saya memiliki sebidang tanah GG / P2 (Tanah Obyek Landreform) seluas ".$data['luastanah_gtra']." m2 yang terletak di ".$data['lainletak_gtra']);
					$row +=10;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Dusun ".$kecamatan['name_dsn'].' Desa '.$data['laindesa_gtra'].', Kecamatan '.$data['lainkecamatan_gtra'].', Kabupaten '.$data['lainkabupaten_gtra']);
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"2.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Tanah tersebut pada butir (1) di atas seluruhnya / sebagian seluas ".$data['luastanah_gtra']." m2 dengan");
					$row +=5;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"batas-batas :");
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Utara",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$data['utara_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Timur",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['timur_gtra'],0,0,'L');
					$row += 5;
					$pdf -> setXY(25,$row); $pdf->Cell(0,0,"Selatan",0,0,'L');
					$pdf -> setXY(50,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(53,$row); $pdf->Cell(0,0,$data['selatan_gtra'],0,0,'L');
					$pdf -> setXY(90,$row); $pdf->Cell(0,0,"Barat",0,0,'L');
					$pdf -> setXY(110,$row); $pdf->Cell(0,0,":",0,0,'L');
					$pdf -> setXY(113,$row); $pdf->Cell(0,0,$data['barat_gtra'],0,0,'L');
					$row +=7;
					$pdf->SetFont('Times','IB',11);
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Sejak tahun 1990 telah dihibahkan kepada saya dari :");
					$pdf->SetFont('Times','',11);
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Nama");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Umur");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Pekerjaan");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=7;
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Alamat");
					$pdf -> setXY(50,$row-2.5); $pdf->MultiCell(170,5,": .......................................................");
					$row +=10;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"3.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Saya menjamin tanah tersebut tidak sengketa, bebas dari sitaan dan tidak terikat sebagai Jaminan.");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"4.");
					$pdf -> setXY(25,$row-2.5); $pdf->MultiCell(170,5,"Segala hak dan kewajiban atas tanah tersebut telah beralih menjadi hak dan kewajiban dari Penerima hibah, termasuk memohon sertipikat atas tanah dimaksud atas nama penerima Hibah.");
					$row +=15;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"Demikian ");
					$pdf->SetFont('Times','B',11);
					$pdf -> setXY(32,$row); $pdf->Cell(0,0,"Surat Pernyataan Ganti Rugi Garapan");
					$pdf->SetFont('Times','',11);
					$pdf -> setXY(99,$row); $pdf->Cell(0,0,"ini dibuat untuk melengkapi pengajuan permohonan");
					$row +=5;
					$pdf -> setXY(15,$row); $pdf->Cell(0,0,"sertipikat hak atas tanah melalui Redistribusi Tanah Obyek Landreform di Kantor Pertanahan Kabupaten Semarang.");

					$row +=10;
					$pdf -> setXY(130,$row); $pdf->Cell(0,5,$kecamatan['nma_kel'].", ......................",0,0,'C');
					$row +=7;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Saksi - saksi :");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Penerima Ganti Rugi Garapan,",0,0,'C');
					$row +=10;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ".$namasaksi1." (..........)");
					$pdf->SetFont('Times','',7);
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,"Materai Rp. 10.000,-",0,0,'C');
					$pdf->SetFont('Times','',12);
					$row +=15;
					$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ".$namasaksi2." (..........)");
					$pdf -> setXY(130,$row); $pdf->Cell(0,0,$datpdk['nma_pdk'],0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Mengetahui,",0,0,'C');
					$row +=5;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
					$row +=25;
					$pdf -> setXY(0,$row); $pdf->Cell(0,0,$kades,0,0,'C');


				$pdf->Output();
			}

		public function renamefile($id=null){
			$ptsl['type']                   = "multiple";
			$ptsl['table']                  = "tb_gtraberkas";
			$ptsl['column']                 = "id_pbk,name_dsn,berkas_pbk,ppptr_gtra";
			$ptsl['join']['table'] = "tb_gtra,ms_dusun";
			$ptsl['join']['key'] = "id_gtra,id_dsn";
			$ptsl['join']['ref'] = "idgtra_pbk,iddsn_gtra";
			if($id){
				$ptsl['condition']['idgtra_pbk'] = $id;
			}
			$data                        = $this->crud_model->get_data($ptsl);

			if($data){
				foreach ($data as $dd) {
					$ext = explode('.',$dd['berkas_pbk']);
					$namabaru = str_replace(" ","_",$dd['name_dsn']).'_'.$dd['ppptr_gtra'].'_'.time().''.rand(0,100).'.'.$ext[1];
					echo $namabaru.'<br>';

					$simpan = $this->crud_model->update('tb_gtraberkas',array('berkas_pbk'=>$namabaru),array('id_pbk'=>$dd['id_pbk']));
					if($simpan){
							rename("./DATA/BERKASGTRA/".$dd['berkas_pbk'],"./DATA/BERKASGTRA/".$namabaru);
					}
				}
			}
		}

		public function updatenubdsn(){
			$ptsl['type']                   = "multiple";
			$ptsl['table']                  = "tb_gtra";
			$ptsl['orderby']['column'] 			= "iddsn_gtra,nub_gtra";
			$ptsl['orderby']['sort']				= "ASC";
			$data                        = $this->crud_model->get_data($ptsl);

			if($data){
				$nubdusun=0;
				foreach ($data as $dd) {
					if($nubdusun==0){
						$nubdusun=1;
						$start=1;
					}
					if($start!=$dd['iddsn_gtra']){
						$start=$dd['iddsn_gtra'];
						$nubdusun=1;
					}

					$update = $this->crud_model->update('tb_gtra',array('nubdsn_gtra'=>$nubdusun),array('id_gtra'=>$dd['id_gtra']));

					if($update){
							echo '<span class="btn-success">BERHASIL UPDATE id:'.$dd['id_gtra'].' - desa:'.$dd['iddsn_gtra'].' - nub:'.$dd['nub_gtra'].' - nub baru:'.$nubdusun.'</span><br>';
							$nubdusun++;
					}else{
						echo '<span class="btn-warning">GAGAL !!! id:'.$dd['id_gtra'].' - desa:'.$dd['iddsn_gtra'].' - nub:'.$dd['nub_gtra'].' - nub baru:'.$nubdusun.'</span><br>';
					}

				}

			}

		}

		public function exportberkas($id){

				define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
				require(APPPATH .'plugins/fpdf/fpdf.php');

				$ptsl['type']                   = "multiple";
				$ptsl['table']                  = "tb_gtraberkas";
				$ptsl['condition']['idgtra_pbk'] = $id;
				$data                        = $this->crud_model->get_data($ptsl);



					$pdf = new FPDF('p','mm',array(210,330));

					if($data){
						foreach ($data as $dd) {
							$array = array('png','jpg','jpeg');
							$ext = explode(".",$dd['berkas_pbk']);
							if(in_array($ext[1], $array)){
								$pdf -> AddPage();
								$pdf -> Image("./DATA/BERKASGTRA/".$dd['berkas_pbk'],10,15,180);
							}

						}

					}

					$pdf -> setDisplayMode ('fullpage');

				$pdf->Output();
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

	public function delete($kode)
	{
		$ar = array(
			'publish_gtra' => '0'
		);
		$hapus = $this->crud_model->update('tb_gtra',$ar,array('id_gtra'=>$kode));
		$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_gtra','e Panitia Desa-0-'.$kode,"Menghapus Data GTRA dengan kode ".$kode);

		if($hapus){
			$msg = true;
		}
		echo json_encode($msg);die();
	}

}
