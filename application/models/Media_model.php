<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model {
	
	public function __construct (){
		$this->load->database();
	}

	public function get_data ($nssch=FALSE){
		if($nssch === FALSE){
			return NULL;
		}
		$this->db->select('ja.*,jt.nmjnt_jnt as jenisalat');
		$this->db->order_by('tahun_jna','DESC');
		$this->db->join('ms_jenisalat jt','ja.idjnt_jna=jt.idjnt_jnt','left');
		$query = $this->db->get_where('tb_jenisalat ja',array('nssch_jna' => $nssch));
		return $query -> result_array();
	}

	public function get_data_by_id ($nssch=FALSE,$id=FALSE){
		if($nssch === FALSE or $id===FALSE){
			return NULL;
		}
		$this->db->select('ja.*,jt.nmjnt_jnt as jenisalat');
		$this->db->join('ms_jenisalat jt','ja.idjnt_jna=jt.idjnt_jnt','left');
		$query = $this->db->get_where('tb_jenisalat ja',array('nssch_jna' => $nssch,'idjna_jna' => $id));
		return $query -> row_array();
	}

	public function get_data_jenisalat (){

		$query = $this->db->get_where('ms_jenisalat',array('subid_jnt' => 0));
		return $query -> result_array();
	}

	public function get_data_subjenisalat (){

		$query = $this->db->get_where('ms_jenisalat',array('subid_jnt' != 0));
		return $query -> result_array();
	}

} 

?>