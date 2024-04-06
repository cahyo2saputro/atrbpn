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

					<form id="form-tambah" method="post" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/editregister/<?= $this->uri->segment(3); ?>/<?= $this->uri->segment(4); ?>">
						<input type="text" hidden name="idblk_nub" id="idblk_nub" value="<?=$block['idblk_blk']?>">

						<div class="form-group row file-nohak">
							<label class="col-sm-3">Nomor Hak</label>
							<div class="col-sm-9">
								<select class="form-control input-sm" id="nohak_nub" name="nohak_nub" style="width: 100%">
									<?php
										foreach ($nohak as $dt_hak) {
									 ?>
									 <option value="<?=$dt_hak['no_hak']?>" <?php if($dt_hak['no_hak']==$dnohak['nohak_nub']){echo 'selected';}?>><?=$dt_hak['no_hak']?></option>
									<?php } ?>
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
								<?php $nom=0; $selected='';
								foreach ($sppt as $db):
									$nom++;?>
									<div id='obj<?=$nom?>' class='row obj' style='width:100%'>
										<div class='col-sm-8'>
											<input type='text' class='form-control' readonly value='<?= createkodebpkad($block['idkel_blk']).''.$block['nama_blk'].''.$db['nosppt_dhkp'];?>'>
											<input type='hidden' name='dhkp[]' value='<?= $db['iddhkp_nub']?>'>
										</div>
										<div class='col-sm-4'>
											<a class='btn btn-danger' onclick='removechild(<?=$nom?>)'>-</a>
										</div><br>
									</div>
								<?php
								$selected = $db['iddhkp_nub'];
								endforeach; ?>
								<input type='hidden' id='sumspt' value='1'>
								<div class='row' id='beforethis'>
									<div class='col-sm-8'>
										<select class='form-control' id='dhkp' name='dhkp[]' style="width: 100%">
											<option value=''>Pilih NOP</option>
											<?php foreach ($dhkp as $data) {
												?><option value='<?=$data['id_dhkp']?>' <?php if($selected==$data['id_dhkp']){echo 'selected';}?>><?= createkodebpkad($data['idkel_blk']).''.$block['nama_blk'].''.$data['nosppt_dhkp'];?></option><?php
											}?>
										</select>
									</div>
								<div class='col-sm-4'>
									<a id='plus' class='btn btn-warning' style='margin-left:-22px'>+</a>
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
		$('#nohak_nub').select2();

			$('#btn-batal').click(function() {
							document.location='<?=base_url()?>studio_3_2/register/?search='+<?=$block['idblk_blk']?>;
			});
		$('#dhkp').select2();

		removechild(<?=$nom?>);

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

	})

	$('#plus').on('click',function () {
		var sel = document.getElementById('dhkp');
		var opt = sel.options[sel.selectedIndex];
		var id = $('#sumspt').val();
		var text = "<div id='obj"+id+"' class='row obj' style='width:100%'><div class='col-sm-8'><input type='text' class='form-control' readonly value='"+opt.text+"'><input type='hidden' name='dhkp[]' value='"+opt.value+"'></div><div class='col-sm-4'><a class='btn btn-danger' onclick='removechild("+id+")'>-</a></div><br></div>"
		$('#sumspt').val(id+1);
		$(text).insertBefore($('#beforethis'));
	});

	function removechild(id){
		$('#obj'+id).remove();
	}

</script>
