<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* ------- DONE --------*/
class Ajax extends CI_Controller {
    var $userdata = NULL;

    public function __construct (){
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function cari_nib() {
      $id = $this->input->get('id');

      $k4['type'] 	= "single";
      $k4['table'] = "tb_hak";
      $k4['column'] = "nib_hak";
      $k4['condition']['no_hak'] = $id;
      $data = $this->crud_model->get_data($k4);

      echo $data['nib_hak'];
    }

    public function cek_permohonan() {
      $id = $this->input->get('permohonan');

      $k4['type'] 	= "single";
      $k4['table'] = "tb_sertipikat";
      $k4['join']['table'] = "tb_register";
      $k4['join']['key'] = "id_reg";
      $k4['join']['ref'] = "idreg_srt";
      $k4['condition']['nope_srt'] = $id;
      $data = $this->crud_model->get_data($k4);

      if($data){
        ?>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">NIK</label>
          <div class="col-sm-6">
            <?=$data['nik_reg']?>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">NAMA</label>
          <div class="col-sm-6">
            <?=$data['nma_reg']?>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">ALAMAT</label>
          <div class="col-sm-6">
            <?=$data['alamat_reg']?>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">No HP</label>
          <div class="col-sm-6">
            <?=$data['nohp_reg']?>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">Sertipikat</label>
          <div class="col-sm-6">
            <b><?=status($data['sert_srt'],'berkas')?></b>
          </div>
        </div>
        <?php
      }
    }

    public function cari_nop($exist=NULL) {
      $kel = $this->input->get('kel');
      $kec = $this->input->get('kec');

      $data['type'] 	= "multiple";
      $data['table'] = "tb_dhkp";
      $data['join']['table'] = "tb_block,ms_kelurahan";
      $data['join']['key'] = "idblk_blk,kd_full";
      $data['join']['ref'] = "idblk_dhkp,idkel_blk";
      $data['condition']['kdkec_kel'] = $kec;
      $data['condition']['kd_kel'] = $kel;
      $fdata = $this->crud_model->get_data($data);

      foreach ($fdata as $dd) {
        $nop = createkodebpkad($dd['idkel_blk']).''.$dd['nama_blk'].''.$dd['nosppt_dhkp'];
        ?><option value='<?= $dd['id_dhkp']?>' <?php if($exist==$dd['id_dhkp']){echo 'selected';}?>><?= $nop ?></option><?php
      }
    }

    public function cari_pengajuan() {
      $sert = $this->input->get('sertipikat');
      $selected = $this->input->get('status');

      if($sert!=''){
      ?>

        <?php
        if($sert==0){
          ?><option value='0'>Pengukuran</option>
          <?php
        }else{
          ?><option value='0' <?php if($selected==0){echo 'selected';}?>>Pengukuran</option>
            <option value='1' <?php if($selected==1){echo 'selected';}?>>Validasi Sertipikat</option>
          <?php
        }
      }
        ?>

      <?php

    }

    public function cari_kelurahan($exist=NULL) {
      $kec = $this->input->get('kec');

      $data['type'] 	= "multiple";
      $data['table']  = "ms_kelurahan";
      $data['condition']['kdkec_kel'] = $kec;
      $data['orderby']['column'] = 'nma_kel';
      $data['orderby']['sort'] = 'asc';
      $result = $this->crud_model->get_data($data);

      foreach ($result as $dd): ?>
          <option value='<?=$dd['kd_kel']?>' <?php if($dd['kd_kel']==$exist){echo 'selected';}?>><?=$dd['nma_kel']?></option>
      <?php endforeach;
    }

    public function cari_kelurahanfull($exist=NULL) {
      $kec = $this->input->get('kec');

      $data['type'] 	= "multiple";
      $data['table']  = "ms_kelurahan";
      $data['condition']['kdkec_kel'] = $kec;
      $data['orderby']['column'] = 'nma_kel';
      $data['orderby']['sort'] = 'asc';
      $result = $this->crud_model->get_data($data);

      foreach ($result as $dd): ?>
          <option value='<?=$dd['kd_full']?>' <?php if($exist){if($exist==$dd['kd_full']){echo 'selected';}}?>><?=$dd['nma_kel']?></option>
      <?php endforeach;
    }

    public function cek_register() {
      $nik = $this->input->get('nik');

      $k4['type'] 	= "single";
      $k4['table'] = "tb_register";
      $k4['condition']['nik_reg'] = $nik;
      $data = $this->crud_model->get_data($k4);

      if($data){
        ?><div class="form-group row file-nohak">
          <label class="col-sm-3"></label>
          <div class="col-sm-6">
            <label style='color:#0000ff'>Sudah Registrasi</label>
            <input type='hidden' name='idreg' value='<?=$data['id_reg']?>'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">Nama</label>
          <div class="col-sm-6">
            <?=$data['nma_reg']?>
            <input type='hidden' class='form-control' name='nama' value='<?=$data['nma_reg']?>'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">Alamat</label>
          <div class="col-sm-6">
            <?=$data['nohp_reg']?>
            <input type='hidden' class='form-control' name='alamat' value='<?=$data['alamat_reg']?>'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">No HP</label>
          <div class="col-sm-6">
            <?=$data['nohp_reg']?>
            <input type='hidden' class='form-control' name='nohp' value='<?=$data['nohp_reg']?>'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">KTP</label>
          <div class="col-sm-6">
            <img class='fancybox' style='max-height:300px' src='<?= base_url()?>Penduduk/<?=$data['ktp_reg']?>' href='<?= base_url()?>Penduduk/<?=$data['ktp_reg']?>'></img>
            <input type='hidden' class='form-control' name='ktp'>
          </div>
        </div><?php
      }else{
        ?><div class="form-group row file-nohak">
          <label class="col-sm-3"></label>
          <div class="col-sm-6">
            <label style='color:#ff0000'>Belum Registrasi</label>
            <input type='hidden' name='idreg' value='0'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">Nama</label>
          <div class="col-sm-6">
            <input type='text' class='form-control' id='namareg' name='nama'>
          </div>
          <div class='col-sm-3'>
            <span class='btn-warning btn' id='cekinternal'>Cek Data Internal</span>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">Alamat</label>
          <div class="col-sm-6">
            <input type='text' class='form-control' id='alamatreg' name='alamat'>
          </div>
          <div class='col-sm-3'>
            <span class='btn-info btn' id='cekdukcapil'>Cek Data Dukcapil</span>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">No HP</label>
          <div class="col-sm-6">
            <input type='text' class='form-control' name='nohp'>
          </div>
        </div>
        <div class="form-group row file-nohak">
          <label class="col-sm-3">KTP</label>
          <div class="col-sm-6">
            <input type='file' class='form-control' name='ktp'>
          </div>
        </div>
        <script>
        $('#cekdukcapil').click(function () {
      		$.ajax({
      			type: 'GET',
      			url: '<?php echo base_url();?>ajax/get_dukcapil',
      			data: 'nik=<?=$nik?>',
      			async: 		false,
      			dataType: 'json',
      			success: function(response) {
      					console.log(response);
      						$("#namareg").val(response.content[0].NAMA_LGKP);
      						$("#alamatreg").val(response.content[0].ALAMAT);
      			}
      		});
      	});

        $(".fancybox").fancybox({
    				openEffect: "none",
    				closeEffect: "none"
    		});

        $('#cekinternal').click(function () {
      		$.ajax({
      			type: 'GET',
      			url: '<?php echo base_url();?>ajax/get_nikinternal',
      			data: 'nik=<?=$nik?>',
      			async: 		false,
      			dataType: 'json',
      			success: function(response) {
      					console.log(response);
      						$("#namareg").val(response.nma_pdk);
      						$("#alamatreg").val(response.almat_pdk);
      			}
      		});
      	});
        </script>
        <?php
      }
    }

    public function formptsl($fungsi) {
      $user = $this->auth_model->get_userdata();
      if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}
      $idblk = $this->input->get('idblk');
      $nib = substr($this->input->get('nib'),8);

      $block = $this->studio_2_1_model->sr_name_block($idblk);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);

      if ($this->input->post()) {

				$this->db->trans_start();
				$datktp['table'] = "tb_penduduk";
	      $datktp['type'] = "single";
				$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
				$ktp = $this->crud_model->get_data($datktp);

				$user = $this->auth_model->get_userdata();

				if(!$ktp){
					$ar = array(
						'noktp_pdk' => $this->input->post('ktp'),
						'nma_pdk'   => $this->input->post('nama'),
						'ttl_pdk' => $this->input->post('ttl'),
						'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
						'idpeker_pdk' => $this->input->post('pekerjaan'),
						'agm_pdk' => $this->input->post('agama'),
						'almat_pdk' => $this->input->post('alamat'),
						'publish_pdk' => '1',
						'idusr_pdk' => $user['idusr_usr'],
						'create_at' => date('Y-m-d H:i:s')
					);
					$simpan = $this->crud_model->input('tb_penduduk',$ar);

					$insert_id = $this->db->insert_id();
					$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menambahkan Data Penduduk dengan rincian ".displayArray($ar));

				}else{
					$insert_id = $ktp['idpdk_pdk'];
				}

				// GET NUB
				$datnub['table'] = "tb_ptsl";
	      $datnub['type'] = "single";
				$datnub['column'] = "MAX(nub_ptsl) as maximum";
				$datnub['condition']['idblk_ptsl'] = $this->input->post('blok');
				$datnub['condition']['publish_ptsl'] = 1;
				$nub = $this->crud_model->get_data($datnub);

				if($nub){
					$dnub=$nub['maximum']+1;
				}else{
					$dnub=1;
				}

        if($fungsi==3){
          $dataarray = array(
						'nub_ptsl' => $dnub,
						'idpdk_ptsl'   => $insert_id,
						'idguna_ptsl' => $this->input->post('guna'),
						'idblk_ptsl' => $this->input->post('blok'),
						'utara_ptsl' => $this->input->post('utara'),
						'timur_ptsl' => $this->input->post('timur'),
						'selatan_ptsl' => $this->input->post('selatan'),
						'barat_ptsl' => $this->input->post('barat'),
						'desc0_ptsl' => $this->input->post('des0'),
						'desc1_ptsl' => $this->input->post('des1'),
						'desc2_ptsl' => $this->input->post('des2'),
						'desc3_ptsl' => $this->input->post('des3'),
						'desc4_ptsl' => $this->input->post('des4'),
						'dc_ptsl' => $this->input->post('dc'),
						'dpersil_ptsl' => $this->input->post('dpersil'),
						'dklas_ptsl' => $this->input->post('dklas'),
						'dluas_ptsl' => $this->input->post('dluas'),
						'ddari_ptsl' => $this->input->post('ddari'),
						'idkperluan_ptsl' => $this->input->post('dkeperluan'),
						'thn_ptsl' => $this->input->post('dtahun'),
						'note_ptsl' => $this->input->post('note'),
						'nib_ptsl' => $this->input->post('nib'),
						'thn_risalah' => $this->input->post('thn_risalah'),
						'publish_ptsl' => '1',
						'idusr_ptsl' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);
        }else if($fungsi==7){
          $dataarray = array(
						'nub_ptsl' => $dnub,
						'idpdk_ptsl'   => $insert_id,
						'idguna_ptsl' => $this->input->post('guna'),
						'idblk_ptsl' => $this->input->post('blok'),
						'utara_ptsl' => $this->input->post('utara'),
						'timur_ptsl' => $this->input->post('timur'),
						'selatan_ptsl' => $this->input->post('selatan'),
						'barat_ptsl' => $this->input->post('barat'),
						'desc0_ptsl' => $this->input->post('des0'),
						'desc1_ptsl' => $this->input->post('des1'),
						'desc2_ptsl' => $this->input->post('des2'),
						'desc3_ptsl' => $this->input->post('des3'),
						'desc4_ptsl' => $this->input->post('des4'),
						'dc_ptsl' => $this->input->post('dc'),
						'dpersil_ptsl' => $this->input->post('dpersil'),
						'dklas_ptsl' => $this->input->post('dklas'),
						'dluas_ptsl' => $this->input->post('dluas'),
						'ddari_ptsl' => $this->input->post('ddari'),
						'idkperluan_ptsl' => $this->input->post('dkeperluan'),
						'thn_ptsl' => $this->input->post('dtahun'),
						'note_ptsl' => $this->input->post('note'),
            'luasfisik_ptsl' => $this->input->post('luas'),
						'noberkas_ptsl' => $this->input->post('noberkas'),
						'nib_ptsl' => $this->input->post('nib'),
						'klaster_ptsl' => $this->input->post('seleksik1'),
						'noberkasyrd_ptsl' => $this->input->post('noberkasyuridis'),
						'thn_risalah' => $this->input->post('thn_risalah'),
						'publish_ptsl' => '1',
						'idusr_ptsl' => $user['idusr_usr'],
						'create_at' => date("Y-m-d H:i:s")
					);
        }


				$simpan = $this->crud_model->input('tb_ptsl',$dataarray);
				$insert_id = $this->db->insert_id();

        $cekblockz['table'] 	= "tb_block";
				$cekblockz['type'] 	  = "single";
				$cekblockz['column'] 	= "idkel_blk";
				$cekblockz['condition']['idblk_blk'] 	  = $this->input->post('blok');
				$cekbl = $this->crud_model->get_data($cekblockz);
				if($this->input->post('nib')){
						$input = $this->crud_model->input("tb_nib",array('idkel_nib'=>$cekbl['idkel_blk'],'nib_nib'=>$this->input->post('nib'),'idref_nib'=>$insert_id,'status_nib'=>0));
				}

				$dhkp = $this->input->post('dhkp');
				foreach ($dhkp as $dd) {
					$datptsl ['idptsl_ptsl'] = $insert_id;
					$datptsl ['iddhkp_ptsl'] = $dd;

					$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
				}

        $count = count($_FILES['berkas']['name']);
				for($i=0;$i<$count;$i++){
						$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
	          $_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
	          $_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
	          $_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
	          $_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

						$file = explode(".",$_FILES["berkas"]["name"][$i]);
		        $sum = count($file);
						$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
						$config1['upload_path']		= './DATA/BERKAS/';
						$config1['allowed_types']	= '*';
						$this->upload->initialize($config1);
						$uploads 				= $this->upload->do_upload('file');
						$data1					= $this->upload->data();
						$nama_upload 		= $data1['file_name'];

						if($data1){
							$ar = array(
								'idptsl_pbk' => $insert_id,
								'berkas_pbk' => $nama_upload
							);
							$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas',$insert_id,"Add Berkas dengan rincian ".displayArray($ar));
						}
				}

				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$insert_id,"Menambahkan Data PTSL dengan rincian ".displayArray($dataarray));
				$this->db->trans_complete();

        if($simpan){
  				$msg = true;
  			}
  			echo json_encode($msg);die();

			}

      $dat['table'] = "tb_pekerjaan";
      $dat['type'] = "multiple";
      $dat['orderby']['column'] = 'nama_pkr';
      $dat['orderby']['sort'] = 'asc';
      $this->content['pekerjaan'] = $this->crud_model->get_data($dat);

      $dhkp['table'] = "tb_dhkp";
      $dhkp['type'] = "multiple";
      $dhkp['join']['table'] = "tb_block";
      $dhkp['join']['key'] = "idblk_blk";
      $dhkp['join']['ref'] = "idblk_dhkp";
      $dhkp['condition']['idblk_dhkp'] =$idblk;
      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);


      $template['type'] = "single";
      $template['table'] = "tb_ptsl";
      $template['column'] = "desc0_ptsl,desc1_ptsl,desc2_ptsl,desc3_ptsl,desc4_ptsl,thn_risalah";
      $template['condition']['id_ptsl'] = 0;
      $this->content['template'] = $this->crud_model->get_data($template);
      $this->content['nib'] = $nib;

      $this->content['block'] = $block;

      $this->load->view('studio3/form_ptsl',$this->content);
      ?>
      <script type="text/javascript">
            $(function() {
                $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
                $('.monthpicker').datepicker({ dateFormat: 'mm-yy' }).val();
            });
        </script>
      <?php

    }

    public function editptsl($fungsi){

      $user = $this->auth_model->get_userdata();
      if(isset($this->session->userdata['smt_member'])){
				$this->content['data']['user'] = $this->auth_model->get_userdata();
			}

      $idblk = $this->input->get('idblk');
      $method = $this->input->get('method');
      $nub = $this->input->get('nub');
      $nib = substr($this->input->get('nib'),8);

      $template['type'] = "single";
			$template['table'] = "tb_ptsl";
			$template['join']['table']="tb_penduduk";
			$template['join']['key']="idpdk_ptsl";
			$template['join']['ref']="idpdk_pdk";
			$template['condition']['idblk_ptsl'] = $idblk;
      $template['condition']['nib_ptsl'] = $nib;
      $template['condition']['nub_ptsl'] = $nub;
			$datatemplate = $this->crud_model->get_data($template);
      $this->content['template'] = $datatemplate;

      $berkas['type'] = "multiple";
			$berkas['table'] = "tb_ptslberkas";
			$berkas['condition']['idptsl_pbk'] = $datatemplate['id_ptsl'];
			$this->content['berkas'] = $this->crud_model->get_data($berkas);

			$spt['type'] = "multiple";
			$spt['table'] = "tb_ptsldhkp";
			$spt['join']['table']="tb_dhkp";
			$spt['join']['key']="id_dhkp";
			$spt['join']['ref']="iddhkp_ptsl";
			$spt['condition']['idptsl_ptsl'] = $datatemplate['id_ptsl'];
			$this->content['sppt'] = $this->crud_model->get_data($spt);

			if ($this->input->post()) {
        $this->db->trans_start();

        if($fungsi==3){
    			$datktp['table'] = "tb_penduduk";
    			$datktp['type'] = "single";
    			$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
    			$ktp = $this->crud_model->get_data($datktp);

    			if(!$ktp){
    				$ar = array(
    					'noktp_pdk' => $this->input->post('ktp'),
    					'nma_pdk'   => $this->input->post('nama'),
    					'ttl_pdk' => $this->input->post('ttl'),
    					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
    					'idpeker_pdk' => $this->input->post('pekerjaan'),
    					'agm_pdk' => $this->input->post('agama'),
    					'almat_pdk' => $this->input->post('alamat'),
    					'publish_pdk' => '1',
    					'idusr_pdk' => $user['idusr_usr'],
    					'create_at' => date('Y-m-d H:i:s')
    				);
    				$simpan = $this->crud_model->input('tb_penduduk',$ar);
    				$insert_id = $this->db->insert_id();
    				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
    			}else{
    				$ar = array(
    					'nma_pdk'   => $this->input->post('nama'),
    					'ttl_pdk' => $this->input->post('ttl'),
    					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
    					'idpeker_pdk' => $this->input->post('pekerjaan'),
    					'agm_pdk' => $this->input->post('agama'),
    					'almat_pdk' => $this->input->post('alamat'),
    					'publish_pdk' => '1',
    					'idusr_pdk' => $user['idusr_usr'],
    					'create_at' => date('Y-m-d H:i:s')
    				);
    				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
    				$insert_id = $ktp['idpdk_pdk'];
    				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
    			}

    			$dataarray = array(
    				'idpdk_ptsl'   => $insert_id,
    				'idguna_ptsl' => $this->input->post('guna'),
    				'idblk_ptsl' => $this->input->post('blok'),
    				'utara_ptsl' => $this->input->post('utara'),
    				'timur_ptsl' => $this->input->post('timur'),
    				'selatan_ptsl' => $this->input->post('selatan'),
    				'barat_ptsl' => $this->input->post('barat'),
    				'desc0_ptsl' => $this->input->post('des0'),
    				'desc1_ptsl' => $this->input->post('des1'),
    				'desc2_ptsl' => $this->input->post('des2'),
    				'desc3_ptsl' => $this->input->post('des3'),
    				'desc4_ptsl' => $this->input->post('des4'),
    				'dc_ptsl' => $this->input->post('dc'),
    				'dpersil_ptsl' => $this->input->post('dpersil'),
    				'dklas_ptsl' => $this->input->post('dklas'),
    				'dluas_ptsl' => $this->input->post('dluas'),
    				'ddari_ptsl' => $this->input->post('ddari'),
    				'idkperluan_ptsl' => $this->input->post('dkeperluan'),
    				'thn_ptsl' => $this->input->post('dtahun'),
    				'note_ptsl' => $this->input->post('note'),
    				'thn_risalah' => $this->input->post('thn_risalah'),
    				'publish_ptsl' => '1',
    				'idusr_ptsl' => $user['idusr_usr']
    			);

          $delete = $this->crud_model->delete('tb_ptsldhkp',array('idptsl_ptsl'=>$this->input->post('templateid')));

    			$dhkp = $this->input->post('dhkp');
    			foreach ($dhkp as $dd) {
    				$datptsl ['idptsl_ptsl'] = $this->input->post('templateid');
    				$datptsl ['iddhkp_ptsl'] = $dd;

    				$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
    			}

          $count = count($_FILES['berkas']['name']);
  				for($i=0;$i<$count;$i++){
  						$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
  	          $_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
  	          $_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
  	          $_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
  	          $_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

  						$file = explode(".",$_FILES["berkas"]["name"][$i]);
  		        $sum = count($file);
  						$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
  						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
  						$config1['upload_path']		= './DATA/BERKAS/';
  						$config1['allowed_types']	= '*';
  						$this->upload->initialize($config1);
  						$uploads 				= $this->upload->do_upload('file');
  						$data1					= $this->upload->data();
  						$nama_upload 		= $data1['file_name'];

  						if($data1){
  							$ar = array(
  								'idptsl_pbk' => $this->input->post('templateid'),
  								'berkas_pbk' => $nama_upload
  							);
  							$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
  							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas',$this->input->post('templateid'),"Add Berkas dengan rincian ".displayArray($ar));
  						}
  				}

    			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$this->input->post('templateid'),"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));

        }else if($fungsi==5){
          $dataarray = array(
            'luasfisik_ptsl'   => $this->input->post('luas'),
            'idusr_ptsl' => $user['idusr_usr']
          );
          $this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$this->input->post('templateid'),"Edit Luas PTSL dengan rincian ".displayArray($dataarray));
        }else if($fungsi==6){
          $dataarray = array(
  					'luasfisik_ptsl'   => $this->input->post('luas'),
  					'nib_ptsl'   => $this->input->post('nib'),
  					'noberkas_ptsl'   => $this->input->post('noberkas'),
  					'idusr_ptsl' => $user['idusr_usr']
  				);
          $this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$this->input->post('templateid'),"Edit Luas dan berkas PTSL dengan rincian ".displayArray($dataarray));
        }else if($fungsi==7){
          $datktp['table'] = "tb_penduduk";
    			$datktp['type'] = "single";
    			$datktp['condition']['noktp_pdk'] = $this->input->post('ktp');
    			$ktp = $this->crud_model->get_data($datktp);

    			if(!$ktp){
    				$ar = array(
    					'noktp_pdk' => $this->input->post('ktp'),
    					'nma_pdk'   => $this->input->post('nama'),
    					'ttl_pdk' => $this->input->post('ttl'),
    					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
    					'idpeker_pdk' => $this->input->post('pekerjaan'),
    					'agm_pdk' => $this->input->post('agama'),
    					'almat_pdk' => $this->input->post('alamat'),
    					'publish_pdk' => '1',
    					'idusr_pdk' => $user['idusr_usr'],
    					'create_at' => date('Y-m-d H:i:s')
    				);
    				$simpan = $this->crud_model->input('tb_penduduk',$ar);
    				$insert_id = $this->db->insert_id();
    				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$insert_id,"Menginput Data Penduduk dengan rincian ".displayArray($ar));
    			}else{
    				$ar = array(
    					'nma_pdk'   => $this->input->post('nama'),
    					'ttl_pdk' => $this->input->post('ttl'),
    					'ttg_pdk' => date("Y-m-d",strtotime($this->input->post('tgl'))),
    					'idpeker_pdk' => $this->input->post('pekerjaan'),
    					'agm_pdk' => $this->input->post('agama'),
    					'almat_pdk' => $this->input->post('alamat'),
    					'publish_pdk' => '1',
    					'idusr_pdk' => $user['idusr_usr'],
    					'create_at' => date('Y-m-d H:i:s')
    				);
    				$simpan = $this->crud_model->update('tb_penduduk',$ar,array('noktp_pdk' => $this->input->post('ktp')));
    				$insert_id = $ktp['idpdk_pdk'];
    				$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_penduduk',$this->input->post('ktp'),"Mengedit Data Penduduk dengan rincian ".displayArray($ar));
    			}

          $dataarray = array(
    				'idpdk_ptsl'   => $insert_id,
    				'idguna_ptsl' => $this->input->post('guna'),
    				'idblk_ptsl' => $this->input->post('blok'),
    				'utara_ptsl' => $this->input->post('utara'),
    				'timur_ptsl' => $this->input->post('timur'),
    				'selatan_ptsl' => $this->input->post('selatan'),
    				'barat_ptsl' => $this->input->post('barat'),
    				'desc0_ptsl' => $this->input->post('des0'),
    				'desc1_ptsl' => $this->input->post('des1'),
    				'desc2_ptsl' => $this->input->post('des2'),
    				'desc3_ptsl' => $this->input->post('des3'),
    				'desc4_ptsl' => $this->input->post('des4'),
    				'dc_ptsl' => $this->input->post('dc'),
    				'dpersil_ptsl' => $this->input->post('dpersil'),
    				'dklas_ptsl' => $this->input->post('dklas'),
    				'dluas_ptsl' => $this->input->post('dluas'),
    				'ddari_ptsl' => $this->input->post('ddari'),
    				'idkperluan_ptsl' => $this->input->post('dkeperluan'),
    				'thn_ptsl' => $this->input->post('dtahun'),
    				'note_ptsl' => $this->input->post('note'),
    				'luasfisik_ptsl' => $this->input->post('luas'),
    				'noberkas_ptsl' => $this->input->post('noberkas'),
    				'nib_ptsl' => $this->input->post('nib'),
    				'klaster_ptsl' => $this->input->post('seleksik1'),
    				'noberkasyrd_ptsl' => $this->input->post('noberkasyuridis'),
    				'thn_risalah' => $this->input->post('thn_risalah'),
    				'publish_ptsl' => '1',
    				'idusr_ptsl' => $user['idusr_usr']
    			);

          $delete = $this->crud_model->delete('tb_ptsldhkp',array('idptsl_ptsl'=>$this->input->post('templateid')));

    			$dhkp = $this->input->post('dhkp');
    			foreach ($dhkp as $dd) {
    				$datptsl ['idptsl_ptsl'] = $this->input->post('templateid');
    				$datptsl ['iddhkp_ptsl'] = $dd;

    				$inputdhkp = $this->crud_model->input("tb_ptsldhkp",$datptsl);
    			}

          $count = count($_FILES['berkas']['name']);
  				for($i=0;$i<$count;$i++){
  						$_FILES['file']['name'] = $_FILES['berkas']['name'][$i];
  	          $_FILES['file']['type'] = $_FILES['berkas']['type'][$i];
  	          $_FILES['file']['tmp_name'] = $_FILES['berkas']['tmp_name'][$i];
  	          $_FILES['file']['error'] = $_FILES['berkas']['error'][$i];
  	          $_FILES['file']['size'] = $_FILES['berkas']['size'][$i];

  						$file = explode(".",$_FILES["berkas"]["name"][$i]);
  		        $sum = count($file);
  						$nmfile1 					= "Berkas_".time().".".$file[$sum-1]; 	//nama file saya beri nama langsung dan diikuti fungsi time
  						$config1['file_name'] 		= $nmfile1; 				//nama yang terupload nantinya
  						$config1['upload_path']		= './DATA/BERKAS/';
  						$config1['allowed_types']	= '*';
  						$this->upload->initialize($config1);
  						$uploads 				= $this->upload->do_upload('file');
  						$data1					= $this->upload->data();
  						$nama_upload 		= $data1['file_name'];

  						if($data1){
  							$ar = array(
  								'idptsl_pbk' => $this->input->post('templateid'),
  								'berkas_pbk' => $nama_upload
  							);
  							$simpan = $this->crud_model->input('tb_ptslberkas',$ar);
  							$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptslberkas',$this->input->post('templateid'),"Add Berkas dengan rincian ".displayArray($ar));
  						}
  				}

    			$this->referensi_model->save_logs($this->content['data']['user']['idusr_usr'],'tb_ptsl',$this->input->post('templateid'),"Mengedit Data PTSL dengan rincian ".displayArray($dataarray));
        }

				$simpan = $this->crud_model->update('tb_ptsl',$dataarray,array('id_ptsl'=>$this->input->post('templateid')));

        $this->db->trans_complete();
        if($simpan){
  				$msg = true;
  			}
  			echo json_encode($msg);die();

			}

			$dat['table'] = "tb_pekerjaan";
			$dat['type'] = "multiple";
			$dat['orderby']['column'] = 'nama_pkr';
			$dat['orderby']['sort'] = 'asc';
			$this->content['pekerjaan'] = $this->crud_model->get_data($dat);

			$status = $this->uri->segment(3);
			$set_status = $this->uri->segment(4);

			$block = $this->studio_2_1_model->sr_name_block($idblk);
			cekkelurahan($user['idusr_usr'],$user['level_usr'],$block['idkel_blk']);


				$idkel = $block['idkel_blk'];
				$this->content['block'] = $block;

				$dhkp['table'] = "tb_dhkp";
	      $dhkp['type'] = "multiple";
				$dhkp['join']['table'] = "tb_block";
				$dhkp['join']['key'] = "idblk_blk";
				$dhkp['join']['ref'] = "idblk_dhkp";
				$dhkp['condition']['idblk_dhkp'] =$idblk;
	      $this->content['dhkp'] = $this->crud_model->get_data($dhkp);

        if($method==3 || $method==7){
            $this->load->view('studio3/edit_ptsl',$this->content);
        }else{
          $this->load->view('studio5/edit_ptsl',$this->content);
        }


    }

    public function download_peta() {
      $idkel = $this->input->get('idkel');

      $data['type'] 	= "multiple";
      $data['table']  = "tb_petaanalog";
      $data['condition']['idkel_pal'] = $idkel;
      $sdata = $this->crud_model->get_data($data);

      $value='analog_pal';
      $url = '/PETA/PETA_ANALOG/';

      ?><input type='hidden' value='<?=count($sdata)?>' id='sumfile'>
      <input type='hidden' value='analog_pal[]' id='nameform'><?php

      if(!$sdata){
        ?><div class="form-group row">
          <label class="col-sm-3">File Upload</label>
          <div class="col-sm-6">
            <input type="file" class="form-control input-sm" name="analog_pal[]">
          </div>
        </div><?php
      }else{
        foreach ($sdata as $dd) {
          ?><div class="form-group row" id='exs<?=$dd['id_pal']?>'>
            <label class="col-sm-3">File <?= fdate($dd['create_at'],'HHDDMMYYYY');?></label>
            <div class="col-sm-1">
              <a class='btn btn-warning' data-toggle="tooltip" title='download berkas' href='<?= base_url().''.$url.''.$dd[$value]?>'><span class="fa fa-cloud-download"></span></a>
            </div>
            <div class="col-sm-1">
              <a class='btn btn-danger' data-toggle="tooltip" title='hapus berkas' onClick='hapus(<?=$dd['id_pal']?>)'><span class="fa fa-close"></span></a>
            </div>
          </div><?php
        }
      }
    }

    public function download_berkas($jenis) {
      $idblk = $this->input->get('idblk');
      if($jenis=='gu'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockgu";
        $data['condition']['idbgu_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);

        $value='gu_blk';
        $url = '/DATA/GU/';

      }else if($jenis=='ukur'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockukur";
        $data['condition']['idbuk_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);

        $value='petukur_blk';
        $url = '/PETA/PETA_UKUR/';

      }else if($jenis=='mentah'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockdatmen";
        $data['condition']['idbdm_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);

        $value='datmen_blk';
        $url = '/DATA/DATA_MENTAH/';

      }else if($jenis=='ptsl'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockptsl";
        $data['condition']['idbpt_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);

        $value='petptsl_blk';
        $url = '/PETA/PETA_PTSL/';

      }else if($jenis=='analog'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_petaanalog";
        $data['condition']['idkel_pal'] = $idblk;
        $sdata = $this->crud_model->get_data($data);

        $value='analog_pal';
        $url = '/PETA/PETA_ANALOG/';

      }


      if($sdata){
        foreach ($sdata as $dd) {
          ?><div class="form-group row" id='exs<?=$dd[$primary]?>'>
            <label class="col-sm-3">File <?= fdate($dd['create_at'],'HHDDMMYYYY')?></label>
            <div class="col-sm-1">
              <a class='btn btn-warning' data-toggle="tooltip" title='download berkas' href='<?= base_url().''.$url.''.$dd[$value]?>'><span class="fa fa-cloud-download"></span></a>
            </div>
          </div><?php
        }
      }
    }

    public function cari_berkas($jenis) {
      $idblk = $this->input->get('idblk');
      if($jenis=='gu'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockgu";
        $data['condition']['idbgu_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);
        $name='gu_blk[]';
        $value='gu_blk';
        $url = '/DATA/GU/';
        $primary='id_bgu';
      }else if($jenis=='ukur'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockukur";
        $data['condition']['idbuk_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);
        $name='petukur_blk[]';
        $value='petukur_blk';
        $url = '/PETA/PETA_UKUR/';
        $primary='id_buk';
      }else if($jenis=='datmen'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockdatmen";
        $data['condition']['idbdm_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);
        $name='datmen_blk[]';
        $value='datmen_blk';
        $url = '/DATA/DATA_MENTAH/';
        $primary='id_bdm';
      }else if($jenis=='ptsl'){
        $data['type'] 	= "multiple";
        $data['table']  = "tb_blockptsl";
        $data['condition']['idbpt_blk'] = $idblk;
        $sdata = $this->crud_model->get_data($data);
        $name='petptsl_blk[]';
        $value='petptsl_blk';
        $url = '/PETA/PETA_PTSL/';
        $primary='id_bpt';
      }

      ?><input type='hidden' value='<?=count($sdata)?>' id='sumfile'>
      <input type='hidden' value='<?=$name?>' id='nameform'><?php

      if(!$sdata){
        ?><div class="form-group row">
          <label class="col-sm-3">File Upload</label>
          <div class="col-sm-6">
            <input type="file" class="form-control input-sm" name="<?=$name?>">
          </div>
        </div><?php
      }else{
        foreach ($sdata as $dd) {
          ?><div class="form-group row" id='exs<?=$dd[$primary]?>'>
            <label class="col-sm-3">File <?= fdate($dd['create_at'],'HHDDMMYYYY');?></label>
            <div class="col-sm-1">
              <a class='btn btn-warning' data-toggle="tooltip" title='download berkas' href='<?= base_url().''.$url.''.$dd[$value]?>'><span class="fa fa-cloud-download"></span></a>
            </div>
            <div class="col-sm-1">
              <a class='btn btn-danger' data-toggle="tooltip" title='hapus berkas' onClick='hapus(<?=$dd[$primary]?>,"<?=$jenis?>")'><span class="fa fa-close"></span></a>
            </div>
          </div><?php
        }
      }
    }

    public function hapus_berkas($jenis) {

      $id = $this->input->get('id');

      if($jenis=='gu'){
        $data['type'] 	= "single";
        $data['table']  = "tb_blockgu";
        $data['condition']['id_bgu'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_blockgu',array('id_bgu'=>$id));
        unlink('./DATA/GU/'.$sdata['gu_blk']);
      }else if($jenis=='ukur'){
        $data['type'] 	= "single";
        $data['table']  = "tb_blockukur";
        $data['condition']['id_buk'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_blockukur',array('id_buk'=>$id));
        unlink('./PETA/PETA_UKUR/'.$sdata['petukur_blk']);
      }else if($jenis=='datmen'){
        $data['type'] 	= "single";
        $data['table']  = "tb_blockdatmen";
        $data['condition']['id_bdm'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_blockdatmen',array('id_bdm'=>$id));
        unlink('./DATA/DATA_MENTAH/'.$sdata['datmen_blk']);
      }else if($jenis=='ptsl'){
        $data['type'] 	= "single";
        $data['table']  = "tb_blockptsl";
        $data['condition']['id_bpt'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_blockptsl',array('id_bpt'=>$id));
        unlink('./PETA/PETA_PTSL/'.$sdata['petptsl_blk']);
      }else if($jenis=='berkas'){
        $data['type'] 	= "single";
        $data['table']  = "tb_ptslberkas";
        $data['condition']['id_pbk'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_ptslberkas',array('id_pbk'=>$id));
        unlink('./DATA/BERKAS/'.$sdata['berkas_pbk']);
      }else if($jenis=='analog'){
        $data['type'] 	= "single";
        $data['table']  = "tb_petaanalog";
        $data['condition']['id_pal'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('tb_petaanalog',array('id_pal'=>$id));
        unlink('./PETA/PETA_ANALOG/'.$sdata['analog_pal']);
      }else if($jenis=='sertipikat'){
        $data['type'] 	= "single";
        $data['table']  = "img_sertipikat";
        $data['condition']['id_isrt'] = $id;
        $sdata = $this->crud_model->get_data($data);
        $delete = $this->crud_model->delete('img_sertipikat',array('id_isrt'=>$id));
        unlink('./sertipikat/'.$sdata['image_isrt']);
      }


    }

    public function get_nib() {
      $id = $this->input->get('idptsl');

      $ptsl['type'] 	= "single";
      $ptsl['table'] = "tb_ptsl";
      $ptsl['column'] = "nib_ptsl,luasfisik_ptsl,noberkas_ptsl";
      $ptsl['condition']['id_ptsl'] = $id;
      $data = $this->crud_model->get_data($ptsl);

      echo json_encode($data);die();
    }

    public function get_nikinternal() {
      $nik = $this->input->get('nik');

      $ptsl['type'] 	= "single";
      $ptsl['table'] = "tb_penduduk";
      $ptsl['column'] = "nma_pdk,almat_pdk";
      $ptsl['condition']['noktp_pdk'] = $nik;
      $data = $this->crud_model->get_data($ptsl);

      echo json_encode($data);die();
    }

    public function adddhkp() {
      $dhkp = $this->input->get('dhkp');
    }

    public function get_pekerjaan() {
      $kerja = $this->input->get('kerja');
      if (strpos($kerja, '/') !== false) {
        $data = explode("/", $kerja);
        $kerja=$data[0];
      }


      $tabelkerja['type'] 	= "single";
      $tabelkerja['table'] = "tb_pekerjaan";
      $tabelkerja['column'] = "idpkr_pkr";
      $tabelkerja['like']['nama_pkr'] = ucfirst($kerja);
      $data = $this->crud_model->get_data($tabelkerja);

      echo json_encode($data);die();
    }

    public function get_dukcapil() {
      $nik = $this->input->get('nik');

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "http://103.121.123.36:90/dukcapil/get_json/bpn/call_nik",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{   \n\t\"user_id\"    : \"BPN\",\n    \"password\"   : \"1234\",\n    \"ip_address\" : \"10.100.100.1\",\n    \"nik\"        : \"$nik\"\n}",
        CURLOPT_HTTPHEADER => array(
          "A: application/json",
          "Content-Type: application/json"
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

      echo $response;die();
    }

    public function cari_dhkp() {
      $id = $this->input->get('id');

      if($id==''){
        $attr = '';
      }else{
        $attr = 'readonly';
      }
      $dhkp['type'] 	= "single";
      $dhkp['table'] = "tb_dhkp";
      $dhkp['join']['table'] = "tb_block";
      $dhkp['join']['key'] = "idblk_dhkp";
      $dhkp['join']['ref'] = "idblk_blk";
      $dhkp['condition']['id_dhkp'] = $id;
      $dkel = $this->crud_model->get_data($dhkp);
      ?>
      <div class='form-group col-sm-12'>
        <label>No.SPPT / NOP</label> :
        <div class='form-inline'>
          <?php $nop = createkodebpkad($dkel['idkel_blk']).''.$dkel['nama_blk']; ?>
          <input type='text' disabled style='width:50%;float:left' class='form-control' value='<?= $nop?>'>
          <input type='text' name='sppt' style='width:40%;float:left' placeholder='no.sppt' class='form-control' id='sppt' <?=$attr?>  value='<?=$dkel['nosppt_dhkp']?>'>
        </div>
      </div>
      <div class='form-group col-sm-12'>
        <label>Nama Wajib Pajak</label> :
        <input type='text' name='nwp' id='nwp' class='form-control' placeholder='nama lengkap'  <?=$attr?> value='<?=$dkel['nama_dhkp']?>'>
      </div>
      <div class='form-inline col-sm-6'>
        <label>NJOP Rp.</label> :
        <input type='number' placeholder='nominal njop' id='njop' name='njop' class='form-control'  <?=$attr?> value='<?= $dkel['njopsppt_dhkp']?>'>
      </div>
      <div class='form-inline col-sm-6'>
        <label>Luas Bumi</label> :
        <input type='number' placeholder='Luas SPPT' id='luassppt' name='luassppt' class='form-control'  <?=$attr?> value='<?= $dkel['luassppt_dhkp']?>'>
      </div>
      <div class='form-group col-sm-12'>
        <label>Alamat Objek Pajak</label> :
        <textarea placeholder='alamat objek pajak' name='aop' id='aop' class='form-control' <?=$attr?> ><?= $dkel['aopsppt_dhkp']?></textarea>
      </div>
      <div class='form-group col-sm-12'>
        <label>Alamat Wajib Pajak</label> :
        <textarea placeholder='alamat wajib pajak' name='awp' id='awp' class='form-control' <?=$attr?> ><?= $dkel['awpsppt_dhkp']?></textarea>
      </div>
      <?php
    }

    function get_kelurahan()
    {
      $data = $this->input->get();
      $kelurahan['type'] 	= "multiple";
      $kelurahan['table'] = "ms_kelurahan";
      $kelurahan['orderby']['column'] = "nma_kel";
      $kelurahan['orderby']['sort']   = "asc";
      $kelurahan['condition']['kdkec_kel'] = $data['id'];
      $dkel = $this->crud_model->get_data($kelurahan);

      foreach ($dkel as $dk) {
        ?>
        <tr>
          <td>
            <div class="checkbox">
              <label><input type="checkbox" class='kelurahan' name='kel[]' value="<?= $dk['kd_full']?>"><?php echo $dk['nma_kel'];?></label>
            </div>
          </td>
        </tr>
      <?php }
    }

    function get_template()
    {
      $menu['type'] 	= "multiple";
			$menu['table'] 	= "tb_hakakses";
			$menu['orderby']['column'] 	= "studio_has,nma_has";
			$menu['orderby']['sort'] 	  = "asc";
			$menus = $this->crud_model->get_data($menu);

      $start=1;
      foreach ($menus as $dd) {
        $role['type'] = "single";
        $role['table'] = "tb_templaterole";
        $role['condition']['idmenu_role'] = $dd['id_has'];
        $role['condition']['idtmp_role'] = $this->input->get('id');
        $drole = $this->crud_model->get_data($role);
        if($start==$dd['studio_has'] && $dd['studio_has']==1){
          ?><div id="menu<?=$dd['studio_has']?>" class="tab-pane fade in active"><?php
          $start++;
        }

        if($start==$dd['studio_has']){
          ?></div><div id="menu<?=$dd['studio_has']?>" class="tab-pane fade"><?php
          $start++;
        }

        ?>
          <div class="checkbox">
            <label><input type="checkbox" class='menu' name='menu[]' <?php if($drole){echo 'checked';}?> value="<?= $dd['id_has']?>"><?= $dd['nma_has']?> <?= $dd['aksi_has']?></label>
          </div>

        <?php
      }

    }

    public function gethari() {
      $hari=$this->input->post('tanggal');
      $day = date('w', strtotime($hari));
      switch($day){
        case '0':
          echo 'Minggu';
          break;
        case '1':
          echo 'Senin';
          break;
        case '2':
          echo 'Selasa';
          break;
        case '3':
          echo 'Rabu';
          break;
        case '4':
          echo 'Kamis';
          break;
        case '5':
          echo 'Jumat';
          break;
        case '6':
          echo 'Sabtu';
          break;
        default:
          echo 'undefined';
          break;
      }
    }

    public function get_infonib($id) {
      $nib = $this->input->get('nib');
      $koor = $this->input->get('coor');

      $table['type'] 					= "single";
      $table['table'] 				= "tb_hak";
      $table['join']['table'] = "tb_nub,tb_block,tb_dhkp,tb_su";
      $table['join']['key'] 	= "nohak_nub,idblk_blk,id_dhkp,nohak_su";
      $table['join']['ref'] 	= "no_hak,idblk_nub,iddhkp_nub,no_hak";
      $table['column'] 	= "pmi_hak,nosppt_dhkp,njopsppt_dhkp,luas_su,nama_blk,id_kelurahan";
      $table['condition']['nib_hak']= $nib;
      $table['condition']['id_kelurahan']= $id;
      $data = $this->crud_model->get_data($table);

      $nop = createkodebpkad($data['id_kelurahan']).''.$data['nama_blk'].''.$data['nosppt_dhkp'];

      echo $koor."NIB : ".$data['id_kelurahan'].''.$this->input->get('nib')."<br> Luas : ".$data['luas_su']."<br> Pemilik : ".$data['pmi_hak']."<br> NOP : ".$nop."<br> NJOP : ".$data['njopsppt_dhkp']."<br>Penggunaan : ".$this->input->get('guna');
    }

    public function get_fullnib($id,$method) {
      if($method==2){
        $nib = $this->input->get('nib');
      }else{
        $nib = substr($this->input->get('nib'),8);
      }
      $area = $this->input->get('area');

      if($method==1 || $method==2){
        $c1 = 'id_kelurahan';
        $c2 = 'idkel_blk';
      }else{
        $c1 = 'idblk_nub';
        $c2 = 'idblk_ptsl';
      }

      $table['type'] 					= "multiple";
      $table['table'] 				= "tb_nub";
      $table['join']['table'] = "tb_hak,tb_dhkp,tb_su,tb_block";
      $table['join']['key'] 	= "no_hak,id_dhkp,nohak_su,idblk_nub";
      $table['join']['ref'] 	= "nohak_nub,iddhkp_nub,no_hak,idblk_blk";
      $table['column'] 	= "pmi_hak,nosppt_dhkp,no_hak,luas_su,id_kelurahan,nama_blk";
      $table['condition']['nib_hak']= $nib;
      $table['condition'][$c1]= $id;
      $table['condition']['publish_nub']= 1;
      $data = $this->crud_model->get_data($table);

      if($data){
        if(count($data)>1){
            echo "<b style='color:red'>DUPLICATE ENTRY</b><br><br>";
        }
        echo "<b>SUDAH SERTIPIKAT</b><br>";
        foreach ($data as $dt) {
          $nop = createkodebpkad($dt['id_kelurahan']).''.$dt['nama_blk'].''.$dt['nosppt_dhkp'];

          echo "NIB : ".$this->input->get('nib')."<br>Block : ".$dt['nama_blk']."<br> Luas : ".$dt['luas_su']."<br> NOP : ".$nop."<br> No Hak : ".$dt['no_hak']."<br> Pemilik : ".$dt['pmi_hak']."<br><br>";
        }

      }else{
        $ptsl['type'] 					= "multiple";
        $ptsl['table'] 				  = "tb_ptsl";
        $ptsl['join']['table']  = "tb_penduduk,tb_ptsldhkp,tb_dhkp,tb_block";
        $ptsl['join']['key'] 	  = "idpdk_pdk,idptsl_ptsl,id_dhkp,idblk_blk";
        $ptsl['join']['ref'] 	  = "idpdk_ptsl,id_ptsl,iddhkp_ptsl,idblk_ptsl";
        $ptsl['column'] 	      = "nma_pdk,nub_ptsl,nosppt_dhkp,idkel_blk,nama_blk,idguna_ptsl,luasfisik_ptsl";
        $ptsl['condition']['nib_ptsl']= $nib;
        $ptsl['condition'][$c2]= $id;
        $ptsl['condition']['publish_ptsl']= 1;
        $ptsl['groupby']        = 'nub_ptsl';
        $dataptsl = $this->crud_model->get_data($ptsl);

        if($dataptsl){
          if(count($dataptsl)>1){
            echo "<b style='color:red'>DUPLICATE ENTRY</b><br><br>";
          }
          echo "<b>BELUM SERTIPIKAT</b><br>";

          foreach ($dataptsl as $dt) {

            if($dt['idguna_ptsl']==1){
              $guna = 'Perumahan';
            }else if($dt['idguna_ptsl']==2){
              $guna = 'Pekarangan';
            }else if($dt['idguna_ptsl']==3){
              $guna = 'Sawah';
            }else if($dt['idguna_ptsl']==4){
              $guna = 'Tegalan';
            }else{
              $guna = 'Undefined';
            }
            $nop = createkodebpkad($dt['idkel_blk']).''.$dt['nama_blk'].''.$dt['nosppt_dhkp'];

            echo "NUB : ".$dt['nub_ptsl']."<br>NIB : ".$this->input->get('nib')."<br>Block : ".$dt['nama_blk']."<br> NOP : ".$nop."<br> Pemilik : ".$dt['nma_pdk']."<br> Penggunaan : ".$guna."<br> Luas : ".$dt['luasfisik_ptsl'];

            if($method==1){
              echo "<br><br>";
            }else{
              echo "<br><br><a href='#' onClick='editptsl(".$dt['nub_ptsl'].",".$method.",".$id.",".$this->input->get('nib').")' class='btn-open-ptsl btn btn-warning'>edit</a><br><br>";
            }

          }

        }else{
          if($method==3 || $method==7){
              echo "<b>BELUM SERTIPIKAT</b><br>NUB : Belum Entry<br>NIB : ".$this->input->get('nib')."<br>Luas Peta : ".$area."<br><br><a href='#' onClick='berkasptsl(".$method.",".$id.",".$this->input->get('nib').")' class='btn-open-ptsl btn btn-warning'>input<a>";
          }else{
              echo "<b>BELUM SERTIPIKAT</b><br>NUB : Belum Entry<br>NIB : ".$this->input->get('nib')."<br>Luas Peta : ".$area;
          }
        }

      }
    }

    public function get_warnanib($id,$jenis) {
      $nib = substr($this->input->get('nib'),8);

      if($jenis==1){
        $c1 = 'id_kelurahan';
        $c2 = 'idkel_blk';
        $c3 = 'id_kelurahan';
      }else{
        $c1 = 'idblk_nub';
        $c2 = 'idblk_ptsl';
        $c3 = 'idblk_blk';
      }

      $cenub['type'] 					= "single";
      $cenub['table'] 				= "tb_nub";
      $cenub['column'] 				= "count(idnub_nub) as jumnub";
      $cenub['join']['table'] = "tb_hak";
      $cenub['join']['key'] 	= "no_hak";
      $cenub['join']['ref'] 	= "nohak_nub";
      $cenub['condition']['nib_hak']= $nib;
      $cenub['condition'][$c1]= $id;
      $cenub['condition']['publish_nub']= 1;
      $nub = $this->crud_model->get_data($cenub);

      if($nub['jumnub']>1){
        $sign = 5;
      }else if($nub['jumnub']==1){
        $sign = 1;
      }else{
        $cekptsl['type'] 					= "single";
        $cekptsl['table'] 				= "tb_ptsl";
        $cekptsl['column'] 				= "count(id_ptsl) as jumptsl";
        if($jenis==1){
          $cekptsl['join']['table'] = "tb_block";
          $cekptsl['join']['key'] 	= "idblk_blk";
          $cekptsl['join']['ref'] 	= "idblk_ptsl";
        }
        $cekptsl['condition']['nib_ptsl']= $nib;
        $cekptsl['condition'][$c2]= $id;
        $cekptsl['condition']['publish_ptsl']= 1;
        $ptsl = $this->crud_model->get_data($cekptsl);

        if($ptsl['jumptsl']>1){
          $sign=5;
        }else if($ptsl['jumptsl']==1){
          $sign=2;
        }else{
          $cekhak['type'] 			  = "single";
          $cekhak['table'] 				= "tb_hak";
          $cekhak['column'] 			= "count(id_studio_1_1) as jumhak";
          if($jenis!=1){
            $cekhak['join']['table']= "tb_block";
            $cekhak['join']['key'] 	= "idkel_blk";
            $cekhak['join']['ref'] 	= "id_kelurahan";
          }
          $cekhak['condition']['nib_hak']= $this->input->get('nib');
          $cekhak['condition'][$c3]= $id;
          $cekhak['condition']['status_hak']= 1;
          $hak = $this->crud_model->get_data($cekhak);
          if($hak['jumhak']>1){
            $sign=5;
          }else if($hak['jumhak']==1){
            $sign=3;
          }else{
            $sign=4;
          }
        }
      }

      echo $sign;

    }

    public function get_jenisnib($id,$jenis) {
      if($jenis==1){
          $nib = substr($this->input->get('nib'),8);
      }else{
        $nib = $this->input->get('nib');
      }


      $cek['type'] 					= "single";
      $cek['table'] 				= "tb_nib";
      $cek['column'] 				= "count(*) as jumlah,status_nib";
      $cek['condition']['nib_nib']= $nib;
      $cek['condition']['idkel_nib']= $id;
      $dnib = $this->crud_model->get_data($cek);

      if($dnib['jumlah']>1){
        $sign = 5;
      }else if($dnib['jumlah']==1){
        if($dnib['status_nib']==1){
          $sign = 1;
        }else if($dnib['status_nib']==0){
          $sign = 2;
        }
      }else{
        $sign = 4;
      }
      echo $sign;
    }

    public function getberkas() {
      ?>
      <style>
      	#mobile{
      		display: none;
      	}
      	@media (max-width: 767px) {
      		#mobile{
      			display: block;
      		}
      		#wide{
      			display: none;
      		}
      	}
      </style>
      <?php
      $nohak = $this->input->get('nohak');
      $jenis = $this->input->get('jenis');

      if($jenis=='SU'){
        $table['type'] 					= "single";
        $table['table'] 				= "tb_hak";
        $table['join']['table'] = "ms_kelurahan,ms_kecamatan,tb_su";
        $table['join']['key'] 	= "kd_full,kd_kec,nohak_su";
        $table['join']['ref'] 	= "id_kelurahan,kdkec_kel,no_hak";
        $table['column'] 	= "no_su,thn_su,nma_kel,nma_kec,kd_full";
        $table['condition']['no_hak']= $nohak;
        $data = $this->crud_model->get_data($table);
        ?>
        <embed id='wide' style='width:100%;height:600px' src='<?= base_url()."digitalisasi/".strtoupper($data['nma_kec'])."/".strtoupper($data['nma_kel'])."/SURAT_UKUR/SU_".$data['kd_full']."_".$data['no_su']."_".$data['thn_su'].".pdf"; ?>'>
        <embed id='mobile' style='width:100%;height:600px' src='https://docs.google.com/viewer?url=<?= base_url()."digitalisasi/".strtoupper($data['nma_kec'])."/".strtoupper($data['nma_kel'])."/SURAT_UKUR/SU_".$data['kd_full']."_".$data['no_su']."_".$data['thn_su'].".pdf&embedded=true"; ?>'>
        <?php

      }else if($jenis='BT'){
        $table['type'] 					= "single";
        $table['table'] 				= "tb_hak";
        $table['join']['table'] = "ms_kelurahan,ms_kecamatan";
        $table['join']['key'] 	= "kd_full,kd_kec";
        $table['join']['ref'] 	= "id_kelurahan,kdkec_kel";
        $table['column'] 	= "nma_kel,nma_kec";
        $table['condition']['no_hak']= $nohak;
        $data = $this->crud_model->get_data($table);
        $bt = str_replace('.','',$nohak);
        ?>
        <embed id='wide' style='width:100%;height:600px' src='<?= base_url()."digitalisasi/".strtoupper($data['nma_kec'])."/".strtoupper($data['nma_kel'])."/BUKU_TANAH/BT_".$bt.".pdf"; ?>'>
        <embed id='mobile' style='width:100%;height:600px' src='https://docs.google.com/viewer?url=<?= base_url()."digitalisasi/".strtoupper($data['nma_kec'])."/".strtoupper($data['nma_kel'])."/BUKU_TANAH/BT_".$bt.".pdf&embedded=true"; ?>'>
        <?php
      }

    }

}
?>
