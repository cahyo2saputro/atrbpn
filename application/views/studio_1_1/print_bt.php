<?php 
	$id = $this->uri->segment(3);//pengambilan segment pada url
	/*$data = $this->db->query("SELECT * FROM tb_upbt WHERE id_bt = '$id' AND jenis_bt <> 'pdf' ORDER BY page_bt ASC")->result();*/
	$kec = $this->db->query("SELECT nma_kel,nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan WHERE kd_full = '$id' ");
 	foreach ($data as $data) {
 	$allow	 = array('png' ,'jpg' ,'jpeg','pdf');
 	$filename = $data->doc_bt;
 	$out 		= pathinfo($filename,PATHINFO_EXTENSION);//fungsi untuk mendeteksi extensi
 	if(!in_array($out,$allow)){

 	}else{

 		$document = '<img src="' .base_url(). 'images/document/' .$data->doc_bt. '" style="width:100%;height:auto;" >';
 	}
 	
 ?>
 <p>Page <?=$data->page_bt;?></p>
 <?=$document;?>
 <?php } ?>	
 11260405101802
 11260102100001