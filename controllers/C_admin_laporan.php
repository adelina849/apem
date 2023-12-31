<?php

	Class C_admin_laporan Extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model(array('M_laporan'));
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
							
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " AND AA.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
									AND 
									(
										AA.no_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.kode_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.nama_jenis_naskah LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.diajukan_oleh LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.perihal LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.sumber LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR AA.hasil_pengajuan LIKE '%".str_replace("'","",$_GET['cari'])."%'
									)
									";
					}
					else
					{
						$cari = " AND AA.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
					}
					
					if((!empty($_GET['berdasarkan'])) && ($_GET['berdasarkan']!= "")  )
					{
						$berdasarkan = "AA.".$_GET['berdasarkan']." ASC";
					}
					else
					{
						$berdasarkan = "AA.tgl_ins DESC";
					}
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					$config['first_url'] = site_url('admin-laporan-dokumen?'.http_build_query($_GET));
					$config['base_url'] = site_url('admin-laporan-dokumen/');
					$config['total_rows'] = $this->M_laporan->count_list_pengajuan($cari,$dari,$sampai)->JUMLAH;
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
					$list_laporan_naskah = $this->M_laporan->lap_list_pengajuan($cari,$berdasarkan,$dari,$sampai,$config['per_page'],$this->uri->segment(2,0));
					$data = array('page_content'=>'king_admin_laporan','halaman'=>$halaman,'dari' => $dari, 'sampai' => $sampai,'list_laporan_naskah'=>$list_laporan_naskah);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-login');
				}
			}
		}
		
		function lap_pajak()
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
						//$dari = date('Y-m-01', strtotime($hari_ini));
						$dari = date("Y-m-d");
					}
					
					if((!empty($_GET['sampai'])) && ($_GET['sampai']!= "")  )
					{
						$sampai = $_GET['sampai'];
					}
					else
					{
						//$sampai = date('Y-m-t', strtotime($hari_ini));
						$sampai = date("Y-m-d");
					}
							
					if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
					{
						$cari = " 
									WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'  
									AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."'
									AND 
									(
										A.nik LIKE '%".str_replace("'","",$_GET['cari'])."%'
										OR A.noPol_ori LIKE '%".str_replace("'","",$_GET['cari'])."%'
									)
									";
					}
					else
					{
						$cari = "WHERE 
									A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'  
									AND DATE(A.tgl_ins) BETWEEN '".$dari."' AND '".$sampai."' ";
						//$cari = "";
					}
					
					
					$this->load->library('pagination');
					//$config['first_url'] = base_url().'admin/jabatan?'.http_build_query($_GET);
					//$config['base_url'] = base_url().'admin/jabatan/';
					$config['first_url'] = site_url('admin-laporan-pajak?'.http_build_query($_GET));
					$config['base_url'] = site_url('admin-laporan-pajak/');
					$config['total_rows'] = $this->M_laporan->count_pajak($cari,$dari,$sampai)->JUMLAH;
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
					$list_laporan_pajak = $this->M_laporan->list_pajak($cari,$config['per_page'],$this->uri->segment(2,0));
					$data = array('page_content'=>'king_admin_laporan_pajak','halaman'=>$halaman,'dari' => $dari, 'sampai' => $sampai,'list_laporan_pajak'=>$list_laporan_pajak);
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