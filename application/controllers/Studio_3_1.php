<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_3_1 extends CI_Controller
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
			if($this->input->get('search')){
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->input->get('search'));
			}
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = '';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$cari = $this->input->get('search');

			$kelurahan['type'] = "single";
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['condition']['kd_full'] = $cari;

			$nma_kel = $this->crud_model->get_data($kelurahan);

			$this->content['data']['title'] = "e-Panitia Desa Kelurahan ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studio_3_1/index/';
			$config['total_rows'] = $t_data;
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 100;

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


			$this->content['block'] = $this->studio_3_1_model->show_data($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio3/data_studio_3_1");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_ptsl($id)
		{
			$user = $this->auth_model->get_userdata();

			$sr_kel = $this->db->query("SELECT nma_kel, nma_kec, idkel_blk FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idblk_blk = '$id'")->row_array();
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

			$nmfile2 					= "PTSL"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
    		$config2['upload_path']		= './PETA/PETA_PTSL/';
    		$config2['allowed_types']	= '*';
    		$this->upload->initialize($config2);
    		$upload2 				= $this->upload->do_upload('petptsl_blk');
    		$data2					= $this->upload->data();
    		$nama_upload_ptsl 		= $data2['file_name'];

    		$ar = array(
    				'idusr_blk' => $user['idusr_usr'],
					'petptsl_blk' => $nama_upload_ptsl
			);



			$simpan = $this->studio_2_1_model->edit($id,$ar);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Panitia Desa-'.$nma_kel.'-'.$id,"Edit Peta PTSL dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_peta_block($id)
		{
			$user = $this->auth_model->get_userdata();

			$sr_kel = $this->db->query("SELECT nma_kel, nma_kec, idkel_blk FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idblk_blk = '$id'")->row_array();
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

			$nmfile1 					= "BLK"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
    		$config1['upload_path']		= './PETA/PETA_BLOCK/';
    		$config1['allowed_types']	= '*';
    		$this->upload->initialize($config1);
    		$uploads 				= $this->upload->do_upload('petblk_blk');
    		$data1					= $this->upload->data();
    		$nama_upload_blk 		= $data1['file_name'];

    		$ar = array(
    			'idusr_blk' => $user['idusr_usr'],
					'petblk_blk' => $nama_upload_blk
			);



			$simpan = $this->studio_2_1_model->edit($id,$ar);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Panitia Desa-'.$nma_kel.'-'.$id,"Edit Peta Block dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		function export()
		{
			$id = $this->input->get('blok');
			$desa = $this->input->get('search');
			if ($id) {
				$kec ['type'] 	= "single";
				$kec ['table']	= "tb_block";
				$kec ['column']	= "nma_kel,nma_kec,nama_blk,idkel_blk";
				$kec ['join']['table']	= "ms_kelurahan,ms_kecamatan";
				$kec ['join']['key']	= "kd_full,kdkec_kel";
				$kec ['join']['ref']	= "idkel_blk,kd_kec";
				$kec ['condition']['idblk_blk'] = $id;
				$this->content['desa'] = $this->crud_model->get_data($kec);

				$data ['type']		= "multiple";
				$data ['table'] 	= "tb_ptsl";
				$data ['join']['table'] = "tb_block,tb_penduduk";
				$data ['join']['key'] 	= "idblk_blk,idpdk_pdk";
				$data ['join']['ref']	= "idblk_ptsl,idpdk_ptsl";
				$data ['condition']['idblk_ptsl'] = $id;
				$data ['condition']['publish_ptsl'] = 1;
				$data ['groupby'] = 'nub_ptsl';
				$data ['orderby']['column'] = 'nub_ptsl';
				$data ['orderby']['sort'] = 'asc';


				$this->content['dat'] = $this->crud_model->get_data($data);
				$this->load->view('studio3/exportblock',$this->content);
			}else if($desa){
				
				$kec ['type'] 	= "single";
				$kec ['table']	= "tb_block";
				$kec ['column']	= "nma_kel,nma_kec,nama_blk,idkel_blk";
				$kec ['join']['table']	= "ms_kelurahan,ms_kecamatan";
				$kec ['join']['key']	= "kd_full,kdkec_kel";
				$kec ['join']['ref']	= "idkel_blk,kd_kec";
				$kec ['condition']['idkel_blk'] = $desa;
				$this->content['desa'] = $this->crud_model->get_data($kec);

				$data ['type']		= "multiple";
				$data ['table'] 	= "tb_ptsl";
				$data ['join']['table'] = "tb_block,tb_penduduk";
				$data ['join']['key'] 	= "idblk_blk,idpdk_pdk";
				$data ['join']['ref']	= "idblk_ptsl,idpdk_ptsl";
				$data ['condition']['idkel_blk'] = $desa;
				$data ['condition']['publish_ptsl'] = 1;
				// $data ['groupby'] = 'nub_ptsl';
				$data ['orderby']['column'] = 'nama_blk,nub_ptsl';
				$data ['orderby']['sort'] = 'asc';

				$this->content['dat'] = $this->crud_model->get_data($data);
				$this->load->view('studio3/exportdesa',$this->content);
			}
		}

		function exportnub()
		{
			$id = $this->input->get('blok');
			if ($id) {
				$kec ['type'] 	= "single";
				$kec ['table']	= "tb_block";
				$kec ['column']	= "nma_kel,nma_kec,nama_blk,idkel_blk";
				$kec ['join']['table']	= "ms_kelurahan,ms_kecamatan";
				$kec ['join']['key']	= "kd_full,kdkec_kel";
				$kec ['join']['ref']	= "idkel_blk,kd_kec";
				$kec ['condition']['idblk_blk'] = $id;
				$this->content['desa'] = $this->crud_model->get_data($kec);

				$data['table'] = "tb_nub";
	      $data['type'] = "multiple";
				$data['column'] = "tb_hak.no_hak,tb_hak.id_kelurahan,tb_hak.nib_hak,tb_hak.nosu_hak,tb_su.luas_su,
													tb_hak.jenis_kw_awal,tb_hak.pma_hak,tb_hak.pmi_hak,tb_hak.buku_tanah,tb_hak.entry_su_tekstual,
													tb_hak.su_spasial,tb_hak.bidang_tanah";
				$data['join']['table'] = "tb_su,tb_hak";
				$data['join']['key'] = "nohak_su,no_hak";
				$data['join']['ref'] = "nohak_nub,nohak_nub";
				$data ['condition']['idblk_nub'] = $id;
				$data ['groupby'] = 'nohak_nub';

				$this->content['dat'] = $this->crud_model->get_data($data);
				$this->load->view('studio3/exportblocknub',$this->content);
			}
		}

		public function petaonline($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['join']['table'] = "tb_block";
			$dat['join']['key'] = "kd_full";
			$dat['join']['ref'] = "idkel_blk";
			$dat['condition']['idblk_blk'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$this->content['peta'] = $hasil;

			$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Panitia Desa","Studio3"),array("Daftar Blok","Studio_3_1/index/?search=".$hasil['kd_full']),array("Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'],"Studio_6_1/petaonline/".$cari));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->content['load'] = array("studio6/peta");
			$this->load->view('adm',$this->content);
		}
	}
