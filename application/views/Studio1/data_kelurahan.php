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
							<div class="form-inline">
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kecamatan</label><br>
										<select class="form-control input-sm" id="filter_kecamatan" style='width:150px'>
											<option value="0">Pilih Kecamatan</option>
											<?php
												foreach ($filter_kecamatan as $fk) {
											 ?>
											 <option value="<?=$fk->kd_kec?>"><?=$fk->nma_kec?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class='col-sm-4'>
										<label>Kelurahan</label><br>
										<select class="form-control input-sm" id="filter_kelurahan" style='width:150px'>
											<option value="0">Pilih Kelurahan</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<br>
									<?php if($fungsi=='validation'){
											?><button class="btn btn-sm btn-primary" id="cari_valid">Cari</button><?php
									}else{
										?><button class="btn btn-sm btn-primary" id="cari_filter">Cari</button><?php
									} ?>

								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 table-responsive">
						<table class="table table-bordered" cellspacing="0">
							<thead>
								<tr>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">NO</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">KODE</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">KECAMATAN</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">KELURAHAN</th>
									<th style="text-align: center; vertical-align: middle;" colspan="3">JUMLAH BIDANG</th>
									<th style="text-align: center; vertical-align: middle;" colspan="4">REALISASI</th>
									<th style="text-align: center; vertical-align: middle;" rowspan="2">ACTION</th>
								</tr>
								<tr>
									<th style="text-align: center;">TERDAFTAR</th>
									<th style="text-align: center;">BELUM TERDAFTAR</th>
									<th style="text-align: center;">TOTAL</th>
									<th style="text-align: center;">BT</th>
									<th style="text-align: center;">SU</th>
									<th style="text-align: center;">GU</th>
									<th style="text-align: center;">WARKAH</th>
								</tr>
							</thead>
							<tbody id="tabel-body">
								<?php
									$no = $this->uri->segment('3') + 1;
									foreach ($studio as $data) {
								 ?>
								<tr>
								 	<td><?=$no++;?></td>
								 	<td> <?=$data->kd_full;?> </td>
									<td> <?=$data->nma_kec;?></td>
									<td> <?=$data->nma_kel;?> </td>
								 	<td><?=$data->jml_terdaftar;?></td>
								 	<td></td>
								 	<td></td>
								 	<td> <?= $data->jml_bt?> </td>
								 	<td> <?= $data->jml_su?></td>
								 	<td><!-- <?=$data->jml_gu;?> --></td>
								 	<td></td>
								 	<td>
								 		<div class="form-group">
											<?php if($this->uri->segment(2)=='validation'){?>
												<button class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-cari-valid" data-toggle="tooltip" title="lihat kelurahan" type="button"><span class="fa fa-search"></span></button>
											<?php }else{?>
												<button class="btn btn-sm btn-info" data-id="<?=$data->kd_full?>" id="btn-cari-kelurahan" data-toggle="tooltip" title="lihat kelurahan" type="button"><span class="fa fa-search"></span></button>
											<?php }?>
										</div>
								 	</td>
								 </tr>
								<?php } ?>
							</tbody>
						</table>
						<?php
						echo $link;
						 ?>
						<!-- <?php
						$hal='';
        $pagination = round($sumkel['jumlah']/10);
        ?>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
             <?php
             if($page>10){
                $modulo = ($page%10);
                if($modulo==0){
                    $previous=(floor(($page/10))*10-10);
                }else{
                    $previous=(floor(($page/10))*10);
                }
                ?><li class="page-item"><a class="page-link" href="<?php echo base_url().''.$menu.'/'.$fungsi; ?>?page=<?php echo ($previous);?>&<?php echo $hal; ?>">Previous</a></li><?php
             }else{
                $previous=0;
             }
             $number = $previous;
             for($i=1;$i<=10;$i++){
                 $number++;
                 if($number<($pagination+2)){
                 ?><li class="page-item <?php if($number==$page){echo 'active';}?>"><a class="page-link" href="<?php echo base_url().''.$menu.'/'.$fungsi; ?>?page=<?php echo $number;?>&<?php echo $hal; ?>"><?php echo $number?></a></li><?php

                 }
             }
             if($number<=$pagination){
                 $number++;
               ?><li class="page-item"><a class="page-link" href="<?php echo base_url().''.$menu.'/'.$fungsi; ?>?page=<?php echo $number;?>&<?php echo $hal; ?>">Next</a></li><?php
             }
             ?>

          </ul>
        </nav> -->
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

		$('#tabel-body').on('click','#btn-cari-kelurahan',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_1_1/index/?search='+s_filter,'_self',false);
		});

		$('#tabel-body').on('click','#btn-cari-valid',function () {
			var s_filter = $(this).data('id');
			window.open('<?=base_url()?>studio_1_1/valid/?search='+s_filter,'_self',false);
		});

		$('#cari_filter').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
				window.open('<?=base_url()?>studio1/?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);

		})

		$('#cari_valid').on('click',function () {
			var s_filter = $('#filter_kecamatan').val();
			var s_filterkelurahan = $('#filter_kelurahan').val();
				window.open('<?=base_url()?>studio1/validation?filter='+s_filter+'&filterkelurahan='+s_filterkelurahan,'_self',false);

		})

		$('#btn-tambah-kelurahan').on('click',function () {
			window.open('<?=base_url()?>kelurahan/form_kelurahan','_self',false);
		});
		$('#tabel-body').on('click','#btn-edit-kelurahan',function () {
			var id = $(this).data('id');
			window.open('<?=base_url()?>kelurahan/form_kelurahan/'+id,'_self',false);
		});
		$('#tabel-body').on('click','#btn-hapus-kelurahan',function () {
				var kode 	= $(this).data('id');
				var nama 	= $(this).data('nama');
				swal({
					title: "Apakah anda yakin?",
					text: "Untuk menghapus data : " + nama,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						$.ajax({
							type: 		'ajax',
							method: 	'post',
							url: 		'<?=base_url()?>'+'kelurahan/hapus_kelurahan/' + kode,
							async: 		true,
							dataType: 	'json',
							success: 	function(response){
								if(response==true){
									location. reload(true);
									swal("Hapus Data Berhasil !", {
									  icon: "success",
									});
								}else{
									swal("Hapus Data Gagal !", {
									  icon: "warning",
									});
								}
							},
							error: function(){
								swal("ERROR", "Hapus Data Gagal.", "error");
							}
						});
					} else {
						swal("Cancelled", "Hapus Data Dibatalkan.", "error");
					}
				});
			});

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
