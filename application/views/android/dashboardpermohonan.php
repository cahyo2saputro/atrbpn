<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$menu = $this->uri->segment(1);
$fungsi = $this->uri->segment(2);
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-left">
							<div class="form-inline">
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kecamatan</label><br>
										<select class="form-control input-sm" id="filter_kecamatan" style='width:150px'>
											<option value="0">Pilih Kecamatan</option>
											<?php
												foreach ($filter_kecamatan as $fk) {
											 ?>
											 <option value="<?=$fk->kd_kec?>"><?=$fk->nma_kec?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kelurahan</label><br>
										<select class="form-control input-sm" id="filter_kelurahan" style='width:150px'>
											<option value="0">Pilih Kelurahan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<br>
									<?php if($fungsi=='validation'){
											?><button class="btn btn-sm btn-primary" id="cari_valid">Cari</button><?php
									}else{
										?><button class="btn btn-sm btn-primary" id="cari_filter">Cari</button><?php
									} ?>

								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">NO</th>
									<th style="text-align: center; vertical-align: middle;">KODE</th>
									<th style="text-align: center; vertical-align: middle;">KECAMATAN</th>
									<th style="text-align: center; vertical-align: middle;">KELURAHAN</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS DIAJUKAN</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS FORM 2</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS ACC PERMOHONANKADES</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS PENGECEKAN</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS TERBIT NOMOR PENGESAHAN</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH STATUS DITOLAK</th>
									<th style="text-align: center; vertical-align: middle;">JUMLAH PERMOHONAN</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
								 ?>
								<tr>
								 	<td><?=$no++;?></td>
								 	<td> <?=$data['kdfull'];?> </td>
									<td> <?=$data['nma_kec'];?></td>
									<td> <?=$data['nma_kel'];?></td>
									<td style='text-align:center'> <?=$data['diajukan'];?></td>
								 	<td style='text-align:center'> <?=$data['form2'];?></td>
								 	<td style='text-align:center'><?=$data['kadesacc'];?></td>
									<td style='text-align:center'><?=$data['cek'];?></td>
								 	<td style='text-align:center'><?=$data['nosah'];?></td>
									<td style='text-align:center'><?=$data['tolak']?></td>
									<td style='text-align:center'><?=$data['permohonan']?></td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		/*$('#data_kelurahan').DataTable();*/
		$('#filter_kecamatan').select2();
		$('#filter_kelurahan').select2();


			$('#filter_kecamatan').on('change',function () {
				$.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>kelurahan/cekkelurahan',
            data: 'kecamatan='+$(this).val(),
            dataType: 'html',
            beforeSend: function() {
                $('#filter_kelurahan').html('Loading ....');
            },
            success: function(response) {
                $("#filter_kelurahan").html(response);
            }
        });
			});

			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>kelurahan/cekkelurahan',
					data: 'kecamatan='+$('#filter_kecamatan').val(),
					dataType: 'html',
					beforeSend: function() {
							$('#filter_kelurahan').html('Loading ....');
					},
					success: function(response) {
							$("#filter_kelurahan").html(response);
					}
			});
	})
</script>
