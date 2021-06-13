<html>
<head>
<style type="text/css" media="print">
	table {border: solid 1px #000; border-collapse: collapse; width: 100%}
	tr { border: solid 1px #000}
	td { padding: 7px 5px}
	h3 { margin-bottom: -17px }
	h2 { margin-bottom: 0px }
</style>
<style type="text/css" media="screen">
	table {border: solid 1px #000; border-collapse: collapse; width: 60%}
	tr { border: solid 1px #000}
	td { padding: 7px 5px}
	h3 { margin-bottom: -17px }
	h2 { margin-bottom: 0px }
</style>
</head>

<body style="height:700px;width:800px">
<table>
	<!--<tr>
		<td width = "5%">&nbsp;</td>
		<td width = "10%"><img width = "45px" height = "45px" src="<?php echo base_URL()?>/upload/logo.png"></td>
		<td width ="80%"  align="left" ><b style="font-size: 24px;">BPS Kabupaten Semarang</b></td>
		<td width = "5%">&nbsp;</td>
	</tr>
	
	<tr>
		<td width = "80%" align = "center" colspan="4"><b style="font-size: 18px; font-weight:bold;">Rekap Data Mitra Berdasarkan Kecamatan</b></td>
	</tr>
	<!--if (!empty($data)) {
		$no = 0;
		foreach ($data as $d) {
		?>-->
		<table border="1" class="table table-bordered table-hover">
	<thead>
			<th width="5%">No</th>
			<th>Jenis</th>
			<th>No Peminjaman</th>
			<th>Status Peminjam</th>
			<th>Id Peminjam</th>
			<th>Peminjam</th>
			<th>Tgl pinjam</th>
			<th>Tgl pengembalian</th>
			<th></th>
	</thead>
	
	<tbody>
	<?php 
          $i = 1;
          foreach($peminjaman->result() as $row): 
          ?>
          <tr>
            <td align="center"><?=$i?></td>
            <td align="center"><?=($row->jenis_pinjaman==1)?'Peminjaman':'Praktikum'?></td>
            <td align="center"><?=$row->no_peminjaman?></td>
            <td align="center"><?=status_peminjam($row->status_peminjam)?></td>
            <td align="center"><?=$row->id_peminjam?></td>
            <td align="center"><?=$row->peminjam?></td>
            <td align="center"><?=$row->tgl.' '.$row->jam_pinjam?></td>
            <td align="center"><?=$row->tgl_kembali.' '.$row->jam_kembali?></td>
          </tr>
        <?php endforeach;?>
        
	</tbody>
</table>

<script>
	window.print()
</script>