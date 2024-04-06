<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($status == 'edit'){
	$id_kelurahan_fake = $get_data['kd_full'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";
	$id_kecamatan_fake = $get_data['kd_kec'];
	$id_kecamatan_edit = "$('#id_kecamatan').val('$id_kecamatan_fake').trigger('change');";
	$display 		   = "none";

	$dd = explode('.',$get_data['no_hak']);
	$nohak = $dd[5];
	$prefik = $dd[0].'.'.$dd[1].'.'.$dd[2].'.'.$dd[3].'.'.$dd[4].'.';
	$kodekel = $dd[3];
	$kode_hak_fake = $get_data['kdhak_hak'];
	$kode_hak_edit = "$('#kode_hak').val('$kode_hak_fake').trigger('change');";
}else{
	$kode_hak_edit 				= "";
	$nohak = "";$prefik="";$kodekel="";
	$display 					="none";
	$get_data['id_studio_1_1']	= "";
	$get_data['no_hak']			= "";
	$get_data['nib_hak']			= "";
	$get_data['pma_hak'] = "";
	$get_data['pmi_hak'] = "";
	$get_data['jenis_kw_awal']	= "";
	$id_kelurahan_edit 		= "$('#id_kelurahan').select2();";
	$id_kecamatan_edit 		= "$('#id_kecamatan').select2();";
}
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<form id="form-tambah" action="<?=$link?>" method="post">
						<input type="hidden" name="id" id="id" value="<?=$get_data['id_studio_1_1']?>">
						<input type="hidden" id="kodefullset">
						<div class="form-group row">
							<label class="col-sm-3">Daftar Kecamatan</label>
							<div class="col-sm-9">
								<select class="form-control input-sm select2" id="id_kecamatan" name="id_kecamatan">
									<option>Pilih Kecamatan</option>
									<?php
										foreach ($kecamatan as $kecamatan) {
											echo '<option value="'.$kecamatan->kd_kec.'">'.$kecamatan->kd_kec.'-'.$kecamatan->nma_kec.'</option>';
										}
									 ?>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Daftar Desa</label>
							<div class="col-sm-9">
								<select class="form-control input-sm select2" id="id_kelurahan" name="id_kelurahan">
									<option>Pilih Kelurahan</option>
									<?php
										// foreach ($kelurahan as $kelurahan) {
										// 	echo '<option value="'.$kelurahan->kd_kel.'">'.$kelurahan->kd_full.' - ' .$kelurahan->nma_kel.'</option>';
										// 	}
									?>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Kode Hak</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" id="kode_hak" name="kode_hak">
									<option>Pilih Kode Hak</option>
									<option value="1">1 - Milik (HM)</option>
									<option value="2">2 - Guna Usaha (HGU)</option>
									<option value="3">3 - Guna Bangunan (HGB)</option>
									<option value="4">4 - Pakai (HP)</option>
									<option value="5">5 - Pengelolaan (HPL)</option>
									<option value="6">6 - Tanggungan (HT)</option>
									<option value="7">7 - Rumah Susun (HMS)</option>
									<option value="8">8 - Wakaf (HW)</option>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Nomor Hak</label>
							<div class="col-sm-3">
								<input type="text" name="no_hakfront" id="no_hakfront" class="form-control input-sm" value="<?=$prefik;?>" readonly>
							</div>
							<div class="col-sm-6">
								<input type="text" name="no_hak" id="no_hak" class="form-control input-sm" value="<?=$nohak?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">NIB</label>
							<div class="col-sm-9">
								<input type="text" name="nib" id="nib" class="form-control input-sm" value="<?=$get_data['nib_hak']?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Nama Pemilik pertama</label>
							<div class="col-sm-9">
								<input type="text" name="pemilik_pertama" id="pemilik_pertama" class="form-control input-sm" value="<?=$get_data['pma_hak']?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Nama Pemilik Terakhir</label>
							<div class="col-sm-9">
								<input type="text" name="pemilik_terakhir" id="pemilik_terakhir" class="form-control input-sm" value="<?=$get_data['pmi_hak']?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Jenis KW Awal</label>
							<div class="col-sm-9">
								<select class='form-control' id="jenis_kw_awal" name="jenis_kw_awal">
									<option value='KW1' <?php if($get_data['jenis_kw_awal']=='KW1'){echo 'selected';}?>>KW1</option>
									<option value='KW2' <?php if($get_data['jenis_kw_awal']=='KW2'){echo 'selected';}?>>KW2</option>
									<option value='KW3' <?php if($get_data['jenis_kw_awal']=='KW3'){echo 'selected';}?>>KW3</option>
									<option value='KW4' <?php if($get_data['jenis_kw_awal']=='KW4'){echo 'selected';}?>>KW4</option>
									<option value='KW5' <?php if($get_data['jenis_kw_awal']=='KW5'){echo 'selected';}?>>KW5</option>
								</select>
							</div>
						</div>


					<div class="box-footer">
						<div class="col-sm-12">
							<div class="pull-right">
								<button type="button" id="btn-batal" class="btn btn-warning">Batal</button>
								<button type="button" id="btn-simpan" class="btn btn-primary">Simpan</button>
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
		$('.select2').select2();
		$('#kode_hak').select2();

		<?=$id_kecamatan_edit?>
		<?=$id_kelurahan_edit?>
		<?=$kode_hak_edit?>

		$('#btn-batal').click(function () {
			history.go(-1);
		})

		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							document.location='<?=base_url()?>studio_1_1/index/?search='+$('#kodefullset').val();
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});

			$('#id_kecamatan').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/cekkelurahan',
						data: 'kecamatan='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
								$('#id_kelurahan').html('Loading ....');
						},
						success: function(response) {
								$("#id_kelurahan").html(response);
						}
				});

				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/getkode',
						data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan='+$(this).val()+'&kodehak='+$('#kode_hak').val(),
						dataType: 'html',
						beforeSend: function() {
								$('#no_hakfront').val('Loading ....');
						},
						success: function(response) {
								$("#no_hakfront").val(response);
						}
				});

				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/getfullkode',
						data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
								$('#kodefullset').val('Loading ....');
						},
						success: function(response) {
								$("#kodefullset").val(response);
						}
				});
			});

			$('#id_kelurahan').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/getkode',
						data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan='+$(this).val()+'&kodehak='+$('#kode_hak').val(),
						dataType: 'html',
						beforeSend: function() {
								$('#no_hakfront').val('Loading ....');
						},
						success: function(response) {
								$("#no_hakfront").val(response);
						}
				});

				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/getfullkode',
						data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
								$('#kodefullset').val('Loading ....');
						},
						success: function(response) {
								$("#kodefullset").val(response);
						}
				});
			});

			$('#kode_hak').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>kelurahan/getkode',
						data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan='+$(this).val()+'&kodehak='+$('#kode_hak').val(),
						dataType: 'html',
						beforeSend: function() {
								$('#no_hakfront').val('Loading ....');
						},
						success: function(response) {
								$("#no_hakfront").val(response);
						}
				});
			});

			<?php if($status == 'edit'){ ?>
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>kelurahan/getkelurahan',
					data: 'kecamatan='+$('#id_kecamatan').val()+'&kdkel=<?php echo $kodekel;?>',
					dataType: 'html',
					beforeSend: function() {
							$('#id_kelurahan').html('Loading ....');
					},
					success: function(response) {
							$("#id_kelurahan").html(response);
					}
			});

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>kelurahan/getfullkode',
					data: 'kecamatan='+$('#id_kecamatan').val()+'&kelurahan=<?php echo $kodekel;?>',
					dataType: 'html',
					beforeSend: function() {
							$('#kodefullset').val('Loading ....');
					},
					success: function(response) {
							$("#kodefullset").val(response);
					}
			});
			<?php } ?>

	})
</script>
