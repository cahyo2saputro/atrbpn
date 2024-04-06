<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studiotataruang_1 extends CI_Controller
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
			$this->content['data']['title_page'] = 'Tata Ruang 1';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$cari = $this->input->get('search');

			$kelurahan['type'] = "single";
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['condition']['kd_full'] = $cari;

			$nma_kel = $this->crud_model->get_data($kelurahan);

			$this->content['data']['title'] = "Tata Ruang Kelurahan ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-Tataruang","Studiotataruang"),array("Daftar Blok","Studiotataruang_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studiotataruang_1/index/';
			$config['total_rows'] = $t_data;
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


			$this->content['block'] = $this->studio_3_1_model->show_data_tr($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studiotataruang/data_studio_1");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_tataruang($id)
		{
			$user = $this->auth_model->get_userdata();

				$block['type'] = "single";
				$block['table'] = "tb_block";
				$block['column'] = "idkel_blk,nama_blk";
				$block['condition']['idblk_blk'] = $id;
				$db = $this->crud_model->get_data($block);

  			$nmfile2 					= "TATARUANGBLOCK_".$db['idkel_blk']."".$db['nama_blk']."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
    		$config2['upload_path']		= './PETA/PETA_TATARUANGBLOCK/';
    		$config2['allowed_types']	= '*';
    		$this->upload->initialize($config2);
    		$upload2 				= $this->upload->do_upload('pettr_blk');
    		$data2					= $this->upload->data();
    		$nama_upload_tr = $data2['file_name'];

    		$ar = array(
    			'idusr_blk' => $user['idusr_usr'],
					'pettr_blk' => $nama_upload_tr
			  );

			$simpan = $this->studio_2_1_model->edit($id,$ar);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Tataruang-0-'.$id,"Edit Peta RT RW dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}


	}
