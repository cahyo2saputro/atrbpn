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
							
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">NO</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">KODE</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">KECAMATAN</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">KELURAHAN</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">JUMLAH USER</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">SERTIPIKAT</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="3">JUMLAH PENGAJUAN</th>
								</tr>
								<tr>
									<th style="text-align: center; vertical-align: middle;">BELUM SERTIPIKAT</th>
									<th style="text-align: center; vertical-align: middle;" colspan="2">SUDAH SERTIPIKAT</th>
								</tr>
								<tr>
									<th style="text-align: center; vertical-align: middle;">PENGUKURAN</th>
									<th style="text-align: center; vertical-align: middle;">PENGUKURAN</th>
									<th style="text-align: center; vertical-align: middle;">VALIDASI SERTIPIKAT</th>
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
								 	<td style='text-align:center'> <?=$data['jumlahuser'];?></td>
								 	<td style='text-align:center'><?=$data['belumsertipikat'];?></td>
									<td style='text-align:center'><?=$data['sudahsertipikatukur'];?></td>
								 	<td style='text-align:center'><?=$data['sudahsertipikatcek'];?></td>
									<td style='text-align:center'><?= ($data['belumsertipikat']+$data['sudahsertipikatukur']+$data['sudahsertipikatcek'])?></td>
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
