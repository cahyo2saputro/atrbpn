<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plotting extends CI_Controller
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
		$this->content['data']['title_page'] = 'Plotting';
		$this->load->view('auth/authorized');
	}

	public function index($id=null)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = " Plotting Online";
		$this->content['data']['subtitle'] = array(array("Plotting","Plotting"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = setDB('online','plotting_sertifikat');
		$dat['type'] = "single";
		$dat['column'] = "COUNT(id) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'plotting/index/';
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

		$this->content['data'] = $this->studio3_model->show_dataluasshat($config['per_page'],$from);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("plotting/data_studio");
		$this->load->view('adm',$this->content);
	}

}
