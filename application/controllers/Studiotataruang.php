<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Studiotataruang extends CI_Controller
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
		$this->content['data']['title_page'] = 'Tata Ruang';
		$this->load->view('auth/authorized');
	}

	public function index()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "e-Tataruang";
		$this->content['data']['subtitle'] = array(array("e-Tataruang","Studiotataruang"));

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

		$config['base_url'] = base_url().'Studiotataruang/index/';
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

		$this->content['studio'] = $this->studio3_model->show_tataruang($config['per_page'],$from,$cari,$carikelurahan);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("studiotataruang/data_studio");
		$this->load->view('adm',$this->content);
	}

	public function petaonline(){
		$user = $this->auth_model->get_userdata();
		$cari = $this->input->get('search');

		$dat['table'] = "ms_kelurahan";
		$dat['type'] = "single";
		$dat['condition']['kd_full'] = $cari;
		$hasil = $this->crud_model->get_data($dat);

		$peta['table'] = "tb_peta";
		$peta['type'] = "single";
		$peta['condition']['idkel_pt'] = $cari;
		$this->content['peta'] = $this->crud_model->get_data($peta);

		$this->content['data']['title'] = "Peta e-Tataruang ".$hasil['nma_kel'];
		$this->content['data']['subtitle'] = array(array("e-Tataruang","Studiotataruang"),array("Peta Online ".$hasil['nma_kel'],"Studiotataruang/petaonline/?search=".$cari));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->content['load'] = array("studiotataruang/peta");
		$this->load->view('adm',$this->content);
	}

}
