<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$select = '';
$user = $this->auth_model->get_userdata();
if($user['level_usr']==7){
	$select = 'disabled';
}

?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<?php
					if($this->uri->segment(2)=='addpermohonan'){
							?><form id="form-tambah" method="post" enctype="multipart/form-data" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/addpermohonan/"><?php
					}else{
						?><form id="form-tambah" method="post" enctype="multipart/form-data" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/editpermohonan/<?=$this->uri->segment(3)?>"><?php
					}
					 ?>

						<div class="form-group row file-nohak">
							<label class="col-sm-3">NIK</label>
							<div class="col-sm-5">
								<input type='text' class='form-control' name='nik' id='nik' value='<?=$selected['nik_reg']?>'>
							</div>
							<div class='col-sm-4'>
								<span class='btn-primary btn' id='ceknik'>Cek Registrasi</span>
							</div>
						</div>
						<div id='dataview'>
						</div>
						<div class="form-group row file-daftar">
							<label class="col-sm-3">Kecamatan</label>
							<div class="col-sm-6">
								<select name='kecamatan' id='kecamatan' class='form-control' <?=$select;?>>
									<option value='' readonly>Pilih Kecamatan</option>
									<?php
									foreach ($kecamatan as $kec) {
										?><option value='<?= $kec['kd_kec']?>' <?php if($kec['kd_kec']==$userlev['kec_srt']){echo 'selected';}?>><?= $kec['nma_kec']?></option><?php
									}
									?>
								</select>
								<?php
								if($user['level_usr']==7){
									?>
									<input type='hidden' name='kecamatan'  value='<?=$userlev['kec_srt']?>' >
									<input type='hidden' name='kelurahan'  value='<?=$userlev['kel_srt']?>' >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group row kelurahan">
							<label class="col-sm-3">Kelurahan</label>
							<div class="col-sm-6">
								<input type='hidden' id='idkelurahan' value='<?=$userlev['kel_srt']?>'>
								<select name='kelurahan' id='kelurahan' class='form-control' <?=$select;?>>

								</select>
							</div>
						</div>
						<div class="form-group row file-daftar">
							<label class="col-sm-3">Sertipikat / Belum Sertipikat</label>
							<div class="col-sm-6">
								<select name='sertipikat' id='sertipikat' class='form-control'>
									<option value='' readonly>-- Pilih --</option>
									<option value='0' <?php if($selected['sert_srt']=='0'){echo 'selected';}?>>Belum Sertipikat</option>
									<option value='1' <?php if($selected['sert_srt']==1){echo 'selected';}?>>Sudah Sertipikat</option>
								</select>
							</div>
						</div>
						<div id='belumsertipikat'>
							<div class="form-group row">
								<label class="col-sm-3">No. Letter C</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='noc' value='<?=$ref[0]?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Persil</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='persil' value='<?=$ref[1]?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Klas</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='klas' value='<?=$ref[2]?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Atas Nama</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='atna' value='<?=$ref[3]?>'>
								</div>
							</div>
						</div>
						<div id='sudahsertipikat'>
						<div class="form-group row">
							<label class="col-sm-3">Alas Hak</label>
							<div class="col-sm-6">
								<select name='keperluan' id='keperluan' class='form-control'>
									<option value='1' <?php if($ref[0]==1){echo 'selected';}?>>Milik (HM)</option>
									<option value='2' <?php if($ref[0]==2){echo 'selected';}?>>Guna Usaha (HGU)</option>
									<option value='3' <?php if($ref[0]==3){echo 'selected';}?>>Guna Bangunan (HGB)</option>
									<option value='4' <?php if($ref[0]==4){echo 'selected';}?>>Pakai (HP)</option>
									<option value='5' <?php if($ref[0]==5){echo 'selected';}?>>Pengelolaan (HPL)</option>
									<option value='6' <?php if($ref[0]==6){echo 'selected';}?>>Tanggungan (HT)</option>
									<option value='7' <?php if($ref[0]==7){echo 'selected';}?>>Rumah Susun (HMS)</option>
									<option value='8' <?php if($ref[0]==8){echo 'selected';}?>>Wakaf (HW)</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">No. Hak</label>
							<div class="col-sm-6">
								<input type='text' class='form-control' name='ref' value='<?=$ref[1]?>'>
							</div>
						</div>
						</div>
						<input type='hidden' id='statuscek' value='<?=$selected['status_srt']?>'>
						<div id='optional'>
							<div class="form-group row file-daftar">
			          <label class="col-sm-3">Keperluan</label>
			          <div class="col-sm-6">
			            <select name='pengajuan' id='pengajuan' class='form-control'>

									</select>
								</div>
							</div>
						</div>
						<div id='multiplesetipikat' class='col-sm-12'>
							<div class='form-group'>
								<label>Upload Sertipikat</label><br>
								<?php if($imgsertipikat){
									foreach ($imgsertipikat as $im) {
									?><div id="exs<?=$im['id_isrt']?>" style='border:1px solid #dadada;padding:5px;float:left;text-align:center'><img class='fancybox' style='max-height:300px' src='<?=base_url()?>/sertipikat/<?=$im['image_isrt']?>' href="<?=base_url()?>/sertipikat/<?=$im['image_isrt']?>">
										<br>
										<a class="btn btn-danger" onclick="hapus(<?=$im['id_isrt']?>,'sertipikat')">x</a>
										</div>
										<?php
									}
								}?>
								<input type='file' name='berkas[]' class='form-control' style="width:75%">
							</div>
							<input type='hidden' id='sumfile' value='1'>
							<div class="row" id='beforethisfile'>
								<div class='col-sm-8'>
									<a id='plusfile' class='btn btn-warning'>+</a>
								</div>
							</div>
						</div>
						<div id='fullbelumsertipikat'>
							<div class="form-group row">
								<label class="col-sm-3">Batas utara</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='utara' value='<?=$permohonan['utara_pmh']?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Batas Barat</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='barat' value='<?=$permohonan['barat_pmh']?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Batas Selatan</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='selatan' value='<?=$permohonan['selatan_pmh']?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Batas Timur</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='timur' value='<?=$permohonan['timur_pmh']?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Luas</label>
								<div class="col-sm-6">
									<input type='text' class='form-control' name='luas' value='<?=$permohonan['luas_pmh']?>'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">Foto SPPT PBB</label>
								<div class="col-sm-6">
									<?php if($permohonan['imgnop_pmh']){
										?><img class='fancybox' style='max-height:300px' src='<?=base_url()?>/sppt/<?=$permohonan['imgnop_pmh']?>' href='<?=base_url()?>/sppt/<?=$permohonan['imgnop_pmh']?>'><?php
									} ?>
									<input type='file' class='form-control' name='sppt'>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3">NOP</label>
								<div class="col-sm-6">
									<input type='hidden' id='iddhkp' value='<?=$permohonan['iddhkp_pmh']?>'>
									<select name='nop' id='nop' class='form-control'>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row" id='kuasa'>
							<label class="col-sm-3">Dengan Kuasa</label>
							<div class="col-sm-6">
								<select name='pilihkuasa' id='pilihkuasa' class='form-control'>
									<option value='0' <?php if($permohonan['kuasa_pmh']==0){echo 'selected';}?>>Tanpa Kuasa</option>
									<option value='1' <?php if($permohonan['kuasa_pmh']==1){echo 'selected';}?>>Dengan Kuasa</option>
								</select>
							</div>
						</div>
						<div id='namakuasa' class='row'>
							<div class='col-sm-6'>
									<h4>Input Kuasa</h4>
									<div class='form-group col-sm-12'>
										<div class='col-sm-8'>
											<label>No.KTP</label> :
											<input type='text' name='ktp' id='ktp' class='form-control' placeholder='no. ktp (16 digit)' value='<?=$kuasa['noktp_pdk']?>'>
										</div>
										<div class='col-sm-4'>
											<label>Cek Dukcapil</label>
											<input type='button' value='Cek' id='cktp' class='btn btn-warning'>
										</div>
									</div>
									<div class='form-group col-sm-12'>
										<label>Nama</label> :
										<input type='text' name='namakuasa' id='nama' class='form-control' placeholder='nama lengkap' value='<?=$kuasa['nma_pdk']?>'>
									</div>
									<div id='cek'></div>
									<div class='form-inline col-sm-4'>
										<label>Tempat Lahir</label> :
										<input type='text' name='ttl' id='ttl' placeholder='tempat lahir' style='margin:5px;width:150px' class='form-control' value='<?=$kuasa['ttl_pdk']?>'>
									</div>
									<div class='form-inline col-sm-4'>
										<label>Tanggal Lahir</label> :
										<input type='text' name='tgl' id='tgl' style='margin:5px;width:150px' class='datepicker form-control' value='<?=$kuasa['ttg_pdk']?>'>
									</div>
									<div class='form-inline col-sm-6'>
										<label>Pekerjaan</label> :
										<select name='pekerjaan' id='pekerjaan' style='margin:5px' class='form-control'>
											<?php
											foreach ($pekerjaan as $dd) {
												?><option value='<?=$dd['idpkr_pkr']?>' <?php if($dd['idpkr_pkr']==$kuasa['idpeker_pdk']){echo 'selected';}?>><?=$dd['nama_pkr']?></option><?php
											}
											 ?>
										</select>
									</div>
									<div class='form-group col-sm-6'>
										<label>Agama</label> :
										<select name='agama' id='agama' style='margin:5px' class='form-control'>
											<option value='1' <?php if($kuasa['agm_pdk']==1){echo 'selected';}?>>Islam</option>
											<option value='2' <?php if($kuasa['agm_pdk']==2){echo 'selected';}?>>Kristen</option>
											<option value='3' <?php if($kuasa['agm_pdk']==3){echo 'selected';}?>>Katholik</option>
											<option value='4' <?php if($kuasa['agm_pdk']==4){echo 'selected';}?>>Budha</option>
											<option value='5' <?php if($kuasa['agm_pdk']==5){echo 'selected';}?>>Hindu</option>
											<option value='6' <?php if($kuasa['agm_pdk']==6){echo 'selected';}?>>Konghucu</option>
										</select>
									</div>
									<div class='form-group col-sm-12'>
										<label>Alamat Lengkap</label> :
										<textarea name='alamat' id='alamat' class='form-control' placeholder='alamat lengkap'><?=$kuasa['almat_pdk']?></textarea>
									</div>
								</div>
						</div>
						<div class="box-footer">
							<div class="col-sm-12">
								<div class="pull-right">
									<button type="button" id="btn-batal" class="btn btn-warning">Batal</button>
									<button type="submit" id="btn-simpan" class="btn btn-primary">Simpan</button>
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
		$('#kuasa').hide();
		$('#namakuasa').hide();
		$('#multiplesetipikat').hide();
		$('#fullbelumsertipikat').hide();
		$('#sudahsertipikat').hide();
		$('#belumsertipikat').hide();
		$('#optional').hide();

			// ON EDIT
			if($('#nik').val()!=''){
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cek_register',
						data: 'nik='+$('#nik').val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#dataview").html(response);
						}
				});
			}

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_kelurahan/'+$('#idkelurahan').val(),
					data: 'kec='+$('#kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#kelurahan").html(response);
					}
			});

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_pengajuan',
					data: 'sertipikat='+$('#sertipikat').val()+'&status='+$('#statuscek').val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#pengajuan").html(response);
							if($("#pengajuan").val()==1){
								$('#fullbelumsertipikat').hide();
								$('#kuasa').hide();
							}else{
								$('#fullbelumsertipikat').show();
								$('#kuasa').show();
							}
					}
			});

			if($('#sertipikat').val()=='0'){
				$('#kuasa').show();
				$('#multiplesetipikat').hide();
				$('#fullbelumsertipikat').show();
				$('#sudahsertipikat').hide();
				$('#optional').show();
				$('#belumsertipikat').show();
			}else if($('#sertipikat').val()=='1'){
				$('#kuasa').show();
				$('#multiplesetipikat').show();
				$('#fullbelumsertipikat').show();
				$('#sudahsertipikat').show();
				$('#optional').show();
				$('#belumsertipikat').hide();
			}else{
				$('#kuasa').hide();
				$('#multiplesetipikat').hide();
				$('#fullbelumsertipikat').hide();
				$('#sudahsertipikat').hide();
				$('#optional').hide();
				$('#belumsertipikat').hide();
			}

			if($('#pilihkuasa').val()==1){
				$('#namakuasa').show();
			}else{
				$('#namakuasa').hide();
			}

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cari_nop/'+$('#iddhkp').val(),
					data: 'kel='+$('#idkelurahan').val()+'&kec='+$('#kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#nop").html(response);
					}
			});

			// ON ADD

			$('#ceknik').on('click',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cek_register',
						data: 'nik='+$('#nik').val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#dataview").html(response);
						}
				});
			});

			$('#kecamatan').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cari_kelurahan',
						data: 'kec='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#kelurahan").html(response);
						}
				});

				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cari_nop',
						data: 'kel='+$('#kelurahan').val()+'&kec='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#nop").html(response);
						}
				});
			});

			$('#kelurahan').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cari_nop',
						data: 'kel='+$(this).val()+'&kec='+$('#kecamatan').val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#nop").html(response);
						}
				});
			});

			$('#sertipikat').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cari_pengajuan',
						data: 'sertipikat='+$(this).val()+'&status='+$('#statuscek').val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#pengajuan").html(response);
						}
				});

				if($(this).val()==0){
					$('#kuasa').show();
					$('#multiplesetipikat').hide();
					$('#fullbelumsertipikat').show();
					$('#sudahsertipikat').hide();
					$('#optional').show();
					$('#belumsertipikat').show();
				}else{
					$('#kuasa').show();
					$('#multiplesetipikat').show();
					$('#fullbelumsertipikat').show();
					$('#sudahsertipikat').show();
					$('#optional').show();
					$('#belumsertipikat').hide();
				}
			});

			$('#pilihkuasa').on('change',function () {
				if($(this).val()==1){
					$('#namakuasa').show();
				}else{
					$('#namakuasa').hide();
				}
			});

			$('#pengajuan').on('change',function () {
				if($(this).val()==1){
					$('#fullbelumsertipikat').hide();
					$('#kuasa').hide();
				}else{
					$('#fullbelumsertipikat').show();
					$('#kuasa').show();
				}
			});
	})

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

	$(document).ready(function () {
		$('#kecamatan').select2();
		$('#kelurahan').select2();
		$('#nop').select2();

		$(".fancybox").fancybox({
				openEffect: "none",
				closeEffect: "none"
		});

	});

	function hapus(id,jenis){
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
							$('#exs'+id).remove();
					}
			});
		}else{
			return false;
		}

	}


</script>
