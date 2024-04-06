<?php
class Kelurahan_kw_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function show_data()
	{
		$QuerySaya = $this->db->query("SELECT nama_kelurahan,nama_kecamatan, change as kode FROM tb_kec_purwo");
		return $QuerySaya->result();
	}

	public function show_edit_kelurahan($kode)
	{
		$QuerySaya = $this->db->query("SELECT a.nma_kel, a.kdpbb_kel, a.kd_full , b.nma_kec,b.kd_kec,a.id_kel,a.kd_kel FROM ms_kelurahan a LEFT JOIN ms_kecamatan b ON a.kdkec_kel = b.kd_kec WHERE id_kel = '$kode'");
		return $QuerySaya->row_array();
	}

	public function data_kecamatan_add_all($kode = NULL)
	{
		if(isset($kode)){
			$QuerySaya = $this->db->query("SELECT b.nama_kecamatan, b.id_kecamatan FROM tb_kelkw a INNER JOIN tb_kecamatan b ON a.id_kecamatan = b.id_kecamatan WHERE b.id_kabkota = '54' AND a.id_kelkw ='$kode' ");
		}else{
			$QuerySaya = $this->db->query("SELECT nama_kecamatan, id_kecamatan FROM tb_kecamatan WHERE id_kabkota = '54'");
		}
		return $QuerySaya->result();
	}

	public function data_kelurahan_add_all($kode = NULL)
	{
		if(isset($kode)){
			$search_kecamatan 	= $this->db->query("SELECT id_kecamatan FROM tb_kelkw WHERE id_kelkw = '$kode'");
			$ar 				= $search_kecamatan->row_array();
			$id_kecamatan		= $ar['id_kecamatan'];
			$QuerySaya = $this->db->query("SELECT nama_kelurahan, id_kelurahan FROM tb_kelurahan WHERE id_kabkota = '54' AND id_kecamatan = '$id_kecamatan'");
		}else{
			$QuerySaya = $this->db->query("SELECT nama_kelurahan, id_kelurahan FROM tb_kelurahan WHERE id_kabkota = '54'");
		}
		return $QuerySaya->result();
	}

	public function simpan_data_kelurahan($value)
	{
		$QuerySaya = $this->db->insert('ms_kelurahan',$value);
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function edit_data_kelurahan($id,$value)
	{
		$this->db->where('id_kel',$id);
		$QuerySaya = $this->db->update('ms_kelurahan',$value);
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function hapus_data_kelurahan($id)
	{
		$this->db->where('id_kel', $id);
		$QuerySaya = $this->db->delete('ms_kelurahan');
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function cari_data_desa($id)
	{
		$QuerySaya = $this->db->query("SELECT * FROM tb_kelurahan WHERE id_kecamatan = '$id'");
		return $QuerySaya->result();
	}

}
?>
