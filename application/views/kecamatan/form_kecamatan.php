<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($status == 'edit'){
	$link = base_url()."kecamatan/edit_kecamatan";
}else{
	$get_data['nama_kecamatan'] = "";
	/*$get_data['id_kelkw']	= "";
	$get_data['jbid_kw']			= "";
	$get_data['tar_kw']				= "";*/
	$link = base_url()."kecamatan/tambah_kecamatan";
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
						<input type="hidden" name="id" id="id" value="">

						<div class="form-group row">
							<label class="col-sm-3">Nama Kecamatan</label>
							<div class="col-sm-9">
								<input type="text" name="nama_kecamatan" id="nama_kecamatan" class="form-control input-sm" value="<?=$get_data['nama_kecamatan']?>">
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