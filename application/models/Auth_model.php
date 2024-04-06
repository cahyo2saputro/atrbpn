<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Auth_model extends CI_Model {



	public function __construct (){

		$this->load->database();

	}



	public function authorizing ($data){

		if($data['source']==1){
			$condition = "u.usrid_usr =" . "'" . $data['userid'] . "' AND " . "u.pasid_usr =" . "'" .enkripsi_pass($data['passid']). "' AND activ_usr='1'";
			$this->db->select('u.*,u.level_usr');
			$this->db->from('ms_users u');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				return $query -> row_array();
			}else {
				return false;
			}
		}else{
			$condition = "nik_reg =" . "'" . $data['userid'] . "' AND " . "pass_reg =" . "'" .enkripsi_pass($data['passid']). "' AND publish_reg='1' AND typeusr_reg='3'";
			$this->db->select('nik_reg as userid,id_reg as idusr_usr');
			$this->db->from('tb_register');
			$this->db->where($condition);
			$this->db->limit(1);
			$query = $this->db->get();
			if ($query->num_rows() == 1) {
				return $query -> row_array();
			}else {
				return false;
			}
		}

	}

	public function authorizing_guest ($data){
		$condition = "u.nik_reg =" . "'" . $data['userid'] . "' AND " . "u.pass_reg =" . "'" .enkripsi_pass($data['passid']). "' AND publish_reg='1'";

		$this->db->select('u.*');

		$this->db->from('tb_register u');

		/*$this->db->join('ms_employee e',"u.idemp_usr=e.idemp_emp","LEFT");*/

		$this->db->where($condition);

		$this->db->limit(1);



		$query = $this->db->get();

		if ($query->num_rows() == 1) {

			return $query -> row_array();

		}

		else {

			return false;

		}

	}

	public function get_userdata ($userid = NULL){
		if(!$this->session->smt_member){
			redirect('auth');
		}
		if($userid == NULL){
			if($this->session->smt_member['source']==1){
					$condition = "u.usrid_usr =" . "'" . $this->session->smt_member['userid']."'";
			}else{
					$condition = "u.nik_reg =" . "'" . $this->session->smt_member['userid']."'";
			}


		}

		else {

			$condition = "u.usrid_usr =" . "'" . $userid."'";

		}

		if($this->session->smt_member['source']==1){
			$this->db->select('u.*');
			$this->db->from('ms_users u');
		}else{
			$this->db->select('u.*,u.id_reg as idusr_usr,nma_reg as name_usr,ktp_reg as foto_usr,nik_reg as usrid_usr');
			$this->db->from('tb_register u');
		}



		$this->db->where($condition);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {

			return $query -> row_array();

		}

		else {

			return false;

		}



	}






	public function get_guestdata ($userid = NULL){
		if(!$this->session->smt_member){
			redirect('guest');
		}
		if($userid == NULL){

			$condition = "u.nik_reg =" . "'" . $this->session->smt_member['userid']."'";

		}

		else {

			$condition = "u.nik_reg =" . "'" . $userid."'";

		}

		/*$this->db->select('u.*,e.idemp_emp,e.name_emp,e.nip_emp,e.photo_emp,e.jabatan_emp');

		$this->db->from('ms_users u');

		$this->db->join('ms_employee e',"u.idemp_usr=e.idemp_emp","LEFT");*/

		$this->db->select('u.*');

		$this->db->from('tb_register u');

		/*$this->db->join('ms_employee e',"u.idemp_usr=e.idemp_emp","LEFT");*/

		$this->db->where($condition);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {

			return $query -> row_array();

		}

		else {

			return false;

		}



	}





}



?>
