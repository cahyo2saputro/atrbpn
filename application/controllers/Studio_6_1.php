<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_6_1 extends CI_Controller
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
			if($this->input->get('search')){
				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->input->get('search'));
			}
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = '';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$cari = $this->input->get('search');

			$kelurahan['type'] = "single";
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['condition']['kd_full'] = $cari;

			$nma_kel = $this->crud_model->get_data($kelurahan);

			$this->content['data']['title'] = "e-Pemetaan ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-Pemetaan","Studio6"),array("Daftar Blok","Studio_6_1/index/?search=".$cari));

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$block['type'] = "single";
			$block['table'] = "tb_block";
			$block['column'] = "COUNT(idblk_blk) as jumlah";
			$block['condition']['idkel_blk'] = $cari;
			$block['condition']['status_blk'] = '1';
			$datablock = $this->crud_model->get_data($block);
			$t_data = $datablock['jumlah'];

			$config['base_url'] = base_url().'Studio_6_1/index/';
			$config['total_rows'] = $t_data;
			$config['uri_segment'] = 3;
			$config['reuse_query_string'] = TRUE;
			$config['per_page'] = 30;

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


			$this->content['block'] = $this->studio_3_1_model->show_data_tr($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio6/data_studio_6_1");
			$this->load->view('adm',$this->content);
		}

		public function simpan_peta_ptsl($id)
		{
			$user = $this->auth_model->get_userdata();

			$count = count($_FILES['petptsl_blk']['name']);

			for($i=0;$i<$count;$i++){
					$_FILES['file']['name'] = $_FILES['petptsl_blk']['name'][$i];
          $_FILES['file']['type'] = $_FILES['petptsl_blk']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['petptsl_blk']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['petptsl_blk']['error'][$i];
          $_FILES['file']['size'] = $_FILES['petptsl_blk']['size'][$i];

					$file = explode(".",$_FILES["petptsl_blk"]["name"][$i]);
	        $sum = count($file);
					$nmfile1 					= "PTSL_".$id."_".time().'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
					$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
					$config1['upload_path']		= './PETA/PETA_PTSL/';
					$config1['allowed_types']	= '*';
					$this->upload->initialize($config1);

					$uploads 				= $this->upload->do_upload('file');
					$data1					= $this->upload->data();
					$nama_upload_ptsl 		= $data1['file_name'];
					$description = $this->input->post('desc_blk');

					if($data1){
						$ar = array(
							'idbpt_blk' => $id,
							'petptsl_blk' => $nama_upload_ptsl,
							'desc_blk' => $description[$i],
							'idusr_blk' => $user['idusr_usr'],
							'create_at' => date('Y-m-d H:i:s')
						);
						$simpan = $this->crud_model->input('tb_blockptsl',$ar);
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_blockptsl','e Pemetaan-0-'.$id,"Add PETA PTSL dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_peta_online($id)
		{
			$user = $this->auth_model->get_userdata();

				$nmfile2 					= "PETONLINE_".$id."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
    		$config2['upload_path']		= './PETA/PETA_ONLINEBLOCK/';
    		$config2['allowed_types']	= '*';
    		$this->upload->initialize($config2);
    		$upload2 				= $this->upload->do_upload('online');
    		$data2					= $this->upload->data();
    		$nama_upload_online 		= $data2['file_name'];

    		$ar = array(
    			'idusr_blk' => $user['idusr_usr'],
					'petonline_blk' => $nama_upload_online
			);

			$simpan = $this->studio_2_1_model->edit($id,$ar);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Pemetaan-0-'.$id,"Edit Peta Online Block dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function petaonline($cari){
			$user = $this->auth_model->get_userdata();

			$dat['table'] = "ms_kelurahan";
			$dat['type'] = "single";
			$dat['join']['table'] = "tb_block";
			$dat['join']['key'] = "kd_full";
			$dat['join']['ref'] = "idkel_blk";
			$dat['condition']['idblk_blk'] = $cari;
			$hasil = $this->crud_model->get_data($dat);

			$this->content['peta'] = $hasil;

			$this->content['data']['title'] = "Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'];
			$this->content['data']['subtitle'] = array(array("e-Pemetaan","Studio6"),array("Daftar Blok","Studio_6_1/index/?search=".$hasil['kd_full']),array("Peta Online ".$hasil['nma_kel'].' Blok '.$hasil['nama_blk'],"Studio_6_1/petaonline/".$cari));

			$from = $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->content['load'] = array("studio6/peta");
			$this->load->view('adm',$this->content);
		}

	}
