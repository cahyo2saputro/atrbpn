<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Reportppat extends CI_Controller
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
			$this->load->view('auth/authorized');
		}

		public function index()
		{
			$this->content['data']['title'] = "Laporan Akta PPAT";
			$this->content['data']['subtitle'] = array(array("laporan akta ppat","Reportppat"));
			$this->content['data']['user'] = $this->auth_model->get_userdata();

			$usr = $this->auth_model->get_userdata();
			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "tb_reportppat";
      $tdata['type'] = "single";
			$tdata['condition']['publish_rpt'] = 1;
			if($this->input->get('bulan')){
					$tdata['like']['tb_reportppat.tglakta_rpt'] =$this->input->get('bulan');
			}else{
					$tdata['like']['tb_reportppat.tglakta_rpt'] = date('Y-m');
			}
			if($this->input->get('user')){
					$tdata['condition']['idppat_rpt']=$this->input->get('user');
			}
			if($usr['level_usr']!=1){
					$tdata['condition']['idppat_rpt']=$usr['idusr_usr'];
			}
			$tdata['column'] = "COUNT(id_rpt) as jumlah";

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Reportppat/index';
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

			$dat['table'] = "tb_reportppat";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "ms_users";
			$dat['join']['key'] = "idusr_usr";
			$dat['join']['ref'] = "idppat_rpt";
			$dat['condition']['publish_rpt'] = 1;
			if($this->input->get('bulan')){
					$dat['like']['tb_reportppat.tglakta_rpt'] =$this->input->get('bulan');
			}else{
					$dat['like']['tb_reportppat.tglakta_rpt'] = date('Y-m');
			}

			$dat['orderby']['column'] = 'tb_reportppat.create_at';
			$dat['orderby']['sort'] = 'desc';
			if($this->input->get('user')){
					$dat['condition']['idppat_rpt']=$this->input->get('user');
			}
			if($usr['level_usr']!=1){
					$dat['condition']['idppat_rpt']=$usr['idusr_usr'];
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
			$this->content['userr'] = $this->crud_model->get_data($user);

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio4/data_report_ppat");
			$this->load->view('adm',$this->content);
		}

		public function excel_reportppat()
		{
			$usr = $this->auth_model->get_userdata();
			$this->content['data']['user'] = $this->auth_model->get_userdata();

			$dat['table'] = "tb_reportppat";
      $dat['type'] = "multiple";
			$dat['join']['table'] = "ms_users";
			$dat['join']['key'] = "idusr_usr";
			$dat['join']['ref'] = "idppat_rpt";
			$dat['condition']['publish_rpt'] = 1;
			if($this->input->get('bulan')){
					$dat['like']['tb_reportppat.tglakta_rpt'] =$this->input->get('bulan');
			}else{
					$dat['like']['tb_reportppat.tglakta_rpt'] = date('Y-m');
			}

			$dat['orderby']['column'] = 'tb_reportppat.create_at';
			$dat['orderby']['sort'] = 'desc';
			if($this->input->get('user')){
					$dat['condition']['idppat_rpt']=$this->input->get('user');
			}
			if($usr['level_usr']!=1){
					$dat['condition']['idppat_rpt']=$usr['idusr_usr'];
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->load->view('studio4/export_report_ppat',$this->content);
		}

		public function bulanan()
		{
			$this->content['data']['title'] = "Rekap Laporan Akta PPAT";
			$this->content['data']['subtitle'] = array(array("rekap laporan akta ppat","Reportppat/bulanan"));
			$this->content['data']['user'] = $this->auth_model->get_userdata();

			$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$tdata['table'] = "ms_users";
      $tdata['type'] = "single";
			$tdata['condition']['level_usr'] = 4;

			$tdata['column'] = "COUNT(idusr_usr) as jumlah";

			$t_data = $this->crud_model->get_data($tdata);

			$config['base_url'] = base_url().'Reportppat/bulanan';
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
			$bulan = '';
			if($this->input->get('bulan')){
				$bulan = "AND tb_reportppat.create_at like '%".$this->input->get('bulan')."%'";
			}
			$dat['column'] = "*,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as sumjb,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as luasjb,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='2' ".$bulan.") as sumhibah,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='2' ".$bulan.") as luashibah,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='3' ".$bulan.") as sumphb,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='3' ".$bulan.") as luasphb,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as sumht,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as luasht,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='5' ".$bulan.") as sumskmht,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='5' ".$bulan.") as luasskmht,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='6' ".$bulan.") as sumatm,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='6' ".$bulan.") as luasatm,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as sumtotal,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as luastotal,
												(SELECT SUM(nilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as nilaiht,
												(SELECT SUM(nilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as nilaiajb,
												(SELECT SUM(sspnilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as nilaissp,
												(SELECT SUM(sppdnilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as nilaisppd,
												";
			$dat['condition']['level_usr'] = 4;
			$dat['orderby']['column'] = 'ms_users.name_usr';
			$dat['orderby']['sort'] = 'asc';

			if($from!=0){
				$dat['limit']['lim'] = 10;
				$dat['limit']['first'] = $from;
			}else{
				$dat['limit'] = 10;
			}

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->content['link'] = $this->pagination->create_links();

			$this->content['load'] = array("studio4/monthly_report_ppat");
			$this->load->view('adm',$this->content);
		}

		public function excel_monthlyppat()
		{

			$dat['table'] = "ms_users";
      $dat['type'] = "multiple";
			$bulan = '';
			if($this->input->get('bulan')){
				$bulan = "AND tb_reportppat.create_at like '%".$this->input->get('bulan')."%'";
			}
			$dat['column'] = "*,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as sumjb,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as luasjb,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='2' ".$bulan.") as sumhibah,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='2' ".$bulan.") as luashibah,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='3' ".$bulan.") as sumphb,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='3' ".$bulan.") as luasphb,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as sumht,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as luasht,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='5' ".$bulan.") as sumskmht,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='5' ".$bulan.") as luasskmht,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='6' ".$bulan.") as sumatm,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='6' ".$bulan.") as luasatm,
												(SELECT COUNT(id_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as sumtotal,
												(SELECT SUM(luastanah_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as luastotal,
												(SELECT SUM(nilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='4' ".$bulan.") as nilaiht,
												(SELECT SUM(nilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr and bph_rpt='1' ".$bulan.") as nilaiajb,
												(SELECT SUM(sspnilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as nilaissp,
												(SELECT SUM(sppdnilai_rpt) FROM tb_reportppat WHERE idppat_rpt=idusr_usr ".$bulan.") as nilaisppd,
												";
			$dat['condition']['level_usr'] = 4;
			$dat['orderby']['column'] = 'ms_users.name_usr';
			$dat['orderby']['sort'] = 'asc';

      $this->content['studio'] = $this->crud_model->get_data($dat);

			$this->load->view('studio4/excel_monthly_ppat',$this->content);
		}

		public function form_reportppat()
		{
			$this->content['mode'] = 'add';
			$this->content['data']['title'] = "Form Input Laporan Akta PPAT";
			$this->content['data']['subtitle'] = array(array("laporan akta ppat","Reportppat"),array("Form Input Laporan PPAT","Reportppat/form_reportppat"));
			$user = $this->auth_model->get_userdata();

			if ($this->input->post()) {
				if($user['level_usr']==1){
					$ppat=$this->input->post('ppat');
				}else{
					$ppat=$user['idusr_usr'];
				}

				$dataarray = array(
					'idppat_rpt'   => $ppat,
					'noakta_rpt'   => $this->input->post('noakta'),
					'tglakta_rpt'   => date("Y-m-d",strtotime($this->input->post('tanggalakta'))),
					'bph_rpt'   => $this->input->post('bph'),
					'palih_rpt'   => $this->input->post('pihakalih'),
					'pterima_rpt'   => $this->input->post('penerima'),
					'nohak_rpt'   => $this->input->post('hak'),
					'lokasi_rpt'   => $this->input->post('lokasi'),
					'luastanah_rpt'   => $this->input->post('luastanah'),
					'luasbangunan_rpt'   => $this->input->post('luas'),
					'nilai_rpt'   => $this->input->post('nilai'),
					'spptnop_rpt'   => $this->input->post('spptnop'),
					'spptnjop_rpt'   => $this->input->post('spptnjop'),
					'ssptanggal_rpt'   => date("Y-m-d",strtotime($this->input->post('ssptanggal'))),
					'sspnilai_rpt'   => $this->input->post('sspnilai'),
					'sppdtanggal_rpt'   => date("Y-m-d",strtotime($this->input->post('sppdtanggal'))),
					'sppdnilai_rpt'   => $this->input->post('sppdnilai'),
					'publish_rpt'   => '1',
					'idusr_rpt' => $user['idusr_usr'],
					'create_at' => date("Y-m-d H:i:s")
				);
				$simpan = $this->crud_model->input('tb_reportppat',$dataarray);
				$insert_id = $this->db->insert_id();
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_reportppat','Laporan PPAT-'.$user['usrid_usr'].'<br>'.$user['name_usr'].'-'.$insert_id,"Menambahkan Data Report PPAT  dengan rincian ".displayArray($dataarray));
				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>Reportppat/">
				<?php
			}

			if($user['level_usr']==1){
				$userppat['table'] = "ms_users";
	      $userppat['type'] = "multiple";
				$userppat['condition']['level_usr'] = 4;
				$this->content['ppat'] = $this->crud_model->get_data($userppat);
			}
			$this->content['report'] = null;
			$this->content['load'] = array("studio4/form_report_ppat");
			$this->load->view('adm',$this->content);
		}

		public function edit_reportppat($id)
		{
			$this->content['mode'] = 'edit';
			$this->content['data']['title'] = "Form Edit Laporan Akta PPAT";
			$this->content['data']['subtitle'] = array(array("laporan akta ppat","Reportppat"),array("Form Edit Laporan Akta PPAT","Reportppat/edit_reportppat/".$id));
			$user = $this->auth_model->get_userdata();

			cekuserppat($user['idusr_usr'],$user['level_usr'],$id);

			if ($this->input->post()) {
				if($user['level_usr']==1){
					$ppat=$this->input->post('ppat');
				}else{
					$ppat=$user['idusr_usr'];
				}

				$dataarray = array(
					'idppat_rpt'   => $ppat,
					'noakta_rpt'   => $this->input->post('noakta'),
					'tglakta_rpt'   => date("Y-m-d",strtotime($this->input->post('tanggalakta'))),
					'bph_rpt'   => $this->input->post('bph'),
					'palih_rpt'   => $this->input->post('pihakalih'),
					'pterima_rpt'   => $this->input->post('penerima'),
					'nohak_rpt'   => $this->input->post('hak'),
					'lokasi_rpt'   => $this->input->post('lokasi'),
					'luastanah_rpt'   => $this->input->post('luastanah'),
					'luasbangunan_rpt'   => $this->input->post('luas'),
					'nilai_rpt'   => $this->input->post('nilai'),
					'spptnop_rpt'   => $this->input->post('spptnop'),
					'spptnjop_rpt'   => $this->input->post('spptnjop'),
					'ssptanggal_rpt'   => date("Y-m-d",strtotime($this->input->post('ssptanggal'))),
					'sspnilai_rpt'   => $this->input->post('sspnilai'),
					'sppdtanggal_rpt'   => date("Y-m-d",strtotime($this->input->post('sppdtanggal'))),
					'sppdnilai_rpt'   => $this->input->post('sppdnilai'),
					'idusr_rpt' => $user['idusr_usr']
				);
				$simpan = $this->crud_model->update('tb_reportppat',$dataarray,array('id_rpt'=>$id));
				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_reportppat','Laporan PPAT-'.$user['usrid_usr'].'<br>'.$user['name_usr'].'-'.$id,"Mengubah Data Report PPAT  dengan rincian ".displayArray($dataarray));
				?>
				<meta http-equiv="refresh" content="1;<?php echo base_url();?>Reportppat/">
				<?php
			}

			if($user['level_usr']==1){
				$userppat['table'] = "ms_users";
	      $userppat['type'] = "multiple";
				$userppat['condition']['level_usr'] = 4;
				$this->content['ppat'] = $this->crud_model->get_data($userppat);
			}

			$reportppat['table'] = "tb_reportppat";
			$reportppat['type'] = "single";
			$reportppat['condition']['id_rpt'] = $id;
			$this->content['report'] = $this->crud_model->get_data($reportppat);

			$this->content['load'] = array("studio4/form_report_ppat");
			$this->load->view('adm',$this->content);
		}

		public function delete($kode)
		{
			$ar = array(
				'publish_rpt' => '0'
			);
			$hapus = $this->crud_model->update('tb_reportppat',$ar,array('id_rpt'=>$kode));
			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_reportppat','Laporan PPAT-0-'.$kode,"Menghapus Laporan PPAT dengan kode ".$kode);

			if($hapus){
				$msg = true;
			}
			echo json_encode($msg);die();
		}
}
 ?>
