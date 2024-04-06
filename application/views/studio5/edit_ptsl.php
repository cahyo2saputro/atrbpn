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
						<form enctype="multipart/form-data" id="form-tambah" method="post" action="<?php echo base_url().'/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>">
						<div class='col-sm-12'>
							<div id='pengukuran' class='col-sm-12'>
								<h4>Pengukuran dan Pemetaan</h4>
								<?php
								if(strtolower($this->uri->segment(1))=='studio_5_2' || strtolower($this->uri->segment(1))=='studio_6_2' || strtolower($this->uri->segment(1))=='ajax'){
									?>
									<div class='form-group col-sm-12'>
										<div class='col-sm-2'>
											<label>Luas</label>
										</div>
										<div class='col-sm-6'>
											<input type='number' name='luas' value='<?= $template['luasfisik_ptsl']?>' placeholder="input luas" class='form-control'>
										</div>
									</div>
									<?php
								}
								if(strtolower($this->uri->segment(1))=='studio_6_2' || (strtolower($this->uri->segment(1))=='ajax' && $this->uri->segment(3)==6)){
									?>
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
									<?php
								}
							 		?>
							</div>
							<div id='pengukuran' class='col-sm-12'>
								<div class='form-group'>
									<div class="pull-right">
										<?php if(strtolower($this->uri->segment(1))!='ajax'){ ?>
										<button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
									<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class='col-sm-12'>
							<ul class="nav nav-tabs" id="myTab" role="tablist">
							  <li class="nav-item">
							    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">KTP,KK dan SPPT</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">KTP</a>
							  </li>
							  <li class="nav-item">
							    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">SPPT</a>
							  </li>
								<li class="nav-item">
							    <a class="nav-link" id="batas-tab" data-toggle="tab" href="#batas" role="tab" aria-controls="batas" aria-selected="false">BATAS-BATAS</a>
							  </li>
							  </li>
								<li class="nav-item">
							    <a class="nav-link" id="waris-tab" data-toggle="tab" href="#waris" role="tab" aria-controls="waris" aria-selected="false">Bukti Pemilikan/Penguasaan</a>
							  </li>
							</ul>
							<div class="tab-content" id="myTabContent">
							  <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
									<?php
									foreach ($berkas as $bk) {
										if(isset($bk['berkas_pbk']) && file_exists('./DATA/BERKAS/'.$bk['berkas_pbk'])){
											?>
										<div class='form-group'>
											<embed style="width:200px" src='<?= base_url()?>DATA/BERKAS/<?= $bk['berkas_pbk'];?>'>
										</div><?php
										}
									}
									 ?>
								</div>
							  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
									<div id='penduduk' class='col-sm-12'>
										<h4>IDENTITAS PEMILIK</h4>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>No.KTP</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$template['noktp_pdk'];?>'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Nama</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='nama' id='nama' class='form-control' placeholder='nama lengkap' value='<?= $template['nma_pdk'];?>'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Tempat Lahir</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='ttl' id='ttl' placeholder='tempat lahir' class='form-control' value='<?= $template['ttl_pdk']?>'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Tanggal Lahir</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control' value='<?= $template['ttg_pdk']?>'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Pekerjaan</label>
											</div>
											<div class='col-sm-6'>
												<select name='pekerjaan' disabled id='pekerjaan' style='margin:5px' class='form-control'>
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
												<label>Tanggal Lahir</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control' value='<?= $template['ttg_pdk']?>'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Agama</label>
											</div>
											<div class='col-sm-6'>
												<select name='agama' disabled id='agama' style='margin:5px' class='form-control'>
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
												<textarea name='alamat' disabled id='alamat' class='form-control' placeholder='alamat lengkap'><?= $template['almat_pdk']?></textarea>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>RT</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='rt' id='rt' class='form-control' placeholder='RT' value="<?= $template['rt_pdk'];?>">
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>RW</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='rw' id='rw' class='form-control' placeholder='RW' value="<?= $template['rw_pdk'];?>">
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Kelurahan</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='kel' id='kel' class='form-control' placeholder='Kelurahan' value="<?= $template['kel_pdk'];?>">
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Kecamatan</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='kec' id='kec' class='form-control' placeholder='Kecamatan' value="<?= $template['kec_pdk'];?>">
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Kabupaten</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='kab' id='kab' class='form-control' placeholder='Kabupaten' value="<?= $template['kab_pdk'];?>">
											</div>
										</div>
									</div>
								</div>
							  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
														<!-- <a class='btn btn-danger' onclick='removechild(<?=$nom?>)'>-</a> -->
													</div><br>
												</div>
											<?php
											$selected = $db['iddhkp_ptsl'];
										 	endforeach; ?>
											<input type='hidden' id='sumspt' value='<?= $nom;?>'>
											<div class='row' id='beforethis'>
												<div class='col-sm-8'>
												<select disabled class='form-control' id='dhkp' name='dhkp[]'>
													<option value=''>Pilih No.SPPT</option>
													<?php foreach ($dhkp as $data) {
														?><option value='<?=$data['id_dhkp']?>' <?php if($data['id_dhkp']==$selected){echo 'selected';}?>><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
													}?>
												</select>
											</div>

										</div>
										</div>
									<div id='ajaxarea'>
										<div class='form-group col-sm-12'>
											<label>No.SPPT</label> :
											<div class='form-inline'>
												<?php $nop = createkodebpkad($block['idkel_blk']).''.$block['nama_blk']; ?>
												<input type='text' disabled style='width:60%;float:left' class='form-control' value='<?= $nop?>'>
												<input type='text' name='sppt' style='width:40%;float:left' placeholder='no.sppt' class='form-control' value='<?=$template['nosppt_ptsl'];?>'>
											</div>
										</div>
										<div class='form-inline col-sm-6'>
											<label>NJOP Rp.</label> :
											<input type='number' placeholder='nominal njop' value='<?=$template['njop_ptsl'];?>' name='njop' class='form-control'>
										</div>
										<div class='form-inline col-sm-6'>
											<label>Luas Bumi</label> :
											<input type='number' placeholder='Luas SPPT' name='luassppt' value='<?=$template['luassppt_ptsl'];?>' class='form-control'>
										</div>
										<div class='form-group col-sm-12'>
											<label>Alamat Objek Pajak</label> :
											<textarea placeholder='alamat objek pajak' name='aop' class='form-control'><?=$template['aopsppt_ptsl'];?></textarea>
										</div>
										<div class='form-group col-sm-12'>
											<label>Alamat Wajib Pajak</label> :
											<textarea placeholder='alamat wajib pajak' name='awp' class='form-control'><?=$template['awpsppt_ptsl'];?></textarea>
										</div>
									</div>
									<div class='form-group col-sm-4'>
										<label>Penggunaan</label> :
										<select name='guna' disabled class='form-control'>
											<option value='1' <?php if($template['idguna_ptsl']=='1'){echo 'selected';}?>>Pertanian</option>
											<option value='2' <?php if($template['idguna_ptsl']=='2'){echo 'selected';}?>>Non Pertanian</option>
										</select>
									</div>
									<div class='form-group col-sm-4'>
										<label>Pemanfaatan</label> :
										<select name='manfaat' disabled class='form-control'>
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
											<option value='11'<?php if($template['idmanfaat_ptsl']=='13'){echo 'selected';}?>>Jalan</option>
											<option value='12'<?php if($template['idmanfaat_ptsl']=='12'){echo 'selected';}?>>Tidak dimanfaatkan</option>
										</select>
									</div>
										<div class='form-group col-sm-4'>
											<label>Blok</label> :
											<input type='hidden' readonly name='templateid' value='<?= $template['id_ptsl'] ?>'>
											<input type='text' disabled name='nameblok' value='<?= $block['nama_blk'] ?>' disabled class='form-control'>
											<input type='hidden' name='blok' value='<?= $block['idblk_blk'] ?>' class='form-control'>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="batas" role="tabpanel" aria-labelledby="batas-tab">
									<div id='tanah' class='col-sm-6'>
										<h4>Batas-Batas Tanah</h4>
										<div class='form-group col-sm-12'>
											<label>Utara</label> :
											<input type='text' disabled name='utara' placeholder='batas utara' value='<?=$template['utara_ptsl'];?>' class='form-control'>
										</div>
										<div class='form-group col-sm-12'>
											<label>Timur</label> :
											<input type='text' disabled name='timur' placeholder='batas timur' value='<?=$template['timur_ptsl'];?>' class='form-control'>
										</div>
										<div class='form-group col-sm-12'>
											<label>Selatan</label> :
											<input type='text' disabled name='selatan' placeholder='batas selatan' value='<?=$template['selatan_ptsl'];?>' class='form-control'>
										</div>
										<div class='form-group col-sm-12'>
											<label>Barat</label> :
											<input type='text' disabled name='barat' placeholder='batas barat' value='<?=$template['barat_ptsl'];?>' class='form-control'>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="waris" role="tabpanel" aria-labelledby="waris-tab">
									<div id='tanah' class='col-sm-12'>
										<h4>Bukti-bukti Pemilikan/Penguasaan</h4>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Nama Pewaris</label>
											</div>
											<div class='col-sm-6'>
												<input type='text' disabled name='pewaris' value='<?=$template['nm_pewaris']?>' class='form-control'>
											</div>
										</div>
										<div class='form-group col-sm-12'>
											<div class='col-sm-2'>
												<label>Meninggal tahun</label>
											</div>
											<div class='col-sm-6'>
												<input type='number' disabled name='meninggal' class='form-control' value='<?=$template['thn_meninggal']?>'>
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
													<input type='date' disabled name='tglhibah' value='<?=$template['tgl_hibah']?>' class='form-control'>
												</div>
												<div class='col-sm-4'>
													<label>Nomor Akta</label>
													<input type='text' disabled name='nohibah' class='form-control' value='<?=$template['nmr_hibah']?>'>
												</div>
												<div class='col-sm-4'>
													<label>Nama PPAT</label>
													<input type='text' disabled name='namahibah' class='form-control' value='<?=$template['ppat_hibah']?>'>
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
													<input type='date' disabled name='tglbeli' class='form-control' value='<?=$template['tgl_beli']?>'>
												</div>
												<div class='col-sm-4'>
													<label>Nomor Akta</label>
													<input type='text' disabled name='nobeli' class='form-control' value='<?=$template['nmr_beli']?>'>
												</div>
												<div class='col-sm-4'>
													<label>Nama PPAT</label>
													<input type='text' disabled name='namabeli' class='form-control' value='<?=$template['ppat_beli']?>'>
												</div>
											</div>
										</div>
									</div>
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

	function removechild(id){
		$('#obj'+id).remove();
	}

</script>
