<?php
	class Detail_su_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function show_sugs()
		{
			$QuerySaya = $this->db->query("SELECT DISTINCT sugs_su FROM tb_su");
			return $QuerySaya->result();
		}

		public function show_edit_su($kode)
		{
			$QuerySaya = $this->db->query("
											SELECT a.id_su,REPLACE(SUBSTR(a.nohak_su,1,20),'.','') as nohakfile,a.nohak_su,b.id_kelurahan,a.sugs_su,a.no_su,a.thn_su,a.luas_su,a.produk_su,a.luaspeta_su,a.nolem_su,a.norak_su,a.nobalb_su,a.fisik_su, c.kd_full,c.nma_kel 
											FROM tb_su a
											LEFT JOIN tb_hak b ON a.nohak_su = b.no_hak
											LEFT JOIN ms_kelurahan c ON b.id_kelurahan = c.kd_full
											WHERE a.nohak_su = '$kode' AND a.status_su <>'0'
				");
			return $QuerySaya->row_array();
		}

		public function simpan_data_su($value,$nohak)
		{
			$this->db->query("UPDATE tb_hak SET status_su = '1' WHERE no_hak = '$nohak'");
			$QuerySaya = $this->db->insert('tb_su',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function edit_data_su($id,$value)
		{
			$this->db->where('id_su',$id);
			$QuerySaya = $this->db->update('tb_su',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}


		public function hapus_data_su($id)
		{
			$this->db->where('id_detail_su',$id);

			$value = array(
				'status_tb_detail_su' => '0'
			);

			$QuerySaya = $this->db->update('tb_detail_su',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function simpan_data_upload_su($value)
		{
			$QuerySaya = $this->db->insert('tb_upsu',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function sr_hak($nohak)
		{
			$QuerySaya = $this->db->query("SELECT REPLACE(SUBSTR(no_hak,14,25),'.','') as no_hak FROM `tb_hak` WHERE no_hak = '$nohak'");
			return $QuerySaya->row_array();
		}

		public function sr_su($nohak)
		{
			$QuerySaya = $this->db->query("SELECT id_su FROM tb_su WHERE nohak_su = '$nohak'");
			return $QuerySaya->row_array();
		}
	}
 ?>
