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
						<table id="data-staff" class="table">
							<tbody>
								<tr>
									<th>Data Sudah Sertipikat</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<td>Nomor Permohonan</td>
									<td>:</td>
									<td><?=$studio['nope_srt']?></td>
								</tr>
								<tr>
									<td>Nama</td>
									<td>:</td>
									<td><?=$studio['nma_reg']?></td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>:</td>
									<td><?=$studio['alamat_reg']?></td>
								</tr>
								<tr>
									<td>NO HP</td>
									<td>:</td>
									<td><?=$studio['nohp_reg']?></td>
								</tr>
								<tr>
									<td>Kecamatan</td>
									<td>:</td>
									<td><?=$studio['nma_kec']?></td>
								</tr>
								<tr>
									<td>Kelurahan</td>
									<td>:</td>
									<td><?=$studio['nma_kel']?></td>
								</tr>
								<tr>
									<td>Hak</td>
									<td>:</td>
									<td><?='11.26.'.$studio['kec_srt'].'.'.$studio['kel_srt'].'.'.$studio['ref_srt']?></td>
								</tr>
								<tr>
									<td>Dokumen</td>
									<td>:</td>
									<td>
										<?php
										if($image){
											foreach ($image as $im) {
												?>
												<img class='fancybox' style='max-height:300px' src='<?= base_url()?>Sertipikat/<?= $im['image_isrt']?>' href='<?= base_url()?>Sertipikat/<?= $im['image_isrt']?>'>
												<?php
											}
										}
										 ?>

									</td>
								</tr>
								<tr>
									<td>Status</td>
									<td>:</td>
									<td><?=status($studio['tracking_srt'],'statuskw')?></td>
								</tr>
							</tbody>
						</table>

						<table id="data-staff" class="table">
							<tbody>
								<tr>

								</tr>
								<tr>

								</tr>
								<tr>

								</tr>
								<tr>

								</tr>
								<tr>

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
<script>
		$(document).ready(function(){
			//FANCYBOX
			//https://github.com/fancyapps/fancyBox
			$(".fancybox").fancybox({
					openEffect: "none",
					closeEffect: "none"
			});
		});
</script>
