<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permohonanukur extends CI_Controller
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
		$this->content['data']['title'] = "Daftar Permohonan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan Ukur","Permohonanukur"));

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

		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$dat['table'] = "tb_pengukuran,ms_kelurahan";
		$dat['type'] = "single";
		$dat['join']['table'] = "tb_sertipikat,tb_register";
		$dat['join']['key']   = "nope_png,id_reg";
		$dat['join']['ref']   = "nope_srt,idreg_srt";
		$dat['column'] = "COUNT(nope_png) as jumlah";
		$dat['groupby']       = "nope_png";
		$dat['cuzcondition']  = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$hasil = $this->crud_model->get_data($dat);
		$t_data=$hasil['jumlah'];

		$config['base_url'] = base_url().'Permohonanukur/index/';
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

		$data['table'] = "tb_pengukuran,ms_kelurahan";
		$data['column'] = "*,(SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$data['type'] = "multiple";
		$data['cuzcondition'] = "kel_srt=kd_kel AND kec_srt=kdkec_kel";
		$data['join']['table'] = "tb_sertipikat,tb_register,ms_kecamatan";
		$data['join']['key']   = "nope_png,id_reg,kd_kec";
		$data['join']['ref']   = "nope_srt,idreg_srt,kec_srt";
		$data['condition']['publish_png']   = "1";
		$data['limit']['lim']         =  $config['per_page'];
		$data['limit']['first']       =  $from;
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['link'] = $this->pagination->create_links();

		$this->content['load'] = array("pengukuran/data_ukur");
		$this->load->view('adm',$this->content);
	}

	public function editpermohonan($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Form Edit Permohonan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan","Permohonanukur"),array("Form Edit Permohonan Ukur","Permohonanukur/editpermohonan/".$id));

		$selected['table'] = "tb_pengukuran";
		$selected['type'] = "single";
		$selected['condition']['id_png'] = $id;
		$this->content['selected'] = $this->crud_model->get_data($selected);

		if($this->input->post()){
			$this->db->trans_start();

			// INPUT STP IMAGE
			$imagestp='';
			if($_FILES["stp"]['name']){
					unlink('./stp/'.$this->content['selected']['stp_png']);

					$file = explode(".",$_FILES["stp"]["name"]);
					$sum = count($file);
					$nmfilesppt 					= "STP"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$configsppt['file_name'] 		= $nmfilesppt; 				//nama yang terupload nantinya
					//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
					$configsppt['upload_path']		= './stp/';
					$configsppt['allowed_types']	= '*';
					$this->upload->initialize($configsppt);
					$uploadsppt 				= $this->upload->do_upload('stp');
					$datasppt					= $this->upload->data();
					$imagestp 		    = $datasppt['file_name'];
			}

			// 	INPUT PERMOHONAN
			$inputpermohonan = array(
				'nope_png'=> $this->input->post('nopermohonan'),
				'noberkas_png'=> $this->input->post('noberkas'),
				'nostp_png'=> $this->input->post('nostp'),
				'stp_png'=> $imagestp,
				'tglukur_png'=>date("Y-m-d",strtotime($this->input->post('tgl'))),
				'pu_png'=>$this->input->post('petugasukur')
			);
			$simpanregister = $this->crud_model->update('tb_pengukuran',$inputpermohonan,array('id_png'=>$id));

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_pengukuran',$id,"Mengubah Data Permohonan ukur dengan rincian ".displayArray($inputpermohonan));

			$this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Permohonanukur">
			<?php
		}

		$data['table'] = "tb_sertipikat";
		$data['type']  = "multiple";
		$data['condition']['publish_srt'] = 1;
		$data['condition']['status_srt']  = 0;
		$data['cuzcondition'] = 'nope_srt!="-" AND nope_srt NOT IN (SELECT nope_png FROM tb_pengukuran)';

		$this->content['nopermohonan'] = $this->crud_model->get_data($data);

		$pu['table'] = "tb_register";
		$pu['type'] = "multiple";
		$pu['condition']['publish_reg'] = 1;
		$pu['condition']['typeusr_reg'] = 4;

		$this->content['petugasukur'] = $this->crud_model->get_data($pu);

		$this->content['load'] = array("pengukuran/form_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function addpermohonan()
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Form Permohonan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan ukur","Permohonanukur"),array("Form Permohonan ukur","Permohonanukur/addpermohonan"));

		if($this->input->post()){
			$this->db->trans_start();

			// INPUT STP IMAGE
			$imagestp='';
			if($_FILES["stp"]['name']){
					$file = explode(".",$_FILES["stp"]["name"]);
					$sum = count($file);
					$nmfilesppt 					= "STP"."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$configsppt['file_name'] 		= $nmfilesppt; 				//nama yang terupload nantinya
					//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
					$configsppt['upload_path']		= './stp/';
					$configsppt['allowed_types']	= '*';
					$this->upload->initialize($configsppt);
					$uploadsppt 				= $this->upload->do_upload('stp');
					$datasppt					= $this->upload->data();
					$imagestp 		    = $datasppt['file_name'];
			}


						// 	INPUT PERMOHONAN
						$inputpermohonan = array(
							'nope_png'=> $this->input->post('nopermohonan'),
							'noberkas_png'=> $this->input->post('noberkas'),
							'nostp_png'=> $this->input->post('nostp'),
							'stp_png'=> $imagestp,
							'tglukur_png'=>date("Y-m-d",strtotime($this->input->post('tgl'))),
							'pu_png'=>$this->input->post('petugasukur'),
							'tracking_png'=>0,
							'publish_png'=>1,
							'idusr_png'=>$user['idusr_usr'],
							'create_at'=> date('Y-m-d H:i:s')
						);
						$simpanregister = $this->crud_model->input('tb_pengukuran',$inputpermohonan);
						$idreg_srt = $this->db->insert_id();
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_pengukuran',$idreg_srt,"Menambahkan Data Permohonan dengan rincian ".displayArray($inputpermohonan));

						// CEK KADES
						$cekpu['table'] = "tb_register";
						$cekpu['type'] = "single";
						$cekpu['condition']['id_reg'] = $this->input->post('petugasukur');
						$pu = $this->crud_model->get_data($cekpu);

						// CEK SERTIPIKAT DAN PERMOHONAN
						$sert['table']         = "tb_sertipikat";
						$sert['type']          = "single";
						$sert['column']        = "id_pmh as idpermohonan,id_srt as idsertipikat";
						$sert['join']['table'] = 'tb_permohonan';
						$sert['join']['key'] = 'id_pmh';
						$sert['join']['ref'] = 'idsrt_pmh';
						$sert['condition']['nope_srt'] = $this->input->post('nopermohonan');
						$idsert = $this->crud_model->get_data($sert);

						// KIRIM NOTIFIKASI
						$msg = array
						(
							'title'		     => "E-bpn.net" ,
							'subtitle'		 => "Pemberitahuan pengukuran",
							'idpengukuran' => $idreg_srt,
							'idpermohonan' => $idsert['idpermohonan'],
							'idsertipikat' => $idsert['idsertipikat']
						);

						kirim_notifikasi('private',$pu['fcmtoken_reg'],$msg);

			$this->db->trans_complete();
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Permohonanukur">
			<?php
		}

		$this->content['selected']= NULL;

		$data['table'] = "tb_sertipikat";
		$data['type'] = "multiple";
		$data['condition']['publish_srt'] = 1;
		$data['condition']['status_srt']  = 0;
		$data['cuzcondition'] = 'nope_srt !="-" AND nope_srt NOT IN (SELECT nope_png FROM tb_pengukuran WHERE publish_png=1)';

		$this->content['nopermohonan'] = $this->crud_model->get_data($data);

		$pu['table'] = "tb_register";
		$pu['type'] = "multiple";
		$pu['condition']['publish_reg'] = 1;
		$pu['condition']['typeusr_reg'] = 4;

		$this->content['petugasukur'] = $this->crud_model->get_data($pu);

		$this->content['load'] = array("pengukuran/form_permohonan");
		$this->load->view('adm',$this->content);
	}

	public function detail($id)
	{
		$user = $this->auth_model->get_userdata();
		$this->content['data']['title'] = "Detail Permohonan Ukur";
		$this->content['data']['subtitle'] = array(array("Daftar Permohonan Ukur","Permohonanukur"),array("Detail Permohonan Ukur","Permohonanukur/detail".$id));

		$data['table'] = "tb_pengukuran";
		$data['type'] = "single";
		$data['condition']['id_png'] = $id;
		$data['join']['table'] = "tb_sertipikat,tb_register,ms_kecamatan";
		$data['join']['key']   = "nope_srt,id_reg,kd_kec";
		$data['join']['ref']   = "nope_png,idreg_srt,kec_srt";
		$data['column']        = "*,(SELECT nma_kel FROM ms_kelurahan where kdkec_kel=kec_srt AND kd_kel=kel_srt) as nma_kel";
		$this->content['studio'] = $this->crud_model->get_data($data);

		$this->content['load'] = array("pengukuran/data_permohonan_detail");
		$this->load->view('adm',$this->content);
	}

	public function delete($id)
	{
		$user = $this->auth_model->get_userdata();

		$data['table'] = "tb_pengukuran";
		$data['type'] = "single";
		$data['condition']['id_png'] = $id;
		$selected = $this->crud_model->get_data($data);

		$ar = array(
			'publish_png' => 0
		);

		$hapus = $this->crud_model->update('tb_pengukuran',$ar,array('id_png'=>$id));
		$this->referensi_model->save_android($user['idusr_usr'],'tb_pengukuran','Hapus Data Pengukuran dengan id-'.$id.'<br>&-&',"Hapus pengukuran dengan rincian".displayArray($selected));

		if($hapus){
			$msg = true;
		}
		echo json_encode($msg);die();
	}

}
