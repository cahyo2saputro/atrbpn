<?php
    @$namafile="Sample DHKP Kecamatan ".$desa['nma_kec']." Kelurahan ".$desa['nma_kel'];
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment; filename=\"$namafile.xls\"");
    header("Pragma:no-cache");
    header("Cache-Control:must-revalidate, post-check=\"0\", pre-check=\"0\"");
    header("Expires:0");
?>
<table>
    <tbody>
      <tr>
          <th colspan='7' style="text-align:center">DAFTAR HIMPUNAN KETETAPAN PAJAK BUMI DAN BANGUNAN</th>
      </tr>
      <tr>
          <th>KABUPATEN</th>
          <td></td>
          <td>: 022 SEMARANG</td>
      </tr>
      <tr>
          <th>KECAMATAN</th>
          <td></td>
          <td>: <?= $desa['kdpbb_kec'].' '.$desa['nma_kec']; ?></td>
      </tr>
        <tr>
            <th>KELURAHAN</th>
            <td></td>
            <td>: <?= $desa['kdpbb_kel'].' '.$desa['nma_kel'];?></td>
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
            <th>NOP</th>
            <th>KECAMATAN</th>
            <th>KELURAHAN</th>
            <th>NAMA</th>
            <th>ALAMAT WAJIB PAJAK</th>
            <th>LETAK OBJEK PAJAK</th>
            <th>LUAS BUMI</th>
            <th>LUAS BANGUNAN</th>
            <th>TAHUN</th>
            <th>TAGIHAN (PAJAK)</th>
            <th>KET.</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
