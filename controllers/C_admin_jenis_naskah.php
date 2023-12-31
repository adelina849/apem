<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_jenis_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_jenis_naskah'));
		
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
					$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND nama_jenis_naskah LIKE '%".str_replace("'","",$_GET['cari'])."%'";
				}
				else
				{
					$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				$config['first_url'] = site_url('admin-jenis-dokumen?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-jenis-dokumen/');
				$config['total_rows'] = $this->M_jenis_naskah->count_jenis_naskah_limit($cari)->JUMLAH;
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
				$list_jenis_naskah = $this->M_jenis_naskah->list_jenis_naskah_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'king_admin_jenis_naskah','halaman'=>$halaman,'list_jenis_naskah'=>$list_jenis_naskah);
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
			$this->M_jenis_naskah->edit
				(
					$_POST['stat_edit']
					,$_POST['nama_jenis_naskah']
					,$_POST['syarat_jenis_naskah']
					,$_POST['ket_jenis_naskah']
					,$this->session->userdata('ses_id_karyawan')
				
				);
			header('Location: '.base_url().'admin-jenis-dokumen');
		}
		else
		{
			$this->M_jenis_naskah->simpan
				(
					$_POST['nama_jenis_naskah']
					,$_POST['syarat_jenis_naskah']
					,$_POST['ket_jenis_naskah']
					,$this->session->userdata('ses_id_karyawan')
					,$this->session->userdata('ses_kode_kantor')
					,'KAB'
				);
			header('Location: '.base_url().'admin-jenis-dokumen');
		}
		
		//echo 'ade';
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		$this->M_jenis_naskah->hapus($id);
		header('Location: '.base_url().'admin-jenis-dokumen');
	}
	
	function cek_jenis_naskah()
	{
		$hasil_cek = $this->M_jenis_naskah->get_jenis_naskah('nama_jenis_naskah',$_POST['nama_jenis_naskah']);
		echo $hasil_cek;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jenis_naskah.php */