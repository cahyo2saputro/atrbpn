<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
h4{
	border-bottom:1px solid #f0f0f0;
	padding:10px;
	font-weight: bold;
}
#penduduk,#sppt,#penggunaan,#tanah,#pengukuran{
	border:1px solid #dadada;
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
							<div id='penduduk' class='col-sm-12'>
								<h4>IDENTITAS PEMILIK</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-4 form-inline'>
										<input type='button' value='Tambahkan Data Penduduk baru tanpa nik' id='newnik' class='btn btn-primary'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>No.KTP</label>
										</div>
										<div class='col-sm-6'>
 											<input type='text' name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$template['noktp_pdk'];?>'>
										</div>
										<div class='col-sm-4 form-inline'>
											<input type='button' value='Cek NIK' id='cekinternal' class='btn btn-warning'>
										</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Nama</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='nama' id='nama' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($template['nma_pdk']);?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Tempat Lahir</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='ttl' id='ttl' placeholder='tempat lahir' class='form-control' value='<?= $template['ttl_pdk']?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Tanggal Lahir</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control' value='<?= ftanggal($template['ttg_pdk'],'DDMMYYYY')?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Pekerjaan</label>
									</div>
									<div class='col-sm-6'>
										<select name='pekerjaan' id='pekerjaan' style='margin:5px' class='form-control'>
											<?php
											foreach ($pekerjaan as $dd) {
												?><option value='<?=$dd['idpkr_pkr']?>' <?php if($template['idpeker_pdk']==$dd['idpkr_pkr']){ echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
											}
											 ?>
										</select>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Agama</label>
									</div>
									<div class='col-sm-6'>
										<select name='agama' id='agama' style='margin:5px' class='form-control'>
											<option value='1' <?php if($template['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
											<option value='2' <?php if($template['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
											<option value='3' <?php if($template['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
											<option value='4' <?php if($template['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
											<option value='5' <?php if($template['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
											<option value='6' <?php if($template['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
										</select>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Alamat Lengkap</label>
									</div>
									<div class='col-sm-6'>
										<textarea name='alamat' id='alamat' class='form-control' placeholder='alamat lengkap'><?= $template['almat_pdk']?></textarea>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>RT</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='rt' id='rt' class='form-control' placeholder='RT' value="<?= $template['rt_pdk'];?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>RW</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='rw' id='rw' class='form-control' placeholder='RW' value="<?= $template['rw_pdk'];?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kelurahan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kel' id='kel' class='form-control' placeholder='Kelurahan' value="<?= $template['kel_pdk'];?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kecamatan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kec' id='kec' class='form-control' placeholder='Kecamatan' value="<?= $template['kec_pdk'];?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kabupaten</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kab' id='kab' class='form-control' placeholder='Kabupaten' value="<?= $template['kab_pdk'];?>">
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>No.Telp</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='notelp' id='notelp' class='form-control' placeholder='nomor telepon' value="<?= $template['notelp_pdk'];?>">
									</div>
								</div>
							</div>
							<div id='sppt' class='col-sm-12'>
								<h4>SPPT</h4>
								<div class='form-group'>
									<?php $nom=0; $selected='';
									foreach ($sppt as $db):
										$nom++;?>
										<div id='obj<?=$nom?>' class='row obj' style='width:100%'>
											<div class='col-sm-8'>
												<input type='text' class='form-control' readonly value='<?= createkodebpkad($block['idkel_blk']).''.$block['nama_blk'].''.$db['nosppt_dhkp'];?>'>
												<input type='hidden' name='dhkp[]' value='<?= $db['iddhkp_ptsl']?>'>
											</div>
											<div class='col-sm-4'>
												<a class='btn btn-danger' onclick='removechild(<?=$nom?>)'>-</a>
											</div><br>
										</div>
									<?php
									$selected = $db['iddhkp_ptsl'];
								 	endforeach; ?>
									<input type='hidden' id='sumspt' value='<?= $nom;?>'>
									<div class='row' id='beforethis'>
										<div class='col-sm-8'>
										<select class='form-control' id='dhkp' name='dhkp[]'>
											<option value=''>Pilih No.SPPT</option>
											<?php foreach ($dhkp as $data) {
												?><option value='<?=$data['id_dhkp']?>' <?php if($data['id_dhkp']==$selected){echo 'selected';}?>><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
											}?>
										</select>
									</div>
										<div class='col-sm-4'>
											<a id='plus' class='btn btn-warning' style='margin-left:-22px'>+</a>
										</div>
								</div>
								</div>
								<div id='areaaddnop' class='form-group col-sm-12'>
									<div class='col-sm-2'>
									</div>
									<div class='col-sm-6'>
										<a style='cursor:pointer' id='addnop' class='btn btn-info'>Tambahkan SPPT Baru</a>
									</div>
								</div>
							<div id='ajaxarea'>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>No.SPPT</label>
									</div>
									<div class='col-sm-6 form-inline'>
										<?php $nop = createkodebpkad($block['idkel_blk']).''.$block['nama_blk']; ?>
										<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $nop?>'>
										<input type='text' id='nosppt' name='sppt' style='width:40%;float:left' placeholder='no.sppt' class='form-control' value='<?=$template['nosppt_ptsl'];?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>NJOP Rp.</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' placeholder='nominal njop' value='<?=$template['njop_ptsl'];?>' id='njop' name='njop' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Luas Bumi</label>
									</div>
									<div class='col-sm-6'>
											<input type='text' placeholder='Luas SPPT' name='luassppt' id='luassppt' value='<?=$template['luassppt_ptsl'];?>' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Alamat Objek Pajak</label>
									</div>
									<div class='col-sm-6'>
										<textarea placeholder='alamat objek pajak' name='aop' id='aop' class='form-control'><?=$template['aopsppt_ptsl'];?></textarea>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Alamat Wajib Pajak</label>
									</div>
									<div class='col-sm-6'>
										<textarea placeholder='alamat wajib pajak' id='awp' name='awp' class='form-control'><?=$template['awpsppt_ptsl'];?></textarea>
									</div>
								</div>
							</div>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Blok</label>
								</div>
								<div class='col-sm-6'>
									<input type='hidden' readonly name='templateid' value='<?= $template['id_ptsl'] ?>'>
									<input type='text' name='nameblok' value='<?= $block['nama_blk'] ?>' disabled class='form-control'>
									<input type='hidden' id='idblock' name='blok' value='<?= $block['idblk_blk'] ?>' class='form-control'>
								</div>
							</div>
							<div id='areasavenop' class='form-group col-sm-12'>
								<div class='col-sm-2'>
								</div>
								<div class='col-sm-6'>
									<a style='cursor:pointer' id='savenop' class='btn btn-primary'>Simpan SPPT Baru</a>
								</div>
							</div>
						</div>
						<div id='pengukuran' class='col-sm-12'>
							<h4>PENGGUNAAN DAN PEMANFAATAN</h4>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Penggunaan</label>
								</div>
								<div class='col-sm-6'>
									<select name='guna' class='form-control'>
										<option value='1' <?php if($template['idguna_ptsl']=='1'){echo 'selected';}?>>Pertanian</option>
										<option value='2' <?php if($template['idguna_ptsl']=='2'){echo 'selected';}?>>Non Pertanian</option>
									</select>
								</div>
							</div>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Pemanfaatan</label>
								</div>
								<div class='col-sm-6'>
									<select name='manfaat' class='form-control'>
										<option value='1'<?php if($template['idmanfaat_ptsl']=='1'){echo 'selected';}?>>Perumahan</option>
										<option value='2'<?php if($template['idmanfaat_ptsl']=='2'){echo 'selected';}?>>Pekarangan</option>
										<option value='3'<?php if($template['idmanfaat_ptsl']=='3'){echo 'selected';}?>>Sawah</option>
										<option value='4'<?php if($template['idmanfaat_ptsl']=='4'){echo 'selected';}?>>Ladang/Tegalan</option>
										<option value='5'<?php if($template['idmanfaat_ptsl']=='5'){echo 'selected';}?>>Kebun/Kebun Campuran</option>
										<option value='6'<?php if($template['idmanfaat_ptsl']=='6'){echo 'selected';}?>>Kolam Ikan</option>
										<option value='7'<?php if($template['idmanfaat_ptsl']=='7'){echo 'selected';}?>>Industri</option>
										<option value='8'<?php if($template['idmanfaat_ptsl']=='8'){echo 'selected';}?>>Perkebunan</option>
										<option value='9'<?php if($template['idmanfaat_ptsl']=='9'){echo 'selected';}?>>Dikelola Pengembang</option>
										<option value='10'<?php if($template['idmanfaat_ptsl']=='10'){echo 'selected';}?>>Lapangan Umum</option>
										<option value='11'<?php if($template['idmanfaat_ptsl']=='11'){echo 'selected';}?>>Peternakan</option>
										<option value='13'<?php if($template['idmanfaat_ptsl']=='13'){echo 'selected';}?>>Jalan</option>
										<option value='12'<?php if($template['idmanfaat_ptsl']=='12'){echo 'selected';}?>>Tidak dimanfaatkan</option>
									</select>
								</div>
							</div>
						</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Batas-Batas Tanah</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Utara</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='utara' placeholder='batas utara' value='<?=$template['utara_ptsl'];?>' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Timur</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='timur' placeholder='batas timur' value='<?=$template['timur_ptsl'];?>' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Selatan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='selatan' placeholder='batas selatan' value='<?=$template['selatan_ptsl'];?>' class='form-control'>
									</div>
								</div>

								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Barat</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='barat' placeholder='batas barat' value='<?=$template['barat_ptsl'];?>' class='form-control'>
									</div>
								</div>
							</div>
						</div>
						<div class='col-sm-12'>
							<div id='tanah' class='col-sm-12'>
								<h4>Alas Hak Pendaftaran</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Nomor C</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dc' onkeyup='descfill()' id='dc' class='form-control' value='<?=$template['dc_ptsl'];?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Persil</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dpersil' onkeyup='descfill()' id='dpersil' class='form-control' value='<?=$template['dpersil_ptsl'];?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Klas</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dklas' onkeyup='descfill()' id='dklas' class='form-control' value='<?=$template['dklas_ptsl'];?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Luas</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dluas' onkeyup='descfill()' id='dluas' class='form-control' value='<?=$template['dluas_ptsl'];?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>diperoleh dari</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='ddari' onkeyup='descfill()' id='ddari' class='form-control' value='<?=$template['ddari_ptsl'];?>'>
									</div>
								</div>
								<h4>Riwayat Tanah</h4>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' id='desc0' name='des0' placeholder='deskripsi 0' style='width:100% !important'><?=$template['desc0_ptsl'];?></textarea>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">
							    Riwayat 1
							  </a>
								<div class="collapse <?php if($template['thn_ptsl']){echo 'in';}?>" id="collapse1">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan' id='dkeperluan1' onchange='riwayatfill(1)' style='margin:5px' class='form-control'>
											<option value='1' <?php if($template['idkperluan_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
											<option value='2' <?php if($template['idkperluan_ptsl']=='2'){echo 'selected';}?>>Waris</option>
											<option value='3' <?php if($template['idkperluan_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
											<option value='4' <?php if($template['idkperluan_ptsl']=='4'){echo 'selected';}?>>Wakaf</option>
											<option value='5' <?php if($template['idkperluan_ptsl']=='5'){echo 'selected';}?>>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun' onkeyup='riwayatfill(1)' id='dtahun1' style='width:70px;margin:5px' value='<?=$template['thn_ptsl'];?>' class='form-control'>
										<label>Atas Nama</label>
										<input type='text' name='dnama' onkeyup='riwayatfill(1)' id='dnama1' style='width:200px;margin:5px' value='<?=$template['atasnama_ptsl'];?>' class='form-control'>
										<label>Luas</label>
										<input type='text' name='dluas1' onkeyup='riwayatfill(1)' id='dluas1' style='width:70px;margin:5px' value='<?=$template['dluas1_ptsl'];?>' class='form-control'>
										<label>Letter C</label>
										<input type='text' name='dc1' onkeyup='riwayatfill(1)' id='dc1' style='width:70px;margin:5px' value='<?=$template['dc1_ptsl'];?>' class='form-control'>
									</div>
									<div class='form-group col-sm-12'>
										<textarea style='min-height:100px' class='form-control' id='des1' name='des1' placeholder='deskripsi 1' style='width:100% !important'><?=$template['desc1_ptsl'];?></textarea>
									</div>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">
							    Riwayat 2
							  </a>
								<div class="collapse <?php if($template['thn2_ptsl']){echo 'in';}?>" id="collapse2">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan2' id='dkeperluan2' onchange='riwayatfill(2)' style='margin:5px' class='form-control'>
											<option value='1' <?php if($template['idkperluan2_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
											<option value='2' <?php if($template['idkperluan2_ptsl']=='2'){echo 'selected';}?>>Waris</option>
											<option value='3' <?php if($template['idkperluan2_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
											<option value='4' <?php if($template['idkperluan2_ptsl']=='4'){echo 'selected';}?>>Wakaf</option>
											<option value='5' <?php if($template['idkperluan2_ptsl']=='5'){echo 'selected';}?>>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun2' onkeyup='riwayatfill(2)' id='dtahun2' value='<?=$template['thn2_ptsl'];?>' style='width:70px;margin:5px' class='form-control'>
										<label>Atas Nama</label>
										<input type='text' name='dnama2' onkeyup='riwayatfill(2)' id='dnama2' style='width:200px;margin:5px' value='<?=$template['atasnama2_ptsl'];?>' class='form-control'>
										<label>Luas</label>
										<input type='text' name='dluas2' onkeyup='riwayatfill(2)' id='dluas2' style='width:70px;margin:5px' value='<?=$template['dluas2_ptsl'];?>' class='form-control'>
										<label>Letter C</label>
										<input type='text' name='dc2' onkeyup='riwayatfill(2)' id='dc2' style='width:70px;margin:5px' value='<?=$template['dc2_ptsl'];?>' class='form-control'>
									</div>
									<div class='form-group col-sm-12'>
										<textarea style='min-height:100px' class='form-control' id='des2' name='des2' placeholder='deskripsi 2' style='width:100% !important'><?=$template['desc2_ptsl'];?></textarea>
									</div>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">
							    Riwayat 3
							  </a>
								<div class="collapse <?php if($template['thn3_ptsl']){echo 'in';}?>" id="collapse3">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan3' id='dkeperluan3' onchange='riwayatfill(3)' style='margin:5px' class='form-control'>
											<option value='1' <?php if($template['idkperluan3_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
											<option value='2' <?php if($template['idkperluan3_ptsl']=='2'){echo 'selected';}?>>Waris</option>
											<option value='3' <?php if($template['idkperluan3_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
											<option value='4' <?php if($template['idkperluan3_ptsl']=='4'){echo 'selected';}?>>Wakaf</option>
											<option value='5' <?php if($template['idkperluan3_ptsl']=='5'){echo 'selected';}?>>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun3' onkeyup='riwayatfill(3)' id='dtahun3' style='width:70px;margin:5px' value='<?=$template['thn3_ptsl'];?>' class='form-control'>
										<label>Atas Nama</label>
										<input type='text' name='dnama3' onkeyup='riwayatfill(3)' id='dnama3' style='width:200px;margin:5px' value='<?=$template['atasnama3_ptsl'];?>' class='form-control'>
										<label>Luas</label>
										<input type='text' name='dluas3' onkeyup='riwayatfill(3)' id='dluas3' style='width:70px;margin:5px' value='<?=$template['dluas3_ptsl'];?>' class='form-control'>
										<label>Letter C</label>
										<input type='text' name='dc3' onkeyup='riwayatfill(3)' id='dc3' style='width:70px;margin:5px' value='<?=$template['dc3_ptsl'];?>' class='form-control'>
									</div>
									<div class='form-group col-sm-12'>
										<textarea style='min-height:100px' class='form-control' id='des3' name='des3' placeholder='deskripsi 3' style='width:100% !important'><?=$template['desc3_ptsl'];?></textarea>
									</div>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse4" role="button" aria-expanded="false" aria-controls="collapse4">
							    Riwayat 4
							  </a>
								<div class="collapse <?php if($template['thn4_ptsl']){echo 'in';}?>" id="collapse4">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan4' id='dkeperluan4' onchange='riwayatfill(4)' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['idkperluan4_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
											<option value='2' <?php if($template['idkperluan4_ptsl']=='2'){echo 'selected';}?>>Waris</option>
											<option value='3' <?php if($template['idkperluan4_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
											<option value='4' <?php if($template['idkperluan4_ptsl']=='4'){echo 'selected';}?>>Wakaf</option>
											<option value='5' <?php if($template['idkperluan4_ptsl']=='5'){echo 'selected';}?>>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun4' onkeyup='riwayatfill(4)' id='dtahun4' style='width:70px;margin:5px' value='<?=$template['thn4_ptsl'];?>' class='form-control'>
										<label>Atas Nama</label>
										<input type='text' name='dnama4' onkeyup='riwayatfill(4)' id='dnama4' style='width:200px;margin:5px' value='<?=$template['atasnama4_ptsl'];?>' class='form-control'>
										<label>Luas</label>
										<input type='text' name='dluas4' onkeyup='riwayatfill(4)' id='dluas4' style='width:70px;margin:5px' value='<?=$template['dluas4_ptsl'];?>' class='form-control'>
										<label>Letter C</label>
										<input type='text' name='dc4' onkeyup='riwayatfill(4)' id='dc4' style='width:70px;margin:5px' value='<?=$template['dc4_ptsl'];?>' class='form-control'>
									</div>
									<div class='form-group col-sm-12'>
										<textarea style='min-height:100px' class='form-control' id='des4' name='des4' placeholder='deskripsi 4' style='width:100% !important'><?=$template['desc4_ptsl'];?></textarea>
									</div>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5">
							    Riwayat 5
							  </a>
								<div class="collapse <?php if($template['thn5_ptsl']){echo 'in';}?>" id="collapse5">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan5' id='dkeperluan5' onchange='riwayatfill(5)' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['idkperluan5_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
											<option value='2' <?php if($template['idkperluan5_ptsl']=='2'){echo 'selected';}?>>Waris</option>
											<option value='3' <?php if($template['idkperluan5_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
											<option value='4' <?php if($template['idkperluan5_ptsl']=='4'){echo 'selected';}?>>Wakaf</option>
											<option value='5' <?php if($template['idkperluan5_ptsl']=='5'){echo 'selected';}?>>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun5' onkeyup='riwayatfill(5)' id='dtahun5' style='width:100px;margin:5px' value='<?=$template['thn5_ptsl'];?>' class='form-control'>
										<label>Atas Nama</label>
										<input type='text' name='dnama5' onkeyup='riwayatfill(5)' id='dnama5' style='width:200px;margin:5px' value='<?=$template['atasnama5_ptsl'];?>' class='form-control'>
										<label>Luas</label>
										<input type='text' name='dluas5' onkeyup='riwayatfill(5)' id='dluas5' style='width:70px;margin:5px' value='<?=$template['dluas5_ptsl'];?>' class='form-control'>
										<label>Letter C</label>
										<input type='text' name='dc5' onkeyup='riwayatfill(5)' id='dc5' style='width:70px;margin:5px' value='<?=$template['dc5_ptsl'];?>' class='form-control'>
									</div>
									<div class='form-group col-sm-12'>
										<textarea style='min-height:100px' class='form-control' id='des5' name='des5' placeholder='deskripsi 5' style='width:100% !important'><?=$template['desc5_ptsl'];?></textarea>
									</div>
							</div>
							<br><br>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Tahun Risalah (poin 3a DI 201)</label>
								</div>
								<div class='col-sm-6'>
									<input type='number' class='form-control' placeholder='tahun risalah' value='<?= $template['thn_risalah']?>' name='thn_risalah'>
								</div>
							</div>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Catatan</label>
								</div>
								<div class='col-sm-6'>
									<textarea class='form-control' placeholder='catatan' name='note'><?=$template['note_ptsl'];?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class='col-sm-12'>
							<div id='tanah' class='col-sm-12'>
								<h4>Upload KTP,KK, SPPT-PBB dan Letter C</h4>
								<span style='color:#ff0000'>Ukuran file harus KURANG  1MB</span><br>
								<span style='color:#ff0000'>Jika menggunakan kamera hp pastikan kualitas gambar LOW/RENDAH agar bisa terupload </span>
							<div class='form-group'>

								<?php
								$no=0;
								foreach ($berkas as $bk) {
									$no++;
									?><div class='row' id='files<?=$bk['id_pbk']?>'><?php
									if(isset($bk['berkas_pbk']) && file_exists('./DATA/BERKAS/'.$bk['berkas_pbk'])){
										?>
										<embed style="width:200px" src='<?= base_url()?>DATA/BERKAS/<?= $bk['berkas_pbk'];?>'>
										<?php
									}
									?>
									<div class='col-sm-8'>
										Berkas <?=$no?>
									</div>
									<div class='col-sm-4'>
										<a class='btn btn-danger' onclick='removedata(<?=$bk['id_pbk']?>,"berkas")'>x</a>
									</div>
									</div>
									<?php
								}
								 ?>
							</div>
							<input type='hidden' id='sumfile' value='1'>
							<div class="row" id='beforethisfile'>
								<div class='col-sm-8'>
									<a id='plusfile' class='btn btn-warning'>+</a>
								</div>
							</div>
							<br>
						</div>
							<?php
							if(strtolower($this->uri->segment(1))=='studio_7_2' || (strtolower($this->uri->segment(1))=='ajax' && $this->uri->segment(3)==7)){
								?>
							<div id='pengukuran' class='col-sm-12'>
								<h4>Pengukuran dan Pemetaan</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Luas</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' name='luas' value='<?= $template['luasfisik_ptsl']?>' placeholder="input luas" class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>No. Berkas Fisik</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='noberkas' value='<?= $template['noberkas_ptsl']?>' placeholder="input no berkas" class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>NIB</label>
									</div>
									<div class='col-sm-6'>
										<?php if(strtolower($this->uri->segment(1))=='ajax'){?>
											<input type='text' name='nib' readonly value="<?= $template['nib_ptsl']?>" class='form-control'>
										<?php }else{?>
											<input type='text' name='nib' value='<?= $template['nib_ptsl']?>' placeholder="input nib" class='form-control'>
										<?php }?>
									</div>
								</div>
							</div>
							<div id='pengukuran' class='col-sm-12'>
								<h4>E-Yuridis</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Seleksi Klaster</label>
									</div>
									<div class='col-sm-6'>
										<select name='seleksik1' class='form-control'>
											<option <?php if($template['klaster_ptsl']=='k1'){echo 'selected';}?> value='k1'>k1</option>
											<option <?php if($template['klaster_ptsl']=='k2'){echo 'selected';}?> value='k2'>k2</option>
											<option <?php if($template['klaster_ptsl']=='k3 1'){echo 'selected';}?> value='k3 1'>k3 1</option>
											<option <?php if($template['klaster_ptsl']=='k3 2'){echo 'selected';}?> value='k3 2'>k3 2</option>
											<option <?php if($template['klaster_ptsl']=='k3 3'){echo 'selected';}?> value='k3 3'>k3 3</option>
											<option <?php if($template['klaster_ptsl']=='k3 4'){echo 'selected';}?> value='k3 4'>k3.4</option>
										</select>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>No.Berkas E-Yuridis</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='noberkasyuridis' value='<?= $template['noberkasyrd_ptsl']?>' placeholder="input no.berkas e-yuridis" class='form-control'>
									</div>
								</div>
							</div>
								<?php
							}
							 ?>
							 <div id='uploaded_image'></div>
							 <div id='pengukuran' class='col-sm-12'>
 								<h4>Jenis Hak</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Jenis Hak</label>
									</div>
									<div class='col-sm-6'>
										<select name='jenishak' class='form-control'>
	 										<option <?php if($template['hak_ptsl']=='1'){echo 'selected';}?> value='1'>Hak Milik</option>
	 										<option <?php if($template['hak_ptsl']=='2'){echo 'selected';}?> value='2'>Hak Guna Bangunan</option>
	 										<option <?php if($template['hak_ptsl']=='3'){echo 'selected';}?> value='3'>Hak Pakai</option>
											 <option <?php if($template['hak_ptsl']=='4'){echo 'selected';}?> value='4'>Hak Wakaf</option>
	 									</select>
									</div>
								</div>
 							</div>
						</div>
					</div>
					<div class='col-sm-12'>
						<div id='tanah' class='col-sm-12'>
							<h4></h4>
							<div class='form-group col-sm-6'>
								<div class='col-sm-4'>
									<label>DI 202</label>
								</div>
								<div class='col-sm-8'>
									<input type='text' name='di202' value='<?=$template['nodi202_ptsl']?>' class='form-control'>
								</div>
							</div>
							<div class='form-group col-sm-6'>
								<div class='col-sm-4'>
									<label>Tanggal DI 202</label>
								</div>
								<div class='col-sm-8'>
									<input type='date' name='tgldi202' value='<?=$template['date202_ptsl']?>' class='form-control'>
								</div>
							</div>
							<div class='form-group col-sm-6'>
								<div class='col-sm-4'>
									<label>DI 301</label>
								</div>
								<div class='col-sm-8'>
									<input type='text' name='di301' value='<?=$template['nodi301_ptsl']?>' class='form-control'>
								</div>
							</div>
							<div class='form-group col-sm-6'>
								<div class='col-sm-4'>
									<label>Tanggal DI 301</label>
								</div>
								<div class='col-sm-8'>
									<input type='date' name='tgldi301' value='<?=$template['date301_ptsl']?>' class='form-control'>
								</div>
							</div>
						</div>
					</div>
					<div class='col-sm-12'>
							<div id='tanah' class='col-sm-12'>
								<h4>Bukti-bukti Pemilikan/Penguasaan</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Nama Pewaris</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='pewaris' value='<?=$template['nm_pewaris']?>' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Meninggal tahun</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' name='meninggal' class='form-control' value='<?=$template['thn_meninggal']?>'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Surat Keterangan waris</label>
									</div>
									<div class='col-sm-6'>
										<input type='radio' name='waris' value='1' <?php if($template['srt_ket_waris']=='1'){echo 'checked';}?>> ada <br>
										<input type='radio' name='waris' value='0' <?php if($template['srt_ket_waris']=='0'){echo 'checked';}?>> tidak
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Surat Wasiat</label>
									</div>
									<div class='col-sm-6'>
										<input type='radio' name='wasiat' value='1' <?php if($template['srt_wasiat']=='1'){echo 'checked';}?>> ada <br>
										<input type='radio' name='wasiat' value='0' <?php if($template['srt_wasiat']=='0'){echo 'checked';}?>> tidak
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Hibah / Pemberian</label>
									</div>
									<div class='col-sm-10'>
										<div class='col-sm-4'>
											<label>Tanggal Akta</label>
											<input type='date' name='tglhibah' value='<?=$template['tgl_hibah']?>' class='form-control'>
										</div>
										<div class='col-sm-4'>
											<label>Nomor Akta</label>
											<input type='text' name='nohibah' class='form-control' value='<?=$template['nmr_hibah']?>'>
										</div>
										<div class='col-sm-4'>
											<label>Nama PPAT</label>
											<input type='text' name='namahibah' class='form-control' value='<?=$template['ppat_hibah']?>'>
										</div>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Pembelian</label>
									</div>
									<div class='col-sm-10'>
										<div class='col-sm-4'>
											<label>Tanggal Akta</label>
											<input type='date' name='tglbeli' class='form-control' value='<?=$template['tgl_beli']?>'>
										</div>
										<div class='col-sm-4'>
											<label>Nomor Akta</label>
											<input type='text' name='nobeli' class='form-control' value='<?=$template['nmr_beli']?>'>
										</div>
										<div class='col-sm-4'>
											<label>Nama PPAT</label>
											<input type='text' name='namabeli' class='form-control' value='<?=$template['ppat_beli']?>'>
										</div>
									</div>
								</div>
						</div>
						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-right">
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

<!-- modal penduduk -->
<div id="modal-penduduk" class="modal fade" role="dialog">
	<form id="form-ptsl" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-ptsl">Tambahkan Data Penduduk</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div id='penduduk' class='col-sm-12'>
						<h4>IDENTITAS PEMILIK</h4>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Nama</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='nama' id='new-nama' class='form-control' placeholder='nama lengkap'>
							</div>
						</div>
						<div id='cek'></div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Tempat Lahir</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='ttl' id='new-ttl' placeholder='tempat lahir' class='form-control'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Tanggal Lahir</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='tgl' id='new-tgl' style='margin:5px;width:150px' class='datepicker form-control'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Pekerjaan</label>
							</div>
							<div class='col-sm-6'>
								<select name='pekerjaan' id='new-pekerjaan' style='margin:5px' class='form-control'>
									<?php
									foreach ($pekerjaan as $dd) {
										?><option value='<?=$dd['idpkr_pkr']?>'><?=$dd['nama_pkr']?></option><?php
									}
										?>
								</select>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Agama</label>
							</div>
							<div class='col-sm-6'>
								<select name='agama' id='new-agama' style='margin:5px' class='form-control'>
									<option value='1'>Islam</option>
									<option value='2'>Kristen</option>
									<option value='3'>Katholik</option>
									<option value='4'>Budha</option>
									<option value='5'>Hindu</option>
									<option value='6'>Konghucu</option>
								</select>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Alamat Lengkap</label>
							</div>
							<div class='col-sm-6'>
								<textarea name='alamat' id='new-alamat' class='form-control' placeholder='alamat lengkap'></textarea>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>RT</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='rt' id='new-rt' class='form-control' placeholder='RT'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>RW</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='rw' id='new-rw' class='form-control' placeholder='RW'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Kelurahan</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='kel' id='new-kel' class='form-control' placeholder='Kelurahan'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Kecamatan</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='kec' id='new-kec' class='form-control' placeholder='Kecamatan'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>Kabupaten</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='kab' id='new-kab' class='form-control' placeholder='Kabupaten'>
							</div>
						</div>
						<div class='form-group col-sm-12'>
							<div class='col-sm-2'>
								<label>No.Telp</label>
							</div>
							<div class='col-sm-6'>
								<input type='text' name='notelp' id='new-notelp' class='form-control' placeholder='nomor telepon'>
							</div>
						</div>
					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" id='simpan-penduduk' class="btn btn-primary btn-sm" id="btn-simpan-ptsl">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#pekerjaan').select2();
		$('#dhkp').select2();
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cari_dhkp_json',
				data: 'id='+$('#dhkp').val(),
				dataType: 'json',
				beforeSend: function() {
				},
				success: function(response) {
					$('#nosppt').val(response.data.nosppt_dhkp).attr("readonly", true);
					$('#nwp').val(response.data.nama_dhkp).attr("readonly", true);
					$('#njop').val(response.data.njopsppt_dhkp).attr("readonly", true);
					$('#luassppt').val(response.data.luassppt_dhkp).attr("readonly", true);
					$('#aop').val(response.data.aopsppt_dhkp).attr("readonly", true);
					$('#awp').val(response.data.awpsppt_dhkp).attr("readonly", true);
					$('#areasavenop').hide();
				}
		});

		removechild(<?=$nom?>);
		
	})

	$('#newnik').click(function(){
		$('#modal-penduduk').modal('show');
	});

	$('#addnop').click(function () {
		alert('Form telah diaktifkan, silakan isi data nop yang belum terdaftar');
		$('#nosppt').val("").attr("readonly", false).focus();
		$('#nwp').val("").attr("readonly", false);
		$('#njop').val("").attr("readonly", false);
		$('#luassppt').val("").attr("readonly", false);
		$('#aop').val("").attr("readonly", false);
		$('#awp').val("").attr("readonly", false);
		$('#areasavenop').show();
	});

	$('#simpan-penduduk').on('click',function () {
		if($('#new-nama').val()==''){
			alert('Nama harus diisi');
			return false;
		}
		$.ajax({
				type: 'POST',
				url: '<?php echo base_url();?>ajax/simpanpenduduk',
				data: {
					nama	: $('#new-nama').val(),
					ttl	: $('#new-ttl').val(),
					tgl	: $('#new-tgl').val(),
					pekerjaan	: $('#new-pekerjaan').val(),
					agama	: $('#new-agama').val(),
					alamat	: $('#new-alamat').val(),
					rt	: $('#new-rt').val(),
					rw	: $('#new-rw').val(),
					kel	: $('#new-kel').val(),
					kec	: $('#new-kec').val(),
					kab	: $('#new-kab').val(),
					telp: $('#new-notelp').val(),
				},
				dataType: 'json',
				beforeSend: function() {
					
				},
				success: function(response) {
					$('#ktp').val(response);
					$('#modal-penduduk').modal('hide');
					$('#cekinternal').click();
				}
		});
	});

	$('#nosppt').on('keyup',function () {
		$.ajax({
				type: 'POST',
				url: '<?php echo base_url();?>ajax/cek_dhkp',
				data: {
					block	: $('#idblock').val(),
					nosspt	: $('#nosppt').val(),
				},
				dataType: 'json',
				beforeSend: function() {
				},
				success: function(response) {
					if(response.data!=null){
						$('#nwp').val(response.data.nama_dhkp);
						$('#njop').val(response.data.njopsppt_dhkp);
						$('#luassppt').val(response.data.luassppt_dhkp);
						$('#aop').val(response.data.aopsppt_dhkp);
						$('#awp').val(response.data.awpsppt_dhkp);
					}else{
						$('#nwp').val("");
						$('#njop').val("");
						$('#luassppt').val("");
						$('#aop').val("");
						$('#awp').val("");
					}
					$('#savenop').html(response.button);
					
				}
		});
	});
	

	$('#savenop').on('click',function () {
		$.ajax({
				type: 'POST',
				url: '<?php echo base_url();?>ajax/simpan_dhkp',
				data: {
					block	: $('#idblock').val(),
					nosspt	: $('#nosppt').val(),
					nwp		: $('#nwp').val(),
					njop	: $('#njop').val(),
					luassppt	: $('#luassppt').val(),
					aop		: $('#aop').val(),
					awp		: $('#awp').val(),
				},
				dataType: 'json',
				beforeSend: function() {
				},
				success: function(response) {
						console.log(response);
						// dd(response);
						alert(response.msg);
						var id = $('#sumspt').val();
						var text = "<div id='obj"+id+"' class='row obj' style='width:100%'><div class='col-sm-8'><input type='text' class='form-control' readonly value='"+response.text+"'><input type='hidden' name='dhkp[]' value='"+response.value+"'></div><div class='col-sm-4'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div><br></div>"
						$('#sumspt').val(id+1);
						$(text).insertBefore($('#beforethis'));
						$('#nosppt').attr("readonly", true);
						$('#nwp').attr("readonly", true);
						$('#njop').attr("readonly", true);
						$('#luassppt').attr("readonly", true);
						$('#aop').attr("readonly", true);
						$('#awp').attr("readonly", true);
						$('#areasavenop').hide();
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
		var text = "<div id='file"+id+"' class='form-group row'><div class='col-sm-3 uploaded_image'></div><div class='col-sm-6'><input type='file' class='form-control fileo input-sm' name='berkas[]'></div><div class='col-sm-3'><a class='btn btn-danger' onclick='removefile("+id+")'>-</a></div></div>"
		$(text).insertBefore($('#beforethisfile'));
		$('#sumfile').val(id);
	});

	function removefile(id){
		$('#file'+id).remove();
	}

	function removechild(id){
		$('#obj'+id).remove();
	}

	function descfill(){
		msg = 'Pada tahun 1960 tercatat tanah bekas HMA/Yasan C '+$('#dc').val()+' persil '+$('#dpersil').val()+' Klas '+$('#dklas').val()+' Luas '+$('#dluas').val()+' atas nama '+$('#ddari').val()+'';
		$('#desc0').val(msg);
		riwayatfill(0);
	}

	function riwayatfill($id){
		nowname = $('#ddari').val();
		nowc = $('#dc').val();
		for(k=1;k<=5;k++){
			if($('#dtahun'+k).val()!='0'){
				if($('#dc'+k).val()!=0 && $('#dc'+k).val()!=nowc){
					nowname = $('#dnama'+k).val();
					nowc = $('#dc'+k).val();						
				}
				msg = 'Pada tahun '+$('#dtahun'+k).val()+' sebagian/seluruh*) seluas '+$('#dluas'+k).val()+' m2 '+$('#dkeperluan'+k+' option:selected').text()+'*) kepada '+$('#dnama'+k).val()+' dan telah berubah menjadi Letter C Nomor '+nowc+' atas nama '+nowname;
				$('#des'+k).val(msg);
			}
		}
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

	$('#dhkp').on('change',function () {
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cari_dhkp_json',
				data: 'id='+$(this).val(),
				dataType: 'json',
				beforeSend: function() {
				},
				success: function(response) {
					$('#nosppt').val(response.data.nosppt_dhkp).attr("readonly", true);
					$('#nwp').val(response.data.nama_dhkp).attr("readonly", true);
					$('#njop').val(response.data.njopsppt_dhkp).attr("readonly", true);
					$('#luassppt').val(response.data.luassppt_dhkp).attr("readonly", true);
					$('#aop').val(response.data.aopsppt_dhkp).attr("readonly", true);
					$('#awp').val(response.data.awpsppt_dhkp).attr("readonly", true);
					$('#areasavenop').hide();
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
						if(response.ttg_pdk){
							tanggal = response.ttg_pdk;
							var tgl = tanggal.split("-");
							var formated = tgl[2]+'-'+tgl[1]+'-'+tgl[0];
							$("#tgl").val(formated);
						}
						$("#alamat").val(response.almat_pdk);
						$("#rt").val(response.rt_pdk);
						$("#rw").val(response.rw_pdk);
						$("#kec").val(response.kec_pdk);
						$("#kel").val(response.kel_pdk);
						$("#kab").val(response.kab_pdk);
						$("#kk").val(response.nokk_pdk);
						$("#notelp").val(response.notelp_pdk);
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

	});

	$(document).ready(function(){
		$(document).on('change', '.fileo', function(){
			var name = this.files[0].name;
			
			var form_data = new FormData();
			var ext = name.split('.').pop().toLowerCase();

			if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1) 
			{
				alert("Invalid Image File");
			}
			// alert(name);

			var oFReader = new FileReader();
			oFReader.readAsDataURL(this.files[0]);
			var f = this.files[0];
			var fsize = f.size||f.fileSize;
			var bag = $(this).parent().parent().find('.uploaded_image');
			// alert(bag.html());
			if(fsize > 3000000)
			{
				alert("Image File Size is very big");
			}else{
				form_data.append("file", this.files[0]);
				form_data.append('inputname', <?=$this->uri->segment(3)?>);
				$.ajax({
					url:'<?php echo base_url();?>ajax/upload_image/edit',
					method:"POST",
					data: form_data,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend:function(){
						bag.html("<label class='text-success'>Image Uploading...</label>");
						// $('#uploaded_image').html();
					},   
					success:function(data)
					{
						// alert(bag);
						bag.html(data);
						// $('#uploaded_image').html(data);
					}
				});
			}
		});
	});
</script>
