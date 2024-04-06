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
<div id='alert'></div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<?php if($this->uri->segment(1)=='ajax'){ ?>
					<form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3);?>?search=<?php echo $this->input->get('search'); ?>">
					<?php }else{
						?><form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2);?>?search=<?php echo $this->input->get('search'); ?>"><?php
					}?>
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
 											<input type='text' name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)'>
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
										<input type='text' name='nama' id='nama' class='form-control' placeholder='nama lengkap'>
									</div>
								</div>
								<div id='cek'></div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Tempat Lahir</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='ttl' id='ttl' placeholder='tempat lahir' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Tanggal Lahir</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control'>
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
										<select name='agama' id='agama' style='margin:5px' class='form-control'>
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
										<textarea name='alamat' id='alamat' class='form-control' placeholder='alamat lengkap'></textarea>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>RT</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='rt' id='rt' class='form-control' placeholder='RT'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>RW</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='rw' id='rw' class='form-control' placeholder='RW'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kelurahan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kel' id='kel' class='form-control' placeholder='Kelurahan'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kecamatan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kec' id='kec' class='form-control' placeholder='Kecamatan'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Kabupaten</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='kab' id='kab' class='form-control' placeholder='Kabupaten'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>No.Telp</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='notelp' id='notelp' class='form-control' placeholder='nomor telepon'>
									</div>
								</div>
							</div>
							<div id='sppt' class='col-sm-12'>
								<h4>SPPT</h4>
								<div id='morespt'>
									<div class='form-group'>
										<input type='hidden' id='sumspt' value='1'>
										<div class='row' id='beforethis'>
											<div class='col-sm-8'>
												<select class='form-control dhkp' id='pilihspt' name='dhkp[]'>
													<option value=''>Pilih No.SPPT</option>
													<?php foreach ($dhkp as $data) {
														?><option class='option' value='<?=$data['id_dhkp']?>'><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
													}?>
												</select>
											</div>
											<div class='col-sm-4'>
												<a id='plus' class='btn btn-warning' style='margin-left:-22px'>+</a>
											</div>
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
										<label>No.SPPT / NOP</label>
									</div>
									<div class='col-sm-6 form-inline'>
										<?php $nop = createkodebpkad($block['idkel_blk']).''.$block['nama_blk']; ?>
										<input type='text' disabled style='width:50%;float:left' class='form-control' value='<?= $nop?>'>
										<input type='text' name='sppt' style='width:40%;float:left' placeholder='no.sppt' class='form-control' id='nosppt'>
										<!-- <button id='btn-cari' class='btn btn-warning'><i class="fa fa-search" aria-hidden="true"></i></button> -->
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Nama Wajib Pajak</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='nwp' id='nwp' class='form-control' placeholder='nama lengkap'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>NJOP Rp.</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' placeholder='nominal njop' id='njop' name='njop' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Luas Bumi</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' placeholder='Luas SPPT' id='luassppt' name='luassppt' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Alamat Objek Pajak</label>
									</div>
									<div class='col-sm-6'>
										<textarea placeholder='alamat objek pajak' name='aop' id='aop' class='form-control'></textarea>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Alamat Wajib Pajak</label>
									</div>
									<div class='col-sm-6'>
										<textarea placeholder='alamat wajib pajak' name='awp' id='awp' class='form-control'></textarea>
									</div>
								</div>
							</div>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Blok</label>
								</div>
								<div class='col-sm-6'>
									<input type='text' name='nameblok' value='<?= $block['nama_blk'] ?>' disabled class='form-control'>
									<input type='hidden' name='blok' id='idblock' value='<?= $block['idblk_blk'] ?>' class='form-control'>
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
						<div id='penggunaan' class='col-sm-12'>
							<h4>PENGGUNAAN DAN PEMANFAATAN</h4>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Penggunaan</label>
								</div>
								<div class='col-sm-6'>
									<select name='guna' class='form-control'>
										<option value='1'>Pertanian</option>
										<option value='2'>Non Pertanian</option>
									</select>
								</div>
							</div>
							<div class='form-group col-sm-12'>
								<div class='col-sm-2'>
									<label>Pemanfaatan</label>
								</div>
								<div class='col-sm-6'>
									<select name='manfaat' class='form-control'>
										<option value='1'>Perumahan</option>
										<option value='2'>Pekarangan</option>
										<option value='3'>Sawah</option>
										<option value='4'>Ladang/Tegalan</option>
										<option value='5'>Kebun/Kebun Campuran</option>
										<option value='6'>Kolam Ikan</option>
										<option value='7'>Industri</option>
										<option value='8'>Perkebunan</option>
										<option value='9'>Dikelola Pengembang</option>
										<option value='10'>Lapangan Umum</option>
										<option value='11'>Peternakan</option>
										<option value='13'>Jalan</option>
										<option value='12'>Tidak dimanfaatkan</option>
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
										<input type='text' name='utara' placeholder='batas utara' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Timur</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='timur' placeholder='batas timur' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Selatan</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='selatan' placeholder='batas selatan' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Barat</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='barat' placeholder='batas barat' class='form-control'>
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
										<input type='text' name='dc' onkeyup='descfill()' id='dc' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Persil</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dpersil' onkeyup='descfill()' id='dpersil'  class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Klas</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dklas' onkeyup='descfill()' id='dklas'  class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Luas</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='dluas' onkeyup='descfill()' id='dluas' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>diperoleh dari :</label>
									</div>
									<div class='col-sm-6'>
										<input type='text' name='ddari' onkeyup='descfill()' id='ddari' class='form-control'>
									</div>
								</div>
								<h4>Riwayat Tanah</h4>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' id='desc0' name='des0' placeholder='deskripsi 0' style='width:100% !important'><?=$template['desc0_ptsl'];?></textarea>
								</div>
								<a class="btn btn-primary" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1">
							    Riwayat 1
							  </a>
								<div class="collapse" id="collapse1">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan' id='dkeperluan1' onchange='riwayatfill(1)' style='margin:5px' class='form-control'>
											<option value='1' disabled selected>Pilih Keperluan</option>
											<option value='1'>Jual beli</option>
											<option value='2'>Waris</option>
											<option value='3'>Hibah</option>
											<option value='4'>Wakaf</option>
											<option value='5'>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun' onkeyup='riwayatfill(1)' id='dtahun1' style='width:70px;margin:5px' class='form-control'>
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
								<div class="collapse" id="collapse2">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan2' id='dkeperluan2' onchange='riwayatfill(2)' style='margin:5px' class='form-control'>
											<option value='1' disabled selected>Pilih Keperluan</option>
											<option value='1'>Jual beli</option>
											<option value='2'>Waris</option>
											<option value='3'>Hibah</option>
											<option value='4'>Wakaf</option>
											<option value='5'>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun2' onkeyup='riwayatfill(2)' id='dtahun2' style='width:70px;margin:5px' class='form-control'>
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
								<div class="collapse" id="collapse3">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan3' id='dkeperluan3' onchange='riwayatfill(3)' style='margin:5px' class='form-control'>
											<option value='1' disabled selected>Pilih Keperluan</option>
											<option value='1'>Jual beli</option>
											<option value='2'>Waris</option>
											<option value='3'>Hibah</option>
											<option value='4'>Wakaf</option>
											<option value='5'>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun3' onkeyup='riwayatfill(3)' id='dtahun3' style='width:70px;margin:5px' class='form-control'>
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
								<div class="collapse" id="collapse4">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan4' id='dkeperluan4' onchange='riwayatfill(4)' style='margin:5px' class='form-control'>
											<option value='1' disabled selected>Pilih Keperluan</option>	
											<option value='1'>Jual beli</option>
											<option value='2'>Waris</option>
											<option value='3'>Hibah</option>
											<option value='4'>Wakaf</option>
											<option value='5'>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun4' onkeyup='riwayatfill(4)' id='dtahun4' style='width:70px;margin:5px' class='form-control'>
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
								<div class="collapse" id="collapse5">
									<div class='form-inline col-sm-12'>
										<label>Berdasarkan</label>
										<select name='dkeperluan5' id='dkeperluan5' onchange='riwayatfill(5)' style='margin:5px' class='form-control'>
											<option value='1' disabled selected>Pilih Keperluan</option>
											<option value='1'>Jual beli</option>
											<option value='2'>Waris</option>
											<option value='3'>Hibah</option>
											<option value='4'>Wakaf</option>
											<option value='5'>Tukar Menukar</option>
										</select>
										<label>Tahun</label>
										<input type='text' name='dtahun5' onkeyup='riwayatfill(5)' id='dtahun5' style='width:100px;margin:5px' class='form-control'>
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
									<textarea class='form-control' placeholder='catatan' name='note'></textarea>
								</div>
							</div>
						</div>
						<div id='tanah' class='col-sm-12'>
							<h4>Upload KTP,KK, SPPT-PBB dan Letter C</h4>
							<span style='color:#ff0000'>Ukuran file harus KURANG  1MB</span><br>
								<span style='color:#ff0000'>Jika menggunakan kamera hp pastikan kualitas gambar LOW/RENDAH agar bisa terupload </span>
							<div class='form-group'>
								<!-- <input type='file' name='berkas[]' class='form-control'> -->
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
							if(strtolower($this->uri->segment(1))=='studio_7_2' || $this->uri->segment(1)=='ajax'){
								?>
						<div id='pengukuran' class='col-sm-12'>
								<h4>Pengukuran dan Pemetaan</h4>
								<?php if($this->uri->segment(1)!='ajax' || ($this->uri->segment(1)=='ajax' && $this->uri->segment(3)==7)){?>
									<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>Luas</label>
										</div>
										<div class='col-sm-6'>
											<input type='text' name='luas' placeholder="input luas" class='form-control'>
										</div>
									</div>
									<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>No. Berkas Fisik</label>
										</div>
										<div class='col-sm-6'>
											<input type='text' name='noberkas' placeholder="input no berkas" class='form-control'>
										</div>
									</div>
								<?php }?>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>NIB</label>
									</div>
									<div class='col-sm-6'>
										<?php if($this->uri->segment(1)=='ajax'){
											?><input type='text' name='nib' readonly value="<?=$nib?>" class='form-control'><?php
										}else{
												?><input type='text' name='nib' placeholder="input nib" class='form-control'><?php
										} ?>
									</div>
								</div>
							</div>
						</div>
							<?php if($this->uri->segment(1)!='ajax' || ($this->uri->segment(1)=='ajax' && $this->uri->segment(3)==7)){?>
						<div class='col-sm-12'>
							<div id='pengukuran' class='col-sm-12'>
									<h4>E-Yuridis</h4>
									<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>Seleksi Klaster</label>
										</div>
										<div class='col-sm-6'>
											<select name='seleksik1' class='form-control'>
												<option value='k1'>k1</option>
												<option value='k2'>k2</option>
												<option value='k3 1'>k3 1</option>
												<option value='k3 2'>k3 2</option>
												<option value='k3 3'>k3 3</option>
												<option value='k3 4'>k3.4</option>
											</select>
										</div>
									</div>
									<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>No.Berkas E-Yuridis</label>
										</div>
										<div class='col-sm-6'>
											<input type='text' name='noberkasyuridis' placeholder="input no.berkas e-yuridis" class='form-control'>
										</div>
									</div>
								</div>
							</div>
							<?php }?>
								<?php
							}
							 ?>
						</div>
						<div class='col-sm-12'>
								<div id='pengukuran' class='col-sm-12'>
								<h4>Jenis Hak</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Jenis Hak</label>
									</div>
									<div class='col-sm-6'>
										<select name='jenishak' class='form-control'>
											<option value='1'>Hak Milik</option>
											<option value='2'>Hak Guna Bangunan</option>
											<option value='3'>Hak Pakai</option>
											<option value='4'>Hak Wakaf</option>
										</select>
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
										<input type='text' name='di202' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-6'>
									<div class='col-sm-4'>
										<label>Tanggal DI 202</label>
									</div>
									<div class='col-sm-8'>
										<input type='date' name='tgldi202' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-6'>
									<div class='col-sm-4'>
										<label>DI 301</label>
									</div>
									<div class='col-sm-8'>
										<input type='text' name='di301'  class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-6'>
									<div class='col-sm-4'>
										<label>Tanggal DI 301</label>
									</div>
									<div class='col-sm-8'>
										<input type='date' name='tgldi301'  class='form-control'>
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
										<input type='text' name='pewaris' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Meninggal tahun</label>
									</div>
									<div class='col-sm-6'>
										<input type='number' name='meninggal' class='form-control'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Surat Keterangan waris</label>
									</div>
									<div class='col-sm-6'>
										<input type='radio' name='waris' value='1'> ada <br>
										<input type='radio' name='waris' value='0'> tidak
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Surat Wasiat</label>
									</div>
									<div class='col-sm-6'>
										<input type='radio' name='wasiat' value='1'> ada <br>
										<input type='radio' name='wasiat' value='0'> tidak
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<div class='col-sm-2'>
										<label>Hibah / Pemberian</label>
									</div>
									<div class='col-sm-10'>
										<div class='col-sm-4'>
											<label>Tanggal Akta</label>
											<input type='date' name='tglhibah' class='form-control'>
										</div>
										<div class='col-sm-4'>
											<label>Nomor Akta</label>
											<input type='text' name='nohibah' class='form-control'>
										</div>
										<div class='col-sm-4'>
											<label>Nama PPAT</label>
											<input type='text' name='namahibah' class='form-control'>
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
											<input type='date' name='tglbeli' class='form-control'>
										</div>
										<div class='col-sm-4'>
											<label>Nomor Akta</label>
											<input type='text' name='nobeli' class='form-control'>
										</div>
										<div class='col-sm-4'>
											<label>Nama PPAT</label>
											<input type='text' name='namabeli' class='form-control'>
										</div>
									</div>
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
		$('.dhkp').select2();
		$('#areasavenop').hide();
		$('#nosppt').val("").attr("readonly", true);
		$('#nwp').val("").attr("readonly", true);
		$('#njop').val("").attr("readonly", true);
		$('#luassppt').val("").attr("readonly", true);
		$('#aop').val("").attr("readonly", true);
		$('#awp').val("").attr("readonly", true);
	})

	$('#newnik').click(function(){
		$('#modal-penduduk').modal('show');
	});

	$('.dhkp').on('change',function () {
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

	$('#plus').on('click',function () {
		var sel = document.getElementById('pilihspt');
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

	function removechild(id){
		$('#obj'+id).remove();
	}

	function removefile(id){
		$('#file'+id).remove();
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
				form_data.append('inputname', <?=$this->input->get('search')?>);
				$.ajax({
					url:'<?php echo base_url();?>ajax/upload_image/input',
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
