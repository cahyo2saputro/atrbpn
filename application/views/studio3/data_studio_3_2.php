<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <?php
      if (in_array(28, $_SESSION['menu']) || in_array(80, $_SESSION['menu']) ||  $user['level_usr']==1) {
        ?><li class="nav-item" style='background:#fff'>
          <a class="nav-link active" id="home-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/data/?search=<?php echo $idblk;?>" role="tab" aria-controls="home" aria-selected="true">Belum Sertipikat</a>
        </li><?php
      }
      if (in_array(27, $_SESSION['menu']) || in_array(81, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="profile-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/register/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Sudah Sertipikat</a>
        </li><?php
      }
      if (in_array(54, $_SESSION['menu']) || in_array(79, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="dhkp-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/dhkp/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data DHKP</a>
        </li><?php
      }
      if (in_array(52, $_SESSION['menu']) || in_array(78, $_SESSION['menu']) || $user['level_usr']==1) {
        ?><li class="nav-item">
          <a class="nav-link" id="k4-tab" href="<?php echo base_url()?><?php echo $this->uri->segment(1)?>/k4/?search=<?php echo $idblk;?>" role="tab" aria-controls="profile" aria-selected="false">Data Sertipikat</a>
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
                <form action='<?= base_url().''.$this->uri->segment(1).'/'.$this->uri->segment(2)?>' method='get'>
                  <div class='col-sm-3'>
                    <label>Pencarian</label>
                    <select name='param' class='form-control'>
                      <?php
                      foreach ($data['param'] as $filter) {
                        ?><option value='<?=$filter[1]?>' <?php if($this->input->get('param')==$filter[1]){echo 'selected';}?>><?=$filter[0]?></option><?php
                      }
                       ?>
                    </select>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <input type='hidden' name='search' value='<?= $this->input->get('search')?>'>
                    <input style='margin-top:5px;' class='form-control col-lg-3' type='text' name='nilai' value='<?= $this->input->get('nilai')?>'>
                  </div>
                  <div class='col-sm-3'>
                    <br>
                    <button style='margin-top:5px;' type='submit' class='btn btn-primary'>cari</button>
                  </div>
                </form>
							</div>
							<div class="col-sm-12">
								<div class="pull-right">
                  <?php
                  if (in_array(29, $_SESSION['menu']) || in_array(82, $_SESSION['menu']) || $user['level_usr']==1) {
                    ?><button class="btn btn-sm btn-primary" id="btn-tambah" style="margin-bottom: 10px;">Tambah <span class="fa fa-plus-square-o"></span></button>
                    <?php
                  }
                   ?>
								</div>
							</div>
							<div class="col-sm-12 table-responsive">
								<table id="data-staff" class="table table-bordered">
									<thead>
										<tr>
											<!-- <th>No</th> -->
                      <th>NUB</th>
											<th>No.KTP</th>
											<th>Nama</th>
											<th>No.SPPT</th>
                      <?php if(strtolower($this->uri->segment(1))=='studio_7_2'){
                        ?>
                        <th>Luas Pemetaan</th>
                        <th>No Berkas Fisik</th>
                        <th>NIB</th>
                        <th>Klaster</th>
                        <th>No Berkas Yuridis</th>
                        
                        <?php
                      } ?>
                      <th>Export K 3 1</th>
                      <th>Export K 1</th>
											<th style="width:23%">Action</th>
										</tr>
									</thead>
									<tbody id="tabel-body">
										<?php
											foreach ($studio as $st) {

										 ?>
										 <tr>
											<!-- <td><?php //$no++?></td> -->
                      <td><?=$st['nub_ptsl'];?></td>
											<td><?= $st['noktp_pdk'];?></td>
											<td><?=stripslashes($st['nma_pdk']);?></td>
											<td>
                        <?php
                        $dat['table'] = "tb_ptsldhkp";
                        $dat['join']['table'] = "tb_dhkp";
                  			$dat['join']['key'] = "id_dhkp";
                  			$dat['join']['ref'] = "iddhkp_ptsl";
                        $dat['type'] = "multiple";
                        $dat['condition']['idptsl_ptsl'] = $st['id_ptsl'];
                        $datanop = $this->crud_model->get_data($dat);
                        foreach ($datanop as $dd) {
                          $nop = createkodebpkad($st['idkel_blk']).''.$st['nama_blk'].''.$dd['nosppt_dhkp'];

                          echo $nop.'<br>';
                        }
                        ?>
                      </td>
                      <?php if($this->uri->segment(1)=='studio_7_2' || $this->uri->segment(1)=='Studio_7_2'){
                        ?>
                        <td><?=$st['luasfisik_ptsl']?></td>
                        <td><?=$st['noberkas_ptsl']?></td>
                        <td><?php if($st['nib_ptsl']!=""){ ?><?=$st['idkel_blk']?><?=$st['nib_ptsl']?><?php }?></td>
                        <td><?=$st['klaster_ptsl']?></td>
                        <td><?=$st['noberkasyrd_ptsl']?></td>
                        <?php
                      } ?>
                      <td>
                          <div class="btn-group">
                            <?php

                              if (in_array(33, $_SESSION['menu']) || in_array(84, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?><a data-toggle="tooltip" <?php echo "title='export k 3 1 (2023)'";?> target='_blank' href='<?= base_url()?>studio_3_2/exportk312023/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-folder-o"></span></button></a><?php
                              }
                              ?>
                            </div>
                        </td>
                        <td>
												<div class="btn-group">
                              <?php
                              if (in_array(32, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?>
                                <a data-toggle="tooltip" <?php echo "title='export K.1 2023'"?> target='_blank' href='<?= base_url()?>studio_3_2/exportk12023/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file-pdf-o"></span></button></a>
                                <a data-toggle="tooltip" <?php echo "title='export K.1 2024'"?> target='_blank' href='<?= base_url()?>studio_3_2/exportk12024/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-primary"><span class="fa fa-file-pdf-o"></span></button></a><?php
                              }
                               ?>
												</div>
											</td>
											<td>
												<div class="btn-group">
                           <?php
                           if (in_array(29, $_SESSION['menu']) || in_array(82, $_SESSION['menu']) || $user['level_usr']==1) {
                             ?><button data-toggle="tooltip" title="edit data ptsl" class="btn btn-sm btn-warning" id="btn-edit" data-id="<?=$st['id_ptsl']?>"><span class="fa fa-edit"></span></button>
                             <?php
                             if($user['level_usr']==1 && strtolower($this->uri->segment(1))=='studio_3_2') {
                               ?><button data-toggle="tooltip" title="hapus data ptsl" class="btn btn-sm btn-danger" id="btn-hapus" data-id="<?=$st['id_ptsl']?>"><span class="fa fa-trash"></span></button><?php
                             }

                           }
                            ?>
                              <?php

                              if (in_array(32, $_SESSION['menu']) || in_array(83, $_SESSION['menu']) || $user['level_usr']==1) {
                                ?>
                                <a data-toggle="tooltip" <?php echo "title='export Blangko'"?> target='_blank' href='<?= base_url()?>studio_3_2/blangko/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-file"></span></button></a><?php
                              }
                               ?>
                                <?php
                                if (in_array(34, $_SESSION['menu']) || in_array(85, $_SESSION['menu']) || $user['level_usr']==1) {
                                  ?><a data-toggle="tooltip" <?php echo "title='export risalah penelitian a3'";?> target='_blank' href='<?= base_url()?>studio_3_2/exportrisalaha3/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-folder"></span></button></a><?php
                                }
                                 ?>
                               <?php
                               if (in_array(34, $_SESSION['menu']) || in_array(85, $_SESSION['menu']) || $user['level_usr']==1) {
                                if($st['gambar']>0){
                                  ?><a data-toggle="tooltip" <?php echo "title='export berkas jpg (".$st['gambar']." file)'";?> target='_blank' href='<?= base_url()?>studio_3_2/exportberkas/<?=$st['id_ptsl'];?>/<?=$st['idblk_ptsl'];?>'><button class="btn btn-sm btn-default"><span class="fa fa-image"></span></button></a><?php
                                } 
                                if($st['pdf']>0){
                                  $berkas['table']     = "tb_ptslberkas";
                                  $berkas['type']      = "multiple";
                                  $berkas['cuzcondition'] = 'berkas_pbk like "%.pdf%" and idptsl_pbk='.$st['id_ptsl'];
                                  $pdf = $this->crud_model->get_data($berkas);
                                  $p=1;
                                  foreach($pdf as $fat){
                                    ?><a data-toggle="tooltip" title='pdf <?=$p?>' target='_blank' href='<?= base_url()?>DATA/BERKAS/<?=$fat['berkas_pbk'];?>'><button class="btn btn-sm btn-info"><span class="fa fa-file-pdf-o"></span></button></a><?php
                                    $p++;
                                  }

                                  
                                } 
                                ?><?php
                               }
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

		<script type="text/javascript">
			$(document).ready(function () {
				$('#btn-tambah').on('click',function () {
					window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/input/?search='+'<?=$idblk?>','_self',false);
				});

				$('#tabel-body').on('click','#btn-edit',function () {
					var id = $(this).data('id');
					var set = $(this).data('set');
					window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/edit/'+id+'/'+'<?=$idblk?>','_self',false);
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
									url: 		'<?=base_url()?>'+'<?php echo $this->uri->segment(1)?>/delete/' + kode,
									async: 		true,
									dataType: 	'json',
									success: 	function(response){
										if(response==true){
											/*tabel_studio.ajax.reload(null,false);*/
										/*	location. reload(true);*/
											swal("Hapus Data Berhasil !", {
												icon: "success",
											});
                      window.open('<?=base_url()?><?php echo $this->uri->segment(1)?>/data/?search='+'<?=$idblk?>','_self',false);
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
