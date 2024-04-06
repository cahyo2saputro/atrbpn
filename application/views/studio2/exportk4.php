<?php
    @$namafile="Sample K4 Kecamatan ".$desa['nma_kec']." Kelurahan ".$desa['nma_kel'];
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table>
    <tbody>
      <tr>
          <th>KABUPATEN</th>
          <td></td>
          <td>: 07 SEMARANG</td>
      </tr>
      <tr>
          <th>KECAMATAN</th>
          <td></td>
          <td>: <?= $desa['kd_kec'].' '.$desa['nma_kec']; ?></td>
      </tr>
        <tr>
            <th>KELURAHAN</th>
            <td></td>
            <td>: <?= $desa['kd_kel'].' '.$desa['nma_kel'];?></td>
        </tr>
        <tr>
            <th></th>
            <td></td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>Tipe Hak</th>
            <th>Nomor Hak</th>
            <th>Surat Ukur</th>
            <th>NIB</th>
            <th>Luas</th>
            <th>Luas Peta</th>
            <th>KW</th>
            <th>Pemilik Pertama</th>
            <th>Pemilik Akhir</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
