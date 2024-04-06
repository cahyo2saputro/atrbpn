<?php defined('BASEPATH') OR exit('No direct script access allowed'); $user = $this->auth_model->get_userdata(); ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/form-validator/jquery.form-validator.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<div class="box box-primary">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="box-body">
                      <h2>Grafik Input PTSL bulan <?=fbulan(date('m')).' '.date('Y')?></h2>
                      <div class="chart">
                        <div id="myfirstchart" style="height: 250px;"></div>
                      </div>
                      <div class='col-12'>
                        <table class='table table-striped'>
                          <tr>
                            <td>No</td>
                            <td>Kode</td>
                            <td>Kelurahan</td>
                            <td>Jumlah</td>
                          </tr>
                        <?php
                        $i=1;
                        foreach($graphic as $gr){
                          $dat['table'] = "tb_ptsl";
                          $dat ['join']['table']	= "tb_block";
                          $dat ['join']['key']	= "idblk_ptsl";
                          $dat ['join']['ref']	= "idblk_blk";
                          $dat['type'] = "single";
                          $dat['column'] = "COUNT(id_ptsl) as jumlah";
                          $dat['condition']['idkel_blk'] = $gr->kd_full;
                          $dat['cuzcondition'] = '(tb_ptsl.create_at like "%'.date('Y-m').'%" OR tb_ptsl.update_at like "%'.date('Y-m').'%")';
                          $hasil = $this->crud_model->get_data($dat);
                          $i++
                          // echo $hasil['jumlah'];
                          ?>
                          <tr>
                            <td><?=$i?></td>
                            <td><?=$gr->kd_full?></td>
                            <td><?=$gr->nma_kel?></td>
                            <td><?=$hasil['jumlah']?></td>
                          </tr>
                          <?php
                        }
                        ?>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
new Morris.Line({
// ID of the element in which to draw the chart.
element: 'myfirstchart',
// Chart data records -- each entry in this array corresponds to a point on
// the chart.
data: [
  <?php
  $date = date('F Y');//Current Month Year
  while (strtotime($date) <= strtotime(date('Y-m') . '-' . date('t', strtotime($date)))) {
      $day_num = date('Y-m-d', strtotime($date));
      $day_condition = date('Y-m-d', strtotime($date));//th, nd, st and rd
      $day = "$day_num";
      $datas = '';
      foreach($graphic as $gr){
        $dat['table'] = "tb_ptsl";
        $dat ['join']['table']	= "tb_block";
  			$dat ['join']['key']	= "idblk_ptsl";
  			$dat ['join']['ref']	= "idblk_blk";
        $dat['type'] = "single";
        $dat['column'] = "COUNT(id_ptsl) as jumlah";
        $dat['condition']['idkel_blk'] = $gr->kd_full;
        $dat['cuzcondition'] = '(tb_ptsl.create_at like "%'.$day_condition.'%" OR tb_ptsl.update_at like "%'.$day_condition.'%")';
        $hasil = $this->crud_model->get_data($dat);
        // echo $hasil['jumlah'];

        $datas .= $gr->kd_full.':'.$hasil['jumlah'].',';
      }
      ?>{ day: '<?=$day?>', <?=$datas?> },<?php
      $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));//Adds 1 day onto current date
  }
  ?>
],
// The name of the data record attribute that contains x-values.
xkey: 'day',
// A list of names of data record attributes that contain y-values.
<?php
  $label='';$value='';
  foreach($graphic as $gr){
    $value .= "'".$gr->kd_full."',";
    $label .= "'".$gr->nma_kel."',";
  }
?>
ykeys: [<?=$value?>],
// Labels for the ykeys -- will be displayed when you hover over the
// chart.
labels: [<?=$label?>],
xLabelFormat: function (d) {
    return ("0" + d.getDate()).slice(-2) + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
  },
});
</script>
<script>
	$(document).ready(function(){
		$('.sidebar-toggle').click();
	})
</script>
