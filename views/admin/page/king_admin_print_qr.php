
	<style>
		@page { size: auto; margin-top:0mm;margin-bottom:0mm; }	
	</style>
	<!-- <body> -->
	<body onload="window.print();" style="font-family:arial narrow;font-size:13px;font-weight:bold;margin:1%;">
		
		<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;">
			<tr>
				<td style="width:15%;border-bottom:1px solid black;">
					<img id="img_bpt" src="<?php echo base_url('assets/global/images/jabar.png');?>" style="width:85%;">
				</td>
				<td style="text-align:center;width:70%;font-weight:bold;border-bottom:1px solid black;">
				ANJUNGAN PATEN MANDIRI (APEM)
				<br/>
				KECAMATAN CIBEBER 
				<!--
				DAN
				SISTEM PENAGIHAN PAJAK KENDARAAN BERMOTOR TERINTEGRITAS PELAYANAN TERPADU DI KECAMATAN
				(SI GANTENG DT)
				-->
				</td>
				<td style="width:15%;border-bottom:1px solid black;">
					<img id="img_cam" src="<?php echo base_url('assets/global/images/sugih_mukti.png');?>" style="width:100%;">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;">
						<tr>
							<td style="width:30%;"></td>
							<td style="width:3%;"></td>
							<td style="text-align:right;width:67%;">Cianjur, <?php echo date('Y-m-d');?></td>
						</tr>
						<tr>
							<td>Dokumen</td>
							<td>:</td>
							<td><?php echo $get_data_pengajuan->nama_jenis_naskah;?></td>
						</tr>
						<tr>
							<td>NIK Pemohon</td>
							<td>:</td>
							<td><?php echo $get_data_pengajuan->sumber;?></td>
						</tr>
						<tr>
							<td>Nama Pemohon</td>
							<td>:</td>
							<td><?php echo $get_data_pengajuan->diajukan_oleh;?></td>
						</tr>
						<tr>
							<td>Perihal</td>
							<td>:</td>
							<td><?php echo $get_data_pengajuan->perihal;?></td>
						</tr>
						<tr>
							<td>Diterima</td>
							<td>:</td>
							<td><?php echo $get_data_pengajuan->tgl_surat_masuk;?></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;">
								<img id="img_cam" src="<?php echo base_url('assets/global/images/qrcode/'.$get_data_pengajuan->no_pengajuan.'.png');?>" style="width:25%;">
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;font-weight:bold;border-bottom:1px solid black;">
								<?php echo $get_data_pengajuan->no_pengajuan;?>
							</td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;font-weight:normal;">
								Simpan slip ini sebagai bukti pengajuan dokumen untuk melihat status pengajuan, <br/>silahkan kunjungi halaman <?php echo base_url();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<body>