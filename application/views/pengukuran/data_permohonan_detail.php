<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVrqtBQj9xi_C5DcyMEr8tsy65cCMHduU"></script>
			<script type="text/javascript">
			var posisi = new google.maps.LatLng(<?=$studio['koor_png']?>);

			function mulai(){
					var opsi = {
							center : posisi,
							zoom:15,
							mapTypeId:google.maps.MapTypeId.ROADMAP
					};

					peta = new google.maps.Map(document.getElementById('map_canvas'),opsi);

					var marker = new google.maps.Marker({
			        position:posisi
			    });

					marker.setMap(peta);
			    var infowindow = new google.maps.InfoWindow({
			        content:"<span>Lokasi Pengecekan</span>"
			    });

			    google.maps.event.addListener(marker,'click',function(){
			        infowindow.open(peta,marker);
			    });

			}

			google.maps.event.addDomListener(window,'load',mulai);

</script>
<div class="box box-primary">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	        <div class="x_content">
				<div class="col-md-12 box-body">
					<div class="col-sm-6 table-responsive">
						<table id="data-staff" class="table table-responsive">
							<tbody>
								<tr>
									<th>Data Permohonan Ukur</th>
									<th></th>
									<th></th>
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
									<td>Nomor Berkas</td>
									<td>:</td>
									<td><?=$studio['noberkas_png']?></td>
								</tr>
								<tr>
									<td>No STP</td>
									<td>:</td>
									<td><?=$studio['nostp_png']?></td>
								</tr>
								<tr>
									<td>Foto STP</td>
									<td>:</td>
									<td><?php if($studio['stp_png']){?><image style='max-height:300px' class='fancybox' src='<?= base_url()?>stp/<?= $studio['stp_png']?>' href='<?= base_url()?>stp/<?= $studio['stp_png']?>'><?php } ?></td>
								</tr>
								<tr>
									<td>No Permohonan</td>
									<td>:</td>
									<td><?=$studio['nope_png']?></td>
								</tr>
								<tr>
									<td>Petugas Ukur</td>
									<td>:</td>
									<td><?=who($studio['pu_png'])?></td>
								</tr>
								<tr>
									<td>Tanggal Ukur</td>
									<td>:</td>
									<td><?=fdate($studio['tglukur_png'],'DDMMYYYY')?></td>
								</tr>
								<tr>
									<th>Status Progress</th>
									<th>:</th>
									<th><?=status($studio['tracking_png'],'ukur')?></th>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-6 table-responsive">
						<table id="data-staff" class="table">
							<tbody>
								<tr>
									<th>Data Register</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<td>NIk</td>
									<td>:</td>
									<td><?=$studio['nik_reg']?></td>
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
							</tbody>
						</table>
						<table id="data-staff" class="table">
							<tbody>
								<tr>
									<th>Data Proses Pengukuran</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<td>Foto Bukti</td>
									<td>:</td>
									<td><?php if($studio['foto_png']){?><image style='max-height:300px' class='fancybox' src='<?= base_url()?>Real/<?= $studio['foto_png']?>' href='<?= base_url()?>Real/<?= $studio['foto_png']?>'><?php } ?></td>
								</tr>
								<tr>
									<td>Koordinat Pengecekan</td>
									<td>:</td>
									<?php if($studio['koor_png']){?><td></td></tr><tr><td colspan='3'><div id="map_canvas" style="height: 480px; width: 100%; border:1px solid #DADADA; margin-bottom:10px;"></td></div><?php }else{ echo '<td>Lokasi belum dicek</td>';} ?>
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
