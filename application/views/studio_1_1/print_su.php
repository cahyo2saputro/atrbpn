<?php 
	$id = $this->uri->segment(3);//pengambilan segment pada url
	$data = $this->db->query("SELECT * FROM tb_upsu WHERE id_su = '$id' AND jenis_su <> 'pdf' ORDER BY page_su ASC")->result();
 	foreach ($data as $data) {
 	$allow	 = array('png' ,'jpg' ,'jpeg');
 	$filename = $data->doc_su;
 	$out 		= pathinfo($filename,PATHINFO_EXTENSION);//fungsi untuk mendeteksi extensi
 	if(!in_array($out,$allow)){

 	}else{

 		$document = '<img src="' .base_url(). 'images/document/' .$data->doc_su. '" style="width:100%;height:auto;" >';
 	}
 	
 ?>
 <p>Page <?=$data->page_su;?></p>
 <?=$document;?>
 <?php } ?>	
 