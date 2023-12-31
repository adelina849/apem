<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_var_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_var_naskah','M_dash'));
		
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
				
				//CEK JENIS NASKAH DULU
				$md5_id_jenis_naskah = $this->uri->segment(2,0);
				$query_cek_naskah = "
									SELECT * 
									FROM tb_jenis_naskah 
									WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
									AND MD5(id_jenis_naskah) = '".$md5_id_jenis_naskah."'
									";
				$get_jenis_naskah = $this->M_dash->view_query_general($query_cek_naskah);
				if(!empty($get_jenis_naskah))
				{
					$get_jenis_naskah = $get_jenis_naskah->row();
				
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
								AND MD5(trim(id_jenis_naskah)) = '".$md5_id_jenis_naskah."' 
								AND nama_var LIKE '%".str_replace("'","",$_GET['cari'])."%'";
					}
					else
					{
						$cari = "WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
								AND MD5(trim(id_jenis_naskah)) = '".$md5_id_jenis_naskah."' 
							";
					}
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					$config['first_url'] = site_url('admin-kebutuhan-data/'.$md5_id_jenis_naskah.'?'.http_build_query($_GET));
					$config['base_url'] = site_url('admin-kebutuhan-data/'.$md5_id_jenis_naskah.'/');
					$config['total_rows'] = $this->M_var_naskah->count_var_naskah_limit($cari)->JUMLAH;
					$config['uri_segment'] = 3;	
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
					$list_var_naskah = $this->M_var_naskah->list_var_naskah_limit($cari,$config['per_page'],$this->uri->segment(3,0));
					$data = array('page_content'=>'king_admin_var_naskah','halaman'=>$halaman,'list_var_naskah'=>$list_var_naskah,'get_jenis_naskah' => $get_jenis_naskah);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-jenis-dokumen');
				}
				//CEK JENIS NASKAH DULU
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
			$this->M_var_naskah->edit
				(
					$_POST['stat_edit']
					,$_POST['id_jenis_naskah']
					,$_POST['nama_var']
					,$_POST['tipe']
					,0 //,$_POST['idx']
					,$_POST['ket']
					,$this->session->userdata('ses_kode_kantor')
				);
			header('Location: '.base_url().'admin-kebutuhan-data/'.md5(trim($_POST['id_jenis_naskah'])));
		}
		else
		{
			$this->M_var_naskah->simpan
				(
					$_POST['id_jenis_naskah']
					,$_POST['nama_var']
					,$_POST['tipe']
					,'0' //,$_POST['idx']
					,$_POST['ket']
					,$this->session->userdata('ses_kode_kantor')
				);
			header('Location: '.base_url().'admin-kebutuhan-data/'.md5(trim($_POST['id_jenis_naskah'])));
			/*
			echo $_POST['id_jenis_naskah'];
			echo'<br/>';
			echo md5(trim($_POST['id_jenis_naskah']));
			echo'<br/>';
			echo md5(1);
			*/
		}
		
		//echo 'ade';
	}
	
	public function hapus()
	{
		$id = trim($this->uri->segment(2,0));
		$id_jenis_naskah = trim($this->uri->segment(3,0));
		$this->M_var_naskah->hapus($id);
		header('Location: '.base_url().'admin-kebutuhan-data/'.$id_jenis_naskah);
		//echo $id_jenis_naskah;
	}
	
	function ubah_idx()
	{
		$id_var_naskah = htmlentities($_POST['id_var_naskah'], ENT_QUOTES, 'UTF-8');
		$idx = htmlentities($_POST['idx'], ENT_QUOTES, 'UTF-8');
		
		$query = "UPDATE tb_var_naskah SET idx = '".$idx."' WHERE id_var_naskah = '".$id_var_naskah."' ;";
		$this->M_dash->exec_query_general($query);
		echo'BERHASIL';
		//echo $query;
	}
	
	function cek_var_naskah()
	{
		$hasil_cek = $this->M_var_naskah->get_var_naskah('nama_var',$_POST['nama_var']);
		if(!empty($hasil_cek))
		{
			echo'SUDAH ADA';
		}
		else
		{
			echo'BERHASIL';
		}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_admin_var_naskah.php */