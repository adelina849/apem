
	<style>
		@page { size: auto; margin-top:0mm;margin-bottom:0mm; }	
	</style>
	<!-- <body> -->
	<body onload="window.print();" style="font-family:arial narrow;font-size:13px;font-weight:bold;margin:1%;">
		<!-- <textarea name="format_naskah" id="format_naskah" class="required form-control" style="" title="Isikan Artikel" placeholder="*Isikan Artikel"><?php echo $get_jenis_naskah->format_naskah;?></textarea> -->
		
		<?php //echo date('Y/m/d') ?>
		<?php
		
			/*
			//GET DATA DI FORMAT NASKAH
				$query_var_naskah = "SELECT * FROM tb_var_naskah WHERE id_jenis_naskah = '".$get_jenis_naskah->id_jenis_naskah."';";
				$var_naskah = $this->M_dash->view_query_general($query_var_naskah);
				if(!empty($var_naskah))
				{
					
					//$data_arr_kat_costumer = array();
					//$data_arr_format_naskah = array();
					$find2 = [];
					foreach ($var_naskah->result_array() as $format):                                                     
								//$data_arr_format_naskah['{'.$format['nama_var'].'}'] = $format['ket'];
								$find2[] =  '{'.$format['nama_var'].'}';
					endforeach;
					
				}	
				else
				{
					//$data_arr_kat_costumer['kosong'] = "";
					//$data_arr_format_naskah['kosong'] = "";
					$find2 = [];
				}
			//GET DATA DI FORMAT NASKAH
			//echo '<br/>FORMAT NASKAH : '.$data_arr_format_naskah['{Nomor Surat}'];
			*/
			
			
		
			//1. DATA PENDUDUK
			$find = ['[nik]','[nama]','[jenis_kelamin]','[tempat_lahir]','[tgl_lahir]','[tlp]','[email]','[alamat]'];
			$replace = 
						[
							$get_data_penduduk->nik
							,$get_data_penduduk->nama
							,$get_data_penduduk->jenis_kelamin
							,$get_data_penduduk->tempat_lahir
							,tanggal($get_data_penduduk->tgl_lahir)
							,$get_data_penduduk->tlp
							,$get_data_penduduk->email
							,$get_data_penduduk->alamat
						];
			
			$string = str_replace($find,$replace,$get_jenis_naskah->format_naskah);
			//1. DATA PENDUDUK
			
			//2. DATA ISIAN
			//GET ISIAN NASKAH
				if(!empty($get_isian_naskah))
				{
					
					//$data_arr_kat_costumer = array();
					//$data_arr_isian_naskah = array();
					$find2 = [];
					$replace2 = [];
					//foreach ($get_isian_naskah->result_array() as $isian):                                                     
					foreach ($get_isian_naskah->result() as $isian):                                                     
								//$data_arr_isian_naskah['{'.$isian['var_naskah'].'}'] = $isian['isi'];
								$find2[] = '{'.$isian->var_naskah.'}';
								$replace2[] = $isian->isi;
					endforeach;
					
				}	
				else
				{
					//$data_arr_kat_costumer['kosong'] = "";
					$find2 = [];
					$replace2 = [];
				}
				
				//CONTOH PENGGUNAAN
				//$id_kat_costumer = $data_arr_kat_costumer[$rowData[0][3]];
			//GET ISIAN NASKAH
			//echo '<br/>ISIAN NASKAH : '.$data_arr_isian_naskah['{Nomor Surat}'];
			//echo '<br/>ID_EPNGAJUAN_FORMAT : '.$id_pengajuan_format;
			$string = str_replace($find2,$replace2,$string);
			//2. DATA ISIAN
			
			//3. DATA FORMAT
			$string = str_replace('@tanggal@',tanggal(date('Y-m-d')),$string);
			//3. DATA FORMAT
			
			

			echo htmlspecialchars_decode($string);
			
			
			
		?>
	<body>