<?php
class Studio_1_1_model extends CI_Model
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
			'(SELECT COUNT(id_kelurahan) FROM tb_hak WHERE id_kelurahan = kd_full) as jml_terdaftar',
			'(SELECT SUM(file_bt) FROM tb_hak WHERE id_kelurahan = kd_full) as jml_bt',
			'(SELECT SUM(file_su) FROM tb_hak WHERE id_kelurahan = kd_full) as jml_su',
			'(SELECT nma_kec FROM ms_kecamatan WHERE kdkec_kel = kd_kec) as nma_kec'
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

	public function show_data($number,$offset,$cari = NULL,$hak = NULL,$su = NULL,$file=NULL)
	{
		$ar = array(
					'tb_hak.id_studio_1_1',
							'tb_hak.no_hak',
							'tb_hak.id_kelurahan',
							'REPLACE(tb_hak.no_hak,".","") as nohakfile',
							'tb_hak.kdhak_hak',
							'tb_hak.nib_hak',
							'tb_hak.jenis_kw_awal',
							'tb_hak.buku_tanah',
							'tb_hak.entry_su_tekstual',
							'tb_hak.su_spasial',
							'tb_hak.bidang_tanah',
							'tb_hak.status_bt',
							'tb_hak.status_su',
							'tb_hak.status_gu',
							'tb_hak.nosu_hak'
			);

			$this->db->select($ar);
			$this->db->from('tb_hak');
			$this->db->where('tb_hak.status_hak <>',0);
			if($cari!=0){
				$this->db->where('tb_hak.id_kelurahan =',$cari);
			}
			if($hak!=NULL){
				$this->db->where('tb_hak.no_hak =',$hak);
			}
			if($su!=NULL){
				$this->db->where('tb_hak.nosu_hak like','%'.$su.'%');
			}
			if($file!=''){
				if($file==1){
					$this->db->where('tb_hak.file_bt IS NULL');
				}else if($file==2){
					$this->db->where('tb_hak.file_su IS NULL');
				}

			}

			$this->db->limit($number,$offset);
			$this->db->order_by("no_hak", "asc");
			$QuerySaya = $this->db->get();

		return $QuerySaya->result();

	}

	public function show_edit_studio($kode)
	{
		$QuerySaya = $this->db->query(
			"SELECT a.id_studio_1_1,REPLACE(SUBSTR(a.no_hak,1,25),'.','') as nohakfile,a.id_kelurahan,a.no_hak,a.nib_hak,a.jenis_kw_awal,a.pma_hak,a.pmi_hak,a.kdhak_hak,b.kd_full,b.nma_kel,d.kd_kec,d.nma_kec,e.thn_su,e.no_su,e.sugs_su,e.id_su, f.nohak_bt,f.id_bt
			FROM tb_hak a
			LEFT JOIN ms_kelurahan b ON a.id_kelurahan = b.kd_full
			LEFT JOIN ms_kecamatan d ON b.kdkec_kel = d.kd_kec
			LEFT JOIN tb_su e ON a.no_hak = e.nohak_su
			LEFT JOIN tb_bt f ON a.no_hak = f.nohak_bt
			WHERE a.no_hak = '$kode' AND a.status_hak <>'0'"
		);
		return $QuerySaya->row_array();
	}

	public function data_kecamatan_add_all($kode = NULL)
	{
		$user = $this->auth_model->get_userdata();
		if(isset($kode)){
			// $sr = $this->db->query("SELECT b.kdkec_kel FROM tb_hak a INNER JOIN ms_kelurahan b ON a.id_kelurahan = b.kd_full WHERE a.no_hak = '$kode'")->row_array();
			// $set = $sr['kdkec_kel'];

			$QuerySaya = $this->db->query("SELECT nma_kec , kd_kec FROM ms_kecamatan");

			/*$sr = $this->db->query("SELECT b.nma_kel as nama_kelurahan FROM tb_hak a INNER JOIN tb_test_2 b ON a.id_kelurahan = b.id_kelurahan WHERE a.no_hak = '$kode'")->row_array();
			$set = $sr['nma_kel as nama_kelurahan'];
			$QuerySaya = $this->db->query("SELECT c.id_kecamatan,c.nama_kecamatan FROM tb_test_2 a LEFT JOIN tb_kelurahan b ON a.nma_kel as nama_kelurahan = b.nma_kel as nama_kelurahan LEFT JOIN tb_kecamatan c ON b.id_kecamatan = c.id_kecamatan WHERE a.nma_kel as nama_kelurahan = '$set'");*/
		}else{
			/*$QuerySaya = $this->db->query("SELECT nama_kecamatan, id_kecamatan FROM tb_kecamatan WHERE id_kabkota = '54'");*/
			if($user['level_usr']!=1){
				$QuerySaya = $this->db->query("SELECT nma_kec , kd_kec FROM ms_kecamatan WHERE kd_kec IN (SELECT kdkec_kel from ms_kelurahan where kd_full IN (SELECT idkel_kel FROM tb_userkel WHERE idusr_kel=".$user['idusr_usr']."))");
			}else{
				$QuerySaya = $this->db->query("SELECT nma_kec , kd_kec FROM ms_kecamatan");
			}
		}
		return $QuerySaya->result();
	}

	public function data_kelurahan_add_all($kode = NULL)
	{
		if(isset($kode)){
			$QuerySaya = $this->db->query("SELECT b.kd_full,b.nma_kel FROM tb_hak a INNER JOIN ms_kelurahan b ON a.id_kelurahan = b.kd_full WHERE a.no_hak = '$kode'");
		}else{
			$QuerySaya = $this->db->query("SELECT kd_full, nma_kel FROM ms_kelurahan");
		}
		return $QuerySaya->result();
	}

	public function simpan_data_studio($value)
	{
		$QuerySaya = $this->db->insert('tb_hak',$value);
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function edit_data_studio($id,$value)
	{
		$this->db->where('id_studio_1_1',$id);
		$QuerySaya = $this->db->update('tb_hak',$value);
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function hapus_data_studio($id_studio_1_1)
	{


		$cari_bt = $this->db->query("SELECT b.id_bt FROM tb_hak a INNER JOIN tb_bt b ON a.no_hak = b.nohak_bt WHERE b.nohak_bt = '$id_studio_1_1'")->row_array();

		$id_bt 	 = $cari_bt['id_bt'];

		$this->db->query("UPDATE tb_bt SET status_bt = '0' WHERE id_bt = '$id_bt'");

		$cari_su = $this->db->query("SELECT b.no_su,b.id_su FROM tb_hak a INNER JOIN tb_su b ON a.no_hak = b.nohak_su WHERE b.nohak_su = '$id_studio_1_1'")->row_array();

		$id_su 	 = $cari_su['id_su'];
		$no_su   = $cari_su['no_su'];

		$this->db->query("UPDATE tb_su SET status_su = '0' WHERE id_su = '$id_su'");

		$cari_gu = $this->db->query("SELECT b.id_gu FROM tb_su a INNER JOIN tb_gu b ON a.no_su = b.nosu1_gu WHERE b.nosu1_gu = '$no_su'")->row_array();

		$id_gu 	 = $cari_gu['id_gu'];

		$this->db->query("UPDATE tb_gu SET status_gu = '0' WHERE id_gu = '$id_gu'");

		$this->db->where('no_hak',$id_studio_1_1);

		$value_studio = array(
			'status_hak' => '0',
			'status_bt'	 => '0',
			'status_su'	 => '0'
		);

		$QuerySaya = $this->db->update('tb_hak',$value_studio);
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function cari_data_desa($id)
	{

		$QuerySaya = $this->db->query("SELECT * FROM ms_kelurahan WHERE kdkec_kel = '$id'");
		return $QuerySaya->result();
	}

	public function count_bt($id)
	{
		$QuerySaya = $this->db->query("SELECT a.id_studio_1_1,b.id_bt,b.nohak_bt FROM tb_bt b LEFT JOIN tb_hak a ON b.nohak_bt = a.no_hak WHERE b.nohak_bt = '$id'");
		return $QuerySaya->num_rows();
	}

	public function count_su($id)
	{
		$QuerySaya = $this->db->query("SELECT a.id_studio_1_1,b.id_su,b.nohak_su FROM tb_su b LEFT JOIN tb_hak a ON b.nohak_su = a.no_hak WHERE b.nohak_su = '$id' ");
		return $QuerySaya->num_rows();
	}

	public function count_gu($id)
	{
		$QuerySaya =  $this->db->query("SELECT a.id_su,a.nohak_su,b.nosu1_gu,b.id_gu FROM tb_gu b LEFT JOIN tb_su a ON b.nosu1_gu = a.nohak_su WHERE b.nosu1_gu = '$id'");
		return $QuerySaya->num_rows();
	}

	public function sr_kel_kec($id_kelurahan)
	{
		$QuerySaya = $this->db->query("SELECT a.kd_kel as id_kelurahan_hak, a.kdkec_kel as id_kecamatan_hak, UPPER(a.nma_kel) as nma_kel, UPPER(b.nma_kec) as nma_kec FROM ms_kelurahan a LEFT JOIN ms_kecamatan b ON a.kdkec_kel = b.kd_kec WHERE a.kd_full = '$id_kelurahan'");
		return $QuerySaya->row_array();
	}

	public function update_buku_tanah($kode,$value)
	{
		if($value=="1"){
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET buku_tanah='1'
							  WHERE id_studio_1_1='$kode';"
							);
		}else{
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET buku_tanah='0'
							  WHERE id_studio_1_1='$kode';"
							);
		}
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function update_entry_su($kode,$value)
	{
		if($value=="1"){
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET entry_su_tekstual='1'
							  WHERE id_studio_1_1='$kode';"
							);
		}else{
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET entry_su_tekstual='0'
							  WHERE id_studio_1_1='$kode';"
							);
		}
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function update_su_spasial($kode,$value)
	{
		if($value=="1"){
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET su_spasial='1'
							  WHERE id_studio_1_1='$kode';"
							);
		}else{
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET su_spasial='0'
							  WHERE id_studio_1_1='$kode';"
							);
		}
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}

	public function update_bidang_tanah($kode,$value)
	{
		if($value=="1"){
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET bidang_tanah='1'
							  WHERE id_studio_1_1='$kode';"
							);
		}else{
			$QuerySaya 		= $this->db->query(
							  "UPDATE tb_hak
							  SET bidang_tanah='0'
							  WHERE id_studio_1_1='$kode';"
							);
		}
		if($QuerySaya){
			return TRUE;
		}else{
			return FALSE;
		};
	}


}
?>
