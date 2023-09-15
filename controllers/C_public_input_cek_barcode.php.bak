<?php

	Class C_public_input_cek_barcode Extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model(array('M_pengajuan','M_status_naskah','M_images'));
			
			//TAMBAHAN ERROR str_replace
			error_reporting(0);
			$this->load->library("session");
			$this->load->helper('url');
		}
		
		function index()
		{
			$this->load->view('admin/input_cek_barcode.html');
		}
		
		function progress()
		{
			
			//$no_pengajuan = str_replace("'","",$_POST['no_pengajuan']);
			//$no_pengajuan =  htmlentities( str_replace("'","",$_POST['scan']), ENT_QUOTES, 'UTF-8');
			$no_pengajuan = str_replace("'","",$_POST['scan']);
			//echo $no_pengajuans
			
			$data_pengajuan = $this->M_status_naskah->list_status_naskah_limit(" WHERE AA.no_pengajuan = '".$no_pengajuan."' ",1,0);
			if(!empty($data_pengajuan))
			{
				$data_pengajuan = $data_pengajuan->row();
				$list_status_naskah = $this->M_status_naskah->detail_status_naskah($data_pengajuan->id_pengajuan,$data_pengajuan->id_jenis_naskah,$this->session->userdata('ses_kode_kantor'));
				
				//echo $data_pengajuan->id_pengajuan." - ".$data_pengajuan->id_jenis_naskah." - ".$this->session->userdata('ses_id_karyawan');
				
				$list_images = $this->M_images->list_images_limit($data_pengajuan->id_pengajuan,'pengajuan','',10,0);
				$status_terakhir_naskah = $this->M_status_naskah->get_status_naskah_terakhir($data_pengajuan->id_pengajuan);
				
				$data = array('page_content'=>'king_admin_detail_status_naskah','data_pengajuan' => $data_pengajuan,'list_status_naskah'=>$list_status_naskah,'list_images' => $list_images,'status_terakhir_naskah'=>$status_terakhir_naskah);
				$this->load->view('public/page/king_public_detail_status_naskah.html',$data);
				
			}
			else
			{
				//header('Location: '.base_url().'cek-barcode');
				echo'<h1>DATA TIDAK DITEMUKAN</h1><br/><a href="'.base_url().'cek-barcode">Kembali</a>';
			}
			
				
			
		}
	}

?>