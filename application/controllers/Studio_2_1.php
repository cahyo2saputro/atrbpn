<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Studio_2_1 extends CI_Controller
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

			$this->content['data']['title'] = " e-Data ".$nma_kel['nma_kel'];
			$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Daftar Blok","Studio_2_1/index/?search=".$cari));



			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$blocks['type'] = "single";
			$blocks['table'] = "tb_block";
			$blocks['condition']['idkel_blk'] = $cari;
			$blocks['column'] = "COUNT(idblk_blk) as jumlahblock";
			$sumblock = $this->crud_model->get_data($blocks);

			$t_data  = $sumblock['jumlahblock'];

			$config['base_url'] = base_url().'Studio_2_1/index/';
			$config['total_rows'] = $t_data;
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


			$this->content['block'] = $this->studio_2_1_model->show_data($config['per_page'],$from,$cari);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio2/data_studio_2_1");
			$this->load->view('adm',$this->content);
		}

		public function form()
		{
			$user = $this->auth_model->get_userdata();
			$status = $this->uri->segment(3);
			$idkel = $this->input->get('idkel');
			if ($idkel) {
				$this->content['data']['title'] = "Tambah Blok";
				$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Daftar Blok","Studio_2_1/index/?search=".$idkel),array("Tambah Blok","Studio_2_1/form?idkel=".$idkel));

				$kelurahan['type'] = "single";
				$kelurahan['table'] = "ms_kelurahan";
				$kelurahan['condition']['kd_full'] = $idkel;

				$this->content['idkel_blk'] = $this->crud_model->get_data($kelurahan);
				$this->content['status'] = "tambah";

				cekkelurahan($user['idusr_usr'],$user['level_usr'],$idkel);

				$this->content['link'] = base_url().'studio_2_1/tambah';
			}else{
				$this->content['data']['title'] = "Edit Form Block";
				$this->content['data']['subtitle'] = array(array("e-data","Studio2"),array("Daftar Blok","javascript:history.go(-1)"),array("Edit Blok","Studio_2_1/form/".$this->uri->segment(3)));

				$this->content['get_data'] = $this->studio_2_1_model->show_edit($status);
				$this->content['status'] = "edit";

				cekkelurahan($user['idusr_usr'],$user['level_usr'],$this->content['get_data']['idkel_blk']);

				$this->content['link'] = base_url().'studio_2_1/edit';
			}


			$this->content['load'] = array("studio2/form_block");
			$this->load->view('adm',$this->content);
		}

		public function tambah()
		{
			$user = $this->auth_model->get_userdata();
			$id_kel = $this->input->post('idkel_blk');

			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kel);
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

				if(!empty($_FILES['petblk_blk']['name'])){
						$nmfile1 					= "BLK"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './PETA/PETA_BLOCK/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('petblk_blk');
						$data1					= $this->upload->data();
				}

				if(!empty($_FILES['petptsl_blk']['name'])){
					$nmfile2 					= "PTSL"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
	    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
	    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
	    		$config2['upload_path']		= './PETA/PETA_PTSL/';
	    		$config2['allowed_types']	= '*';
	    		$this->upload->initialize($config2);
	    		$upload2 				= $this->upload->do_upload('petptsl_blk');
	    		$data2					= $this->upload->data();
				}


			if (empty($_FILES['petblk_blk']['name'])&&empty($_FILES['petptsl_blk']['name'])) {
    			$nama_upload_ptsl = "";
    			$nama_upload_blk = "";
    		}else if(!empty($_FILES['petptsl_blk']['name']) && empty($_FILES['petblk_blk']['name'])){
    			$nama_upload_ptsl 		= $data2['file_name'];
    			$nama_upload_blk 		= "";
    		}else if(!empty($_FILES['petblk_blk']['name']) && empty($_FILES['petptsl_blk']['name'])){
    			$nama_upload_ptsl 		= "";
    			$nama_upload_blk 		= $data1['file_name'];
    		}else{
    			$nama_upload_ptsl 		= $data2['file_name'];
    			$nama_upload_blk 		= $data1['file_name'];
    		}

    		$ar = array(
					'idkel_blk' => $this->input->post('idkel_blk') ,
					'idusr_blk' => $user['idusr_usr'],
					'nama_blk' => $this->input->post('nama_blk'),
					'petblk_blk' => $nama_upload_blk,
					'petptsl_blk' => $nama_upload_ptsl,
					'status_blk' => "1",
					'create_at' => date("Y-m-d")
			);


			$simpan = $this->studio_2_1_model->simpan($ar);
			$insert_id = $this->db->insert_id();

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Data BPN-'.$nma_kel.'-'.$insert_id,"Tambah Blok dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit()
		{
			$user = $this->auth_model->get_userdata();
			$id = $this->input->post('id');
			$id_kel = $this->input->post('idkel_blk');

			$sr_kel = $this->studio_1_1_model->sr_kel_kec($id_kel);
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

				if(!empty($_FILES['petblk_blk']['name'])){
						$nmfile1 					= "BLK"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './PETA/PETA_BLOCK/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('petblk_blk');
						$data1					= $this->upload->data();
				}

				if(!empty($_FILES['petptsl_blk']['name'])){
					$nmfile2 					= "PTSL"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
	    		$config2['file_name'] 		= $nmfile2; 				//nama yang terupload nantinya
	    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
	    		$config2['upload_path']		= './PETA/PETA_PTSL/';
	    		$config2['allowed_types']	= '*';
	    		$this->upload->initialize($config2);
	    		$upload2 				= $this->upload->do_upload('petptsl_blk');
	    		$data2					= $this->upload->data();
				}

				$ar2=array();
				$ar3=array();
    		if(!empty($_FILES['petptsl_blk']['name'])){
    			$nama_upload_ptsl 		= $data2['file_name'];
					$ar3 = array('petptsl_blk' => $nama_upload_ptsl);
    		}

				if(!empty($_FILES['petblk_blk']['name'])){
    			$nama_upload_blk 		= $data1['file_name'];
					$ar2 = array('petblk_blk' => $nama_upload_blk);
    		}

    		$ar = array(
					'idkel_blk' => $this->input->post('idkel_blk') ,
					'nama_blk' => $this->input->post('nama_blk'),
					'idusr_blk' => $user['idusr_usr'],
				);

				$ar1 = array_merge($ar,$ar2,$ar3);
			$simpan = $this->studio_2_1_model->edit($id,$ar1);

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Data BPN-'.$nma_kel.'-'.$id,"Edit Blok dengan rincian ".displayArray($ar1));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus($kode)
		{
			$hapus = $this->studio_2_1_model->hapus($kode);

			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Data BPN-0-'.$kode,"Hapus Blok dengan kode ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
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
						$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_blockptsl','e Data BPN-0-'.$id,"Add PETA PTSL dengan rincian ".displayArray($ar));
					}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function simpan_peta_block($id)
		{
			$user = $this->auth_model->get_userdata();

			$sr_kel = $this->db->query("SELECT nma_kel, nma_kec, idkel_blk FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idblk_blk = '$id'")->row_array();
    		$nma_kel = $sr_kel['nma_kel'];
    		$nma_kec = $sr_kel['nma_kec'];

			$nmfile1 					= "BLK"."_".time(); 	//nama file saya beri nama langsung dan diikuti fungsi time
    		$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
    		//$config['upload_path'] = './digitalisasi/'.$nma_kec.'/'.$nma_kel.'/BUKU%20TANAH/';
    		$config1['upload_path']		= './PETA/PETA_BLOCK/';
    		$config1['allowed_types']	= '*';
    		$this->upload->initialize($config1);
    		$uploads 				= $this->upload->do_upload('petblk_blk');
    		$data1					= $this->upload->data();
    		$nama_upload_blk 		= $data1['file_name'];

    		$ar = array(
    			'idusr_blk' => $user['idusr_usr'],
				'petblk_blk' => $nama_upload_blk
			);



			$simpan = $this->studio_2_1_model->edit($id,$ar);
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_block','e Data BPN-0-'.$id,"Edit Peta Block dengan rincian ".displayArray($ar));

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}
	}
