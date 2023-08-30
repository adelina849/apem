<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_profile extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_karyawan','M_akun'));
		
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
				// $KLAP_KODE = $this->uri->segment(2,0);
				// $data_klaporan = $this->M_klaporan->get_klaporan('KLAP_KODE',$KLAP_KODE);
				// if(!empty($data_klaporan))
				// {
					// $data_klaporan = $data_klaporan->row();
					
					// $list_laporan = $this->M_laporan->get_laporan_perkecamatan($this->session->userdata('ses_KEC_ID'),"WHERE A.KLAP_ID = '".$data_klaporan->KLAP_ID."'");
					// $data = array('page_content'=>'ptn_kec_list_laporan','data_klaporan' => $data_klaporan,'list_laporan' => $list_laporan);
					// $this->load->view('kecamatan/container',$data);
				// }
				// else
				// {
					// header('Location: '.base_url().'kecamatan-admin-dashboard');
				// }
				
				$data_karyawan = $this->M_akun->get_login($this->session->userdata('ses_user_admin'),md5($this->session->userdata('ses_pass_admin_pure')));
				
				
				$data = array('page_content' => 'king_admin_profile','data_karyawan' => $data_karyawan);
				$this->load->view('admin/container',$data);
				
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	
	public function ubah_password()
	{
		if($_POST['pass'] == $_POST['pass2'])
		{
			$id_karyawan = $this->session->userdata('ses_id_karyawan');
			$user = $_POST['user'];
			$pass = $_POST['pass'];
			$this->M_akun->edit_password($id_karyawan,$user,$pass);
			
			header('Location: '.base_url().'admin-logout');
		}
		else
		{
			header('Location: '.base_url().'profile/pass');
		}
	}
	
	
	public function simpan()
	{
		if($_POST['no_karyawan'] == "")
		{
			$no_karyawan = $this->M_akun->get_no_karyawan($this->session->userdata('ses_kode_kantor'))->no_karyawan;
		}
		else
		{
			$no_karyawan = $_POST['no_karyawan'];
		}
			
				if (empty($_FILES['foto']['name']))
				{
					$this->M_karyawan->edit_no_image
					(
						$_POST['stat_edit']
						,$_POST['jabatan']
						,$no_karyawan
						,$_POST['nik']
						,$_POST['nama']
						,$_POST['pnd']
						,$_POST['tlp']
						,$_POST['email']
						,$_POST['alamat']
						,$_POST['keterangan']
						,$this->session->userdata('ses_id_karyawan')
					);
				}
				else
				{
					$data_karyawan = $this->M_karyawan->get_karyawan_id($_POST['stat_edit']);
					$this->do_upload($_FILES['foto']['name'],$data_karyawan->avatar);
					$foto = $_FILES['foto']['name'];
					$this->M_karyawan->edit_with_image
					(
						$_POST['stat_edit']
						,$_POST['jabatan']
						,$no_karyawan
						,$_POST['nik']
						,$_POST['nama']
						,$_POST['pnd']
						,$_POST['tlp']
						,$_POST['email']
						,$foto
						,base_url().'assets/global/karyawan/'.$foto
						,$_POST['alamat']
						,$_POST['keterangan']
						,$this->session->userdata('ses_id_karyawan')
					);
				}
				
				header('Location: '.base_url().'profile');
			
		
	}
	
	function do_upload($id,$cek_bfr)
	{
		$this->load->library('upload');

		if($cek_bfr != '')
		{
			@unlink('./assets/global/karyawan/'.$cek_bfr);
		}
		
		if (!empty($_FILES['foto']['name']))
		{
			$config['upload_path'] = 'assets/global/karyawan/';
			$config['allowed_types'] = 'gif|jpg|png';
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_admin_laporan.php */