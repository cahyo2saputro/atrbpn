<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$url_set = $this->input->get('search');
$user = $this->auth_model->get_userdata();
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
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;">No</th>
									<th style="text-align: center; vertical-align: middle;">Dusun</th>
									<th style="text-align: center; vertical-align: middle;">Peta Kerja</th>
									<th style="text-align: center; vertical-align: middle;">Obyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">Subyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">KK Subyek Redist</th>
									<th style="text-align: center; vertical-align: middle;">Lain-lain</th>
									<th style="text-align: center; vertical-align: middle;" width="20%">Action</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									$oby=0;
									$sby=0;
									$kksby=0;
									$lain=0;
									foreach ($block as $blk) {

										$url_peta_block = base_url().'PETA/PETA_DUSUN/'.$blk['peta_dsn'];

										if (empty($blk['peta_dsn'])) {
											$button_block = '<button class="btn btn-sm btn-danger btn-open-block" data-toggle="tooltip" title="upload peta dusun" id="" data-id="'.$blk['id_dsn'].'"><span class="fa  fa-cloud-upload"></span></button>';
										}else{
											$button_block = '<button class="btn btn-sm btn-danger btn-open-block" data-toggle="tooltip" title="upload peta dusun" id="" data-id="'.$blk['id_dsn'].'"><span class="fa  fa-cloud-upload"></span></button>
											<a href="'.$url_peta_block.'"><button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button></a>';
										}
								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td><?=$blk['name_dsn']?></td>
									<td align='center'><?=$button_block?></td>
									<td style="text-align: center;">
								 		<?=$blk['jml_oby'];$oby+=$blk['jml_oby']?>
								 	</td>
									<td style="text-align: center;">
										<?=$blk['jml_sby'];$sby+=$blk['jml_sby']?>
								 	</td>
								 	<td style="text-align: center;">
								 		<?=$blk['jml_kksby'];$kksby+=$blk['jml_kksby']?>
								 	</td>
								 	<td style="text-align: center;"><?=$blk['jml_lain'];$lain+=$blk['jml_lain']?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button data-toggle="tooltip" title="lihat data e-gtra" class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk['id_dsn']?>" data-kel="<?=$url_set?>"><span class="fa fa-clipboard"></span></button>
												<?php
												if (in_array(132, $_SESSION['menu']) || $user['level_usr']==1) {
									        ?><button data-toggle="tooltip" title="form saksi" class="btn btn-sm btn-default" id="btn-saksi" data-id="<?=$blk['id_dsn']?>" data-kel="<?=$url_set?>"><span class="fa fa-file-o"></span></button><?php
									      }
												?>
										</div>
								 	</td>
								 </tr>
								<?php } ?>
								<tr>
								 <td colspan='3'>Total</td>
								 <td style="text-align: center;">
									 <?=$oby?>
								 </td>
								 <td style="text-align: center;">
									 <?=$sby?>
								 </td>
								 <td style="text-align: center;">
									 <?=$kksby?>
								 </td>
								 <td style="text-align: center;"><?=$lain?></td>
								 <td style="text-align: center;">
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

<!-- modal block -->
<div id="modal-block" class="modal fade" role="dialog">
	<form id="form-block" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-block">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-sm-3">File Upload</label>
						<div class="col-sm-9">
							<input type="file" class="form-control input-sm" name="petblk_blk" id="petblk_blk">
						</div>
					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary btn-sm" id="btn-simpan-block">Simpan</button>
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
	$(document).ready(function () {

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			var kel = $(this).data('kel');
			window.open('<?=base_url()?>egtra/data/'+id+'?search='+kel,'_self',false);
		})

		$("#tabel-body").on('click','#btn-saksi',function () {
			var id = $(this).data('id');
			var kel = $(this).data('kel');
			window.open('<?=base_url()?>egtra/form_saksi/'+id+'?search='+kel,'_self',false);
		})

		$('.btn-open-block').click(function(){
			$('.mt-block').html('Upload/Download Peta Kerja');
			var idblk = $(this).data('id');
			$('#form-block').attr('action','<?=base_url()?>egtra/simpan_peta/'+idblk);
			$('#form-block')[0].reset();
			$('#modal-block').modal('show');
		});

		$('#btn-simpan-block').click(function() {
				$('#form-block').ajaxForm({
					success: 	function(response){
						if(response=='true'){
							swal("Success!", "Response Berhasil", "success");
							location. reload(true);
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
