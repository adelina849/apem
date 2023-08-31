<!DOCTYPE HTML>
    <head>
    	<meta http-equiv="content-type" content="text/html" />
    	<meta name="IMS Technology 2017" content="Dashboard Admin" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		
		<!--
		<meta content="sekpri,cianjur,kabupaten,naskah,dinas,sistem" name="keywords">
		<meta content="SISTEM TATA NASKAH DINAS SEKPRI KAB. CIANJUR adalah sistem aplikasi website terintegrasi yang dibuat/dirancang untuk mempermudah pengecekan dan pengelolaan naskah dinas yang masuk di kabupaten cianjur." name="description">
		-->
		
		<meta content="cibeber, cianjur, apem, pelayanan, paten" name="keywords">
		<meta content="Anjungan PATEN Mandiri (APEM) Kecamatan Cibeber adalah sebuah sistem layanan self service atau layanan sendiri yang berbasis sistem pelayanan terpadu menggunakan digital, untuk mempermudah masyarakat dalam pelayanan administrasi yang ada di Kecamatan Cibeber." name="description">
    
		<?php
			if(!empty($title))
			{
				echo'<title>'.$title.'</title>';
			}
			else
			{
				echo'<title>Anjungan Paten Mandiri | APEM</title>';
			}
		?>
    	
        
        <!-- view source dll.-->
        <!--<script type="text/javascript">
        window.addEventListener("keydown",function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){e.preventDefault()}});document.keypress=function(e){if(e.ctrlKey&&(e.which==65||e.which==66||e.which==67||e.which==73||e.which==80||e.which==83||e.which==85||e.which==86)){}return false}
        </script>
        <script type="text/javascript">
        document.onkeydown=function(e){e=e||window.event;if(e.keyCode==123||e.keyCode==18){return false}}
        </script>-->
        
        <!-- mengaktifkan javascript-->
        <!--<div align="center"><noscript>
           <div style="position:fixed; top:0px; left:0px; z-index:3000; height:100%; width:100%; background-color:#FFFFFF">
           <div style="font-family: Arial; font-size: 17px; background-color:#00bbf9; padding: 11pt;">Mohon aktifkan javascript pada browser untuk mengakses halaman ini!</div></div>
        </noscript></div>-->
        
        <!--kanan-->
        <!--<script type="text/javascript">
        function mousedwn(e){try{if(event.button==2||event.button==3)return false}catch(e){if(e.which==3)return false}}document.oncontextmenu=function(){return false};document.ondragstart=function(){return false};document.onmousedown=mousedwn
        </script>-->
        
        
        <!-- jQuery 2.1.4 -->
        <script src="<?=base_url();?>assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    </head>
    
    <body class="skin-red-light sidebar-mini" onLoad="JavaScript:loadSearchHighlight();">
        <div class="wrapper"> <!-- Buka | Untuk Wrapper/Container -->
            
            <!--<div> <!-- Buka | Untuk Header -->
                <?php
                    $this->load->view('admin/header');
                ?>
            <!--</div> <!-- Tutup | Untuk Header -->
            
            
                <!-- Buka | Untuk Sidebar-->
                   <?php 
                        $this->load->view('admin/sidebar');
                   ?> 
                <!-- Tutup | Untuk Sidebar -->
                
                
                
                    <?php 
                        $this->load->view('admin/page/'.$page_content.'.html');
                        //$this->load->view('admin/page/'.$page_content);
                    ?>
                

            
            <div> <!-- Buka | Untuk Footer-->
                <?php 
                    $this->load->view('admin/footer');
                ?>
            </div> <!-- Tutup | Untuk Footer -->
            
            <div> <!-- Buka | Sidebar control-->
                <?php 
                    $this->load->view('admin/control_sidebar');
                ?>
            </div> <!-- Tutup | Sidebar Control -->
            
        </div> <!-- Tutup | Untuk Wrapper/Container -->
        
         
        <!-- AdminLTE for demo purposes -->
        <script src="<?=base_url();?>assets/adminlte/dist/js/demo.js"></script>
        <!-- page script -->
        
    </body>
</html>