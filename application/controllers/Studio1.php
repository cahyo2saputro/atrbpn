<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio1 extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();

			$this->load->database();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
			$user = $this->auth_model->get_userdata();
			cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);
			$level_usr = $user['level_usr'];

			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Kelurahan';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = " E-Digitalisasi";
			$this->content['data']['subtitle'] = array(array("e-digitalisasi","Studio1"));

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

			$config['base_url'] = base_url().'Studio1/index/';
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

			$this->content['studio'] = $this->studio_1_1_model->show_kecamatan($config['per_page'],$from,$cari,$carikelurahan);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("Studio1/data_kelurahan");

			$this->load->view('adm',$this->content);
		}

		public function validation()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = " E-Validation";
			$this->content['data']['subtitle'] = array(array("e-validation","Studio1/validation"));

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

			$config['base_url'] = base_url().'Studio1/validation/';
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

			$this->content['studio'] = $this->studio_1_1_model->show_kecamatan($config['per_page'],$from,$cari,$carikelurahan);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("Studio1/data_kelurahan");

			$this->load->view('adm',$this->content);
		}

		public function cari_desa()
		{
			$id = $this->input->post('id');
			$data = $this->kelurahan_kw_model->cari_data_desa($id);
			echo json_encode($data);die();
		}
	}
 ?>
