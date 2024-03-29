<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_tahapan_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_tahapan_naskah','M_dash'));
		
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
					$cari = str_replace("'","",$_GET['cari']);
				}
				else
				{
					$cari = "";
				}
				
				$this->load->library('pagination');
				//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
				//$config['base_url'] = base_url().'admin/jabatan/';
				$config['first_url'] = site_url('admin-tahapan-dokumen?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-tahapan-dokumen/');
				$config['total_rows'] = $this->M_tahapan_naskah->count_tahapan_naskah_limit($cari)->JUMLAH;
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
				$list_tahapan_naskah = $this->M_tahapan_naskah->list_tahapan_naskah_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'king_admin_tahapan_naskah','halaman'=>$halaman,'list_tahapan_naskah'=>$list_tahapan_naskah);
				$this->load->view('admin/container',$data);
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function format_naskah()
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
				$id_jenis_naskah = $this->uri->segment(2,0);
				$query_get_jenis_naskah = "SELECT * FROM tb_jenis_naskah WHERE md5(id_jenis_naskah) = '".$id_jenis_naskah."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
				
				$get_jenis_naskah = $this->M_dash->view_query_general($query_get_jenis_naskah);
				if(!empty($get_jenis_naskah))
				{
					$get_jenis_naskah = $get_jenis_naskah->row();
					
					$query_get_var_naskah = "SELECT * FROM tb_var_naskah WHERE md5(id_jenis_naskah) = '".$id_jenis_naskah."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'; ";
					$get_var_naskah = $this->M_dash->view_query_general($query_get_var_naskah);
					
					$data = array('page_content'=>'king_admin_format_naskah','get_jenis_naskah'=>$get_jenis_naskah,'get_var_naskah' => $get_var_naskah);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-tahapan-dokumen');
				}
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}

	function edit_format_naskah()
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
				$id_jenis_naskah = htmlentities($_POST['id_jenis_naskah'], ENT_QUOTES, 'UTF-8');
				$format_naskah = htmlentities($_POST['format_naskah'], ENT_QUOTES, 'UTF-8');
				
				$query = "UPDATE tb_jenis_naskah SET format_naskah = '".$format_naskah."' WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';";
				
				$this->M_dash->exec_query_general($query);
				echo'BERHASIL';
				//echo $query;
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_tahapan_naskah.php */