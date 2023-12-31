<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_images extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_images','M_pengajuan'));
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
				$cek_pengajuan = $this->M_pengajuan->get_pengajuan('id_pengajuan',$this->uri->segment(2,0));
				if(!empty($cek_pengajuan))
				{
					
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = "WHERE img_file LIKE '%".str_replace("'","",$_GET['cari'])."%'";
					}
					else
					{
						$cari = "";
					}
					
					$this->load->library('pagination');
					$config['first_url'] = site_url('admin-pengajuan-dokumen-images/'.$this->uri->segment(2,0).'?'.http_build_query($_GET));
					$config['base_url'] = site_url('admin-pengajuan-dokumen-images/'.$this->uri->segment(2,0).'/');
					$config['total_rows'] = $this->M_images->count_images_limit($this->uri->segment(2,0),'pengajuan',$cari)->JUMLAH;
					$config['uri_segment'] = 3;	
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
					$list_produks_images = $this->M_images->list_images_limit($this->uri->segment(2,0),'pengajuan',$cari,$config['per_page'],$this->uri->segment(3,0));
					$data = array('page_content'=>'king_admin_images','halaman'=>$halaman,'list_produks_images'=>$list_produks_images,'cek_pengajuan'=>$cek_pengajuan->row());
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-pengajuan-dokumen');
				}
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
				if (empty($_FILES['foto']['name']))
				{
					$this->M_images->edit_no_image
					(
						$_POST['stat_edit']
						,$_POST['id']
						,'pengajuan'
						,$_POST['nama']
						,$_POST['keterangan']
						,$this->session->userdata('ses_id_karyawan')
					);
				}
				else
				{
					//$data_images = $this->M_images->get_images($_POST['id'],'pengajuan','id_images',$_POST['stat_edit']);
					$data_images = $this->M_images->get_images($_POST['id'],'pengajuan','id_images',$_POST['stat_edit']);
					$data_images = $data_images->row();
					$this->do_upload(str_replace(" ","",$_FILES['foto']['name']),$data_images->img_file);
					$foto = str_replace(" ","",$_FILES['foto']['name']);
					$foto_url = base_url().'assets/global/images/'.$foto;
					$this->M_images->edit_with_image
					(	
						$_POST['stat_edit']
						,$_POST['id']
						,'pengajuan'
						,$_POST['nama']
						,$foto
						,$foto_url
						,$_POST['keterangan']
						,$this->session->userdata('ses_id_karyawan')
					);
				}
				
				header('Location: '.base_url().'admin-pengajuan-dokumen-images/'.$_POST['id']);
			}
			else
			{
				if (!empty($_FILES['foto']['name']))
				{
					$this->do_upload(str_replace(" ","",$_FILES['foto']['name']),'');
					$foto = str_replace(" ","",$_FILES['foto']['name']);
					$foto_url = base_url().'assets/global/images/'.$foto;
				}
				else
				{
					$foto = '';
					$foto_url = '';
				}
				$this->M_images->simpan
				(
					$_POST['id']
					,'pengajuan'
					,$_POST['nama']
					,$foto
					,$foto_url
					,$_POST['keterangan']
					,$this->session->userdata('ses_kode_kantor')
					,$this->session->userdata('ses_id_karyawan')
				);
				header('Location: '.base_url().'admin-pengajuan-dokumen-images/'.$_POST['id']);
			}
		
	}
	
	function do_upload($id,$cek_bfr)
	{
		$this->load->library('upload');

		if($cek_bfr != '')
		{
			@unlink('./assets/global/images/'.$cek_bfr);
		}
		
		if (!empty($_FILES['foto']['name']))
		{
			$config['upload_path'] = 'assets/global/images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
			$config['max_size']	= '2024';
			//$config['max_widtd']  = '300';
			//$config['max_height']  = '300';
			$config['file_name']	= $id;
			$config['overwrite']	= true;
			

			$this->upload->initialize($config);

			//Upload file 1
			if ($this->upload->do_upload('foto'))
			{
				$hasil = $this->upload->data();
			}
		}
	}
	
	function cek_images()
	{
		$hasil_cek = $this->M_images->get_images($_POST['id'],'pengajuan','img_nama',$_POST['nama']);
		echo $hasil_cek;
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(3,0);
		$hasil_cek = $this->M_images->get_images($this->uri->segment(2,0),'pengajuan','id_images',$id);
		if(!empty($hasil_cek))
		{
			$hasil_cek = $hasil_cek->row();
			$images = $hasil_cek->img_file;
			$this->do_upload('',$images);
			$this->M_images->hapus($id);
		}
		header('Location: '.base_url().'admin-pengajuan-dokumen-images/'.$this->uri->segment(2,0));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_admin_images.php */