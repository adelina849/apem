<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_penduduk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_penduduk'));
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
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
								AND 
									(
										nik LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR nama LIKE '%".str_replace("'","",$_GET['cari'])."%'
									)
								";
				}
				else
				{
					$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-penduduk?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-penduduk/');
				$config['total_rows'] = $this->M_penduduk->count_penduduk($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 30;
				$config['num_links'] = 2;
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
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
				
				$list_penduduk = $this->M_penduduk->list_penduduk_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				
				$data = array('page_content'=>'king_admin_penduduk','halaman'=>$halaman,'list_penduduk'=>$list_penduduk);
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
					$this->M_penduduk->edit
					(
						$_POST['stat_edit']
						,$_POST['nik']
						,$_POST['nama']
						,$_POST['jenis_kelamin']
						,'' //,$_POST['status_menikah']
						,$_POST['tempat_lahir']
						,$_POST['tgl_lahir']
						,$_POST['tlp']
						,$_POST['email']
						,$_POST['alamat']
						,$this->session->userdata('ses_kode_kantor')
					);
					
					header('Location: '.base_url().'admin-penduduk');
				}
				else
				{
					$this->M_penduduk->simpan
					(
						$_POST['nik']
						,$_POST['nama']
						,$_POST['jenis_kelamin']
						,'' //,$_POST['status_menikah']
						,$_POST['tempat_lahir']
						,$_POST['tgl_lahir']
						,$_POST['tlp']
						,$_POST['email']
						,$_POST['alamat']
						,'ADMIN' //$from_db
						,$this->session->userdata('ses_kode_kantor')
					);
					header('Location: '.base_url().'admin-penduduk');
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
		$id_penduduk = $this->uri->segment(2,0);
		$cari = "WHERE MD5(id_penduduk) = '".$id_penduduk."' ";
		$hasil_cek = $this->M_penduduk->get_data_penduduk($cari);
		if(!empty($hasil_cek))
		{
			$this->M_penduduk->hapus_data_penduduk($cari);
		}
		header('Location: '.base_url().'admin-penduduk');
	}
	
	function get_data_penduduk()
	{
		$nik = $_POST['nik'];
		$cari = "WHERE (nik) = '".$nik."' ";
		$hasil_cek = $this->M_penduduk->get_data_penduduk($cari);
		if(!empty($hasil_cek))
		{
			//$this->M_penduduk->hapus_data_penduduk($cari);
			echo'BERHASIL';
		}
		else
		{
			return false;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_admin_penduduk.php */