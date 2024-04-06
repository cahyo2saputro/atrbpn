<?php
	class Studio_2_1_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function show_data($number,$offset,$cari = NULL)
		{
			$select = array(
				'idblk_blk',
				'idusr_blk',
				'nama_blk',
				'petblk_blk',
				'(SELECT COUNT(id_bpt) FROM tb_blockptsl WHERE idbpt_blk = idblk_blk) AS jml_ptsl',
				'(SELECT COUNT(idblk_nub) FROM tb_nub WHERE idblk_nub = idblk_blk AND status_nub <> 0 AND nohak_nub IS NOT NULL) AS jml_terdaftar',
				'(SELECT COUNT(idblk_nub) FROM tb_nub WHERE idblk_nub = idblk_blk AND status_nub <> 0 AND nohak_nub IS NULL) AS jml_tidak'
			);
			if (isset($cari)) {
				$this->db->select($select);
				$this->db->from('tb_block');
				$this->db->where('status_blk <>',0);
				$this->db->group_start();
					$this->db->where('idkel_blk =',$cari);
					$this->db->or_where('nama_blk =',$cari);
				$this->db->group_end();
				$this->db->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				$this->db->select($select);
				$this->db->from('tb_block');
				$this->db->where('status_blk <>',0);
				$this->db->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}
			return $QuerySaya->result();
		}

		public function show_usr()
		{
			$QuerySaya = $this->db->query("SELECT name_usr , idusr_usr FROM ms_users WHERE level_usr <> '1' AND status_usr <> '0'");
			return $QuerySaya->result();
		}

		public function show_edit($id)
		{
			$QuerySaya = $this->db->query("SELECT idblk_blk,idkel_blk,nama_blk,nma_kel,petblk_blk,petptsl_blk FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full WHERE idblk_blk = '$id'
				");
			return $QuerySaya->row_array();
		}

		public function simpan($value)
		{
			$QuerySaya = $this->db->insert('tb_block',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit($id,$value)
		{
			$this->db->where('idblk_blk',$id);
			$QuerySaya = $this->db->update('tb_block',$value);

			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function hapus($id)
		{
			$this->db->where('idblk_blk',$id);

			$ar = array(
				'status_blk'	=> '0',
			);

			$QuerySaya = $this->db->update('tb_block',$ar);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function sr_name_block($id)
		{
			$QuerySaya = $this->db->query("SELECT *,ms_kelurahan.nma_kel FROM tb_block,ms_kelurahan WHERE idblk_blk = '$id' AND idkel_blk=kd_full");
			return $QuerySaya->row_array();
		}

		public function sr_peta_ptsl($id)
		{
			$QuerySaya = $this->db->query("SELECT UPPER(nma_kel) as nma_kel, UPPER(nma_kec) as nma_kec ,nama_blk, petblk_blk  FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idkel_blk = '$id'");
			return $QuerySaya->result();
		}
	}
 ?>
