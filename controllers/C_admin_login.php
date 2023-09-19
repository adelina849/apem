<?php

	Class C_admin_login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(array('captcha','array','myHelper_helper'));
            $this->load->library(array('form_validation'));
            $this->config->load('cap');
			$this->load->model(array('M_dash','M_penduduk','M_pengajuan'));
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
				
									
				$query_list_layanan = "SELECT
							A.*
							,CASE WHEN (LENGTH(A.format_naskah) > 3) THEN 'SUDAH' ELSE 'BELUM' END AS SDH_FORMAT
							,COALESCE(B.tahapan,'') AS tahapan
							,COALESCE(C.nama_syarat,'') AS nama_syarat
							,COALESCE(D.nama_var,'') AS nama_var
							,
							COALESCE(
							(
								SELECT COUNT(id_pengajuan) AS CNT 
								FROM tb_pengajuan 
								WHERE sumber = '".$cek_nik->nik."'
								AND id_jenis_naskah = A.id_jenis_naskah
							) 
							,
							0
							)
							AS CNT
						FROM tb_jenis_naskah AS A
						LEFT JOIN
						(
							SELECT A2.id_jenis_naskah
								,A2.naskah	
								,GROUP_CONCAT(
												DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.tahapan)
												ORDER BY A2.ordr_index
												SEPARATOR ' <br/>'
											) AS tahapan
								,A2.kode_kantor
							FROM
							(
								SELECT 
									A.id_tahapan_naskah 
									,A.id_tahapan,A.id_jenis_naskah,A.ordr_index
									,COALESCE(B.nama_jenis_naskah,'') AS naskah
									,COALESCE(C.nama_tahapan,'') AS tahapan
									,A.kode_kantor
								FROM tb_tahapan_naskah AS A
								LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
								LEFT JOIN tb_tahapan AS C ON A.id_tahapan  = C.id_tahapan  AND A.kode_kantor = C.kode_kantor
								-- WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%%'
							) AS A2
							GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
						) AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
						
						LEFT JOIN
						(
							SELECT A2.id_jenis_naskah
								,A2.naskah	
								,GROUP_CONCAT(
												DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.nama_syarat)
												ORDER BY A2.ordr_index
												SEPARATOR ' <br/>'
											) AS nama_syarat
								,A2.kode_kantor
							FROM
							(
								SELECT 
									A.id_syarat_naskah 
									,A.id_syarat,A.id_jenis_naskah,A.ordr_index
									,COALESCE(B.nama_jenis_naskah,'') AS naskah
									,COALESCE(C.nama_syarat,'') AS nama_syarat
									,A.kode_kantor
								FROM tb_persyaratan_naskah AS A
								LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
								LEFT JOIN tb_persyaratan AS C ON A.id_syarat  = C.id_syarat  AND A.kode_kantor = C.kode_kantor
								-- WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%%'
							) AS A2
							GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
						) AS C ON A.id_jenis_naskah = C.id_jenis_naskah AND A.kode_kantor = C.kode_kantor
						
						
						LEFT JOIN
						(
							SELECT A2.id_jenis_naskah
								,A2.naskah	
								,GROUP_CONCAT(
												DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.nama_var)
												ORDER BY A2.ordr_index
												SEPARATOR ' <br/>'
											) AS nama_var
								,A2.kode_kantor
							FROM
							(
								SELECT 
									A.id_var_naskah 
									,A.id_jenis_naskah,A.idx AS ordr_index
									,COALESCE(B.nama_jenis_naskah,'') AS naskah
									,COALESCE(A.nama_var,'') AS nama_var
									,A.kode_kantor
								FROM tb_var_naskah AS A
								LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
								-- WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%%'
							) AS A2
							GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
						) AS D ON A.id_jenis_naskah = D.id_jenis_naskah AND A.kode_kantor = D.kode_kantor
						WHERE COALESCE(A.nama_jenis_naskah,'') LIKE '%%' ORDER BY A.nama_jenis_naskah ";
				
				$list_layanan = $this->M_dash->view_query_general($query_list_layanan);
				if(!empty($list_layanan))
				{
					echo'<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;">';
						echo'<thead>';
						echo'<tr>';
													echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">No</th>';
													echo '<th width="20%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">NAMA PELAYANAN</th>';
													echo '<th width="20%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">TAHAPAN</th>';
													echo '<th width="20%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">PERSYARATAN</th>';
													echo '<th width="30%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">DATA</th>';
													echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;border: 1px solid black;border-collapse: separate;">Aksi</th>';
						echo'</tr>';
						echo'</thead>';
						
						$list_result = $list_layanan->result();
						$no = 1;
						echo '<tbody>';
						foreach($list_result as $row)
						{
							echo'<tr>';
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">'.$no.'</td>';
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">
													
													'.$row->nama_jenis_naskah.'
													<!--
													<br/>
													<br/><b>Sudah Ada Format:</b>
													<br/>'.$row->SDH_FORMAT.'
													-->
												</td>';
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">'.$row->tahapan.'</td>';
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">'.$row->nama_syarat.'</td>';
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">'.$row->nama_var.'</td>';
								/*
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
								*/
								echo'<input type="hidden" id="id_jenis_naskah_'.$no.'" value="'.$row->id_jenis_naskah.'" />';
								echo'<input type="hidden" id="nama_jenis_naskah_'.$no.'" value="'.$row->nama_jenis_naskah.'" />';
								echo'<input type="hidden" id="syarat_jenis_naskah_'.$no.'" value="'.$row->syarat_jenis_naskah.'" />';
								echo'<input type="hidden" id="ket_jenis_naskah_'.$no.'" value="'.$row->ket_jenis_naskah.'" />';
								
								echo'<input type="hidden" id="no_pengajuan_'.$no.'" value="" />';
								
								echo'<td style="border-bottom: 1px solid grey;border-collapse: separate;color:black;">

<a href="javascript:void(0)" class="btn btn-success btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'" onclick="pilih_layanan(this)" title = "Ubah Data '.$row->nama_jenis_naskah.'" alt = "Ubah Data '.$row->nama_jenis_naskah.'">PILIH</a>

<a href="javascript:void(0)" class="btn btn-danger btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'" onclick="view_history_ajuan(this)" title = "Ubah Data '.$row->nama_jenis_naskah.'" alt = "Ubah Data '.$row->nama_jenis_naskah.'">LIHAT AJUAN ('.$row->CNT.')</a>
								
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
        
		function view_daftar_warga()
		{
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$query_cek_nik = "SELECT * FROM tb_penduduk WHERE nik = '".$nik."'";
			$cek_nik = $this->M_dash->view_query_general($query_cek_nik);
			if(!empty($cek_nik) && ($nik != ''))
			{
				$cek_nik = $cek_nik->row();
				$nik = $cek_nik->nik;
				$nama = $cek_nik->nama;
				$jenis_kelamin = $cek_nik->jenis_kelamin;
				$val_jenis_kelamin = $cek_nik->jenis_kelamin;
				$tempat_lahir = $cek_nik->tempat_lahir;
				$tgl_lahir = $cek_nik->tgl_lahir;
				$tlp = $cek_nik->tlp;
				$email = $cek_nik->email;
				$alamat = $cek_nik->alamat;
			}
			else
			{
				$nik = "";
				$nama = "";
				$jenis_kelamin = "--Pilih Kelamin--";
				$val_jenis_kelamin = "";
				$tempat_lahir = "";
				$tgl_lahir = date("Y-m-d");
				$tlp = "";
				$email = "";
				$alamat = "";
			}
			
			echo'<h2><center>FORM PENDAFTARAN</center></h2>';
			echo'<form role="form" class="frm-input" enctype="multipart/form-data">';
			echo'<div class="form-group">
				  <label for="nik">NIK</label>
				  <input type="text" id="nik" name="nik"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" onchange="cek_nik_pas_daftar()" value="'.$nik.'"/><span id="pesan"></span>
				</div>';
			echo'<div class="form-group">
				  <label for="nama">Nama Lengkap</label>
				  <input type="text" id="nama" name="nama"  maxlength="35" class="required form-control" size="35" alt="nama" title="Nama Lengkap" placeholder="*Nama" value="'.$nama.'"/>
				</div>';
			echo'<div class="form-group">
				  <label for="jenis_kelamin">Jenis Kelamin</label>
					<select name="jenis_kelamin" id="jenis_kelamin" class="required form-control select2" title="Jenis Kelamin">
						<option value="'.$val_jenis_kelamin.'">'.$jenis_kelamin.'</option>
						<option value="PRIA">PRIA</option>
						<option value="WANITA">WANITA</option>
					</select>
				</div>';
			echo'<div class="form-group">
				  <label for="tempat_lahir">Tempat Lahir</label>
				  <input type="text" id="tempat_lahir" name="tempat_lahir"  maxlength="35" class="required form-control" size="35" alt="Tempat Lahir" title="Tempat Lahir" placeholder="*Tempat Lahir" value="'.$tempat_lahir.'"/>
				</div>';
			echo'
				<div class="form-group">
					<label>Tanggal Lahir</label>
					<div class="input-group date">
					  <div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					  </div>
					  <input name="tgl_lahir" type="date" class="required form-control pull-right settingDate datemask_mysql" id="tgl_lahir" alt="Tanggal Lahir" title="Tanggal Lahir" value="'.$tgl_lahir.'" data-date-format="yyyy-mm-dd" onkeyup="">
					</div>
					<!-- /.input group -->
				</div>';
			echo'<div class="form-group">
				  <label for="tlp">No Tlp</label>
				  <input type="text" id="tlp" name="tlp"  maxlength="35" onkeypress="return isNumberKey(event)" class="required form-control" size="35" alt="tlp" title="No Telpon" placeholder="*No Tlp" value="'.$tlp.'"/>
				</div>';
			echo'<div class="form-group">
				  <input type="hidden" id="cek_email" name="cek_email" />
				  <label for="email">Email</label>
				  <input type="text" id="email" name="email"  maxlength="35" class="email form-control" size="35" alt="tlp" title="Email" placeholder="Email" value="'.$email.'"/> <span id="pesan2"></span>
				</div>';
			echo'<div class="form-group">
				  <label for="alamat">Alamat Lengkap</label>
				  <textarea name="alamat" id="alamat" class="required form-control" title="Alamat Lengkap" placeholder="*Alamat Lengkap">'.$alamat.'</textarea>
				</div>';
			echo'</form>';
			echo'
			<div class="col-xs-12">
              <button type="button" id="btn_simpan_data_warga" class="btn-warga btn btn-success btn-block btn-flat" style="border:1px dotted black;" onclick="simpan_daftar_warga()">SIMPAN DATA</button>
            </div>
			';
		}
		
		function simpan_data_warga_test()
		{
			$tgl_lahir = htmlentities($_POST['tgl_lahir'], ENT_QUOTES, 'UTF-8');
			echo $tgl_lahir;
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
			
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$query_cek_nik = "SELECT * FROM tb_penduduk WHERE nik = '".$nik."'";
			$cek_nik = $this->M_dash->view_query_general($query_cek_nik);
			if(!empty($cek_nik))
			{
				$cek_nik = $cek_nik->row();
				$this->M_penduduk->edit
				(
					$cek_nik->id_penduduk
					,$nik
					,$nama
					,$jenis_kelamin
					,'' //,$status_menikah
					,$tempat_lahir
					,$tgl_lahir
					,$tlp
					,$email
					,$alamat
					,'KABCJR'
				);
			}
			else
			{
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
			}
			
			echo'BERHASIL';
		}
		
        public function cek_login()
        {
            $user = htmlentities($_POST['user'], ENT_QUOTES, 'UTF-8');
            $pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');
			$check = isset($_POST['chk_saya_bukan_robot']) ? "checked" : "unchecked";
			
			if($check == 'checked')
			{
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
			else
			{
				header('Location: '.base_url().'admin-login?from=cek');
			}
        }
        
		function view_pengajuan()
		{
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			
			
			
			//if((!empty( htmlentities($_POST['no_pengajuan'], ENT_QUOTES, 'UTF-8') )) && ( htmlentities($_POST['no_pengajuan'], ENT_QUOTES, 'UTF-8') != "")  )
			if((!empty($_POST['no_pengajuan'])) && ($_POST['no_pengajuan']!= "")  )
			{
				$no_pengajuan = htmlentities($_POST['no_pengajuan'], ENT_QUOTES, 'UTF-8');
			}
			else
			{
				$no_pengajuan = "";
			}
			
			$query_cek_nik = "SELECT * FROM tb_penduduk WHERE nik = '".$nik."'";
			$cek_nik = $this->M_dash->view_query_general($query_cek_nik);
			if(!empty($cek_nik))
			{
				$cek_nik = $cek_nik->row();
				
				$query_cek_jenis_naskah = "SELECT * FROM tb_jenis_naskah WHERE id_jenis_naskah = '".$id_jenis_naskah."'";
				$cek_jenis_naskah = $this->M_dash->view_query_general($query_cek_jenis_naskah);
				if(!empty($cek_jenis_naskah))
				{
					$cek_jenis_naskah = $cek_jenis_naskah->row();
					
					$query_cek_apakah_sudah_ada_edit = "SELECT * FROM tb_pengajuan WHERE no_pengajuan = '".$no_pengajuan."';";
					$cek_apakah_sudah_ada_edit = $this->M_dash->view_query_general($query_cek_apakah_sudah_ada_edit);
					if(!empty($cek_apakah_sudah_ada_edit))
					{
						$cek_apakah_sudah_ada_edit = $cek_apakah_sudah_ada_edit->row();
						$kode_pengajuan = $cek_apakah_sudah_ada_edit->kode_pengajuan;
						$perihal = $cek_apakah_sudah_ada_edit->perihal;
						$diajukan_oleh = $cek_apakah_sudah_ada_edit->diajukan_oleh;
						$tandatangan_oleh = $cek_apakah_sudah_ada_edit->tandatangan_oleh;
						
						$tgl_surat_dibuat = $cek_apakah_sudah_ada_edit->tgl_surat_dibuat;
						$tgl_surat_dibuat_untuk_isian = $cek_apakah_sudah_ada_edit->tgl_surat_dibuat;
						
						$tgl_surat_masuk = $cek_apakah_sudah_ada_edit->tgl_surat_masuk;
						$ket_pengajuan = $cek_apakah_sudah_ada_edit->ket_pengajuan;
						$penting = $cek_apakah_sudah_ada_edit->penting;
					}
					else
					{
						$kode_pengajuan = ""; 
						$perihal = ""; 
						$diajukan_oleh = ""; 
						$tandatangan_oleh = ""; 
						
						$tgl_surat_dibuat = date("Y-m-d"); 
						$tgl_surat_dibuat_untuk_isian = "";
						
						$tgl_surat_masuk = date("Y-m-d"); 
						$ket_pengajuan = ""; 
						$penting = ""; 
					}
						
					
					echo'<h2><center style="color:black;">FORM PENGAJUAN '.strtoupper($cek_jenis_naskah->nama_jenis_naskah).'</center></h2>';
					echo'<form role="form" class="frm-input" enctype="multipart/form-data">';
					
					echo'<input type="hidden" id="no_pengajuan" name="no_pengajuan"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" value="'.$no_pengajuan.'" readonly />';
					
					echo'<input type="hidden" id="nama_jenis_naskah" name="nama_jenis_naskah"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" value="'.strtoupper($cek_jenis_naskah->nama_jenis_naskah).'" readonly />';
					
					echo'<input type="hidden" id="id_jenis_naskah" name="id_jenis_naskah"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" value="'.$cek_jenis_naskah->id_jenis_naskah.'" readonly />';
					
					echo'<div class="form-group">
						  <label for="sumber">NIK</label>
						  <input type="text" id="sumber" name="sumber"  maxlength="35" class="required form-control" size="35" alt="NIK" title="NIK" placeholder="*NIK" value="'.$nik.'" readonly />
						</div>';
					echo'<div class="form-group">
						  <label for="nama_pengaju">NAMA AKUN PEMBUAT</label>
						  <input type="text" id="nama_pengaju" name="nama_pengaju"  maxlength="35" class="required form-control" size="35" alt="NAMA AKUN PEMBUAT" title="NAMA AKUN PEMBUAT" placeholder="*NAMA AKUN PEMBUAT"  value="'.$cek_nik->nama.'" readonly />
						</div>';
						
					echo'<div class="form-group">
						  <label for="kode_pengajuan">No Surat Pengantar (Jika Ada)</label>
						  <input type="text" id="kode_pengajuan" name="kode_pengajuan"  maxlength="35" class="required form-control" size="35" alt="No Surat Pengantar" title="No Surat Pengantar" placeholder="*No Surat Pengantar" value="'.$kode_pengajuan.'"/>
						</div>';
						
					echo'<div class="form-group">
						  <label for="perihal">Perihal</label>
						  <input type="text" id="perihal" name="perihal"  maxlength="35" class="required form-control" size="35" alt="Perihal" title="Perihal" placeholder="*Perihal" value="'.$perihal.'"/>
						</div>';
						
					echo'<div class="form-group" style="display:none;">
							<label for="diajukan_oleh_dodol">Diajukan Oleh (Nama Pemohon Jika Pemohon dan Pengisi Beda)</label>
							<input type="text" id="diajukan_oleh_dodol" name="diajukan_oleh_dodol"  maxlength="35" class="required form-control" size="35" alt="Diajukan Oleh" title="Diajukan Oleh" placeholder="*Diajukan Oleh" value="'.$cek_nik->nama.'"/>
						</div>';
					
					echo'<div class="form-group" style="display:none;">
						  <label for="tandatangan_oleh">Ditanda tangani oleh</label>
							<select name="tandatangan_oleh" id="tandatangan_oleh" class="required form-control select2" title="Tingkat Kepentingan">
								<option value="'.$tandatangan_oleh.'">'.$tandatangan_oleh.'</option>
								<option value="CAMAT">CAMAT</option>
								<option value="SEKMAT">SEKMAT</option>
								<option value="KASI">KASI</option>
							</select>
						</div>';
						
					echo'<div class="form-group">
							<label>Tanggal Pelayanan/Dokumen dibutuhkan</label>
							<div class="input-group date">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input name="tgl_surat_dibuat" type="date" class="required form-control pull-right settingDate" id="tgl_surat_dibuat" alt="Tanggal Dokumen Dibuat" title="Tanggal Dokumen Dibuat" value="'.$tgl_surat_dibuat.'" data-date-format="yyyy-mm-dd">
							</div>
							<!-- /.input group -->
						</div>';
						
					echo'<div class="form-group" style="display:none;">
							<label>Tanggal Dokumen Masuk</label>
							<div class="input-group date">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input name="tgl_surat_masuk" type="text" class="required form-control pull-right settingDate" id="tgl_surat_masuk" alt="Tanggal Dokumen Masuk" title="Tanggal Dokumen Masuk" value="'.$tgl_surat_masuk.'" data-date-format="yyyy-mm-dd">
							</div>
							<!-- /.input group -->
						</div>';
						
					echo'<div class="form-group">
							<label for="ket_pengajuan">Keterangan</label>
							<textarea name="ket_pengajuan" id="ket_pengajuan" class="required form-control" title="Keterangan" placeholder="*Keterangan">'.$ket_pengajuan.'</textarea>
						</div>';
						
					echo'<div class="form-group" style="display:none;">
						  <label for="penting">Tingkat Kepentingan</label>
							<select name="penting" id="penting" class="required form-control select2" title="Tingkat Kepentingan">
								<option value="'.$penting.'">'.$penting.'</option>
								<option value="Kurang Penting">Kurang Penting</option>
								<option value="Penting">Penting</option>
								<option value="Sangat Penting">Sangat Penting</option>
								<option value="Diutamakan">Diutamakan</option>
							</select>
						</div>';
					echo'</form>';
					
					
					//VIEW ISIAN DATA
					$query_data = "
								SELECT A.*,COALESCE(B.isi,'') AS isi
								FROM tb_var_naskah AS A
								LEFT JOIN tb_isi_var_naskah AS B 
									ON A.id_var_naskah = B.id_var_naskah 
									AND B.id_jenis_naskah = '".$cek_jenis_naskah->id_jenis_naskah."'
									AND B.id_pengajuan = '".$cek_jenis_naskah->id_jenis_naskah."-".$nik."-".$tgl_surat_dibuat_untuk_isian."'
								WHERE A.id_jenis_naskah = '".$cek_jenis_naskah->id_jenis_naskah."' 
								";
					$list_data = $this->M_dash->view_query_general($query_data);
					if(!empty($list_data))
					{	
							$list_result = $list_data->result();
							
							foreach($list_result as $row)
							{
								if($row->tipe=="NUMBER")
								{
									echo'<div class="form-group">
										<label for="var-'.$row->id_var_naskah.'">'.$row->nama_var.'</label>
										<input type="text" id="var-'.$row->id_var_naskah.'" name="var-'.$row->id_var_naskah.'"  maxlength="35" class="required form-control" size="35" alt="'.$row->ket.'" title="'.$row->ket.'" placeholder="*'.$row->ket.'" onkeypress="return isNumberKey(event)" value="'.$row->isi.'" onchange="simpan_isian_var_naskah(this)"/>
									</div>';
								}
								elseif($row->tipe=="TANGGAL")
								{
									echo'<div class="form-group">
										<label>'.$row->nama_var.'</label>
										<div class="input-group date">
										  <div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										  </div>
										  <input name="var-'.$row->id_var_naskah.'" type="date" class="required form-control pull-right settingDate" id="var-'.$row->id_var_naskah.'" alt="'.$row->ket.'" title="'.$row->ket.'" onchange="simpan_isian_var_naskah(this)" value="'.$row->isi.'" data-date-format="yyyy-mm-dd">
										</div>
										<!-- /.input group -->
									</div>';
								}
								else
								{
									echo'<div class="form-group">
										<label for="var-'.$row->id_var_naskah.'">'.$row->nama_var.'</label>
										<input type="text" id="var-'.$row->id_var_naskah.'" name="var-'.$row->id_var_naskah.'"  maxlength="35" class="required form-control" size="35" alt="'.$row->ket.'" title="'.$row->ket.'" placeholder="*'.$row->ket.'" value="'.$row->isi.'" onchange="simpan_isian_var_naskah(this)"/>
									</div>';
								}
							}
					}
					//VIEW ISIAN DATA
					
					echo'<hr/>';
					echo'<h2><center style="color:black;">PERSYARATAN '.strtoupper($cek_jenis_naskah->nama_jenis_naskah).'</center></h2>';
					
					/*
					SELECT A.*,COALESCE(B.isi,'') AS isi
								FROM tb_var_naskah AS A
								LEFT JOIN tb_isi_var_naskah AS B 
									ON A.id_var_naskah = B.id_var_naskah 
									AND B.id_jenis_naskah = '".$cek_jenis_naskah->id_jenis_naskah."'
									AND B.id_pengajuan = '".$cek_jenis_naskah->id_jenis_naskah."-".$nik."-".$tgl_surat_dibuat_untuk_isian."'
								WHERE A.id_jenis_naskah = '".$cek_jenis_naskah->id_jenis_naskah."' 
								
								
								$cek_apakah_sudah_ada_edit = $cek_apakah_sudah_ada_edit->row();
						$kode_pengajuan = $cek_apakah_sudah_ada_edit->kode_pengajuan;
						$perihal = $cek_apakah_sudah_ada_edit->perihal;
						$diajukan_oleh = $cek_apakah_sudah_ada_edit->diajukan_oleh;
						$tandatangan_oleh = $cek_apakah_sudah_ada_edit->tandatangan_oleh;
						
						$tgl_surat_dibuat = $cek_apakah_sudah_ada_edit->tgl_surat_dibuat;
						$tgl_surat_dibuat_untuk_isian = $cek_apakah_sudah_ada_edit->tgl_surat_dibuat;
						
						$tgl_surat_masuk = $cek_apakah_sudah_ada_edit->tgl_surat_masuk;
						$ket_pengajuan = $cek_apakah_sudah_ada_edit->ket_pengajuan;
						$penting = $cek_apakah_sudah_ada_edit->penting;
						
								*/
					
					//VIEW ISIAN SYARAT
					$query_persyaratan = "
									
									SELECT 
										A.*
										,COALESCE(B.nama_syarat,'') AS nama_syarat
										,COALESCE(B.ket_syarat,'') AS ket_syarat
										,COALESCE(C.isi,'') AS isi
										,COALESCE(C.id_pengajuan,'') AS id_pengajuan_format_isi_syarat_naskah
									FROM tb_persyaratan_naskah AS A 
									INNER JOIN tb_persyaratan AS B 
									ON A.id_syarat = B.id_syarat AND A.kode_kantor = B.kode_kantor
									LEFT JOIN tb_isi_syarat_naskah AS C ON A.id_syarat_naskah = C.id_syarat_naskah AND A.id_jenis_naskah = C.id_jenis_naskah AND C.id_pengajuan = '".$id_jenis_naskah."-".$nik."-".$tgl_surat_dibuat_untuk_isian."' 
									WHERE A.id_jenis_naskah = '".$cek_jenis_naskah->id_jenis_naskah."' 
									";
					$list_syarat = $this->M_dash->view_query_general($query_persyaratan);
					if(!empty($list_syarat))
					{
						echo'<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;border-collapse: separate;">';
							echo'<thead>';
							echo'<tr>';
														echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;border:1px solid black;border-collapse: separate;">NO</th>';
														
														
														echo '<th width="30%" style="background-color:red;color:white;font-weight:bold;border:1px solid black;border-collapse: separate;">NAMA PERSYARATAN</th>';
														
														
														echo '<th width="30%" style="background-color:red;color:white;font-weight:bold;border:1px solid black;border-collapse: separate;">KETERANGAN</th>';
														
														echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;border:1px solid black;border-collapse: separate;">ADA</th>';
														
							echo'</tr>';
							echo'</thead>';
							
							$list_result = $list_syarat->result();
							$no = 1;
							echo '<tbody>';
							foreach($list_result as $row)
							{
								echo'<tr>';
									echo'<td style="border:1px solid black;border-collapse: separate;">'.$no.'</td>';
									echo'<td style="border:1px solid black;border-collapse: separate;">'.$row->nama_syarat.'</td>';
									echo'<td style="border:1px solid black;border-collapse: separate;">'.$row->ket_syarat.'</td>';
									
									if($row->isi == '1')
									{
										echo '<td style="border:1px solid black;border-collapse: separate;">
											<div class="form-group">
											<label>
											  <input type="checkbox" id="isAktif-'.$no.'" name="isAktif-'.$no.'" class="flat-red" onchange="simpan_isi_syarat_naskah('.$no.')" checked>
											</label>
											</div>
										</td>';
									}
									else
									{
										echo '<td style="border:1px solid black;border-collapse: separate;">
											<div class="form-group">
											<label>
											  <input type="checkbox"  id="isAktif-'.$no.'" name="isAktif-'.$no.'" class="flat-red" onchange="simpan_isi_syarat_naskah('.$no.')">
											</label>
											</div>
										</td>';
									}
									
									echo'<input type="hidden" id="id_pengajuan_for_isi_syarat_naskah-'.$no.'" value="'.$row->id_pengajuan_format_isi_syarat_naskah.'" />';
									
									echo'<input type="hidden" id="id_pengajuan_format_for_isi_syarat_naskah-'.$no.'" value="'.$id_jenis_naskah.'-'.$nik.'-'.$tgl_surat_dibuat_untuk_isian.'" />';
									
									
									
									
									echo'<input type="hidden" id="id_jenis_naskah_for_isi_syarat_naskah-'.$no.'" value="'.$row->id_jenis_naskah.'" />';
									echo'<input type="hidden" id="id_syarat_naskah_for_isi_syarat_naskah-'.$no.'" value="'.$row->id_syarat_naskah.'" />';
									
									/*
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
									*/
									
								echo'</tr>';
								$no++;
							}
							echo '</tbody>';
						echo'</table>';
					}
					else
					{
						echo'<center>TIDAK ADA PERSYARATAN YANG TERLAMPIR</center>';
					}
					//VIEW ISIAN SYARAT
					
					echo'
						<br/>
						<div class="col-xs-12">
						  <button type="button" id="btn_simpan_data_pengajuan-'.$cek_jenis_naskah->id_jenis_naskah.'-1" class="btn-warga btn btn-success btn-block btn-flat btn_simpan_data_pengajuan" style="border:1px dotted black;" onclick="simpan_pengajuan(this)">SIMPAN DATA</button>
						</div>
						';
					
					
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		function simpan_pengajuan_pelayanan()
		{
			if((!empty($_POST['no_pengajuan'])) && ($_POST['no_pengajuan']!= "")  )
			{
				$cek_terakhir = "SELECT * FROM tb_pengajuan WHERE sumber = '".$_POST['sumber']."' AND id_jenis_naskah = '".$_POST['id_jenis_naskah']."' AND tgl_surat_dibuat = '".$_POST['tgl_surat_dibuat']."' ORDER BY id_pengajuan DESC LIMIT 0,1; ";
				$data_pengajuan = $this->M_dash->view_query_general($cek_terakhir);
				if(!empty($data_pengajuan))
				{
					$data_pengajuan = $data_pengajuan->row();
					$this->M_pengajuan->edit
								(
									$data_pengajuan->id_pengajuan
									,$_POST['id_jenis_naskah']
									,$_POST['no_pengajuan']
									,$_POST['kode_pengajuan']
									,$_POST['diajukan_oleh']
									,$_POST['perihal']
									,$_POST['sumber']
									,$_POST['tandatangan_oleh']
									,$_POST['tgl_surat_dibuat']
									,$_POST['tgl_surat_masuk']
									,$_POST['ket_pengajuan']
									,$_POST['penting']
									,$this->session->userdata('ses_id_karyawan')
								
								);
					echo $_POST['no_pengajuan'];
				}
				else
				{
					return false;
				}
			}
			else
			{
			
				$this->M_pengajuan->simpan
				(
					$_POST['id_jenis_naskah']
					,$_POST['kode_pengajuan']
					,$_POST['diajukan_oleh']
					,$_POST['perihal']
					,$_POST['sumber']
					,$_POST['tandatangan_oleh']
					,$_POST['tgl_surat_dibuat']
					,$_POST['tgl_surat_masuk']
					,$_POST['ket_pengajuan']
					,$_POST['penting']
					,'' //$this->session->userdata('ses_id_karyawan')
					,'KABCJR' //$this->session->userdata('ses_kode_kantor')
					,'KAB'
				);
				
				//$data_pengajuan = $this->M_pengajuan->get_pengajuan('A.kode_pengajuan',$_POST['kode_pengajuan'])	;
				//$data_pengajuan = $this->M_pengajuan->get_pengajuan('A.kode_pengajuan',$_POST['kode_pengajuan'])	;
				
				
				//BUAT QR CODE
				$cek_terakhir = "SELECT * FROM tb_pengajuan WHERE sumber = '".$_POST['sumber']."' AND id_jenis_naskah = '".$_POST['id_jenis_naskah']."' ORDER BY id_pengajuan DESC LIMIT 0,1; ";
				$data_pengajuan = $this->M_dash->view_query_general($cek_terakhir);
				if(!empty($data_pengajuan))
				{
					$data_pengajuan = $data_pengajuan->row();
					//GENERATE QR CODE
						$this->load->library('ciqrcode'); //pemanggilan library QR CODE
						$config['cacheable']    = true; //boolean, the default is true
						$config['cachedir']     = './assets/'; //string, the default is application/cache/
						$config['errorlog']     = './assets/'; //string, the default is application/logs/
						$config['imagedir']     = './assets/global/images/qrcode/'; //direktori penyimpanan qr code
						$config['quality']      = true; //boolean, the default is true
						$config['size']         = '1024'; //interger, the default is 1024
						$config['black']        = array(224,255,255); // array, default is array(255,255,255)
						$config['white']        = array(70,130,180); // array, default is array(0,0,0)
						$this->ciqrcode->initialize($config);
				 
						$image_name=$data_pengajuan->no_pengajuan.'.png'; //buat name dari qr code sesuai dengan nim
				 
						$params['data'] = $data_pengajuan->no_pengajuan; //data yang akan di jadikan QR CODE
						$params['level'] = 'H'; //H=High
						$params['size'] = 10;
						$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
						$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
					//GENERATE QR CODE
				}
				//BUAT QR CODE
				
				//SIMPAN DATA VARIABLE NASKAH
				//SIMPAN DATA VARIABLE NASKAH
				
				echo $data_pengajuan->no_pengajuan;
				
				$query_penduduk = "SELECT * FROM tb_penduduk WHERE nik = '".$_POST['sumber']."';";
				$get_data_penduduk = $this->M_dash->view_query_general($query_penduduk);
				if(!empty($get_data_penduduk))
				{
					$get_data_penduduk = $get_data_penduduk->row();
						//KIRIM EMAIL
						$this->load->config('email');
						$this->load->library('email');
						
						$pesan = "
							<html>
							   <head>
								 <title>Permintaan Pelayanan Diterima</title>
							   </head>
							   <body>
								 <p>Assalamualaikum Wr,Wb,</p>
								 <p>Hi ".$_POST['diajukan_oleh']." Terima kasih telah melakukan penaftaran pelayanan Pada Aplikasi pelayanan <b>ANJUNGAN PATEN MANDIRI (APEM) KECAMATAN CIBEBER</b>. Berikut kami sampaikan informasi pendaftara anda :</p>
								 
								  <table border='0'>
									  <tbody>
										<tr>
											<td>No Registrasi</td>
											<td>:</td>
											<td>".$data_pengajuan->no_pengajuan."</td>
										</tr>
										<tr>
											<td>Jenis Pelayanan</td>
											<td>:</td>
											<td>".$_POST['nama_jenis_naskah']."</td>
										</tr>
										<tr>
											<td>Tanggal Pengajuan</td>
											<td>:</td>
											<td>".$_POST['tgl_surat_dibuat']."</td>
										</tr>
										<tr>
											<td>Perihal</td>
											<td>:</td>
											<td>".$_POST['perihal']."</td>
										</tr>
									  </tbody>
								 </table>

								 <p>Anda bisa melakukan pengecekan dengan menggunakan fasilitas QR Code yang tertera pada bukti terima.<br></p>

								 <p>Hormat kami, 
									<br>
									<br>
									<center>
									Petugas
									<b>ANJUNGAN PATEN MANDIRI (APEM) KECAMATAN CIBEBER</b>
									</center>
								 </p>
							   </body>
							 </html>
						";

						$from = $this->config->item('smtp_user');
						//$to = $email;
						$to = $get_data_penduduk->email;
						$subject = 'Pendaftaran  akun Megafire Berhasil';
						$message = $pesan;

						$this->email->set_newline("\r\n");
						$this->email->from($from);
						$this->email->to($to);
						$this->email->subject($subject);
						$this->email->message($message);

						if ($this->email->send()) 
						{
							// echo 'Your Email has successfully been sent.';
						} else 
						{
							show_error($this->email->print_debugger());
						} 
				}
				
				//KIRIM EMAIL
			}
		}
		
		function view_history_pengajuan()
		{
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			
			$query = "
					SELECT * FROM tb_pengajuan AS A 
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah
					WHERE A.sumber = '".$nik."' AND A.id_jenis_naskah = '".$id_jenis_naskah."' ORDER BY A.tgl_ins DESC;";
			
			$cek_data_pengajuan = $this->M_dash->view_query_general($query);
			if(!empty($cek_data_pengajuan))
			{
				echo'<table width="100%" id="example2" class="table table-hover hoverTable" style="opacity:1;">';
					echo'<thead>';
					echo'<tr>';
												echo '<th width="5%" style="background-color:red;color:white;font-weight:bold;">No</th>';
												echo '<th width="35%" style="background-color:red;color:white;font-weight:bold;">QRCODE</th>';
												echo '<th width="45%" style="background-color:red;color:white;font-weight:bold;">PELAYANAN</th>';
												echo '<th width="15%" style="background-color:red;color:white;font-weight:bold;">Aksi</th>';
					echo'</tr>';
					echo'</thead>';
					
					$list_result = $cek_data_pengajuan->result();
					$no = 1;
					echo '<tbody>';
					foreach($list_result as $row)
					{
						echo'<tr>';
							echo'<td>'.$no.'</td>';
							
							if(file_exists("assets/global/images/qrcode/".$row->no_pengajuan.".png"))
							{
								echo'<td >
										<center>
											<img id="imgQr"  width="100px" height="100px" src="'.base_url().'assets/global/images/qrcode/'.$row->no_pengajuan.'.png" />
											<br/>
											'.($row->no_pengajuan).'
										</center>
									</td>';
							}
							else
							{
								echo'<td><center>'.($row->no_pengajuan).'</center></td>';
							}
							
							
							if($row->hasil_pengajuan == '')
							{
								$format = "display:none;";
								$format_dok = "display:none;";
							}
							else
							{
								$format = "color:black;";
								$format_dok = "";
							}
							
							echo'<td>
								<b>Jenis Dokumen : </b>'.$row->nama_jenis_naskah.' 
								<br/> <b>No Dok : </b>'.$row->kode_pengajuan.' 
								<br/> <b>Pemohon : </b>'.$row->diajukan_oleh.' 
								<br/> <b>Perihal : </b>'.$row->perihal.'
								<br/> <b>Tgl Masuk : </b>'.$row->tgl_surat_masuk.'
								
								<br/>
								<br/>
								<div style="padding:2%;border:1px dotted black;'.$format.'">
								<b>Hasil : </b>'.$row->hasil_pengajuan.'
								<br/> <b>Ket Hasil : </b>'.$row->ket_hasil.'
								</div>
								<a href="javascript:void(0)" class="btn btn-default btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'-cetakdok" title = "Cetak Data '.$row->nama_jenis_naskah.'" alt = "Cetak Data '.$row->nama_jenis_naskah.'" onclick="cetak_dok(this)" style="'.$format_dok.'"> <span class="glyphicon glyphicon-print " style="padding-top:1.5%;"></span> CETAK DOKUMEN</a>
							</td>';
						
							echo'<input type="hidden" id="id_pengajuan_'.$no.'" value="'.$row->id_pengajuan.'" />';
							echo'<input type="hidden" id="id_jenis_naskah_'.$no.'" value="'.$row->id_jenis_naskah.'" />';
							echo'<input type="hidden" id="sumber_'.$no.'" value="'.$row->sumber.'" />';
							echo'<input type="hidden" id="no_pengajuan_'.$no.'" value="'.$row->no_pengajuan.'" />';
							
							echo'<input type="hidden" id="nama_jenis_naskah_'.$no.'" value="'.$row->nama_jenis_naskah.'" />';
							echo'<input type="hidden" id="syarat_jenis_naskah_'.$no.'" value="'.$row->syarat_jenis_naskah.'" />';
							
							echo'<input type="hidden" id="tgl_surat_masuk_'.$no.'" value="'.$row->tgl_surat_masuk.'" />';
							
							echo'<input type="hidden" id="ket_jenis_naskah_'.$no.'" value="'.$row->ket_jenis_naskah.'" />';
							
							echo'<td>

<a href="javascript:void(0)" class="btn btn-default btn-sm btn-flat btn-block" id="btnfromubah-'.$row->id_jenis_naskah.'-'.$no.'-ubah" onclick="pilih_layanan(this)" title = "Ubah Data '.$row->nama_jenis_naskah.'" alt = "Ubah Data '.$row->nama_jenis_naskah.'"> <span class="glyphicon glyphicon-edit " style="padding-top:1.5%;"></span> UBAH</a>

<a href="javascript:void(0)" class="btn btn-default btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'-hapus" onclick="hapus_pengajuan(this)" title = "Hapus Data '.$row->nama_jenis_naskah.'" alt = "Hapus Data '.$row->nama_jenis_naskah.'"> <span class="glyphicon glyphicon-trash " style="padding-top:1.5%;"></span>HAPUS</a>

<a href="'.base_url().'admin-cetak-faktur?pengajuan='.$row->no_pengajuan.'" class="btn btn-default btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'-qr" title = "Cetak Data '.$row->nama_jenis_naskah.'" alt = "Cetak Data '.$row->nama_jenis_naskah.'"> <span class="glyphicon glyphicon-print " style="padding-top:1.5%;"></span> CETAK QR</a>

<a href="javascript:void(0)" class="btn btn-default btn-sm btn-flat btn-block" id="btn-'.$row->id_jenis_naskah.'-'.$no.'-progress" title = "Cetak Data '.$row->nama_jenis_naskah.'" alt = "Cetak Data '.$row->nama_jenis_naskah.'" onclick="cek_progress(this)"> <span class="glyphicon glyphicon-hourglass " style="padding-top:1.5%;"></span> PROGRES</a>




							
							</td>';
							
							
						echo'</tr>';
						$no++;
					}
					echo '</tbody>';
				echo'</table>';
				
			}
			else
			{
				echo'TIDAK ADA DATA YANG DITAMPILKAN';
			}
			
		}
		
		
		function hapus_pengajuan()
		{
			$id_pengajuan = htmlentities($_POST['id_pengajuan'], ENT_QUOTES, 'UTF-8');
			$this->M_pengajuan->hapus($id_pengajuan);
			
			//Hapus Images
				$this->load->model('M_images');
				$list_images = $this->M_images->get_images($id_pengajuan,'pengajuan','id',$id_pengajuan);
				if(!empty($list_images))
				{
					$list_result = $list_images->result();
					foreach($list_result as $row)
					{
						$this->M_images->do_upload('',$row->img_file);
					}
				}
			//Hapus Images
			
			//header('Location: '.base_url().'admin-pengajuan-dokumen');
			echo'BERHASIL';
		}
	
		function simpan_isi_var_naskah()
		{
			$id_pengajuan = htmlentities($_POST['id_pengajuan'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			$id_var_naskah = htmlentities($_POST['id_var_naskah'], ENT_QUOTES, 'UTF-8');
			$isi = htmlentities($_POST['isi'], ENT_QUOTES, 'UTF-8');
			
			$query = "
					SELECT * FROM tb_isi_var_naskah 
					WHERE id_pengajuan = '".$id_pengajuan."'
					AND id_jenis_naskah = '".$id_jenis_naskah."'
					AND id_var_naskah = '".$id_var_naskah."'
					";
			$cek_isi_var_naskah = $this->M_dash->view_query_general($query);
			if(!empty($cek_isi_var_naskah))
			{
				//EDIT
				$cek_isi_var_naskah = $cek_isi_var_naskah->row();
				$query_edit = "
								UPDATE tb_isi_var_naskah SET isi = '".$isi."'  
								WHERE id_pengajuan = '".$id_pengajuan."'
								AND id_jenis_naskah = '".$id_jenis_naskah."'
								AND id_var_naskah = '".$id_var_naskah."'
								";
				$this->M_dash->exec_query_general($query_edit);
			}
			else
			{
				//SIMPAN
				$query_simpan = "INSERT INTO tb_isi_var_naskah (id_pengajuan,id_jenis_naskah,id_var_naskah,isi,tgl_ins)
				VALUES
				('".$id_pengajuan."','".$id_jenis_naskah."','".$id_var_naskah."','".$isi."',NOW());
				";
				$this->M_dash->exec_query_general($query_simpan);
			}
			
			echo'BERHASIL';
			
		}
		
		function simpan_isi_syarat_naskah()
		{
			$id_pengajuan = htmlentities($_POST['id_pengajuan'], ENT_QUOTES, 'UTF-8');
			$id_pengajuan_format_for_isi_syarat_naskah = htmlentities($_POST['id_pengajuan_format_for_isi_syarat_naskah'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			$id_syarat_naskah = htmlentities($_POST['id_syarat_naskah'], ENT_QUOTES, 'UTF-8');
			
			$query_get_info_isi_syarat_naskah = "
											SELECT * 
											FROM tb_isi_syarat_naskah 
											WHERE id_pengajuan = '".$id_pengajuan_format_for_isi_syarat_naskah."' 
											AND id_jenis_naskah = '".$id_jenis_naskah."' 
											AND id_syarat_naskah = '".$id_syarat_naskah."' ;";
			$get_info_isi_syarat_naskah = $this->M_dash->view_query_general($query_get_info_isi_syarat_naskah);
			if(!empty($get_info_isi_syarat_naskah))
			{
				//HAPUS
				$query_delete = "
								DELETE
								FROM tb_isi_syarat_naskah 
								WHERE id_pengajuan = '".$id_pengajuan_format_for_isi_syarat_naskah."' 
								AND id_jenis_naskah = '".$id_jenis_naskah."' 
								AND id_syarat_naskah = '".$id_syarat_naskah."' ;
				";
				$this->M_dash->exec_query_general($query_delete);
				
				echo'BERHASIL';
			}
			else
			{
				//SIMPAN
				$query_delete = "
								INSERT INTO tb_isi_syarat_naskah
								(id_pengajuan,id_jenis_naskah,id_syarat_naskah,isi,tgl_ins,kode_kantor)
								VALUES
								('".$id_pengajuan_format_for_isi_syarat_naskah."','".$id_jenis_naskah."','".$id_syarat_naskah."',1,NOW(),'KABCJR')
								";
								
				$this->M_dash->exec_query_general($query_delete);
				
				echo'BERHASIL';
			}
		}
	
		function view_download_dok()
		{
			$id_pengajuan = htmlentities($_GET['id_pengajuan'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_GET['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			
			//GET PENGAJUAN
			$query_get_pengajuan = "SELECT * FROM tb_pengajuan WHERE id_pengajuan = '".$id_pengajuan."' ;";
			$get_pengajuan = $this->M_dash->view_query_general($query_get_pengajuan);
			if(!empty($get_pengajuan))
			{
				$get_pengajuan = $get_pengajuan->row();
				
				//GET PENDUDUK
				$query_get_data_penduduk = "SELECT * FROM tb_penduduk WHERE nik = '".$get_pengajuan->sumber."'";
				$get_data_penduduk = $this->M_dash->view_query_general($query_get_data_penduduk);
				if(!empty($get_data_penduduk))
				{
					$get_data_penduduk = $get_data_penduduk->row();
					
					//GET JENIS NASKAH
					$query_get_jenis_naskah = "SELECT * FROM tb_jenis_naskah WHERE id_jenis_naskah = '".$id_jenis_naskah."' ;";
					$get_jenis_naskah = $this->M_dash->view_query_general($query_get_jenis_naskah);
					if(!empty($get_jenis_naskah))
					{
						$get_jenis_naskah = $get_jenis_naskah->row();
						
						$id_pengajuan_format = $get_jenis_naskah->id_jenis_naskah."-".$get_pengajuan->sumber."-".$get_pengajuan->tgl_surat_dibuat;
						//GET VAIRABLE
						$query_get_isian_naskah = "
							SELECT
								A.*
								,COALESCE(B.nama_var,'') AS var_naskah
							FROM tb_isi_var_naskah AS A 
							LEFT JOIN tb_var_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.id_var_naskah = B.id_var_naskah AND B.id_jenis_naskah = '".$get_jenis_naskah->id_jenis_naskah."'
							WHERE A.id_jenis_naskah = '".$get_jenis_naskah->id_jenis_naskah."' 
							AND A.id_pengajuan = '".$id_pengajuan_format."';
							-- AND A.id_pengajuan = '1-3203042104900001-2023-09-19';
						";
						$get_isian_naskah = $this->M_dash->view_query_general($query_get_isian_naskah);
						
						//$data = array('page_content'=>'king_admin_tahapan','halaman'=>$halaman,'list_tahapan'=>$list_tahapan);
						$data = array('get_pengajuan'=>$get_pengajuan,'get_data_penduduk'=>$get_data_penduduk,'get_jenis_naskah'=>$get_jenis_naskah,'get_isian_naskah'=>$get_isian_naskah,'id_pengajuan_format'=>$id_pengajuan_format);
						$this->load->view('admin/page/king_admin_cetak_format_naskah.php',$data);
					}
					else
					{
						echo'TIDAK ADA DATA !';
					}
				}
				else
				{
					echo'TIDAK ADA DATA !';
				}
			}
			else
			{
				echo'TIDAK ADA DATA !';
			}
		}
	
		function cek_satu_nik_satu_hari_ajuan()
		{
			$nik = htmlentities($_POST['nik'], ENT_QUOTES, 'UTF-8');
			$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
			//$tgl_surat_masuk = htmlentities($_POST['tgl_surat_masuk'], ENT_QUOTES, 'UTF-8');
			
			//$query = "SELECT * FROM tb_pengajuan WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND sumber = '".$nik."' AND tgl_surat_masuk = '".$tgl_surat_masuk."';";
			$query = "SELECT * FROM tb_pengajuan WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND sumber = '".$nik."' AND DATE(tgl_surat_masuk) = DATE(NOW());";
			
			$get_pengajuan = $this->M_dash->view_query_general($query);
			if(!empty($get_pengajuan))
			{
				//SUDAAH ADA
				echo "GAGAL";
			}
			else
			{
				echo "BERHASIL";
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