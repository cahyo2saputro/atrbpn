<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {
	var $userdata = NULL;
	public function __construct (){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');

		if(isset($this->session->smt_member)){
			$this->userdata['user'] = $this->auth_model->get_userdata();
		}
		$usr = $this->auth_model->get_userdata();
		$level_usr = $usr['level_usr'];
		if($level_usr != "1"){
				redirect('Studio_1_1');
		}
		date_default_timezone_set('Asia/Jakarta');
        $this->load->view('auth/authorized');

	}


	public function output($output = null)
	{
		// echo $output;
		$this->load->view('adm_gro.php',$output);

	}

	public function index($output = null) {
		redirect(base_url('admin'),'refresh');
	}

	function set_date ($value,$format){
        $date = explode("-",$value);

		return $date[2]."-".$date[1]."-".$date[0];
	}

	public function foto(){
		$crud = new grocery_CRUD();

		//SET TEMA GROCERY (flexigrid / datatables)
		$crud->set_table('ms_employee');
        $crud->set_subject('karyawan');
        $crud->set_language("indonesian");


		$crud->display_as('photo_emp','FOTO PROFILE');

		$crud->change_field_type('create_at','invisible');
		$crud->change_field_type('update_at','invisible');
		$crud->change_field_type('nip_emp','invisible');
		$crud->change_field_type('name_emp','invisible');
		$crud->change_field_type('addrs_emp','invisible');
		$crud->change_field_type('phone_emp','invisible');
		$crud->change_field_type('email_emp','invisible');
		$crud->change_field_type('jabatan_emp','invisible');
		$crud->change_field_type('status_emp','invisible');
		$crud->change_field_type('ttl_emp','invisible');
		$crud->change_field_type('sex_emp','invisible');
		$crud->change_field_type('agama_emp','invisible');
		$crud->change_field_type('statkawin_emp','invisible');
		$crud->change_field_type('tglmsk_emp','invisible');
		$crud->set_field_upload('photo_emp','images');

		$crud->unset_columns('create_at','update_at');
		$crud->unset_back_to_list();
		$crud->callback_before_update(array($this,'get_init_date'));

		$output = $crud->render();
		$output->data['title'] = "Edit Foto";
		$output->data['subtitle'] = array(array("Profile"),array("Edit Foto"));

		$this->output($output);
	}

	public function kecamatan(){
	    $user = $this->auth_model->get_userdata();
	    if ($user['level_usr'] == 1) {
		$crud = new grocery_CRUD();

		//data tabel ms user versi 2
		$crud->set_table('ms_kecamatan');
        $crud->set_subject('kecamatan');
        $crud->set_language("indonesian");
        $crud->columns('kd_kec','nma_kec');

		$crud->display_as('nma_kec','Kecamatan')->display_as('kd_kec','Kode Kecamatan')->display_as('kdpbb_kec','Kode PBB Kecamatan');

		$crud->unset_clone();
		$crud->unset_read();

		$crud->change_field_type('create_at','invisible');
		$crud->change_field_type('update_at','invisible');
		$crud->required_fields('kd_kec','kdpbb_kec','nma_kec');
		$crud->edit_fields('kd_kec','kdpbb_kec','nma_kec');

		$crud->callback_before_update(array($this,'get_init_date'));


		$output = $crud->render();
		$output->data['title'] = "Data kecamatan";
		$output->data['subtitle'] = array(array("Kecamatan","Master/kecamatan"));

		$this->output($output);
	    } else {
		    redirect('Home','refresh');
		}
	}

	public function administrasi(){
	    $user = $this->auth_model->get_userdata();
	    if ($user['level_usr'] == 1) {
		$crud = new grocery_CRUD();

		//data tabel ms user versi 2
		$crud->set_table('ms_administrasi');
        $crud->set_subject('Kategori Administrasi');
        $crud->set_language("indonesian");
        $crud->columns('name_adm');

		$crud->display_as('name_adm','Kategori Administrasi');

		$crud->unset_clone();
		$crud->unset_read();

		$crud->change_field_type('create_at','invisible');
		$crud->change_field_type('update_at','invisible');
		$crud->required_fields('name_adm');
		$crud->edit_fields('name_adm');

		$crud->callback_before_update(array($this,'get_init_date'));


		$output = $crud->render();
		$output->data['title'] = "Data Kategori Administrasi";
		$output->data['subtitle'] = array(array("Kategori Administrasi","Master/administrasi"));

		$this->output($output);
	    } else {
		    redirect('Home','refresh');
		}
	}

	public function term(){
	    $user = $this->auth_model->get_userdata();
	    if ($user['level_usr'] == 1) {
		$crud = new grocery_CRUD();

		//data tabel ms user versi 2
		$crud->set_table('ms_term');
        $crud->set_subject('Kebijakan Aplikasi E-ppat');
        $crud->set_language("indonesian");
        $crud->columns('desc_trm');

		$crud->display_as('desc_trm','Kebijakan');

		$crud->unset_clone();
		$crud->unset_read();

		$crud->change_field_type('create_at','invisible');
		$crud->change_field_type('update_at','invisible');
		$crud->required_fields('desc_trm');
		$crud->edit_fields('desc_trm');

		$crud->callback_before_update(array($this,'get_init_date'));


		$output = $crud->render();
		$output->data['title'] = "Kebijakan E-PPAT";
		$output->data['subtitle'] = array(array("Kebijakan E-PPAT","Master/term"));

		$this->output($output);
	    } else {
		    redirect('Home','refresh');
		}
	}

	public function reset(){
	    $user = $this->auth_model->get_userdata();
	    if ($user['level_usr'] == 1 OR $user['level_usr'] == 2) {
        $post                           = $this->input->post();
        $pgw['type']                    = "single";
        $pgw['table']                   = "ms_users";
        $pgw['condition']['idusr_usr']  = $this->uri->segment(3);
        $clients      = $this->crud_model->get_data($pgw);

            $dataupload ['pasid_usr'] = enkripsi_pass("12345");
            $this->crud_model->update('ms_users',$dataupload,array('idusr_usr'=>$this->uri->segment(3)));


            $this->session->set_flashdata('message', 'Data Successfully Updated');
            echo "<script>alert('Password Berhasil di reset');</script>";
            redirect('Master/user','refresh');
	    } else {
		    redirect('Home','refresh');
		}
    }


  public function get_init_date($post_array){

  		$post_array['create_at'] = date('Y-m-d H:i:s');

  		return $post_array;
	}



	function md5($post_array) {
		$post_array['pasid_usr'] = enkripsi_pass($post_array['pasid_usr']);
		$post_array['publish'] = 1;
		$post_array['update'] = date("Y-m-d H:i:s");

		return $post_array;
	}


	public function _rules(){

        $config = array(
            array(
                'field'  => 'qty',
                'label'  => 'Jumlah Opname',
                'rules'  => 'required',
                'errors' => array('required' => '%s harus diisi')
            ),
            array(
                'field'  => 'tanggal',
                'label'  => 'Tanggal',
                'rules'  => 'required',
                'errors' => array('required' => '%s harus diisi')
            ),
            array(
                'field'  => 'idprd',
                'label'  => 'Nama Produk',
                'rules'  => 'required',
                'errors' => array('required' => '%s harus diisi')
            ),
            array(
                'field'  => 'jenis',
                'label'  => 'Jenis Opname',
                'rules'  => 'required',
                'errors' => array('required' => '%s harus diisi')
            )
        );
        $this->form_validation->set_rules($config);
    }


}
