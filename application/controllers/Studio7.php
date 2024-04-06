<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Studio7 extends CI_Controller
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
		$this->content['data']['title_page'] = 'Studio 2';
		$this->load->view('auth/authorized');
	}

	public function index()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = " e-Yuridis";
		$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio7"));

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

		$config['base_url'] = base_url().'Studio7/index/';
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

		$this->content['studio'] = $this->studio3_model->show_dataluasshat($config['per_page'],$from,$cari,$carikelurahan);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("studio7/data_studio_7");
		$this->load->view('adm',$this->content);
	}

	function dashboard()
	{
		$this->content['data']['title'] = " Dashboard Pemetaan";
		$this->content['data']['subtitle'] = array(array("daftar kompetitif","Studio6/dashboard"));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$cari = $this->input->get('filter');
		$carikelurahan=$this->input->get('filterkelurahan');

		$dat['table'] = "tb_targetuser";
		$dat['type'] = "single";
		$dat['column'] = "COUNT(kdfull_tgt) as jumlah";
		$dat['groupby'] = "kdfull_tgt";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$this->content['studio'] = $this->studio3_model->get_rank3(50,$from,$cari,$carikelurahan);


		$this->content['load'] = array("studio3/dashboard");
		$this->load->view('adm',$this->content);
	}

	function target()
	{
		$this->content['data']['title'] = "Target SHAT";
		$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio7"),array("input target SHAT","Studio7/target?target=".$this->input->get('target')));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] 	= "tb_targetshat";
		$dat['type'] 	= "multiple";
		$dat['column']	= "target_tgt,tahun_tgt";
		$dat['condition']['kdfull_tgt'] = $this->input->get('target');
		$dat['orderby']['column'] 	= "create_at";
		$dat['orderby']['sort']		= "DESC";
		$this->content['target'] = $this->crud_model->get_data($dat);

		if ($this->input->post()) {

			$data = $this->input->post();
			$this->crud_model->delete('tb_targetshat',array('kdfull_tgt'=>$data['kdfull']));

			$count = count($this->input->post('tahun'));

			for($i=0;$i<$count;$i++){
					if($data['tahun'][$i]!=''){
						$data_input ['idusr_tgt']	= $this->content['data']['user']['idusr_usr'];
						$data_input ['kdfull_tgt']	= $data['kdfull'];
						$data_input ['tahun_tgt'] 	= $data['tahun'][$i];
						$data_input ['target_tgt']	= $data['target'][$i];

						$input = $this->crud_model->input('tb_targetshat', $data_input);
					}
			}

			if ($input) {
				// save log
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_targetshat','e Yuridis-0-'.$insert_id,"input data target SHAT dengan rincian ".displayArray($data_input));
				?><meta http-equiv="refresh" content="1;<?php echo base_url();?>studio7"><?php
			}
		}

		$this->content['load'] = array("studio3/form_target");
		$this->load->view('adm',$this->content);
	}

	public function petaonline($cari){
		$user = $this->auth_model->get_userdata();

		$dat['table'] = "ms_kelurahan";
		$dat['type'] = "single";
		$dat['condition']['kd_full'] = $cari;
		$hasil = $this->crud_model->get_data($dat);

		$desa['table'] = "tb_block";
		$desa['type'] = "multiple";
		$desa['condition']['idkel_blk'] = $cari;
		$this->content['blok'] = $this->crud_model->get_data($desa);

		$this->content['peta'] = $hasil;

		$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'];
		$this->content['data']['subtitle'] = array(array("e-Yuridis","Studio7"),array("Peta Online ".$hasil['nma_kel'],"Studio7/petaonline/".$cari));

		$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->content['load'] = array("studio6/petadesa");
		$this->load->view('adm',$this->content);
	}
}
