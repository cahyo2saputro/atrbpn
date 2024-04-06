<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller{

  var $userdata = NULL;
	public function __construct (){
		parent::__construct();

    if(isset($this->session->userdata['smt_member'])){
      $this->content['data']['user'] = $this->auth_model->get_userdata();
    }


		date_default_timezone_set('Asia/Jakarta');
        $this->load->view('auth/authorized');
	}


    public function index(){
      $this->content['data']['title'] = " Dashboard Progress PTSL";
      $this->content['data']['subtitle'] = array(array("daftar Progress PTSL","home"));

      $from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $cari = $this->input->get('filter');
      $carikelurahan=$this->input->get('filterkelurahan');

      $dat['table'] = "tb_targetuser";
      $dat['type'] = "single";
      $dat['column'] = "COUNT(kdfull_tgt) as jumlah";
      $dat['groupby'] = "kdfull_tgt";
      $hasil = $this->crud_model->get_data($dat);
      $t_data=$hasil['jumlah'];

      $config['per_page'] = 100;

  		$tahun=date('Y');
      $this->content['tahun']=date('Y');
      if($this->input->get('tahun')){
				$this->content['tahun']=$this->input->get('tahun');
        $tahun=$this->input->get('tahun');
			}

      $this->content['studio'] = $this->studio3_model->get_rankfull($config['per_page'],$from,$cari,$carikelurahan,$tahun);

      $this->content['user'] = $this->auth_model->get_userdata();

      $this->content['load'] = array("beranda/beranda");
      $this->load->view('adm',$this->content);

    }





}
