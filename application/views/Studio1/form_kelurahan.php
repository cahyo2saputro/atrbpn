<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($status == 'edit'){
	$id_kelurahan_fake = $get_data['id_kelurahan'];
	$id_kelurahan_edit = "$('#id_kelurahan').val('$id_kelurahan_fake').trigger('change');";
	$id_kecamatan_fake = $get_data['id_kecamatan'];
	$id_kecamatan_edit = "$('#id_kecamatan').val('$id_kecamatan_fake').trigger('change');";
}else{
	$get_data['id_kelkw']	= "";
	$get_data['jbid_kw']			= "";
	$get_data['tar_kw']				= "";
	$id_kelurahan_edit 				= "$('#id_kelurahan').select2();";
	$id_kecamatan_edit 				= "$('#id_kecamatan').select2();";
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
						<input type="hidden" name="id" id="id" value="<?=$get_data['id_kelkw']?>">

						<div class="form-group row">
							<label class="col-sm-3">Daftar Kecamatan</label>
							<div class="col-sm-9">
								<select class="form-control input-sm select2" id="id_kecamatan" name="id_kecamatan">
									<option>Pilih Kecamatan</option>
									<?php 
										foreach ($kecamatan as $kecamatan) {
											echo '<option value="'.$kecamatan->id_kecamatan.'">'.$kecamatan->id_kecamatan.'-'.$kecamatan->nama_kecamatan.'</option>';
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
										foreach ($kelurahan as $kelurahan) {
											echo '<option value="'.$kelurahan->id_kelurahan.'">'.$kelurahan->id_kelurahan.' - ' .$kelurahan->nama_kelurahan.'</option>';
											}
									?> 
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Jumlah Bidang</label>
							<div class="col-sm-9">
								<input type="text" name="jml_bidang" id="jml_bidang" class="form-control input-sm" value="<?=$get_data['jbid_kw']?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Target</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" id="target" name="target" value="<?=$get_data['tar_kw']?>">
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
		
		<?=$id_kecamatan_edit?>
		<?=$id_kelurahan_edit?>

		$('#btn-batal').click(function () {
			document.location='<?=base_url()?>kelurahan';
		})

		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							document.location='<?=base_url()?>kelurahan';
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});
		
		$('#id_kecamatan').change(function () {
				var id = $(this).val();
				$.ajax({
					url:'<?=base_url()?>kelurahan/cari_desa',
					method:'post',
					data:{id:id},
					async:false,
					dataType:'json',
					success:function(data){
						var html = '';
						var i;
						for(i=0;i<data.length;i++){
							html += '<option value ='+'"' +data[i].id_kelurahan + '">' +data[i].id_kelurahan+ '-' +data[i].nama_kelurahan+'</option>';
						}
						$('#id_kelurahan').html(html);
						$('#id_kelurahan').removeAttr('disabled');
					}
				});
			})
	})
</script>