<?php defined('BASEPATH') OR exit('No direct script access allowed'); $user = $this->auth_model->get_userdata(); ?>
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
                        <div class="col-sm-3">
                            <select class='form-control' id='issue'>
                              <option value='1' selected>NIK tidak valid</option>
                              <option value='2'>NIB Double</option>
                            </select>
                        </div>
                        <div class='col-sm-3'>
                          <select class="form-control input-sm" id="filter_kecamatan" style='width:100%'>
                            <option value="0">Pilih Kecamatan PTSL</option>
                            <?php
                              foreach ($filter_kecamatan as $fk) {
                            ?>
                            <option value="<?=$fk->kd_kec?>"><?=$fk->nma_kec?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class='col-sm-3'>
                          <select class="form-control input-sm" id="filter_kelurahan" style='width:100%'>
                            <option value="0">Pilih Kelurahan PTSL</option>
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <input id='month' type='month' class='form-control'>
                        </div>
                        <div class="col-sm-3">
                            <button id='cari' class='btn btn-primary'>cari</button>
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table id="data" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th style="text-align: center; vertical-align: middle;">No</th>
                                    <?php
                                    if($type==1){
                                      ?>
                                        <th style="text-align: center; vertical-align: middle;">No KTP</th>
                                        <th style="text-align: center; vertical-align: middle;">Nama</th>
                                        <th style="text-align: center; vertical-align: middle;">Alamat</th>
                                        <th style="text-align: center; vertical-align: middle;">Kelurahan</th>
                                        <th style="text-align: center; vertical-align: middle;">Kecamatan</th>
                                        <th style="text-align: center; vertical-align: middle;">Kabupaten</th>
                                        <th style="text-align: center; vertical-align: middle;">PTSL</th>
                                        <th style="text-align: center; vertical-align: middle;">Tanggal</th>
                                        <th style="text-align: center; vertical-align: middle;">Aksi</th>
                                      <?php  
                                    }else if($type==2){
                                      ?>
                                        <th style="text-align: center; vertical-align: middle;">NIB</th>
                                        <th style="text-align: center; vertical-align: middle;">Blok</th>
                                        <th style="text-align: center; vertical-align: middle;">Jumlah</th>
                                        <th style="text-align: center; vertical-align: middle;">Aksi</th>
                                      <?php 
                                    }
                                    ?>
                                  </tr>
                                </thead>
                                <tbody id="tabel-body">
                                  <?php
                                  $no = $this->uri->segment('4') + 1;
                                  foreach($studio as $std){
                                    ?>
                                    <tr>
                                      <td><?=$no++;?></td>
                                      <?php
                                      if($type==1){
                                        ?>
                                          <td><?=$std['noktp_pdk']?></td>
                                          <td><?=$std['nma_pdk']?></td>
                                          <td><?=$std['almat_pdk']?></td>
                                          <td><?=$std['kel_pdk']?></td>
                                          <td><?=$std['kec_pdk']?></td>
                                          <td><?=$std['kab_pdk']?></td>
                                          <td><?=$std['nib_ptsl']?></td>
                                          <td><?=fdate($std['dibuat'],'HHDDMMYYYY')?></td>
                                          <td>
                                          <a target='_blank' data-toggle="tooltip" title='detail penduduk' href="<?= base_url('Datapenduduk/detail/').$std['idpdk_pdk']; ?>" class="btn btn-sm btn-info"><span class="fa fa-file"></span></a>
                                          <?php
                                          if($std['id_ptsl']){
                                              ?>
                                               <a target='_blank' data-toggle="tooltip" title='edit ptsl' href="<?= base_url('Studio_3_2/edit/').$std['id_ptsl'].'/'.$std['idblk_ptsl']; ?>" class="btn btn-sm btn-warning"><span class="fa fa-edit"></span></a>
                                                <a onclick="return confirm()" target='_blank' data-toggle="tooltip" title='hapus ptsl' href="<?= base_url('Studio_3_2/delete/').$std['id_ptsl']; ?>" class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></a>
                                              <?php
                                          }
                                          ?>
                                         
                                          </td>
                                        <?php  
                                      }else if($type==2){
                                        ?>
                                        <td><?=$std['kd_full']?><?=$std['nib_ptsl']?></td>
                                        <td><?=$std['nama_blk'].' '.$std['nma_kel'].' '.$std['nma_kec']?></td>
                                        <td><?=$std['count(nib_ptsl)']?></td>
                                        <td><a target='_blank' data-toggle="tooltip" title='edit ptsl' href="<?= base_url('Studio_6_2/data?param=nib_ptsl&search='.$std['idblk_ptsl'].'&nilai='.$std['kd_full'].''.$std['nib_ptsl']); ?>" class="btn btn-sm btn-warning"><span class="fa fa-file"></span></a></td>
                                        <?php
                                      }
                                      ?>
                                    </tr>
                                    <?php
                                  } 
                                  ?>
                                  
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
				$('#cari').on('click',function () {
					window.open('<?=base_url()?>Analysist/index/'+$('#issue').val()+'?kecamatan='+$('#filter_kecamatan').val()+'&kelurahan='+$('#filter_kelurahan').val()+'&month='+$('#month').val()+'','_self',false);
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
      });
</script>