<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
h4{
	border-bottom:1px solid #f0f0f0;
	padding:10px;
	font-weight: bold;
}

#penduduk,#sppt,#tanah{
	border:1px solid #dadada;
}

h4{
	color:#2980b9;
}
</style>
<div id='alert'></div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<?php if($this->uri->segment(2)=='edit'){ ?>
					<form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>">
					<?php }else{
						?><form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>?search=<?php echo $this->input->get('search'); ?>"><?php
					}?>
						<div class='col-sm-6'>
							<div id='penduduk' class='col-sm-12'>
								<h4>KTP</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-8'>
										<label>No.KTP</label> :
										<input type='text' name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$template['noktp_pdk']?>'>
										<span id='alertnik' style='color:#ff0000'>ktp harus 16 digit</span>
									</div>
									<div class='col-sm-4'>
										<label>Cek NIK</label> :<br>
										<input type='button' value='Cek' id='cekinternal' class='btn btn-warning'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<label>Dusun</label> :
									<select name='dusun' id='dusun' style='margin:5px' class='form-control'>
										<?php
										foreach ($dusun as $dd) {
											?><option value='<?=$dd['id_dsn']?>' <?php if($template['iddsn_gtra']==$dd['id_dsn']){echo 'selected';}?>><?=$dd['name_dsn']?></option><?php
										}
										 ?>
									</select>
								</div>
								<div class='form-group col-sm-6'>
									<label>No.PPPTR</label> :
									<input type='number' value='<?=$template['ppptr_gtra']?>' name='p3tr' id='ppptr' class='form-control' placeholder='no.PPPTR'>
									<span id='alertptr' style='color:#ff0000'>no.PPPTR sudah ada di sistem</span>
								</div>
								<div class='form-group col-sm-6'>
									<label>NIB</label> :
									<input type='text' value='<?=$template['nib_gtra']?>' name='nib' id='nib' class='form-control' placeholder='nomor nib'>
									<span id='alertnib' style='color:#ff0000'>nib sudah ada di sistem</span>
								</div>
								<div class='form-group col-sm-12'>
									<label>Nama</label> :
									<input type='text' value='<?=$template['nma_pdk']?>' name='nama' id='nama' class='form-control' placeholder='nama lengkap'>
								</div>
								<div id='cek'></div>
								<div class='form-inline col-sm-4'>
									<label>Tempat Lahir</label> :
									<input type='text' name='ttl' value='<?=$template['ttl_pdk']?>' id='ttl' placeholder='tempat lahir' style='margin:5px;width:150px' class='form-control'>
								</div>
								<div class='form-inline col-sm-4'>
									<label>Tanggal Lahir</label> :
									<input type='text' name='tgl' id='tgl' value='<?=$template['ttg_pdk']?>' style='margin:5px;width:150px' class='datepicker form-control'>
								</div>
								<div class='form-inline col-sm-6'>
									<label>Pekerjaan</label> :
									<select name='pekerjaan' id='pekerjaan' style='margin:5px' class='form-control'>
										<?php
										foreach ($pekerjaan as $dd) {
											?><option value='<?=$dd['idpkr_pkr']?>' <?php if($template['idpeker_pdk']==$dd['idpkr_pkr']){echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
										}
										 ?>
									</select>
								</div>
								<div class='form-group col-sm-6'>
									<label>Agama</label> :
									<select name='agama' id='agama' style='margin:5px' class='form-control'>
										<option <?php if($template['agm_pdk']==1){echo 'selected';}?> value='1'>Islam</option>
										<option <?php if($template['agm_pdk']==2){echo 'selected';}?> value='2'>Kristen</option>
										<option <?php if($template['agm_pdk']==3){echo 'selected';}?> value='3'>Katholik</option>
										<option <?php if($template['agm_pdk']==4){echo 'selected';}?> value='4'>Budha</option>
										<option <?php if($template['agm_pdk']==5){echo 'selected';}?> value='5'>Hindu</option>
										<option <?php if($template['agm_pdk']==6){echo 'selected';}?> value='6'>Konghucu</option>
									</select>
								</div>
								<div class='form-group col-sm-12'>
									<label>Alamat Lengkap</label> :
									<textarea name='alamat' id='alamat' class='form-control' placeholder='alamat lengkap'><?=$template['almat_pdk']?></textarea>
								</div>
								<div class='form-group col-sm-3'>
									<label>RT</label> :
									<input type='text' name='rt' id='rt' value='<?=$template['rt_pdk']?>' class='form-control' placeholder='RT'>
								</div>
								<div class='form-group col-sm-3'>
									<label>RW</label> :
									<input type='text' name='rw' id='rw' value='<?=$template['rw_pdk']?>' class='form-control' placeholder='RW'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Kelurahan</label> :
									<input type='text' name='kel' id='kel' value='<?=$template['kel_pdk']?>' class='form-control' placeholder='Kelurahan'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Kecamatan</label> :
									<input type='text' name='kec' id='kec' value='<?=$template['kec_pdk']?>' class='form-control' placeholder='Kecamatan'>
								</div>
								<div class='form-group col-sm-6'>
									<label>Kabupaten</label> :
									<input type='text' name='kab' id='kab' value='<?=$template['kab_pdk']?>' class='form-control' placeholder='Kabupaten'>
								</div>
								<div class='col-sm-6 form-group'>
									<label>No.KK</label> :
									<input type='text' name='kk' id='kk' class='form-control' value='<?=$template['nokk_pdk']?>' placeholder='no. kk'>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Nama Istri/Suami</label> :
									<input type='text' name='pasangan' id='pasangan' value='<?=$template['pasangan_pdk']?>' class='form-control' placeholder='nama istri/suami'>
								</div>
								<div class='col-sm-12 form-group'>
									<label>Nama Anak/Usia</label> :
									<div id='ajaxanak'>
									</div>
									<?php
									$sumanak =1;
									if($anak){
										foreach ($anak as $dd) {
											$sumanak++;
											?>
											<div id='anak<?=$sumanak?>' class='form-group row'><div class='col-sm-9'><input type='text' class='form-control input-sm' value='<?=$dd['nama_ank']?>' name='anak[]'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removeanak("<?=$sumanak?>")'>-</a></div></div>
											<?php
										}
										?>

										<?php
									} ?>
									<input type='hidden' id='sumanak' value='<?=$sumanak?>'>
									<div class="row" id='beforethisanak'>
										<div class='col-sm-8'>
											<a id='plusanak' class='btn btn-warning'>+</a>
										</div>
									</div>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Penghasilan Perbulan</label> :
									<input type='text' name='penghasilan' value='<?=$template['penghasilan_pdk']?>' id='penghasilan' class='form-control' placeholder='penghasilan perbulan'>
								</div>
							</div>
							<div id='sppt' class='col-sm-12'>
								<h4>Domisili</h4>
								<div class='col-sm-4 form-group'>
									<label>Desa/Kelurahan</label>
									<input type='text' class='form-control' placeholder='kelurahan domisili' value='<?=$template['domkel_pdk']?>' name='keldom'>
								</div>
								<div class='col-sm-4 form-group'>
									<label>Kecamatan</label>
									<input type='text' class='form-control' placeholder='kecamatan domisili' value='<?=$template['domkec_pdk']?>' name='kecdom'>
								</div>
								<div class='col-sm-4 form-group'>
									<label>Kabupaten</label>
									<input type='text' class='form-control' placeholder='kabupaten domisili' value='<?=$template['domkab_pdk']?>' name='kabdom'>
								</div>
							</div>
							<div id='sppt' class='col-sm-12'>
								<h4>SPPT</h4>
								<div id='morespt'>
									<div class='form-group'>
										<?php $nom=1; $selected='';
										if($sppt){
											foreach ($sppt as $db):
												$nom++;?>
												<div id='obj<?=$nom?>' class='row obj' style='width:100%'>
													<div class='col-sm-8'>
														<input type='text' class='form-control' readonly value='<?= createkodebpkad($block['idkel_blk']).''.$block['nama_blk'].''.$db['nosppt_dhkp'];?>'>
														<input type='hidden' name='dhkp[]' value='<?= $db['iddhkp_gtra']?>'>
													</div>
													<div class='col-sm-4'>
														<a class='btn btn-danger' onclick='removechild(<?=$nom?>)'>-</a>
													</div><br>
												</div>
											<?php
											$selected = $db['iddhkp_gtra'];
											endforeach;
										}?>
										<input type='hidden' id='sumspt' value='<?=$nom?>'>
										<div class='row' id='beforethis'>
											<div class='col-sm-8'>
												<select class='form-control dhkp' id='dhkp' name='dhkp[]'>
													<option value=''>Pilih No.SPPT</option>
													<?php foreach ($dhkp as $data) {
														?><option class='option' value='<?=$data['id_dhkp']?>' <?php if($data['id_dhkp']==$selected){echo 'selected';}?>><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
													}?>
												</select>
											</div>
											<div class='col-sm-4'>
												<a id='plus' class='btn btn-warning' style='margin-left:-22px'>+</a>
											</div>
									</div>
								</div>
							</div>
							<div id='ajaxarea'>
								<div class='form-group col-sm-12'>
									<label>No.SPPT / NOP</label> :
									<div class='form-inline'>
										<?php $nop = createkodebpkad($block['idkel_blk']).''.$block['nama_blk']; ?>
										<input type='text' disabled style='width:50%;float:left' class='form-control' value='<?= $nop?>'>
										<input type='text' name='sppt' style='width:40%;float:left' placeholder='no.sppt' class='form-control' id='sppt'>
										<!-- <button id='btn-cari' class='btn btn-warning'><i class="fa fa-search" aria-hidden="true"></i></button> -->
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<label>Nama Wajib Pajak</label> :
									<input type='text' name='nwp' id='nwp' class='form-control' placeholder='nama lengkap'>
								</div>
								<div class='form-inline col-sm-6'>
									<label>NJOP Rp.</label> :
									<input type='number' placeholder='nominal njop' id='njop' name='njop' class='form-control'>
								</div>
								<div class='form-inline col-sm-6'>
									<label>Luas Bumi</label> :
									<input type='number' placeholder='Luas SPPT' id='luassppt' name='luassppt' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Alamat Objek Pajak</label> :
									<textarea placeholder='alamat objek pajak' name='aop' id='aop' class='form-control'></textarea>
								</div>
								<div class='form-group col-sm-12'>
									<label>Alamat Wajib Pajak</label> :
									<textarea placeholder='alamat wajib pajak' name='awp' id='awp' class='form-control'></textarea>
								</div>
							</div>
								<div class='form-group col-sm-4'>
									<label>Blok</label> :
									<input type='text' name='nameblok' value='<?= $block['nama_blk'] ?>' disabled class='form-control'>
									<input type='hidden' name='blok' value='<?= $block['idblk_blk'] ?>' class='form-control'>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Batas-Batas Tanah</h4>
								<div class='form-group col-sm-12'>
									<label>Utara</label> :
									<input type='text' name='utara' value='<?=$template['utara_gtra']?>' placeholder='batas utara' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Timur</label> :
									<input type='text' name='timur'  value='<?=$template['timur_gtra']?>' placeholder='batas timur' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Selatan</label> :
									<input type='text' name='selatan'  value='<?=$template['selatan_gtra']?>' placeholder='batas selatan' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Barat</label> :
									<input type='text' name='barat' value='<?=$template['barat_gtra']?>'  placeholder='batas barat' class='form-control'>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div id='tanah' class='col-sm-12'>
								<h4>Tanah Garapan</h4>
								<div class='form-group col-sm-12'>
									<label>Dasar Penggarapan Tanah</label> :
									<select name='dasargarap' style='margin:5px' class='form-control'>
										<option value='SKT' <?php if($template['tanahdasar_gtra']=='SKT'){echo 'selected';}?>>SKT</option>
										<option value='SIM' <?php if($template['tanahdasar_gtra']=='SIM'){echo 'selected';}?>>SIM</option>
										<option value='Tol Lama' <?php if($template['tanahdasar_gtra']=='Tol Lama'){echo 'selected';}?>>TOL Lama</option>
										<option value='Surat Oper Alih Garapan' <?php if($template['tanahdasar_gtra']=='Surat Oper Alih Garapan'){echo 'selected';}?>>Surat Oper Alih Garapan</option>
										<option value='Surat Pernyataan Penguasaan Tanah' <?php if($template['tanahdasar_gtra']=='Surat Pernyataan Penguasaan Tanah'){echo 'selected';}?>>Surat Pernyataan Penguasaan Tanah</option>
										<option value='Surat Izin Membuka Hutan' <?php if($template['tanahdasar_gtra']=='Surat Izin Membuka Hutan'){echo 'selected';}?>>Surat Izin Membuka Hutan</option>
										<option value='SK Pelepasan Kawasan Hutan' <?php if($template['tanahdasar_gtra']=='SK Pelepasan Kawasan Hutan'){echo 'selected';}?>>SK Pelepasan Kawasan Hutan</option>
										<option value='SK Pelepasan HGU' <?php if($template['tanahdasar_gtra']=='SK Pelepasan HGU'){echo 'selected';}?>>SK Pelepasan HGU</option>
										<option value='SK Tanah Terlantar' <?php if($template['tanahdasar_gtra']=='SK Tanah Terlantar'){echo 'selected';}?>>SK Tanah Terlantar</option>
										<option value='SK Pelepasan Aset' <?php if($template['tanahdasar_gtra']=='SK Pelepasan Aset'){echo 'selected';}?>>SK Pelepasan Aset</option>
										<option value='Lainnya'>Lainnya</option>
										<option value='Tidak Ada' <?php if($template['tanahdasar_gtra']=='Tidak Ada'){echo 'selected';}?>>Tidak Ada</option>
									</select>
									<input name='dasargaraplain' placeholder='untuk lain-lain' class='form-control lain' type='text'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Sumber Tanah</label> :
									<select name='sumbertanah' style='margin:5px' class='form-control'>
										<option value='Tol Lama' <?php if($template['tanahsumber_gtra']=='Tol Lama'){echo 'selected';}?>>Tol Lama</option>
										<option value='Bekas HGU' <?php if($template['tanahsumber_gtra']=='Bekas HGU'){echo 'selected';}?>>Bekas HGU</option>
										<option value='Pelepasan Kawasan Hutan' <?php if($template['tanahsumber_gtra']=='Pelepasan Kawasan Hutan'){echo 'selected';}?>>Pelepasan Kawasan Hutan</option>
										<option value='Tanah Terlantar' <?php if($template['tanahsumber_gtra']=='Tanah Terlantar'){echo 'selected';}?>>Tanah Terlantar</option>
										<option value='Tanah Adat' <?php if($template['tanahsumber_gtra']=='Tanah Adat'){echo 'selected';}?>>Tanah Adat</option>
										<option value='Tanah Negara lainnya' <?php if($template['tanahsumber_gtra']=='Tanah Negara lainnya'){echo 'selected';}?>>Tanah Negara lainnya</option>
									</select>
								</div>
								<div class='form-group col-sm-12'>
									<label>Luas</label> :
									<input type='text' name='luas' value='<?=$template['luastanah_gtra']?>' placeholder='luas' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Penggunaan Tanah</label> :
									<select name='gunatanah' style='margin:5px' class='form-control'>
										<option value='Lahan Sawah' <?php if($template['gunatanah_gtra']=='Lahan Sawah'){echo 'selected';}?>>Lahan Sawah</option>
										<option value='Lahan Tambak' <?php if($template['gunatanah_gtra']=='Lahan Tambak'){echo 'selected';}?>>Lahan Tambak</option>
										<option value='Lahan Kering' <?php if($template['gunatanah_gtra']=='Lahan Kering'){echo 'selected';}?>>Lahan Kering</option>
										<option value='Pemukiman' <?php if($template['gunatanah_gtra']=='Pemukiman'){echo 'selected';}?>>Pemukiman</option>
										<option value='Lainnya'>Lainnya</option>
									</select>
									<input name='gunatanahlain' placeholder='untuk lain-lain' class='form-control lain' type='text'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Pemanfaatan Tanah</label> :
									<select name='manfaattanah' style='margin:5px' class='form-control'>
										<option value='Sawah .......x Padi' <?php if($template['manfaattanah_gtra']=='Sawah .......x Padi'){echo 'selected';}?>>Sawah .......x Padi</option>
										<option value='Tegalan' <?php if($template['manfaattanah_gtra']=='Tegalan'){echo 'selected';}?>>Tegalan</option>
										<option value='Kebun Campuran' <?php if($template['manfaattanah_gtra']=='Kebun Campuran'){echo 'selected';}?>>Kebun Campuran</option>
										<option value='Rumah Tinggal' <?php if($template['manfaattanah_gtra']=='Rumah Tinggal'){echo 'selected';}?>>Rumah Tinggal</option>
										<option value='Lainnya'>Lainnya</option>
									</select>
									<input name='manfaattanahlain'  placeholder='untuk lain-lain' class='form-control lain' type='text'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Nilai Tanah saat ini</label> :
									<input type='text' name='nilai' value='<?=$template['nilaitanah_gtra']?>' placeholder='harg per m2' class='form-control'>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Penguasaan Tanah Garapan</h4>
								<div class='form-group col-sm-12'>
									<label>Cara Pengusahaan</label> :
									<select name='kuasacara' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['kuasacara_gtra']=='1'){echo 'selected';}?>>Sendiri</option>
										<option value='2' <?php if($template['kuasacara_gtra']=='2'){echo 'selected';}?>>Pihak Lain</option>
									</select>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Tanaman dominan yang ada</label>
									<input type='text' class='form-control' value='<?=$template['tanamankuasa_gtra']?>' placeholder='tanaman dominan' name='tanaman'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Peruntukan & Penggunaan Tanah saat ini</label> :
									<select name='gunakuasa' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['kuasacara_gtra']=='1'){echo 'selected';}?>>Pertanian</option>
										<option value='2' <?php if($template['kuasacara_gtra']=='2'){echo 'selected';}?>>Non Pertanian</option>
										<option value='3' <?php if($template['kuasacara_gtra']=='3'){echo 'selected';}?>>Lain-lain</option>
									</select>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Tahun Penggarapan</label>
									<input type='number' class='form-control' value='<?=$template['tahunkuasa_gtra']?>' placeholder='tahun penggrapan' name='tahungarap'>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Dasar Penguasaan Tanah</label>
									<input type='text' class='form-control' value='<?=$template['dasarkuasa_gtra']?>' placeholder='dasar penguasaan tanah garapan' name='dasarkuasa'>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Lain-lain</h4>
								<div class='col-sm-12'>
									<label>Tanah yang telah dimiliki</label> :
								</div>
								<div class='col-sm-6 form-group'>
									<label>Luas</label>
									<input type='text' value='<?=$template['laintanahluas_gtra']?>' class='form-control' placeholder='luas ... m2' name='tanahluas'>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Bidang</label>
									<input type='text' class='form-control' placeholder='banyak bidang' value='<?=$template['laintanahbidang_gtra']?>' name='tanahbidang'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Terletak di :</label> :
									<textarea name='alamatletak' class='form-control'><?=$template['lainletak_gtra']?></textarea>
								</div>
								<div class='col-sm-4 form-group'>
									<label>Desa/Kelurahan</label>
									<input type='text' class='form-control' placeholder='desa' value='<?=$template['laindesa_gtra']?>' name='desa'>
								</div>
								<div class='col-sm-4 form-group'>
									<label>Kecamatan</label>
									<input type='text' class='form-control' placeholder='kecamatan' value='<?=$template['lainkecamatan_gtra']?>' name='kecamatan'>
								</div>
								<div class='col-sm-4 form-group'>
									<label>Kabupaten</label>
									<input type='text' class='form-control' placeholder='kabupaten' value='<?=$template['lainkabupaten_gtra']?>' name='kabupaten'>
								</div>
								<div class='col-sm-12'>
									<label>Luas Tanah yang digarap + luas tanah telah dimiliki</label> :
								</div>
								<div class='col-sm-6 form-group'>
									<label>Luas</label>
									<input type='text' class='form-control' placeholder='luas ... m2' value='<?=$template['lainluasgarap_gtra']?>' name='garapluas'>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Bidang</label>
									<input type='text' class='form-control' placeholder='banyak bidang' value='<?=$template['lainbidanggarap_gtra']?>' name='garapbidang'>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Berkas</h4>
								<div class='form-group col-sm-12'>
									<label>Upload KTP,KK dan SPPT</label>
									<?php
									$no=0;
									if($berkas){
									foreach ($berkas as $bk) {
										$no++;
										?><div class='row' id='files<?=$bk['id_pbk']?>'>
											<div class='col-sm-12'>
												Berkas <?=$no?>
											</div>
											<div class='col-sm-8'><?php
												if(isset($bk['berkas_pbk']) && file_exists('./DATA/BERKASGTRA/'.$bk['berkas_pbk'])){
													?>
													<embed style="width:200px" src='<?= base_url()?>DATA/BERKASGTRA/<?= $bk['berkas_pbk'];?>'>
													<?php
												}
												?>
											</div>
										<div class='col-sm-4'>
											<a class='btn btn-danger' onclick='removedata(<?=$bk['id_pbk']?>,"berkasgtra")'>x</a>
										</div>
									</div>

									<?php
								}
							}
								 ?>
							<input type='hidden' id='sumfile' value='1'>
							<div class="row" id='beforethisfile'>
								<div class='col-sm-8'>
									<a id='plusfile' class='btn btn-warning'>+</a>
								</div>
							</div>
							</div>
						</div>
						<div id='tanah' class='col-sm-12'>
							<h4>Sketsa</h4>
							<div class='form-group col-sm-12'>
								<label>Gambar Sketsa</label>
								<?php
								if($template['skets_gtra']){
									?><img style='width:200px' src='<?=base_url()?>Skets/<?=$template['skets_gtra']?>'><?php
								}
								?>
								<input type='file' name='skets'>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-right">
									<?php if(strtolower($this->uri->segment(1))!='ajax'){ ?>
									<button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
									<?php } ?>
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

	$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/cari_dhkp',
			data: 'id='+$('#dhkp').val(),
			dataType: 'html',
			beforeSend: function() {
			},
			success: function(response) {
					$("#ajaxarea").html(response);
			}
	});

	removechild(<?=$nom?>);
})

	$(document).ready(function () {
		$('#pekerjaan').select2();
		$('.dhkp').select2();
		$('.lain').hide();
		$('#alertnib').hide();
		$('#alertptr').hide();
		$('#alertnik').hide();
	})

	$('.dhkp').on('change',function () {
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cari_dhkp',
				data: 'id='+$(this).val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						$("#ajaxarea").html(response);
				}
		});
	});

	$('#plus').on('click',function () {
		var sel = document.getElementById('dhkp');
		var opt = sel.options[sel.selectedIndex];
		var id = $('#sumspt').val();
		var text = "<div id='obj"+id+"' class='row obj' style='width:100%'><div class='col-sm-8'><input type='text' class='form-control' readonly value='"+opt.text+"'><input type='hidden' name='dhkp[]' value='"+opt.value+"'></div><div class='col-sm-4'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div><br></div>"
		$('#sumspt').val(id+1);
		$(text).insertBefore($('#beforethis'));
	});

	$('#plusfile').on('click',function () {
		var id = $('#sumfile').val();
		id = parseInt(id)+1;
		var name = $('#nameform').val();
		var text = "<div id='file"+id+"' class='form-group row'><div class='col-sm-9'><input type='file' class='form-control input-sm' name='berkas[]'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removefile("+id+")'>-</a></div></div>"
		$(text).insertBefore($('#beforethisfile'));
		$('#sumfile').val(id);
	});

	$('#plusanak').on('click',function () {
		var id = $('#sumanak').val();
		id = parseInt(id)+1;
		var text = "<div id='anak"+id+"' class='form-group row'><div class='col-sm-9'><input type='text' class='form-control input-sm' placeholder='nama / usia' name='anak[]'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removeanak("+id+")'>-</a></div></div>"
		$(text).insertBefore($('#beforethisanak'));
		$('#sumanak').val(id);
	});

	function removechild(id){
		$('#obj'+id).remove();
	}
	function removeanak(id){
		$('#anak'+id).remove();
	}

	function removefile(id){
		$('#file'+id).remove();
	}

	function removedata(id,jenis){
		cek = confirm('Apakah kamu yakin mau menghapus berkas ?');
		if(cek==true){
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/hapus_berkas/'+jenis,
					data: 'id='+id,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$('#files'+id).remove();
					}
			});
		}else{
			return false;
		}
	}

	$('#nib').keyup(function () {
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cek_data/nib',
				data: 'id='+$(this).val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						if(response){
								$('#alertnib').show();
						}else{
								$('#alertnib').hide();
						}
				}
		});
	});

	$('#ktp').keyup(function () {
			ktp = $(this).val();
			// alert(ktp.length);
			if(ktp.length!=16){
					$('#alertnik').show();
			}else{
					$('#alertnik').hide();
			}
	});

	$('#ppptr').keyup(function () {
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cek_data/ppptr',
				data: 'id='+$(this).val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						if(response){
								$('#alertptr').show();
						}else{
								$('#alertptr').hide();
						}
				}
		});
	});
	$('#cekinternal').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_nikinternal',
			data: 'nik='+$('#ktp').val(),
			async: 		false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama").val(response.nma_pdk);
						$("#ttl").val(response.ttl_pdk);
						$("#tgl").val(response.ttg_pdk);
						$("#alamat").val(response.almat_pdk);
						$("#rt").val(response.rt_pdk);
						$("#rw").val(response.rw_pdk);
						$("#kec").val(response.kec_pdk);
						$("#kel").val(response.kel_pdk);
						$("#kab").val(response.kab_pdk);
						$("#kk").val(response.nokk_pdk);
						$("#pasangan").val(response.pasangan_pdk);
						$("#pekerjaan").val(response.idpeker_pdk);
						$('#pekerjaan').select2();

						var agama = response.agm_pdk;
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
						$("#agama").val(agama);
			}
		});

		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_anak',
			data: 'nik='+$('#ktp').val(),
			dataType: 'html',
			success: function(data) {
					$("#ajaxanak").html(data);
			}
		});

	});

	$('#cktp').click(function () {
		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_dukcapil',
			data: 'nik='+$('#ktp').val(),
			async: 		false,
			dataType: 'json',
			success: function(response) {
					console.log(response);
						$("#nama").val(response.content[0].NAMA_LGKP);
						$("#ttl").val(response.content[0].TMPT_LHR);
						$("#tgl").val(response.content[0].TGL_LHR);
						$("#alamat").val(response.content[0].ALAMAT);
						$("#rt").val(response.content[0].NO_RT);
						$("#rw").val(response.content[0].NO_RW);
						$("#kec").val(response.content[0].KEC_NAME);
						$("#kel").val(response.content[0].KEL_NAME);
						$("#kab").val(response.content[0].KAB_NAME);
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
						$("#agama").val(agama);

						$.ajax({
							type: 'GET',
							url: '<?php echo base_url();?>ajax/get_pekerjaan',
							data: 'kerja='+response.content[0].JENIS_PKRJN,
							//async: 		false,
							dataType: 'json',
							success: function(data) {
									$("#pekerjaan").val(data.idpkr_pkr);
									$('#pekerjaan').select2();
							}
						});
			}
		});

		$.ajax({
			type: 'GET',
			url: '<?php echo base_url();?>ajax/get_anak',
			data: 'nik='+$('#ktp').val(),
			dataType: 'json',
			success: function(data) {
					$("#ajaxanak").html(data);
			}
		});

	});



</script>
