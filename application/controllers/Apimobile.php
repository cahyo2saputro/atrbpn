<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* ------- DONE --------*/
class Apimobile extends CI_Controller {
    var $userdata = NULL;

    public function __construct (){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // CEK API KEY SIK CUK

    }

    public function checklogin() {
      $username = $this->input->post("username");
      $password = $this->input->post("password");
      $token = $this->input->post("token_fcm");
      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);

        return;

      }

      $dat['table'] 	= "ms_users";
      $dat['type'] 	  = "single";
      $dat['condition']['usrid_usr']	= $username;
      $dat['condition']['pasid_usr']	= enkripsi_pass($password);
      $dat['condition']['level_usr']	= 4;
      $dat['condition']['status_usr']	= 1;
      $dat['column']	                = "idusr_usr as alias,name_usr as nama,nip_usr as nip";
      $user = $this->crud_model->get_data($dat);

      if($user){
        $response['success'] = true;
        $response['message'] = "Data User ditemukan";
        $response['result'] = $user;

        //UPDATE FCM TOKEN
        $simpan = $this->crud_model->update('ms_users',array('fcmtoken_usr'=>$token),array('idusr_usr'=>$user['alias']));

      }else{
        $response['success'] = false;
        $response['message'] = "Data User tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);


    }

    public function changepass() {
      $idusr = $this->input->post("alias");
      $passlama = $this->input->post("oldpass");
      $passbaru = $this->input->post("newpass");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "ms_users";
      $dat['type'] 	  = "single";
      $dat['condition']['idusr_usr']	= $idusr;
      $dat['condition']['pasid_usr']	= enkripsi_pass($passlama);
      $dat['condition']['level_usr']	= 4;
      $dat['condition']['status_usr']	= 1;
      $dat['column']	                = "idusr_usr as alias,name_usr as nama,nip_usr as nip";
      $user = $this->crud_model->get_data($dat);

      if($user){
        $response['success'] = true;
        $response['message'] = "Password berhasil diganti";
        $response['result'] = $user;

        //UPDATE FCM TOKEN
        $simpan = $this->crud_model->update('ms_users',array('pasid_usr'=>enkripsi_pass($passbaru)),array('idusr_usr'=>$idusr));

      }else{
        $response['success'] = false;
        $response['message'] = "Password Gagal diubah";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function updatetoken() {
      $alias = $this->input->post("alias");
      $token = $this->input->post("token_fcm");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "ms_users";
      $dat['type'] 	  = "single";
      $dat['condition']['idusr_usr']	= $alias;
      $dat['column']	                = "idusr_usr as alias,name_usr as nama,nip_usr as nip";
      $user = $this->crud_model->get_data($dat);

      if($user){
        $response['success'] = true;
        $response['message'] = "Data User ditemukan";
        $response['result'] = $user;

        //UPDATE FCM TOKEN
        $simpan = $this->crud_model->update('ms_users',array('fcmtoken_usr'=>$token),array('idusr_usr'=>$user['alias']));

      }else{
        $response['success'] = false;
        $response['message'] = "Data User tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function checkhak() {
      $alias    = $this->input->post("alias");
      $nomorhak = $this->input->post("nomorhak");
      // $foto     = $this->input->post("berkas");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      // CEK NOMOR
      $dat['table'] 	= "tb_hak";
      $dat['type'] 	  = "single";
      $dat['column'] 	= "jenis_kw_awal,buku_tanah,entry_su_tekstual,su_spasial,bidang_tanah";
      $dat['condition']['no_hak']	= $nomorhak;
      $nohak = $this->crud_model->get_data($dat);

      if($nohak && (strtolower($nohak['jenis_kw_awal'])=='kw1') || ($nohak['bidang_tanah']=='1' && $nohak['entry_su_tekstual']=='1' && $nohak['su_spasial']=='1' && $nohak['bidang_tanah']=='1')){
        // KIRIM STATUS HAK SUDAH KW1
        $status=2;

      }else if($nohak){
        // MENUNGGU KONFIRMASI ADMIN
        $status=1;
      }else{
        // MENUNGGU KONFIRMASI ADMIN
        $status=0;
      }

      $check['table'] 	= "tb_hakppat";
      $check['type'] 	  = "single";
      $check['condition']['nohak_hpat']	= $nomorhak;
      $nohakcheck = $this->crud_model->get_data($check);

      if(!$nohakcheck){
        $ar = array(
          'nohak_hpat' => $nomorhak,
          'idppat_hpat' => $alias,
          'status_hpat' => $status,
          'create_at' => date('Y-m-d H:i:s')
        );
        $simpan = $this->crud_model->input('tb_hakppat',$ar);
        $insert_id = $this->db->insert_id();

        // $count = count($_FILES['berkas']['name']);
        $count = $this->input->post("size");
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['berkas'.$i]['name'];
	          $_FILES['file']['type'] = $_FILES['berkas'.$i]['type'];
	          $_FILES['file']['tmp_name'] = $_FILES['berkas'.$i]['tmp_name'];
	          $_FILES['file']['error'] = $_FILES['berkas'.$i]['error'];
	          $_FILES['file']['size'] = $_FILES['berkas'.$i]['size'];

						$file = explode(".",$_FILES["berkas".$i]["name"]);
		        $sum = count($file);
						$nmfile1 					= "PPAT_N_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './HAKPPAT/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data					= $this->upload->data();
						$nama_upload 		= $data['file_name'];

						if($data){
              $foto = array(
                'idhpat_img' => $insert_id,
                'image_img'  => $data['file_name'],
                'create_at' => date('Y-m-d H:i:s')
              );
              $simpan2 = $this->crud_model->input('tb_imghakppat',$foto);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas','e Yuridis-'.$block['nma_kel'].'<br>'.$block['nama_blk'].'-'.$insert_id,"Add Berkas dengan rincian ".displayArray($ar));
						}
				}

        // $file = explode(".",$_FILES["foto"]["name"]);
        // $sum = count($file);
        // $nmfile 					= time()."".$nomorhak."_PPAT_".$alias.'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
        // $config['file_name'] 		= $nmfile;
        // $config['upload_path']		= './HAKPPAT/';
        // $config['allowed_types']	= '*';
        // $this->upload->initialize($config);
        //
        // $upload 				  = $this->upload->do_upload('foto');
        // $data					    = $this->upload->data();



        // $foto = array(
        //   'idhpat_img' => $insert_id,
        //   'image_img'  => $data['file_name'],
        //   'create_at' => date('Y-m-d H:i:s')
        // );
        // $simpan = $this->crud_model->input('tb_imghakppat',$foto);
      }

      if($simpan2){
        $response['success'] = true;
        $response['message'] = "Berhasil Input Data";
        $response['result']  = $insert_id;
      }else{
        $response['success'] = false;
        $response['message'] = "Data NO Hak gagal diinput / duplikat";
        $response['result']  = NULL;
      }
      echo json_encode($response);

    }

    public function inputimghak() {
      $idhpat = $this->input->post("idhak");
      $foto     = $this->input->post("foto");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $file = explode(".",$_FILES["foto"]["name"]);
      $sum = count($file);
      $nmfile 					= time()."".$nomorhak."_PPAT_".$alias.'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
      $config['file_name'] 		= $nmfile;
      $config['upload_path']		= './HAKPPAT/';
      $config['allowed_types']	= '*';
      $this->upload->initialize($config);

      $upload 				  = $this->upload->do_upload('foto');
      $data					    = $this->upload->data();

      $ar = array(
        'idhpat_img' => $idhpat,
        'image_img'  => $data['file_name'],
        'create_at' => date('Y-m-d H:i:s')
      );

      $simpan = $this->crud_model->input('tb_imghakppat',$ar);

      if($simpan){
        $response['success'] = true;
        $response['message'] = "Berhasil Input Data";
      }else{
        $response['success'] = false;
        $response['message'] = "Data Gambar gagal diinput / duplikat";
      }
      echo json_encode($response);

    }

    public function deliverhak($id) {
      $this->load->view('auth/authorized');
      $ar = array(
        'status_hpat' => '2'
      );
      $simpan = $this->crud_model->update('tb_hakppat',$ar,array('id_hpat'=>$id));

      $check['table'] 	= "tb_hakppat";
      $check['type'] 	  = "single";
      $check['join']['table'] = "ms_users";
      $check['join']['key'] = "idusr_usr";
      $check['join']['ref'] = "idppat_hpat";
      $check['condition']['id_hpat']	= $id;
      $nohak = $this->crud_model->get_data($check);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );


      //$registrationIds = array('fFcZd9GS-Po:APA91bH9PzaBARxK8R2Nfc1FWvQrcaMeMX14I9qWjGrYR5SeQq-DAgvHEmu6O6d_R2HM511CSoRWMrVw8oNdMxAw5IpPcH5HcOILDIfQUPywKs_AoPF-4QU_i8e8Jmlz1DfGOQfv36Xd');
      //$registrationIds = array('cvme27YXuWk:APA91bEsdU7wzDpp7a35rnNlR49NtFNeSpk6IyJhEpGF5FWaY9wXBrpIYFv0vm_WykzZSQQYFIKygjfda4Fji_TEe50_OKGrbcqy7fg6A2Yvvu0_O39WDAb657Vn6ReQ0FG_EeaLFZRf'); //token tiap hp
      // $tujuan = $_POST['username']; //isi dengan username
      // $judul = $_POST['judul']; //judul yg di tampilkan di notif
      // $pesan = $_POST['pesan']; //pesan lengkap..
      // $kode_notif = $_POST['kode_notif']; //
      $registrationIds = array($nohak['fcmtoken_usr']);

      // prep the bundle
      $msg = array
      (
      	'message' 	=> $nohak['nohak_hpat']." Sudah KW1, silakan menunggu penyiapan berkas dari admin",//$pesan,
      	'title'		=> "Pemberitahuan ".$nohak['nohak_hpat'] , //$judul,
      	'kode_notif' => 1, //$kode_notif,
        'status' => $nohak['status_hpat'],
      	'id_hak' => $nohak['nohak_hpat']

      );
      $fields = array
      (
      	'registration_ids' 	=> $registrationIds,
      	//'to' => "/topics/" . $tujuan,
      	'data'			=> $msg
      );

      $headers = array
      (
      	'Authorization: key=' . API_ACCESS_KEY,
      	'Content-Type: application/json'
      );

      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
      echo $result;

      ?>
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Studioppat">
      <?php

    }

    public function confirmhak($id) {
      $this->load->view('auth/authorized');
      $ar = array(
        'status_hpat' => '2'
      );
      $simpan = $this->crud_model->update('tb_hakppat',$ar,array('id_hpat'=>$id));

      $check['table'] 	= "tb_hakppat";
      $check['type'] 	  = "single";
      $check['join']['table'] = "ms_users";
      $check['join']['key'] = "idusr_usr";
      $check['join']['ref'] = "idppat_hpat";
      $check['condition']['id_hpat']	= $id;
      $nohak = $this->crud_model->get_data($check);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );


      //$registrationIds = array('fFcZd9GS-Po:APA91bH9PzaBARxK8R2Nfc1FWvQrcaMeMX14I9qWjGrYR5SeQq-DAgvHEmu6O6d_R2HM511CSoRWMrVw8oNdMxAw5IpPcH5HcOILDIfQUPywKs_AoPF-4QU_i8e8Jmlz1DfGOQfv36Xd');
      //$registrationIds = array('cvme27YXuWk:APA91bEsdU7wzDpp7a35rnNlR49NtFNeSpk6IyJhEpGF5FWaY9wXBrpIYFv0vm_WykzZSQQYFIKygjfda4Fji_TEe50_OKGrbcqy7fg6A2Yvvu0_O39WDAb657Vn6ReQ0FG_EeaLFZRf'); //token tiap hp
      // $tujuan = $_POST['username']; //isi dengan username
      // $judul = $_POST['judul']; //judul yg di tampilkan di notif
      // $pesan = $_POST['pesan']; //pesan lengkap..
      // $kode_notif = $_POST['kode_notif']; //
      $registrationIds = array($nohak['fcmtoken_usr']);

      // prep the bundle
      $msg = array
      (
      	'message' 	=> $nohak['nohak_hpat']." Berkas Fisik sudah disiapkan, silakan didaftarkan",//$pesan,
      	'title'		=> "Pemberitahuan ".$nohak['nohak_hpat'] , //$judul,
      	'kode_notif' => 1, //$kode_notif,
        'status' => $nohak['status_hpat'],
      	'id_hak' => $nohak['nohak_hpat']

      );
      $fields = array
      (
      	'registration_ids' 	=> $registrationIds,
      	//'to' => "/topics/" . $tujuan,
      	'data'			=> $msg
      );

      $headers = array
      (
      	'Authorization: key=' . API_ACCESS_KEY,
      	'Content-Type: application/json'
      );

      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
      echo $result;

      ?>
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Studioppat">
      <?php

    }

    public function warning($idppat) {
      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $check['table'] 	= "tb_warning";
      $check['type'] 	  = "single";
      $check['column']	= "COUNT(id_warn) as jumlah";
      $check['condition']['idusr_warn']	= $idppat;
      $warning = $this->crud_model->get_data($check);

      $user['table'] 	= "ms_users";
      $user['type'] 	  = "single";
      $user['condition']['idusr_usr']	= $idppat;
      $data = $this->crud_model->get_data($user);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );


      //$registrationIds = array('fFcZd9GS-Po:APA91bH9PzaBARxK8R2Nfc1FWvQrcaMeMX14I9qWjGrYR5SeQq-DAgvHEmu6O6d_R2HM511CSoRWMrVw8oNdMxAw5IpPcH5HcOILDIfQUPywKs_AoPF-4QU_i8e8Jmlz1DfGOQfv36Xd');
      //$registrationIds = array('cvme27YXuWk:APA91bEsdU7wzDpp7a35rnNlR49NtFNeSpk6IyJhEpGF5FWaY9wXBrpIYFv0vm_WykzZSQQYFIKygjfda4Fji_TEe50_OKGrbcqy7fg6A2Yvvu0_O39WDAb657Vn6ReQ0FG_EeaLFZRf'); //token tiap hp
      // $tujuan = $_POST['username']; //isi dengan username
      // $judul = $_POST['judul']; //judul yg di tampilkan di notif
      // $pesan = $_POST['pesan']; //pesan lengkap..
      // $kode_notif = $_POST['kode_notif']; //
      $registrationIds = array($data['fcmtoken_usr']);

      // prep the bundle
      $msg = array
      (
      	'message' 	=> "Anda telah menyalahi kebijakan penggunaan selama ".$warning['jumlah']." kali",//$pesan,
      	'title'		=> "Pemberitahuan Pelanggaran Kebijakan" , //$judul,
      	'kode_notif' => 2, //$kode_notif,

      );
      $fields = array
      (
      	'registration_ids' 	=> $registrationIds,
      	//'to' => "/topics/" . $tujuan,
      	'data'			=> $msg
      );

      $headers = array
      (
      	'Authorization: key=' . API_ACCESS_KEY,
      	'Content-Type: application/json'
      );

      $ch = curl_init();
      curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
      curl_setopt( $ch,CURLOPT_POST, true );
      curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
      curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
      curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
      $result = curl_exec($ch );
      curl_close( $ch );
      echo $result;

      ?>
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Studioppat/user">
      <?php

    }

    public function sumwarning() {
      $alias    = $this->input->post("alias");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "tb_warning";
      $dat['type'] 	  = "single";
      $dat['column']	= "COUNT(id_warn) as jumlah";
      $dat['condition']['idusr_warn']	= $alias;
      $warning = $this->crud_model->get_data($dat);

      if($warning){

        $response['success'] = true;
        $response['message'] = "Data Warning ditemukan";
        $response['result'] = $warning;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Warning tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function listhak() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("alias");
      if(!$this->input->post("page")){
        $page = 0;
      }else{
        $page = (($this->input->post("page")-1)*10)+1;
      }

      $dat['table'] 	= "tb_hakppat";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "id_hpat as idhak,nohak_hpat as nomorhak,status_hpat as status,
                        (SELECT nma_kec FROM ms_kecamatan WHERE kd_kec=SUBSTRING(nomorhak,7,2)) as kec,
                        (SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=SUBSTRING(nomorhak,7,2) AND kd_kel=SUBSTRING(nomorhak,10,2)) as kel
                        ";
      $dat['limit']['lim']	= 10;
      $dat['limit']['first']	= $page;
      $dat['condition']['idppat_hpat']	= $alias;
      $dat['orderby']['column'] = 'tb_hakppat.create_at';
			$dat['orderby']['sort'] = 'desc';
      $hpat = $this->crud_model->get_data($dat);

      if($hpat){

        $response['success'] = true;
        $response['message'] = "Data Hak ditemukan";
        $response['result'] = $hpat;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Hak tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function filterlist() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['apikey'] = $apikey;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("alias");

      $dat['table'] 	= "tb_hakppat";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "id_hpat as idhak,nohak_hpat as nomorhak,status_hpat as status,
                        (SELECT nma_kec FROM ms_kecamatan WHERE kd_kec=SUBSTRING(nohak_hpat,7,2)) as kec,
                        (SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=SUBSTRING(nohak_hpat,7,2) AND kd_kel=SUBSTRING(nohak_hpat,10,2)) as kel
                        ";
      if($this->input->post("idkel") && $this->input->post("idkec")){
        $dat['cuzcondition']	= "SUBSTRING(REPLACE(nohak_hpat,'.',''),5,2)='".$this->input->post("idkec")."' AND SUBSTRING(REPLACE(nohak_hpat,'.',''),7,2)='".$this->input->post("idkel")."'";
      }else if($this->input->post("idkec")){
        $dat['cuzcondition']	= "SUBSTRING(REPLACE(nomorhak,'.',''),5,2)='".$this->input->post("idkec")."'";
      }
      //$dat['condition']['idppat_hpat']	= $alias;
      $dat['orderby']['column'] = 'tb_hakppat.create_at';
			$dat['orderby']['sort'] = 'desc';
      $hpat = $this->crud_model->get_data($dat);

      if($hpat){

        $response['success'] = true;
        $response['message'] = "Data Hak ditemukan";
        $response['result'] = $hpat;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Hak tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function listcari() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("alias");

      $dat['table'] 	= "tb_hakppat";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "id_hpat as idhak,nohak_hpat as nomorhak,status_hpat as status,
                        (SELECT nma_kec FROM ms_kecamatan WHERE kd_kec=SUBSTRING(nomorhak,7,2)) as kec,
                        (SELECT nma_kel FROM ms_kelurahan WHERE kdkec_kel=SUBSTRING(nomorhak,7,2) AND kd_kel=SUBSTRING(nomorhak,10,2)) as kel
                        ";
      if($this->input->post("keyword")){
        $dat['like']['nohak_hpat']	= $this->input->post("keyword");
      }
      $dat['condition']['idppat_hpat']	= $alias;
      $hpat = $this->crud_model->get_data($dat);

      if($hpat){
        $response['success'] = true;
        $response['message'] = "Data Hak ditemukan";
        $response['result'] = $hpat;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Hak tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function detailhak() {
      $idhak    = $this->input->post("idhak");

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "tb_hakppat";
      $dat['type'] 	  = "single";
      $dat['column']	= "id_hpat as idhak,foto_hpat as foto,nohak_hpat as nomorhak,status_hpat as status";
      $dat['condition']['id_hpat']	= $idhak;
      $hak = $this->crud_model->get_data($dat);

      if($hak){

        $datfoto['table'] 	= "tb_imghakppat";
        $datfoto['type'] 	  = "multiple";
        $datfoto['column']	= "image_img as foto";
        $datfoto['condition']['idhpat_img']	= $idhak;
        $hak['foto'] = $this->crud_model->get_data($datfoto);

        $kode = explode(".",$hak['nomorhak']);

        $kec['table']  = "ms_kecamatan";
        $kec['type']   = "single";
        $kec['column'] = "nma_kec";
        $kec['condition']['kd_kec'] = $kode[2];
        $dkec = $this->crud_model->get_data($kec);
        $hak['kec'] = $dkec['nma_kec'];

        $kel['table']  = "ms_kelurahan";
        $kel['type']   = "single";
        $kel['column'] = "nma_kel";
        $kel['condition']['kdkec_kel'] = $kode[2];
        $kel['condition']['kd_kel'] = $kode[3];
        $dkel = $this->crud_model->get_data($kel);
        $hak['kel'] = $dkel['nma_kel'];

        if($hak['status']==2){
          $hak['status']="Tervalidasi - Silakan daftar pengecekan online";

        }else if($hak['status']==1){
          $hak['status']="Dalam Proses Validasi - Tunggu konfirmasi admin";
        }else{
          // MENUNGGU KONFIRMASI ADMIN
          $hak['status']="ID Hak belum terdaftar di System - Tunggu konfirmasi admin";
        }

        $response['success'] = true;
        $response['message'] = "Data Hak ditemukan";
        $response['result'] = $hak;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Hak tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function getkecamatan() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "ms_kecamatan";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "kd_kec as kodekec,nma_kec as namakec";
      $dat['orderby']['column'] = 'namakec';
			$dat['orderby']['sort'] = 'asc';
      $kecamatan = $this->crud_model->get_data($dat);

      if($kecamatan){
        $response['success'] = true;
        $response['message'] = "Data Kecamatan ditemukan";
        $response['result'] = $kecamatan;
      }else{
        $response['success'] = false;
        $response['message'] = "Data kecamatan tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function getkelurahan() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $idkecamatan = $this->input->post("kodekec");

      $dat['table'] 	= "ms_kelurahan";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "kd_kel as kodekel,nma_kel as namakel";
      $dat['condition']['kdkec_kel']	= $idkecamatan;
      $dat['orderby']['column'] = 'namakel';
			$dat['orderby']['sort'] = 'asc';
      $kelurahan = $this->crud_model->get_data($dat);

      if($kelurahan){
        $response['success'] = true;
        $response['message'] = "Data Kelurahan ditemukan";
        $response['result'] = $kelurahan;
      }else{
        $response['success'] = false;
        $response['message'] = "Data kelurahan tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function term() {

      $apikey = $this->input->post("apikey");
      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $dat['table'] 	= "ms_term";
      $dat['type'] 	  = "multiple";
      $dat['column']	= "desc_trm as deskripsi";
      $term = $this->crud_model->get_data($dat);

      if($term){
        $response['success'] = true;
        $response['message'] = "Data Kebijakan ditemukan";
        $response['result'] = $term;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Kebijakan tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

}
?>
