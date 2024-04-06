<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Datapenduduk extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();

			$this->load->database();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Penduduk';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			$this->content['data']['param'] = array(array('Nama','nma_pdk'),array('No. KTP','noktp_pdk'),array('Kelurahan','kel_pdk'),array('Domisili','domisili_pdk'));

			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "Data Penduduk ";
			$this->content['data']['subtitle'] = array(array("",""));

			if($this->input->get('param') || $this->input->get('nilai')){
				$dat['like'][$this->input->get('param')] = $this->input->get('nilai');
				$tdata['like'][$this->input->get('param')] = $this->input->get('nilai');
			}

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_penduduk";
			$tdata['type'] = "single";
			$tdata['column'] = "COUNT(idpdk_pdk) as jumlah";
			$ttdata = $this->crud_model->get_data($tdata);


			$config['base_url'] = base_url().'Datapenduduk/index/';
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

			$dat['table'] = "tb_penduduk";
      		$dat['type'] = "multiple";
			$dat['orderby']['column'] = 'nma_pdk';
			$dat['orderby']['sort'] = 'asc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      		$this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("penduduk/data");
			$this->load->view('adm',$this->content);
		}

		public function detail($id)
		{
			$user = $this->auth_model->get_userdata();
			$cari = $this->input->get('search');
			$block = $this->studio_2_1_model->sr_name_block($cari);

			// SEARCHING
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

			$this->content['data']['title'] = "Data Penduduk ";
			$this->content['data']['subtitle'] = array(array("Data penduduk","Datapenduduk"));

			$dat['table'] = "tb_penduduk";
      		$dat['type'] = "single";
			  $dat['join']['table'] = "tb_pekerjaan";
			  $dat['join']['key'] = "idpkr_pkr";
			  $dat['join']['ref'] = "idpeker_pdk";
			$dat['condition']['idpdk_pdk'] = $id;

      		$this->content['studio'] = $this->crud_model->get_data($dat);


			$this->content['load'] = array("penduduk/detail");
			$this->load->view('adm',$this->content);
		}

	}
 ?>
