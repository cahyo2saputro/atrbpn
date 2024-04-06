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
							<div class="form-inline">
								<div class="form-group">
									<input type="text" class="form-control input-sm" name="nama_blk" id="nama_blk" placeholder="Cari Nama Block">
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary" id="btn-cari">Cari <span class="fa fa-search"></span></button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table id="data-staff" class="table table-bordered">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Block</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">Download Peta PTSL</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">DHKP</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">Bidang Tanah Terentri</th>
									<th style="text-align: center; vertical-align: middle;" width="20%" rowspan="2">Action</th>
								</tr>
								<tr>
									<th style="text-align: center;">Sudah Sertipikat</th>
									<th style="text-align: center;">Belum Sertipikat</th>
									<th style="text-align: center;">Total</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									$sudah=0;
									$belum=0;
									$dhkp=0;
									foreach ($block as $blk) {

										$disabled = "";

										if ($blk->jml_ptsl==0) {
									 		$button_ptsl = '';
									 	}else{
									 		$button_ptsl = '<a class="open-berkas" data-id="'.$blk->idblk_blk.'" data-jenis="ptsl">
													<button data-toggle="tooltip" title="download peta ptsl" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
												</a>';
									 	}

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td>Blok <?=$blk->nama_blk?></td>
									<td style="text-align: center;">
										<div class="btn-group">
											<?php
													if (in_array(76, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_ptsl?><?php
													}
												 ?>
								 		</div>
								 	</td>
									<td style="text-align: center;"><?=$blk->jml_dhkp?><?php $dhkp+=$blk->jml_dhkp?></td>
									<td style="text-align: center;"><?=$blk->jml_terdaftar?><?php $sudah+=$blk->jml_terdaftar;?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?><?php $belum+=$blk->jml_tidak;?></td>
								 	<td style="text-align: center;"><?=$total = $blk->jml_terdaftar+$blk->jml_tidak?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button <?=$disabled?> data-toggle="tooltip" title="lihat data" class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-clipboard"></span></button>
												<?php
												if (in_array(77, $_SESSION['menu']) || $user['level_usr']==1) {
													?><a data-toggle="tooltip" title='export data nominatif belum sertipikat' href="<?= base_url('studio_3_1/export').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a>
													<a data-toggle="tooltip" title='export data nominatif hak link nop' href="<?= base_url('studio_3_1/exportnub').'?search='.$blk->idkel_blk.'&blok='.$blk->idblk_blk; ?>" class="btn btn-sm btn-warning"><span class="fa fa-file"></span></a><?php
												}?>
												<?php
												if((in_array(106, $_SESSION['menu']) || $user['level_usr']==1) && $blk->petonline_blk){
													?><button data-toggle="tooltip" title='lihat map' class="btn btn-sm btn-danger" data-id="<?=$blk->idblk_blk?>" id="btn-map-online" type="button"><span class="fa fa-map"></span></button><?php
												}
												if (in_array(134, $_SESSION['menu']) || $user['level_usr']==1) {
													?>
													<a data-toggle="tooltip" title="input saksi ptsl" href='<?= base_url()?>studio_3_2/formsaksi/<?=$blk->idblk_blk;?>'><button class="btn btn-sm btn-default" id="btn-saksi"><span class="fa fa-address-book-o"></span></button></a>
													<a data-toggle="tooltip" title="input puldatan" href='<?= base_url()?>studio_3_2/formpuldatan/<?=$blk->idblk_blk;?>'><button class="btn btn-sm btn-primary" id="btn-puldatan"><span class="fa fa-address-book-o"></span></button></a>
													<?php
												}
												 ?>
										</div>
								 	</td>
								 </tr>
								<?php } ?>
								<tr>
									<th style='text-align:center' colspan='3'>Total</th>
									<th style='text-align:center'><?=$dhkp;?></th>
									<th style='text-align:center'><?=$sudah;?></th>
									<th style='text-align:center'><?=$belum;?></th>
									<th style='text-align:center'><?=$sudah+$belum;?></th>
									<th></th>
								</tr>
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

<!-- modal ptsl -->
<div id="modal-ptsl" class="modal fade" role="dialog">
	<form id="form-ptsl" method="POST" action="">
		<div class="modal-dialog modal-lg">
			<!-- konten modal-->
			<div class="modal-content">
				<!-- heading modal -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title mt-ptsl">Bagian heading modal</h5>
				</div>
				<!-- body modal -->
				<div class="modal-body">
					<div class='dataku'>

					</div>
				</div>
				<!-- footer modal -->
				<div class="modal-footer">
					<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">close</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$('#btn-cari').on('click',function () {
			var nama = $('#nama_blk').val();
			window.open('<?=base_url()?>Studio_6_1/index/?search='+nama,'_self',false);
		})

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studio_7_2/data/?search='+id,'_self',false);
		})

		$('.open-berkas').click(function(){
			var idblk = $(this).data('id');
			var jenis = $(this).data('jenis');
			$('.mt-ptsl').html('Download Peta PTSL');
			$.ajax({
					type: 'GET',
					url: '<?php echo base_url();?>ajax/download_berkas/'+jenis,
					data: 'idblk='+idblk,
					dataType: 'html',
					beforeSend: function() {
					},
					success: function(response) {
							$(".dataku").html(response);
					}
			});
			$('#modal-ptsl').modal('show');
		});

		$('#tabel-body').on('click','#btn-map-online',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_7_1/petaonline/'+s_filter,'_self',false);
		});

	})
</script>
