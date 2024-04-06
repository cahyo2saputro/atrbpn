<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Systemlog extends CI_Controller
	{
		var $userdata = NULL;
		function __construct()
		{
			parent::__construct();
			if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}

			$usr = $this->auth_model->get_userdata();
			$level_usr = $usr['level_usr'];
			if($level_usr != "1"){
				redirect('Studio_1_1');
			}
			date_default_timezone_set('Asia/Jakarta');
			$this->content['data']['title_page'] = 'Satgas Fisik';
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$this->content['data']['title'] = "History Logs";
			$this->content['data']['subtitle'] = array(array("Daftar Logs","Systemlog"));

			// MENAMBAHKAN DATA
			$add['table'] = "tb_logs";
			$add['type'] = "single";
			$add['column'] = "COUNT(id_logs) as jumlah";
			$add['like']['aktivitas_logs'] = 'Menambahkan Data';

			// MENGEDIT DATA
			$edit['table'] = "tb_logs";
			$edit['type'] = "single";
			$edit['column'] = "COUNT(id_logs) as jumlah";
			$edit['like']['aktivitas_logs'] = 'Mengedit Data';

			$dat['table'] = "tb_logs";
      		$dat['type'] = "multiple";
			$dat['join']['table'] = 'ms_users';
			$dat['join']['key'] = 'id_user_logs';
			$dat['join']['ref']    = 'idusr_usr';
			$dat['orderby']['column'] = 'date_logs';
			$dat['orderby']['sort'] = 'desc';
			if($this->input->get('tanggal')){
				$dat['like']['date_logs'] = date('Y-m-d',strtotime($this->input->get('tanggal')));
				$add['like']['date_logs'] = date('Y-m-d',strtotime($this->input->get('tanggal')));
				$edit['like']['date_logs'] = date('Y-m-d',strtotime($this->input->get('tanggal')));
			}else{
				$dat['like']['date_logs'] = date('Y-m-d');
				$add['like']['date_logs'] = date('Y-m-d');
				$edit['like']['date_logs'] = date('Y-m-d');
			}

			$user['table'] 			= "tb_logs";
      		$user['type'] 			= "multiple";
			$user['column'] 		= "name_usr,id_user_logs";
			$user['join']['table'] 	= 'ms_users';
			$user['join']['key'] 	= 'id_user_logs';
			$user['join']['ref']    = 'idusr_usr';
			$user['groupby'] 		= 'id_user_logs';
			$user['cuzcondition'] 	= 'id_user_logs !=""';

			$menu['table'] 			= "tb_logs";
      		$menu['type'] 			= "multiple";
			$menu['groupby'] 		= 'menu_logs';
			$menu['cuzcondition'] 	= 'menu_logs !=""';

			$user['like']['date_logs'] = date('Y-m');
			$menu['like']['date_logs'] = date('Y-m');
			$this->content['tanggal'] = date('Y-m-d');

			if($this->input->get('tanggal')){
				$user['like']['date_logs'] = date('Y-m',strtotime($this->input->get('tanggal')));
				$menu['like']['date_logs'] = date('Y-m',strtotime($this->input->get('tanggal')));
				$this->content['tanggal'] = date('Y-m-d',strtotime($this->input->get('tanggal')));
			}

			if($this->input->get('bulan')){
				$dat['like']['date_logs'] = date('Y-m',strtotime($this->input->get('bulan')));
				$add['like']['date_logs'] = date('Y-m',strtotime($this->input->get('bulan')));
				$edit['like']['date_logs'] = date('Y-m',strtotime($this->input->get('bulan')));
			}

			if($this->input->get('user')){
				$dat['condition']['id_user_logs'] = $this->input->get('user') ;
				$add['condition']['id_user_logs'] = $this->input->get('user');
				$edit['condition']['id_user_logs'] = $this->input->get('user');
			}

			if($this->input->get('menu')){
				$dat['condition']['menu_logs'] = $this->input->get('menu') ;
				$add['condition']['menu_logs'] = $this->input->get('menu');
				$edit['condition']['menu_logs'] = $this->input->get('menu');
			}

			$this->content['add'] = $this->crud_model->get_data($add);
			$this->content['edit'] = $this->crud_model->get_data($edit);
      		$this->content['datauser'] = $this->crud_model->get_data($user);
			$this->content['datamenu'] = $this->crud_model->get_data($menu);
			$this->content['history'] = $this->crud_model->get_data($dat);

			$this->content['load'] = array("history/list");
			$this->load->view('adm',$this->content);
		}

		public function dashboard()
		{
			$this->content['data']['title'] = "Dashboard Logs";
			$this->content['data']['subtitle'] = array(array("Daftar Logs","Systemlog"));

			$user['table'] 			= "tb_logs";
      		$user['type'] 			= "multiple";
			$user['column'] 		= "name_usr,id_user_logs";
			$user['join']['table'] 	= 'ms_users';
			$user['join']['key'] 	= 'id_user_logs';
			$user['join']['ref']    = 'idusr_usr';
			$user['groupby'] 		= 'id_user_logs';
			$user['cuzcondition'] 	= 'id_user_logs !=""';

			$menu['table'] 			= "tb_logs";
      		$menu['type'] 			= "multiple";
			$menu['groupby'] 		= 'menu_logs';
			$menu['cuzcondition'] 	= 'menu_logs !=""';

			if($this->input->get('tanggal')){
				$user['like']['date_logs'] = date('Y-m',strtotime($this->input->get('tanggal')));
				$menu['like']['date_logs'] = date('Y-m',strtotime($this->input->get('tanggal')));
				$this->content['tanggal'] = date('Y-m',strtotime($this->input->get('tanggal')));
			}else{
				$user['like']['date_logs'] = date('Y-m');
				$menu['like']['date_logs'] = date('Y-m');
				$this->content['tanggal'] = date('Y-m');
			}

			$datauser = $this->crud_model->get_data($user);
			$datamenu = $this->crud_model->get_data($menu);
			$sorting = array();
			$i=0;
			foreach($datauser as $ds){
				$totalside = 0;$sorting2=array();
				$k=0;
				$sorting2['userid'] = $ds['id_user_logs'];
				$sorting2['user'] = $ds['name_usr'];
				foreach ($datamenu as $mm) {
					$dd['table'] 					= "tb_logs";
					$dd['type'] 					= "single";
					$dd['column'] 					= 'count(id_logs) as jumlah';
					$dd['condition']['menu_logs'] 	= $mm['menu_logs'];
					$dd['condition']['id_user_logs']= $ds['id_user_logs'];
					$dd['like']['date_logs']		= $this->content['tanggal'];
					$hasil = $this->crud_model->get_data($dd);
					$totalside += $hasil['jumlah'];
					$sorting2[$k]= $hasil['jumlah'];
					$k++;
				}
				$sorting2['total']= $totalside;
				$sorting[$i] = $sorting2;
				$i++;
			}

			usort($sorting, function($a, $b) {
				return $b['total'] - $a['total'];
			});

			$this->content['datasorting'] = $sorting;

      		$this->content['datauser'] = $this->crud_model->get_data($user);
			$this->content['datamenu'] = $this->crud_model->get_data($menu);

			$this->content['load'] = array("history/dashboard");
			$this->load->view('adm',$this->content);
		}

	}
 ?>
