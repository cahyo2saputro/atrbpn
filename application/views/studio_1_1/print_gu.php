<?php 
	$id = $this->uri->segment(3);//pengambilan segment pada url
	$data = $this->db->query("SELECT * FROM tb_upgu WHERE id_gu = '$id' AND jenis_gu <> 'pdf' ORDER BY page_gu ASC")->result();
 	foreach ($data as $data) {
 	$allow	 = array('png' ,'jpg' ,'jpeg');
 	$filename = $data->doc_gu;
 	$out 		= pathinfo($filename,PATHINFO_EXTENSION);//fungsi untuk mendeteksi extensi
 	if(!in_array($out,$allow)){

 	}else{

 		$document = '<img src="' .base_url(). 'images/document/' .$data->doc_gu. '" style="width:100%;height:auto;" >';
 	}
 	
 ?>
 <p>Page <?=$data->page_gu;?></p>
 <?=$document;?>
 <?php } ?>	
 