<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Templateuser extends CI_Controller
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
			$this->content['data']['title'] 	= "Template Hak akses";
			$this->content['data']['subtitle'] 	= array(array("Template Hak akses","Templateuser"));

			$template['type'] 	= "multiple";
			$template['table'] 	= "ms_template";
			$template['condition']['publish_tmp'] 	= "1";
			$this->content['template'] = $this->crud_model->get_data($template);

			$this->content['load'] 				= array("user/template");
			$this->load->view('adm',$this->content);
		}

		public function add()
		{
			$this->content['data']['title'] = "Tambah Template Hak Akses ";
			$this->content['data']['subtitle'] 	= array(array("Template Hak akses","Templateuser"),array("Tambah Hak akses","Templateuser/add"));
			$this->content['mode'] = 'input';

			if($this->input->post()){

				$dataarray = array(
					'name_tmp'      => $this->input->post('template'),
					'desc_tmp'      => $this->input->post('desc'),
					'publish_tmp'   => "1",
					'create_at'   => date('Y-m-d H:i:s')
				);
				$simpan = $this->crud_model->input('ms_template',$dataarray);
				$insert_id = $this->db->insert_id();

				$data_role 	= $this->input->post('menu');

				foreach ($data_role as $dd) {
					$dataarray = array(
						'idtmp_role'   	=> $insert_id,
						'idmenu_role'   => $dd
					);
					$simpan = $this->crud_model->input('tb_templaterole',$dataarray);
				}
				// SAVE LOG
				$this->referensi_model->save_logs($this->iduser,'tb_templaterole',$this->uri->segment(2),'input data Template role dengan data'.displayArray($dataarray));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>Templateuser">
				<?php
			}

			$menu['type'] 	= "multiple";
			$menu['table'] 	= "tb_hakakses";
			$menu['orderby']['column'] 	= "studio_has,nma_has,aksi_has";
			$menu['orderby']['sort'] 	  = "asc";
			$this->content['menu'] = $this->crud_model->get_data($menu);
			$this->content['template']=null;

			$this->content['load'] = array("user/template_hakakses");
			$this->load->view('adm',$this->content);
		}

		public function edit($id)
		{
			$this->content['data']['title'] = "Edit Template Hak Akses ";
			$this->content['data']['subtitle'] 	= array(array("Template Hak akses","Templateuser"),array("Edit Hak akses","Templateuser/edit/".$id));
			$this->content['mode'] = 'edit';

			if($this->input->post()){

				$dataarray = array(
					'name_tmp'      => $this->input->post('template'),
					'desc_tmp'      => $this->input->post('desc')
				);
				$simpan = $this->crud_model->update('ms_template',$dataarray,array('id_tmp'=>$id));
				$this->crud_model->delete('tb_templaterole',array('idtmp_role'=>$id));

				$data_role 	= $this->input->post('menu');

				foreach ($data_role as $dd) {
					$dataarray = array(
						'idtmp_role'   	=> $id,
						'idmenu_role'   => $dd
					);
					$simpan = $this->crud_model->input('tb_templaterole',$dataarray);
				}
				// SAVE LOG
				$this->referensi_model->save_logs($this->iduser,'tb_templaterole',$this->uri->segment(2),'edit data Template role dengan data'.displayArray($dataarray));

				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>Templateuser">
				<?php
			}
			$user['type'] 	= "single";
			$user['table'] 	= "ms_template";
			$user['condition']['id_tmp'] = $id;
			$this->content['template'] = $this->crud_model->get_data($user);

			$menu['type'] 	= "multiple";
			$menu['table'] 	= "tb_hakakses";
			$menu['orderby']['column'] 	= "studio_has,nma_has,aksi_has";
			$menu['orderby']['sort'] 	  = "asc";
			$this->content['menu'] = $this->crud_model->get_data($menu);

			$this->content['load'] = array("user/template_hakakses");
			$this->load->view('adm',$this->content);
		}

		public function delete($id)
		{
			$ar = array(
				'publish_tmp' => '0'
			);
			$hapus = $this->crud_model->update('ms_template',$ar,array('id_tmp'=>$id));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'ms_template',$id,"Menghapus Data Template dengan id ".$id);
			?>
			<meta http-equiv="refresh" content="1;<?php echo base_url();?>Templateuser">
			<?php
		}

	}
 ?>
