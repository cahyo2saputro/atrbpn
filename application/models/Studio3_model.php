<?php
	class Studio3_model extends CI_Model
	{

		function __construct()
		{
			parent::__construct();
		}

		public function graphic($tahun)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'target_tgt as target',
			);
			$this->db
				->select($select)
				->from('ms_kelurahan')
				->join('tb_targetuser','kdfull_tgt=kd_full')
				->where('tahun_tgt =',$tahun)
				->group_by('kd_full');
			$QuerySaya = $this->db->get();

			return $QuerySaya->result();
		}

		public function show_unregister($idblk,$tipe,$idkel=NULL)
		{
			if($tipe=='count'){
				$select = array(
					'count(nib_ptsl) as belumfix'
				);
			}else if($tipe=='data'){
				$select = array(
					'nib_ptsl',
					'id_ptsl',
					'iddhkp_ptsl',
					'no_hak'
				);
				$this->db->join('tb_hak','nib_hak=nib_ptsl AND id_kelurahan='.$idkel,'LEFT');
				$this->db->join('tb_ptsldhkp','idptsl_ptsl=id_ptsl','LEFT');
				// $this->db->where('iddhkp_ptsl!=','');
			}

			$this->db
				->select($select)
				->from('tb_ptsl')
				->where('nib_ptsl !=','')
				->where('idblk_ptsl =',$idblk);
			$QuerySaya = $this->db->get();

			if($tipe=='count'){
				return $QuerySaya->row_array();
			}else{
				return $QuerySaya->result();
			}
		}

		public function show_kecamatan($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT max(tahun_tgt) FROM tb_targetuser WHERE kdfull_tgt=kd_full) as tahunbaru',
				'(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full AND tahun_tgt=tahunbaru) as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_pal) FROM tb_petaanalog WHERE idkel_pal = kd_full) as jml_analog',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE petonline_blk!="" AND idkel_blk = kd_full AND status_blk <> 0) as jml_peta',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND publish_ptsl <> 0) AS jml_ptsl',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
			);

			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kd_full =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}

			}

			return $QuerySaya->result();
		}

		public function show_gtra($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT max(tahun_tgt) FROM tb_targetuser WHERE kdfull_tgt=kd_full) as tahunbaru',
				'(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full AND tahun_tgt=tahunbaru) as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(id_dsn) FROM ms_dusun WHERE kdkel_dsn = kd_full) as jml_dsn',
				'(SELECT COUNT(id_gtra) FROM tb_gtra,ms_dusun WHERE iddsn_gtra=id_dsn AND kdkel_dsn = kd_full) as jml_oby',
				'(SELECT COUNT(DISTINCT idpdk_gtra) FROM tb_gtra,ms_dusun WHERE iddsn_gtra=id_dsn AND kdkel_dsn = kd_full) as jml_sby',
				'(SELECT COUNT(DISTINCT nokk_pdk) FROM tb_gtra,ms_dusun,tb_penduduk WHERE idpdk_gtra=idpdk_pdk AND iddsn_gtra=id_dsn AND kdkel_dsn = kd_full AND idpdk_gtra!=111) as jml_kksby',
				'(SELECT COUNT(id_gtra) FROM tb_gtra,ms_dusun WHERE iddsn_gtra=id_dsn AND kdkel_dsn = kd_full AND idpdk_gtra=111) as jml_lain',
			);

			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['idusr_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kd_full =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}

			}

			return $QuerySaya->result();
		}

		public function show_tataruang($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'idpet_pt',
				'pettr_pt',
				'pettronline_pt',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar'
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
				if($user['idusr_usr']!=1){
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
				if($user['level_usr']!=1){
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

		public function show_dataluas($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT max(tahun_tgt) FROM tb_targetuser WHERE kdfull_tgt=kd_full) as tahunbaru',
				'(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full AND tahun_tgt=tahunbaru) as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_pal) FROM tb_petaanalog WHERE idkel_pal = kd_full) as jml_analog',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE petonline_blk!="" AND idkel_blk = kd_full AND status_blk <> 0) as jml_peta',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(id_studio_1_1) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_blk=idblk_ptsl AND luasfisik_ptsl>0 AND status_blk <> 0 AND publish_ptsl <> 0) as jmlluas',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",

			);

			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['idusr_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kd_full =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}

			}

			return $QuerySaya->result();
		}

		public function show_dataluasnib($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT max(tahun_tgt) FROM tb_targetuser WHERE kdfull_tgt=kd_full) as tahunbaru',
				'(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full AND tahun_tgt=tahunbaru) as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_pal) FROM tb_petaanalog WHERE idkel_pal = kd_full) as jml_analog',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE petonline_blk!="" AND idkel_blk = kd_full AND status_blk <> 0) as jml_peta',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(id_studio_1_1) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_blk=idblk_ptsl AND nib_ptsl!="" AND noberkas_ptsl!="" AND luasfisik_ptsl>0 AND status_blk <> 0 AND publish_ptsl <> 0) as jmlluas',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
			);

			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['idusr_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kd_full =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}

			}

			return $QuerySaya->result();
		}

		public function show_dataluasshat($number,$offset,$cari = NULL,$carikelurahan=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'(SELECT max(tahun_tgt) FROM tb_targetuser WHERE kdfull_tgt=kd_full) as tahunbaru',
				'(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full AND tahun_tgt=tahunbaru) as target',
				'(SELECT target_tgt FROM tb_targetshat WHERE kdfull_tgt = kd_full group by kdfull_tgt) as targetshat',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE petonline_blk!="" AND idkel_blk = kd_full AND status_blk <> 0) as jml_peta',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(id_studio_1_1) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_blk=idblk_ptsl AND luasfisik_ptsl>0 AND noberkas_ptsl!="" AND nib_ptsl!="" AND status_blk <> 0 AND publish_ptsl <> 0) as jmlluas',
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_blk=idblk_ptsl AND luasfisik_ptsl>0 AND noberkas_ptsl!="" AND noberkasyrd_ptsl!="" AND klaster_ptsl!="" AND nib_ptsl!="" AND status_blk <> 0) as jmlyuridis',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",

			);

			if(isset($carikelurahan) && $carikelurahan!='0' && $cari!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kdkec_kel =',$cari)
					->where('kd_kel =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else if(isset($cari) && $cari!='0'){
				if($user['idusr_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].') AND kdkec_kel='.$cari.'')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kdkec_kel =',$cari)
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}
			}else if(isset($cari) && $carikelurahan!='0'){
				$this->db
					->select($select)
					->from('ms_kelurahan')
					->where('kd_full =',$carikelurahan)
					->order_by('kd_full','ASC')
					->limit($number,$offset);
				$QuerySaya = $this->db->get();
			}else{
				if($user['level_usr']!=1){
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->where('kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel='.$user['idusr_usr'].')')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}else{
					$this->db
						->select($select)
						->from('ms_kelurahan')
						->order_by('kd_full','ASC')
						->limit($number,$offset);
					$QuerySaya = $this->db->get();
				}

			}

			return $QuerySaya->result();
		}

		public function get_rank($number,$offset,$cari = NULL,$carikelurahan=NULL,$tahun=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'target_tgt as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
				"(((SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0)/(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ))*100) as prosentasek4",
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND publish_ptsl <> 0) AS total',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentase',
			);
			$this->db
				->select($select)
				->from('ms_kelurahan')
				->join('tb_targetuser','kdfull_tgt=kd_full')
				->where('tahun_tgt =',$tahun)
				->order_by('(prosentase+prosentasek4)/2','DESC')
				->group_by('kd_full')
				->limit($number,$offset);
			$QuerySaya = $this->db->get();

			return $QuerySaya->result();
		}

		public function get_rank2($number,$offset,$cari = NULL,$carikelurahan=NULL,$tahun=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'target_tgt as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
				"(((SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0)/(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ))*100) as prosentasek4",
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND luasfisik_ptsl>0 AND publish_ptsl <> 0) AS total',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND luasfisik_ptsl > 0 AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentase',
			);
			$this->db
				->select($select)
				->from('ms_kelurahan')
				->join('tb_targetuser','kdfull_tgt=kd_full')
				->where('tahun_tgt =',$tahun)
				->order_by('(prosentase+prosentasek4)/2','DESC')
				->group_by('kd_full')
				->limit($number,$offset);
			$QuerySaya = $this->db->get();

			return $QuerySaya->result();
		}

		public function get_rankfull($number,$offset,$cari = NULL,$carikelurahan=NULL,$tahun=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'target_tgt as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
				"(((SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0)/(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ))*100) as prosentasek4",

				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND publish_ptsl <> 0) AS totalpanitia',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentasepanitia',

				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND luasfisik_ptsl>0 AND publish_ptsl <> 0) AS totalpengukuran',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND luasfisik_ptsl > 0 AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentasepengukuran',

				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND publish_ptsl <> 0) AS totalpemetaan',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentasepemetaan',

				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND noberkasyrd_ptsl!="" AND klaster_ptsl!="" AND publish_ptsl <> 0) AS totalyuridis',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND noberkasyrd_ptsl!="" AND klaster_ptsl!="" AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentaseyuridis',
			);
			$this->db
				->select($select)
				->from('ms_kelurahan')
				->join('tb_targetuser','kdfull_tgt=kd_full')
				->where('tahun_tgt =',$tahun)
				->order_by('(prosentasepengukuran+prosentasepanitia+prosentasepemetaan+prosentaseyuridis+prosentasek4)/5','DESC')
				->group_by('kd_full')
				->limit($number,$offset);
			$QuerySaya = $this->db->get();

			return $QuerySaya->result();
		}

		public function get_rank3($number,$offset,$cari = NULL,$carikelurahan=NULL,$tahun=NULL)
		{
			$user = $this->auth_model->get_userdata();
			$select = array(
				'nma_kel',
				'kd_full',
				'target_tgt as target',
				'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec',
				'(SELECT COUNT(idblk_blk) FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0) as jml_blk',
				'(SELECT COUNT(id_dhkp) FROM tb_dhkp WHERE idblk_dhkp IN (select idblk_blk FROM tb_block WHERE idkel_blk = kd_full AND status_blk <> 0)) as jml_dhkp',
				'(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ) AS jml_terdaftar',
				"(SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0) AS jml_sudahhak",
				"(((SELECT COUNT(DISTINCT(nohak_nub)) FROM tb_nub WHERE SUBSTR(REPLACE(nohak_nub,'.',''),1,8) = kd_full AND publish_nub <> 0)/(SELECT COUNT(no_hak) FROM tb_hak WHERE id_kelurahan = kd_full AND status_hak <> 0 ))*100) as prosentasek4",
				'(SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND publish_ptsl <> 0) AS total',
				'((((SELECT COUNT(id_ptsl) FROM tb_ptsl,tb_block WHERE idkel_blk = kd_full AND idblk_ptsl = idblk_blk AND nib_ptsl!="" AND noberkas_ptsl!="" AND publish_ptsl <> 0))/(SELECT target_tgt FROM tb_targetuser WHERE kdfull_tgt = kd_full group by kdfull_tgt))*100) as prosentase',
			);
			$this->db
				->select($select)
				->from('ms_kelurahan')
				->join('tb_targetuser','kdfull_tgt=kd_full')
				->where('tahun_tgt =',$tahun)
				->order_by('(prosentase+prosentasek4)/2','DESC')
				->group_by('kd_full')
				->limit($number,$offset);
			$QuerySaya = $this->db->get();

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
