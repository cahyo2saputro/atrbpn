<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class User extends CI_Controller
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
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}

			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Staff';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$this->content['data']['title'] 	= "Data User";
			$this->content['data']['subtitle'] 	= array(array("User","User"));

			/*$this->content['data_studio_1_1']	= $this->studio_1_1_model->show_data();
*/
			$this->content['load'] 				= array("user/data_staff");
			$this->load->view('adm',$this->content);
		}

		public function data()
		{
			$data = $this->staff_model->tampil_data();
			$hasil          = array();
	      	$result         = array();
	      	$nomor          = 0;
	      	foreach ($data as $data) {
	      		$nomor        = $nomor + 1;

	      		if($data->usrid_usr == NULL){
	      			$usr = '<div clas="btn-group">
	      						<button data-toggle="tooltip" title="tambah data" class="btn btn-sm btn-primary" type="button" id="btn-usr" data-id="'.$data->idusr_usr.'"><span class="fa fa-user-plus"></span></button>
	      					</div>';
	      		}else{
	      			$usr = $data->usrid_usr;
	      		}

						if($data->level_usr == "2"){
							$act = '<div class="btn-group">
										<a href='.base_url()."user/hakakses/".$data->idusr_usr.'><button data-toggle="tooltip" title="edit hak akses" class="btn btn-sm btn-warning" type="button"><span class="fa fa-bars"><span></button></a>
										<button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" data-id="'.$data->idusr_usr.'" type="button" id="btn-edit"><span class="fa fa-edit"><span></button>
										<button data-toggle="tooltip" title="hapus data" class="btn btn-sm btn-danger" data-id="'.$data->idusr_usr.'" data-nama="'.$data->name_usr.'" type="button" id="btn-hapus"><span class="fa fa-trash-o"></span></button>
									</div>';
						}else{
							$act = '<div class="btn-group">
										<button data-toggle="tooltip" title="edit data" class="btn btn-sm btn-warning" data-id="'.$data->idusr_usr.'" type="button" id="btn-edit"><span class="fa fa-edit"><span></button>
										<button data-toggle="tooltip" title="hapus data" class="btn btn-sm btn-danger" data-id="'.$data->idusr_usr.'" data-nama="'.$data->name_usr.'" type="button" id="btn-hapus"><span class="fa fa-trash-o"></span></button>
									</div>';
						}


	      		if($data->level_usr == "1"){
	      			$lvl  = "Admin";
	      		}else if($data->level_usr == "2"){
	      			$lvl  = "User";
	      		}else if($data->level_usr == "3"){
	      			$lvl  = "BP2KAD";
	      		}else if($data->level_usr == "4"){
	      			$lvl  = "PPAT";
	      		}else{
	      			$lvl = "Undefined";
	      		}


	      		$hasil[]	= 	array(
	      				'no' 		=> $nomor,
	      				'nip' 		=> $data->nip_usr,
	      				'nama'		=> $data->name_usr,
	      				'usr'		=> $usr,
	      				'level'		=> $lvl,
								'ket'		=> $data->ket_usr,
	      				'action'	=> $act
	      		);
	      	}
	      	 $result         = array (
	          'aaData'      => $hasil
	        );
	      	echo json_encode($result);die();
		}

		public function staff_id()
		{
			$data = $this->staff_model->staff_id($_GET['id']);
			echo json_encode($data);die();
		}

		public function hakakses($id)
		{
			$this->content['data']['title'] = "Setting Hak Akses ";
			$this->content['data']['subtitle'] = array(array("User","User"),array("Setting Hak akses","User/hakakses/".$id));

			$this->content['status'] = "tambah";
			if($this->input->post()){
				$this->crud_model->delete('tb_userrole',array('idusr_role'=>$id));
				$this->crud_model->delete('tb_userkel',array('idusr_kel'=>$id));

				$data_role 	= $this->input->post('menu');
				$data_kel 	= $this->input->post('kel');
				foreach ($data_role as $dd) {
					$dataarray = array(
						'idusr_role'   	=> $id,
						'idmenu_role'   => $dd
					);
					$simpan = $this->crud_model->input('tb_userrole',$dataarray);
				}
				// SAVE LOG
				$this->referensi_model->save_logs($this->iduser,'tb_userrole',$this->uri->segment(2),'update data user role dengan data'.displayArray($dataarray));

				if($data_kel){
					foreach ($data_kel as $dkel) {
						$datakel = array(
							'idusr_kel' => $id,
							'idkel_kel' => $dkel
						);
						$simpan = $this->crud_model->input('tb_userkel',$datakel);
					// SAVE LOG
					$this->referensi_model->save_logs($this->iduser,'tb_userkel',$this->uri->segment(2),'update data user kelurahan dengan data'.displayArray($datakel));
					}
				}
				?>
				<!-- <meta http-equiv="refresh" content="1;<?php echo base_url();?>user"> -->
				<?php
			}
			$user['type'] 	= "single";
			$user['table'] 	= "ms_users";
			$user['condition']['idusr_usr'] = $id;
			$this->content['user'] = $this->crud_model->get_data($user);

			$menu['type'] 	= "multiple";
			$menu['table'] 	= "tb_hakakses";
			$menu['orderby']['column'] 	= "studio_has,nma_has,aksi_has";
			$menu['orderby']['sort'] 	  = "asc";
			$this->content['menu'] = $this->crud_model->get_data($menu);

			$kecamatan['type'] 	= "multiple";
			$kecamatan['table'] 	= "ms_kecamatan";
			$this->content['kecamatan'] = $this->crud_model->get_data($kecamatan);

			$template['type'] 	= "multiple";
			$template['table'] 	= "ms_template";
			$this->content['template'] = $this->crud_model->get_data($template);

			$kelurahan['type'] 		= "multiple";
			$kelurahan['table'] 	= "tb_userkel";
			$kelurahan['column'] 	= "idkel_kel,nma_kel";
			$kelurahan['join']['table'] 	= "ms_kelurahan";
			$kelurahan['join']['key'] 	= "kd_full";
			$kelurahan['join']['ref'] 	= "idkel_kel";
			$kelurahan['condition']['idusr_kel'] 	= $id;
			$this->content['kelurahan'] = $this->crud_model->get_data($kelurahan);

			$this->content['load'] = array("user/form_hakakses");
			$this->load->view('adm',$this->content);
		}

		public function tambah()
		{
			$nip = $this->input->post('nip_usr');
			$lvl = $this->input->post('level_usr');
			$ket = $this->input->post('ket_usr');

			if(empty($nip)) {
				$msg = false;
			} else if(empty($this->input->post('name_usr'))){
				$msg = false;
			} else if(empty($this->input->post('activ_usr'))){
				$msg = false;
			} else if(empty($this->input->post('level_usr')) || empty($this->input->post('usrid_usr')) || empty($this->input->post('pasid_user'))){
				$msg = false;
			} else if($this->input->post('pasid_user')!=$this->input->post('pasid_user_ret')){
				$msg = false;
			} else {
				$usr['type'] = "single";
				$usr['table'] = "ms_users";
				$usr['condition']['usrid_usr'] = $this->input->post('usrid_usr');
				$user = $this->crud_model->get_data($usr);

				if($user) {
					$msg = false;
				} else {
					$ar = array(
							'nip_usr' => $nip,
							'name_usr' => $this->input->post('name_usr'),
							'usrid_usr' => $this->input->post('usrid_usr'),
							'pasid_usr' => enkripsi_pass($this->input->post('pasid_user')),
							'level_usr' => $lvl,
							'activ_usr' => $this->input->post('activ_usr'),
							'ket_usr' => $this->input->post('ket_usr'),
							'publish' => '1',
							'status_usr' => '1'
					);

					$simpan = $this->staff_model->tambah_data($ar);

					// SAVE LOG
					$this->referensi_model->save_logs($this->iduser,'ms_user',$this->uri->segment(1),'tambah user dengan rincian '.displayArray($ar));
				}
			}

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit()
		{
			$id_usr = $this->input->post('id');
			$nip = $this->input->post('nip_usr');
			$id_st = $this->input->post('id_st');
			$ket = $this->input->post('ket_usr');
			$lvl = $this->input->post('level_usr');

			if(empty($nip)){
				$msg = false;
			}else if(empty($this->input->post('name_usr'))){
				$msg = false;
			}else if(empty($this->input->post('activ_usr'))){
				$msg = false;
			}else if(empty($this->input->post('level_usr'))){
				$msg = false;
			}else{
				if(!empty($this->input->post('usrid_usr'))){
					$usr['type'] = "single";
					$usr['table'] = "ms_users";
					$usr['condition']['usrid_usr'] = $this->input->post('usrid_usr');
					$user = $this->crud_model->get_data($usr);

					if($user && $id_usr!=$user['idusr_usr']){
						$msg = false;
					}else if($this->input->post('pasid_user')!=''){
							if($this->input->post('pasid_user')!=$this->input->post('pasid_user_ret')){
									$msg = false;
							}else{
								$ar = array(
			    					'nip_usr' => $nip,
			    					'name_usr' => $this->input->post('name_usr'),
			    					'usrid_usr' => $this->input->post('usrid_usr'),
			    					'pasid_usr' => enkripsi_pass($this->input->post('pasid_user')),
			    					'level_usr' => $lvl,
										'ket_usr' => $ket,
			    					'activ_usr' => $this->input->post('activ_usr'),
			    					'publish' => '1',
			    					'status_usr' => '1'
			    				);

			    			$simpan = $this->staff_model->edit_data($id_usr,$ar);

								if($simpan){
									$msg = true;
								}
							}
					}else{
						$ar = array(
								'nip_usr' => $nip,
								'name_usr' => $this->input->post('name_usr'),
								'usrid_usr' => $this->input->post('usrid_usr'),
								'level_usr' => $lvl,
									'ket_usr' => $ket,
								'activ_usr' => $this->input->post('activ_usr'),
								'publish' => '1',
								'status_usr' => '1'
						);

						$simpan = $this->staff_model->edit_data($id_usr,$ar);

							if($simpan){
								$msg = true;
							}
	    			}
				}else{
					if(!empty($this->input->post('pasid_user'))){
							// echo 'balalal';
							if($this->input->post('pasid_user')!=$this->input->post('pasid_user_ret')){
									$msg = false;
							}else{
								$ar = array(
			    					'nip_usr' => $nip,
			    					'name_usr' => $this->input->post('name_usr'),
			    					'pasid_usr' => enkripsi_pass($this->input->post('pasid_user')),
			    					'level_usr' => $lvl,
										'ket_usr' => $ket,
			    					'activ_usr' => $this->input->post('activ_usr'),
			    					'publish' => '1',
			    					'status_usr' => '1'
								);
								$simpan = $this->staff_model->edit_data($id_usr,$ar);

								// SAVE LOG
								$this->referensi_model->save_logs($this->iduser,'ms_user',$this->uri->segment(1),'edit user dengan rincian '.displayArray($ar));

								if($simpan){
									$msg = true;
								}
							}
					}else{
						$ar = array(
								'nip_usr' => $nip,
								'name_usr' => $this->input->post('name_usr'),
								'level_usr' => $lvl,
								'ket_usr' => $ket,
								'activ_usr' => $this->input->post('activ_usr'),
								'publish' => '1',
								'status_usr' => '1'
						);

						$simpan = $this->staff_model->edit_data($id_usr,$ar);

						// SAVE LOG
						$this->referensi_model->save_logs($this->iduser,'ms_user',$this->uri->segment(1),'edit user dengan rincian '.displayArray($ar));

						if($simpan){
							$msg = true;
						}
					}

				}
			}
			echo json_encode($msg);die();
		}


		public function add_usr()//menambahkan username dan password pada staff
		{
			$id_usr = $this->input->post('id');

			$ar = array(
					'nip_usr' => $this->input->post('nip_usr'),
					'name_usr' => $this->input->post('name_usr'),
					'usrid_usr' => $this->input->post('usrid_usr'),
					'pasid_usr' => enkripsi_pass($this->input->post('pasid_usr'))
			);

			$simpan = $this->staff_model->edit_data($id_usr,$ar);

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function edit_usr()
		{
			$id_usr = $this->input->post('id');

		    $sr_usr = $this->staff_model->staff_pass($id_usr);
			$pass = $sr_usr['pasid_usr'];

			if(enkripsi_pass($this->input->post('pasid_user')) === $pass){
			    if($this->input->post('pasid_user_new') === $this->input->post('pasid_user_ret')){
    			    $ar = array(
    							'pasid_usr' => enkripsi_pass($this->input->post('pasid_user_new'))
    					);
			    }
			}

			$simpan = $this->staff_model->edit_data($id_usr,$ar);

			if($simpan){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function hapus($kode)
		{
			$hapus = $this->staff_model->hapus_data($kode);
			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}

		public function forcekelurahan()
		{
			$kelurahan['table'] = "ms_kelurahan";
			$kelurahan['type'] = "multiple";
			//$kelurahan['limit'] = 1;
			$data = $this->crud_model->get_data($kelurahan);

			foreach ($data as $dd) {
				$cekkel['table'] = "ms_users";
				$cekkel['type'] = "single";
				$cekkel['condition']['usrid_usr'] = $dd['kd_full'];
				$cekkell = $this->crud_model->get_data($cekkel);

				if(!$cekkell){
					$dataarray = array(
						'name_usr'   => $dd['nma_kel'],
						'usrid_usr'   => $dd['kd_full'],
						'nip_usr'   => $dd['kd_full'],
						'pasid_usr'   => enkripsi_pass('atrbpnks'.$dd['kd_full']),
						'level_usr'   => '2',
						'ket_usr'   => 'Panitia Desa',
						'activ_usr'   => '1',
						'publish' => '1',
						'status_usr' => '1',
						'create_at' => date("Y-m-d H:i:s")
					);
					$simpan = $this->crud_model->input('ms_users',$dataarray);
					$insert_id = $this->db->insert_id();
				}else{
					$insert_id = $cekkell['idusr_usr'];
				}

					$datarole = array(
						'idusr_role' => $insert_id,
						'idmenu_role' => 13
					);

					$cekrole['table'] = "tb_userrole";
					$cekrole['type'] = "single";
					$cekrole['condition']['idusr_role'] = $insert_id;
					$cekrole['condition']['idmenu_role'] = 13;
					$dcekrole = $this->crud_model->get_data($cekrole);
					if(!$dcekrole){
							$simpan = $this->crud_model->input('tb_userrole',$datarole);
					}

					$datarole = array(
						'idusr_role' => $insert_id,
						'idmenu_role' => 16
					);

					$cekrole['table'] = "tb_userrole";
					$cekrole['type'] = "single";
					$cekrole['condition']['idusr_role'] = $insert_id;
					$cekrole['condition']['idmenu_role'] = 16;
					$dcekrole = $this->crud_model->get_data($cekrole);
					if(!$dcekrole){
							$simpan = $this->crud_model->input('tb_userrole',$datarole);
					}

					// ROLE STUDIO 3
					for($i=20;$i<=21;$i++){
						$datarole = array(
							'idusr_role' => $insert_id,
							'idmenu_role' => $i
						);

						$cekrole['table'] = "tb_userrole";
						$cekrole['type'] = "single";
						$cekrole['condition']['idusr_role'] = $insert_id;
						$cekrole['condition']['idmenu_role'] = $i;
						$dcekrole = $this->crud_model->get_data($cekrole);
						if(!$dcekrole){
								$simpan = $this->crud_model->input('tb_userrole',$datarole);
						}

					}
					for($i=23;$i<=35;$i++){
						$datarole = array(
							'idusr_role' => $insert_id,
							'idmenu_role' => $i
						);

						$cekrole['table'] = "tb_userrole";
						$cekrole['type'] = "single";
						$cekrole['condition']['idusr_role'] = $insert_id;
						$cekrole['condition']['idmenu_role'] = $i;
						$dcekrole = $this->crud_model->get_data($cekrole);
						if(!$dcekrole){
								$simpan = $this->crud_model->input('tb_userrole',$datarole);
						}

					}

					$datakel = array(
						'idusr_kel' => $insert_id,
						'idkel_kel' => $dd['kd_full']
					);

					$cekkelrole['table'] = "tb_userkel";
					$cekkelrole['type'] = "single";
					$cekkelrole['condition']['idusr_kel'] = $insert_id;
					$cekkelrole['condition']['idkel_kel'] = $dd['kd_full'];
					$dcekkelrole = $this->crud_model->get_data($cekkelrole);
					if(!$dcekkelrole){
							$simpan = $this->crud_model->input('tb_userkel',$datakel);
					}

			}
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>User">
			<?php
		}
	}
 ?>
