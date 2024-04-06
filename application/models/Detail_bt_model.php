<?php
	class Detail_bt_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function show_edit_bt($kode)
		{
			$QuerySaya = $this->db->query("
							SELECT a.id_bt,REPLACE(SUBSTR(a.nohak_bt,1,25),'.','') as nohakfile,a.nohak_bt,a.nosertif_bt,b.id_kelurahan,a.nolem_bt,a.norak_bt,a.nobalb_bt,a.fisik_bt,a.validasi_bt, c.kd_full,c.nma_kel
							FROM tb_bt a
							LEFT JOIN tb_hak b ON a.nohak_bt = b.no_hak
							LEFT JOIN ms_kelurahan c ON b.id_kelurahan = c.kd_full
							WHERE a.nohak_bt = '$kode' AND a.status_bt <>'0'
				");
			return $QuerySaya->row_array();
		}

		public function simpan_data_bt($value,$nohak)
		{
			$this->db->query("UPDATE tb_hak SET status_bt = '1' WHERE no_hak = '$nohak'");
			$QuerySaya = $this->db->insert('tb_bt',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit_data_bt($id,$value)
		{
			$this->db->where('id_bt',$id);
			$QuerySaya = $this->db->update('tb_bt',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}


		public function hapus_data_bt($id)
		{
			$this->db->where('id_detail_bt',$id);

			$value = array(
				'status_tb_detail_bt' => '0'
			);

			$QuerySaya = $this->db->update('tb_detail_bt',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function simpan_data_upload_bt($value)
		{
			$QuerySaya = $this->db->insert('tb_upbt',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function update_bt($nohak)
		{
			$this->db->where('no_hak',$nohak);

			$value = array(
				'buku_tanah' => '1'
			);

			$QuerySaya = $this->db->update('tb_hak',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function sr_hak($nohak)
		{
			$QuerySaya = $this->db->query("SELECT REPLACE(SUBSTR(no_hak,1,25),'.','') as no_hak FROM `tb_hak` WHERE no_hak = '$nohak'");
			return $QuerySaya->row_array();
		}

		public function sr_bt($nohak)
		{
			$QuerySaya = $this->db->query("SELECT id_bt FROM tb_bt WHERE nohak_bt = '$nohak'");
			return $QuerySaya->row_array();
		}
	}
 ?>
