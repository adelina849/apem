<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('m_dash'));
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
				$data_transaksi_jual = 0;//$this->m_dash->getTransaksiJual();
				$data_transaksi_beli = 0;//$this->m_dash->getTransaksiBeli();
				$data_total_jual = 0;//$this->m_dash->getTotalJual();
				$data_total_beli = 0;//$this->m_dash->getTotalBeli();
				$st_penjualan = 0;//$this->m_dash->st_penjualan();
				$st_uang_keluar = 0;//$this->m_dash->st_uang_keluar();
				
				$data = array('page_content'=>'admin_dashboard','data_transaksi_jual'=>$data_transaksi_jual,'data_transaksi_beli'=>$data_transaksi_beli,'data_total_jual'=>$data_total_jual,'data_total_beli'=>$data_total_beli,'st_penjualan'=>$st_penjualan,'st_uang_keluar'=>$st_uang_keluar);
				$this->load->view('admin/container',$data);
				//echo "Hallo World";
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin.php */