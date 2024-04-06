<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
h4{
	border-bottom:1px solid #f0f0f0;
	padding:10px;
	font-weight: bold;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
						<form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4);?>">
							<div class='col-sm-12'>
								<div class='col-sm-12'>
									<label>NIS</label> :
									<input type='text' style='width:300px;' name='nis' id='nis' class='form-control' placeholder='nis' value='<?=$ip4t['nis_ip4t'];?>'>
								</div>
								<ul class="nav nav-tabs" id="myTab" role="tablist">
								  <li class="nav-item">
								    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">A1 TERKAIT DENGAN SUBYEK</a>
								  </li>
								  <li class="nav-item">
								    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">A2 PENGUASAAN</a>
								  </li>
								  <li class="nav-item">
								    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">B TERKAIT DENGAN OBJEK</a>
								  </li>
									<li class="nav-item">
								    <a class="nav-link" id="batas-tab" data-toggle="tab" href="#batas" role="tab" aria-controls="batas" aria-selected="false">C TERKAIT DENGAN AKSES</a>
								  </li>
								</ul>
								<div class="tab-content" id="myTabContent">
								  <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
										<div id='penduduk' class='col-sm-6'>
											<h4>A1 TERKAIT DENGAN SUBYEK</h4>
											<div class='form-group col-sm-12'>
												<div class='col-sm-6'>
													<label>No.KTP</label> :
													<input type='text' name='ktp1' id='ktp1' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$a1['noktp_pdk'];?>'>
												</div>
												<div class='col-sm-3'>
													<label>Dukcapil</label>
													<input type='button' value='Cek' id='cktp1' class='btn btn-warning'>
												</div>
												<div class='col-sm-3'>
													<label>Internal</label>
													<input type='button' value='Cek' id='cktpin1' class='btn btn-info'>
												</div>
											</div>
											<div class='form-group col-sm-12'>
												<label>Nama</label> :
												<input type='text' name='nama1' id='nama1' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($a1['nma_pdk']);?>">
											</div>
											<div class='form-inline col-sm-4'>
												<label>Tempat Lahir</label> :
												<input type='text' name='ttl1' id='ttl1' placeholder='tempat lahir' style='margin:5px;width:150px' class='form-control' value='<?= $a1['ttl_pdk']?>'>
											</div>
											<div class='form-inline col-sm-4'>
												<label>Tanggal Lahir</label> :
												<input type='text' name='tgl1' id='tgl1' style='margin:5px;width:150px' class='datepicker form-control' value='<?= $a1['ttg_pdk']?>'>
											</div>
											<div class='form-inline col-sm-6'>
												<label>Pekerjaan</label> :
												<select name='pekerjaan1' id='pekerjaan1' style='margin:5px' class='form-control'>
													<?php
													foreach ($pekerjaan as $dd) {
														?><option value='<?=$dd['idpkr_pkr']?>' <?php if($a1['idpeker_pdk']==$dd['idpkr_pkr']){ echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
													}
													 ?>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Agama</label> :
												<select name='agama1' id='agama1' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a1['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
													<option value='2' <?php if($a1['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
													<option value='3' <?php if($a1['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
													<option value='4' <?php if($a1['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
													<option value='5' <?php if($a1['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
													<option value='6' <?php if($a1['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
												</select>
											</div>
											<div class='form-group col-sm-12'>
												<label>Alamat Lengkap</label> :
												<textarea name='alamat1' id='alamat1' class='form-control' placeholder='alamat lengkap'><?= $a1['almat_pdk']?></textarea>
											</div>
											<div class='form-group col-sm-3'>
												<label>RT</label> :
												<input type='text' name='rt1' id='rt1' class='form-control' placeholder='RT' value="<?= $a1['rt_pdk'];?>">
											</div>
											<div class='form-group col-sm-3'>
												<label>RW</label> :
												<input type='text' name='rw1' id='rw1' class='form-control' placeholder='RW' value="<?= $a1['rw_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kelurahan</label> :
												<input type='text' name='kel1' id='kel1' class='form-control' placeholder='Kelurahan' value="<?= $a1['kel_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kecamatan</label> :
												<input type='text' name='kec1' id='kec1' class='form-control' placeholder='Kecamatan' value="<?= $a1['kec_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kabupaten</label> :
												<input type='text' name='kab1' id='kab1' class='form-control' placeholder='Kabupaten' value="<?= $a1['kab_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Status</label> :
												<select name='status1' id='status1' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a1['status_pdk']=='1'){echo 'selected';}?>>Belum Menikah</option>
													<option value='2' <?php if($a1['status_pdk']=='2'){echo 'selected';}?>>Menikah</option>
													<option value='3' <?php if($a1['status_pdk']=='3'){echo 'selected';}?>>Pernah Menikah</option>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Domisili</label> :
												<select name='domisili1' id='domisili1' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a1['domisili_pdk']=='1'){echo 'selected';}?>>Desa ini</option>
													<option value='2' <?php if($a1['domisili_pdk']=='2'){echo 'selected';}?>>Desa lain berbatasan langsung</option>
													<option value='3' <?php if($a1['domisili_pdk']=='3'){echo 'selected';}?>>Desa lain tidak berbatasan langsung</option>
													<option value='3' <?php if($a1['domisili_pdk']=='4'){echo 'selected';}?>>di luar kecamatan</option>
													<option value='3' <?php if($a1['domisili_pdk']=='5'){echo 'selected';}?>>lainnya ***</option>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Jumlah Anggota</label> :
												<input type='text' name='anggota1' id='anggota1' class='form-control' placeholder='Jumlah anggota' value="<?= $a1['anggota_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Memiliki tanah sejak</label> :
												<input type='text' name='a1tahun' id='a1tahun' class='form-control' placeholder='memiliki tanah sejak tahun' value="<?= $ip4t['a1miliktanah_ip4t'];?>">
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
										<div id='penduduk' class='col-sm-6'>
											<h4>A2 PENGUASAAN</h4>
											<div class='form-group col-sm-12'>
												<div class='col-sm-6'>
													<label>No.KTP</label> :
													<input type='text' name='ktp2' id='ktp2' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$a2['noktp_pdk'];?>'>
												</div>
												<div class='col-sm-3'>
													<label>Dukcapil</label>
													<input type='button' value='Cek' id='cktp2' class='btn btn-warning'>
												</div>
												<div class='col-sm-3'>
													<label>Internal</label>
													<input type='button' value='Cek' id='cktpin2' class='btn btn-info'>
												</div>
											</div>
											<div class='form-group col-sm-12'>
												<label>Nama</label> :
												<input type='text' name='nama2' id='nama2' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($a2['nma_pdk']);?>">
											</div>
											<div class='form-inline col-sm-4'>
												<label>Tempat Lahir</label> :
												<input type='text' name='ttl2' id='ttl2' placeholder='tempat lahir' style='margin:5px;width:150px' class='form-control' value='<?= $a2['ttl_pdk']?>'>
											</div>
											<div class='form-inline col-sm-4'>
												<label>Tanggal Lahir</label> :
												<input type='text' name='tgl2' id='tgl2' style='margin:5px;width:150px' class='datepicker form-control' value='<?= $a2['ttg_pdk']?>'>
											</div>
											<div class='form-inline col-sm-6'>
												<label>Pekerjaan</label> :
												<select name='pekerjaan2' id='pekerjaan2' style='margin:5px' class='form-control'>
													<?php
													foreach ($pekerjaan as $dd) {
														?><option value='<?=$dd['idpkr_pkr']?>' <?php if($a2['idpeker_pdk']==$dd['idpkr_pkr']){ echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
													}
													 ?>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Agama</label> :
												<select name='agama2' id='agama2' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a2['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
													<option value='2' <?php if($a2['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
													<option value='3' <?php if($a2['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
													<option value='4' <?php if($a2['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
													<option value='5' <?php if($a2['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
													<option value='6' <?php if($a2['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
												</select>
											</div>
											<div class='form-group col-sm-12'>
												<label>Alamat Lengkap</label> :
												<textarea name='alamat2' id='alamat2' class='form-control' placeholder='alamat lengkap'><?= $a2['almat_pdk']?></textarea>
											</div>
											<div class='form-group col-sm-3'>
												<label>RT</label> :
												<input type='text' name='rt2' id='rt2' class='form-control' placeholder='RT' value="<?= $a2['rt_pdk'];?>">
											</div>
											<div class='form-group col-sm-3'>
												<label>RW</label> :
												<input type='text' name='rw2' id='rw2' class='form-control' placeholder='RW' value="<?= $a2['rw_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kelurahan</label> :
												<input type='text' name='kel2' id='kel2' class='form-control' placeholder='Kelurahan' value="<?= $a2['kel_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kecamatan</label> :
												<input type='text' name='kec2' id='kec2' class='form-control' placeholder='Kecamatan' value="<?= $a2['kec_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Kabupaten</label> :
												<input type='text' name='kab2' id='kab2' class='form-control' placeholder='Kabupaten' value="<?= $a2['kab_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Status</label> :
												<select name='status2' id='status2' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a2['status_pdk']=='1'){echo 'selected';}?>>Belum Menikah</option>
													<option value='2' <?php if($a2['status_pdk']=='2'){echo 'selected';}?>>Menikah</option>
													<option value='3' <?php if($a2['status_pdk']=='3'){echo 'selected';}?>>Pernah Menikah</option>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Domisili</label> :
												<select name='domisili2' id='domisili2' style='margin:5px' class='form-control'>
													<option value='1' <?php if($a2['domisili_pdk']=='1'){echo 'selected';}?>>Desa ini</option>
													<option value='2' <?php if($a2['domisili_pdk']=='2'){echo 'selected';}?>>Desa lain berbatasan langsung</option>
													<option value='3' <?php if($a2['domisili_pdk']=='3'){echo 'selected';}?>>Desa lain tidak berbatasan langsung</option>
													<option value='3' <?php if($a2['domisili_pdk']=='4'){echo 'selected';}?>>di luar kecamatan</option>
													<option value='3' <?php if($a2['domisili_pdk']=='5'){echo 'selected';}?>>lainnya ***</option>
												</select>
											</div>
											<div class='form-group col-sm-6'>
												<label>Jumlah Anggota</label> :
												<input type='text' name='anggota2' id='anggota2' class='form-control' placeholder='Jumlah anggota' value="<?= $a2['anggota_pdk'];?>">
											</div>
											<div class='form-group col-sm-6'>
												<label>Menguasai tanah sejak</label> :
												<input type='text' name='a2tahun' id='a2tahun' class='form-control' placeholder='memiliki tanah sejak tahun' value="<?= $ip4t['a2kuasatanah_ip4t'];?>">
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="home-tab">
										<div id='sppt' class='col-sm-12'>
											<h4>B TERKAIT DENGAN OBJEK</h4>
											<div class='col-sm-6'>
												<div class='form-group col-sm-12'>
													<label>NIB</label> :
													<div class='form-inline'>
														<?php if($dhkp['nib_hak']){
															$nib = $dhkp['nib_hak'];
														}else if($ptsl['nib_ptsl']){
															$nib = $ptsl['nib_ptsl'];
														}else{
															$nib = '';
														} ?>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $nib?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Nomor Pajak Bumi Bangunan</label> :
													<div class='form-inline'>
														<?php $nop = createkodebpkad($block['idkel_blk']).''.$block['nama_blk'].''.$dhkp['nosppt_dhkp']; ?>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $nop?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Jalan</label> :
													<div class='form-inline'>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $b['almat_pdk']?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>RT/RW</label> :
													<div class='form-inline'>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $b['rt_pdk'].'/'.$b['rw_pdk']?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Desa/Kelurahan</label> :
													<div class='form-inline'>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $kelurahan['nma_kel']?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Kecamatan</label> :
													<div class='form-inline'>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $kelurahan['nma_kec']?>'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Kabupaten/Kota</label> :
													<div class='form-inline'>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='Semarang'>
													</div>
												</div>
												<div class='form-group col-sm-12'>
													<label>Luas Tanah</label> :
													<div class='form-inline'>
														<?php if($ptsl['luasfisik_ptsl']){
															$luas = $ptsl['luasfisik_ptsl'];
														}else{
															$luas = $dhkp['luassppt_dhkp'];
														} ?>
														<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?=$luas?>'>
													</div>
												</div>
												<div class='form-group'>
													<label>Penguasaan Tanah</label> :
													<select class='form-control' name='kuasatanah'>
														<option value='1' <?php if($ip4t['bkuasatanah_ip4t']=='1'){echo 'selected';} ?>>1 = Pemilik</option>
														<option value='2' disabled>2 = Bukan Pemilik </option>
														<option value='2a' <?php if($ip4t['bkuasatanah_ip4t']=='2a'){echo 'selected';} ?>>2a = Gadai</option>
														<option value='2b' <?php if($ip4t['bkuasatanah_ip4t']=='2b'){echo 'selected';} ?>>2b = Sewa</option>
														<option value='2c' <?php if($ip4t['bkuasatanah_ip4t']=='2c'){echo 'selected';} ?>>2c = Pinjam Pakai</option>
														<option value='2d' <?php if($ip4t['bkuasatanah_ip4t']=='2d'){echo 'selected';} ?>>2d = Penggarap</option>
														<option value='2e' <?php if($ip4t['bkuasatanah_ip4t']=='2e'){echo 'selected';} ?>>2e = Lainnya ****</option>
														<option value='3' <?php if($ip4t['bkuasatanah_ip4t']=='3'){echo 'selected';} ?>>3 = bersama / ulayat</option>
														<option value='4' <?php if($ip4t['bkuasatanah_ip4t']=='4'){echo 'selected';} ?>>4 = badan hukum</option>
														<option value='5' <?php if($ip4t['bkuasatanah_ip4t']=='5'){echo 'selected';} ?>>5 = pemerintah</option>
														<option value='6' <?php if($ip4t['bkuasatanah_ip4t']=='6'){echo 'selected';} ?>>6 = tidak ada penguasaan tanah</option>
													</select>
												</div>
												<div class='form-group'>
													<label>Perolehan Tanah</label> :
													<select class='form-control' name='perolehantanah'>
														<option value='1' <?php if($ip4t['bolehtanah_ip4t']=='1'){echo 'selected';} ?>>1 = Warisan</option>
														<option value='2' <?php if($ip4t['bolehtanah_ip4t']=='2'){echo 'selected';} ?>>2 = Jual Beli </option>
														<option value='3' <?php if($ip4t['bolehtanah_ip4t']=='3'){echo 'selected';} ?>>3 = Tukar Menukar</option>
														<option value='4' <?php if($ip4t['bolehtanah_ip4t']=='4'){echo 'selected';} ?>>4 = Hibah</option>
													</select>
												</div>
												<div class='form-group'>
													<label>Pemilikan Tanah</label> :
													<select class='form-control' name='pemilikantanah'>
														<option value='1' <?php if($ip4t['bmiliktanah_ip4t']=='1'){echo 'selected';} ?>>1 = terdaftar, sertipikat no.HM.003</option>
														<option value='2' disabled>2 = Belum Terdaftar </option>
														<option value='2a' <?php if($ip4t['bmiliktanah_ip4t']=='2a'){echo 'selected';} ?>>2a = Tanah adat;Surat No C.01539</option>
														<option value='2b' <?php if($ip4t['bmiliktanah_ip4t']=='2b'){echo 'selected';} ?>>2b = Tanah Ulayat</option>
														<option value='2c' <?php if($ip4t['bmiliktanah_ip4t']=='2c'){echo 'selected';} ?>>2c = Tanah Negara</option>
													</select>
												</div>
											</div>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label>Penggunaan Bidang Tanah Saat ini</label> :
													<select class='form-control' name='gunabidang'>
														<option value='1' <?php if($ip4t['bgunatanah_ip4t']=='1'){echo 'selected';} ?>>1 = Pemukiman, Perkampungan</option>
														<option value='2' <?php if($ip4t['bgunatanah_ip4t']=='2'){echo 'selected';} ?>>2 = Sawah Irigasi </option>
														<option value='3' <?php if($ip4t['bgunatanah_ip4t']=='3'){echo 'selected';} ?>>3 = Sawah non irigasi</option>
														<option value='4' <?php if($ip4t['bgunatanah_ip4t']=='4'){echo 'selected';} ?>>4 = Tegalan, Ladang</option>
														<option value='5' <?php if($ip4t['bgunatanah_ip4t']=='5'){echo 'selected';} ?>>5 = Kebun campuran</option>
														<option value='6' <?php if($ip4t['bgunatanah_ip4t']=='6'){echo 'selected';} ?>>6 = Perairan darat, tambak</option>
														<option value='7' <?php if($ip4t['bgunatanah_ip4t']=='7'){echo 'selected';} ?>>7 = Tegalan, Ladang</option>
														<option value='8' <?php if($ip4t['bgunatanah_ip4t']=='8'){echo 'selected';} ?>>8 = Kebun campuran</option>
														<option value='9' <?php if($ip4t['bgunatanah_ip4t']=='9'){echo 'selected';} ?>>9 = Perairan darat, tambak</option>
														<option value='10' <?php if($ip4t['bgunatanah_ip4t']=='10'){echo 'selected';} ?>>10 = Peternakan</option>
														<option value='11' <?php if($ip4t['bgunatanah_ip4t']=='11'){echo 'selected';} ?>>11 = Lainnya**</option>
													</select>
												</div>
												<label>Jenis Pemanfaatan Bidang Tanah Saat ini</label> :
												<div class='col-sm-12'>
													<div class='form-group'>
														<label>1. Untuk pemanfaatan tempat tinggal</label> :
														<select class='form-control' name='bidang1'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat1_ip4t']=='1'){echo 'selected';} ?>>Rumah Tinggal </option>
														</select>
													</div>
													<div class='form-group'>
														<label>2. Untuk kegiatan produksi pertanian</label> :
														<select class='form-control' name='bidang2'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat2_ip4t']=='1'){echo 'selected';} ?>>Tanaman Musiman </option>
															<option value='2' <?php if($ip4t['bjenismanfaat2_ip4t']=='2'){echo 'selected';} ?>>Tanaman Keras </option>
															<option value='3' <?php if($ip4t['bjenismanfaat2_ip4t']=='3'){echo 'selected';} ?>>Lainnya, </option>
														</select>
													</div>
													<div class='form-group'>
														<label>3. Untuk kegiatan ekonomi/perdagangan</label> :
														<select class='form-control' name='bidang3'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat3_ip4t']=='1'){echo 'selected';} ?>>Kontrakan </option>
															<option value='2' <?php if($ip4t['bjenismanfaat3_ip4t']=='2'){echo 'selected';} ?>>Toko </option>
															<option value='3' <?php if($ip4t['bjenismanfaat3_ip4t']=='3'){echo 'selected';} ?>>Kantor </option>
															<option value='4' <?php if($ip4t['bjenismanfaat3_ip4t']=='4'){echo 'selected';} ?>>Gudang </option>
															<option value='5' <?php if($ip4t['bjenismanfaat3_ip4t']=='5'){echo 'selected';} ?>>Pabrik (Industri) </option>
															<option value='6' <?php if($ip4t['bjenismanfaat3_ip4t']=='6'){echo 'selected';} ?>>Lainnya, </option>
														</select>
													</div>
													<div class='form-group'>
														<label>4. Untuk Usaha Jasa</label> :
														<select class='form-control' name='bidang4'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat4_ip4t']=='1'){echo 'selected';} ?>>Telekomunikasi </option>
															<option value='2' <?php if($ip4t['bjenismanfaat4_ip4t']=='2'){echo 'selected';} ?>>Transportasi </option>
															<option value='3' <?php if($ip4t['bjenismanfaat4_ip4t']=='3'){echo 'selected';} ?>>Lainnya, </option>
														</select>
													</div>
													<div class='form-group'>
														<label>5. Untuk fasos/fasum</label> :
														<select class='form-control' name='bidang5'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat5_ip4t']=='1'){echo 'selected';} ?>>Sekolah </option>
															<option value='2' <?php if($ip4t['bjenismanfaat5_ip4t']=='2'){echo 'selected';} ?>>Masjid </option>
															<option value='3' <?php if($ip4t['bjenismanfaat5_ip4t']=='3'){echo 'selected';} ?>>Kantor Desa </option>
															<option value='4' <?php if($ip4t['bjenismanfaat5_ip4t']=='4'){echo 'selected';} ?>>Lapangan </option>
															<option value='5' <?php if($ip4t['bjenismanfaat5_ip4t']=='5'){echo 'selected';} ?>>Taman </option>
															<option value='6' <?php if($ip4t['bjenismanfaat5_ip4t']=='6'){echo 'selected';} ?>>Puskesmas </option>
															<option value='7' <?php if($ip4t['bjenismanfaat5_ip4t']=='7'){echo 'selected';} ?>>Lainnya, </option>
														</select>
													</div>
													<div class='form-group'>
														<label>6. Tidak ada pemanfaatan</label> :
														<select class='form-control' name='bidang6'>
															<option value=''> - </option>
															<option value='1' <?php if($ip4t['bjenismanfaat6_ip4t']=='1'){echo 'selected';} ?>>Tidak dimanfaatkan </option>
														</select>
													</div>
												</div>
												<div class='form-group'>
													<label>Indikasi tanah terlantar</label> :
													<select class='form-control' name='indikasitanah'>
														<option value='1' <?php if($ip4t['bindikasi_ip4t']=='1'){echo 'selected';} ?>>Terindikasi terlantar</option>
														<option value='2' <?php if($ip4t['bindikasi_ip4t']=='2'){echo 'selected';} ?>>Tidak Terlantar </option>
													</select>
												</div>
												<div class='form-group'>
													<label>Sengketa, konflik dan perkara pertanahanan</label> :
													<select class='form-control' name='sengketa'>
														<option value='1' <?php if($ip4t['bsengketa_ip4t']=='1'){echo 'selected';} ?>>Sengketa</option>
														<option value='2' <?php if($ip4t['bsengketa_ip4t']=='2'){echo 'selected';} ?>>Konflik </option>
														<option value='3' <?php if($ip4t['bsengketa_ip4t']=='3'){echo 'selected';} ?>>Berperkara di pengadilan</option>
														<option value='4' <?php if($ip4t['bsengketa_ip4t']=='4'){echo 'selected';} ?>>Tidak SKP</option>
													</select>
												</div>
												<div class='form-group'>
													<label>Potensi tanah obyek landeform</label> :
													<select class='form-control' name='potensi'>
														<option value='1' <?php if($ip4t['bpotensi_ip4t']=='1'){echo 'selected';} ?>>1 = Tanah Absente</option>
														<option value='2' <?php if($ip4t['bpotensi_ip4t']=='2'){echo 'selected';} ?>>2 = Tanah Kelebihan Maksimum </option>
														<option value='3' <?php if($ip4t['bpotensi_ip4t']=='3'){echo 'selected';} ?>>3 = Tanah Bekas swapraja</option>
														<option value='4' disabled>4 = Tanah Negara lainnya</option>
														<option value='4a' <?php if($ip4t['bpotensi_ip4t']=='4a'){echo 'selected';} ?>>4a = eks HGU no ......</option>
														<option value='4b' <?php if($ip4t['bpotensi_ip4t']=='4b'){echo 'selected';} ?>>4b = Pelepasan HGU no ......</option>
														<option value='4c' <?php if($ip4t['bpotensi_ip4t']=='4c'){echo 'selected';} ?>>4c = Tanah terlantar</option>
														<option value='4d' <?php if($ip4t['bpotensi_ip4t']=='4d'){echo 'selected';} ?>>4d = Tanah penyelesaian SKP</option>
														<option value='4e' <?php if($ip4t['bpotensi_ip4t']=='4e'){echo 'selected';} ?>>4e = Tanah dari pelepasan kawasan hutan</option>
														<option value='4f' <?php if($ip4t['bpotensi_ip4t']=='4f'){echo 'selected';} ?>>4f = Tanah Timbul</option>
														<option value='4g' <?php if($ip4t['bpotensi_ip4t']=='4g'){echo 'selected';} ?>>4g = Tanah bekas tambang yang telah direklamasi</option>
														<option value='4h' <?php if($ip4t['bpotensi_ip4t']=='4h'){echo 'selected';} ?>>4h = Tanah negara dalam penguasaan masyarakat</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="batas" role="tabpanel" aria-labelledby="home-tab">
										<div id='tanah' class='col-sm-12'>
											<h4>C TERKAIT DENGAN AKSES</h4>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label>Sertifikan pernah dijaminkan</label> :
													<select class='form-control' name='sertifikan'>
														<option value='1' <?php if($ip4t['csertif_ip4t']=='1'){echo 'selected';} ?>>Ya</option>
														<option value='2' <?php if($ip4t['csertif_ip4t']=='2'){echo 'selected';} ?>>Tidak </option>
													</select>
												</div>
												<div class='form-group'>
													<label>Potensi akses ***</label> :
													<select class='form-control' name='potensiakses'>
														<option value='1' <?php if($ip4t['cpotensi_ip4t']=='1'){echo 'selected';} ?>>Pertanian</option>
														<option value='2' <?php if($ip4t['cpotensi_ip4t']=='2'){echo 'selected';} ?>>Peternakan </option>
														<option value='3' <?php if($ip4t['cpotensi_ip4t']=='3'){echo 'selected';} ?>>Perkebunan</option>
														<option value='4' <?php if($ip4t['cpotensi_ip4t']=='4'){echo 'selected';} ?>>Perikanan </option>
														<option value='5' <?php if($ip4t['cpotensi_ip4t']=='5'){echo 'selected';} ?>>Industri Kecil</option>
														<option value='6' <?php if($ip4t['cpotensi_ip4t']=='6'){echo 'selected';} ?>>Lainnya </option>
													</select>
												</div>
												<div class='form-group'>
													<label>bantuan yang pernah diterima</label> :
													<div class='col-sm-12'>
														<div class='form-group'>
															<label>Jenis Bantuan</label> :
															<input type='text' name='jenisbantuan' value='<?=$ip4t['cbantuanjenis_ip4t']?>' class='form-control' style='width:50%;'>
														</div>
														<div class='form-group'>
															<label>Dari</label> :
															<input type='text' name='dari' value='<?=$ip4t['cbantuandari_ip4t']?>' class='form-control' style='width:50%;'>
														</div>
														<div class='form-group'>
															<label>Tanggal</label> :
															<input type='text' name='tanggal' value='<?=$ip4t['cbantuantanggal_ip4t']?>' class='datepicker form-control' style='width:50%;'>
														</div>
													</div>
												</div>
											</div>
											<div class='col-sm-6'>

											<div class='form-group'>
												<label>Pendapatan</label> :
												<div class='col-sm-12'>
													<div class='form-group'>
														<label>a. sebelum menerima sertipikat</label> :
														<input type='text' name='pendapatana' value='<?=$ip4t['cpendapatanbelum_ip4t']?>' class='form-control' placeholder="Rp.   " style='width:50%;'>
													</div>
													<div class='form-group'>
														<label>b. sesudah menerima sertipikat</label> :
														<input type='text' name='pendapatanb' value='<?=$ip4t['cpendapatansudah_ip4t']?>' class='form-control' placeholder="Rp.   " style='width:50%;'>
													</div>
												</div>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>
						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-left">
									<?php if(strtolower($this->uri->segment(1))!='ajax'){ ?>
									<button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
									<?php }?>
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#pekerjaan').select2();
		$('#dhkp').select2();
	})

	$('#cktp1').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_dukcapil',
			data: 'nik='+$('#ktp1').val(),
			async: 		false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama1").val(response.content[0].NAMA_LGKP);
						$("#ttl1").val(response.content[0].TMPT_LHR);
						$("#tgl1").val(response.content[0].TGL_LHR);
						$("#alamat1").val(response.content[0].ALAMAT);
						$("#rt1").val(response.content[0].NO_RT);
						$("#rw1").val(response.content[0].NO_RW);
						$("#kec1").val(response.content[0].KEC_NAME);
						$("#kel1").val(response.content[0].KEL_NAME);
						$("#kab1").val(response.content[0].KAB_NAME);
						var agama = response.content[0].AGAMA;
						if(agama=='ISLAM'){
							agama=1;
						}else if(agama=='KRISTEN'){
							agama=2;
						}else if(agama=='KATHOLIK'){
							agama=3;
						}else if(agama=='BUDHA'){
							agama=4;
						}else if(agama=='HINDU'){
							agama=5;
						}else if(agama=='KONGHUCU'){
							agama=6;
						}
						$("#agama1").val(agama);

						$.ajax({
							type: 'GET',
							url: '<?php echo base_url();?>ajax/get_pekerjaan',
							data: 'kerja='+response.content[0].JENIS_PKRJN,
							//async: 		false,
							dataType: 'json',
							success: function(data) {
									$("#pekerjaan1").val(data.idpkr_pkr);
									$('#pekerjaan1').select2();
							}
						});
			}
		});
	});

	$('#cktp2').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_dukcapil',
			data: 'nik='+$('#ktp2').val(),
			async: 		false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama2").val(response.content[0].NAMA_LGKP);
						$("#ttl2").val(response.content[0].TMPT_LHR);
						$("#tgl2").val(response.content[0].TGL_LHR);
						$("#alamat2").val(response.content[0].ALAMAT);
						$("#rt2").val(response.content[0].NO_RT);
						$("#rw2").val(response.content[0].NO_RW);
						$("#kec2").val(response.content[0].KEC_NAME);
						$("#kel2").val(response.content[0].KEL_NAME);
						$("#kab2").val(response.content[0].KAB_NAME);
						var agama = response.content[0].AGAMA;
						if(agama=='ISLAM'){
							agama=1;
						}else if(agama=='KRISTEN'){
							agama=2;
						}else if(agama=='KATHOLIK'){
							agama=3;
						}else if(agama=='BUDHA'){
							agama=4;
						}else if(agama=='HINDU'){
							agama=5;
						}else if(agama=='KONGHUCU'){
							agama=6;
						}
						$("#agama2").val(agama);

						$.ajax({
							type: 'GET',
							url: '<?php echo base_url();?>ajax/get_pekerjaan',
							data: 'kerja='+response.content[0].JENIS_PKRJN,
							//async: 		false,
							dataType: 'json',
							success: function(data) {
									$("#pekerjaan2").val(data.idpkr_pkr);
									$('#pekerjaan2').select2();
							}
						});
			}
		});
	});

	$('#cktpin1').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_nikinternal',
			data: 'nik='+$('#ktp1').val(),
			async: false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama1").val(response.nma_pdk);
						$("#alamat1").val(response.almat_pdk);
						$("#ttl1").val(response.ttl_pdk);
						$("#tgl1").val(response.ttg_pdk);
						$("#rt1").val(response.rt_pdk);
						$("#rw1").val(response.rw_pdk);
						$("#kec1").val(response.kec_pdk);
						$("#kel1").val(response.kel_pdk);
						$("#kab1").val(response.kab_pdk);
						$("#agama1").val(response.agm_pdk);
						$("#pekerjaan1").val(response.idpeker_pdk);
						$("#anggota1").val(response.anggota_pdk);
						$("#status1").val(response.status_pdk);
						$("#domisili1").val(response.domisili_pdk);
			}
		});
	});

	$('#cktpin2').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_nikinternal',
			data: 'nik='+$('#ktp2').val(),
			async: false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama2").val(response.nma_pdk);
						$("#alamat2").val(response.almat_pdk);
						$("#ttl2").val(response.ttl_pdk);
						$("#tgl2").val(response.ttg_pdk);
						$("#rt2").val(response.rt_pdk);
						$("#rw2").val(response.rw_pdk);
						$("#kec2").val(response.kec_pdk);
						$("#kel2").val(response.kel_pdk);
						$("#kab2").val(response.kab_pdk);
						$("#agama2").val(response.agm_pdk);
						$("#pekerjaan2").val(response.idpeker_pdk);
						$("#anggota2").val(response.anggota_pdk);
						$("#status2").val(response.status_pdk);
						$("#domisili2").val(response.domisili_pdk);
			}
		});
	});

</script>
