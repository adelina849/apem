<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_status_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_status_naskah','M_dash'));
		
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
					$cari = "WHERE AA.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
							AND (
									AA.no_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR AA.kode_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR AA.sumber LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR AA.perihal LIKE '%".str_replace("'","",$_GET['cari'])."%'
									OR AA.diajukan_oleh LIKE '%".str_replace("'","",$_GET['cari'])."%'
								)
							";
				}
				else
				{
					$cari = "WHERE AA.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				$config['first_url'] = site_url('admin-status-dokumen?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-status-dokumen/');
				$config['total_rows'] = $this->M_status_naskah->count_status_naskah_limit($cari)->JUMLAH;
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
				$list_status_naskah = $this->M_status_naskah->list_status_naskah_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'king_admin_status_naskah','halaman'=>$halaman,'list_status_naskah'=>$list_status_naskah);
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
		if (!empty($_POST['stat_edit']))
		{
			$this->M_status_naskah->edit
			(
				$_POST['stat_edit']
				,$_POST['tgl_updt_status']
				,$_POST['ket_status']
				,$this->session->userdata('ses_id_karyawan')
			);
			header('Location: '.base_url().'admin-status-dokumen');
		}
		else
		{
			$this->M_status_naskah->simpan
			(
				$_POST['id_tahapan_naskah']
				,$_POST['nama_tahapan']
				,$_POST['id_pengajuan']
				,$_POST['id_karyawan']
				,$_POST['user_insert']
				,$_POST['tgl_updt_status']
				,$_POST['ket_status']
				,$this->session->userdata('ses_id_karyawan')
				,$this->session->userdata('ses_kode_kantor')
				,'KAB'
			);
			header('Location: '.base_url().'admin-status-dokumen');
		}
		
		//echo 'ade';
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		$this->M_status_naskah->hapus($id);
		header('Location: '.base_url().'admin-status-dokumen');
	}
	
	public function simpan_hasil_pengajuan()
	{
		$this->M_status_naskah->hasil_pengajuan($_POST['id_pengajuan'],$_POST['hasil_pengajuan'],$_POST['ket_hasil']);
		
		$query_get_data = "
						SELECT * 
						FROM tb_pengajuan AS A 
						LEFT JOIn tb_penduduk AS B 
							ON A.kode_kantor = B.kode_kantor 
							AND A.sumber = B.nik 
						LEFT JOIN tb_jenis_naskah AS C 
							ON A.kode_kantor = C.kode_kantor
							AND A.id_jenis_naskah = C.id_jenis_naskah
						WHERE A.id_pengajuan = '".$_POST['id_pengajuan']."' 
						AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;
					";
		$get_data = $this->M_dash->view_query_general($query_get_data);
		if(!empty($get_data))
		{
			$get_data = $get_data->row();
			
			//KIRIM EMAIL
			/*
				$this->load->config('email');
				$this->load->library('email');
				
				$pesan = "
					<html>
					   <head>
						 <title>Selamat, Pelayanan Anda Telah Selesai</title>
					   </head>
					   <body>
					   
	<center>
	<img id='img_bpt' src='".base_url('assets/global/images/bupati_cjr.png')."' style='float:left;'>
	<img id='img_cam' src='".base_url('assets/global/images/cam_sam.png')."' style='float:right;'>
	</center>
	
						 <p>Assalamualaikum Wr,Wb,</p>
						 <p>Hi ".$get_data->nama." Terima kasih telah menggunakan layayan Aplikasi <b>ANJUNGAN PATEN MANDIRI (APEM) KECAMATAN CIBEBER</b>. Berikut kami sampaikan informasi hasil dari pengajuan pelayanan anda :</p>
						 
						  <table border='0'>
							  <tbody>
								<tr>
									<td>No Registrasi</td>
									<td>:</td>
									<td>".$get_data->no_pengajuan."</td>
								</tr>
								<tr>
									<td>Jenis Pelayanan</td>
									<td>:</td>
									<td>".$get_data->nama_jenis_naskah."</td>
								</tr>
								<tr>
									<td>Tanggal Pengajuan</td>
									<td>:</td>
									<td>".$get_data->tgl_surat_dibuat."</td>
								</tr>
								<tr>
									<td>Perihal</td>
									<td>:</td>
									<td>".$get_data->perihal."</td>
								</tr>
								<tr>
									<td>Hasil Pengajuan</td>
									<td>:</td>
									<td>".$get_data->hasil_pengajuan."</td>
								</tr>
								<tr>
									<td>Keterangan Hasil</td>
									<td>:</td>
									<td>".$get_data->ket_hasil."</td>
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
				$to = $get_data->email;
				$subject = 'Selamat Pengajuan '.$get_data->nama_jenis_naskah.' Telah Selesai';
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
			*/
			//KIRIM EMAIL
		}
		 
	}
	
	function cek_status_naskah()
	{
		$hasil_cek = $this->M_status_naskah->get_status_naskah('tgl_updt_status',$_POST['tgl_updt_status']);
		echo $hasil_cek;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jabatan.php */