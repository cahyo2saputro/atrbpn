<?php defined('BASEPATH') OR exit('No direct script access allowed'); $user = $this->auth_model->get_userdata(); ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-12 box-body">
                        <div class="col-sm-12 table-responsive">
                          <form method='get' action='' class="form-inline">
                            <div class="form-group">
                              <div class='col-sm-4'>
                                <label>Tahun</label><br>
                                <?php
                                if($this->input->get('tahun')){
                                  $tahun=$this->input->get('tahun');
                                }else{
                                  $tahun=date('Y');
                                }
                                ?>
                                <input type='text' class='form-control' name='tahun' value='<?=$tahun?>'>
                              </div>
                            </div>
                            <div class="form-group">
                              <br>
                              <button class="btn btn-sm btn-primary" id="cari_filter">Cari</button>
                            </div>
                          </form>
                            <table id="data-staff" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Kode</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Kecamatan</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Kelurahan</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Jumlah Blok</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">DHKP</th>
                                        <th style="text-align: center; vertical-align: middle;" colspan='4'>Sudah Sertipikat</th>
                                        <th style="text-align: center; vertical-align: middle;" colspan='4'>Belum Sertipikat</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="2">Progress</th>
                                    </tr>
                                    <tr>
                                      <th style="text-align: center; vertical-align: middle;">Target</th>
                                      <th style="text-align: center; vertical-align: middle;">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;">Progress</th>
                                      <?php
                                      if($this->uri->segment(1)=='Studio5'){
                                        ?><th style="text-align: center; vertical-align: middle;">Target</th><?php
                                      }else{
                                        ?><th style="text-align: center; vertical-align: middle;">Target</th><?php
                                      }
                                      ?>
                                      <th style="text-align: center; vertical-align: middle;">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;">Progress</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-body">
                                    <?php
                                        $no = $this->uri->segment('3') + 1;
                                        $btarget=0;$starget=0;$bsisa=0;
                                        $btotal=0;$stotal=0;$ssisa=0;
                                        $bltarget=0;$dtarget=0;
                                    if($studio){
                                        foreach ($studio as $data) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$no++?></td>
                                        <td class="text-center"><?=$data->kd_full?></td>
                                        <td><?=$data->nma_kec?></td>
                                        <td><?=$data->nma_kel?></td>
                                        <td style="text-align: center;"><?= $data->jml_blk;$bltarget+=$data->jml_blk;?></td>
                                        <td style="text-align: center;"><?= $data->jml_dhkp;$dtarget+=$data->jml_dhkp;?></td>
                                        <td style="text-align: center;"><?= $data->jml_terdaftar;$starget+=$data->jml_terdaftar;?></td>
                                        <td style="text-align: center;"><?= $data->jml_sudahhak;$stotal+=$data->jml_sudahhak?></td>
                                        <td style="text-align: center;"><?= ($data->jml_terdaftar-$data->jml_sudahhak);$ssisa+=($data->jml_terdaftar-$data->jml_sudahhak)?></td>
                                        <td style="text-align: center;"><?= round($data->prosentasek4,2).' %'?></td>
                                        <td style="text-align: center;"><?= $data->target;$btarget+=$data->target;?></td>
                                        <td style="text-align: center;"><?= $data->total;$btotal+=$data->total;?></td>
                                        <td style="text-align: center;"><?= ($data->target-$data->total);$bsisa+=($data->target-$data->total);?></td>
                                        <td style="text-align: center;"><?= round($data->prosentase,2).' %'?></td>
                                        <td style="text-align: center;"><?= round((round($data->prosentasek4,2)+round($data->prosentase,2))/2,2).' %'?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center">Total</td>
                                        <td class="text-center"></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: center;"><?= $bltarget;?></td>
                                        <td style="text-align: center;"><?= $dtarget;?></td>
                                        <td style="text-align: center;"><?= $starget;?></td>
                                        <td style="text-align: center;"><?= $stotal;?></td>
                                        <td style="text-align: center;"><?= $ssisa;?></td>
                                        <td style="text-align: center;"><?= round(($stotal/$starget)*100,2).' %'?></td>
                                        <td style="text-align: center;"><?= $btarget;?></td>
                                        <td style="text-align: center;"><?= $btotal;?></td>
                                        <td style="text-align: center;"><?= $bsisa;?></td>
                                        <td style="text-align: center;"><?= round(($btotal/$btarget)*100,2).' %'?></td>
                                        <td style="text-align: center;"><?= ((round(($stotal/$starget)*100,2)+round(($btotal/$btarget)*100,2))/2).' %'?></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                            <?php
                            //echo $link;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
