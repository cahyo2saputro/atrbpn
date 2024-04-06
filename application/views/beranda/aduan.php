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
                        <div class="col-sm-12 table-responsive">
                            <table id="data" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th style="text-align: center; vertical-align: middle;">No</th>
                                    <th style="text-align: center; vertical-align: middle;">Tanggal</th>
                                    <th style="text-align: center; vertical-align: middle;">User</th>
                                    <th style="text-align: center; vertical-align: middle;">Saran/Keluhan/Error</th>
                                    <th style="text-align: center; vertical-align: middle;">Aksi</th>
                                  </tr>
                                </thead>
                                <tbody id="tabel-body">
                                  <?php
                                  $no = $this->uri->segment('4') + 1;
                                  foreach($studio as $std){
                                    ?>
                                    <tr>
                                      <td><?=$no++;?></td>
                                      <td><?=fdate($std['created_at'],'HHDDMMYYYY')?></td>
                                        <td><?=$std['name_usr']?></td>
                                        <td><?=$std['keluhan']?></td>
                                        <td><a target='_blank' href='<?= base_url()?>/screenshoot/<?= $std['screenshoot']?>' class='btn btn-primary'>lihat gambar</a></td>
                                        <?php
                                      }
                                      ?>
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