<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_7_1 extends CI_Controller
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

			$this->content['data']['title'] = "e-Yuridis ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio7"),array("Daftar Blok","Studio_7_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studio_7_1/index/';
			$config['total_rows'] = $t_data;
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 30;

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


			$this->content['block'] = $this->studio_3_1_model->show_data_tr($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio7/data_studio_7_1");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_ptsl($id)
		{
			$user = $this->auth_model->get_userdata();

			$sr_kel = $this->db->query("SELECT nma_kel, nma_kec, idkel_blk FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idblk_blk = '$id'")->row_array();
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

				$nmfile2 					= "PTSL_".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
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
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Yuridis-0-'.$id,"Edit Peta PTSL dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
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
			$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio7"),array("Daftar Blok","Studio_7_1/index/?search=".$hasil['kd_full']),array("Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'],"Studio_7_1/petaonline/".$cari));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->content['load'] = array("studio6/peta");
			$this->load->view('adm',$this->content);
		}

	}
