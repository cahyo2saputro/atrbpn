<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ($status == 'edit') {
	$idkel = $get_data['idkel_blk']." ".$get_data['nma_kel'];
	$idkel_real = $get_data['idkel_blk'];
	if($get_data['petblk_blk']){
			$url_peta_block = "<a href='".base_url().'PETA/PETA_BLOCK/'.$get_data['petblk_blk']."'>File ada</a>";
	}else{
		$url_peta_block = "";
	}

	if($get_data['petptsl_blk']){
			$url_peta_ptsl = "<a href='".base_url().'PETA/PETA_PTSL/'.$get_data['petptsl_blk']."'>File ada</a>";
	}else{
		$url_peta_ptsl = "";
	}


}else{
	$idkel = $idkel_blk['kd_full']." ".$idkel_blk['nma_kel'];
	$idkel_real = $idkel_blk['kd_full'];

	$get_data['idblk_blk'] = "";
	$get_data['nama_blk'] = "";
	$url_peta_block = "";
	$url_peta_ptsl = "";
}
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">

					<form id="form-tambah" method="post" action="<?=$link?>">
						<input type="text" hidden name="id" id="id" value="<?=$get_data['idblk_blk']?>">
						<input type="text" hidden name="idkel_blk" id="idkel_blk" value="<?=$idkel_real?>">

						<div class="form-group row">
							<label class="col-sm-3">Kelurahan</label>
							<div class="col-sm-9">
								<input type="text" readonly class="form-control input-sm" name="" id="" value="<?=$idkel?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Nama Block</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="nama_blk" id="nama_blk" value="<?=$get_data['nama_blk']?>">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Peta Block</label>
							<div class="col-sm-9">
								<?= $url_peta_block; ?>
								<input type="file" class="form-control input-sm" name="petblk_blk" id="petblk_blk">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-3">Peta PTSL</label>
							<div class="col-sm-9">
								<?= $url_peta_ptsl; ?>
								<input type="file" class="form-control input-sm" name="petptsl_blk" id="petptsl_blk">
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

		$('#btn-simpan').click(function() {
				$('#form-tambah').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							document.location='<?=base_url()?>studio_2_1/index/?search='+'<?=$idkel_real?>';
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
