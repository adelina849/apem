<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_pengajuan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_jenis_naskah','M_pengajuan','M_penduduk','M_dash'));
		$this->load->config('email');
        $this->load->library('email');      
		
	}
	
	public function index()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				// $data = array('page_content'=>'king_jabatan');
				// $this->load->view('admin/container',$data);
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND (
									COALESCE(B.nama_jenis_naskah,'') LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.no_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.kode_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.diajukan_oleh LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.perihal LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.sumber LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR A.tandatangan_oleh LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)";
				}
				else
				{
					$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				$config['first_url'] = site_url('admin-pengajuan-dokumen?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-pengajuan-dokumen/');
				$config['total_rows'] = $this->M_pengajuan->count_pengajuan_limit($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 30;
				$config['num_links'] = 2;
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
				//$config['use_page_numbers'] = TRUE;
				//$config['page_query_string'] = false;
				//$config['query_string_segment'] = '';
				$config['first_page'] = 'Awal';
				$config['last_page'] = 'Akhir';
				$config['next_page'] = '&laquo;';
				$config['prev_page'] = '&raquo;';
				
				
				$config['full_tag_open'] = '<div><ul class="pagination">';
				$config['full_tag_close'] = '</ul></div>';
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev page">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next page">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = 'Next &rarr;';
				$config['next_tag_open'] = '<li class="next page">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&larr; Previous';
				$config['prev_tag_open'] = '<li class="prev page">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li class="page">';
				$config['num_tag_close'] = '</li>';
				
				
				//inisialisasi config
				$this->pagination->initialize($config);
				$halaman = $this->pagination->create_links();
				
				if((!empty($_GET['from'])) && ($_GET['from']!= "")  )
				{
					$list_pengajuan = $this->M_pengajuan->list_pengajuan_limit($cari,1,0);
				}
				else
				{
					$list_pengajuan = $this->M_pengajuan->list_pengajuan_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				}
				
				$list_jenis_naskah = $this->M_jenis_naskah->list_jenis_naskah_limit('',10,0);
				
				$data = array('page_content'=>'king_admin_pengajuan','halaman'=>$halaman,'list_pengajuan'=>$list_pengajuan,'list_jenis_naskah' => $list_jenis_naskah);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	public function simpan()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				if((!empty($_POST['from'])) && ($_POST['from']!= "")  )
				{
					$from = $_POST['from'];
				}
				else
				{
					$from="";
				}
					if (!empty($_POST['stat_edit']))
					{
						$this->M_pengajuan->edit
							(
								$_POST['stat_edit']
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
						header('Location: '.base_url().'admin-pengajuan-dokumen?from'.$from);
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
								,$this->session->userdata('ses_id_karyawan')
								,$this->session->userdata('ses_kode_kantor')
								,'KAB'
							);
							
						$data_pengajuan = $this->M_pengajuan->get_pengajuan('A.kode_pengajuan',$_POST['kode_pengajuan'])	;
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
						
							
							
						header('Location: '.base_url().'admin-pengajuan-dokumen?from'.$from);
					}
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	public function hapus()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				$id = $this->uri->segment(2,0);
				$this->M_pengajuan->hapus($id);
				
				//Hapus Images
					$this->load->model('M_images');
					$list_images = $this->M_images->get_images($id,'pengajuan','id',$id);
					if(!empty($list_images))
					{
						$list_result = $list_images->result();
						foreach($list_result as $row)
						{
							$this->M_images->do_upload('',$row->img_file);
						}
					}
				//Hapus Images
				
				header('Location: '.base_url().'admin-pengajuan-dokumen');
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function cek_pengajuan()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				$hasil_cek = $this->M_pengajuan->get_pengajuan('A.kode_pengajuan',$_POST['kode_pengajuan']);
				echo $hasil_cek;
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function cek_tb_jenis_naskah()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
				{
					$cari = " WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND nama_jenis_naskah LIKE '%".str_replace("'","",$_POST['cari'])."%' " ;
				}
				else
				{
					$cari= " WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
				}
				
				$list_jenis_naskah = $this->M_jenis_naskah->list_jenis_naskah_limit($cari,10,0);
				
				if(!empty($list_jenis_naskah))
				{
					echo'<table width="100%" id="example2" class="table table-bordered table-hover">';
						echo '<thead>
		<tr>';
									echo '<th width="5%">No</th>';
									echo '<th width="30%">Jenis Dokumen</th>';
									echo '<th width="45%">Katerangan</th>';
									echo '<th width="20%">Aksi</th>';
						echo '</tr>
		</thead>';
						$list_result = $list_jenis_naskah->result();
						$no =1;
						echo '<tbody>';
						foreach($list_result as $row)
						{
							echo'<tr>';
								echo'<td><input type="hidden" id="no_'.$row->id_jenis_naskah.'" value="'.$row->id_jenis_naskah.'" />'.$no.'</td>';
								
								//echo'<td>'.number_format($row->besar_denda,0,',','.').' - '.$row->optr.'</td>';
								echo'<td>'.$row->nama_jenis_naskah.'</td>';
								echo'<td>'.$row->ket_jenis_naskah.'</td>';
								
								
								echo'<input type="hidden" id="id_jenis_naskah2_'.$no.'" value="'.$row->id_jenis_naskah.'" />';
								echo'<input type="hidden" id="nama_jenis_naskah2_'.$no.'" value="'.$row->nama_jenis_naskah.'" />';
								
								
								echo'<td>
		<button type="button" onclick="insert('.$no.')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Pilih</button>
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
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function cek_tb_penduduk()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
				{
					$cari = " WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
								AND 
								(
									nik LIKE '%".str_replace("'","",$_POST['cari'])."%'
									OR nama LIKE '%".str_replace("'","",$_POST['cari'])."%'
								)
							";
				}
				else
				{
					$cari= " WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
				}
				
				$list_penduduk = $this->M_penduduk->list_penduduk_limit($cari,30,0);
				
				if(!empty($list_penduduk))
				{
					echo'<table width="100%" id="example2" class="table table-bordered table-hover">';
						echo '<thead>
		<tr>';
									echo '<th width="5%">No</th>';
									echo '<th width="40%">Data Penduduk</th>';
									echo '<th width="40%">Kontak Penduduk</th>';
									echo '<th width="15%">Aksi</th>';
						echo '</tr>
		</thead>';
						$list_result = $list_penduduk->result();
						$no =1;
						echo '<tbody>';
						foreach($list_result as $row)
						{
							echo'<tr>';
								echo'<td>'.$no.'</td>';
								echo'<td>
										<b>NIK : </b>'.$row->nik.'
										<br/><b>Nama : </b>'.$row->nama.'
										<br/><b>Kelamin : </b>'.$row->jenis_kelamin.'
										<br/><b>TTL : </b>'.$row->tempat_lahir.', '.$row->tgl_lahir.'
									</td>';
								
								echo'<td>
										<b>Telpon : </b>'.$row->tlp.'
										<br/><b>Email : </b>'.$row->email.'
										<br/><b>Alamat : </b>'.$row->alamat.'
									</td>';
								
								
								echo'<input type="hidden" id="nik_'.$no.'" value="'.$row->nik.'" />';
								echo'<input type="hidden" id="id_penduduk_'.$no.'" value="'.$row->id_penduduk.'" />';
								
								
								echo'<td>
		<button type="button" onclick="insert_penduduk('.$no.')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal_penduduk">Pilih</button>
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
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function view_isian_var_naskah()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				//VIEW ISIAN DATA
					$nik = htmlentities($_POST['nik_isi_var'], ENT_QUOTES, 'UTF-8');
					$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah_isi_var'], ENT_QUOTES, 'UTF-8');
					$tgl_surat_dibuat_untuk_isian = htmlentities($_POST['tgl_surat_dibuat_isi_var'], ENT_QUOTES, 'UTF-8');
					
					$query_data = "
								SELECT A.*,COALESCE(B.isi,'') AS isi
								FROM tb_var_naskah AS A
								LEFT JOIN tb_isi_var_naskah AS B 
									ON A.id_var_naskah = B.id_var_naskah 
									AND B.id_jenis_naskah = '".$id_jenis_naskah."'
									AND B.id_pengajuan = '".$id_jenis_naskah."-".$nik."-".$tgl_surat_dibuat_untuk_isian."'
								WHERE A.id_jenis_naskah = '".$id_jenis_naskah."' 
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
			
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	
	function view_isian_syarat_naskah()
	{
		if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'admin-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
				$nik = htmlentities($_POST['nik_isi_syarat_naskah'], ENT_QUOTES, 'UTF-8');
				$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah_isi_syarat_naskah'], ENT_QUOTES, 'UTF-8');
				$tgl_surat_dibuat_untuk_isian = htmlentities($_POST['tgl_surat_dibuat_isi_syarat_naskah'], ENT_QUOTES, 'UTF-8');
				
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
									WHERE A.id_jenis_naskah = '".$id_jenis_naskah."' 
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
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function kirim_email_test()
	{
		$pesan = "
			<html>
			   <head>
				 <title>Pendaftaran Akun Berhasil</title>
			   </head>
			   <body>
				 <p>Hello,</p>
				 <p>Anda baru saja melakukan pendaftaran akun login aplikasi Megafire dengan data sebagai berikut :</p>
				 
				  <table border='1'>
					  <tbody>
						<tr>
							<td>Nama Lengkap</td>
							<td>ADE</td>
						</tr>
						<tr>
							<td>No. Telepon</td>
							<td>085710867033</td>
						</tr>
						<tr>
							<td>Email</td>
							<td>test@gmail.com</td>
						</tr>
						<tr>
							<td>Username</td>
							<td>HAHAHA</td>
						</tr>
						<tr>
							<td>Password</td>
							<td>HAHAHA</td>
						</tr>
					  </tbody>
				 </table>

				 <p>Anda belum bisa login sebelum admin melakukan tahap verifikasi. <br> 
					Proses verifikasi akan di lakukan oleh admin secepatnya. </p>

				 <p>Hormat kami, <br>
				 Admin Megafire Indonesia
				 </p>
			   </body>
			 </html>
	  ";

	  $from = $this->config->item('smtp_user');
	  //$to = $email;
	  $to = 'amazon.mulyanayusuf@gmail.com';
	  $subject = 'Pendaftaran  akun Megafire Berhasil';
	  $message = $pesan;

	  $this->email->set_newline("\r\n");
	  $this->email->from($from);
	  $this->email->to($to);
	  $this->email->subject($subject);
	  $this->email->message($message);

	  if ($this->email->send()) {
		 // echo 'Your Email has successfully been sent.';

	  } else {
		  show_error($this->email->print_debugger());
	  }  
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jenis_naskah.php */