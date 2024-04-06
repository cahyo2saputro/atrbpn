<?php defined('BASEPATH') OR exit('No direct script access allowed'); $user = $this->auth_model->get_userdata(); ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<div class="box box-primary">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-12 box-body">
                      <div class="col-sm-12">
                          <div class="pull-left">
                            <form method='get' action=''>
                              <div class="form-inline">
                                <div class="form-group">
                                  <div class='col-sm-4'>
                                    <label>Tahun</label><br>
                                    <input type='text' class='form-control' name='tahun' value='<?= $tahun;?>'>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <br>
                                  <button class="btn btn-sm btn-primary" type="submit">Cari</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table id="data-staff" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="3">No</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="3">Kelurahan / Kecamatan</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="3">Jumlah Blok</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="3">Jumlah DHKP</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan='2' colspan='4'>Sudah Sertipikat</th>
                                        <th style="text-align: center; vertical-align: middle;" colspan='13'>Belum Sertipikat</th>
                                        <th style="text-align: center; vertical-align: middle;" rowspan="3">Progress</th>
                                    </tr>
																		<tr>
																			<th style="text-align: center; vertical-align: middle;" rowspan='2'>Target</th>
                                      <th style="text-align: center; vertical-align: middle;background:#3498db;color:#fff" colspan='3'>Satgas Desa</th>
																			<th style="text-align: center; vertical-align: middle;background:#1abc9c;color:#fff" colspan='3'>Satgas Pengukuran</th>
																			<th style="text-align: center; vertical-align: middle;background:#e67e22;color:#fff" colspan='3'>Satgas Pemetaan</th>
																			<th style="text-align: center; vertical-align: middle;background:#e74c3c;color:#fff" colspan='3'>Satgas Yuridis</th>
                                    </tr>
                                    <tr>
																			<th style="text-align: center; vertical-align: middle;">Target</th>
                                      <th style="text-align: center; vertical-align: middle;">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;">Progr</th>

                                      <th style="text-align: center; vertical-align: middle;background:#3498db;color:#fff">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;background:#3498db;color:#fff">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;background:#3498db;color:#fff">Progr</th>

																			<th style="text-align: center; vertical-align: middle;background:#1abc9c;color:#fff">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;background:#1abc9c;color:#fff">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;background:#1abc9c;color:#fff">Progr</th>

																			<th style="text-align: center; vertical-align: middle;background:#e67e22;color:#fff">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;background:#e67e22;color:#fff">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;background:#e67e22;color:#fff">Progr</th>

																			<th style="text-align: center; vertical-align: middle;background:#e74c3c;color:#fff">Selesai</th>
                                      <th style="text-align: center; vertical-align: middle;background:#e74c3c;color:#fff">Sisa</th>
                                      <th style="text-align: center; vertical-align: middle;background:#e74c3c;color:#fff">Progr</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-body">
                                    <?php
                                        $no = $this->uri->segment('3') + 1;
                                      if($studio){
                                        $btarget=0;$starget=0;$bpansisa=0;$bpensisa=0;$bpemsisa=0;$byudsisa=0;
                                        $bpantotal=0;$bpentotal=0;$bpemtotal=0;$byudtotal=0;$stotal=0;$ssisa=0;
                                        $btotal=0;$dtotal=0;
                                        foreach ($studio as $data) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?=$no++?></td>
                                        <td><b><?=$data->nma_kel?></b><br><?=$data->nma_kec?></td>

                                        <td style="text-align: center;"><?= $data->jml_blk;$btotal+=$data->jml_blk?></td>
                                        <td style="text-align: center;"><?= $data->jml_dhkp;$dtotal+=$data->jml_dhkp?></td>
                                        <td style="text-align: center;"><?= $data->jml_terdaftar;$starget+=$data->jml_terdaftar;?></td>
                                        <td style="text-align: center;"><?= $data->jml_sudahhak;$stotal+=$data->jml_sudahhak?></td>

                                        <td style="text-align: center;"><?= ($data->jml_terdaftar-$data->jml_sudahhak);$ssisa+=($data->jml_terdaftar-$data->jml_sudahhak)?></td>
                                        <td style="text-align: center;"><?= round($data->prosentasek4,2).'%'?></td>
                                        <td style="text-align: center;"><?= $data->target;$btarget+=$data->target;?></td>

                                        <td style="text-align: center;background:#3498db;color:#fff"><?= $data->totalpanitia;$bpantotal+=$data->totalpanitia;?></td>
                                        <td style="text-align: center;background:#3498db;color:#fff"><?= ($data->target-$data->totalpanitia);$bpansisa+=($data->target-$data->totalpanitia);?></td>
                                        <td style="text-align: center;background:#3498db;color:#fff"><?= round($data->prosentasepanitia,2).'%'?></td>

																				<td style="text-align: center;background:#1abc9c;color:#fff"><?= $data->totalpengukuran;$bpentotal+=$data->totalpengukuran;?></td>
                                        <td style="text-align: center;background:#1abc9c;color:#fff"><?= ($data->target-$data->totalpengukuran);$bpensisa+=($data->target-$data->totalpengukuran);?></td>
                                        <td style="text-align: center;background:#1abc9c;color:#fff"><?= round($data->prosentasepengukuran,2).'%'?></td>

																				<td style="text-align: center;background:#e67e22;color:#fff"><?= $data->totalpemetaan;$bpemtotal+=$data->totalpemetaan;?></td>
                                        <td style="text-align: center;background:#e67e22;color:#fff"><?= ($data->target-$data->totalpemetaan);$bpemsisa+=($data->target-$data->totalpemetaan);?></td>
                                        <td style="text-align: center;background:#e67e22;color:#fff"><?= round($data->prosentasepemetaan,2).'%'?></td>

																				<td style="text-align: center;background:#e74c3c;color:#fff"><?= $data->totalyuridis;$byudtotal+=$data->totalyuridis;?></td>
                                        <td style="text-align: center;background:#e74c3c;color:#fff"><?= ($data->target-$data->totalyuridis);$byudsisa+=($data->target-$data->totalyuridis);?></td>
                                        <td style="text-align: center;background:#e74c3c;color:#fff"><?= round($data->prosentaseyuridis,2).'%'?></td>

                                        <td style="text-align: center;"><?= round((round($data->prosentasek4,2)+round($data->prosentasepengukuran,2)+round($data->prosentasepemetaan,2)+round($data->prosentasepanitia,2)+round($data->prosentaseyuridis,2))/5,2).'%'?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="text-center">Total</td>
                                        <td class="text-center"></td>
                                        <td style="text-align: center;"><?= $btotal;?></td>
                                        <td style="text-align: center;"><?= $dtotal;?></td>
                                        <td style="text-align: center;"><?= $starget;?></td>
                                        <td style="text-align: center;"><?= $stotal;?></td>
                                        <td style="text-align: center;"><?= $ssisa;?></td>
                                        <td style="text-align: center;"><?= round(($stotal/$starget)*100,2).'%'?></td>
                                        <td style="text-align: center;"><?= $btarget;?></td>
                                        <td style="text-align: center;background:#3498db;color:#fff"><?= $bpantotal;?></td>
                                        <td style="text-align: center;background:#3498db;color:#fff"><?= $bpansisa;?></td>
                                        <td style="text-align: center;background:#3498db;color:#fff"><?= round(($bpantotal/$btarget)*100,2).'%'?></td>
																				<td style="text-align: center;background:#1abc9c;color:#fff"><?= $bpentotal;?></td>
                                        <td style="text-align: center;background:#1abc9c;color:#fff"><?= $bpensisa;?></td>
                                        <td style="text-align: center;background:#1abc9c;color:#fff"><?= round(($bpentotal/$btarget)*100,2).'%'?></td>
																				<td style="text-align: center;background:#e67e22;color:#fff"><?= $bpemtotal;?></td>
                                        <td style="text-align: center;background:#e67e22;color:#fff"><?= $bpemsisa;?></td>
                                        <td style="text-align: center;background:#e67e22;color:#fff"><?= round(($bpemtotal/$btarget)*100,2).'%'?></td>
																				<td style="text-align: center;background:#e74c3c;color:#fff"><?= $byudtotal;?></td>
                                        <td style="text-align: center;background:#e74c3c;color:#fff"><?= $byudsisa;?></td>
                                        <td style="text-align: center;background:#e74c3c;color:#fff"><?= round(($byudtotal/$btarget)*100,2).'%'?></td>
                                        <td style="text-align: center;"><?= ((round(($stotal/$starget)*100,2)+round(($bpantotal/$btarget)*100,2)+round(($bpentotal/$btarget)*100,2)+round(($bpemtotal/$btarget)*100,2)+round(($byudtotal/$btarget)*100,2))/5).'%'?></td>
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

<script>
	$(document).ready(function(){
		$('.sidebar-toggle').click();
	})
</script>
