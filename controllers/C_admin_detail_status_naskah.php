<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_detail_status_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_pengajuan','M_status_naskah','M_images'));
		
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
				$id_pengajuan = $this->uri->segment(2,0);
				$data_pengajuan = $this->M_pengajuan->get_pengajuan('id_pengajuan',$id_pengajuan);
				if(!empty($data_pengajuan))
				{
					$data_pengajuan = $data_pengajuan->row();
					$list_status_naskah = $this->M_status_naskah->detail_status_naskah($id_pengajuan,$data_pengajuan->id_jenis_naskah,$this->session->userdata('ses_kode_kantor'));
					$list_images = $this->M_images->list_images_limit($id_pengajuan,'pengajuan','',10,0);
					$data = array('page_content'=>'king_admin_detail_status_naskah','data_pengajuan' => $data_pengajuan,'list_status_naskah'=>$list_status_naskah,'list_images' => $list_images);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-status-dokumen');
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
		//CEK APAKAH SUDAH ADA
		$cek_status_tahapan_naskah = $this->M_status_naskah->get_status_naskah_costumer(" WHERE id_pengajuan = '".$_POST['id_pengajuan']."' AND id_tahapan_naskah = '".$_POST['id_tahapan_naskah']."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		
		if(!empty($cek_status_tahapan_naskah))
		{
			$this->M_status_naskah->edit
			(
				$_POST['id_tahapan_naskah']
				,$_POST['id_pengajuan']
				,$_POST['tgl_updt_status']
				,$_POST['di_proses_oleh']
				,$_POST['ket_status']
				,$this->session->userdata('ses_id_karyawan')
			);
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
				,$_POST['di_proses_oleh']
				,$_POST['jenis_keputusan']
				,$_POST['ket_status']
				,$_POST['ordr_index']
				,$this->session->userdata('ses_id_karyawan')
				,$this->session->userdata('ses_kode_kantor')
				,'KAB'
			);
		}
	}
	
	public function hapus()
	{
		$this->M_status_naskah->hapus($_POST['id_tahapan_naskah'],$_POST['id_pengajuan']);
	}
	
	/*public function update()
	{
		if (!empty($_POST['stat_edit']))
		{
			$this->M_jabatan->edit($_POST['stat_edit'],$_POST['nama'],$_POST['ket'],$this->session->userdata('ses_id_karyawan'));
			header('Location: '.base_url().'admin-jabatan');
		}
		else
		{
			$this->M_jabatan->simpan($_POST['nama'],$_POST['ket'],$this->session->userdata('ses_id_karyawan'),$this->session->userdata('ses_kode_kantor'));
			header('Location: '.base_url().'admin-jabatan');
		}
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		$this->M_jabatan->hapus($id);
		header('Location: '.base_url().'admin-jabatan');
	}
	
	function cek_jabatan()
	{
		$hasil_cek = $this->M_jabatan->get_jabatan_num_rows('nama_jabatan',$_POST['nama']);
		echo $hasil_cek;
	}*/
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_jabatan.php */