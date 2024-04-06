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
						<div class='col-sm-6'>
							<div id='penduduk' class='col-sm-12'>
								<h4>KTP</h4>
								<div class='form-group col-sm-12'>
									<div class='col-sm-8'>
										<label>No.KTP</label> :
										<input type='text' name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$template['noktp_pdk'];?>'>
									</div>
									<div class='col-sm-4'>
										<label>Cek Dukcapil</label>
										<input type='button' value='Cek' id='cktp' class='btn btn-warning'>
									</div>
								</div>
								<div class='form-group col-sm-12'>
									<label>Nama</label> :
									<input type='text' name='nama' id='nama' class='form-control' placeholder='nama lengkap' value="<?= stripslashes($template['nma_pdk']);?>">
								</div>
								<div class='form-inline col-sm-4'>
									<label>Tempat Lahir</label> :
									<input type='text' name='ttl' id='ttl' placeholder='tempat lahir' style='margin:5px;width:150px' class='form-control' value='<?= $template['ttl_pdk']?>'>
								</div>
								<div class='form-inline col-sm-4'>
									<label>Tanggal Lahir</label> :
									<input type='text' name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control' value='<?= $template['ttg_pdk']?>'>
								</div>
								<div class='form-inline col-sm-6'>
									<label>Pekerjaan</label> :
									<select name='pekerjaan' id='pekerjaan' style='margin:5px' class='form-control'>
										<?php
										foreach ($pekerjaan as $dd) {
											?><option value='<?=$dd['idpkr_pkr']?>' <?php if($template['idpeker_pdk']==$dd['idpkr_pkr']){ echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
										}
										 ?>
									</select>
								</div>
								<div class='form-group col-sm-6'>
									<label>Agama</label> :
									<select name='agama' id='agama' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['agm_pdk']=='1'){echo 'selected';}?>>Islam</option>
										<option value='2' <?php if($template['agm_pdk']=='2'){echo 'selected';}?>>Kristen</option>
										<option value='3' <?php if($template['agm_pdk']=='3'){echo 'selected';}?>>Katholik</option>
										<option value='4' <?php if($template['agm_pdk']=='4'){echo 'selected';}?>>Budha</option>
										<option value='5' <?php if($template['agm_pdk']=='5'){echo 'selected';}?>>Hindu</option>
										<option value='6' <?php if($template['agm_pdk']=='6'){echo 'selected';}?>>Konghucu</option>
									</select>
								</div>
								<div class='form-group col-sm-12'>
									<label>Alamat Lengkap</label> :
									<textarea name='alamat' id='alamat' class='form-control' placeholder='alamat lengkap'><?= $template['almat_pdk']?></textarea>
								</div>
								<div class='form-group col-sm-3'>
									<label>RT</label> :
									<input type='text' name='rt' id='rt' class='form-control' placeholder='RT' value="<?= $template['rt_pdk'];?>">
								</div>
								<div class='form-group col-sm-3'>
									<label>RW</label> :
									<input type='text' name='rw' id='rw' class='form-control' placeholder='RW' value="<?= $template['rw_pdk'];?>">
								</div>
								<div class='form-group col-sm-6'>
									<label>Kelurahan</label> :
									<input type='text' name='kel' id='kel' class='form-control' placeholder='Kelurahan' value="<?= $template['kel_pdk'];?>">
								</div>
								<div class='form-group col-sm-6'>
									<label>Kecamatan</label> :
									<input type='text' name='kec' id='kec' class='form-control' placeholder='Kecamatan' value="<?= $template['kec_pdk'];?>">
								</div>
								<div class='form-group col-sm-6'>
									<label>Kabupaten</label> :
									<input type='text' name='kab' id='kab' class='form-control' placeholder='Kabupaten' value="<?= $template['kab_pdk'];?>">
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
									<input type='text' placeholder='Luas SPPT' name='luassppt' value='<?=$template['luassppt_ptsl'];?>' class='form-control'>
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
									<select name='guna' class='form-control'>
										<option value='1' <?php if($template['idguna_ptsl']=='1'){echo 'selected';}?>>Pertanian</option>
										<option value='2' <?php if($template['idguna_ptsl']=='2'){echo 'selected';}?>>Non Pertanian</option>
									</select>
								</div>
								<div class='form-group col-sm-4'>
									<label>Pemanfaatan</label> :
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
										<option value='12'<?php if($template['idmanfaat_ptsl']=='12'){echo 'selected';}?>>Tidak dimanfaatkan</option>
									</select>
								</div>
								<div class='form-group col-sm-4'>
									<label>Blok</label> :
									<input type='hidden' readonly name='templateid' value='<?= $template['id_ptsl'] ?>'>
									<input type='text' name='nameblok' value='<?= $block['nama_blk'] ?>' disabled class='form-control'>
									<input type='hidden' name='blok' value='<?= $block['idblk_blk'] ?>' class='form-control'>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Batas-Batas Tanah</h4>
								<div class='form-group col-sm-12'>
									<label>Utara</label> :
									<input type='text' name='utara' placeholder='batas utara' value='<?=$template['utara_ptsl'];?>' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Timur</label> :
									<input type='text' name='timur' placeholder='batas timur' value='<?=$template['timur_ptsl'];?>' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Selatan</label> :
									<input type='text' name='selatan' placeholder='batas selatan' value='<?=$template['selatan_ptsl'];?>' class='form-control'>
								</div>
								<div class='form-group col-sm-12'>
									<label>Barat</label> :
									<input type='text' name='barat' placeholder='batas barat' value='<?=$template['barat_ptsl'];?>' class='form-control'>
								</div>
							</div>
						</div>
						<div class='col-sm-6'>
							<div id='tanah' class='col-sm-12'>
								<h4>Riwayat Tanah</h4>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' name='des0' placeholder='deskripsi 0' style='width:100% !important'><?=$template['desc0_ptsl'];?></textarea>
								</div>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' name='des1' placeholder='deskripsi 1' style='width:100% !important'><?=$template['desc1_ptsl'];?></textarea>
								</div>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' name='des2' placeholder='deskripsi 2' style='width:100% !important'><?=$template['desc2_ptsl'];?></textarea>
								</div>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' name='des3' placeholder='deskripsi 3' style='width:100% !important'><?=$template['desc3_ptsl'];?></textarea>
								</div>
								<div class='form-group col-sm-12'>
									<textarea style='min-height:100px' class='form-control' name='des4' placeholder='deskripsi 4' style='width:100% !important'><?=$template['desc4_ptsl'];?></textarea>
								</div>
							</div>
							<div id='tanah' class='col-sm-12'>
								<h4>Alas Hak Pendaftaran</h4>
								<div class='form-inline col-sm-12'>
									<label>Nomor C</label> :
									<input type='text' name='dc' style='width:80px;margin:5px' class='form-control' value='<?=$template['dc_ptsl'];?>'>
									<label>Persil</label> :
									<input type='text' name='dpersil' style='width:80px;margin:5px' class='form-control' value='<?=$template['dpersil_ptsl'];?>'>
									<br><label>Klas</label> :
									<input type='text' name='dklas' style='width:80px;margin:5px' class='form-control' value='<?=$template['dklas_ptsl'];?>'>
									<label>Luas</label> :
									<input type='text' name='dluas' style='width:80px;margin:5px' class='form-control' value='<?=$template['dluas_ptsl'];?>'>
									<br><label>diperoleh dari :</label>
									<input type='text' name='ddari' style='width:300px;margin:5px' class='form-control' value='<?=$template['ddari_ptsl'];?>'>
									<br><label>Berdasarkan</label>
									<select name='dkeperluan' style='margin:5px' class='form-control'>
										<option value='1' <?php if($template['idkperluan_ptsl']=='1'){echo 'selected';}?>>Jual beli</option>
										<option value='2' <?php if($template['idkperluan_ptsl']=='2'){echo 'selected';}?>>Waris</option>
										<option value='3' <?php if($template['idkperluan_ptsl']=='3'){echo 'selected';}?>>Hibah</option>
									</select>
									<label>Tahun</label>
									<input type='text' name='dtahun' style='width:80px;margin:5px' value='<?=$template['thn_ptsl'];?>' class='form-control'>
								</div>
								<div class='col-sm-12 form-group'>
									<label>Catatan</label>
									<textarea class='form-control' placeholder='catatan' name='note'><?=$template['note_ptsl'];?></textarea>
								</div>
								<div class='col-sm-6 form-group'>
									<label>Tahun Risalah (poin 3a DI 201)</label>
									<input type='number' class='form-control' placeholder='tahun risalah' value='<?= $template['thn_risalah']?>' name='thn_risalah'>
								</div>
							</div>
							<div class='form-group'>
								<label>Upload KTP,KK dan SPPT</label>
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
							<?php
							if(strtolower($this->uri->segment(1))=='studio_7_2' || (strtolower($this->uri->segment(1))=='ajax' && $this->uri->segment(3)==7)){
								?>
							<div id='pengukuran' class='col-sm-12'>
								<h4>Pengukuran dan Pemetaan</h4>
								<div class='form-group'>
									<label>Luas</label>
									<input type='number' name='luas' value='<?= $template['luasfisik_ptsl']?>' placeholder="input luas" class='form-control'>
								</div>
								<div class='form-group'>
									<label>No. Berkas Fisik</label>
									<input type='text' name='noberkas' value='<?= $template['noberkas_ptsl']?>' placeholder="input no berkas" class='form-control'>
								</div>
								<div class='form-group'>
									<label>NIB</label>
									<?php if(strtolower($this->uri->segment(1))=='ajax'){?>
										<input type='text' name='nib' readonly value="<?= $template['nib_ptsl']?>" class='form-control'>
									<?php }else{?>
										<input type='text' name='nib' value='<?= $template['nib_ptsl']?>' placeholder="input nib" class='form-control'>
									<?php }?>
								</div>
							</div>
							<div id='pengukuran' class='col-sm-12'>
								<h4>E-Yuridis</h4>
								<div class='form-group'>
									<label>Seleksi Klaster</label>
									<select name='seleksik1' class='form-control'>
										<option <?php if($template['klaster_ptsl']=='k1'){echo 'selected';}?> value='k1'>k1</option>
										<option <?php if($template['klaster_ptsl']=='k2'){echo 'selected';}?> value='k2'>k2</option>
										<option <?php if($template['klaster_ptsl']=='k3 1'){echo 'selected';}?> value='k3 1'>k3 1</option>
										<option <?php if($template['klaster_ptsl']=='k3 2'){echo 'selected';}?> value='k3 2'>k3 2</option>
										<option <?php if($template['klaster_ptsl']=='k3 3'){echo 'selected';}?> value='k3 3'>k3 3</option>
									</select>
								</div>
								<div class='form-group'>
									<label>No.Berkas E-Yuridis</label>
									<input type='text' name='noberkasyuridis' value='<?= $template['noberkasyrd_ptsl']?>' placeholder="input no.berkas e-yuridis" class='form-control'>
								</div>
							</div>
								<?php
							}
							 ?>
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

	function removefile(id){
		$('#file'+id).remove();
	}

	function removechild(id){
		$('#obj'+id).remove();
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
						$("#alamat").val(response.content[0].ALAMAT+' RT.'+response.content[0].NO_RT+' RW.'+response.content[0].NO_RW+' '+response.content[0].KEL_NAME+' KEC.'+response.content[0].KEC_NAME+' KAB.'+response.content[0].KAB_NAME);
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
	});
</script>
