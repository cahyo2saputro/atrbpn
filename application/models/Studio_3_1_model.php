<?php
	class Studio_3_1_model extends CI_Model
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
				'idkel_blk',
				'nama_blk',
				'petblk_blk',
				'petonline_blk',
				'(SELECT COUNT(id_bpt) FROM tb_blockptsl WHERE idbpt_blk = idblk_blk) AS jml_ptsl',
				'(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE idblk_nub = idblk_blk AND publish_nub <> 0) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl WHERE idblk_ptsl = idblk_blk AND publish_ptsl <> 0) AS jml_tidak',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp = idblk_blk) AS jml_dhkp',
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
				$this->db->order_by('nama_blk','ASC');
				$QuerySaya = $this->db->get();
			}else{
				$this->db->select($select);
				$this->db->from('tb_block');
				$this->db->where('status_blk <>',0);
				$this->db->limit($number,$offset);
				$this->db->order_by('nama_blk','ASC');
				$QuerySaya = $this->db->get();
			}
			return $QuerySaya->result();
		}

		public function show_data_tr($number,$offset,$cari = NULL)
		{
			$select = array(
				'idblk_blk',
				'idusr_blk',
				'idkel_blk',
				'nama_blk',
				'petblk_blk',
				'petonline_blk',
				'pettr_blk',
				'(SELECT COUNT(id_buk) FROM tb_blockukur WHERE idbuk_blk = idblk_blk) AS jml_ukur',
				'(SELECT COUNT(id_bgu) FROM tb_blockgu WHERE idbgu_blk = idblk_blk) AS jml_gu',
				'(SELECT COUNT(id_bdm) FROM tb_blockdatmen WHERE idbdm_blk = idblk_blk) AS jml_datmen',
				'(SELECT COUNT(id_bpt) FROM tb_blockptsl WHERE idbpt_blk = idblk_blk) AS jml_ptsl',
				'(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE idblk_nub = idblk_blk AND publish_nub <> 0) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl WHERE idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND luasfisik_ptsl>0 AND publish_ptsl <> 0) AS jml_tidak',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp = idblk_blk) AS jml_dhkp'
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
				$this->db->order_by('nama_blk','ASC');
				$QuerySaya = $this->db->get();
			}else{
				$this->db->select($select);
				$this->db->from('tb_block');
				$this->db->where('status_blk <>',0);
				$this->db->limit($number,$offset);
				$this->db->order_by('nama_blk','ASC');
				$QuerySaya = $this->db->get();
			}
			return $QuerySaya->result();
		}

		public function show_data_5($number,$offset,$cari = NULL)
		{
			$select = array(
				'idblk_blk',
				'idusr_blk',
				'idkel_blk',
				'nama_blk',
				'petonline_blk',
				'(SELECT COUNT(id_buk) FROM tb_blockukur WHERE idbuk_blk = idblk_blk) AS jml_ukur',
				'(SELECT COUNT(id_bgu) FROM tb_blockgu WHERE idbgu_blk = idblk_blk) AS jml_gu',
				'(SELECT COUNT(id_bdm) FROM tb_blockdatmen WHERE idbdm_blk = idblk_blk) AS jml_datmen',
				'(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE idblk_nub = idblk_blk AND publish_nub <> 0) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl WHERE idblk_ptsl = idblk_blk AND luasfisik_ptsl>0 AND publish_ptsl <> 0) AS jml_tidak',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp = idblk_blk) AS jml_dhkp'
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
				$this->db->order_by('nama_blk','ASC');
				$QuerySaya = $this->db->get();
			}else{
				$this->db->select($select);
				$this->db->from('tb_block');
				$this->db->where('status_blk <>',0);
				$this->db->limit($number,$offset);
				$this->db->order_by('nama_blk','ASC');
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
			$QuerySaya = $this->db->query("SELECT idblk_blk,idkel_blk,nama_blk,nma_kel FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full WHERE idblk_blk = '$id'
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
			$QuerySaya = $this->db->query("SELECT * FROM tb_block WHERE idblk_blk = '$id'");
			return $QuerySaya->row_array();
		}

		public function sr_peta_ptsl($id)
		{
			$QuerySaya = $this->db->query("SELECT UPPER(nma_kel) as nma_kel, UPPER(nma_kec) as nma_kec ,nama_blk, petblk_blk  FROM tb_block LEFT JOIN ms_kelurahan ON idkel_blk = kd_full LEFT JOIN ms_kecamatan ON kdkec_kel = kd_kec WHERE idkel_blk = '$id'");
			return $QuerySaya->result();
		}
	}
 ?>
