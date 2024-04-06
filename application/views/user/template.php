<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Datatables-->
<link href="<?php echo base_url();?>assets/js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datatables/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-12">
						<div class="pull-right">
							<a class="btn btn-sm btn-primary" href='<?= base_url() ?>Templateuser/add' id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></a>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-template" class="table table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Template</th>
									<th>Deskripsi Template</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
								$no=1;
								foreach ($template as $data) {
									?>
									<tr>
										<td><?=$no++;?></td>
										<td><?=$data['name_tmp']?></td>
										<td><?=$data['desc_tmp']?></td>
										<td>
											<a data-toggle="tooltip" title="edit data" href='<?= base_url()?>Templateuser/edit/<?=$data['id_tmp']?>' class='btn btn-warning'><span class="fa fa-edit"></span></a>
											<a data-toggle="tooltip" title="hapus data" onclick="return confirm('apakah kamu yakin mau menghapus ?')" href='<?= base_url()?>Templateuser/delete/<?= $data['id_tmp']?>' class='btn btn-danger'><span class="fa fa-trash"></span></a>
										</td>
									</tr>
									<?php
								}
								 ?>
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
		$('#data-template').DataTable();
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>
