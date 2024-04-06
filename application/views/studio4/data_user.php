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
					<form action='' method='get'>
					<div class="form-inline">
						<div class="form-group">
							<input type="text" class="form-control input-sm" name="user" id="nama_blk" placeholder="Cari Nama PPAT">
						</div>
						<div class="form-group">
							<button class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
						</div>
					</div>
					</form>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama PPAT</th>
									<th>User PPAT</th>
									<th>Pelanggaran</th>
									<th style="width: 13%">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $st) {

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$st['name_usr']?></td>
								 	<td><?=$st['usrid_usr']?></td>
								 	<td><?=$st['warning']?></td>
								 	<td>
								 		<div class="btn-group">
											<?php
													?><a data-toggle="tooltip" title='melanggar kebijakan' href='<?= base_url()?>/Apimobile/warning/<?=$st['idusr_usr']?>' class="btn btn-sm btn-danger" id="btn-edit" ><span class="fa fa-exclamation-triangle"></span></a><?php
											 ?>
								 		</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
						<?php
						echo $link;
						 ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
