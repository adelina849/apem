
	<style>
		@page { size: auto; margin-top:0mm;margin-bottom:0mm; }	
	</style>
	<body onload="window.print();" style="font-family:arial narrow;font-size:13px;font-weight:bold;margin:1%;">
		<!-- <textarea name="format_naskah" id="format_naskah" class="required form-control" style="" title="Isikan Artikel" placeholder="*Isikan Artikel"><?php echo $get_jenis_naskah->format_naskah;?></textarea> -->
		
		<?php //echo date('Y/m/d') ?>
		<?php
			/*
			echo 
				htmlspecialchars_decode(
				str_replace('[','$get_data_penduduk->',
				str_replace(']',';',$get_jenis_naskah->format_naskah)
				)
				);
			*/
				
			/*
			echo 
				str_replace('[','<?php echo $get_data_penduduk->',
				str_replace(']',';?>',htmlspecialchars_decode($get_jenis_naskah->format_naskah))
				);
			*/
			//printf(trim($get_jenis_naskah->format_naskah));
			//echo'<h1>Hello User, </h1> <p>Welcome to {$name}</p>';
			//echo'<p>Hi [nama] <br> Lokasi : {Lokasi Menikah}<img src="http://localhost/apem/assets/images/bintang.png" style="width: 166.172px; height: 110.712px;"></p>';
			
			/*
			echo'<br/>';
			echo'<br/>';
			echo $get_data_penduduk->nama;
			echo'<br/>';
			echo'<br/>';
			echo '[nama] : '.$get_data_penduduk->nama;
			echo'<br/>';
			echo'<br/>';
			echo 
				htmlentities(
				str_replace('[','$get_data_penduduk->',
				str_replace(']',';','[nama]')
				)
				);
			echo'<br/>';
			echo'<br/>';
			echo $get_data_penduduk->nama;
			*/
			
			
			/*
			$var = "
			 <div>
			 $img
			 </div>";
			echo $var;
			*/
			
			$string = (
				str_replace('[','<div> $get_data_penduduk->',
				str_replace(']','</div>',trim('[nama]'))
				)
				);
			$string2 = '<?php echo $get_data_penduduk->nama;?>';
			$var = "
			 <div>
			 $get_data_penduduk->nama
			 </div>";
			
			//echo $get_data_penduduk->nama;
			//html_entity_decode
			//echo $var;
			//echo ($string);
			//echo htmlspecialchars_decode($string);
			
			$string_fix =  
				html_entity_decode
				(
				htmlspecialchars_decode
				(
				str_replace('[','$get_data_penduduk->',
				str_replace(']','',$get_jenis_naskah->format_naskah)
				)
				)
				);
				
				echo $string_fix;
				
			
			
			
		?>
	<body>