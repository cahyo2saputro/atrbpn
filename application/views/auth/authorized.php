<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($this->session->smt_member)) {
	header("location:".base_url()."auth/");
}else{
	if (!empty($this->session->userdata['view_data'])) {
		unlink('./digitalisasi/'.$this->session->userdata['view_data']['datax']);
		$this->session->unset_userdata('view_data');
	}
}
?>
