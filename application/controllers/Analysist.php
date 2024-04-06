<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Analysist extends CI_Controller{

  var $userdata = NULL;
	public function __construct (){
		parent::__construct();

    if(isset($this->session->userdata['smt_member'])){
      $this->content['data']['user'] = $this->auth_model->get_userdata();
    }

		date_default_timezone_set('Asia/Jakarta');
        $this->load->view('auth/authorized');
	}


    public function index($id){
      $this->content['data']['title'] = " Analysist Data";
      $this->content['data']['subtitle'] = array(array("Analysist Data","home"));
      $this->content['type']=1;
      $kelurahan=$this->input->get('kelurahan');
      $kecamatan=$this->input->get('kecamatan');
      $month=$this->input->get('month');

      if($id==1){

        $from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $tdata['table'] = "tb_penduduk";
        $tdata['type'] = "single";
        $tdata['cuzcondition'] = "LENGTH(noktp_pdk) != 16";
        $tdata['column'] = "COUNT(idpdk_pdk) as jumlah";
        if($kecamatan!=0 && $kelurahan!=0){
          $tdata['join']['table'] = "tb_ptsl,tb_block,ms_kelurahan";
          $tdata['join']['key'] = "idpdk_ptsl,idblk_blk,kd_full";
          $tdata['join']['ref'] = "idpdk_pdk,idblk_ptsl,idkel_blk";
          $tdata['condition']['kdkec_kel'] = $kecamatan;
          $tdata['condition']['kd_kel'] = $kelurahan;
          if($month){
            $tdata['like']['tb_ptsl.create_at'] = $month;
          }

        }elseif($kecamatan!=0){
          $tdata['join']['table'] = "tb_ptsl,tb_block,ms_kelurahan";
          $tdata['join']['key'] = "idpdk_ptsl,idblk_blk,kd_full";
          $tdata['join']['ref'] = "idpdk_pdk,idblk_ptsl,idkel_blk";
          $tdata['condition']['kdkec_kel'] = $kecamatan;
          if($month){
            $tdata['like']['tb_ptsl.create_at'] = $month;
          }

        }elseif($month){
          $tdata['join']['table'] = "tb_ptsl";
          $tdata['join']['key'] = "idpdk_ptsl";
          $tdata['join']['ref'] = "idpdk_pdk";
          $tdata['like']['tb_ptsl.create_at'] = $month;
        }


        $ttdata = $this->crud_model->get_data($tdata);


        $config['base_url'] = base_url().'Analysist/index/'.$id;
        $config['total_rows'] = $ttdata['jumlah'];
        $config['uri_segment'] = 4;
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
        $dat['column'] = "idpdk_pdk,noktp_pdk,nma_pdk,almat_pdk,kel_pdk,kec_pdk,kab_pdk,nib_ptsl,id_ptsl,idblk_ptsl,tb_ptsl.create_at as dibuat";
        $dat['join']['table'] = "tb_ptsl,tb_block,ms_kelurahan";
        $dat['join']['key'] = "idpdk_ptsl,idblk_blk,kd_full";
        $dat['join']['ref'] = "idpdk_pdk,idblk_ptsl,idkel_blk";
        if($kecamatan!=0 && $kelurahan!=0){
          $dat['condition']['kd_kel'] = $kelurahan;
          $dat['condition']['kdkec_kel'] = $kecamatan;
        }elseif($kecamatan!=0){
          $dat['condition']['kdkec_kel'] = $kecamatan;
        }
        $dat['cuzcondition'] = "LENGTH(noktp_pdk) != 16";

        if($from!=0){
          $dat['limit']['lim'] = 10;
          $dat['limit']['first'] = $from;
        }else{
          $dat['limit'] = 10;
        }

        if($month){
          $dat['like']['tb_ptsl.create_at'] = $month;
        }

        $this->content['type']=1;
        
        $this->content['studio'] = $this->crud_model->get_data($dat);
        $this->content['link'] = $this->pagination->create_links();

      }else if($id==2){
        $from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $tdata['table'] = "tb_ptsl";
        $tdata['type'] = "multiple";
        $tdata['cuzcondition'] = "nib_ptsl!='' group by nib_ptsl,idblk_ptsl having count(nib_ptsl)>1";
        if($kecamatan!=0 && $kelurahan!=0){
          $tdata['join']['table'] = "tb_block,ms_kelurahan";
          $tdata['join']['key'] = "idblk_blk,kd_full";
          $tdata['join']['ref'] = "idblk_ptsl,idkel_blk";
          $tdata['condition']['kdkec_kel'] = $kecamatan;
          $tdata['condition']['kd_kel'] = $kelurahan;
        }elseif($kecamatan!=0){
          $tdata['join']['table'] = "tb_block,ms_kelurahan";
          $tdata['join']['key'] = "idblk_blk,kd_full";
          $tdata['join']['ref'] = "idblk_ptsl,idkel_blk";
          $tdata['condition']['kdkec_kel'] = $kecamatan;
        }
        $ttdata = $this->crud_model->get_data($tdata);

        $config['base_url'] = base_url().'Analysist/index/'.$id;
        $config['total_rows'] = count($ttdata);
        $config['uri_segment'] = 4;
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

        $dat['table'] = "tb_ptsl";
        $dat['type'] = "multiple";
        $dat['join']['table'] = "tb_block,ms_kelurahan,ms_kecamatan";
        $dat['join']['key'] = "idblk_blk,kd_full,kd_kec";
        $dat['join']['ref'] = "idblk_ptsl,idkel_blk,kdkec_kel";
        if($kecamatan!=0 && $kelurahan!=0){
          $dat['condition']['kdkec_kel'] = $kecamatan;
          $dat['condition']['kd_kel'] = $kelurahan;
        }elseif($kecamatan!=0){
          $dat['condition']['kdkec_kel'] = $kecamatan;
        }
        $dat['column'] = "id_ptsl,nib_ptsl,count(nib_ptsl),idblk_ptsl,nma_kec,nma_kel,nama_blk,kd_full";
        $dat['cuzcondition'] = "nib_ptsl!='' group by nib_ptsl,idblk_ptsl having count(nib_ptsl)>1";
        

        if($from!=0){
          $dat['limit']['lim'] = 10;
          $dat['limit']['first'] = $from;
        }else{
          $dat['limit'] = 10;
        }

        $this->content['type']=2;
        
        $this->content['studio'] = $this->crud_model->get_data($dat);
        $this->content['link'] = $this->pagination->create_links();
      }

      $this->content['filter_kecamatan'] = $this->db->query("SELECT kd_kec,nma_kec FROM `ms_kecamatan` ORDER BY nma_kec ASC")->result();
      $this->content['load'] = array("beranda/issue");
      $this->load->view('adm',$this->content);

    }

    public function aduan(){
      $this->content['data']['title'] = "Aduan Aplikasi";
      $this->content['data']['subtitle'] = array(array("Aduan Aplikasi","home"));

      $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
  
      $this->content['data']['messages'] 	= null;
      $this->content['load'] 		= array("beranda/aduan");
  
      $user = $this->auth_model->get_userdata();

      $tdata['type']          = "single";
      $tdata['table']					= "tb_keluhan";
      $tdata['column'] = "COUNT(id) as jumlah";
      $ttdata = $this->crud_model->get_data($tdata);


        $config['base_url'] = base_url().'Analysist/aduan/';
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

        $usr['type']          = "multiple";
        $usr['table']					= "tb_keluhan";
        $usr['join']['table'] = "ms_users";
        $usr['join']['key']   = "idusr_scr";
        $usr['join']['ref']   = "idusr_usr";
        $usr['orderby']['column'] = 'created_at';
			  $usr['orderby']['sort'] = 'desc';

        if($from!=0){
          $usr['limit']['lim'] = 10;
          $usr['limit']['first'] = $from;
        }else{
          $usr['limit'] = 10;
        }
        $this->content['studio']     = $this->crud_model->get_data($usr);
        $this->content['link'] = $this->pagination->create_links();
  
      $this->load->view('adm',$this->content);
    }





}
