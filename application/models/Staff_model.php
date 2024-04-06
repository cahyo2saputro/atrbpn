<?php
	class Staff_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function tampil_data()
		{
			$QuerySaya = $this->db->query("SELECT idusr_usr,nip_usr,name_usr,usrid_usr,ket_usr,level_usr FROM ms_users WHERE status_usr <>'0'");
			return $QuerySaya->result();
		}

		public function staff_id($id)
		{
			$QuerySaya = $this->db->query("
					SELECT a.idusr_usr,a.nip_usr,a.name_usr,a.level_usr,a.activ_usr,a.usrid_usr,a.ket_usr
					FROM ms_users a
					WHERE a.idusr_usr = '$id'
				");
			return $QuerySaya->result();
		}

		public function staff_nip($nip)
		{
			$QuerySaya = $this->db->query("SELECT idusr_usr FROM ms_users WHERE nip_usr = '$nip' AND status_usr <> '0'");
			return $QuerySaya->row_array();
		}

		public function staff_pass($id)
		{
			 $QuerySaya = $this->db->query("SELECT pasid_usr FROM ms_users WHERE idusr_usr = '$id'");
			 return $QuerySaya->row_array();
		}

		public function tambah_data($value)
		{
			$QuerySaya = $this->db->insert('ms_users',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function edit_data($id,$value)
		{
			$this->db->where('idusr_usr',$id);
			$QuerySaya = $this->db->update('ms_users',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function hapus_data($id)
		{
			$sr_st = $this->db->query("SELECT id_st FROM tb_satgas WHERE idusr_usr = '$id'")->row_array();
			$id_st = $sr_st['id_st'];

			$this->db->query("UPDATE tb_satgas SET status_st = '0' WHERE id_st ='$id_st'");

			$this->db->where('idusr_usr',$id);

			$ar = array(
				'status_usr'	=> '0',
			);

			$QuerySaya = $this->db->update('ms_users',$ar);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
 ?>
