<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* ------- DONE --------*/
class Apimobilepublic extends CI_Controller {
    var $userdata = NULL;

    public function __construct (){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        // CEK API KEY SIK CUK

    }

    public function checklogin() {
      $username = $this->input->post("nik");
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

      $dat['table'] 	= "tb_register";
      $dat['type'] 	  = "single";
      $dat['condition']['nik_reg']	= $username;
      $dat['condition']['pass_reg']	= enkripsi_pass($password);
      $dat['condition']['publish_reg']	= 1;
      $dat['column']	                = "id_reg as alias,nma_reg as nama,nik_reg as nip, typeusr_reg as role,kdfull_reg as kelurahan,idusr_reg as userreg";
      $user = $this->crud_model->get_data($dat);

      if($user && $user['userreg']==$user['alias']){
        $response['success'] = true;
        $response['message'] = "Data User ditemukan, sudah divalidasi";
        $response['result'] = $user;

        //UPDATE FCM TOKEN
        $simpan = $this->crud_model->update('tb_register',array('fcmtoken_reg'=>$token),array('id_reg'=>$user['alias']));

      }else if($user && $user['userreg']!=$user['alias']){
        $response['success'] = false;
        $response['message'] = "Data User ditemukan, belum divalidasi";
        $response['result'] = NULL;
      }else{
        $response['success'] = false;
        $response['message'] = "Data User tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function guestregis() {
      $nik = $this->input->post("nik");
      $nama = $this->input->post("nama");
      $alamat = $this->input->post("alamat");
      $nohp = $this->input->post("nohp");
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

      $ktp = '';
      if($_FILES["foto"]){
          $file = explode(".",$_FILES["foto"]["name"]);
          $sum = count($file);
          $ktp 					= time()."_".$nik.'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
          $config['file_name'] 		= $ktp;
          $config['upload_path']		= './Penduduk/';
          $config['allowed_types']	= '*';
          $this->upload->initialize($config);
          $upload 				  = $this->upload->do_upload('foto');
      }

      $data = array(
            'nik_reg'  => $nik,
            'nma_reg'  => $nama,
            'nohp_reg'  => $nohp,
            'alamat_reg'  => $alamat,
            'ktp_reg'  => $ktp,
            'fcmtoken_reg'  => $token,
            'pass_reg'  => enkripsi_pass($password),
            'idusr_reg'  => '0',
            'typeusr_reg'  => '0',
            'publish_reg'  => '1',
            'create_at' => date('Y-m-d H:i:s')
          );
      $simpan = $this->crud_model->input('tb_register',$data);
      $insert_id = $this->db->insert_id();
      $this->referensi_model->save_android($insert_id,'tb_register','Registrasi Android-'.$nik.'<br>'.$nama.'-'.$insert_id,"Registrasi mobile android dengan rincian".displayArray($data));

      if($simpan){
        $response['success'] = true;
        $response['message'] = "User berhasil didaftarkan";
        $response['result'] = true;

      }else{
        $response['success'] = false;
        $response['message'] = "User gagal didaftarkan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function pengecekandesa() {
      $idpermohonan = $this->input->post("idpermohonan");
      $alias = $this->input->post("alias");
      $catatan = $this->input->post("catatan");
      $hassengketa = $this->input->post("sengketa");
      $haspatok = $this->input->post("patok");
      $longitude = $this->input->post("longitude");
      $latitude = $this->input->post("latitude");
      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $batas = '';
      if($_FILES["foto"]){
          $file = explode(".",$_FILES["foto"]["name"]);
          $sum = count($file);
          $batas 				= time()."_batas.".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
          $config['file_name'] 		= $batas;
          $config['upload_path']		= './Batas/';
          $config['allowed_types']	= '*';
          $this->upload->initialize($config);
          $upload 				  = $this->upload->do_upload('foto');
      }

      $data = array(
            'fotobatas_pmh'  => $batas,
            'koor_pmh'  => $latitude.','.$longitude,
            'ceksengketa_pmh'  => $hassengketa,
            'cektanda_pmh'  => $haspatok,
            'catatan_pmh'  => $catatan,
            'idusrdesa_pmh'  => $alias,
            'datedesa_pmh'  => date('Y-m-d H:i:s'),
            'tracking_pmh'  => '3'
          );
      $simpan = $this->crud_model->update('tb_permohonan',$data,array('id_pmh'=>$idpermohonan));

      // CEK KADES
        // CEK Sertipikat
        $ceksert['table'] = "tb_sertipikat";
        $ceksert['type'] = "single";
        $ceksert['cuzcondition'] = "id_srt=(SELECT idsrt_pmh FROM tb_permohonan WHERE id_pmh=".$idpermohonan.")";
        $sertipikat = $this->crud_model->get_data($ceksert);

      $cekkades['table'] = "tb_register";
      $cekkades['type'] = "single";
      $cekkades['cuzcondition'] = "kdfull_reg=(SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=".$sertipikat['kec_srt']." AND kd_kel=".$sertipikat['kel_srt'].")";
      $cekkades['condition']['typeusr_reg'] = 1;
      $kades = $this->crud_model->get_data($cekkades);

      // KIRIM NOTIFIKASI
      $msg = array
      (
        'title'		     => "E-BPN" ,
        'subtitle'		 => "Pengecekan desa dengan  nomor permohonan ".$sertipikat['nope_srt']." telah selesai dilakukan, menunggu review kades",
        'idpermohonan' => $idpermohonan,
        'idsertipikat' => $sertipikat['id_srt']
      );

      kirim_notifikasi('private',$kades['fcmtoken_reg'],$msg);
      kirim_notifikasi('public','admindesa_'.$kades['kdfull_reg'],$msg);

      $this->referensi_model->save_android($idpermohonan,'tb_permohonan','Update Pengecekan oleh-'.$alias.'<br>'.$alias.'-'.$idpermohonan,"Update pengecekan dengan rincian".displayArray($data));

      if($simpan){
        $response['success'] = true;
        $response['message'] = "Pengecekan telah selesai dilakukan";
        $response['result'] = true;

      }else{
        $response['success'] = false;
        $response['message'] = "Pengecekan gagal dilakukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function selesaipengukuran() {
      $idpengukuran = $this->input->post("idpengukuran");
      $alias = $this->input->post("alias");
      $longitude = $this->input->post("longitude");
      $latitude = $this->input->post("latitude");
      $apikey = $this->input->post("apikey");


      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $batas = '';
      if($_FILES["foto"]){
          $file = explode(".",$_FILES["foto"]["name"]);
          $sum = count($file);
          $batas 					= time()."_real.".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
          $config['file_name'] 		= $batas;
          $config['upload_path']		= './Real/';
          $config['allowed_types']	= '*';
          $this->upload->initialize($config);
          $upload 				  = $this->upload->do_upload('foto');
      }

      $data = array(
            'foto_png'  => $batas,
            'koor_png'  => $latitude.','.$longitude,
            'tracking_png'  => '2'
          );
      $simpan = $this->crud_model->update('tb_pengukuran',$data,array('id_png'=>$idpengukuran));

      $this->referensi_model->save_android($idpengukuran,'tb_pengukuran','Update Pengecekan oleh-'.$alias.'<br>'.$alias.'-'.$idpengukuran,"Update pengecekan dengan rincian".displayArray($data));

      if($simpan){
        $response['success'] = true;
        $response['message'] = "Pengukuran telah selesai dilakukan";
        $response['result'] = true;

      }else{
        $response['success'] = false;
        $response['message'] = "Pengukuran gagal dilakukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function listukur() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("alias");

      $dat['table'] 	= "tb_pengukuran";
      $dat['type'] 	  = "multiple";
      $dat['join']['table'] 	  = "tb_sertipikat,tb_register,tb_permohonan";
      $dat['join']['key'] 	  = "nope_srt,id_reg,idsrt_pmh";
      $dat['join']['ref'] 	  = "nope_png,idreg_srt,id_srt";
      $dat['condition']['pu_png'] = $alias;
      $dat['column'] 	= "id_png as idpengukuran,nostp_png as nomorsuratukur,
      (select nma_kel from ms_kelurahan where kd_kel=kel_srt AND kdkec_kel=kec_srt) as kel,
      (select nma_kec from ms_kecamatan where kd_kec=kec_srt) as kec,
      nma_reg as nama,
      nohp_reg as nomorhp,
      alamat_reg as alamat,
      sert_srt as sertipikat,
      status_srt as keperluan,
      koor_pmh as latlong";
      $dat['orderby']['column'] = 'tb_pengukuran.create_at';
			$dat['orderby']['sort'] = 'desc';

      if($this->input->post("nik")){
        $dat['condition']['nik_reg']	= $this->input->post("nik");
      }
      if($this->input->post("nosurat")){
        $dat['condition']['nostp_png']	= $this->input->post("nosurat");
      }
      if($this->input->post("noberkas")){
        $dat['condition']['noberkas_png']	= $this->input->post("noberkas");
      }
      if($this->input->post("nopermohonan")){
        $dat['condition']['nope_png']	= $this->input->post("nopermohonan");
      }
      if($this->input->post("tracking")){
        $dat['condition']['tracking_png']	= $this->input->post("tracking");
      }

      $pengukuran = $this->crud_model->get_data($dat);

      if($pengukuran){
        $response['success'] = true;
        $response['message'] = "Data Pengukuran ditemukan";
        $response['result'] = $pengukuran;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Pengukuran tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function detailpengukuran() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $idpengukuran    = $this->input->post("idpengukuran");

      $dat['table'] 	= "tb_pengukuran";
      $dat['type'] 	  = "single";
      $dat['join']['table'] 	  = "tb_sertipikat,tb_register,tb_permohonan";
      $dat['join']['key'] 	  = "nope_srt,id_reg,idsrt_pmh";
      $dat['join']['ref'] 	  = "nope_png,idreg_srt,id_srt";
      $dat['condition']['id_png'] = $idpengukuran;
      $dat['column'] 	= "id_png as idpengukuran,nostp_png as nomorsuratukur,
      noberkas_png as nomorberkas,nope_png as nomorpengukuran,
      stp_png as fotostp,foto_png as fotobukti,
      tglukur_png as tanggalukur,tracking_png as tracking,
      (select nma_kel from ms_kelurahan where kd_kel=kel_srt AND kdkec_kel=kec_srt) as kel,
      (select nma_kec from ms_kecamatan where kd_kec=kec_srt) as kec,
      nma_reg as nama,
      nohp_reg as nomorhp,
      alamat_reg as alamat,
      koor_pmh as latlong";
      $pengukuran = $this->crud_model->get_data($dat);

      $pengukuran['tanggalukur'] = fdate($pengukuran['tanggalukur'],'DDMMYYYY');

      if($pengukuran){
        $response['success'] = true;
        $response['message'] = "Detail Pengukuran ditemukan";
        $response['result'] = $pengukuran;
      }else{
        $response['success'] = false;
        $response['message'] = "Detail Pengukuran tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function profile() {

      $apikey = $this->input->post("apikey");
      $alias = $this->input->post("alias");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }


      $dat['table'] 	= "tb_register";
      $dat['type'] 	  = "single";
      $dat['condition']['id_reg'] = $alias;

      $user = $this->crud_model->get_data($dat);

      if($user){
        $response['success'] = true;
        $response['message'] = "Detail Pengukuran ditemukan";
        $response['result'] = $user;
      }else{
        $response['success'] = false;
        $response['message'] = "Detail Pengukuran tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function validregis($id) { // BELUM DITEST

      $this->load->view('auth/authorized');
      $user = $this->auth_model->get_userdata();

      cekkewenangan($this->uri->segment(1),$this->uri->segment(2),$user['idusr_usr'],$user['level_usr']);

      $check['table'] 	= "tb_register";
      $check['type'] 	  = "single";
      $check['condition']['id_reg']	= $id;
      $dcheck = $this->crud_model->get_data($check);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );

      $registrationIds = array($dcheck['fcmtoken_reg']);
      if($dcheck['idusr_reg']==0){
        $ar = array(
          'idusr_reg' => $id
        );

        $msg = array
        (
        	'message' 	=> $dcheck['nma_reg']." berhasil divalidasi",//$pesan,
        	'title'		=> "Pemberitahuan Registrasi".$dcheck['nik_reg'] , //$judul,
        );
      }else{
        $ar = array(
          'idusr_reg' => 0
        );

        $msg = array
        (
        	'message' 	=> $dcheck['nma_reg']." telah dinonaktifkan",//$pesan,
        	'title'		=> "Pemberitahuan Nonaktif ".$dcheck['nik_reg'] , //$judul,
        );
      }

      $simpan = $this->crud_model->update('tb_register',$ar,array('id_reg'=>$id));
      $this->referensi_model->save_android($user['idusr_usr'],'tb_register','Validasi User-'.$id.'<br>&-&',"Validasi user android dengan id".displayArray($ar));
      // prep the bundle

      $fields = array
      (
      	'registration_ids' 	=> $registrationIds,
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
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics/detail/<?= $dcheck['id_reg']?>">
      <?php
    }

    public function form1() {

      $idusr = $this->input->post("alias");
      $keperluan = $this->input->post("keperluan");
      $sertipikat = $this->input->post("sertipikat");

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $check['table'] 	= "tb_register";
      $check['join']['table'] 	= "tb_sertipikat";
      $check['join']['key'] 	= "idreg_srt";
      $check['join']['ref'] 	= "id_srt";
      $check['column'] 	= "*, (select nma_kel from ms_kelurahan where kd_kel=kel_srt AND kdkec_kel=kec_srt)as kelurahan";
      $check['type'] 	  = "single";
      $check['condition']['id_reg']	= $idusr;
      $dcheck = $this->crud_model->get_data($check);

      if($dcheck['idusr_reg']!=$idusr){
        $response['success'] = false;
        $response['message'] = "User belum divalidasi";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      if($sertipikat==0){
        $ref = $this->input->post("letterc").'-'.$this->input->post("persil").'-'.$this->input->post("klas").'-'.$this->input->post("atasnama");
      }else{
        $ref = $this->input->post("alashak").'.'.$this->input->post("nohak");
      }

      $status='';
      if($keperluan==1){
          // CEK NOMOR
          $nomorhak = '11.26.'.$this->input->post("kecamatan").'.'.$this->input->post("kelurahan").'.'.$ref;
          $dat['table'] 	= "tb_hak";
          $dat['type'] 	  = "single";
          $dat['column'] 	= "no_hak,jenis_kw_awal,buku_tanah,entry_su_tekstual,su_spasial,bidang_tanah";
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
      }

      $ar = array(
        'idreg_srt' => $idusr,
        'nope_srt' => '-',
        'kec_srt' => $this->input->post("kecamatan"),
        'kel_srt' => $this->input->post("kelurahan"),
        'status_srt' => $keperluan,
        'sert_srt' => $sertipikat,
        'tracking_srt' => $status,
        'ref_srt'=> $ref,
        'idusr_srt'=> $idusr,
        'create_at'=> date('Y-m-d H:i:s'),
        'publish_srt'=> '1'
      );

      $simpan = $this->crud_model->input('tb_sertipikat',$ar);
      $insert_id = $this->db->insert_id();
      $simpan2 = null;

      if($sertipikat==1){
        $this->referensi_model->save_android($idusr,'tb_sertipikat','Pengajuan Permohonan Sudah Sertipikat-'.$dcheck['id_reg'].'<br>'.$dcheck['kelurahan'].'-'.$dcheck['nma_reg'],"Pengajuan permohonan sudah sertipikat".displayArray($ar));
        // UPLOAD IMAGE
        $count = $this->input->post("jumlah_foto");
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['foto_sertifikat'.$i]['name'];
	          $_FILES['file']['type'] = $_FILES['foto_sertifikat'.$i]['type'];
	          $_FILES['file']['tmp_name'] = $_FILES['foto_sertifikat'.$i]['tmp_name'];
	          $_FILES['file']['error'] = $_FILES['foto_sertifikat'.$i]['error'];
	          $_FILES['file']['size'] = $_FILES['foto_sertifikat'.$i]['size'];

						$file = explode(".",$_FILES["foto_sertifikat".$i]["name"]);
		        $sum = count($file);
						$nmfile1 					= "sertipikat".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './sertipikat/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data					= $this->upload->data();
						$nama_upload 		= $data['file_name'];

						if($data){
              $foto = array(
                'idsrt_isrt' => $insert_id,
                'image_isrt'  => $data['file_name'],
                'idusr_isrt' => $idusr,
                'create_at' => date('Y-m-d H:i:s')
              );
              $simpan2 = $this->crud_model->input('img_sertipikat',$foto);
						}
				}
      }

      if($keperluan==0){
        $simpan = $this->crud_model->input('tb_permohonan',array('idsrt_pmh'=>$insert_id,'tracking_pmh'=>0,'status_pmh'=>1,'create_at'=>date('Y-m-d H:i:s')));
        $idpermohonan = $this->db->insert_id();

        // GET KELURAHAN
        $kode = 'admindesa_1126'.$this->input->post("kecamatan").''.$this->input->post("kelurahan");

        // KIRIM NOTIFIKASI
        $msg = array
        (
          'title'		     => "E-BPN",
          'subtitle'		 => "Permohonan dari user ".$dcheck['nma_reg'].", menunggu proses selanjutnya",
          'idpermohonan' => $idpermohonan,
          'idsertipikat' => $insert_id
        );

        // coba_notifikasi();
        kirim_notifikasi('public',$kode,$msg);
        $this->referensi_model->save_android($idusr,'tb_permohonan','Pengajuan Permohonan Pengukuran-'.$dcheck['id_reg'].'<br>'.$dcheck['kelurahan'].'-'.$dcheck['nma_reg'],"Pengajuan permohonan pengukuran".displayArray(array('idsrt_pmh'=>$insert_id,'tracking_pmh'=>0,'status_pmh'=>1)));
      }

      if($simpan && $simpan2){
        $response['success'] = true;
        $response['message'] = "Input Permohonan Sudah sertipikat berhasil diajukan";
        $response['result']  = true;
      }else if($simpan){
        $response['success'] = true;
        $response['message'] = "Input Permohonan Belum sertipikat berhasil diajukan";
        $response['result']  = true;
      }else{
        $response['success'] = false;
        $response['message'] = "Input Permohonan Gagal";
        $response['result']  = NULL;
      }

      echo json_encode($response);
    }

    public function form1admin() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $idusr = $this->input->post("alias");
      $keperluan = $this->input->post("keperluan");
      $sertipikat = $this->input->post("sertipikat");

      $nik = $this->input->post("nik");
      $nama = $this->input->post("nama");
      $alamat = $this->input->post("alamat");
      $nohp = $this->input->post("nohp");

      $ktp = '';
      if($_FILES["foto"]){
          $file = explode(".",$_FILES["foto"]["name"]);
          $sum = count($file);
          $ktp 					= time()."_".$nik.'.'.$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
          $config['file_name'] 		= $ktp;
          $config['upload_path']		= './Penduduk/';
          $config['allowed_types']	= '*';
          $this->upload->initialize($config);
          $upload 				  = $this->upload->do_upload('foto');
      }

      $data = array(
            'nik_reg'  => $nik,
            'nma_reg'  => $nama,
            'nohp_reg'  => $nohp,
            'alamat_reg'  => $alamat,
            'ktp_reg'  => $ktp,
            'idusr_reg'  => '0',
            'typeusr_reg'  => '0',
            'publish_reg'  => '1',
            'create_at' => date('Y-m-d H:i:s')
          );
      $simpan = $this->crud_model->input('tb_register',$data);
      $insert_id = $this->db->insert_id();
      $this->referensi_model->save_android($insert_id,'tb_register','Registrasi Android via web-'.$nik.'<br>'.$nama.'-'.$insert_id,"Registrasi mobile android via web dengan rincian".displayArray($data));

      if($sertipikat==0){
        $ref = $this->input->post("letterc").'-'.$this->input->post("persil").'-'.$this->input->post("klas").'-'.$this->input->post("atasnama");
      }else{
        $ref = $this->input->post("alashak").'.'.$this->input->post("nohak");
      }
      $status='';
      if($keperluan==1){
          // CEK NOMOR
          $nomorhak = '11.36.'.$this->input->post("kecamatan").'.'.$this->input->post("kelurahan").'.'.$ref;
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
      }

      $ar = array(
        'idreg_srt' => $idusr,
        'nope_srt' => '-',
        'kec_srt' => $this->input->post("kecamatan"),
        'kel_srt' => $this->input->post("kelurahan"),
        'status_srt' => $keperluan,
        'sert_srt' => $sertipikat,
        'ref_srt'=> $ref,
        'tracking_srt'=> $status,
        'idusr_srt'=> $idusr,
        'create_at'=> date('Y-m-d H:i:s'),
        'publish_srt'=> '1'
      );

      $simpan = $this->crud_model->input('tb_sertipikat',$ar);
      $insert_id = $this->db->insert_id();
      $simpan2 = null;

      if($sertipikat==1){
        $this->referensi_model->save_android($idusr,'tb_sertipikat','Pengajuan Permohonan Sudah Sertipikat-'.$dcheck['id_reg'].'<br>'.$dcheck['kelurahan'].'-'.$dcheck['nma_reg'],"Pengajuan permohonan sudah sertipikat".displayArray($ar));
        // UPLOAD IMAGE
        $count = $this->input->post("jumlah_foto");
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['foto_sertifikat'.$i]['name'];
	          $_FILES['file']['type'] = $_FILES['foto_sertifikat'.$i]['type'];
	          $_FILES['file']['tmp_name'] = $_FILES['foto_sertifikat'.$i]['tmp_name'];
	          $_FILES['file']['error'] = $_FILES['foto_sertifikat'.$i]['error'];
	          $_FILES['file']['size'] = $_FILES['foto_sertifikat'.$i]['size'];

						$file = explode(".",$_FILES["foto_sertifikat".$i]["name"]);
		        $sum = count($file);
						$nmfile1 					= "sertipikat".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './sertipikat/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data					= $this->upload->data();
						$nama_upload 		= $data['file_name'];

						if($data){
              $foto = array(
                'idsrt_isrt' => $insert_id,
                'image_isrt'  => $data['file_name'],
                'idusr_isrt' => $idusr,
                'create_at' => date('Y-m-d H:i:s')
              );
              $simpan2 = $this->crud_model->input('img_sertipikat',$foto);
						}
				}
      }

      if($keperluan==0){
        $simpan = $this->crud_model->input('tb_permohonan',array('idsrt_pmh'=>$insert_id,'tracking_pmh'=>0,'status_pmh'=>1));
        $this->referensi_model->save_android($idusr,'tb_permohonan','Pengajuan Permohonan Pengukuran-'.$insert_id.'<br>'.$insert_id.'-'.$insert_id,"Pengajuan permohonan pengukuran".displayArray(array('idsrt_pmh'=>$insert_id,'tracking_pmh'=>0,'status_pmh'=>1)));
      }

      if($simpan && $simpan2){
        $response['success'] = true;
        $response['message'] = "Input Permohonan Form 1, Form2 Sudah sertipikat berhasil diajukan";
        $response['result']  = true;
      }else if($simpan){
        $response['success'] = true;
        $response['message'] = "Input Permohonan Form 1, Form2 Belum sertipikat berhasil diajukan";
        $response['result']  = true;
      }else{
        $response['success'] = false;
        $response['message'] = "Input Permohonan Gagal";
        $response['result']  = NULL;
      }

      echo json_encode($response);
    }

    public function form2() {

      $idusr = $this->input->post("alias");
      $idpermohonan = $this->input->post("idpermohonan");

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $nop = '';
      if($_FILES["foto_nop"]){
          $file = explode(".",$_FILES["foto_nop"]["name"]);
          $sum = count($file);
          $nop 					= time()."_nop.".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
          $config['file_name'] 		  = $nop;
          $config['upload_path']		= './sppt/';
          $config['allowed_types']	= '*';
          $this->upload->initialize($config);
          $upload 				  = $this->upload->do_upload('foto_nop');
      }

      $ar = array(
        'utara_pmh' => $this->input->post("utara"),
        'barat_pmh' => $this->input->post("barat"),
        'timur_pmh' => $this->input->post("timur"),
        'selatan_pmh' => $this->input->post("selatan"),
        'iddhkp_pmh'=> $this->input->post("nop"),
        'imgnop_pmh'=> $nop,
        'luas_pmh'=> $this->input->post("luas"),
        'idusr_pmh'=> $idusr,
        'idusrform2_pmh'=> $idusr,
        'dateform2_pmh'=> date('Y-m-d H:i:s'),
        'tracking_pmh'=> 2,
      );

      $simpan = $this->crud_model->update('tb_permohonan',$ar,array('id_pmh'=>$idpermohonan));

      // CEK KADES
        // CEK Sertipikat
        $ceksert['table'] = "tb_sertipikat";
        $ceksert['type'] = "single";
        $ceksert['cuzcondition']= "id_srt = (SELECT idsrt_pmh FROM tb_permohonan WHERE id_pmh=".$idpermohonan.")";
        $sertipikat = $this->crud_model->get_data($ceksert);

        // CEK DATA SERTIPIKAT
        $cpmh['table'] = "tb_sertipikat";
        $cpmh['type'] = "single";
        $cpmh['column'] = "count(id_srt) as total";
        $cpmh['cuzcondition']         = "nope_srt!='-'";
        $cpmh['condition']['kel_srt'] = $sertipikat['kel_srt'];
        $cpmh['condition']['kec_srt'] = $sertipikat['kec_srt'];
        $cekpmh = $this->crud_model->get_data($cpmh);
        if($cekpmh['total']==0){
          $max=1;
        }else{
          $max=$cekpmh['total']+1;
        }
        $nomorpermohonan = '3306/'.$sertipikat['kec_srt'].'/'.$sertipikat['kel_srt'].'.'.str_pad($max, 5, '0', STR_PAD_LEFT);

      $inputsertipikat = array(
        'nope_srt'=>$nomorpermohonan,
        'create_at'=>date('Y-m-d H:i:s')
      );
      $simpansertipikat = $this->crud_model->update('tb_sertipikat',$inputsertipikat,array('id_srt'=>$sertipikat['id_srt']));

      $cekkades['table'] = "tb_register";
      $cekkades['type'] = "single";
      $cekkades['cuzcondition'] = "kdfull_reg = (SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=".$sertipikat['kec_srt']." AND kd_kel=".$sertipikat['kel_srt'].")";
      $cekkades['condition']['typeusr_reg'] = 1;
      $kades = $this->crud_model->get_data($cekkades);

      // KIRIM NOTIFIKASI
      $msg = array
      (
        'title'		     => "E-BPN",
        'subtitle'		 => "Pemberitahuan permohonan masuk nomor ".$sertipikat['nope_srt'],
        'idpermohonan' => $idpermohonan,
        'idsertipikat' => $sertipikat['id_srt']
      );

      kirim_notifikasi('private',$kades['fcmtoken_reg'],$msg);

      if($simpan){
        $response['success'] = true;
        $response['message'] = "Input Permohonan Form 2 berhasil diajukan";
        $response['result']  = true;
      }else{
        $response['success'] = false;
        $response['message'] = "Input Permohonan Gagal";
        $response['result']  = NULL;
      }

      echo json_encode($response);
    }

    public function listnop() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }


      $dat['table'] 	= "tb_dhkp";
      $dat['type'] 	  = "multiple";
      $dat['column'] 	= "id_dhkp as iddhkp,kdkec_kel as kodekec,kdpbb_kel as kodepbb,nama_blk as blok,nosppt_dhkp as sppt";
      $dat['join']['table'] 	  = "tb_block,ms_kelurahan";
      $dat['join']['key'] 	  = "idblk_blk,kd_full";
      $dat['join']['ref'] 	  = "idblk_dhkp,idkel_blk";
      $dat['condition']['kdkec_kel'] 	  = $this->input->post("kodekec");
      $dat['condition']['kd_kel'] 	= $this->input->post("kodekel");
      $dhkp = $this->crud_model->get_data($dat);

      if($dhkp){
        $response['success'] = true;
        $response['message'] = "Data NOP ditemukan";
        $response['result'] = $dhkp;
      }else{
        $response['success'] = false;
        $response['message'] = "Data NOP tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function listpermohonan() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("alias");
      // if(!$this->input->post("page")){
      //   $page = 0;
      // }else{
      //   $page = (($this->input->post("page")-1)*10)+1;
      // }

      $dat['table'] 	= "tb_sertipikat,ms_kelurahan";
      $dat['type'] 	  = "multiple";
      $dat['cuzcondition']= "kec_srt=kdkec_kel AND kel_srt=kd_kel";
      $dat['join']['table'] 	  = "tb_register,tb_permohonan";
      $dat['join']['key'] 	  = "idreg_srt,idsrt_pmh";
      $dat['join']['ref'] 	  = "id_reg,id_srt";
      $dat['column'] 	= "id_srt as idsertipikat,
      id_pmh as idpermohonan,
      nma_kel as kel,
      (select nma_kec from ms_kecamatan where kd_kec=kec_srt) as kec,
      status_srt as keperluan,
      sert_srt as sertipikat,
      nope_srt as nomor_permohonan,
      tracking_pmh as tracking,
      tb_permohonan.create_at as diajukan";

      // $dat['limit']['lim']	= 10;


      if($this->input->post("role")==1 || $this->input->post("role")==2 || $this->input->post("role")==3){
        $dat['condition']['kd_full']	= $this->input->post("kelurahan");
        $dat['condition']['status_srt']	= "0";
      }else{
        if($this->input->post("alias")){
          $dat['condition']['id_reg']	= $alias;
        }
      }

      if($this->input->post("tracking")){
        $dat['condition']['tracking_pmh']	= $this->input->post("tracking");
      }

      if($this->input->post("nik")){
        $dat['condition']['nik_reg']	= $this->input->post("nik");
      }

      // $dat['limit']['first']	= $page;
      $dat['orderby']['column'] = 'tb_sertipikat.create_at';
			$dat['orderby']['sort'] = 'desc';
      $register = $this->crud_model->get_data($dat);

      if($register){
        $response['success'] = true;
        $response['message'] = "Data Sertipikat ditemukan";
        $response['result'] = $register;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Sertipikat tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);

    }

    public function detailpermohonan() {

      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $alias    = $this->input->post("idsertipikat");

      $dat['table'] 	= "tb_sertipikat";
      $dat['type'] 	  = "single";
      $dat['column'] 	= "id_srt as idsertipikat,
      (select nma_kel from ms_kelurahan where kd_kel=kel_srt AND kdkec_kel=kec_srt) as kel,
      (select nma_kec from ms_kecamatan where kd_kec=kec_srt) as kec,
      nope_srt as nomor_permohonan,
      kel_srt as kodekel,
      kec_srt as kodekec,
      sert_srt as sertipikat,
      status_srt as keperluan,
      ref_srt as referensi,
      tracking_srt as validtracking";
      $dat['condition']['id_srt']	= $alias;

      $register = $this->crud_model->get_data($dat);

      if($register['keperluan']==0){
        // STATUS PENGUKURAN
        $permohonan['table'] 	= "tb_permohonan";
        $permohonan['type'] 	= "single";
        $permohonan['condition']['idsrt_pmh']	= $register['idsertipikat'];
        $permohonan['column']	= 'iddhkp_pmh,
        id_pmh as idpermohonan,
        nosah_pmh as nomor_sah,
        imgnop_pmh as foto_nop,
        luas_pmh as luas,
        utara_pmh as utara,
        barat_pmh as barat,
        timur_pmh as timur,
        selatan_pmh as selatan,
        fotobatas_pmh as foto_batas,
        ceksengketa_pmh as cek_sengketa,
        cektanda_pmh as cek_tanda,
        catatan_pmh as catatan,
        koor_pmh as koordinat,
        tracking_pmh as tracking,
        kuasa_pmh as kuasa';
        $datasupport = $this->crud_model->get_data($permohonan);

        $nop['table'] 	= "tb_dhkp";
        $nop['type'] 	  = "single";
        $nop['column'] 	= "kdkec_kel,kdpbb_kel,nama_blk,nosppt_dhkp";
        $nop['join']['table'] 	= "tb_block,ms_kelurahan";
        $nop['join']['key'] 	  = "idblk_dhkp,idkel_blk";
        $nop['join']['ref'] 	  = "idblk_blk,kd_full";
        $nop['condition']['id_dhkp']	= $datasupport['iddhkp_pmh'];

        $datanop = $this->crud_model->get_data($nop);
        $datasupport['nop'] = '3306'.$datanop['kdkec_kel'].'0'.$datanop['kdpbb_kel'].''.$datanop['nama_blk'].''.$datanop['nosppt_dhkp'];

        if($datasupport['kuasa']==1){
          $kuasa['table'] 	= "tb_kuasa";
          $kuasa['type'] 	  = "single";
          $kuasa['join']['table'] 	= "tb_penduduk";
          $kuasa['join']['key'] 	  = "idpdk_ksa";
          $kuasa['join']['ref'] 	  = "idpdk_pdk";
          $kuasa['condition']['idpmh_ksa']	= $register['idsertipikat'];
          $datakuasa = $this->crud_model->get_data($kuasa);

          $datasupport['namakuasa'] = $datakuasa['nma_pdk'];
          $datasupport['alamatkuasa'] = $datakuasa['almat_pdk'];
          $datasupport['nikkuasa'] = $datakuasa['noktp_pdk'];
        }else{
          $datasupport['namakuasa'] = NULL;
          $datasupport['alamatkuasa'] = NULL;
          $datasupport['nikkuasa'] = NULL;
        }



        if($register['sertipikat']==1){ // SUDAH SERTIPIKAT
          $image['table'] 	= "img_sertipikat";
          $image['type'] 	  = "multiple";
          $image['column']	= 'image_isrt as foto_sertifikat';
          $image['condition']['idsrt_isrt']	= $register['idsertipikat'];
          $datasupport['foto_sertipikat'] = $this->crud_model->get_data($image);
        }else if($register['sertipikat']==0){ // BELUM SERTIPIKAT
          $ref = explode('-',$register['referensi']);
          $datasupport['noc'] = $ref[0];
          $datasupport['persil'] = $ref[1];
          $datasupport['klas'] = $ref[2];
          $datasupport['atasnama'] = $ref[3];
        }

      }else{
        // STATUS VALIDASI KELUARIN FOTO DAN NOMOR HAK
        $image['table'] 	= "img_sertipikat";
        $image['type'] 	  = "multiple";
        $image['column']	= 'image_isrt as foto_sertifikat';
        $image['condition']['idsrt_isrt']	= $register['idsertipikat'];
        $datasupport['foto_sertipikat'] = $this->crud_model->get_data($image);
      }

      if($register){
        $response['success'] = true;
        $response['message'] = "Data Sertipikat ditemukan";
        $response['result'] = $register;
        $response['support'] = $datasupport;
      }else{
        $response['success'] = false;
        $response['message'] = "Data Sertipikat tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function ceknik(){
      $apikey = $this->input->post("apikey");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      $check['table'] 	= "tb_penduduk";
      $check['type'] 	  = "single";
      $check['condition']['noktp_pdk']	= $this->input->post("nik");
      $dcheck = $this->crud_model->get_data($check);

      if($dcheck){
        $response['success'] = true;
        $response['message'] = "Data NIK ditemukan";
        $response['result'] = $dcheck;
      }else{
        $response['success'] = false;
        $response['message'] = "Data NIK tidak ditemukan";
        $response['result'] = NULL;
      }
      echo json_encode($response);
    }

    public function changetrackstatus(){
      $apikey = $this->input->post("apikey");
      $tracking = $this->input->post("tracking");
      $role = $this->input->post("role");
      $alias = $this->input->post("alias");
      $idpermohonan = $this->input->post("idpermohonan");

      if(checkapi($apikey)==false){
        $response['success'] = false;
        $response['message'] = "Apikey tidak terdaftar";
        $response['result'] = NULL;
        echo json_encode($response);
        return;
      }

      // CEK Sertipikat
      $ceksert['table'] = "tb_sertipikat";
      $ceksert['type'] = "single";
      $ceksert['cuzcondition'] = "id_srt=(SELECT idsrt_pmh FROM tb_permohonan WHERE id_pmh=".$idpermohonan.")";
      $sertipikat = $this->crud_model->get_data($ceksert);

      if($role==1 || $role==2){

        if($tracking==5 || $tracking==4){
            // GET PRIVATE TOKEN
            $cekadmin['table'] = "tb_register";
            $cekadmin['type'] = "single";
            $cekadmin['condition']['id_reg'] = $sertipikat['idreg_srt'];
            $admin = $this->crud_model->get_data($cekadmin);
        }else{
            // GET ADMIN KELURAHAN
            $cekadmin['table'] = "tb_register";
            $cekadmin['type'] = "single";
            $cekadmin['cuzcondition'] = "kdfull_reg=(SELECT kd_full FROM ms_kelurahan WHERE kdkec_kel=".$sertipikat['kec_srt']." AND kd_kel=".$sertipikat['kel_srt'].")";
            $cekadmin['condition']['typeusr_reg'] = 3;
            $admin = $this->crud_model->get_data($cekadmin);
        }

        if($tracking==2){
          $dataupdate = array(
              'idusracc_pmh'=> $alias,
              'dateacc_pmh'=> date('Y-m-d H:i:s'),
              'tracking_pmh'=> $tracking,
          );
          // KIRIM NOTIFIKASI
          $msg = array
          (
            'title'		     => "E-BPN",
            'subtitle'		 => "Kades telah melakukan acc permohonan dengan nomor ".$sertipikat['nope_srt'].", menunggu proses selanjutnya",
            'idpermohonan' => $idpermohonan,
            'idsertipikat' => $sertipikat['id_srt']
          );
          kirim_notifikasi('public','admindesa_'.$admin['kdfull_reg'],$msg);

        }else if($tracking==5){
          $dataupdate = array(
              'idusrno_pmh'=> $alias,
              'dateno_pmh'=> date('Y-m-d H:i:s'),
              'tracking_pmh'=> $tracking,
          );

          // KIRIM NOTIFIKASI
          $msg = array
          (
            'title'		     => "E-BPN",
            'subtitle'		 => "Permohonan Pengukuran dengan nomor Permohonan ".$sertipikat['nope_srt']." telah disetujui, menunggu proses selanjutnya",
            'idpermohonan' => $idpermohonan,
            'idsertipikat' => $sertipikat['id_srt']
          );
          kirim_notifikasi('private',$admin['fcmtoken_reg'],$msg);
          publishnosah($idpermohonan);

        }else if($tracking==4){
          $dataupdate = array(
              'idusracc_pmh'=> $alias,
              'update_at'=> date('Y-m-d H:i:s'),
              'tracking_pmh'=> $tracking,
          );

          // KIRIM NOTIFIKASI
          $msg = array
          (
            'title'		     => "Nomor Permohonan ".$sertipikat['nope_srt']." ditolak",
            'subtitle'		 => "Permohonan Pengukuran dengan nomor Permohonan ".$sertipikat['nope_srt']." tidak bisa dilanjutkan karena ada kendala, silakan melakukan pengajuan ulang",
            'idpermohonan' => $idpermohonan,
            'idsertipikat' => $sertipikat['id_srt']
          );
          kirim_notifikasi('private',$admin['fcmtoken_reg'],$msg);

        }
        $simpan = $this->crud_model->update('tb_permohonan',$dataupdate,array('id_pmh'=>$idpermohonan));


        $response['success'] = true;
        $response['message'] = "Proses ACC berhasil dilakukan";

      }else{
        $response['success'] = false;
        $response['message'] = "Access denied";

      }
      echo json_encode($response);
    }

    public function deliverhak($id) {
      $this->load->view('auth/authorized');
      $ar = array(
        'tracking_srt' => '2'
      );
      $simpan = $this->crud_model->update('tb_sertipikat',$ar,array('id_srt'=>$id));

      $check['table'] 	= "tb_sertipikat";
      $check['type'] 	  = "single";
      $check['join']['table'] 	= "tb_register";
      $check['join']['key'] 	  = "id_reg";
      $check['join']['ref'] 	  = "idreg_srt";
      $check['condition']['id_srt']	= $id;
      $nohak = $this->crud_model->get_data($check);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );

      $registrationIds = array($nohak['fcmtoken_reg']);

      $nomorhak = '11.26.'.$nohak['kec_srt'].'.'.$nohak['kel_srt'].'.'.$nohak['ref_srt'];

      // prep the bundle
      $msg = array
      (
      	'subtitle' 	=> $nomorhak." Sudah KW1, silakan menunggu penyiapan berkas dari admin",//$pesan,
      	'title'		=> "Pemberitahuan ".$nomorhak , //$judul,
        'status' => $nohak['tracking_srt'],
      	'idsertipikat' => $nohak['id_srt'],
        'idpermohonan' => '',
        'idpengukuran' => ''

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
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics/validasisertipikat">
      <?php

    }

    public function confirmhak($id) {
      $this->load->view('auth/authorized');

      $check['table'] 	= "tb_sertipikat";
      $check['type'] 	  = "single";
      $check['join']['table'] 	= "tb_register";
      $check['join']['key'] 	  = "id_reg";
      $check['join']['ref'] 	  = "idreg_srt";
      $check['condition']['id_srt']	= $id;
      $nohak = $this->crud_model->get_data($check);

      // KIRIM STATUS NOTIFIKASI KE USER SUDAH KW1
      define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );

      $registrationIds = array($nohak['fcmtoken_reg']);

      $nomorhak = '11.26.'.$nohak['kec_srt'].'.'.$nohak['kel_srt'].'.'.$nohak['ref_srt'];

      // prep the bundle
      $msg = array
      (
      	'subtitle' 	=> "Berkas Fisik dengan nomor ".$nomorhak."sudah disiapkan, silakan mendaftar",//$pesan,
      	'title'		=> "Pemberitahuan ".$nomorhak , //$judul,
        'status' => $nohak['tracking_srt'],
      	'idsertipikat' => $nohak['id_srt'],
        'idpermohonan' => '',
        'idpengukuran' => ''

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
      <meta http-equiv="refresh" content="1;<?php echo base_url();?>Publics/validasisertipikat">
      <?php

    }

}
?>
