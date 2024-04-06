<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_model extends CI_Model {

	public function __construct (){
		$this->load->database();
	}

	public function get_title_page ($page=NULL){
		switch ($page) {
			case 'home':
				return 'Beranda';
				break;
			case 'kependudukan':
				return 'Product';
				break;
			case 'admin':
				return 'Administrator';
				break;
			case 'jenis':
				return 'Jenis';
				break;
			case 'supplier':
				return 'Supplier';
				break;
			case 'content':
				return 'Konten';
				break;
			default:
				return "Home";
				break;

		}
	}

	public function save_logs($iduser = NULL,$db,$refer,$aktivitas){
		$ipclient = getUserIpAddr();
		$os = getOS();
		$browser = getBrowser();
		if (strpos($refer, '-') !== false) {
			$etc = explode("-",$refer);
		  $this->db->query("INSERT INTO tb_logs VALUES('','$iduser','".$this->session->smt_member['source']."','$ipclient','$browser','$os',NOW(),'$db','$etc[0]','$etc[1]','$etc[2]','$aktivitas')");
		}else{
				$this->db->query("INSERT INTO tb_logs VALUES('','$iduser','".$this->session->smt_member['source']."','$ipclient','$browser','$os',NOW(),'$db','','','$refer','$aktivitas')");
		}

	}

	public function save_android($iduser = NULL,$db,$refer,$aktivitas){
		$ipclient = getUserIpAddr();
		$os = getOS();
		$browser = getBrowser();
		if (strpos($refer, '-') !== false) {
			$etc = explode("-",$refer);
		  $this->db->query("INSERT INTO tb_logandroid VALUES('','$iduser','$ipclient','$browser','$os',NOW(),'$db','$etc[0]','$etc[1]','$etc[2]','$aktivitas')");
		}else{
				$this->db->query("INSERT INTO tb_logandroid VALUES('','$iduser','$ipclient','$browser','$os',NOW(),'$db','','','$refer','$aktivitas')");
		}

	}
}

?>
