<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="form-group row file-daftar">
						<label class="col-sm-3">NOP yang terdaftar</label>
						<div class="col-sm-9 form-inline">
							<b>
							<?php foreach ($nub as $dl) {
								echo $dl['idkel_blk'].$dl['nama_blk'].$dl['nosppt_dhkp'].'<br>';
							} ?>
							</b>
						</div>
					</div>
					<form id="form-tambah" method="post" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/addnop/<?= $this->uri->segment(3);?>/<?= $this->uri->segment(4);?>">
						<input type="text" hidden name="idblk_nub" id="idblk_nub" value="<?=$block['idblk_blk']?>">

						<div class="form-group row file-nohak">
							<label class="col-sm-3">Nomor Hak</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" id="nohak_nub" name="nohak_nub" style="width: 100%">
									 <option value="<?=$nohak['no_hak']?>"><?=$nohak['no_hak']?></option>
								</select>
							</div>
						</div>

						<div class="form-group row file-daftar">
							<label class="col-sm-3">Nib Peta</label>
							<div class="col-sm-9 form-inline">
								<input type='text' disabled style='width:50%;float:left' class='form-control' value='<?= $block['idkel_blk']?>'>
								<input type="text" style='width:50%;float:left' class="form-control input-sm" name="nib_peta" id="nib_peta" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">NOP</label>
							<div class="col-sm-9 form-group">
								<select class='form-control' id='dhkp' name='dhkp'>
									<option value=''>Pilih NOP</option>
									<?php foreach ($dhkp as $data) {
										?><option value='<?=$data['id_dhkp']?>'><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
									}?>
								</select>
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
		$.ajax({
				type: 'GET',
				url: '<?php echo base_url();?>ajax/cari_nib',
				data: 'id='+$('#nohak_nub').val(),
				dataType: 'html',
				beforeSend: function() {
				},
				success: function(response) {
						$("#nib_peta").val(response);
				}
		});

		$('#nohak_nub').select2();

			$('#btn-batal').click(function() {
							document.location='<?=base_url()?>studio_3_2/register/?search='+<?=$block['idblk_blk']?>;
			});

			$('#nohak_nub').on('change',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cari_nib',
						data: 'id='+$(this).val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#nib_peta").val(response);
						}
				});
			});

	})

	$(document).ready(function () {
		$('#dhkp').select2();
	})
</script>
