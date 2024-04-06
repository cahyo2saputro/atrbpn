<?php 
	class Detail_gu_model extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
		}

		public function data_kecamatan($kode)
		{
			$sid_kelurahan = $this->db->query("SELECT a.id_gu,a.no_gu,a.thn_gu,a.nosu1_gu,a.nosu2_gu,a.thnsu_gu,a.nolem_gu,a.norak_gu,a.nobalb_gu,a.fisik_gu, d.id_kelurahan,d.nama_kelurahan 
				FROM tb_gu a 
				LEFT JOIN tb_su b ON b.no_su = a.nosu1_gu 
				LEFT JOIN tb_studio_1_1 c ON b.nohak_su = c.no_hak 
				LEFT JOIN tb_kel_purwo d ON c.id_kelurahan = d.id_kelurahan
				WHERE a.id_gu = '$kode' AND a.status_gu <>'0'")->row_array();
			$set 			= $sid_kelurahan['id_kelurahan'];
			if($set == "0"){
				$QuerySaya = $this->db->query("SELECT nama_kecamatan, id_kecamatan FROM tb_kecamatan WHERE id_kabkota = '54'");	
			}else{
				$QuerySaya = $this->db->query("SELECT c.nama_kecamatan, c.id_kecamatan FROM tb_studio_1_1 a INNER JOIN tb_kelurahan b ON a.id_kelurahan = b.id_kelurahan INNER JOIN tb_kecamatan c ON b.id_kecamatan = c.id_kecamatan WHERE a.id_kelurahan = '$set'");
			}
			return $QuerySaya->result();
		}

		public function data_kelurahan($kode)
		{
			$sid_kelurahan = $this->db->query("SELECT d.id_kelurahan FROM tb_gu a LEFT JOIN tb_su b ON b.no_su = a.nosu1_gu LEFT JOIN tb_studio_1_1 c ON b.nohak_su = c.no_hak LEFT JOIN tb_kel_purwo d ON c.id_kelurahan = d.id_kelurahan WHERE a.id_gu = '$kode' AND a.status_gu <>'0'")->row_array();
			$set 			= $sid_kelurahan['id_kelurahan'];
			if($set == "0"){
				$QuerySaya = $this->db->query("SELECT nama_kelurahan, id_kelurahan FROM tb_kel_purwo");	
			}else{
				$QuerySaya = $this->db->query("SELECT b.nama_kelurahan,b.id_kelurahan FROM tb_studio_1_1 a INNER JOIN tb_kel_purwo b ON a.id_kelurahan = b.id_kelurahan WHERE a.id_kelurahan = '$set'");
			}
			return $QuerySaya->result();
		}

		public function show_edit_gu($kode)
		{
			$QuerySaya = $this->db->query("
							SELECT a.id_gu,a.no_gu,a.thn_gu,a.nosu1_gu,a.nosu2_gu,a.thnsu_gu,a.nolem_gu,a.norak_gu,a.nobalb_gu,a.fisik_gu, d.id_kelurahan,d.nama_kelurahan 
							FROM tb_gu a 
							LEFT JOIN tb_su b ON b.no_su = a.nosu1_gu 
							LEFT JOIN tb_studio_1_1 c ON b.nohak_su = c.no_hak 
							LEFT JOIN tb_kel_purwo d ON c.id_kelurahan = d.change 
							WHERE a.id_gu = '$kode' AND a.status_gu <>'0'
				");
			return $QuerySaya->row_array();
		}

		public function simpan_data_gu($value)
		{
			$QuerySaya = $this->db->insert('tb_gu',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit_data_gu($id,$value)
		{
			$this->db->where('id_gu',$id);
			$QuerySaya = $this->db->update('tb_gu',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}


		public function hapus_data_gu($id)
		{
			$this->db->where('id_detail_gu',$id);
			
			$value = array(
				'status_tb_detail_gu' => '0'
			);
			
			$QuerySaya = $this->db->update('tb_detail_gu',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function simpan_data_upload_gu($value)
		{
			$QuerySaya = $this->db->insert('tb_upgu',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}
		
		public function count_doc($kode)
		{
			$QuerySaya = $this->db->query("SELECT b.status_tb_upload_detail_gu FROM tb_detail_gu a INNER JOIN tb_upload_detail_gu b ON a.id_detail_gu = b.id_detail_gu where b.status_tb_upload_detail_gu<>'0' AND a.id_studio_1_1 = '$kode'");
			return $QuerySaya->num_rows();
		}

		public function sr_gu($no_gu)
		{
			$QuerySaya = $this->db->query("SELECT id_gu FROM tb_gu WHERE no_gu = '$no_gu'");
			return $QuerySaya->row_array();
		}

	}
 ?>