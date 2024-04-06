<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Studioppat extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}

			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];

			$this->iduser = $usr['idusr_usr'];

			$user = $this->auth_model->get_userdata();
			cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);

			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Staff';
			$this->load->view('auth/authorized');
		}

		public function index()
		{

			$this->content['data']['title'] = "E-PPAT";
			$this->content['data']['subtitle'] = array(array("e-ppat","Studioppat"));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_hakppat";
      $tdata['type'] = "single";
			if($this->input->get('user')){
					$tdata['condition']['idppat_hpat']=$this->input->get('user');
			}
			if($this->input->get('nohak')){
					$tdata['condition']['nohak_hpat']=$this->input->get('nohak');
			}
			if($this->input->get('bulan')){
					$tdata['like']['create_at']=$this->input->get('bulan');
			}
			if($this->input->get('status')){
					$tdata['condition']['status_hpat']=$this->input->get('status');
			}
			$tdata['column'] = "COUNT(id_hpat) as jumlah";

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Studioppat/index';
			$config['total_rows'] = $t_data['jumlah'];
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

			$dat['table'] = "tb_hakppat";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "ms_users";
			$dat['join']['key'] = "idusr_usr";
			$dat['join']['ref'] = "idppat_hpat";
			$dat['column']	= "id_hpat as idhak,nohak_hpat as nomorhak,status_hpat as status,name_usr as nama,tb_hakppat.create_at as tanggal,
                        (SELECT nma_kec FROM ms_kecamatan WHERE kd_kec=SUBSTRING(nomorhak,7,2)) as kec,
                        (SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=SUBSTRING(nomorhak,7,2) AND kd_kel=SUBSTRING(nomorhak,10,2)) as kel
                        ";
			$dat['orderby']['column'] = 'tb_hakppat.create_at';
			$dat['orderby']['sort'] = 'desc';
			if($this->input->get('user')){
					$dat['condition']['idppat_hpat']=$this->input->get('user');
			}

			if($this->input->get('nohak')){
					$dat['condition']['nohak_hpat']=$this->input->get('nohak');
			}
			if($this->input->get('bulan')){
					$dat['like']['tb_hakppat.create_at']=$this->input->get('bulan');
			}

			if($this->input->get('status')){
					$dat['condition']['status_hpat']=$this->input->get('status');
			}

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

			$user['table'] = "ms_users";
      $user['type'] = "multiple";
			$user['condition']['level_usr'] = 4;
			$this->content['user'] = $this->crud_model->get_data($user);

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio4/data_studio_4");
			$this->load->view('adm',$this->content);
		}

		public function user()
		{

			$this->content['data']['title'] = "User E-PPAT ";
			$this->content['data']['subtitle'] = array(array("user e-ppat","Studioppat/user"));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "ms_users";
      $tdata['type'] = "single";
			$tdata['condition']['level_usr'] = 4;
			if($this->input->get('user')){
					$tdata['like']['usrid_usr']=$this->input->get('user');
			}
			$tdata['column'] = "COUNT(idusr_usr) as jumlah";

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Studioppat/user';
			$config['total_rows'] = $t_data['jumlah'];
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

			$dat['table'] = "ms_users";
      $dat['type'] = "multiple";
			$dat['condition']['level_usr'] = 4;
			$dat['column']	= "ms_users.*,(SELECT COUNT(id_warn) FROM tb_warning WHERE idusr_warn=idusr_usr) as warning";

			if($this->input->get('user')){
					$dat['like']['usrid_usr']=$this->input->get('user');
			}

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio4/data_user");
			$this->load->view('adm',$this->content);
		}

		public function detail($id)
		{

			$this->content['data']['title'] = "Detail Data E-PPAT";
			$this->content['data']['subtitle'] = array(array("e-ppat","Studioppat"),array("detail berkas","Studioppat/detail/".$id));


			$dat['table'] = "tb_hakppat";
      $dat['type'] = "single";
			$dat['join']['table'] = "ms_users";
			$dat['join']['key'] = "idusr_usr";
			$dat['join']['ref'] = "idppat_hpat";
			$dat['column']	= "*,
                        (SELECT nma_kec FROM ms_kecamatan WHERE kd_kec=SUBSTRING(nohak_hpat,7,2)) as kec,
                        (SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=SUBSTRING(nohak_hpat,7,2) AND kd_kel=SUBSTRING(nohak_hpat,10,2)) as kel
                        ";
			$dat['condition']['id_hpat'] = $id;

      $this->content['studio'] = $this->crud_model->get_data($dat);
			$open = $this->crud_model->update('tb_hakppat',array('open_hpat'=>'1'),array('id_hpat'=>$this->content['studio']['id_hpat']));

			$datimg['table'] = "tb_imghakppat";
      $datimg['type'] = "multiple";
			$datimg['condition']['idhpat_img'] = $id;

      $this->content['image'] = $this->crud_model->get_data($datimg);

			$this->content['load'] = array("studio4/datadetail_studio_4");
			$this->load->view('adm',$this->content);
		}

		function import_ppat()
		{
			$user = $this->auth_model->get_userdata();
			$this->content['data']['title'] = "Import Data PPAT";
			$this->content['data']['subtitle'] = array(array("e-PPAT","Studioppat"),array("Import PPAT","Studioppat/import_ppat/"));

			if ($this->input->post()) {
					$this->load->library("Excel/PHPExcel");
					$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$c = 2;$no=1;
					// READ IMPORT
					while(!empty($sheetData[$c]["A"])){

							if (!empty($sheetData[$c]["A"])) {

								$dat['table'] 	= "ms_users";
								$dat['type'] 	  = "single";
								$dat['condition']['usrid_usr']	= $sheetData[$c]["C"];
								$exist = $this->crud_model->get_data($dat);

								if(!$exist){
										$datuser ['name_usr']  	  		 = $sheetData[$c]["B"];
										$datuser ['usrid_usr']    		 = $sheetData[$c]["C"];
										$datuser ['pasid_usr']    		 = enkripsi_pass($sheetData[$c]["D"]);
										$datuser ['level_usr']    	   = 4;
										$datuser ['activ_usr']    	   = 1;
										$datuser ['ket_usr']    	     = "PPAT";
										$datuser ['publish']    	     = 1;
										$datuser ['status_usr']    	   = 1;
										$datuser ['create_at']     	   = date('Y-m-d H:i:s');

										$inputppat = $this->crud_model->input("ms_users",$datuser);

										if($inputppat){
											echo "<span style='color:#27ae60'>Data USER dengan username ".$sheetData[$c]["C"]." berhasil diinput</span><br>";
										}else{
											echo "<span style='color:#c0392b'>Data USER dengan username ".$sheetData[$c]["C"]." gagal diinput</span><br>";
										}
									}else{
										echo "<span style='color:#d35400'>Data USER dengan username ".$sheetData[$c]["C"]." sudah ada di table</span><br>";
									}

								}else{
									echo "<span style='color:#c0392b'>Data USER ".$sheetData[$c]["C"]." gagal diinput</span><br>";
								}

							$c++;
							$no++;
					}
				}else{
				$this->content['file'] = "";
				$this->content['load'] = array("Studio1/form_import");
				$this->load->view('adm',$this->content);
			}
		}

	}
 ?>
