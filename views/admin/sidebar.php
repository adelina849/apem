        <style><!-- 
        SPAN.searchword { background-color:yellow; }
        // -->
        </style>
        <script src="<?=base_url();?>assets/global/js/searchhi.js" type="text/javascript" language="JavaScript"></script>
        <script language="JavaScript"><!--
        function loadSearchHighlight()
        {
          SearchHighlight();
          document.searchhi.h.value = searchhi_string;
          if( location.hash.length > 1 ) location.hash = location.hash;
        }
        // -->
        </script>
		
		<script type="text/javascript">
			//var htmlobjek;
			
			
			$(document).ready(function()
			{	
					//alert('<?php echo $this->session->userdata("ses_gnl_temaadmin"); ?>');
					
						//alert("BENAR");
						window.setInterval(function() //Terus dipanggil setiap 5 detik
						{
							
							
							//PERMAINAN CSS SIDEBAR MENU
								$("li").filter('.treeview').css('border', '0px solid grey');
								$("li").filter('.treeview').css('box-shadow', '2px 2px 2px rgba(0,0,0,0.0)');
								$("li").filter('.treeview').css('margin-right', '0%');
								
								$("li").filter('.active').filter('.treeview').css('border', '1px dashed grey');
								$("li").filter('.active').filter('.treeview').css('box-shadow', '2px 2px 2px rgba(0,0,0,0.3)');
								$("li").filter('.active').filter('.treeview').css('margin-right', '1%');
								
								$("li").filter('.active').filter('.treeview').attr('class','active treeview menu-open'); //SET MENU AKTIF
							//PERMAINAN CSS SIDEBAR MENU
							
							
						}, 99);
						
						//MENGAKALI ANIMASI PANAH SIDEBAR KARENA TIDAK JALAN
						$('.treeview').hover(function() {
							var className = $('#'+this.id).attr("class");
							//alert(className); // Outputs: hint
							var position = className.search("menu-open");
							//alert(position);
							
							var position_active = className.search("active");
							//alert(position_active);
							
							//if(position >= 0)
							if(position_active >= 0)
							{
								$('#'+this.id).attr('class', 'active treeview menu-open');
							}
							else
							{
								if(position >= 0)
								{
									$('#'+this.id).attr('class', 'treeview');
								}
								else
								{
									$('#'+this.id).attr('class', 'treeview menu-open');
								}
								
							}
							
						});
						//MENGAKALI ANIMASI PANAH SIDEBAR KARENA TIDAK JALAN
					
					
				//});
				
				
				
			});
		</script>
		
    <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $this->session->userdata('ses_avatar_url');?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata('ses_nama_karyawan');?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form method="get" name="searchhi" class="sidebar-form" action="#">
            <div class="input-group">
              <input type="text" name="h" onkeyup="oWhichSubmit(this)" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
			<!-- CEK HAK AKSES FROM DATABASE -->
				<?php
					/*$akses_group1 = $this->m_akun->get_hak_akses_group1($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group = $this->m_akun->get_hak_akses_group1_main_group($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group_sub_main = $this->m_akun->get_hak_akses_group1_main_group_sub_group($this->session->userdata('ses_id_jabatan'));*/
				?>
			<!-- CEK HAK AKSES FROM DATABASE -->
		  
            <li class="header">MAIN NAVIGATION</li>
            <!-- <li class="active treeview"> -->
			<li id="dashboard" class="treeview">
              <a href="<?=base_url()?>admin"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
			
			<!-- CEK AKSES GROUP1 = 1 -->
			
				<li id="inputdata" class="treeview">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Input Data (Basis Data)</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					
					<!-- CEK AKSES KARYAWAN -->
							<li id="input-data-karyawan">
							  <a href="#"><i class="fa fa-folder"></i> Input Karyawan <i class="fa fa-angle-left pull-right"></i></a>
							  
							  <ul class="treeview-menu">
								<!-- CEK AKSES JABATAN -->
									<li id="input-data-karyawan-kategori"><a href="<?=base_url()?>admin-jabatan"><i class="fa fa-edit"></i> Jabatan </a></li>
								<!-- CEK AKSES JABATAN -->
								
								<!-- CEK AKSES KARYAWAN -->
									<li id="input-data-karyawan-karyawan"><a href="<?=base_url()?>admin-karyawan"><i class="fa fa-edit"></i> Karyawan </a></li>
								<!-- CEK AKSES KARYAWAN -->
							  </ul>
							</li>
					<!-- CEK AKSES KARYAWAN -->
					
					<!-- CEK AKSES KATEGORI NASKAH -->
							<li id="input-data-jenis-naskah" class="treeview">
							  <a href="<?=base_url()?>admin-jenis-dokumen">
								<i class="fa fa-edit"></i> <span>Jenis Dokumen</span>
							  </a>
							</li>
					<!-- CEK AKSES KATEGORI NASKAH -->
					
					<!-- CEK AKSES TAHAPAN PROSES -->
							<li id="input-data-tahapan" class="treeview">
							  <a href="<?=base_url()?>admin-jenis-tahapan">
								<i class="fa fa-edit"></i> <span>Tahapan Proses</span>
							  </a>
							</li>
					<!-- CEK AKSES TAHAPAN PROSES -->
					
					<!-- CEK AKSES DATA PENDUDUK -->
							<li id="input-data-penduduk" class="treeview">
							  <a href="<?=base_url()?>admin-penduduk">
								<i class="fa fa-users"></i> <span>Data Penduduk</span>
							  </a>
							</li>
					<!-- CEK AKSES DATA PENDUDUK -->
					
					<!-- CEK AKSES PETUGAS PROSES -->
							<!--<li id="setting-stock" class="treeview">
							  <a href="<?=base_url()?>admin-setting-stock">
								<i class="fa fa-edit"></i> <span>Petugas (Proses)</span>
							  </a>
							</li>-->
					<!-- CEK AKSES PETUGAS PROSES -->
					
					<!-- CEK AKSES HASIL PROSES -->
							<!--<li id="setting-stock" class="treeview">
							  <a href="<?=base_url()?>admin-setting-stock">
								<i class="fa fa-edit"></i> <span>Hasil/Keputusan</span>
							  </a>
							</li>-->
					<!-- CEK AKSES HASIL PROSES -->
					
					<!-- CEK AKSES KONTES -->
							<!--<li id="input-data-akun"><a href="<?=base_url()?>admin-nomor-akun"><i class="fa fa-edit"></i> Nomor Akun</a></li>-->
					<!-- CEK AKSES KONTES -->
					
					
				  </ul>
				</li>
			<!-- CEK AKSES GROUP1 = 1 -->
			
			<!-- CEK OPERASI-->
					<li id="operation" class="treeview">
					  <a href="<?=base_url()?>admin-tahapan-dokumen">
						<i class="fa fa-circle-o"></i> <span>Pengaturan</span>
					  </a>
					</li>
			<!-- CEK OPERASI-->
			
			<!-- CEK AKSES TRANSAKSI -->
				<li class="treeview" id="transaksi">
				  <a href="#">
					<i class="fa fa-table"></i> <span>Transaksi</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					<!-- CEK AKSES Kategori LAPORAN -->
							<li id="transaksi-pengajuan"><a href="<?=base_url()?>admin-pengajuan-dokumen"><i class="fa fa-circle-o"></i> Input Pengajuan</a></li>
					<!-- CEK AKSES Kategori LAPORAN -->
					
					<!-- CEK AKSES JENIS LAPORAN -->
							<li id="status-naskah"><a href="<?=base_url()?>admin-status-dokumen"><i class="fa fa-circle-o"></i> Update Status</a></li>
					<!-- CEK AKSES JENIS LAPORAN -->
					
				  </ul>
				</li>
			<!-- CEK AKSES TRANSAKSI -->
			
			<!-- CEK AKSES HASIL LAPORAN-->
					<li id="laporan-dokumen" class="treeview">
					  <a href="<?=base_url()?>admin-laporan-dokumen">
						<i class="fa fa-share"></i> <span>Laporan</span>
					  </a>
					</li>
			<!-- CEK AKSES HASIL LAPORAN-->
			
			<!-- CEK AKSES HASIL LAPORAN-->
					<li id="statistik-dokumen" class="treeview">
					  <a href="<?=base_url()?>admin-statistik-dokumen">
						<i class="fa fa-share"></i> <span>Statistik</span>
					  </a>
					</li>
			<!-- CEK AKSES HASIL LAPORAN-->
			
			<!-- CEK AKSES GROUP1 = 6 AKUN-->
					<li id="aksesakun" class="treeview">
					  <a href="#">
						<i class="fa fa-share"></i> <span>Hak Akses dan Akun</span>
						<i class="fa fa-angle-left pull-right"></i>
					  </a>
					  <ul class="treeview-menu">
					  
						<!-- CEK AKSES AKUN -->
								<li id="akunakses-akun">
									<a href="<?=base_url()?>admin-akun"><i class="fa fa-circle-o"></i>Pemberian Akun</a>
								</li>
						<!-- CEK AKSES AKUN-->
						<!-- <li><a href="#"><i class="fa fa-circle-o"></i>Pemberian hak Akses</a></li> -->
					  </ul>
					</li>
			<!-- CEK AKSES GROUP1 = 6 AKUN-->
			
			
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>