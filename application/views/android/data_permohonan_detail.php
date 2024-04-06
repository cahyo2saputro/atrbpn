<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVrqtBQj9xi_C5DcyMEr8tsy65cCMHduU"></script>
			<script type="text/javascript">
			var posisi = new google.maps.LatLng(<?=$studio['koor_pmh']?>);

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
									<th>Data Permohonan</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<td>Nomor Permohonan</td>
									<td>:</td>
									<td><?=$studio['nope_srt']?></td>
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
									<td><b>Sertipikat/Belum Sertipikat</b></td>
									<td>:</td>
									<td><b><?=status($studio['sert_srt'],'berkas')?></b></td>
								</tr>
								<?php
								if($studio['sert_srt']==0){
									$ref = explode('-',$studio['ref_srt']);
									?>
									<tr>
										<td>No. Letter C</td>
										<td>:</td>
										<td><?=$ref[0]?></td>
									</tr>
									<tr>
										<td>Persil</td>
										<td>:</td>
										<td><?=$ref[1]?></td>
									</tr>
									<tr>
										<td>Klas</td>
										<td>:</td>
										<td><?=$ref[2]?></td>
									</tr>
									<tr>
										<td>Atas Nama</td>
										<td>:</td>
										<td><?=$ref[3]?></td>
									</tr>
									<?php
								}else{
									?>
									<tr>
										<td>No Hak</td>
										<td>:</td>
										<td>11.36.<?=$studio['kec_srt'].'.'.$studio['kel_srt'].'.'.$studio['ref_srt']?></td>
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
									</tr><?php
								}
								?>
								<tr>
									<td>NOP</td>
									<td>:</td>
									<td><?=$nop = createkodebpkad($studio['idkel_blk']).''.$studio['nama_blk'].''.$studio['nosppt_dhkp'];?></td>
								</tr>
								<tr>
									<td>Luas</td>
									<td>:</td>
									<td><?=$studio['luas_pmh']?></td>
								</tr>
								<tr>
									<td>Gambar NOP</td>
									<td>:</td>
									<td><?php if($studio['imgnop_pmh']){?><image style='max-height:300px' class='fancybox' src='<?= base_url()?>sppt/<?= $studio['imgnop_pmh']?>' href='<?= base_url()?>sppt/<?= $studio['imgnop_pmh']?>'> <?php }?></td>
								</tr>
								<tr>
									<td>Batas Utara</td>
									<td>:</td>
									<td><?= $studio['utara_pmh']?></td>
								</tr>
								<tr>
									<td>Batas Barat</td>
									<td>:</td>
									<td><?= $studio['barat_pmh']?></td>
								</tr>
								<tr>
									<td>Batas Selatan</td>
									<td>:</td>
									<td><?= $studio['selatan_pmh']?></td>
								</tr>
								<tr>
									<td>Batas Timur</td>
									<td>:</td>
									<td><?= $studio['timur_pmh']?></td>
								</tr>
								<tr>
									<th>Status Kuasa</th>
									<th></th>
									<th><?= status($studio['kuasa_pmh'],'kuasa')?></th>
								</tr>
								<?php
								if($studio['kuasa_pmh']==1){
									$pdk['table'] = "tb_kuasa";
									$pdk['join']['table'] = "tb_penduduk";
									$pdk['join']['key'] = "idpdk_ksa";
									$pdk['join']['ref'] = "idpdk_pdk";
									$pdk['type'] = "single";
									$pdk['condition']['idpmh_ksa'] = $studio['id_pmh'];
									$cekpdk = $this->crud_model->get_data($pdk);
									?>
									<tr>
										<td>Nama Kuasa</td>
										<td></td>
										<td><?= $cekpdk['nma_pdk']?></td>
									</tr>
									<tr>
										<td>NIK</td>
										<td></td>
										<td><?= $cekpdk['noktp_pdk']?></td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td></td>
										<td><?= $cekpdk['almat_pdk']?></td>
									</tr>
									<?php
								}
								 ?>
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
									<td>Nama</td>
									<td>:</td>
									<td><?=$studio['nma_reg']?></td>
								</tr>
								<tr>
									<td>NO HP</td>
									<td>:</td>
									<td><?=$studio['nohp_reg']?></td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>:</td>
									<td><?=$studio['alamat_reg']?></td>
								</tr>
								<tr>
									<td>Dokumen</td>
									<td>:</td>
									<td><?php if($studio['ktp_reg']){?><img style='max-height:300px' class='fancybox' src='<?= base_url()?>Penduduk/<?= $studio['ktp_reg']?>' href='<?= base_url()?>Penduduk/<?= $studio['ktp_reg']?>'><?php }?>
									</td>
								</tr>
							</tbody>
						</table>
						<table id="data-staff" class="table">
							<tbody>
								<tr>
									<th>Data Pengecekan Desa</th>
									<th></th>
									<th></th>
								</tr>
								<tr>
									<td>Foto Batas</td>
									<td>:</td>
									<td><?php if($studio['fotobatas_pmh']){?><image style='max-height:300px' class='fancybox' src='<?= base_url()?>Batas/<?= $studio['fotobatas_pmh']?>' href='<?= base_url()?>Batas/<?= $studio['fotobatas_pmh']?>'> <?php }?></td>
								</tr>
								<tr>
									<td>Koordinat Pengecekan</td>
									<td>:</td>
									<?php if($studio['koor_pmh']){?><td></td></tr><tr><td colspan='3'><div id="map_canvas" style="height: 480px; width: 100%; border:1px solid #DADADA; margin-bottom:10px;"></td></div><?php }else{ echo '<td>Lokasi belum dicek</td>';} ?>
								</tr>
								<tr>
									<td>Tanda</td>
									<td>:</td>
									<td><?=status($studio['cektanda_pmh'],'tanda')?></td>
								</tr>
								<tr>
									<td>Sengketa</td>
									<td>:</td>
									<td><?=status($studio['ceksengketa_pmh'],'sengketa')?></td>
								</tr>
								<tr>
									<td>Catatan</td>
									<td>:</td>
									<td><?=$studio['catatan_pmh']?></td>
								</tr>
								<tr>
									<th>Status Progress</th>
									<th></th>
									<th><?=status($studio['tracking_pmh'],'tracking')?></th>
								</tr>
								<tr>
									<td>Proses Pengajuan</td>
									<td></td>
									<td><?= fdate($studio['tanggaldiajukan'],'HHDDMMYYYY')?></td>
								</tr>
								<?php
								if($studio['idusrform2_pmh']!=0){
									?>
									<tr>
										<td>Pengisian Form 2 - Menunggu persetujuan kades</td>
										<td>oleh <?= who($studio['idusrform2_pmh'])?></td>
										<td><?= fdate($studio['dateform2_pmh'],'HHDDMMYYYY')?></td>
									</tr>
									<?php
								}

								if($studio['idusracc_pmh']!=0){
									?>
									<tr>
										<td>Acc Kades - Proses Pengecekan Admin</td>
										<td>oleh <?= who($studio['idusracc_pmh'])?></td>
										<td><?= fdate($studio['dateacc_pmh'],'HHDDMMYYYY')?></td>
									</tr>
									<?php
								}

								if($studio['idusrdesa_pmh']!=0){
									if($studio['tracking_pmh']==4){
										$kata = 'Permohonan bermasalah';
									}else if($studio['tracking_pmh']==3){
										$kata = 'Pengecekan selesai - Menunggu persetujuan Kades';
									}else{
										$kata = 'Undefined';
									}
									?>
									<tr>
										<td><?=$kata?></td>
										<td>oleh <?= who($studio['idusrdesa_pmh'])?></td>
										<td><?= fdate($studio['datedesa_pmh'],'HHDDMMYYYY')?></td>
									</tr>
									<?php
								}

								if($studio['idusrno_pmh']!=0){
									?>
									<tr>
										<td>Permohonan diacc, terbit Nomor Pengesahan</td>
										<td>oleh <?= who($studio['idusrno_pmh'])?></td>
										<td><?= fdate($studio['dateno_pmh'],'HHDDMMYYYY')?></td>
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
