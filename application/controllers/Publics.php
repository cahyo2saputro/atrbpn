<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Publics extends CI_Controller
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
		$this->load->view('auth/authorized');
	}

	public function index()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar User Android";
		$this->content['data']['subtitle'] = array(array("User Android","Publics"));

		$condition ='';
		if($this->input->get('nik')){
			$condition .= "nik_reg=".$this->input->get('nik');
		}

		if($this->input->get('status')){
			if($condition!=''){
				$condition .= " AND ";
			}
			if($this->input->get('status')==1){
					$condition .= "idusr_reg=0";
			}else if($this->input->get('status')==2){
					$condition .= "idusr_reg!=0";
			}
		}
		if($condition!=''){
			$dat['cuzcondition'] = $condition;
			$data['cuzcondition'] = $condition;
		}

		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_register";
		$dat['type'] = "single";

		$dat['column'] = "COUNT(id_reg) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/index/';
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

		$data['table'] = "tb_register";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_sertipikat";
		$data['join']['key']   = "idreg_srt";
		$data['join']['ref']   = "id_reg";
		$data['groupby']       = "id_reg";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['condition']['publish_reg']   = "1";
		$data['column']        = "id_reg,typeusr_reg,idusr_reg,nik_reg,nma_reg,nohp_reg,alamat_reg,tb_register.create_at";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_user");
		$this->load->view('adm',$this->content);
	}

	public function levelone()
	{
		$user = $this->auth_model->get_userdata();

		// filter kecamatan
		if($user['level_usr'] != "1"){
			$idusr = $user['idusr_usr'];
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
		}else{
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
		}

		// filter
		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		if($cari!=0 && $carikelurahan==0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
		}else if($cari!=0 && $carikelurahan!=0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
			$dat['condition']['kd_kel'] = $data['condition']['kd_kel'] = $carikelurahan;
		}else if($cari==0 && $carikelurahan!=0){
			$dat['condition']['kd_full'] = $data['condition']['kd_full'] = $carikelurahan;
		}

		$this->content['data']['title'] = "Daftar User Kades";
		$this->content['data']['subtitle'] = array(array("User Kades","Publics/levelone"));

		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_register";
		$dat['join']['table'] = "ms_kelurahan";
		$dat['join']['key']   = "kd_full";
		$dat['join']['ref']   = "kdfull_reg";
		$dat['type'] = "single";
		$dat['column'] = "COUNT(id_reg) as jumlah";
		$dat['condition']['typeusr_reg'] = "1";
		$dat['groupby']       = "id_reg";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/index/';
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

		$data['table'] = "tb_register";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_sertipikat,ms_kelurahan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,kd_full,kd_kec";
		$data['join']['ref']   = "id_reg,kdfull_reg,kdkec_kel";
		$data['groupby']       = "id_reg";
		$data['condition']['typeusr_reg'] = "1";
		$data['condition']['publish_reg']   = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['column']        = "id_reg,nma_kec,nma_kel,idusr_reg,nik_reg,nma_reg,nohp_reg,alamat_reg,tb_register.create_at";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_kades");
		$this->load->view('adm',$this->content);
	}

	public function levelthree()
	{
		$user = $this->auth_model->get_userdata();

		// filter kecamatan
		if($user['level_usr'] != "1"){
			$idusr = $user['idusr_usr'];
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
		}else{
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
		}

		// filter
		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		if($cari!=0 && $carikelurahan==0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
		}else if($cari!=0 && $carikelurahan!=0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
			$dat['condition']['kd_kel'] = $data['condition']['kd_kel'] = $carikelurahan;
		}else if($cari==0 && $carikelurahan!=0){
			$dat['condition']['kd_full'] = $data['condition']['kd_full'] = $carikelurahan;
		}

		$this->content['data']['title'] = "Daftar User Admin Desa";
		$this->content['data']['subtitle'] = array(array("User Android Admin Desa","Publics/levelthree"));

		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_register";
		$dat['join']['table'] = "ms_kelurahan";
		$dat['join']['key']   = "kd_full";
		$dat['join']['ref']   = "kdfull_reg";
		$dat['type'] = "single";
		$dat['column'] = "COUNT(id_reg) as jumlah";
		$dat['condition']['typeusr_reg'] = "3";
		$dat['groupby']       = "id_reg";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/index/';
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

		$data['table'] = "tb_register";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_sertipikat,ms_kelurahan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,kd_full,kd_kec";
		$data['join']['ref']   = "id_reg,kdfull_reg,kdkec_kel";
		$data['groupby']       = "id_reg";
		$data['condition']['typeusr_reg'] = "3";
		$data['condition']['publish_reg']   = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['column']        = "id_reg,nma_kec,nma_kel,idusr_reg,nik_reg,nma_reg,nohp_reg,alamat_reg,tb_register.create_at";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_kades");
		$this->load->view('adm',$this->content);
	}

	public function levelfour()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar User Admin Pengukuran";
		$this->content['data']['subtitle'] = array(array("User Android Admin Pengukuran","Publics/levelfour"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$condition ='';
		if($this->input->get('nik')){
			$condition .= "nik_reg=".$this->input->get('nik');
		}

		if($condition!=''){
			$dat['cuzcondition'] = $condition;
			$data['cuzcondition'] = $condition;
		}

		$dat['table'] = "tb_register";
		$dat['type'] = "single";
		$dat['column'] = "COUNT(id_reg) as jumlah";
		$dat['condition']['typeusr_reg'] = "4";
		$dat['groupby']       = "id_reg";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/index/';
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

		$data['table'] = "tb_register";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_sertipikat,ms_kelurahan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,kd_full,kd_kec";
		$data['join']['ref']   = "id_reg,kdfull_reg,kdkec_kel";
		$data['groupby']       = "id_reg";
		$data['condition']['typeusr_reg'] = "4";
		$data['condition']['publish_reg']   = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['column']        = "id_reg,nma_kec,nma_kel,idusr_reg,nik_reg,nma_reg,nohp_reg,alamat_reg,tb_register.create_at";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_user");
		$this->load->view('adm',$this->content);
	}

	public function dashboardnop()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Dashboard Status Pengukuran";
		$this->content['data']['subtitle'] = array(array("Dashboard Status Pengukuran","Publics/dashboardnop"));

		$model['table'] = 'tb_sertipikat';
		$column = 'COUNT(DISTINCT idreg_srt) as jumlahuser,
							 SUM(case when sert_srt=0 and publish_srt=1 then 1 else 0 end) as belumsertipikat,
							 SUM(case when sert_srt=1 and status_srt=1 and publish_srt=1 then 1 else 0 end) as sudahsertipikatukur,
							 SUM(case when sert_srt=1 and status_srt=0 and publish_srt=1 then 1 else 0 end) as sudahsertipikatcek
							';

		$this->content['studio'] = $this->kelurahan_model->show_kelurahan($model,$column);

		$this->content['load'] = array("android/dashboardnop");
		$this->load->view('adm',$this->content);
	}

	public function dashboardpermohonan()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Dashboard Belum Sertipikat";
		$this->content['data']['subtitle'] = array(array("Dashboard Belum Sertipikat","Publics/dashboardpermohonan"));

		$model['table'] = 'tb_sertipikat';
		$column = '(SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=0 AND idsrt_pmh=id_srt) as diajukan,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=1 AND idsrt_pmh=id_srt) as form2,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=2 AND idsrt_pmh=id_srt) as kadesacc,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=3 AND idsrt_pmh=id_srt) as cek,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=4 AND idsrt_pmh=id_srt) as tolak,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND tracking_pmh=5 AND idsrt_pmh=id_srt) as nosah,
							 (SELECT COUNT(id_pmh) FROM tb_permohonan WHERE status_pmh=1 AND idsrt_pmh=id_srt) as permohonan,
							';

		$this->content['studio'] = $this->kelurahan_model->show_kelurahan($model,$column);

		$this->content['load'] = array("android/dashboardpermohonan");
		$this->load->view('adm',$this->content);
	}

	public function permohonan()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar Permohonan";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan","Publics/permohonan"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_sertipikat";
		$dat['type'] = "single";
		$dat['condition']['sert_srt'] = "0";
		$dat['column'] = "COUNT(id_srt) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/permohonan/';
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

		$data['table'] = "tb_sertipikat";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_register,tb_permohonan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,idsrt_pmh,kd_kec";
		$data['join']['ref']   = "id_reg,id_srt,kec_srt";
		$data['condition']['sert_srt']   = "0";
		$data['condition']['publish_srt']   = "1";
		$data['column']        = "id_srt,nosah_pmh,ref_srt,tracking_pmh,kuasa_pmh,nik_reg,nma_reg,nohp_reg,nma_kec,nope_srt,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function permohonandesa()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar Permohonan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan Ukur","Publics/permohonan"));

		// filter kecamatan
		if($user['level_usr'] != "1"){
			$idusr = $user['idusr_usr'];
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
		}else{
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
		}
		// filter
		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		if($user['level_usr'] == "7"){
			$dat['condition']['kd_full'] = $user['kdfull_reg'];
			$data['condition']['kd_full'] = $user['kdfull_reg'];
		}

		if($cari!=0 && $carikelurahan==0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
		}else if($cari!=0 && $carikelurahan!=0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
			$dat['condition']['kd_kel'] = $data['condition']['kd_kel'] = $carikelurahan;
		}else if($cari==0 && $carikelurahan!=0){
			$dat['condition']['kd_full'] = $data['condition']['kd_full'] = $carikelurahan;
		}

		if($this->input->get('nik')){
			$dat['condition']['nik_reg'] = $data['condition']['nik_reg'] = $this->input->get('nik');
		}

		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_sertipikat,ms_kelurahan";
		$dat['type'] = "single";
		$dat['join']['table'] = "tb_register";
		$dat['join']['key']   = "idreg_srt";
		$dat['join']['ref']   = "id_reg";
		$dat['condition']['publish_srt'] = "1";
		$dat['condition']['status_srt'] = "0";
		$dat['cuzcondition'] = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$dat['column'] = "COUNT(id_srt) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/permohonandesa/';
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

		$data['table'] = "tb_sertipikat,ms_kelurahan";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_register,tb_permohonan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,idsrt_pmh,kd_kec";
		$data['join']['ref']   = "id_reg,id_srt,kec_srt";
		$data['condition']['publish_srt']   = "1";
		$data['condition']['status_srt'] = "0";
		$data['cuzcondition'] = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['orderby']['column']    =  'tb_sertipikat.create_at';
		$data['orderby']['sort']      =  'desc';
		$data['column']        = "kd_full,tb_sertipikat.create_at as tgldiajukan,id_srt,kel_srt,kec_srt,nosah_pmh,ref_srt,sert_srt,tracking_pmh,kuasa_pmh,nik_reg,nma_reg,nohp_reg,nma_kec,nope_srt,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function validasisertipikat()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar Pengecekan Validasi Sertipikat";
		$this->content['data']['subtitle'] = array(array("Daftar Pengecekan Validasi Sertipikat","Publics/validasisertipikat"));

		$from  = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		// filter kecamatan
		if($user['level_usr'] != "1"){
			$idusr = $user['idusr_usr'];
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=$idusr)) ORDER BY nma_kec ASC")->result();
		}else{
			$this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
		}
		// filter
		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		if($cari!=0 && $carikelurahan==0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
		}else if($cari!=0 && $carikelurahan!=0){
			$dat['condition']['kdkec_kel'] = $data['condition']['kdkec_kel'] = $cari;
			$dat['condition']['kd_kel'] = $data['condition']['kd_kel'] = $carikelurahan;
		}else if($cari==0 && $carikelurahan!=0){
			$dat['condition']['kd_full'] = $data['condition']['kd_full'] = $carikelurahan;
		}

		if($this->input->get('nik')){
			$dat['condition']['nik_reg'] = $data['condition']['nik_reg'] = $this->input->get('nik');
		}

		$dat['table'] = "tb_sertipikat,ms_kelurahan";
		$dat['type'] = "single";
		$dat['join']['table'] = "tb_register";
		$dat['join']['key']   = "idreg_srt";
		$dat['join']['ref']   = "id_reg";
		$dat['condition']['publish_srt'] = "1";
		$dat['condition']['status_srt'] = "1";
		$dat['column'] = "COUNT(id_srt) as jumlah";
		$dat['cuzcondition'] = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/validasisertipikat/';
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

		$data['table'] = "tb_sertipikat,ms_kelurahan";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_register,tb_permohonan,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,idsrt_pmh,kd_kec";
		$data['join']['ref']   = "id_reg,id_srt,kec_srt";
		$data['condition']['publish_srt']   = "1";
		$data['condition']['status_srt'] = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['orderby']['column']    =  'tb_sertipikat.create_at';
		$data['orderby']['sort']      =  'desc';
		$data['cuzcondition'] = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$data['column']        = "tb_sertipikat.create_at as tgldiajukan,id_srt,tracking_srt,kel_srt,kec_srt,nosah_pmh,ref_srt,sert_srt,tracking_pmh,kuasa_pmh,nik_reg,nma_reg,nohp_reg,nma_kec,nope_srt,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_pengecekan");
		$this->load->view('adm',$this->content);
	}

	public function pengecekan()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Daftar Pengecekan";
		$this->content['data']['subtitle'] = array(array("Daftar Pengecekan","Publics/pengecekan"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_sertipikat";
		$dat['type'] = "single";
		$dat['condition']['alashak_srt'] = "1";
		$dat['column'] = "COUNT(id_srt) as jumlah";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Publics/pengecekan/';
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

		$data['table'] = "tb_sertipikat";
		$data['type'] = "multiple";
		$data['join']['table'] = "tb_register,ms_kecamatan";
		$data['join']['key']   = "id_reg,kd_kec";
		$data['join']['ref']   = "idreg_srt,kec_srt";
		$data['condition']['alashak_srt'] = "1";
		$data['condition']['publish_srt']   = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$data['column']        = "id_srt,nope_srt,status_srt,noref_srt,nik_reg,nma_reg,kel_srt,kec_srt,nma_kec,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("android/data_pengecekan");
		$this->load->view('adm',$this->content);
	}

	public function detail($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Detail User Android";
		$this->content['data']['subtitle'] = array(array("User Android","Publics"),array("Detail User Android","Publics/detail/".$id));

		$data['table'] = "tb_register";
		$data['type'] = "single";
		$data['condition']['id_reg'] = $id;
		$data['join']['table'] = "tb_sertipikat,ms_kelurahan";
		$data['join']['key']   = "idreg_srt,kdfull_reg";
		$data['join']['ref']   = "id_reg,kd_full";
		$data['column']        = "id_reg,nma_kel,alamat_reg,typeusr_reg,idusr_reg,ktp_reg,nik_reg,nma_reg,nohp_reg,tb_register.create_at";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['load'] = array("android/data_user_detail");
		$this->load->view('adm',$this->content);
	}

	public function changerole($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Ganti user role";
		$this->content['data']['subtitle'] = array(array("User Android","Publics"),array("Ganti user role","Publics/changerole/".$id));

		$data['table'] = "tb_register";
		$data['type'] = "single";
		$data['condition']['id_reg'] = $id;
		$data['join']['table'] = "tb_sertipikat,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,kd_kec";
		$data['join']['ref']   = "id_reg,kec_srt";
		$data['column']        = "id_reg,kdfull_reg,typeusr_reg,idusr_reg,ktp_reg,nik_reg,nma_reg,nohp_reg,nma_kec,tb_register.create_at,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);
		$orang = $this->content['studio'];

		if($this->input->post()){
			$this->db->trans_start();
			if($this->input->post('role')==0 || $this->input->post('role')==4){
				$role = array(
					'typeusr_reg'=> $this->input->post('role'),
					'kdfull_reg'=> NULL
				);
			}else{
				$role = array(
					'typeusr_reg'=> $this->input->post('role'),
					'kdfull_reg'=> $this->input->post('kelurahan')
				);
			}

			$updaterole = $this->crud_model->update('tb_register',$role,array('id_reg'=>$id));
			$this->referensi_model->save_android($id,'tb_register','Update Role-'.$orang['nik_reg'].'<br>'.$orang['nma_reg'].'-'.$id,"Update role dengan rincian".displayArray($role));
			$this->db->trans_complete();

			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics">
			<?php
		}

		$kecamatan['table'] = "ms_kecamatan";
		$kecamatan['type'] = "multiple";
		$this->content['kecamatan'] = $this->crud_model->get_data($kecamatan);

		$kelurahan['table'] = "ms_kelurahan";
		$kelurahan['type']  = "single";
		$kelurahan['join']['table']  = "ms_kecamatan";
		$kelurahan['join']['key']  = "kdkec_kel";
		$kelurahan['join']['ref']  = "kd_kec";
		$kelurahan['condition']['kd_full']  = $this->content['studio']['kdfull_reg'];
		$this->content['kelurahan'] = $this->crud_model->get_data($kelurahan);

		$this->content['load'] = array("android/data_user_change");
		$this->load->view('adm',$this->content);
	}

	public function editpermohonan($id)
	{
		$kelurahan = get_kelurahan_sert($id);
		$user = $this->auth_model->get_userdata();
		cekkelurahan($user['idusr_usr'],$user['level_usr'],$kelurahan);
		$this->content['data']['title'] = "Form Edit Permohonan";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan","Publics/permohonandesa"),array("Form Edit Permohonan","Publics/editpermohonan/".$id));

		$selected['table'] = "tb_sertipikat";
		$selected['type'] = "single";
		$selected['join']['table'] = "tb_register";
		$selected['join']['key']   = "id_reg";
		$selected['join']['ref']   = "idreg_srt";
		$selected['condition']['id_srt'] = $id;
		$this->content['selected'] = $this->crud_model->get_data($selected);
		$this->content['userlev']['kec_srt'] = $this->content['selected']['kec_srt'];
		$this->content['userlev']['kel_srt'] = $this->content['selected']['kel_srt'];

		if($this->content['selected']['status_srt']==0){
			$editpermohonan['table'] = "tb_permohonan";
			$editpermohonan['type'] = "single";
			$editpermohonan['condition']['idsrt_pmh'] = $id;
			$this->content['permohonan'] = $this->crud_model->get_data($editpermohonan);
			$this->content['imgsertipikat'] = NULL;
			$this->content['ref'] = explode('-',$this->content['selected']['ref_srt']);

			if($this->content['selected']['sert_srt']==1){
				$this->content['ref'] = explode('.',$this->content['selected']['ref_srt']);
				$this->content['ref'][2]=$this->content['ref'][3]=NULL;
				$imgsert['table'] = "img_sertipikat";
				$imgsert['type'] = "multiple";
				$imgsert['condition']['idsrt_isrt'] = $id;
				$this->content['imgsertipikat'] = $this->crud_model->get_data($imgsert);
			}

			if($this->content['permohonan']['kuasa_pmh']==1){
				$editkuasa['table'] = "tb_kuasa";
				$editkuasa['type']  = "single";
				$editkuasa['join']['table']  = "tb_penduduk";
				$editkuasa['join']['key']    = "idpdk_pdk";
				$editkuasa['join']['ref']    = "idpdk_ksa";
				$editkuasa['condition']['idpmh_ksa'] = $this->content['permohonan']['id_pmh'];
				$this->content['kuasa'] = $this->crud_model->get_data($editkuasa);
			}else{
				$this->content['kuasa'] = NULL;
			}
		}else{
			$this->content['ref'] = explode('.',$this->content['selected']['ref_srt']);
			$this->content['ref'][2]=$this->content['ref'][3]=NULL;
			$editpermohonan['table'] = "img_sertipikat";
			$editpermohonan['type'] = "multiple";
			$editpermohonan['condition']['idsrt_isrt'] = $id;
			$this->content['imgsertipikat'] = $this->crud_model->get_data($editpermohonan);
			$this->content['permohonan'] = NULL;
			$this->content['kuasa'] = NULL;
		}

		if($this->input->post()){
			$this->db->trans_start();

			// CEK DATA PENDUDUK
			$pdk['table'] = "tb_penduduk";
			$pdk['type'] = "single";
			$pdk['condition']['noktp_pdk'] = $this->input->post('nik');
			$cekpdk = $this->crud_model->get_data($pdk);

			if($cekpdk){
				$idpdk_pmh = $cekpdk['idpdk_pdk'];
			}else{
				$inputpenduduk = array(
					'noktp_pdk'=> $this->input->post('nik'),
					'nma_pdk'=> $this->input->post('nama'),
					'almat_pdk'=> $this->input->post('alamat')
				);
				$simpanpenduduk = $this->crud_model->input('tb_penduduk',$inputpenduduk);
				$idpdk_pmh = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$idpdk_pmh,"Menambahkan Data Penduduk dengan rincian ".displayArray($inputpenduduk));
			}

			// CEK REGISTER
			if(!$this->input->post('idreg')){

					//INPUT KTP REGISTER
					$imageregister='';
					if($_FILES["ktp"]['name']){
							$file = explode(".",$_FILES["ktp"]["name"]);
						  $sum = count($file);
						  $nmfile2 					= "KTP"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
			    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
			    		$config2['upload_path']		= './Penduduk/';
			    		$config2['allowed_types']	= '*';
			    		$this->upload->initialize($config2);
			    		$upload2 				= $this->upload->do_upload('ktp');
			    		$data2					= $this->upload->data();
			    		$imageregister 		= $data2['file_name'];
					}

					// 	INPUT REGISTER
					$inputregister = array(
						'nik_reg'=> $this->input->post('nik'),
						'nma_reg'=> $this->input->post('nama'),
						'alamat_reg'=> $this->input->post('alamat'),
						'nohp_reg'=> $this->input->post('nohp'),
						'ktp_reg'=>$imageregister,
						'typeusr_reg'=>0,
						'publish_reg'=>1,
						'idusr_reg'=>0,
						'create_at'=> date('Y-m-d H:i:s')
					);
					$simpanregister = $this->crud_model->input('tb_register',$inputregister);
					$idreg_srt = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_register',$idreg_srt,"Menambahkan Data Register dengan rincian ".displayArray($inputregister));
			}else{
					$idreg_srt=$this->input->post('idreg');
			}

			if($this->input->post('sertipikat')==0){
				$ref = $this->input->post('noc').'-'.$this->input->post('persil').'-'.$this->input->post('klas').'-'.$this->input->post('atna');
			}else{
				$ref = $this->input->post('keperluan').'.'.$this->input->post('ref');
			}

			if($this->input->post('pengajuan')==1){ // CEK IF VALIDASI
				$nomorpermohonan = '-';
			}else{
				// CEK DATA SERTIPIKAT
				$cpmh['table'] = "tb_sertipikat";
				$cpmh['type'] = "single";
				$cpmh['column'] = "count(id_srt) as total";
				$cpmh['cuzcondition']         = "nope_srt!='-'";
				$cpmh['condition']['kel_srt'] = $this->input->post('kelurahan');
				$cpmh['condition']['kec_srt'] = $this->input->post('kecamatan');
				$cekpmh = $this->crud_model->get_data($cpmh);
				if($cekpmh['total']==0){
					$max=1;
				}else{
					$max=$cekpmh['total']+1;
				}
				$nomorpermohonan = '3306/'.$this->input->post('kecamatan').'/'.$this->input->post('kelurahan').'.'.str_pad($max, 5, '0', STR_PAD_LEFT);
			}

			$inputsertipikat = array(
				'idreg_srt'=>$idreg_srt,
				'nope_srt'=>$nomorpermohonan,
				'kec_srt'=>$this->input->post('kecamatan'),
				'kel_srt'=>$this->input->post('kelurahan'),
				'ref_srt'=>$ref,
				'sert_srt'=>$this->input->post('sertipikat'),
				'status_srt'=>$this->input->post('pengajuan')
			);
			$simpansertipikat = $this->crud_model->update('tb_sertipikat',$inputsertipikat,array('id_srt'=>$id));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_sertipikat',$id,"Update Data Sertipikat dengan rincian ".displayArray($inputsertipikat));

			if($this->input->post('pengajuan')==0){
				// INPUT SPPT PBB
				$imagenop=$this->content['permohonan']['imgnop_pmh'];
				if($_FILES["sppt"]['name']){
						$file = explode(".",$_FILES["sppt"]["name"]);
					  $sum = count($file);
					  $nmfilesppt 					= "SPPT"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
		    		$configsppt['file_name'] 		= $nmfilesppt; 				//nama yang terupload nantinya
		    		$configsppt['upload_path']		= './sppt/';
		    		$configsppt['allowed_types']	= '*';
		    		$this->upload->initialize($configsppt);
		    		$uploadsppt 			= $this->upload->do_upload('sppt');
		    		$datasppt					= $this->upload->data();
		    		$imagenop 		    = $datasppt['file_name'];
				}

				// CEK PERMOHONAN
				$cekpmh['table'] = "tb_permohonan";
				$cekpmh['type']  = "single";
				$cekpmh['condition']['idsrt_pmh'] = $id;
				$cekdatapmh = $this->crud_model->get_data($cekpmh);

				if($cekdatapmh){
					$datapermohonan = array(
						'idpdk_pmh'=>$idpdk_pmh,
						'iddhkp_pmh'=>$this->input->post('nop'),
						'utara_pmh'=>$this->input->post('utara'),
						'barat_pmh'=>$this->input->post('barat'),
						'selatan_pmh'=>$this->input->post('selatan'),
						'timur_pmh'=>$this->input->post('timur'),
						'luas_pmh'=>$this->input->post('luas'),
						'kuasa_pmh'=>$this->input->post('pilihkuasa'),
						'idusrform2_pmh'=> $user['idusr_usr'],
		        'dateform2_pmh'=> date('Y-m-d H:i:s'),
						'imgnop_pmh'=>$imagenop,
						'idusr_pmh'=>$user['idusr_usr']
					);

					$simpansertipikat = $this->crud_model->update('tb_permohonan',$datapermohonan,array('id_pmh'=>$cekdatapmh['id_pmh']));
					$idpmh = $cekdatapmh['id_pmh'];
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_permohonan','e Pengukuran Desa-'.$idpmh.'<br>'.$idpdk_pmh.'-&',"Edit Permohonan dengan rincian ".displayArray($datapermohonan));
				}else{
					$datapermohonan = array(
						'idsrt_pmh'=>$id,
						'idpdk_pmh'=>$idpdk_pmh,
						'iddhkp_pmh'=>$this->input->post('nop'),
						'utara_pmh'=>$this->input->post('utara'),
						'barat_pmh'=>$this->input->post('barat'),
						'selatan_pmh'=>$this->input->post('selatan'),
						'timur_pmh'=>$this->input->post('timur'),
						'luas_pmh'=>$this->input->post('luas'),
						'kuasa_pmh'=>$this->input->post('pilihkuasa'),
						'idusrform2_pmh'=> $idusr,
		        'dateform2_pmh'=> date('Y-m-d H:i:s'),
						'tracking_pmh'=>1,
						'status_pmh'=>1,
						'imgnop_pmh'=>$imagenop,
						'create_at'=> date('Y-m-d H:i:s'),
						'idusr_pmh'=>$user['idusr_usr']
					);

					$simpansertipikat = $this->crud_model->input('tb_permohonan',$datapermohonan);
					$idpmh = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_permohonan','e Pengukuran Desa-'.$idpmh.'<br>'.$idpdk_pmh.'-&',"Input Permohonan dengan rincian ".displayArray($datapermohonan));
				}

				// CEK KUASA
				if($this->input->post('pilihkuasa')==1){
					$delete = $this->crud_model->delete('tb_kuasa',array('idpmh_ksa'=>$idpmh));

					// CEK DATA PENDUDUK
					$kuasa['table'] = "tb_penduduk";
					$kuasa['type'] = "single";
					$kuasa['condition']['noktp_pdk'] = $this->input->post('ktp');
					$cekkuasa = $this->crud_model->get_data($kuasa);

					if($cekkuasa){
						$idkuasa = $cekkuasa['idpdk_pdk'];
					}else{
						$ar = array(
							'noktp_pdk' => $this->input->post('ktp'),
							'nma_pdk'   => $this->input->post('namakuasa'),
							'ttl_pdk' => $this->input->post('ttl'),
							'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
							'idpeker_pdk' => $this->input->post('pekerjaan'),
							'agm_pdk' => $this->input->post('agama'),
							'almat_pdk' => $this->input->post('alamat'),
							'publish_pdk' => '1',
							'idusr_pdk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_penduduk',$ar);

						$idkuasa = $this->db->insert_id();
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Pengukuran Desa-&<br>&-'.$idkuasa,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));
					}

					$inputkuasa = array(
						'idpdk_ksa'=>$idkuasa,
						'idpmh_ksa'=>$idpmh
					);
					$simpan = $this->crud_model->input('tb_kuasa',$inputkuasa);
				}else{
					$delete = $this->crud_model->delete('tb_kuasa',array('idpmh_ksa'=>$idpmh));
				}

				// CEK KADES
				$cekkades['table'] = "tb_register";
				$cekkades['type'] = "single";
				$cekkades['condition']['kdfull_reg'] = "(SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=".$this->input->post('kecamatan')." AND kd_kel=".$this->input->post('kelurahan').")";
				$cekkades['condition']['typeusr_reg'] = 1;
				$kades = $this->crud_model->get_data($cekkades);

				// KIRIM NOTIFIKASI
				$msg = array
				(
					'title'		     => "Permohonan Masuk ".$nomorpermohonan ,
					'subtitle'		 => "Pemberitahuan permohonan masuk dengan nomor ".$nomorpermohonan." dari ".$this->input->post('nama'),
					'idpermohonan' => $idpmh,
					'idsertipikat' => $id
				);

				kirim_notifikasi('private',$kades['fcmtoken_reg'],$msg);

			}else{
				if($this->content['permohonan']['imgnop_pmh']!=''){
						unlink('./sertipikat/'.$this->content['permohonan']['imgnop_pmh']);
				}
				$delete = $this->crud_model->delete('tb_permohonan',array('idsrt_pmh'=>$id));

			}

			if($this->input->post('sertipikat')==1){
				// SUDAH SERTIPIKAT
					$count = count($_FILES['berkas']['name']);
					for($i=0;$i<$count;$i++){
							$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
							$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
							$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

							if($_FILES['file']['name']){

								$file = explode(".",$_FILES["berkas"]["name"][$i]);
								$sum = count($file);
								$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
								$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
								$config1['upload_path']		= './sertipikat/';
								$config1['allowed_types']	= '*';
								$this->upload->initialize($config1);
								$uploads 				= $this->upload->do_upload('file');
								$data1					= $this->upload->data();
								$nama_upload 		= $data1['file_name'];

								if($data1){
									$ar = array(
										'idsrt_isrt' => $id,
										'image_isrt' => $nama_upload,
										'idusr_isrt' => $user['idusr_usr'],
			    					'create_at' => date('Y-m-d H:i:s')
									);
									$simpansertipikat = $this->crud_model->input('img_sertipikat',$ar);
									$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'img_sertipikat','e Pengukuran Desa-'.$id.'<br>'.$idpdk_pmh.'-&',"Add sertipikat dengan rincian ".displayArray($ar));
								}
							}
					}
			}else{
				$delete = $this->crud_model->delete('img_sertipikat',array('idsrt_isrt'=>$id));
			}

			$this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics/permohonandesa">
			<?php
		}

		$data['table'] = "ms_kecamatan";
		$data['type'] = "multiple";
		$this->content['kecamatan'] = $this->crud_model->get_data($data);

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$this->content['load'] = array("android/form_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function addpermohonan()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Form Permohonan";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan","Publics/permohonandesa"),array("Form Permohonan","Publics/addpermohonan"));

		if($this->input->post()){
			$this->db->trans_start();

			// CEK DATA PENDUDUK
			$pdk['table'] = "tb_penduduk";
			$pdk['type'] = "single";
			$pdk['condition']['noktp_pdk'] = $this->input->post('nik');
			$cekpdk = $this->crud_model->get_data($pdk);

			if($cekpdk){
				$idpdk_pmh = $cekpdk['idpdk_pdk'];
			}else{
				$inputpenduduk = array(
					'noktp_pdk'=> $this->input->post('nik'),
					'nma_pdk'=> $this->input->post('nama'),
					'almat_pdk'=> $this->input->post('alamat')
				);
				$simpanpenduduk = $this->crud_model->input('tb_penduduk',$inputpenduduk);
				$idpdk_pmh = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$idpdk_pmh,"Menambahkan Data Penduduk dengan rincian ".displayArray($inputpenduduk));
			}

			//DEFINE KTP REGISTER
			$imageregister='';
			if($_FILES["ktp"]['name']){
					$file = explode(".",$_FILES["ktp"]["name"]);
				  $sum = count($file);
				  $nmfile2 					= "KTP"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
	    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
	    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
	    		$config2['upload_path']		= './Penduduk/';
	    		$config2['allowed_types']	= '*';
	    		$this->upload->initialize($config2);
	    		$upload2 				= $this->upload->do_upload('ktp');
	    		$data2					= $this->upload->data();
	    		$imageregister 		= $data2['file_name'];
			}

			if($this->input->post('idreg')){ // CEK SUDAH REGISTER
				$idreg_srt = $this->input->post('idreg');
			}else{ // CEK BELUM REGISTER

				// 	INPUT REGISTER
				$inputregister = array(
					'nik_reg'=> $this->input->post('nik'),
					'nma_reg'=> $this->input->post('nama'),
					'alamat_reg'=> $this->input->post('alamat'),
					'nohp_reg'=> $this->input->post('nohp'),
					'ktp_reg'=>$imageregister,
					'typeusr_reg'=>0,
					'publish_reg'=>1,
					'idusr_reg'=>0,
					'create_at'=> date('Y-m-d H:i:s')
				);
				$simpanregister = $this->crud_model->input('tb_register',$inputregister);
				$idreg_srt = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_register',$idreg_srt,"Menambahkan Data Register dengan rincian ".displayArray($inputregister));
			}

			if($this->input->post('pengajuan')==1){ // CEK IF VALIDASI
				$ref = $this->input->post('keperluan').'.'.$this->input->post('ref');
				$nomorpermohonan = '-';
			}else{ // CEK IF PENGUKURAN

				// CEK DATA SERTIPIKAT
				$cpmh['table'] = "tb_sertipikat";
				$cpmh['type'] = "single";
				$cpmh['column'] = "count(id_srt) as total";
				$cpmh['cuzcondition']         = "nope_srt!='-'";
				$cpmh['condition']['kel_srt'] = $this->input->post('kelurahan');
				$cpmh['condition']['kec_srt'] = $this->input->post('kecamatan');
				$cekpmh = $this->crud_model->get_data($cpmh);
				if($cekpmh['total']==0){
					$max=1;
				}else{
					$max=$cekpmh['total']+1;
				}
				$nomorpermohonan = '3306/'.$this->input->post('kecamatan').'/'.$this->input->post('kelurahan').'.'.str_pad($max, 5, '0', STR_PAD_LEFT);

				if($this->input->post('sertipikat')==1){ // CEK SUDAH SERTIPIKAT
					$ref = $this->input->post('keperluan').'.'.$this->input->post('ref');
				}else{ // CEK BELUM SERTIPIKAT
					$ref = $this->input->post('noc').'-'.$this->input->post('persil').'-'.$this->input->post('klas').'-'.$this->input->post('atna');
				}

			}

			$inputsertipikat = array(
				'idreg_srt'=>$idreg_srt,
				'nope_srt'=>$nomorpermohonan,
				'kec_srt'=>$this->input->post('kecamatan'),
				'kel_srt'=>$this->input->post('kelurahan'),
				'ref_srt'=>$ref,
				'sert_srt'=>$this->input->post('sertipikat'),
				'status_srt'=>$this->input->post('pengajuan'),
				'publish_srt'=>1,
				'create_at'=> date('Y-m-d H:i:s')
			);
			$simpansertipikat = $this->crud_model->input('tb_sertipikat',$inputsertipikat);
			$idsrt_pmh = $this->db->insert_id();
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_sertipikat',$idsrt_pmh,"Menambahkan Data Permohonan dengan rincian ".displayArray($inputsertipikat));

			if($this->input->post('sertipikat')==1){ // CEK SUDAH SERTIPIKAT

					// INPUT MULTIPLE SERTIPIKAT
					$count = count($_FILES['berkas']['name']);
					for($i=0;$i<$count;$i++){
							$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
							$_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
							$_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

							$file = explode(".",$_FILES["berkas"]["name"][$i]);
							$sum = count($file);
							$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
							$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
							$config1['upload_path']		= './sertipikat/';
							$config1['allowed_types']	= '*';
							$this->upload->initialize($config1);
							$uploads 				= $this->upload->do_upload('file');
							$data1					= $this->upload->data();
							$nama_upload 		= $data1['file_name'];

							if($data1){
								$ar = array(
									'idsrt_isrt' => $idsrt_pmh,
									'image_isrt' => $nama_upload,
									'idusr_isrt' => $user['idusr_usr'],
		    					'create_at' => date('Y-m-d H:i:s')
								);
								$simpansertipikat = $this->crud_model->input('img_sertipikat',$ar);
								$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'img_sertipikat','e Pengukuran Desa-'.$idsrt_pmh.'<br>'.$idpdk_pmh.'-&',"Add sertipikat dengan rincian ".displayArray($ar));
							}
					}
			}

			if($this->input->post('pengajuan')==0){ // CEK IF PENGUKURAN

				// INPUT SPPT PBB
				$imagenop='';
				if($_FILES["sppt"]['name']){
						$file = explode(".",$_FILES["sppt"]["name"]);
						$sum = count($file);
						$nmfilesppt 					= "SPPT"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$configsppt['file_name'] 		= $nmfilesppt; 				//nama yang terupload nantinya
						//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
						$configsppt['upload_path']		= './sppt/';
						$configsppt['allowed_types']	= '*';
						$this->upload->initialize($configsppt);
						$uploadsppt 				= $this->upload->do_upload('sppt');
						$datasppt					= $this->upload->data();
						$imagenop 		    = $datasppt['file_name'];
				}

				// INPUT PERMOHONAN
				$datapermohonan = array(
					'idsrt_pmh'=>$idsrt_pmh,
					'idpdk_pmh'=>$idpdk_pmh,
					'iddhkp_pmh'=>$this->input->post('nop'),
					'utara_pmh'=>$this->input->post('utara'),
					'barat_pmh'=>$this->input->post('barat'),
					'selatan_pmh'=>$this->input->post('selatan'),
					'timur_pmh'=>$this->input->post('timur'),
					'luas_pmh'=>$this->input->post('luas'),
					'kuasa_pmh'=>$this->input->post('pilihkuasa'),
					'idusrform2_pmh'=> $user['idusr_usr'],
	        'dateform2_pmh'=> date('Y-m-d H:i:s'),
					'tracking_pmh'=>1,
					'status_pmh'=>1,
					'imgnop_pmh'=>$imagenop,
					'create_at'=> date('Y-m-d H:i;s'),
					'idusr_pmh'=>$user['idusr_usr']
				);

				$simpansertipikat = $this->crud_model->input('tb_permohonan',$datapermohonan);
				$idpmh = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_permohonan','e Pengukuran Desa-'.$idpmh.'<br>'.$idpdk_pmh.'-&',"Add Permohonan dengan rincian ".displayArray($datapermohonan));

				// CEK KUASA
				if($this->input->post('pilihkuasa')==1){
					// CEK DATA PENDUDUK
					$kuasa['table'] = "tb_penduduk";
					$kuasa['type'] = "single";
					$kuasa['condition']['noktp_pdk'] = $this->input->post('ktp');
					$cekkuasa = $this->crud_model->get_data($kuasa);

					if($cekkuasa){
						$idkuasa = $cekkuasa['idpdk_pdk'];
					}else{
						$ar = array(
							'noktp_pdk' => $this->input->post('ktp'),
							'nma_pdk'   => $this->input->post('namakuasa'),
							'ttl_pdk' => $this->input->post('ttl'),
							'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
							'idpeker_pdk' => $this->input->post('pekerjaan'),
							'agm_pdk' => $this->input->post('agama'),
							'almat_pdk' => $this->input->post('alamat'),
							'publish_pdk' => '1',
							'idusr_pdk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_penduduk',$ar);

						$idkuasa = $this->db->insert_id();
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk','e Pengukuran Desa-&<br>&-'.$idkuasa,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));
					}

					$inputkuasa = array(
						'idpdk_ksa'=>$idkuasa,
						'idpmh_ksa'=>$idpmh
					);
					$simpan = $this->crud_model->input('tb_kuasa',$inputkuasa);
				}

					// CEK KADES
					$cekkades['table'] = "tb_register";
					$cekkades['type'] = "single";
					$cekkades['condition']['kdfull_reg'] = "(SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=".$this->input->post('kecamatan')." AND kd_kel=".$this->input->post('kelurahan').")";
					$cekkades['condition']['typeusr_reg'] = 1;
					$kades = $this->crud_model->get_data($cekkades);

					// KIRIM NOTIFIKASI
					$msg = array
		      (
		      	'title'		     => "Permohonan Masuk ".$nomorpermohonan ,
						'subtitle'		 => "Pemberitahuan permohonan masuk dengan nomor ".$nomorpermohonan." dari ".$this->input->post('nama'),
		      	'idpermohonan' => $idpmh,
		        'idsertipikat' => $idsrt_pmh
		      );

					kirim_notifikasi('private',$kades['fcmtoken_reg'],$msg);

			}

			$this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics/permohonandesa">
			<?php
		}

		$data['table'] = "ms_kecamatan";
		$data['type'] = "multiple";
		$this->content['kecamatan'] = $this->crud_model->get_data($data);

		$dat['table'] = "tb_pekerjaan";
		$dat['type'] = "multiple";
		$dat['orderby']['column'] = 'nama_pkr';
		$dat['orderby']['sort'] = 'asc';
		$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

		$this->content['selected']= NULL;
		$this->content['userlev'] = NULL;
		if($user['level_usr']==7){
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['type'] = "single";
			$kelurahan['condition']['kd_full'] = $user['kdfull_reg'];
			$kel = $this->crud_model->get_data($kelurahan);
			$this->content['userlev']['kec_srt'] = $kel['kdkec_kel'];
			$this->content['userlev']['kel_srt'] = $kel['kd_kel'];
		}

		$this->content['permohonan'] = NULL;
		$this->content['kuasa'] = NULL;
		$this->content['imgsertipikat'] = NULL;
		$this->content['ref'] = NULL;

		$this->content['load'] = array("android/form_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function detailpermohonan($id)
	{
		$kelurahan = get_kelurahan_sert($id);
		$user = $this->auth_model->get_userdata();
		cekkelurahan($user['idusr_usr'],$user['level_usr'],$kelurahan);
		$this->content['data']['title'] = "Detail Permohonan Layanan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan Ukur","Publics/permohonandesa"),array("Detail Permohonan ukur","Publics/detailpermohonan/".$id));

		$data['table'] = "tb_sertipikat";
		$data['type'] = "single";
		$data['condition']['id_srt'] = $id;
		$data['join']['table'] = "tb_register,tb_permohonan,ms_kecamatan,tb_dhkp,tb_block";
		$data['join']['key']   = "idreg_srt,id_srt,kd_kec,id_dhkp,idblk_blk";
		$data['join']['ref']   = "id_reg,idsrt_pmh,kec_srt,iddhkp_pmh,idblk_dhkp";
		$data['column']        = "*,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel,
															tb_permohonan.create_at as tanggaldiajukan";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$image['table'] = "img_sertipikat";
		$image['type'] = "multiple";
		$image['condition']['idsrt_isrt'] = $id;
		$this->content['image'] = $this->crud_model->get_data($image);

		$this->content['load'] = array("android/data_permohonan_detail");
		$this->load->view('adm',$this->content);
	}

	public function detailpengecekan($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Detail Permohonan Sudah Sertipikat";
		$this->content['data']['subtitle'] = array(array("Daftar Validasi Sertipikat","Publics/validasisertipikat"),array("Detail Validasi sertipikat","Publics/detailpengecekan/".$id));

		$data['table'] = "tb_sertipikat";
		$data['type'] = "single";
		$data['condition']['id_srt'] = $id;
		$data['join']['table'] = "tb_register,ms_kecamatan";
		$data['join']['key']   = "idreg_srt,kd_kec";
		$data['join']['ref']   = "id_reg,kec_srt";
		$data['column']        = "*,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$image['table'] = "img_sertipikat";
		$image['type'] = "multiple";
		$image['condition']['idsrt_isrt'] = $id;
		$this->content['image'] = $this->crud_model->get_data($image);

		$this->content['load'] = array("android/data_pengecekan_detail");
		$this->load->view('adm',$this->content);
	}

	public function delete($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Detail User Android";
		$this->content['data']['subtitle'] = array(array("User Android","Publics"),array("Detail User Android","Publics/".$id));

		$data['table'] = "tb_register";
		$data['type'] = "single";
		$data['condition']['id_reg'] = $id;
		$selected = $this->crud_model->get_data($data);

		$ar = array(
			'publish_reg' => 0
		);

		$hapus = $this->crud_model->update('tb_register',$ar,array('id_reg'=>$id));
		$this->referensi_model->save_android($user['idusr_usr'],'tb_register','Hapus User dengan id-'.$id.'<br>&-&',"Hapus user android dengan rincian".displayArray($selected));

		if($hapus){
			$msg = true;
		}
		echo json_encode($msg);die();
	}

	public function export($id){
			$kelurahan = get_kelurahan_sert($id);
			$user = $this->auth_model->get_userdata();
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$kelurahan);

			define('FPDF_FONTPATH',APPPATH .'plugins/fpdf/font/');
			require(APPPATH .'plugins/fpdf/fpdf.php');

			$berkas['type']                   = "single";
			$berkas['table']                  = "tb_sertipikat";
			$berkas['join']['table'] 				= "tb_permohonan";
			$berkas['join']['key'] 					= "idsrt_pmh";
			$berkas['join']['ref'] 					= "id_srt";
			$berkas['condition']['id_srt'] = $id;
			$data                        = $this->crud_model->get_data($berkas);

			$pdk['table'] = "tb_penduduk";
			$pdk['type'] = "single";
			$pdk['condition']['idpdk_pdk'] = $data['idpdk_pmh'];
			$pdk['join']['table'] = "tb_pekerjaan";
			$pdk['join']['key'] = "idpkr_pkr";
			$pdk['join']['ref'] = "idpeker_pdk";
			$datpdk = $this->crud_model->get_data($pdk);



			$kec['table'] = "ms_kelurahan";
			$kec['type'] = "single";
			$kec['column'] = "nma_kel,nma_kec,kades_spt";
			$kec['join']['table'] = 'ms_kecamatan,tb_saksiptsl';
			$kec['join']['key'] = 'kd_kec,kd_full';
			$kec['join']['ref'] = 'kdkec_kel,idkel_spt';
			$kec['condition']['kd_kel'] = $data['kel_srt'];
			$kec['condition']['kdkec_kel'] = $data['kec_srt'];
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
			}else{
				$agama='';
			}

			$user = $this->auth_model->get_userdata();

				$pdf = new FPDF('p','mm',array(210,330));

				$pdf -> setDisplayMode ('fullpage');
				$row = 15;

				// SURAT 4garuda.png
				$pdf -> AddPage();
				$row =10;

				$row +=40;
				$pdf -> Image("./assets/img/garuda.png",92,15,25,'C');
				$pdf->SetFont('Times','BU',15);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"KUTIPAN DAFTAR BUKU C",0,0,'C');
				$row +=10;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Desa : ".$kecamatan['nma_kel']);
				$pdf -> setXY(150,$row); $pdf->Cell(0,0,"Kecamatan : ".$kecamatan['nma_kec']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama Pemilik Tanah");
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,":");
				$pdf->SetFont('Times','B',12);
				$pdf -> setXY(72,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
				$pdf->SetFont('Times','',12);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Tempat Tinggal");
				$pdf -> setXY(70,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(72,$row); $pdf->Cell(0,0,$datpdk['almat_pdk']);

				$row +=5;
				$row2 = $row;
				$pdf -> SetFillColor(200,200,200);
				$pdf->SetFont('Times','B',12);
				$pdf -> setXY(20,$row); $pdf->Cell(80,8,"SAWAH",1,1,'C',1);
				$pdf->SetFont('Times','',12);
				$row +=8;
				$pdf -> rect(20, $row, 15, 21);
				$pdf -> setXY(20+1,$row); $pdf->MultiCell(13,5,"Blok dan No. Blok",0,'C');
				$pdf -> rect(35, $row, 45, 11);
				$pdf -> setXY(35+3,$row+1); $pdf->MultiCell(40,4,"Menurut daftar perincian",0,'C');
				$pdf -> rect(80, $row, 20, 21);
				$pdf -> setXY(80,$row); $pdf->MultiCell(20,5,"Sebab   dan tanggal perubahan",0,'C');
				$row +=11;
				$pdf -> setXY(35,$row); $pdf->Cell(25,5,"Luas Milik",1,1,'C');
				$pdf -> setXY(60,$row); $pdf->Cell(20,5,"Pajak",1,1,'C');
				$row +=5;
				$pdf -> setXY(35,$row); $pdf->Cell(10,5,"Ha",1,1,'C');
				$pdf -> setXY(45,$row); $pdf->Cell(15,5,"m2",1,1,'C');
				$pdf -> setXY(60,$row); $pdf->Cell(10,5,"Ha",1,1,'C');
				$pdf -> setXY(70,$row); $pdf->Cell(10,5,"S",1,1,'C');
				$row +=5;
				$pdf -> rect(20, $row, 15, 30);
				$pdf -> rect(35, $row, 10, 30);
				$pdf -> rect(45, $row, 15, 30);
				$pdf -> rect(60, $row, 10, 30);
				$pdf -> rect(70, $row, 10, 30);
				$pdf -> rect(80, $row, 20, 30);

				$pdf->SetFont('Times','B',12);
				$pdf -> setXY(110,$row2); $pdf->Cell(80,8,"TANAH KERING",1,1,'C',1);
				$pdf->SetFont('Times','',12);
				$row2 +=8;
				$pdf -> rect(110, $row2, 15, 21);
				$pdf -> setXY(110+1,$row2); $pdf->MultiCell(13,5,"Blok dan No. Blok",0,'C');
				$pdf -> rect(125, $row2, 45, 11);
				$pdf -> setXY(125+3,$row2+1); $pdf->MultiCell(40,4,"Menurut daftar perincian",0,'C');
				$pdf -> rect(170, $row2, 20, 21);
				$pdf -> setXY(170,$row2); $pdf->MultiCell(20,5,"Sebab   dan tanggal perubahan",0,'C');
				$row2 +=11;
				$pdf -> setXY(125,$row2); $pdf->Cell(25,5,"Luas Milik",1,1,'C');
				$pdf -> setXY(150,$row2); $pdf->Cell(20,5,"Pajak",1,1,'C');
				$row2 +=5;
				$pdf -> setXY(125,$row2); $pdf->Cell(10,5,"Ha",1,1,'C');
				$pdf -> setXY(135,$row2); $pdf->Cell(15,5,"m2",1,1,'C');
				$pdf -> setXY(150,$row2); $pdf->Cell(10,5,"Ha",1,1,'C');
				$pdf -> setXY(160,$row2); $pdf->Cell(10,5,"S",1,1,'C');
				$pdf -> rect(110, $row, 15, 30);
				$pdf -> rect(125, $row, 10, 30);
				$pdf -> rect(135, $row, 15, 30);
				$pdf -> rect(150, $row, 10, 30);
				$pdf -> rect(160, $row, 10, 30);
				$pdf -> rect(170, $row, 20, 30);

				$row +=35;
				$pdf->SetFont('Times','B',12);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Gambar Situasi Tanah :");
				$pdf->SetFont('Times','',12);
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Lokasi :");
				$row +=8;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Darat Pekuwon");
				$row +=40;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,'Turunan telah sesuai dengan "Daftar Asli"');
				$row +=10;
				$pdf -> setXY(135,$row); $pdf->Cell(50,0,$kecamatan['nma_kel'].' , '.fdate(date('Y-m-d'),'DDMMYYYY'),0,0,'C');
				$row +=5;
				$pdf -> setXY(135,$row); $pdf->Cell(50,0,'Kepala Desa '.$kecamatan['nma_kel'],0,0,'C');
				$row +=40;
				$pdf -> setXY(135,$row); $pdf->Cell(50,0,$kecamatan['kades_spt'],0,0,'C');


				$pdf -> AddPage();
				// $pdf -> rect(165, 10, 35, 10);
				//$pdf -> setXY(170,15); $pdf->Cell(0,0,$data['nub_ptsl'].' / Block '.$datblock['nama_blk'],0,0,'L');
				$row =10;

				$row +=14;
				$pdf->SetFont('Times','B',14);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"SURAT PERNYATAAN PEMASANGAN TANDA BATAS",0,0,'C');
				$row +=10;
				$pdf->SetFont('Times','',12);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Yang bertanda tangan di bawah ini :");
				$row +=5;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Nama");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				$pdf->SetFont('Times','B',11);
				$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nma_pdk']);
				$pdf->SetFont('Times','',11);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"NIK");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['noktp_pdk']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Agama");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(53,$row); $pdf->Cell(0,0,$agama);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Usia/ Tgl Lahir");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				//$pdf -> setXY(53,$row); $pdf->Cell(0,0,$diff->format('%y')." tahun / ".fdate($datpdk['ttg_pdk'],'DDMMYYYY'));
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Pekerjaan");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				$pdf -> setXY(53,$row); $pdf->Cell(0,0,$datpdk['nama_pkr']);
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Alamat");
				$pdf -> setXY(50,$row); $pdf->Cell(0,0,":");
				$row -=2;
				$pdf -> setXY(53,$row); $pdf->MultiCell(0,5,$datpdk['almat_pdk']);
				$row +=10;
				// $pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Selaku pemilik tanah/pemohon pengukuran tanah bekas adat/yasan C Desa No. ".$data['dc_ptsl']." Persil ".$data['dpersil_ptsl']." Klas ".$data['dklas_ptsl']." seluas ".$data['dluas_ptsl']." m2, dipergunakan untuk ".$guna." terletak di Desa ".$kecamatan['nma_kel']." Kecamatan ".$kecamatan['nma_kec']." Kabupaten Semarang.");
				$row +=15;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"Dengan ini menyatakan sebenar-benarnya :");
				$row +=5;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"1. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa tanah yang kami mohonkan pengukuran di Kantor Pertanahan Kabupaten Semarang berdasarkan alas hak tersebut diatas tidak dalam jaminan sesuatu hutang, tidak diletakkan sita jaminan dan telah kami pasang tanda-tanda batasnya sesuai ketentuan yang berlaku, berupa Patok Beton.");
				$row +=18;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"2. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa pemasangan tanda-tanda tanah serta batas-batas tanah yang secara fisik telah ada di lapangan, sudah disepakati dan disetujui oleh para pemilik tanah yang berbatasan dengan bukti ditanda tanganinya surat pernyataan ini oleh kami pemilik tanah dan para pemilik tanah yang berbatasan.");
				$row +=18;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"3. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa dengan dipasangnya tanda-tanda batas tanah serta batas-batas tanah yang telah ada dan sudah disepakati/disetujui, apabila dalam pengukurannya oleh petugas dari Kantor Pertanahan para pemilik tanah yang berbatasan tidak dapat hadir menyaksikan, dengan ini kami pemilik tanah bertindak sebagai penunjuk batas dan para pemilik tanah yang berbatasan telah menyepakati/menyetujui dan tanda tangan persetujuan batas dalam surat pernyataan ini dapat berlaku pula sebagai persetujuan yang sah untuk dokumen Gambar Ukur dan dokumen pengukuran lainnya.");
				$row +=33;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"4. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Apabila hasil pengukuran dari Kantor Pertanahan berdasarkan batas tanah yang telah disepakati/disetujui tersebut telah terjadi perbedaan (lebih besar atau lebih kecil) dengan luas yang tertera dalam alas hak/ permohonan kami pemilik tanah maupun para pemilik tanah yang berbatasan menyetujui dan menerima luas hasil pengukuran tersebut tanpa syarat apapun, selanjutnya apabila dikemudian hari ada pihak-pihak yang keberatan/diragukan atas hasil pengukuran tersebut kami akan mempertanggungjawabkan secara hukum baik secara perdata maupun pidana.");
				$row +=33;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"5. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Bahwa kami menyatakan melepaskan sebagian tanah kami seluas ......... m2. Kepada Negara untuk kepentingan umum/fasilitas umum yang dipergunakan untuk (jalan,saluran,taman............*)");
				$row +=12;
				$pdf -> setXY(20,$row); $pdf->Cell(0,0,"6. ");
				$row -=2.5;
				$pdf -> setXY(25,$row); $pdf->MultiCell(0,5,"Apabila dikemudian hari ternyata isi surat pernyataan kami ini tidak benar maka kami bersedia menerima sanksi hukum sesuai ketentuan yang berlaku.");
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->MultiCell(0,5,"Demikian Surat Pernyataan ini kami buat dengan sebenar-benarnya dengan penuh tanggung jawab tanpa ada tekanan atau paksaan dari manapun juga.");
				$row +=13;
				// $pdf -> setXY(120,$row); $pdf->Cell(0,5,"Semarang ,".fdate($datsaksi['tgl_spt'],'DDMMYYYY'),0,0,'C');
				$row +=5;
				$pdf->SetFont('Times','B',11);
				$pdf -> setXY(120,$row); $pdf->Cell(0,5,"Yang Menyatakan",0,0,'C');
				$row +=5;
				$pdf->SetFont('Times','',11);
				$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Pemilik Tanah yang berbatasan,");
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Utara");
				$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
				$pdf -> setFont ('Times','',9);
				$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['utara_pmh']);
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Timur");
				$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
				$pdf -> setFont ('Times','',9);
				$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['timur_pmh']);
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
				$pdf->SetFont('Times','I',10);
				$pdf -> setXY(125,$row-13); $pdf->Cell(0,5,"materai 6.000,-");
				$pdf->SetFont('Times','',11);
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Selatan");
				$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
				$pdf -> setFont ('Times','',9);
				$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['selatan_pmh']);
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
				$row +=10;
				$pdf -> setXY(20,$row); $pdf->Cell(0,5,"Barat");
				$pdf -> setXY(40,$row); $pdf->Cell(0,5,":");
				$pdf -> setFont ('Times','',9);
				$pdf -> setXY(42,$row); $pdf->Cell(0,5,$data['barat_pmh']);
				$pdf -> setFont ('Times','',11);
				$pdf -> setXY(90,$row); $pdf->Cell(0,5,"(..................)");
				$pdf -> setXY(120,$row-20); $pdf->Cell(0,5,$datpdk['nma_pdk'],0,0,'C');
				$row +=10;
				$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Mengetahui",0,0,'C');
				$row +=4;
				$pdf -> setXY(0,$row); $pdf->Cell(0,5,"Kepala Desa ".$kecamatan['nma_kel'],0,0,'C');
				$row +=25;
				$pdf -> setXY(0,$row); $pdf->Cell(0,5,$kecamatan['kades_spt'],0,0,'C');

			$pdf->Output();
		}

}
