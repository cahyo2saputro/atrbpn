<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud_model extends CI_Model {
	
	public function __construct (){
		$this->load->database();
	}
	/* 
	GET DATA
	$data['type'] = single or multiple
	$data['table'] = Berisi nama tabel
	$data['condition'] = Berisi Kondisi atau WHERE (OPTIONAL)
	$data['orderby']['column'] = Berisi kolom yang akan di order(OPTIONAL)
		$data['orderby']['sort'] = ASC atau DESC (OPTIONAL)
	$data['column'] = kolom yang akan dipanggil (OPTIONAL)
	$data['join']['table'] = TABEL JOIN (OPTIONAL)
		$data['join']['ref']   = Forgein Key 
		$data['join']['key']   = Kunci
	*/
	public function get_data($data){
		if(isset($data['condition'])){
			$jml = count($data['condition']);
			for($i=0;$i<$jml;$i++){
				$this->db->where($data['condition']);	
			}
		}
		if(isset($data['notcondition'])){
			$jml = count($data['notcondition']);
			for($i=0;$i<$jml;$i++){
				$this->db->where_not_in($data['notcondition']);	
			}
		}
		if(isset($data['conditionor'])){
			$jml = count($data['conditionor']);
			for($i=0;$i<$jml;$i++){
				$this->db->or_where($data['conditionor']);	
			}
		}
		if(isset($data['cuzcondition'])){
			$this->db->where($data['cuzcondition']);
		}
		if(isset($data['conditionlikeor'])){
			$jml = count($data['conditionlikeor']);
			for($i=0;$i<$jml;$i++){
				$this->db->or_like($data['conditionlikeor']);	
			}
		}
		if(isset($data['like'])){
			$this->db->like($data['like']);
		}
		if(isset($data['limit'])){
			if(isset($data['limit']['first'])){
				$this->db->limit($data['limit']['lim'], $data['limit']['first']);
			} else {
				$this->db->limit($data['limit']);	
			}
			
		}
		if(isset($data['orderby'])){
			$this->db->order_by($data['orderby']['column'],$data['orderby']['sort']);
		}
		if(isset($data['groupby'])){
			$this->db->group_by($data['groupby']);
		}
		if(isset($data['column'])){
			$query = $this->db->select($data['column']);
		}
		if(isset($data['join'])){
			$datjoin = explode(",", $data['join']['table']);
			$datref = explode(",", $data['join']['ref']);
			$datkey = explode(",", $data['join']['key']);
			$jml = count($datjoin);
			$ketentuan = "LEFT";
			if(isset($data['join']['ketentuan'])){
				$ketentuan = $data['join']['ketentuan'];
			}
			if($jml == 1){
				$this->db->join($datjoin[0],$datkey[0].'='.$datref[0],$ketentuan);
			} else {
				for($i=0;$i<$jml;$i++){
					$this->db->join($datjoin[$i],$datkey[$i].'='.$datref[$i],$ketentuan);
				}
			}
		}
		$query = $this->db->get($data['table']);
		
		if($data['type'] == "single"){
			return $query ->row_array();
		} else {
			return $query ->result_array();
		}
		
	}
	function buat_kode()   {    
	  $this->db->select('RIGHT(tb_transaction.idtrx_trx,2) as kode', FALSE);
	  $this->db->order_by('idtrx_trx','DESC');
	  $this->db->where('jenis_trx','O');    
	  $this->db->limit(1);     
	  $query = $this->db->get('tb_transaction');      //cek dulu apakah ada sudah ada kode di tabel.    
	  if($query->num_rows() <> 0){       
	   //jika kode ternyata sudah ada.      
	   $data = $query->row();      
	   $kode = intval($data->kode) + 1;     
	  }
	  else{       
	   //jika kode belum ada      
	   $kode = 1;     
	  }
	  $kodemax = str_pad($kode, 2, "0", STR_PAD_LEFT);    
	  /*$kodejadi = "KFM".$kodemax;*/     
	  return $kodemax;  
	 }
	 function kode_jer()   {    
	  $this->db->select('RIGHT(ms_jerigen.idjer_jer,5) as kode', FALSE);
	  $this->db->order_by('idjer_jer','DESC');
	  $this->db->limit(1);     
	  $query = $this->db->get('ms_jerigen');      //cek dulu apakah ada sudah ada kode di tabel.    
	  if($query->num_rows() <> 0){       
	   //jika kode ternyata sudah ada.      
	   $data = $query->row();      
	   $kode = intval($data->kode) + 1;     
	  }
	  else{       
	   //jika kode belum ada      
	   $kode = 1;     
	  }
	  $kodemax = str_pad($kode, 5, "0", STR_PAD_LEFT);    
	  /*$kodejadi = "KFM".$kodemax;*/     
	  return $kodemax;  
	 }
	public function input($tabel, $data){
		$input = $this->db->insert($tabel, $data);
		if($input){
			$messages = "<div id='notify' class='valid'>Data Berhasil Disimpan</div>";
		} else {
			$messages = "<div id='notify' class='error'>Data Gagal Disimpan</div>";
		}
		return $messages;
	}

	public function update($tabel, $data, $key=NULL){
		if(isset($key) && $key != ""){
			$this->db->where($key);	
		}
		$edit = $this->db->update($tabel,$data);
		if($edit){
			$messages = "<div id='notify' class='valid'>Data Berhasil Disimpan</div>";
		} else {
			$messages = "<div id='notify' class='error'>Data Gagal Disimpan</div>";
		}
		return $messages;
	}

	public function delete($tabel, $key){
		$this->db->where($key);
		$delete = $this->db->delete($tabel);
		if($delete){
			$messages = "<div id='notify' class='valid'>Data Berhasil Didelete</div>";
		} else {
			$messages = "<div id='notify' class='error'>Data Gagal Didelete</div>";
		}
		return $messages;
	}
	
} 

?>