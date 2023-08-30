<?php

	Class C_admin_statistik Extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model(array('M_statistik'));
		}
		
		function index()
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
					$hari_ini = date("Y-m-d");
					if((!empty($_GET['dari'])) && ($_GET['dari']!= "")  )
					{
						$dari = $_GET['dari'];
					}
					else
					{
						$dari = date('Y-m-01', strtotime($hari_ini));
					}
					
					if((!empty($_GET['sampai'])) && ($_GET['sampai']!= "")  )
					{
						$sampai = $_GET['sampai'];
					}
					else
					{
						$sampai = date('Y-m-t', strtotime($hari_ini));
					}
					
					$stat_by_perhari = $this->M_statistik->stat_by_perhari($dari,$sampai);
					$stat_by_jenis_laporan = $this->M_statistik->stat_by_jenis_laporan($dari,$sampai);
					$stas_by_hasil = $this->M_statistik->stas_by_hasil($dari,$sampai);
					
					$data = array('page_content'=>'king_admin_statistik','dari' => $dari, 'sampai' => $sampai,'stat_by_perhari' => $stat_by_perhari,'stat_by_jenis_laporan' => $stat_by_jenis_laporan,'stas_by_hasil' => $stas_by_hasil);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-login');
				}
			}
		}
	}

?>