<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user = $this->auth_model->get_userdata();
$file='';
if($this->input->get('bulan')){
	$bulan = explode('-',$this->input->get('bulan'));
	$bulan = fbulan($bulan[1]).' '.$bulan[0];
	$file .= ' Bulan '.$bulan;
}else{
	$bulan = explode('-',date('Y-m'));
	$bulan = fbulan($bulan[1]).' '.$bulan[0];
	$file .= ' Bulan '.$bulan;
}

@$namafile="Rekap Laporan Akta PPAT ".$file;
header("Content-type:application/vnd.ms-excel");
header("Content-disposition:attachment; filename=\"$namafile.xls\"");
header("Pragma:no-cache");
header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
header("Expires:0");

?>
<table id="data-staff" class="table table-bordered">
	<thead>
		<tr>
			<th rowspan=2>No</th>
			<th rowspan=2>PPAT</th>
			<th colspan=2 style='text-align:center'>Jual Beli</th>
			<th colspan=2 style='text-align:center'>hibah</th>
			<th colspan=2 style='text-align:center'>PHB</th>
			<th colspan=2 style='text-align:center'>HT</th>
			<th colspan=2 style='text-align:center'>SKMHT</th>
			<th colspan=2 style='text-align:center'>ATM</th>
			<th rowspan=2>∑</th>
			<th rowspan=2>Luas</th>
			<th rowspan=2>Nilai HT</th>
			<th rowspan=2>Transaksi Jual Beli</th>
			<th rowspan=2>SSP</th>
			<th rowspan=2>SPPD</th>
		</tr>
		<tr>
			<th>∑</th>
			<th>Luas</th>
			<th>∑</th>
			<th>Luas</th>
			<th>∑</th>
			<th>Luas</th>
			<th>∑</th>
			<th>Luas</th>
			<th>∑</th>
			<th>Luas</th>
			<th>∑</th>
			<th>Luas</th>
		</tr>
	</thead>
	<tbody id="tabel-body">
		<?php
			$no = $this->uri->segment('3') + 1;
			foreach ($studio as $st) {

		 ?>
		 <tr>
		 	<td><?=$no++?></td>
		 	<td><?=$st['name_usr']?></td>
		 	<td><?=$st['sumjb']?></td>
			<td><?=$st['luasjb']?></td>
			<td><?=$st['sumhibah']?></td>
			<td><?=$st['luashibah']?></td>
			<td><?=$st['sumphb']?></td>
			<td><?=$st['luasphb']?></td>
			<td><?=$st['sumht']?></td>
			<td><?=$st['luasht']?></td>
			<td><?=$st['sumskmht']?></td>
			<td><?=$st['luasskmht']?></td>
			<td><?=$st['sumatm']?></td>
			<td><?=$st['luasatm']?></td>
			<td><?=$st['sumtotal']?></td>
			<td><?=$st['luastotal']?></td>
			<td><?=$st['nilaiht']?></td>
			<td><?=$st['nilaiajb']?></td>
			<td><?=$st['nilaissp']?></td>
			<td><?=$st['nilaisppd']?></td>
		 </tr>
		<?php } ?>
	</tbody>
</table>
