<?php 
	class Studio_2_2_model extends CI_Model
	{
		
		function __construct()
		{
			parent::__construct();
		}
		
		public function show_data($number,$offset,$cari = NULL)
		{
			if (isset($cari)) {
				$this->db->select('
					idnub_nub,
					idform_fnub,
					nohak_nub,
					idblk_blk,
					status_nub,
					status_fnub
					');
				$this->db->from('tb_block');
				$this->db->join('tb_nub','idblk_nub = idblk_blk','left');
				$this->db->join('tb_formnub','idnub_fnub = idnub_nub','left');
				$this->db->where('status_nub <>',0);
				$this->db->group_start();
					$this->db->where('idblk_nub =',$cari);
				$this->db->group_end();
				$this->db->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				$this->db->select('
					idnub_nub,
					idform_fnub,
					nohak_nub,
					idblk_blk,
					status_nub,
					status_fnub
					');
				$this->db->from('tb_block');
				$this->db->join('tb_nub','idblk_nub = idblk_blk','left');
				$this->db->join('tb_formnub','idnub_fnub = idnub_nub','left');
				$this->db->where('status_nub <>',0);
				$this->db->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}
			return $QuerySaya->result();
		}
		
		public function simpan_nub($value)
		{
			$QuerySaya = $this->db->insert('tb_nub',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function simpan_formnub($value)
		{
			$QuerySaya = $this->db->insert('tb_formnub',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit_nub($id,$value)
		{
			$this->db->where('idnub_nub',$id);
			$QuerySaya = $this->db->update('tb_nub',$value);
			
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit_formnub($id,$value)
		{
			$this->db->where('idform_fnub',$id);
			$QuerySaya = $this->db->update('tb_formnub',$value);
			
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function hapus_nub($id)
		{
			$this->db->where('idnub_nub',$id);
			
			$ar = array(
				'status_nub'	=> '0',
			);
			
			$QuerySaya = $this->db->update('tb_nub',$ar);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function hapus_formnub($id)
		{
			$nub = $this->db->query("SELECT idnub_fnub FROM tb_formnub WHERE idform_fnub = '$id'")->row_array();
			$idnub = $nub['idnub_fnub'];

			$this->db->query("UPDATE tb_nub SET status_nub = '0' WHERE idnub_nub = '$idnub'");

			$this->db->where('idform_fnub',$id);
			
			$ar = array(
				'status_fnub'	=> '0',
			);
			
			$QuerySaya = $this->db->update('tb_formnub',$ar);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}