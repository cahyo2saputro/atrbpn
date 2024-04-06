<?php
	class Studio2_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function show_kecamatan($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_pal) FROM tb_petaanalog WHERE idkel_pal = kd_full) as jml_petanalog',
				//'(SELECT COUNT(idblk_nub) FROM tb_nub,tb_block WHERE idkel_blk = kd_full AND idblk_nub = idblk_blk AND status_nub <> 0 AND nohak_nub IS NOT NULL) AS jml_terdaftar',
				//'(SELECT COUNT(idblk_nub) FROM tb_nub,tb_block WHERE idkel_blk = kd_full AND idblk_nub = idblk_blk AND status_nub <> 0 AND nohak_nub IS NULL) AS jml_tidak',
				'idpet_pt',
				'idkel_pt',
				'petkerja_pt',
				'petonline_pt',
				'datadhk_pt',
				'datadi208_pt',
				'penduduk_pt',
				'datak4_pt',
				'petkkp_pt'
			);
			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->join('tb_peta','kd_full = idkel_pt','left')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['level_usr']!=1 && $user['level_usr'] != "3"){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->join('tb_peta','kd_full = idkel_pt','left')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->join('tb_peta','kd_full = idkel_pt','left')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->join('tb_peta','kd_full = idkel_pt','left')
						->where('kd_full =',$carikelurahan)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1 && $user['level_usr'] != "3"){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->join('tb_peta','kd_full = idkel_pt','left')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->join('tb_peta','kd_full = idkel_pt','left')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}

			return $QuerySaya->result();
		}

		public function simpan($value)
		{
			$QuerySaya = $this->db->insert('tb_peta',$value);
			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}

		public function edit($id,$value)
		{
			$this->db->where('idpet_pt',$id);
			$QuerySaya = $this->db->update('tb_peta',$value);

			if($QuerySaya){
				return TRUE;
			}else{
				return FALSE;
			};
		}
	}
?>
