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
					<?php
					if($this->uri->segment(2)=='addpermohonan'){
					?><form id="form-tambah" method="post" enctype="multipart/form-data" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/<?=$this->uri->segment(2)?>/"><?php
				}else{
					?><form id="form-tambah" method="post" enctype="multipart/form-data" action="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/<?=$this->uri->segment(2)?>/<?=$this->uri->segment(3)?>"><?php
				}
					?>

						<div class="form-group row file-nohak">
							<label class="col-sm-3">No Berkas (KKP)</label>
							<div class="col-sm-6">
								<input type='text' class='form-control' name='noberkas' id='noberkas' value='<?=$selected['noberkas_png']?>'>
							</div>
						</div>
						<div class="form-group row file-nohak">
							<label class="col-sm-3">No STP (KKP)</label>
							<div class="col-sm-6">
								<input type='text' class='form-control' name='nostp' id='nostp' value='<?=$selected['nostp_png']?>'>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3">Foto STP</label>
							<div class="col-sm-6">
								<?php if($selected['stp_png']){
									?><img class='fancybox' style='max-height:300px' src='<?=base_url()?>/stp/<?=$selected['stp_png']?>' href='<?=base_url()?>/stp/<?=$selected['stp_png']?>'><?php
								} ?>
								<input type='file' class='form-control' name='stp'>
							</div>
						</div>
						<div class="form-group row file-nohak">
							<label class="col-sm-3">No Permohonan</label>
							<div class="col-sm-6">
								<select name='nopermohonan' id='nopermohonan' class='form-control'>
									<option value='' readonly>Pilih Nomer Permohonan</option>
									<?php
									if($selected['nope_png']){
										?><option value='<?= $selected['nope_png']?>' selected><?= $selected['nope_png']?></option><?php
									}
									foreach ($nopermohonan as $data) {
										?><option value='<?= $data['nope_srt']?>' <?php if($data['nope_srt']==$selected['nope_png']){echo 'selected';}?>><?= $data['nope_srt']?></option><?php
									}
									?>
								</select>
							</div>
							<div class='col-sm-3'>
								<span class='btn-primary btn' id='ceknik'>cek detail</span>
							</div>
						</div>
						<div id='dataview'>
						</div>
						<div class="form-group row file-nohak">
							<label class="col-sm-3">Tanggal Ukur</label>
							<div class="col-sm-6">
								<input type='text' name='tgl' id='tgl' class='datepicker form-control' value='<?=$selected['tglukur_png']?>'>
							</div>
						</div>
						<div class="form-group row file-nohak">
							<label class="col-sm-3">Petugas Ukur</label>
							<div class="col-sm-6">
								<select name='petugasukur' id='petugasukur' class='form-control'>
									<option value='' readonly>Pilih Petugas Ukur</option>
									<?php
									foreach ($petugasukur as $pu) {
										?><option value='<?= $pu['id_reg']?>' <?php if($pu['id_reg']==$selected['pu_png']){echo 'selected';}?>><?= $pu['nma_reg']?></option><?php
									}
									?>
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
			// ON ADD
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/cek_permohonan',
					data: 'permohonan='+$('#nopermohonan').val(),
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$("#dataview").html(response);
					}
			});

			$('#ceknik').on('click',function () {
				$.ajax({
						type: 'GET',
						url: '<?php echo base_url();?>ajax/cek_permohonan',
						data: 'permohonan='+$('#nopermohonan').val(),
						dataType: 'html',
						beforeSend: function() {
						},
						success: function(response) {
								$("#dataview").html(response);
						}
				});
			});

	});

	$(document).ready(function () {
		$('#nopermohonan').select2();
		$('#petugasukur').select2();

		$(".fancybox").fancybox({
				openEffect: "none",
				closeEffect: "none"
		});

	});


</script>
