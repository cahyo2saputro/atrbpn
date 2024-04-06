<?php 
	class Satgas_model extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
		}
		
		public function show_data()
		{
			$QuerySaya = $this->db->query("
									SELECT a.idusr_usr,a.nip_usr,a.name_usr,a.level_usr,a.activ_usr,b.id_st,b.tarsu_st,b.tarbt_st,b.tadgu_st,b.tadsu_st,b.tadbt_st 
									FROM ms_users a 
									LEFT JOIN tb_satgas b ON a.idusr_usr = b.idusr_usr
									WHERE b.status_st <>'0'
									");
			return $QuerySaya->result();
		}

		public function show_usr()
		{
			$QuerySaya = $this->db->query("SELECT name_usr , idusr_usr FROM ms_users WHERE level_usr <> '1' AND status_usr <> '0'");
			return $QuerySaya->result();
		}

		public function show_edit($id)
		{
			$QuerySaya = $this->db->query("SELECT a.id_st,a.tarsu_st,a.tarbt_st,
				a.tadgu_st,a.tadsu_st,a.tadbt_st,b.idusr_usr FROM tb_satgas a INNER JOIN ms_users b ON a.idusr_usr = b.idusr_usr WHERE a.id_st = '$id'
				");
			return $QuerySaya->result();
		}

		public function tambah_data($value)
		{
			$QuerySaya = $this->db->insert('tb_satgas',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit_data($id,$value)
		{
			$this->db->where('id_st',$id);
			$QuerySaya = $this->db->update('tb_satgas',$value);
			
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function hapus_data($id)
		{
			$this->db->where('id_st',$id);
			
			$ar = array(
				'status_st'	=> '0',
			);
			
			$QuerySaya = $this->db->update('tb_satgas',$ar);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
 ?>