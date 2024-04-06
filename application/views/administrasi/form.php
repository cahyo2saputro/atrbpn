<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($status == 'edit'){
	if($get_data['image_adt']!=""){
		$image = "<iframe src='".base_url()."DATA/SURAT/".$get_data['image_adt']."' style='width:500px;height:500px'></iframe>";
	}else{
		$image='';
	}
}else{
	$image='';
	$get_data=NULL;
}
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<form id="form-tambah" action="<?=$link?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="id" id="id" value="<?=$get_data['id_adt']?>">

						<div class="form-group row">
							<label class="col-sm-3">Kategori</label>
							<div class="col-sm-9">
								<select class="form-control input-sm select2" id="kategori" name="kategori">
									<option>Pilih Kategori</option>
									<?php
										foreach ($administrasi as $adm) {
											?><option <?php if($get_data['idadm_adt']==$adm['id_adm']){ echo 'selected';}?> value="<?=$adm['id_adm']?>"><?=$adm['name_adm']?></option>
											<?php
										}
									 ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">No. Surat</label>
							<div class="col-sm-9">
								<input type="text" name="nosurat" id="nosurat" class="form-control input-sm" value="<?=$get_data['no_adt']?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">Perihal</label>
							<div class="col-sm-9">
								<input type="text" name="perihal" id="perihal" class="form-control input-sm" value="<?=$get_data['perihal_adt']?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">Keterangan</label>
							<div class="col-sm-9">
								<input type="text" name="ket" id="ket" class="form-control input-sm" value="<?=$get_data['perihal_adt']?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">Tanggal</label>
							<div class="col-sm-9">
								<input type="text" name="tgl" id="tgl" class="form-control datepicker input-sm" value="<?=$get_data['tanggal_adt']?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">File Digital</label>
							<div class="col-sm-9">
								<input type="file" name="img" id="img" class="form-control input-sm"><br>
								<?= $image;?>
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

		$('#btn-batal').click(function () {
			document.location='<?=base_url()?>studio4';
		})

		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							document.location='<?=base_url()?>studio4';
						}else{
							swal("Error!", "Response Gagal", "error");
						}
					},
					error: function(){
						swal("Error!", "Response Gagal", "error");
					}
				}).submit();
			});
	})
</script>
