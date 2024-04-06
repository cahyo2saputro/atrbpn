<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studioip4t_1 extends CI_Controller
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

			$this->content['data']['title'] = "IP4T ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("IP4T","Studioip4t"),array("Daftar Blok","Studioip4t_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studioip4t_1/index/';
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

			$this->content['load'] = array("studioip4t/data_studio_1");
			$this->load->view('adm',$this->content);
		}

	}
