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
									<th style="text-align: center; vertical-align: middle;" rowspan='2'>Download Peta Blok</th>
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

										$url_peta_block = base_url().'PETA/PETA_BLOCK/'.$blk->petblk_blk;
										$disabled = "";

								 	if (empty($blk->petblk_blk)) {
								 		$button_block = '<button class="btn btn-sm btn-danger btn-open-block" data-toggle="tooltip" title="upload peta blok" id="" data-id="'.$blk->idblk_blk.'"><span class="fa  fa-cloud-upload"></span></button>';
								 	}else{
								 		$button_block = '<a href="'.$url_peta_block.'">
												<button data-toggle="tooltip" title="download peta blok" class="btn btn-sm btn-warning"><span class="fa fa-cloud-download"></span></button>
												</a>';
								 	}

								 ?>
								 <tr>
								 	<td><?=$no++?></td>
								 	<td>Blok <?=$blk->nama_blk?></td>
									<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
													if (in_array(20, $_SESSION['menu']) || $user['level_usr']==1) {
														?><?=$button_block?><?php
													}
												 ?>
								 		</div>
								 	</td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
											<?php
												echo $blk->jml_dhkp;$dhkp+=$blk->jml_dhkp;
													// if (in_array(21, $_SESSION['menu']) || $user['level_usr']==1) {
													// 	?><?php //$button_ptsl?><?php
													// }
												 ?>

								 		</div>
								 	</td>
								 	<td style="text-align: center;"><?=$blk->jml_terdaftar?><?php $sudah+=$blk->jml_terdaftar;?></td>
								 	<td style="text-align: center;"><?=$blk->jml_tidak?><?php $belum+=$blk->jml_tidak;?></td>
								 	<td style="text-align: center;"><?=$total = $blk->jml_terdaftar+$blk->jml_tidak?></td>
								 	<td style="text-align: center;">
								 		<div class="btn-group">
									 			<button <?=$disabled?> data-toggle="tooltip" title="lihat data" class="btn btn-sm btn-default" id="btn-view" data-id="<?=$blk->idblk_blk?>"><span class="fa fa-clipboard"></span></button>
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

<script type="text/javascript">
	$(document).ready(function () {

		$("#tabel-body").on('click','#btn-view',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>studioip4t_2/data/?search='+id,'_self',false);
		})

	})
</script>
