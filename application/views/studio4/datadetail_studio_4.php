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
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<tbody>
								<tr>
									<td>PPAT</td>
									<td>:</td>
									<td><?=$studio['name_usr']?></td>
								</tr>
								<tr>
									<td>NO Hak</td>
									<td>:</td>
									<td><?=$studio['nohak_hpat']?></td>
								</tr>
								<tr>
									<td>Kecamatan</td>
									<td>:</td>
									<td><?=$studio['kec']?></td>
								</tr>
								<tr>
									<td>Kelurahan</td>
									<td>:</td>
									<td><?=$studio['kel']?></td>
								</tr>
								<tr>
									<td>Dokumen</td>
									<td>:</td>
									<td><?php
									foreach ($image as $data) {
											?><img src='<?= base_url()?>HAKPPAT/<?=$data['image_img']?>' width='100%'><br><br><?php
									}
									?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
