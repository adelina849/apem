<?php

	Class C_admin_login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(array('captcha','array'));
            $this->load->library(array('form_validation'));
            $this->config->load('cap');
			$this->load->model(array('M_dash','M_penduduk'));
		}
		
		
		function index()
		{
			// $this->auto_remove_captcha();
			// $user = $this->input->post('user');
			// $pass = $this->input->post('pass');
			
            // if($this->validasi_input_captcha() == false)
            // {
                // $data = array();
				
				// $text = ucfirst(random_element($this->config->item('captcha_word')));
					// $number = random_element($this->config->item('captcha_num'));
					// $word = $text.$number;

					// $expiration = time()-300; //batas waktu 5 menit
					// $this->db->query('DELETE FROM tb_captcha WHERE captcha_time < '.$expiration);
					// //konfigurasi captcha
					// $vals = array(
						// 'font_path' => 'system/fonts/texb.ttf',
						// 'img_path' => './assets/captcha/',
						// 'img_url' => base_url().'assets/captcha/',
						// 'img_width' => '235',
						// 'img_height' => 50,
						// 'word' => $word,
						// 'expiration' => $expiration,
						// 'time' => date('Y-m-d H:i:s')
						// );
					
					// $cap = create_captcha($vals);
					// $data['captcha_img'] = $cap['image'];
					
					 // $captcha = array(   'captcha_id' => '',
										// 'captcha_time' => date('Y-m-d H:i:s'),
										// 'ip_address' => $this->input->ip_address(),
										// 'word' => "TEST"
										// );
					 // $query = $this->db->insert_string('tb_captcha',$captcha);
					// $this->db->query($query);	
					
					
					// $this->db->query('OPTIMIZE TABLE `tb_captcha` ');
                    // $this->load->view('admin/login.html',$data);
                    
            // }
            // else
            // {
                    // $this->cek_login();
            // }
			
			
			// if(($this->session->userdata('user_admin') == null) or ($this->session->userdata('pass_admin') == null))
			// {
				// //redirect('index.php/admin-login','location');
				// header('Location: '.base_url().'admin-login');
			// }
			// else
			// {
				// $data_login = $this->M_a_akun->cek_login($this->session->userdata('user_admin'),md5(base64_decode($this->session->userdata('pass_admin'))),'0');
				// if (!empty($data_login))
				// {
                    // $listKategori = $this->M_kat_produk->listKategori();
                    // $listPrduk = $this->M_produk->listproduk('','');
					// $data = array('page_content'=>'adm_produk','title'=>'Elga Network | Dashboard Admin Produk','listKategori'=>$listKategori,'listPrduk'=>$listPrduk);
					// $this->load->view('admin/container',$data);
				// //}
				// // else
				// // {
					// // header('Location: '.base_url().'admin-login');
				// // }
				
				$this->load->view('admin/login.html');
				////}
			////}
		}
		
		function login_warga()
		{
			$this->load->view('admin/login_warga.html');
		}
		
		function cek_nik()
		{
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$query_cek_nik = "SELECT * FROM tb_penduduk WHERE nik = '".$nik."'";
			$cek_nik = $this->M_dash->view_query_general($query_cek_nik);
			if(!empty($cek_nik))
			{
				$cek_nik = $cek_nik->row();
				//echo $cek_nik->nama;
				
				$query_list_layanan = "SELECT * FROM tb_jenis_naskah ORDER BY nama_jenis_naskah ASC";
				$list_layanan = $this->M_dash->view_query_general($query_list_layanan);
				if(!empty($list_layanan))
				{
					echo'<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;">';
						echo'<thead>';
						echo'<tr>';
													echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;">No</th>';
													echo '<th width="35%" style="background-color:red;color:white;font-weight:bold;">NAMA PELAYANAN</th>';
													echo '<th width="45%" style="background-color:red;color:white;font-weight:bold;">PERSYARATAN</th>';
													echo '<th width="15%" style="background-color:red;color:white;font-weight:bold;">Aksi</th>';
						echo'</tr>';
						echo'</thead>';
						
						$list_result = $list_layanan->result();
						$no = 1;
						echo '<tbody>';
						foreach($list_result as $row)
						{
							echo'<tr>';
								echo'<td>'.$no.'</td>';
								echo'<td>'.$row->nama_jenis_naskah.'</td>';
								echo'<td>'.
											str_replace('6.','<br/>6.',
												str_replace('5.','<br/>5.',
													str_replace('4.','<br/>4.',
														str_replace('3.','<br/>3.',
															str_replace('2.','<br/>2.',$row->syarat_jenis_naskah)
														)
													)
												)
											)
										.'</td>';
							
								echo'<input type="hidden" id="id_jenis_naskah_'.$no.'" value="'.$row->id_jenis_naskah.'" />';
								echo'<input type="hidden" id="nama_jenis_naskah_'.$no.'" value="'.$row->nama_jenis_naskah.'" />';
								echo'<input type="hidden" id="syarat_jenis_naskah_'.$no.'" value="'.$row->syarat_jenis_naskah.'" />';
								echo'<input type="hidden" id="ket_jenis_naskah_'.$no.'" value="'.$row->ket_jenis_naskah.'" />';
								
								echo'<td>

<a href="javascript:void(0)" class="btn btn-success btn-sm btn-flat btn-block" onclick="edit('.$no.')" title = "Ubah Data '.$row->nama_jenis_naskah.'" alt = "Ubah Data '.$row->nama_jenis_naskah.'">PILIH</a>
								
								</td>';
								
								
							echo'</tr>';
							$no++;
						}
						echo '</tbody>';
					echo'</table>';
				}
			}
			else
			{
				return false;
			}
		}
        
		function daftar_warga()
		{
			echo'<h2><center>FORM PENDAFTARAN</center></h2>';
			echo'<form role="form" class="frm-input" enctype="multipart/form-data">';
			echo'<div class="form-group">
				  <label for="nik">NIK</label>
				  <input type="text" id="nik" name="nik"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" onchange="cek_nik_pas_daftar()"/><span id="pesan"></span>
				</div>';
			echo'<div class="form-group">
				  <label for="nama">Nama Karyawan</label>
				  <input type="text" id="nama" name="nama"  maxlength="35" class="required form-control" size="35" alt="nama" title="Nama Karyawan" placeholder="*Nama"/>
				</div>';
			echo'<div class="form-group">
				  <label for="jenis_kelamin">Jenis Kelamin</label>
					<select name="jenis_kelamin" id="jenis_kelamin" class="required form-control select2" title="Jenis Kelamin">
						<option value="">--Pilih Kelamin--</option>
						<option value="PRIA">PRIA</option>
						<option value="WANITA">WANITA</option>
					</select>
				</div>';
			echo'<div class="form-group">
				  <label for="tempat_lahir">Tempat Lahir</label>
				  <input type="text" id="tempat_lahir" name="tempat_lahir"  maxlength="35" class="required form-control" size="35" alt="Tempat Lahir" title="Tempat Lahir" placeholder="*Tempat Lahir"/>
				</div>';
			echo'<div class="form-group">
					<label>Tanggal Lahir</label>
					<div class="input-group date">
					  <div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					  </div>
					  <input name="tgl_lahir" type="text" class="required form-control pull-right settingDate" id="tgl_lahir" alt="Tanggal Lahir" title="Tanggal Lahir" value="'.date("Y-m-d").'" data-date-format="yyyy-mm-dd">
					</div>
					<!-- /.input group -->
				</div>';
			echo'<div class="form-group">
				  <label for="tlp">No Tlp</label>
				  <input type="text" id="tlp" name="tlp"  maxlength="35" onkeypress="return isNumberKey(event)" class="required form-control" size="35" alt="tlp" title="No Telpon" placeholder="*No Tlp"/>
				</div>';
			echo'<div class="form-group">
				  <input type="hidden" id="cek_email" name="cek_email" />
				  <label for="email">Email</label>
				  <input type="text" id="email" name="email"  maxlength="35" class="email form-control" size="35" alt="tlp" title="Email" placeholder="Email"/> <span id="pesan2"></span>
				</div>';
			echo'<div class="form-group">
				  <label for="alamat">Alamat Lengkap</label>
				  <textarea name="alamat" id="alamat" class="required form-control" title="Alamat Lengkap" placeholder="*Alamat Lengkap"></textarea>
				</div>';
			echo'</form>';
			echo'
			<div class="col-xs-12">
              <button type="button" id="btn_lanjut" class="btn-warga btn btn-success btn-block btn-flat" style="border:1px dotted black;" onclick="simpan_daftar_warga()">SIMPAN DATA</button>
            </div>
			';
		}
		
		function simpan_data_warga()
		{
			
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$nama = htmlentities($_POST['nama'], ENT_QUOTES, 'UTF-8');
			$jenis_kelamin = htmlentities($_POST['jenis_kelamin'], ENT_QUOTES, 'UTF-8');
			$tempat_lahir = htmlentities($_POST['tempat_lahir'], ENT_QUOTES, 'UTF-8');
			$tgl_lahir = htmlentities($_POST['tgl_lahir'], ENT_QUOTES, 'UTF-8');
			$tlp = htmlentities($_POST['tlp'], ENT_QUOTES, 'UTF-8');
			$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
			$alamat = htmlentities($_POST['alamat'], ENT_QUOTES, 'UTF-8');
			
			$this->M_penduduk->simpan
			(
				$nik
				,$nama
				,$jenis_kelamin
				,'' //,$status_menikah
				,$tempat_lahir
				,$tgl_lahir
				,$tlp
				,$email
				,$alamat
				,'ADMIN' //$from_db
				,'KABCJR'
			);
			echo'BERHASIL';
		}
		
        public function cek_login()
        {
            $user = htmlentities($_POST['user'], ENT_QUOTES, 'UTF-8');
            $pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');
            $data_login = $this->M_akun->get_login($user,md5($pass));
    		if(!empty($data_login))
    		{
                if ($data_login->avatar <> "")
                {
                    $src = $data_login->avatar_url;
                }
                else
                {
                	$src = base_url().'assets/global/users/loading.gif';
                }
				
				$user = array(
					'ses_user_admin'  => $user,
					'ses_pass_admin'  => base64_encode($pass),
					'ses_pass_admin_pure'  => ($pass),
					'ses_id_karyawan' => $data_login->id_karyawan,
					'ses_id_jabatan' => $data_login->id_jabatan,
					'ses_nama_jabatan' => $data_login->nama_jabatan,
					'ses_nama_karyawan' => $data_login->nama_karyawan,
					'ses_kode_kantor' => $data_login->kode_kantor,
					'ses_avatar_url' => $src,
					'ses_tgl_ins' => $data_login->tgl_ins
				);
				
    			
    
    			$this->session->set_userdata($user);
    			//redirect('index.php/admin','location');
				header('Location: '.base_url().'admin');
    		}
    		else
    		{
    			//redirect('index.php/login','location');
				header('Location: '.base_url().'admin-login');
    		}
        }
        
        public function validasi_input_captcha()
        {
            $this->form_validation->set_rules('captcha','Captcha','required|callback_check_captcha');
            return ($this->form_validation->run() == false)? False : true;
        }
        
        
        function logout()
		{
			$this->session->unset_userdata('ses_user_admin');
			$this->session->unset_userdata('ses_pass_admin');
			$this->session->unset_userdata('ses_id_karyawan');
            $this->session->unset_userdata('ses_id_jabatan');
			$this->session->unset_userdata('ses_nama_jabatan');
			$this->session->unset_userdata('ses_nama_karyawan');
			$this->session->unset_userdata('ses_avatar_url');
			$this->session->unset_userdata('ses_tgl_ins');
			
			//redirect('index.php/login','location');
			header('Location: '.base_url().'admin-login');
		}
        
        
        
        
        function auto_remove_captcha()
		{
			list($usec,$sec) = explode(" ",microtime());
			$now = ((float)$usec + (float)$sec);
			$expiration = 60;//10menit
			$captcha_dir = @opendir("./assets/captcha/");
			while($filename = @readdir($captcha_dir))
			{
				if($filename != "." and $filename != ".." and $filename != "index.php")
				{
					$name = str_replace(".jpg","",$filename);
					if($name+$expiration < $now)
					{
						@unlink("./assets/captcha/".$filename);
					}
				}
			}
			@closedir($captcha_dir);
			//redirect(base_url(),'localtion');
		}
        
        function check_captcha()
		{
			//batas waktu
			$expiration = time()-300;
			//hapus berkas cptcha yang kadaluarsa dalam direktori
			//hapus data captcah yang kadaluarsa pada database
			$this->db->query("DELETE FROM tb_captcha where captcha_time < ".$expiration);
			//$this->db->query("DELETE FROM tb_captcha");
			$sql = "select count(*) as count from tb_captcha where word = ? and ip_address = ? and captcha_time > ?";
			$binds = array($this->input->post('captcha'),$this->input->ip_address(),$expiration);
			
			$query = $this->db->query($sql,$binds);
			$row = $query->row();
			
			if($row->count == 0)
			{
				$this->form_validation->set_message('check_captcha','Captcha yang anda masukan salah atau sudah kadaluarsa');
				return false;
			}
			else
			{
				return true;
				
			}
		}
	}

?>