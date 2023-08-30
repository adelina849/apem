			<?php	
				if(($akses_group1->akses1 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
				{
			?>
				<li id="inputdata" class="treeview">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Input Data (Basis Data)</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					
					<!-- CEK AKSES KARYAWAN -->
						<?php
							if(($akses_group1_main_group->akses11 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
							{
						?>
							<li id="input-data-karyawan">
							  <a href="#"><i class="fa fa-folder"></i> Input Karyawan <i class="fa fa-angle-left pull-right"></i></a>
							  
							  <ul class="treeview-menu">
								<!-- CEK AKSES JABATAN -->
								<?php if(($akses_group1_main_group_sub_main->akses111 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-karyawan-kategori"><a href="<?=base_url()?>admin-jabatan"><i class="fa fa-edit"></i> Jabatan </a></li>
								<?php } ?>
								<!-- CEK AKSES JABATAN -->
								
								<!-- CEK AKSES KARYAWAN -->
								<?php if(($akses_group1_main_group_sub_main->akses112 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-karyawan-karyawan"><a href="<?=base_url()?>admin-karyawan"><i class="fa fa-edit"></i> Karyawan </a></li>
								<?php } ?>
								<!-- CEK AKSES KARYAWAN -->
							  </ul>
							</li>
						<?php
							}
						?>
					<!-- CEK AKSES KARYAWAN -->
					
					
					<!-- CEK AKSES PRODUK -->
						<?php
							if(($akses_group1_main_group->akses12 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
							{
						?>
							<li id="input-data-produk">
							  <a href="#"><i class="fa fa-folder"></i> Input Produk <i class="fa fa-angle-left pull-right"></i></a>
							  <ul class="treeview-menu">
								<!-- CEK AKSES K PRODUK -->
								<?php if(($akses_group1_main_group_sub_main->akses121 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-produk-kategori"><a href="<?=base_url()?>admin-kategori-produk"><i class="fa fa-edit"></i> Kategori Produk </a></li>
								<?php
									}
								?>
								<!-- CEK AKSES K PRODUK -->
								
								<!-- CEK AKSES PRODUK -->
								<?php if(($akses_group1_main_group_sub_main->akses122 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-produk-produk"><a href="<?=base_url()?>admin-produk"><i class="fa fa-edit"></i> Produk
									</a></li>
								<?php
									}
								?>
								<!-- CEK AKSES PRODUK -->
							  </ul>
							</li>
						<?php
							}
						?>
					<!-- CEK AKSES PRODUK -->
					
					
					
					<!-- CEK AKSES COSTUMER -->
						<?php
							if(($akses_group1_main_group->akses13 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
							{
						?>
							<li id="input-data-costumer">
							  <a href="#"><i class="fa fa-folder"></i> Input Costumer <i class="fa fa-angle-left pull-right"></i></a>
							  <ul class="treeview-menu">
								<!-- CEK AKSES K COSTUMER -->
								<?php if(($akses_group1_main_group_sub_main->akses131 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-costumer-kategori"><a href="<?=base_url()?>admin-kategori-costumer"><i class="fa fa-edit"></i> Kategori Costumer </a></li>
								<?php
									}
								?>
								<!-- CEK AKSES K COSTUMER -->
								
								<!-- CEK AKSES COSTUMER -->
								<?php if(($akses_group1_main_group_sub_main->akses132 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-costumer-costumer"><a href="<?=base_url()?>admin-costumer"><i class="fa fa-edit"></i> Costumer </a></li>
								<?php
									}
								?>
								<!-- CEK AKSES COSTUMER -->
							  </ul>
							</li>
						<?php
							}
						?>
					<!-- CEK AKSES PRODUK -->
					
					<!-- CEK AKSES SUPPLIER -->
						<?php
							if(($akses_group1_main_group->akses14 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
							{
						?>
							<li id="input-data-supplier">
							  <a href="#"><i class="fa fa-folder"></i> Input Supplier <i class="fa fa-angle-left pull-right"></i></a>
							  <ul class="treeview-menu">
								<!-- CEK AKSES K SUPPLIER -->
								<?php if(($akses_group1_main_group_sub_main->akses141 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li id="input-data-supplier-kategori"><a href="<?=base_url()?>admin-kategori-supplier"><i class="fa fa-edit"></i> Kategori Supplier </a></li>
								<?php
									}
								?>
								<!-- CEK AKSES K SUPPLIER -->
								
								<!-- CEK AKSES SUPPLIER -->
								<?php if(($akses_group1_main_group_sub_main->akses142 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi')){
								?>
									<li  id="input-data-supplier-supplier"><a href="<?=base_url()?>admin-supplier"><i class="fa fa-edit"></i> Supplier </a></li>
								<?php
									}
								?>
								<!-- CEK AKSES SUPPLIER -->
							  </ul>
							</li>
						<?php
							}
						?>
					<!-- CEK AKSES SUPPLIER -->
					
					<!-- CEK AKSES BANK -->
						<?php
							if(($akses_group1_main_group->akses15 > 0) || ($this->session->userdata('ses_nama_jabatan') == 'Admin Aplikasi'))
							{
						?>
							<li id="input-data-bank"><a href="<?=base_url()?>admin-bank"><i class="fa fa-edit"></i> BANK</a></li>
						<?php
							}
						?>
					<!-- CEK AKSES BANK -->
					
					<!--<li>
					  <a href="#"><i class="fa fa-folder"></i> Input Assets <i class="fa fa-angle-left pull-right"></i></a>
					  <ul class="treeview-menu">
						<li><a href="#"><i class="fa fa-edit"></i> Kode/Kategori Assets </a></li>
						<li><a href="#"><i class="fa fa-edit"></i> Assets </a></li>
					  </ul>
					</li>-->
					
				  </ul>
				</li>
			<?php
				}
			?>