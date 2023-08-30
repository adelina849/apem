<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_pengajuan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_jenis_naskah','M_pengajuan'));
		
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
				
				$list_jenis_naskah = $this->M_jenis_naskah->list_jenis_naskah_limit('',10,0);
				$list_pengajuan = $this->M_pengajuan->list_pengajuan_limit($cari,$config['per_page'],$this->uri->segment(2,0));
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
						header('Location: '.base_url().'admin-pengajuan-dokumen');
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
						
							
							
						header('Location: '.base_url().'admin-pengajuan-dokumen');
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
	
	function cek_pengajuan()
	{
		$hasil_cek = $this->M_pengajuan->get_pengajuan('A.kode_pengajuan',$_POST['kode_pengajuan']);
		echo $hasil_cek;
	}
	
	function cek_tb_jenis_naskah()
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jenis_naskah.php */