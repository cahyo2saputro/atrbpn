<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(28, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link active" id="home-tab" href="" role="tab" aria-controls="home" aria-selected="true">Lahan Pertanian Kosong</a>
        </li><?php
      }
      if (in_array(27, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="" role="tab" aria-controls="profile" aria-selected="false">Pemukiman</a>
        </li><?php
      }
      if (in_array(54, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="" role="tab" aria-controls="profile" aria-selected="false">Pertanian Lahan Basah</a>
        </li><?php
      }
  ?>
</ul>

		<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
		<div class="box box-primary">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
							<div class="x_content">
						<div class="col-md-12 box-body">
							<div class="col-sm-12">
								<div class="pull-right">

								</div>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">

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
				$('#btn-tambah').on('click',function () {
					window.open('<?=base_url()?>studio_3_2/input/?search='+'<?=$idblk?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-edit',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?>studio_3_2/edit/'+id+'/'+'<?=$idblk?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-hapus',function () {
						var kode 	= $(this).data('id');
						swal({
							title: "Apakah anda yakin?",
							text: "Untuk menghapus data tersebut",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						})
						.then((willDelete) => {
							if (willDelete) {
								$.ajax({
									type: 		'ajax',
									method: 	'post',
									url: 		'<?=base_url()?>'+'studio_3_2/delete/' + kode,
									async: 		true,
									dataType: 	'json',
									success: 	function(response){
										if(response==true){
											/*tabel_studio.ajax.reload(null,false);*/
										/*	location. reload(true);*/
											swal("Hapus Data Berhasil !", {
												icon: "success",
											});
                      window.open('<?=base_url()?>studio_3_2/data/?search='+'<?=$idblk?>','_self',false);
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
			})
		</script>
	</div>
