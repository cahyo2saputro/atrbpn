<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function checkapi($apikey) {
  if($apikey=="XmxdW0198qReOvPXBrZRu2BvWzlxb1RMFXAXV"){
    return true;
  }else{
    return false;
  }
}

function setDB($type,$table){
    switch ($type) {
        case 'online':
            return "bpnung_online.".$table;
            break;
        default:
            return $table;
            break;
    }
}

function cek_berkas($file,$kec,$kel,$jenis) {

  $curl = curl_init();
  $kel = str_replace(' ','_',$kel);
  $kec = str_replace(' ','_',$kec);

  curl_setopt_array($curl, array(
    CURLOPT_URL => "http:///index.php/Apiservice/cekfile?apikey=BPNKKYMTU00711&file=$file&kec=$kec&kel=$kel&jenis=$jenis",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    // CURLOPT_POSTFIELDS =>"{   \n\t\"apikey\"    : \"BPNKKYMTU00711\",\n    \"file\"   : \"$file\",\n    \"jenis\"        : \"$jenis\"\n}",
    // CURLOPT_POSTFIELDS => array('apikey' => 'BPNKKYMTU00711','file'=>$file,'jenis'=>$jenis),
    CURLOPT_HTTPHEADER => array(
      "A: application/json",
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}

function get_berkas($type,$data,$user){

  $ci =& get_instance();
  //Load codeigniter FTP class
  $ci->load->library('ftp');

  //FTP configuration
  $ftp_config['hostname'] = '103.153.186.245';
  $ftp_config['username'] = 'bpnuploader';
  $ftp_config['password'] = 'Upload!';
  $ftp_config['port']     = 21;
  $ftp_config['debug']    = TRUE;

  //Connect to the remote server
  $ci->ftp->connect($ftp_config);

  //File path at local server
  $encript = md5(rand(0,9));
  $destination = './digitalisasi/'.$user.''.$encript.'.pdf';

  $get_file 		= $ci->db->query("SELECT UPPER(nma_kel) as nma_kel,UPPER(nma_kec) as nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan ON kd_kec = kdkec_kel WHERE kd_full = ".$data['kd_full']." ")->row_array();

  switch($type){
    case 'BT':
        //File upload path of remote server
         $source = str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/BUKU_TANAH/BT_'.$data['nohakfile'].'.pdf';
    break;
    case 'SU':
        //File upload path of remote server
         $source = str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/SURAT_UKUR/SU_'.$data['kd_full'].'_'.$data['no_su'].'_'.$data['thn_su'].'.pdf';
    break;
    case 'GS':
        //File upload path of remote server
         $source = str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/SURAT_UKUR/GS_'.$data['kd_full'].'_'.$data['no_su'].'_'.$data['thn_su'].'.pdf';
    break;
    default :
        $source ='';
    break;
  }

  $session_data = array(
    'datax' => $user.''.$encript.'.pdf'
  );
  $ci->session->set_userdata('view_data',$session_data);
  $ci->ftp->download($source, $destination,'auto');

  // Close FTP connection
  $ci->ftp->close();
}

function get_link($type,$data,$user){

  $ci =& get_instance();

  $get_file 		= $ci->db->query("SELECT UPPER(nma_kel) as nma_kel,UPPER(nma_kec) as nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan ON kd_kec = kdkec_kel WHERE kd_full = ".$data['kd_full']." ")->row_array();

  switch($type){
    case 'BT':
        //File upload path of remote server
         $source = 'http://api.e-atrbpn.net/digitalisasi/'.str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/BUKU_TANAH/BT_'.$data['nohakfile'].'.pdf';
    break;
    case 'SU':
        //File upload path of remote server
         $source = 'http://api.e-atrbpn.net/103.153.186.245:7092/digitalisasi/'.str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/SURAT_UKUR/SU_'.$data['kd_full'].'_'.$data['no_su'].'_'.$data['thn_su'].'.pdf';
    break;
    case 'GS':
        //File upload path of remote server
         $source = 'http://api.e-atrbpn.net/digitalisasi/'.str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/SURAT_UKUR/GS_'.$data['kd_full'].'_'.$data['no_su'].'_'.$data['thn_su'].'.pdf';
    break;
    default :
        $source ='';
    break;
  }

  return $source;

}

function get_file($type,$data,$user){

  $ci =& get_instance();

  $get_file 		= $ci->db->query("SELECT UPPER(nma_kel) as nma_kel,UPPER(nma_kec) as nma_kec FROM ms_kelurahan LEFT JOIN ms_kecamatan ON kd_kec = kdkec_kel WHERE kd_full = ".$data['kd_full']." ")->row_array();

  switch($type){
    case 'BT':
        //File upload path of remote server
         $source = 'http://103.153.186.245:7092/digitalisasi/'.str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/BUKU_TANAH/BT_'.$data['nohakfile'].'.pdf';
    break;
    case 'SU':
        //File upload path of remote server
         $source = 'http://103.153.186.245:7092/digitalisasi/'.str_replace(' ','_',$get_file['nma_kec']).'/'.str_replace(' ','_',$get_file['nma_kel']).'/SURAT_UKUR/SU_'.$data['kd_full'].'_'.$data['no_su'].'_'.$data['thn_su'].'.pdf';
    break;
    default :
        $source ='';
    break;
  }

  $homepage = file_get_contents($source);
  echo $homepage;

}

function getRomawi($bln){
	switch ($bln){
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
      }
}

function publishnosah($idpermohonan){
  $ci =& get_instance();
  $ci->load->model('crud_model');

  $check['table'] 	= "tb_permohonan";
  $check['type'] 	  = "single";
  $check['column'] 	= "COUNT(id_pmh) as jumlah";
  $check['cuzcondition']	= "nosah_pmh!=''";
  $cekpermohonan = $ci->crud_model->get_data($check);

  $jumlah = $cekpermohonan['jumlah']+1;
  $nosah = str_pad($jumlah, 8, '0', STR_PAD_LEFT).'/'.getRomawi(date('m')).'/'.date('Y');

  $final = $ci->crud_model->update("tb_permohonan",array('nosah_pmh'=>$nosah),array('id_pmh'=>$idpermohonan));

}

function kirim_notifikasi($sifat,$tujuan,$msg){
  // API access key from Google API's Console
  define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );

  // $msg = array
  // (
  // 	'title'		=> "Judul FCM", //$judul,
  // 	'subtitle' 	=> "JOS GANDOS",//$pesan,
  // 	'idsertipikat' => "6", //id sertipikat,
  // 	'idpermohonan'	=> "" //id permohonan
  //
  // );
  // $tujuan = 'admindesa_11260122';
  if($sifat=='public'){
    $fields = array
    (

        'to' => "/topics/".$tujuan,
    	  'data'			=> $msg,
        'direct_book_ok' => true

    );
  }else{
    $fields = array
    (
      // alert 1 'to' => 'eXQcAlw12Vg:APA91bFFq1WxI4uYHZTPRkJ4qjB0oTld1AQKKKgu9Enh9PLd6gpgd5Gt5M7W6LFbLwKEoHrXOsQovs2Zi-EpH9qwk3pfdFC8pdM6K72R-yQCRiO91qv2L79rbQSKDmnf8PBQvn1E2dp5',
      // 'to' => 'e0HXwmvOMGs:APA91bG72OdfgQf-ZGcGKSqv_B8i73SZRiFy-unV1gFM-y7dFteT_vKAl0eWtekuuZY4BGb0Ldi_FjC2WeYLZWvYaCN_BRmnjcPsUOS8oXn009yEWh4ZhSg0J0dTt_afKzDzzdQDLHjC',
    	'to' => $tujuan,
    	  'data'			=> $msg,
        'direct_book_ok' => true

    );
  }


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
  // echo $result;
}


function kirim_notifikasil($sifat,$tujuan,$msg){
  // KIRIM STATUS NOTIFIKASI
  define( 'API_ACCESS_KEY', 'AAAAiqDst40:APA91bFAyYCCxE2fv7-dXiuiBMsIzcblk_gBo7gNEXkEb8ZDGlX7T1w3AGLg53YLjQKHBTgEioAVIYDb3tDJTKpw0iJNoKJguRbXL-uq-V7Hwe0gkrHn-97YgYrWKwTkrXsU0WqjU0gc' );

  if($sifat=='private'){
    $registrationIds = $tujuan;
    $fields = array
    (
      'to' 	=> $registrationIds,
      'data'			=> $msg,
      'direct_book_ok' => true
    );
  }else if($sifat=='public'){
    $fields = array
    (
      'to' => "/topics/" . $tujuan,
      'data'			=> $msg,
      'direct_book_ok' => true
    );
  }


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

}

function typeuser($param,$jenis){
  switch($jenis){
    case 'type':
      if(isset($param) && $param==0){
        return 'Guest';
      }else if($param==1){
        return 'Kades';
      }else if($param==2){
        return 'Sekdes';
      }else if($param==3){
        return 'Admin Desa';
      }else if($param==4){
        return 'Petugas Ukur';
      }else{
        return 'undefined';
      }
      break;

    case 'valid':
      if(isset($param) && $param==0){
        return '<span style="color:#ff0000">Belum divalidasi</span>';
      }else{
        return '<span style="color:#0000ff">Sudah divalidasi</span>';
      }
      break;

    default :
      return 'not register';
  }
}

function who($param){
  $ci =& get_instance();
  $ci->load->model('crud_model');

  $dd['type'] = "single";
  $dd['table'] = "tb_register";
  $dd['condition']['id_reg'] = $param;

  $datauser = $ci->crud_model->get_data($dd);

  echo $datauser['nma_reg'];
}

function get_kelurahan_sert($id){
  $ci =& get_instance();
  $ci->load->model('crud_model');

  $reg['type'] = "single";
  $reg['table'] = "tb_sertipikat";
  $reg['condition']['id_srt']=$id;
  $datareg = $ci->crud_model->get_data($reg);

  $dd['type'] = "single";
  $dd['table'] = "ms_kelurahan";
  $dd['condition']['kdkec_kel'] = $datareg['kec_srt'];
  $dd['condition']['kd_kel'] = $datareg['kel_srt'];

  $datakel = $ci->crud_model->get_data($dd);

  return $datakel['kd_full'];
}


function status($param,$jenis){
  switch($jenis){
    case 'berkas':
      if($param==0){
        return 'Belum Sertipikat';
      }else if($param==1){
        return 'Sudah Sertipikat';
      }else{
        return 'Belum Mengajukan';
      }
    break;
    case 'gunakuasa':
      if($param==1){
        return 'Pertanian';
      }else if($param==2){
        return 'Non Pertanian';
      }else{
        return 'lain-lain';
      }
    break;
    case 'kuasacara':
      if($param==1){
        return 'Sendiri';
      }else if($param==2){
        return 'Pihak Lain';
      }else{
        return 'lain-lain';
      }
    break;
    case 'status':
      if($param==0){
        return "<span class='btn btn-primary'>Pengecekan</span>";
      }else if($param==1){
        return "<span class='btn btn-info'>Pengukuran</span>";
      }else{
        return 'Undefined';
      }
    break;
    case 'statusnikah':
      if($param==1){
        return "Belum Menikah";
      }else if($param==2){
        return "Menikah";
      }else if($param==3){
        return "Pernah Menikah";
      }else{
        return 'Undefined';
      }
    break;
    case 'domisili':
      if($param==1){
        return "Desa ini";
      }else if($param==2){
        return "Desa lain berbatasan langsung";
      }else if($param==3){
        return "Desa lain tidak berbatasan langsung";
      }else if($param==4){
        return "di luar kecamatan";
      }else if($param==5){
        return "lainnya ***";
      }else{
        return 'Undefined';
      }
    break;
    case 'kuasatanah':
      if($param==1){
        return "1 = Pemilik";
      }else if($param=='2a'){
        return "2 = Bukan Pemilik , 2a = Gadai";
      }else if($param=='2b'){
        return "2 = Bukan Pemilik , 2b = Sewa";
      }else if($param=='2c'){
        return "2 = Bukan Pemilik , 2c = Pinjam Pakai";
      }else if($param=='2d'){
        return "2 = Bukan Pemilik , 2d = Penggarap";
      }else if($param=='2e'){
        return "2 = Bukan Pemilik , 2e = Lainnya ***";
      }else if($param==3){
        return "3 = Bersama / Ulayat";
      }else if($param==4){
        return "4 = Badan Hukum";
      }else if($param==5){
        return "5 = Pemerintah";
      }else if($param==6){
        return "6 = tidak ada penguasaan tanah";
      }else{
        return 'Undefined';
      }
    break;
    case 'olehtanah':
      if($param==1){
        return "1 = Warisan";
      }else if($param==2){
        return "2 = Jual Beli";
      }else if($param==3){
        return "3 = Tukar Menukar";
      }else if($param==4){
        return "4 = Hibah";
      }else{
        return 'Undefined';
      }
    break;
    case 'miliktanah':
      if($param==1){
        return "1 = Terdaftar, sertipikat no.HM.003";
      }else if($param=='2a'){
        return "2 = Belum Terdaftar , 2a Tanah Adat;Surat No.C.01539";
      }else if($param=='2b'){
        return "2 = Belum Terdaftar , 2b Tanah Ulayat";
      }else if($param=='2c'){
        return "2 = Belum Terdaftar , 2c Tanah Negara";
      }else{
        return 'Undefined';
      }
    break;
    case 'gunabidang':
      if($param==1){
        return "1 = Pemukiman, Perkampungan";
      }else if($param==2){
        return "2 = Sawah Irigasi";
      }else if($param==3){
        return "3 = Sawah Non Irigasi";
      }else if($param==4){
        return "4 = Tegalan, Ladang";
      }else if($param==5){
        return "5 = Kebun Campuran";
      }else if($param==6){
        return "6 = Perairan Darat, tambak";
      }else if($param==7){
        return "7 = tanah terbuka, tanah kosong";
      }else if($param==8){
        return "8 = fasum, fasos";
      }else if($param==9){
        return "9 = Industri";
      }else if($param==10){
        return "10 = Peternakan";
      }else if($param==11){
        return "11 = Lainnya **";
      }else{
        return 'Undefined';
      }
    break;
    case 'agama':
      if($param==1){
        return "Islam";
      }else if($param==2){
        return "Kristen";
      }else if($param==3){
        return "Katholik";
      }else if($param==4){
        return "Budha";
      }else if($param==5){
        return "Hindu";
      }else{
        return '';
      }
    break;
    case 'manfaat1':
      if($param==1){
        return "Rumah Tinggal";
      }else{
        return '';
      }
    break;
    case 'manfaat2':
      if($param==1){
        return "Tanaman Musiman";
      }else if($param==2){
        return "Tanaman Keras";
      }else if($param==3){
        return "Lainnya";
      }else{
        return '';
      }
    break;
    case 'manfaat3':
      if($param==1){
        return "Kontrakan";
      }else if($param==2){
        return "Toko";
      }else if($param==3){
        return "Kantor";
      }else if($param==4){
        return "Gudang";
      }else if($param==5){
        return "Pabrik (Industri)";
      }else if($param==6){
        return "Lainnya,";
      }else{
        return '';
      }
    break;
    case 'manfaat4':
      if($param==1){
        return "Telekomunikasi";
      }else if($param==2){
        return "Transportasi";
      }else if($param==3){
        return "Lainnya";
      }else{
        return '';
      }
    break;
    case 'manfaat5':
      if($param==1){
        return "Sekolah";
      }else if($param==2){
        return "Masjid";
      }else if($param==3){
        return "Kantor Desa";
      }else if($param==4){
        return "Lapangan";
      }else if($param==5){
        return "Taman";
      }else if($param==6){
        return "Puskesmas";
      }else if($param==7){
        return "Lainnya,";
      }else{
        return '';
      }
    break;
    case 'manfaat6':
      if($param==1){
        return "Tidak dimanfaatkan";
      }else{
        return '';
      }
    break;
    case 'indikasi':
      if($param==1){
        return "Terindikasi terlantar";
      }else if($param==2){
        return "Tidak Terlantar";
      }else{
        return '';
      }
    break;
    case 'sengketa':
      if($param==1){
        return "Sengketa";
      }else if($param==2){
        return "Konflik";
      }else if($param==3){
        return "Berperkara di pengadilan";
      }else if($param==4){
        return "Tidak SKP";
      }else{
        return '';
      }
    break;
    case 'potensi':
      if($param==1){
        return "1 = Tanah Absente";
      }else if($param==2){
        return "2 = Tanah Kelebihan Maksimum";
      }else if($param==3){
        return "3 = Tanah Bekas Swapraja";
      }else if($param=='4a'){
        return "4 = Tanah Negara Lainnya , 4a = eks HGU no ......";
      }else if($param=='4b'){
        return "4 = Tanah Negara Lainnya , 4b = Pelepasan HGU no ......";
      }else if($param=='4c'){
        return "4 = Tanah Negara Lainnya , 4c = Tanah terlantar";
      }else if($param=='4d'){
        return "4 = Tanah Negara Lainnya , 4d = Tanah Penyelesaian SKP";
      }else if($param=='4e'){
        return "4 = Tanah Negara Lainnya , 4e = Tanah dari Pelepasan kawasan hukum";
      }else if($param=='4f'){
        return "4 = Tanah Negara Lainnya , 4f = Tanah Timbul";
      }else if($param=='4g'){
        return "4 = Tanah Negara Lainnya , 4g = Tanah Bekas tambang yang telah direklamasi";
      }else if($param=='4h'){
        return "4 = Tanah Negara Lainnya , 4h = Tanah Negara dalam penguasaan masyarakat";
      }else{
        return '';
      }
    break;
    case 'sertif':
      if($param==1){
        return "Ya";
      }else if($param==2){
        return "Tidak";
      }else{
        return '';
      }
    break;
    case 'potensiakses':
      if($param==1){
        return "Pertanian";
      }else if($param==2){
        return "Peternakan";
      }else if($param==3){
        return "Perkebunan";
      }else if($param==4){
        return "Perikanan";
      }else if($param==5){
        return "Industri Kecil";
      }else if($param==6){
        return "Lainnya,";
      }else{
        return '';
      }
    break;
    case 'statuskw':
      if($param==0){
        return "<span class='btn btn-danger'>Belum Terdaftar</span>";
      }else if($param==1){
        return "<span class='btn btn-warning'>Belum KW1</span>";
      }else if($param==2){
        return "<span class='btn btn-info'>Sudah KW1</span>";
      }else{
        return 'Undefined';
      }
    break;
    case 'ukur':
      if($param==0){
        return "Proses Pengajuan / Menunggu Pengukuran";
      }else if($param==1){
        return "Selesai Pengukuran - Menunggu Cek admin";
      }else if($param==2){
        return "Sudah dicek admin";
      }else{
        return 'Undefined';
      }
    break;
    case 'tracking':
      if($param==0){
        return "Proses Pengajuan";
      }else if($param==1){
        return "Mengisi Form 2 - Pengajuan ke Kades";
      }else if($param==2){
        return "Acc Kades - Proses Pengecekan Admin";
      }else if($param==3){
        return "Pengecekan selesai dilakukan - Menunggu persetujuan Kades";
      }else if($param==4){
        return "Permohonan bermasalah";
      }else if($param==5){
        return "Permohonan diacc, terbit Nomor Pengesahan";
      }else{
        return 'Undefined';
      }
    break;
    case 'kuasa':
      if($param==0){
        return 'tanpa kuasa';
      }else{
        return 'dengan kuasa';
      }
    break;
    case 'keperluan':
      if($param==1){
        return 'Milik (HM)';
      }else if($param==2){
        return 'Guna Usaha (HGU)';
      }else if($param==3){
        return 'Guna Bangunan (HGB)';
      }else if($param==4){
        return 'Pakai (HP)';
      }else if($param==5){
        return 'Pengelolaan (HPL)';
      }else if($param==6){
        return 'Tanggungan (HT)';
      }else if($param==7){
        return 'Tanggungan (HT)';
      }else if($param==8){
        return 'Tanggungan (HT)';
      }else{
        return 'Undefined';
      }
    break;
    case 'guna':
      if($param==1){
        return 'Pertanian';
      }else if($param==2){
        return 'Non Pertanian';
      }else{
        return 'Pertanian / Non Pertanian';
      }
    break;
    case 'hak':
      if($param==1){
        return 'Hak Milik';
      }else if($param==2){
        return 'Hak Guna Bangunan';
      }else if($param==3){
        return 'Hak Pakai';
      }else if($param==4){
        return 'Hak Wakaf';
      }else{
        return 'Pertanian / Non Pertanian';
      }
    break;
    case 'dengancara':
      if($param==1){
        return 'Jual Beli';
      }else if($param==2){
        return 'Waris';
      }else if($param==3){
        return 'Hibah';
      }else if($param==4){
        return 'Wakaf';
      }else if($param==5){
        return 'Tukar Menukar';
      }else{
        return 'Undefined';
      }
    break;
    case 'manfaat':
      if($param==1){
        return 'Perumahan';
      }else if($param==2){
        return 'Pekarangan';
      }else if($param==3){
        return 'Sawah';
      }else if($param==4){
        return 'Ladang/Tegalan';
      }else if($param==5){
        return 'Kebun/Kebun Campuran';
      }else if($param==6){
        return 'Kolam Ikan';
      }else if($param==7){
        return 'Industri';
      }else if($param==8){
        return 'Perkebunan';
      }else if($param==9){
        return 'Dikelola Pengembang';
      }else if($param==10){
        return 'Lapangan Umum';
      }else if($param==11){
        return 'Peternakan';
      }else if($param==12){
        return 'Tidak dimanfaatkan';
      }else if($param==13){
        return 'Jalan';
      }else{
        return 'Undefined';
      }
    break;
    case 'tanda':
      if($param==''){
        return "<span>Belum dicek</span>";
      }else if($param==1){
        return "<span style='color:#00ff00'>Sudah ada tanda</span>";
      }else{
        return "<span style='color:#ff0000'>Belum ada tanda</span>";
      }
    break;
    case 'sengketa':
      if($param==''){
        return "<span>Belum dicek</span>";
      }else if($param==1){
        return "<span style='color:#00ff00'>Tidak ada sengketa</span>";
      }else{
        return "<span style='color:#ff0000'>Bermasalah</span>";
      }
    break;

    default:
    return 'undefined';
  }
}

function rupiah($uang)
{
    $rupiah  = "";
    $panjang = Strlen($uang);

    while ($panjang > 3)
    {
    $rupiah = "." . substr($uang, -3) . $rupiah;
    $lebar = strlen($uang) - 3;
    $uang   = substr($uang,0,$lebar);
    $panjang= strlen($uang);
    }

    $rupiah = $uang.$rupiah." ,-";
    return $rupiah;
}

function getUserIpAddr(){
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }else{
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}


function cekkewenangan($controller,$method,$user,$level){

  if($level==3){
    if(strtolower($controller)!='Home'){
      Redirect(base_url()."Home");
    }
  }else if($level==2){
    if($method==''){
      $method='index';
    }
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $dd['type'] = "multiple";
    $dd['table'] = "tb_userrole";
    $dd['join']['table'] = "tb_hakakses";
    $dd['join']['key'] = "id_has";
    $dd['join']['ref'] = "idmenu_role";
    $dd['condition']['idusr_role'] = $user;
    $dd['condition']['controller'] = strtolower($controller);
    $dd['like']['method'] = strtolower($method);

    $datauser = $ci->crud_model->get_data($dd);

    if(!$datauser){
      Redirect(base_url()."home");
    }

  }else if($level==4){
    if(strtolower($controller)!='reportppat'){
          Redirect(base_url()."Home");
    }else if(strtolower($controller)=='reportppat' && strtolower($method)=='bulanan'){
      Redirect(base_url()."Home");
    }
  }else if($level==7){
    if($method==''){
      $method='index';
    }
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $usr['type'] = "single";
    $usr['column'] = "typeusr_reg";
    $usr['table'] = "tb_register";
    $usr['condition']['id_reg'] = $user;
    $datauser = $ci->crud_model->get_data($usr);

    $dd['type'] = "single";
    $dd['table'] = "tb_levelrole";
    $dd['join']['table'] = "tb_hakakses";
    $dd['join']['key'] = "id_has";
    $dd['join']['ref'] = "idmenu_role";
    $dd['condition']['idlvl_role'] = $datauser['typeusr_reg'];
    $dd['condition']['controller'] = strtolower($controller);
    $dd['like']['method'] = strtolower($method);

    $datamenu = $ci->crud_model->get_data($dd);

    if(!$datamenu){
      Redirect(base_url()."home");
    }
  }


}

function cekkelurahan($user,$level,$kel){

  if($level==2){
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $ddm['type'] = "multiple";
    $ddm['table'] = "tb_userkel";
    $ddm['condition']['idusr_kel'] = $user;
    $ddm['condition']['idkel_kel'] = $kel;

    $datauserm = $ci->crud_model->get_data($ddm);

    if(!$datauserm){
      Redirect(base_url()."home");
    }
  }else if($level==7){
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $ddm['type'] = "single";
    $ddm['table'] = "tb_register";
    $ddm['condition']['id_reg'] = $user;

    $datauserm = $ci->crud_model->get_data($ddm);

    if($datauserm['kdfull_reg']!=$kel){
      Redirect(base_url()."publics/permohonandesa");
    }
  }
}

function cekuserppat($user,$level,$idedit){

  if($level==4){
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $ddm['type'] = "single";
    $ddm['table'] = "tb_reportppat";
    $ddm['condition']['id_rpt'] = $idedit;
    $datauserm = $ci->crud_model->get_data($ddm);

    if($datauserm['idppat_rpt']!=$user){
      Redirect(base_url()."home");
    }

  }else if($level!=1){
    Redirect(base_url()."home");
  }
}

function ceknib($nib,$ref,$kel,$status){
  $ci =& get_instance();
  $ci->load->model('crud_model');

  $existhak['table'] 	  = "tb_nib";
  $existhak['type'] 	  = "single";
  $existhak['condition']['idref_nib'] 	  = $ref;
  $existhak['condition']['idkel_nib'] 	  = $kel;
  $existhak['condition']['status_nib'] 	  = $status;
  $datex = $ci->crud_model->get_data($existhak);

  if($datex){
    $datnub ['nib_nib']    = $nib;

    $updatenub = $ci->crud_model->update("tb_nib",$datnub,array('idref_nib'=>$ref,'idkel_nib'=>$kel,'status_nib'=>$status));
  }else{
    $datnub ['idref_nib']  = $ref;
    $datnub ['nib_nib']    = $nib;
    $datnub ['idkel_nib']  = $kel;
    $datnub ['status_nib'] = $status;

    $inputnub = $ci->crud_model->input("tb_nib",$datnub);
  }

}

function getuserkelurahan($user,$level,$kel){

  if($level==2){
    $ci =& get_instance();
    $ci->load->model('crud_model');

    $ddm['type'] = "single";
    $ddm['table'] = "tb_userkel";
    $ddm['join']['table'] = "ms_kelurahan";
    $ddm['join']['key'] = "kd_full";
    $ddm['join']['ref'] = "idkel_kel";
    $ddm['condition']['idusr_kel'] = $user;
    $ddm['condition']['idkel_kel'] = $kel;

    $datauserm = $ci->crud_model->get_data($ddm);

    if($datauserm){
      echo $datauserm['nma_kel'];
    }else{
      echo 'not found';
    }

  }else{
    echo 'admin';
  }


}

function createkodebpkad($kdfull){

    $ci =& get_instance();
    $ci->load->model('crud_model');

    $dat['table'] = "ms_kelurahan";
    $dat['type'] = "single";
    $dat['join']['table'] = "ms_kecamatan";
    $dat['join']['key'] = "kd_kec";
    $dat['join']['ref'] = "kdkec_kel";
    $dat['column'] = "kdpbb_kec,kdpbb_kel";
    $dat['condition']['kd_full'] = $kdfull;
    $datakode = $ci->crud_model->get_data($dat);

    return '3322'.$datakode['kdpbb_kec'].''.$datakode['kdpbb_kel'];

}




function displayArray($input){
    return implode(
        ', <br/>',
        array_map(
            function ($v, $k) {
                return sprintf("%s => <strong>\'%s\'</strong>", $k, addslashes($v));
            },
            $input,
            array_keys($input)
        )
    );
}


function getUserIP(){
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function getOS() {
    $user_agent = @$_SERVER['HTTP_USER_AGENT'];
    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }
    }
    return $os_platform;

}


function getBrowser() {
    $user_agent = @$_SERVER['HTTP_USER_AGENT'];
    $browser        =   "Unknown Browser";
    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }
    }

    return $browser;
}




function represh($delay,$url){
    return "<meta http-equiv='refresh' content='$delay;$url'>";
}

function enkripsi_pass($pass){
    return md5(sha1(hash("sha512", hash("sha512", $pass))));
}


function format_date($time,$lang=null) {
    $temp_time = substr($time,0,10);
    if ($lang == ''){
        $lang = 'id';
    }
    else {
        $lang = $lang;
    }

    $exploding = explode("-", substr($time,0,10));
    $numm = array('01','02','03','04','05','06','07','08','09','10','11','12');
    $month_id = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',  'September','Oktober','November','Desember');
    $month_en = array('January', 'February', 'March', 'April', 'Mey', 'June', 'July', 'August',  'September','October','November','December');
    if ($lang == 'id') {
        for ($i=0;$i<=11;$i++) {
            if($exploding[1] == $numm[$i] ) {
                $time = $exploding[2].' '.$month_id[$i].' '.$exploding[0];
            }
        }
    }

    if ($lang == 'en') {
        for ($i=0;$i<=11;$i++) {
            if($exploding[1] == $numm[$i] ) {
                $time = $exploding[2].' '.$month_en[$i].' '.$exploding[0];
            }
        }
    }
    return $time;
}



function getWeeks($date, $rollover) {
    $cut = substr($date, 0, 8);
    $daylen = 86400;

    $timestamp = strtotime($date);
    $first = strtotime($cut . "00");
    $elapsed = ($timestamp - $first) / $daylen;
    $weeks = 1;

    for ($i = 1; $i <= $elapsed; $i++)    {
        $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);

        $daytimestamp = strtotime($dayfind);
        $day = strtolower(date("l", $daytimestamp));
        if($day == strtolower($rollover))  $weeks ++;
    }

    return $weeks;

}








function compres_img_medium($imagedata){



	$okee = &get_instance();



	$resize_conf = array(



        // it's something like "/full/path/to/the/image.jpg" maybe



        'source_image'  => $imagedata['full_path'],



        // and it's "/full/path/to/the/" + "thumb_" + "image.jpg



        // or you can use 'create_thumbs' => true option instead



        'new_image'     => $imagedata['file_path'].'medium-'.$imagedata['file_name'],



        'width'         => 300,



        'height'        => (300/$imagedata['image_width'])*$imagedata['image_height']



        );







    // initializing



    $okee->image_lib->initialize($resize_conf);



    $okee->image_lib->resize();



}







function compres_img_small($imagedata){



	$okee = &get_instance();



	$resize_conf = array(



        // it's something like "/full/path/to/the/image.jpg" maybe



        'source_image'  => $imagedata['full_path'],



        // and it's "/full/path/to/the/" + "thumb_" + "image.jpg



        // or you can use 'create_thumbs' => true option instead



        'new_image'     => $imagedata['file_path'].'small-'.$imagedata['file_name'],



        'width'         => 150,



        'height'        => (150/$imagedata['image_width'])*$imagedata['image_height']



        );







    // initializing



    $okee->image_lib->initialize($resize_conf);



    $okee->image_lib->resize();



}







function delete_pictt($source){



	if(file_exists("./images/".$source)){



		unlink("./images/".$source);



		unlink("./images/medium-".$source);



		unlink("./images/small-".$source);



	}







}











function config_uploadimg(){



	$config['upload_path'] = './images/';



    $config['allowed_types'] = 'gif|jpg|png|jpeg';



    $config['max_size'] = '1024';



    $config['encrypt_name'] = TRUE;



    return $config;



}








function fdate ($value,$format) {



    list($thn,$bln,$tgl) = explode ("-",$value);



    $return = "";



    switch ($format) {



        case "DDMMYYYY" :



            $return = $tgl." ".fbulan($bln)." ".$thn;



        break;



        case "DD" :



            $return = $tgl;



        break;



        case "MM" :



            $return = $bln;



        break;



        case "YYYYY" :



            $return = $thn;



        break;



        case "MMYYYY" :



            $return = fbulan($bln)." ".$thn;



        break;



        case "mm" :



            $return = fbulan($bln);



        break;



        case "HHDDMMYYYY" :



            $eks = explode(" ", $tgl);



            $tgl = $eks[0];



            $jam = $eks[1];



            list($H,$M,$S) = explode(":",$jam);



            $return = $tgl." ".fbulan($bln)." ".$thn." | ".$H.":".$M.":".$S;



        break;

        case 'hari' :
          $day = date('w', strtotime($value));
          switch($day){
            case '0':
              $return = 'Minggu';
              break;
            case '1':
              $return = 'Senin';
              break;
            case '2':
              $return = 'Selasa';
              break;
            case '3':
              $return = 'Rabu';
              break;
            case '4':
              $return = 'Kamis';
              break;
            case '5':
              $return = 'Jumat';
              break;
            case '6':
              $return = 'Sabtu';
              break;
            default:
            $return = 'undefined';
              break;
          }
        break;

        case "DDMMYYYYHH" :



            $eks = explode(" ", $tgl);



            $tgl = $eks[0];



            $jam = $eks[1];



            list($H,$M,$S) = explode(":",$jam);



            $return = $tgl."-".$bln."-".$thn." | ".$H.":".$M.":".$S;



        break;







    }



    return $return;



}






function ftanggal ($value,$format) {



    list($thn,$bln,$tgl) = explode ("-",$value);



    $return = "";



    switch ($format) {



        case "DDMMYYYY" :



            $return = $tgl."-".$bln."-".$thn;



        break;



        case "DD" :



            $return = $tgl;



        break;



        case "MM" :



            $return = $bln;



        break;



        case "YYYYY" :



            $return = $thn;



        break;



        case "MMYYYY" :



            $return = $bln."-".$thn;



        break;



        case "mm" :



            $return = fbulan($bln);



        break;



        case "HHDDMMYYYY" :



            $eks = explode(" ", $tgl);



            $tgl = $eks[0];



            $jam = $eks[1];



            list($H,$M,$S) = explode(":",$jam);



            $return = $tgl."-".$bln."-".$thn." | ".$H.":".$M.":".$S;



        break;







    }



    return $return;



}



function fbulan ($bulan) {



    if ($bulan=="01") { $bln="Januari"; } else if ($bulan=="02") { $bln="Februari"; }



    else if ($bulan=="03") { $bln="Maret"; } else if ($bulan=="04") { $bln="April"; }



    else if ($bulan=="05") { $bln="Mei"; } else if ($bulan=="06") { $bln="Juni"; }



    else if ($bulan=="07") { $bln="Juli"; } else if ($bulan=="08") { $bln="Agustus"; }



    else if ($bulan=="09") { $bln="September"; } else if ($bulan=="10") { $bln="Oktober"; }



    else if ($bulan=="11") { $bln="November"; } else if ($bulan=="12") { $bln="Desember"; }



    else { $bln = ""; }



    return $bln;



}

function notification ($type,$title,$status){ ?>
    <div class="alert alert-<?php echo $type; ?> alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-check"></i> <?php echo $type; ?></h4>
    <?php echo $status; ?></div>
    <?php
}
