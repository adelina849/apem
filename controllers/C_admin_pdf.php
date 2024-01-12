<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_pdf extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('Fpdf');
		//$this->load->model(array('M_h_pendaftaran','M_kontes','M_d_pendaftaran','M_member','M_penutupan'));
		$this->load->model(array('M_pengajuan'));
	}
	
	
	function tanggal($var = '')
	{
	$tgl = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$pecah = explode("-", $var);
	return $pecah[2]." ".$tgl[$pecah[1] - 1]." ".$pecah[0];
	}
	
	function Terbilang($x)
	{
	  $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	  if ($x < 12)
		return " " . $abil[$x];
	  elseif ($x < 20)
		return $this->Terbilang($x - 10) . " Belas";
	  elseif ($x < 100)
		return $this->Terbilang($x / 10) . " Puluh" . $this->Terbilang($x % 10);
	  elseif ($x < 200)
		return " Seratus" . $this->Terbilang($x - 100);
	  elseif ($x < 1000)
		return $this->Terbilang($x / 100) . " Ratus" . $this->Terbilang($x % 100);
	  elseif ($x < 2000)
		return " Seribu" . $this->Terbilang($x - 1000);
	  elseif ($x < 1000000)
		return $this->Terbilang($x / 1000) . " Ribu" . $this->Terbilang($x % 1000);
	  elseif ($x < 1000000000)
		return $this->Terbilang($x / 1000000) . " Juta" . $this->Terbilang($x % 1000000);
	}
	
	function testpdf()
	{
		
		//$this->fpdf->FPDF('P','cm','A4');
		$this->fpdf->AddPage('L','A4');
		$this->fpdf->SetFont('Arial','',10);
		$teks = "Ini hasil Laporan PDF menggunakan Library FPDF di CodeIgniter";
		$this->fpdf->Cell(3, 0.5, $teks, 1, '0', 'L', true);
		// $this->fpdf->Ln();
		$this->fpdf->Output(); 
	}
	
	
	public function print_faktur_pengajuan()
	{
		$no_pengajuan = $_GET['pengajuan'];
		// echo $id_member;
		//DATA PENGAJUAN
			$data_pengajaun = $this->M_pengajuan->get_pengajuan('A.no_pengajuan',$no_pengajuan);
		//DATA PENGAJUAN
		if(!empty($data_pengajaun))
		{
			$data_pengajaun = $data_pengajaun->row();
			
			
			
			//HEADER
				$this->fpdf->AddPage();
				$this->fpdf->Image('assets/global/images/jabar.png',5,7,15,15);
				//$this->fpdf->Image('assets/global/images/cjrjago200.png',80,7,10,10);
				$this->fpdf->Image('assets/global/images/sugih_mukti.png',85,7,15,15);
				
				$this->fpdf->SetFont('Arial','B',10);
				$this->fpdf->Ln(3);
				$this->fpdf->SetY(10); //Set posisi top
				
				
				//$this->fpdf->Cell(5,5,'',0,0,'L',false);
				$x = $this->fpdf->GetX();
				$this->fpdf->Cell(85,5,'PEMERINTAH KABUPATEN CIANJUR',0,1,'C',false);
				//$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
				
				
				
				//$this->fpdf->Cell(5,5,'',0,0,'L',false);
				//$this->fpdf->Cell(85,5,'KECAMATAN CIBEBER',0,1,'C',false);
				
				$this->fpdf->Cell(85,5, strtoupper( $this->session->userdata('ses_nama_kantor') ) ,0,1,'C',false);
				
				//$this->fpdf->Cell(85,5,'KELURAHAN BOJONGHERANG',0,1,'C',false);
				
				$this->fpdf->Ln(3);
				$this->fpdf->Line(5,23,95,23);
				
				$this->fpdf->SetFont('Arial','',6);
				$this->fpdf->SetY(22); //Set posisi top
				$this->fpdf->Cell(80,5,date('Y-m-d h:m:s'),0,1,'R',false);
				
				$this->fpdf->SetFont('times','',10);
				$this->fpdf->Cell(25,5,"Jenis Dokumen",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);$this->fpdf->Cell(50,5,$data_pengajaun->nama_jenis_naskah,0,1,'L',false);
				
				
				//$this->fpdf->Cell(25,5,"Asal Dokumen",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);$this->fpdf->Cell(50,5,$data_pengajaun->sumber,0,1,'L',false);
				$this->fpdf->Cell(25,5,"NIK/No Pemohon",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);$this->fpdf->Cell(50,5,$data_pengajaun->sumber,0,1,'L',false);
				
				$this->fpdf->Cell(25,5,"Nama Pemohon",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);$this->fpdf->Cell(50,5,$data_pengajaun->diajukan_oleh,0,1,'L',false);
				
				$this->fpdf->Cell(25,5,"Perihal",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);
				//$this->fpdf->Cell(5,5,$data_pengajaun->perihal,0,1,'L',false);
				$x = $this->fpdf->GetX();
				//$this->fpdf->vcell(50,5,$x,$data_pengajaun->perihal);
				$this->fpdf->vcell(50,10,$x,$data_pengajaun->perihal);
				
				
				
				$this->fpdf->Cell(25,5,"Tanggal Terima",0,0,'L',false);$this->fpdf->Cell(3,5,":",0,0,'L',false);$this->fpdf->Cell(50,5,$data_pengajaun->tgl_surat_masuk,0,1,'L',false);
				
				if(file_exists("assets/global/images/qrcode/".$data_pengajaun->no_pengajuan.".png"))
				{
					$this->fpdf->Image("assets/global/images/qrcode/".$data_pengajaun->no_pengajuan.".png",35,57,25,25);
				}
				
				
				
				$this->fpdf->SetY(81); //Set posisi top
				$this->fpdf->SetFont('Arial','B',12);
				$this->fpdf->Cell(75,5,$data_pengajaun->no_pengajuan,0,1,'C',false);
				
				$this->fpdf->SetFont('Arial','',6);
				$this->fpdf->Cell(50,3,"Printed : ".$this->session->userdata('ses_nama_karyawan'),0,1,'L',false);
				
				$this->fpdf->SetFont('Arial','',7);
				$this->fpdf->Ln(3);
				$this->fpdf->Line(5,89,95,89);
				
				$this->fpdf->Cell(75,3,'Simpan slip ini sebagai bukti pengajuan dokumen',0,1,'C',false);
				$this->fpdf->Cell(75,3,'untuk melihat status pengajuan, silahkan kunjungi halaman',0,1,'C',false);
				$this->fpdf->Cell(75,3,base_url(),0,1,'C',false);
				//$this->fpdf->Cell(75,3,'aplikasi naskah dinas cianjur di playstore',0,1,'C',false);
				
				
			//HEADER
			
			$this->fpdf->Output($no_pengajuan.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-member');
		}
	}
	
	// public function print_faktur_pengajuan()
	// {
		// $no_pengajuan = $_GET['pengajuan'];
		// // echo $id_member;
		// //DATA PENGAJUAN
			// $data_pengajaun = $this->M_pengajuan->get_pengajuan('no_pengajuan',$no_pengajuan);
		// //DATA PENGAJUAN
		// if(!empty($data_pengajaun))
		// {
			// $data_pengajaun = $data_pengajaun->row();
			
			
			
			// //HEADER
				// $this->fpdf->AddPage();
				// $this->fpdf->Image('assets/global/images/logo.png',5,7,10,10);
				// $this->fpdf->Image('assets/global/images/cjrjago200.png',80,7,10,10);
			
				// $this->fpdf->SetFont('Arial','B',10);
				// $this->fpdf->Ln(3);
				// $this->fpdf->SetY(7); //Set posisi top
				
				
				// //$this->fpdf->Cell(5,5,'',0,0,'L',false);
				// $x = $this->fpdf->GetX();
				// $this->fpdf->Cell(75,5,'PEMERINTAH KABUPATEN CIANJUR',0,1,'C',false);
				// //$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
				
				// //$this->fpdf->Cell(5,5,'',0,0,'L',false);
				// $this->fpdf->Cell(75,5,'BUKTI PENGAJUAN DOKUMEN',0,1,'C',false);
				
				// $this->fpdf->Ln(3);
				// $this->fpdf->Line(5,20,90,20);
				
				// $this->fpdf->SetFont('Arial','',6);
				// $this->fpdf->SetY(22); //Set posisi top
				// $this->fpdf->Cell(80,5,date('Y-m-d h:m:s'),0,1,'R',false);
				
				// if(file_exists("assets/global/images/qrcode/".$data_pengajaun->no_pengajuan.".png"))
				// {
					// $this->fpdf->Image("assets/global/images/qrcode/".$data_pengajaun->no_pengajuan.".png",35,25,25,25);
				// }
				
				// $this->fpdf->SetY(50); //Set posisi top
				// $this->fpdf->SetFont('Arial','B',12);
				// $this->fpdf->Cell(75,5,$data_pengajaun->no_pengajuan,0,1,'C',false);
				
				// $this->fpdf->SetFont('Arial','',6);
				// $this->fpdf->Cell(50,3,"Printed : ".$this->session->userdata('ses_nama_karyawan'),0,1,'L',false);
				
				// $this->fpdf->SetFont('Arial','',7);
				// $this->fpdf->Ln(3);
				// $this->fpdf->Line(5,58,90,58);
				
				// $this->fpdf->Cell(75,3,'Simpan slip ini sebagai bukti pengajuan dokumen',0,1,'C',false);
				// $this->fpdf->Cell(75,3,'untuk melihat status pengajuan, silahkan download',0,1,'C',false);
				// $this->fpdf->Cell(75,3,'aplikasi naskah dinas cianjur di playstore',0,1,'C',false);
				
				
				// /*$this->fpdf->SetFont('Arial','',10);
				// $this->fpdf->Cell(30,5,'NO REG',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->no_member,0,1,'L',false);
				// $this->fpdf->Cell(30,5,'Telah Terima Dari  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->nama_member,0,1,'L',false);
				
				
				// //$this->fpdf->Ln(8);
				// $this->fpdf->SetFont('Arial','',10);
				// $this->fpdf->SetDrawColor(0,0,0);
				// $this->fpdf->SetFillColor(224,224,224);
				// $this->fpdf->SetTextColor(0,0,0);
				
				// $this->fpdf->Cell(30,5,'Uang Sejumlah  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);
				
				// $this->fpdf->SetFont('Arial','B',10);
				// $this->fpdf->Cell(150,10,$this->Terbilang($data_pendaftaran_member->biaya),0,1,'L',true);
				
				
				// $this->fpdf->SetFont('Arial','',10);
				// $this->fpdf->Cell(200,5,'Untuk pembayaran pendaftaran ikan sebanyak  :',0,1,'L',false);
				
				// $this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,$data_pendaftaran_member->JUM_IKAN.' Ekor',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($data_pendaftaran_member->biaya,0,',','.'),0,1,'R',false);
				
				// $this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,'Diskon 0%',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,'0',0,1,'R',false);
				
				
				// //$this->fpdf->Line(float x1, float y1, float x2, float y2);
				// $this->fpdf->Ln(3);
				// $this->fpdf->Line(50,65,180,65);
				
				// $this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,'Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($data_pendaftaran_member->biaya,0,',','.'),0,1,'R',false);
				
				// $this->fpdf->SetFont('Arial','B',10);
				// $this->fpdf->Cell(10,5,'Rp.  ',0,0,'L',false);$this->fpdf->Cell(50,10,number_format($data_pendaftaran_member->biaya,0,',','.'),0,0,'L',true);$this->fpdf->Cell(50,5,'',0,0,'L',false);
				// $this->fpdf->SetFont('Arial','',10);
				// $this->fpdf->Cell(75,10,$data_kontes->wilayah_kontes.','.$this->tanggal(date('Y-m-d')),0,1,'L',false);
				
				// $this->fpdf->Cell(200,10,'',0,1,'L',false);
				
				// $this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,$data_kontes->ketua_pelaksana,'I',1,'C',false);
				// $this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,'Ketua Pelaksana',0,1,'C',false);*/
				
				
				
				
				
				
				
			// //HEADER
			
			// $this->fpdf->Output($no_pengajuan.'.pdf','D');
		// }
		// else
		// {
			// header('Location: '.base_url().'admin-member');
		// }
	// }
	
	public function print_kwitansi()
	{	
		if(!empty($_GET['id_h_pendaftaran'])){$id_h_pendaftaran = $_GET['id_h_pendaftaran'];}else{$id_h_pendaftaran="";}
		
		//DATA KONTES
			$data_kontes = $this->M_kontes->get_default_kontes();
		//DATA KONTES
		
		//DATA H PENDAFTARAN
			$data_h_pendaftaran = $this->M_h_pendaftaran->list_h_pendaftaran_limit("WHERE A.id_h_pendaftaran = '".$id_h_pendaftaran."' AND A.id_kontes = '".$data_kontes->id_kontes."'",10000,0)->row();
		//DATA H PENDAFTARAN
		
        $this->fpdf->AddPage();
        $this->fpdf->Image('assets/global/kontes/'.$data_kontes->avatar,10,7,50,10);
 
        //Line break
		$this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Ln(3);
        $this->fpdf->SetY(7); //Set posisi top
		
		$this->fpdf->Cell(120,5,'',0,0,'L',false);
		$x = $this->fpdf->GetX();
		//$this->fpdf->vcell(50,5,$x,$data_kontes->nama_kontes);
		$this->fpdf->Cell(100,5,$data_kontes->nama_kontes,0,1,'L',false);
		$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
		
		$this->fpdf->Cell(180,5,'',0,1,'L',false);
		$this->fpdf->Cell(180,5,'KWITANSI PENDAFTARAN',0,1,'C',false);
		$this->fpdf->Cell(15,5,'NO REG',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(50,5,$data_h_pendaftaran->no_h_pendaftaran,0,1,'L',false);
		$this->fpdf->Cell(15,5,'NAMA',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(50,5,$data_h_pendaftaran->nama_handling,0,1,'L',false);
		
		//Line break
        $this->fpdf->Ln(20);
        $this->fpdf->SetY(40); //reset the Y to the original, since we moved it down to write INVOICE
        $this->fpdf->SetFont('Arial','B',8);
 
        $this->fpdf->Ln(0);
        $this->fpdf->Cell(8,8,"NO",1,0,'C',false);
        $this->fpdf->Cell(40,8,"PEMILIK",1,0,'C',false);
        $this->fpdf->Cell(40,8,"HANDLING",1,0,'C',false);
        $this->fpdf->Cell(20,8,"ID IKAN",1,0,'C',false);
		$this->fpdf->Cell(30,8,"JENIS IKAN",1,0,'C',false);
		$this->fpdf->Cell(20,8,"UKURAN",1,0,'C',false);
		$this->fpdf->Cell(25,8,"HARGA",1,0,'C',false);
		$this->fpdf->Cell(10,8,"UK",1,0,'C',false);
 
        $this->fpdf->Ln(8);
		$this->fpdf->SetFont('Arial','',8);
        $this->fpdf->SetDrawColor(0,0,0);
        $this->fpdf->SetFillColor(224,224,224);
        $this->fpdf->SetTextColor(0,0,0);
 
 
		//DATA PENJUALAN
		
			$data_d_pendaftaran = $this->M_d_pendaftaran->list_d_pendaftaran_limit(" WHERE A.id_h_pendaftaran = '".$id_h_pendaftaran."'",1000,0);
			
			if(!empty($data_d_pendaftaran))
			{
				$list_result = $data_d_pendaftaran->result();
				$no =1;
				
				foreach($list_result as $row)
				{
					$this->fpdf->Cell(8,8,$no,1,0,'C');
					$x = $this->fpdf->GetX();
					$this->fpdf->vcell(40,8,$x,$row->nama_pemilik);
					$x = $this->fpdf->GetX();
					$this->fpdf->vcell(40,8,$x,$row->nama_handling);
					$this->fpdf->Cell(20,8,$row->no_d_pendaftaran,1,0,'C');
					$this->fpdf->Cell(30,8,$row->nama_jikan,1,0,'C');
					$this->fpdf->Cell(20,8,$row->nama_uikan,1,0,'C');
					$this->fpdf->Cell(25,8,number_format($row->biaya,0,',','.'),1,0,'C');
					$this->fpdf->Cell(10,8,$row->uk,1,0,'C');
					$this->fpdf->Ln(8);
						
					$no++;
				}
			}
			else
			{
				echo'<center>';
				echo'Tidak Ada Data Yang Ditampilkan !';
				echo'</center>';
			}
			
			$this->fpdf->SetFont('Arial','B',8);
			$this->fpdf->Cell(138,8,'JUMLAH IKAN',1,0,'C');
			$this->fpdf->Cell(20,8,number_format($no-1,0,',','.'),1,0,'C');
			$this->fpdf->Cell(35,8,'Rp.'.number_format($data_h_pendaftaran->BIAYA,0,',','.'),1,0,'C');
			$this->fpdf->Ln(8);
			
			$this->fpdf->Cell(138,8,'VAT',1,0,'C');
			$this->fpdf->Cell(20,8,number_format($data_h_pendaftaran->jum_vat,0,',','.'),1,0,'C');
			$this->fpdf->Cell(35,8,'Rp.'.number_format( ($data_h_pendaftaran->harga_vat * $data_h_pendaftaran->jum_vat),0,',','.'),1,0,'C');
			// $this->fpdf->Cell(35,8,'Rp.'.number_format($data_h_pendaftaran->BIAYA,0,',','.'),1,0,'C');
			$this->fpdf->Ln(8);
			
			
			
			$this->fpdf->Cell(138,8,'TOTAL PEMBAYARAN',1,0,'C');
			
			$this->fpdf->SetDrawColor(255,255,255);
			$this->fpdf->SetFillColor(0,0,0); //Set background of the cell to be that grey color
			$this->fpdf->SetTextColor(255,255,255);
			$this->fpdf->Cell(55,8,'Rp.'.number_format($data_h_pendaftaran->BIAYA + $data_h_pendaftaran->VAT,0,',','.'),1,0,'C',true);
			$this->fpdf->Ln(8);
			
			
			$this->fpdf->Cell(200,10,'',0,1,'L',false);
			
			
			$this->fpdf->SetFont('Arial','B',10);
			$this->fpdf->SetDrawColor(0,0,0);
			$this->fpdf->SetFillColor(224,224,224);
			$this->fpdf->SetTextColor(0,0,0);
			$this->fpdf->Cell(10,5,'Rp.  ',0,0,'L',false);$this->fpdf->Cell(50,10,number_format($data_h_pendaftaran->BIAYA + $data_h_pendaftaran->VAT,0,',','.'),0,0,'L',true);$this->fpdf->Cell(50,5,'',0,0,'L',false);
			$this->fpdf->SetFont('Arial','',10);
			$this->fpdf->Cell(75,10,$data_kontes->wilayah_kontes.','.$this->tanggal(date('Y-m-d')),0,1,'C',false);
			
			$this->fpdf->SetFont('Arial','B',10);
			$this->fpdf->SetDrawColor(0,0,0);
			$this->fpdf->SetFillColor(224,224,224);
			$this->fpdf->SetTextColor(0,0,0);
			if($data_h_pendaftaran->status_lunas == "")
			{
				$this->fpdf->Cell(100,10,'Pembayaran : Belum Lunas',0,0,'L',false);$this->fpdf->Cell(100,10,'',0,1,'L',false);
			}
			else
			{
				$this->fpdf->Cell(100,10,'Pembayaran : '.$data_h_pendaftaran->status_lunas.'('.$data_h_pendaftaran->cara_bayar.')',0,0,'L',false);$this->fpdf->Cell(100,10,'',0,1,'L',false);
			}
			
			$this->fpdf->SetFont('Arial','',10);
			$this->fpdf->Cell(100,5,'User Input : '.$data_h_pendaftaran->nama_karyawan,0,0,'L',false);$this->fpdf->Cell(100,5,'',0,1,'L',false);
			$this->fpdf->Cell(100,5,'Kantong : S : '.$data_h_pendaftaran->uk_s.',M : '.$data_h_pendaftaran->uk_m.',L : '.$data_h_pendaftaran->uk_l.', VAT : '.$data_h_pendaftaran->uk_vat,0,0,'L',false);$this->fpdf->Cell(100,5,'',0,1,'L',false);
			
			//$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,$data_kontes->ketua_pelaksana,'I',1,'C',false);
			$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,"ANANG",'I',1,'C',false);
			$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,'BENDAHARA',0,1,'C',false);
			$this->fpdf->SetFont('Arial','',8);
			$this->fpdf->Cell(110,5,'No Rekening Resmi : 3770 2238 30 - BCA Atas nama REGGI AGUSTIAN',0,1,'C',false);
		
		$this->fpdf->Output($data_h_pendaftaran->no_h_pendaftaran.'.pdf','D');
	}
	
	public function print_kwitansi_pemilik()
	{
		$id_member = $_GET['member'];
		// echo $id_member;
		//DATA MEMBER
			$data_member = $this->M_member->get_member_id($id_member);
		//DATA MEMBER
		if(!empty($data_member))
		{
			//DATA KONTES
				$data_kontes = $this->M_kontes->get_default_kontes();
			//DATA KONTES
			
			//DATA PENDAFTARAN MEMBER
				$data_pendaftaran_member = $this->M_d_pendaftaran->get_pendaftaran_per_member($data_member->nama_member,$data_kontes->id_kontes);
			//DATA PENDAFTARAN MEMBER
			
			
			
			//HEADER
				$this->fpdf->AddPage();
				$this->fpdf->Image('assets/global/kontes/'.$data_kontes->avatar,10,7,50,10);
			
				$this->fpdf->SetFont('Arial','B',10);
				$this->fpdf->Ln(3);
				$this->fpdf->SetY(7); //Set posisi top
				
				$this->fpdf->Cell(120,5,'',0,0,'L',false);
				$x = $this->fpdf->GetX();
				$this->fpdf->Cell(100,5,$data_kontes->nama_kontes,0,1,'L',false);
				$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
				
				$this->fpdf->Cell(180,5,'',0,1,'L',false);
				$this->fpdf->Cell(180,5,'KWITANSI PENDAFTARAN (Pemilik)',0,1,'C',false);
				
				
				$this->fpdf->SetFont('Arial','',10);
				$this->fpdf->Cell(30,5,'NO REG',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->no_member,0,1,'L',false);
				$this->fpdf->Cell(30,5,'Telah Terima Dari  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->nama_member,0,1,'L',false);
				
				
				//$this->fpdf->Ln(8);
				$this->fpdf->SetFont('Arial','',10);
				$this->fpdf->SetDrawColor(0,0,0);
				$this->fpdf->SetFillColor(224,224,224);
				$this->fpdf->SetTextColor(0,0,0);
				
				$this->fpdf->Cell(30,5,'Uang Sejumlah  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);
				
				$this->fpdf->SetFont('Arial','B',10);
				$this->fpdf->Cell(150,10,$this->Terbilang($data_pendaftaran_member->biaya),0,1,'L',true);
				
				
				$this->fpdf->SetFont('Arial','',10);
				$this->fpdf->Cell(200,5,'Untuk pembayaran pendaftaran ikan sebanyak  :',0,1,'L',false);
				
				$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,$data_pendaftaran_member->JUM_IKAN.' Ekor',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($data_pendaftaran_member->biaya,0,',','.'),0,1,'R',false);
				
				$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,'Diskon 0%',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,'0',0,1,'R',false);
				
				
				//$this->fpdf->Line(float x1, float y1, float x2, float y2);
				$this->fpdf->Ln(3);
				$this->fpdf->Line(50,65,180,65);
				
				$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(30,5,'Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($data_pendaftaran_member->biaya,0,',','.'),0,1,'R',false);
				
				$this->fpdf->SetFont('Arial','B',10);
				$this->fpdf->Cell(10,5,'Rp.  ',0,0,'L',false);$this->fpdf->Cell(50,10,number_format($data_pendaftaran_member->biaya,0,',','.'),0,0,'L',true);$this->fpdf->Cell(50,5,'',0,0,'L',false);
				$this->fpdf->SetFont('Arial','',10);
				$this->fpdf->Cell(75,10,$data_kontes->wilayah_kontes.','.$this->tanggal(date('Y-m-d')),0,1,'L',false);
				
				$this->fpdf->Cell(200,10,'',0,1,'L',false);
				
				$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,$data_kontes->ketua_pelaksana,'I',1,'C',false);
				$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,'Ketua Pelaksana',0,1,'C',false);
				
				
				
				
				
				
				
			//HEADER
			
			$this->fpdf->Output($data_member->nama_member.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-member');
		}
	}
	
	
	public function print_kwitansi_pemilik_looping()
	{
		$id_member = $_GET['member'];
		// echo $id_member;
		//DATA MEMBER
			$data_member = $this->M_member->get_member_id($id_member);
		//DATA MEMBER
		if(!empty($data_member))
		{
			//DATA KONTES
				$data_kontes = $this->M_kontes->get_default_kontes();
			//DATA KONTES
			
			//DATA HEADER PENDAFTARAN MEMBER
				$data_h_pendaftaran_member = $this->M_h_pendaftaran->list_h_pendaftaran_limit(" WHERE B.nama_pemilik = '".$data_member->nama_member."' AND A.id_kontes = '".$data_kontes->id_kontes."'",10000,0);
			//DATA HEADER PENDAFTARAN MEMBER
			
			
			//MEMULAI LOOPIING
				if(!empty($data_h_pendaftaran_member))
				{
					$list_result = $data_h_pendaftaran_member->result();
					$no =1;
					foreach($list_result as $row)
					{	
						
						$this->fpdf->AddPage();
						$this->fpdf->Image('assets/global/kontes/'.$data_kontes->avatar,10,7,50,10);
					
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Ln(3);
						$this->fpdf->SetY(7); //Set posisi top
						
						$this->fpdf->Cell(120,5,'',0,0,'L',false);
						$x = $this->fpdf->GetX();
						$this->fpdf->Cell(100,5,$data_kontes->nama_kontes,0,1,'L',false);
						$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
						
						$this->fpdf->Cell(180,5,'',0,1,'L',false);
						$this->fpdf->Cell(180,5,'KWITANSI PENDAFTARAN (Pemilik)',0,1,'C',false);
						
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(32,5,'NO REG',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$row->no_h_pendaftaran,0,1,'L',false);
						$this->fpdf->Cell(32,5,'Telah Terima Dari  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->nama_member,0,1,'L',false);
						$this->fpdf->Cell(32,5,'Handling',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$row->nama_handling,0,1,'L',false);
						
						
						//$this->fpdf->Ln(8);
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->SetDrawColor(0,0,0);
						$this->fpdf->SetFillColor(224,224,224);
						$this->fpdf->SetTextColor(0,0,0);
						
						$this->fpdf->Cell(32,5,'Uang Sejumlah  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(150,10,$this->Terbilang($row->BIAYA + ($row->VAT)),0,1,'L',true);
						
						
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(32,5,'Untuk Pembayaran',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,'Pendaftaran ikan sebanyak '.$row->CNT.' Ekor',0,1,'L',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(200,5,'Rincian Biaya',0,1,'C',false);
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Daftar '.$row->CNT.' Ekor',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA,0,',','.'),0,1,'R',false);
						
						//$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Diskon 0%',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,'0',0,1,'R',false);
						
						
						
						// $this->fpdf->Ln(3);
						// $this->fpdf->Line(50,70,180,70);
						
						// $this->fpdf->SetFont('Arial','B',10);
						// $this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Sub Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA,0,',','.'),0,1,'R',false);
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Sewa Bak/VAT ('.$row->jum_vat.') ',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->VAT,0,',','.'),0,1,'R',false);
						
						
						//$this->fpdf->Line(float x1, float y1, float x2, float y2);
						$this->fpdf->Ln(3);
						//$this->fpdf->Line(50,80,180,80);
						$this->fpdf->Line(50,75,180,75);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Grand Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA + ($row->VAT),0,',','.'),0,1,'R',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(10,5,'Rp.  ',0,0,'L',false);$this->fpdf->Cell(50,10,number_format($row->BIAYA + ($row->VAT),0,',','.'),0,0,'L',true);$this->fpdf->Cell(50,5,'',0,0,'L',false);
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(75,10,$data_kontes->wilayah_kontes.','.$this->tanggal(date('Y-m-d')),0,1,'C',false);
						
						if($row->status_lunas == "")
						{
							$this->fpdf->SetFont('Arial','B',10);
							$this->fpdf->Cell(100,5,'Pembayaran : Belum Lunas',0,1,'L',false);
						}
						else
						{
							$this->fpdf->SetFont('Arial','B',10);
							$this->fpdf->Cell(100,5,'Pembayaran : '.$row->status_lunas.'('.$row->cara_bayar.')',0,1,'L',false);
						}
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(20,5,'User Input  ',0,0,'L',false);$this->fpdf->Cell(90,5,$row->nama_karyawan,0,1,'L',false);
						
						
						
						$this->fpdf->Cell(200,5,'',0,1,'L',false);
						
						//$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,$data_kontes->ketua_pelaksana,'I',1,'C',false);
						$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,"ANANG",'I',1,'C',false);
						$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,'BENDAHARA',0,1,'C',false);
						
						$this->fpdf->SetFont('Arial','I',10);
						$this->fpdf->Cell(110,5,'No Rekening Resmi : 3770 2238 30 - BCA Atas nama REGGI AGUSTIAN',0,1,'C',false);
						$no++;
					}
				}
				else
				{
					echo'<center>';
					echo'Tidak Ada Data Yang Ditampilkan !';
					echo'</center>';
				}
			//MEMULAI LOOPIING
			
			$this->fpdf->Output($data_member->nama_member.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-member');
		}
	}
	
	
	public function print_kwitansi_pemilik_looping_satu_nomor_reg()
	{
		$nama_member = $_GET['nama_member'];
		$id_h_pendaftaran = $_GET['registrasi'];
		// echo $id_member;
		//DATA MEMBER
			$data_member = $this->M_member->get_member_row('nama_member',$nama_member);
		//DATA MEMBER
		if(!empty($data_member))
		{
			//DATA KONTES
				$data_kontes = $this->M_kontes->get_default_kontes();
			//DATA KONTES
			
			//DATA HEADER PENDAFTARAN MEMBER
				$data_h_pendaftaran_member = $this->M_h_pendaftaran->list_h_pendaftaran_limit(" WHERE B.nama_pemilik = '".$data_member->nama_member."' AND A.id_h_pendaftaran = '".$id_h_pendaftaran."' AND A.id_kontes = '".$data_kontes->id_kontes."'",10000,0);
			//DATA HEADER PENDAFTARAN MEMBER
			
			
			//MEMULAI LOOPIING
				if(!empty($data_h_pendaftaran_member))
				{
					$list_result = $data_h_pendaftaran_member->result();
					$no =1;
					foreach($list_result as $row)
					{	
						
						$this->fpdf->AddPage();
						$this->fpdf->Image('assets/global/kontes/'.$data_kontes->avatar,10,7,50,10);
					
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Ln(3);
						$this->fpdf->SetY(7); //Set posisi top
						
						$this->fpdf->Cell(120,5,'',0,0,'L',false);
						$x = $this->fpdf->GetX();
						$this->fpdf->Cell(100,5,$data_kontes->nama_kontes,0,1,'L',false);
						$this->fpdf->Cell(120,5,'',0,0,'L',false);$this->fpdf->Cell(100,5,$data_kontes->mulai.' - '.$data_kontes->sampai,0,1,'L',false);
						
						$this->fpdf->Cell(180,5,'',0,1,'L',false);
						$this->fpdf->Cell(180,5,'KWITANSI PENDAFTARAN (Pemilik)',0,1,'C',false);
						
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(32,5,'NO REG',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$row->no_h_pendaftaran,0,1,'L',false);
						$this->fpdf->Cell(32,5,'Telah Terima Dari  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$data_member->nama_member,0,1,'L',false);
						$this->fpdf->Cell(32,5,'Handling',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,$row->nama_handling,0,1,'L',false);
						
						
						//$this->fpdf->Ln(8);
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->SetDrawColor(0,0,0);
						$this->fpdf->SetFillColor(224,224,224);
						$this->fpdf->SetTextColor(0,0,0);
						
						$this->fpdf->Cell(32,5,'Uang Sejumlah  ',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(150,10,$this->Terbilang($row->BIAYA + ($row->VAT)),0,1,'L',true);
						
						
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(32,5,'Untuk Pembayaran',0,0,'L',false);$this->fpdf->Cell(5,5,' : ',0,0,'L',false);$this->fpdf->Cell(150,5,'Pendaftaran ikan sebanyak '.$row->CNT.' Ekor',0,1,'L',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(200,5,'Rincian Biaya',0,1,'C',false);
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Daftar '.$row->CNT.' Ekor',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA,0,',','.'),0,1,'R',false);
						
						//$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Diskon 0%',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,'0',0,1,'R',false);
						
						
						
						// $this->fpdf->Ln(3);
						// $this->fpdf->Line(50,70,180,70);
						
						// $this->fpdf->SetFont('Arial','B',10);
						// $this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Sub Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA,0,',','.'),0,1,'R',false);
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Sewa Bak/VAT ('.$row->jum_vat.') ',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->VAT,0,',','.'),0,1,'R',false);
						
						
						//$this->fpdf->Line(float x1, float y1, float x2, float y2);
						$this->fpdf->Ln(3);
						//$this->fpdf->Line(50,80,180,80);
						$this->fpdf->Line(50,75,180,75);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(50,5,'',0,0,'L',false);$this->fpdf->Cell(35,5,'Grand Total',0,0,'L',false);$this->fpdf->Cell(5,5,' : Rp.',0,0,'L',false);$this->fpdf->Cell(50,5,number_format($row->BIAYA + ($row->VAT),0,',','.'),0,1,'R',false);
						
						$this->fpdf->SetFont('Arial','B',10);
						$this->fpdf->Cell(10,5,'Rp.  ',0,0,'L',false);$this->fpdf->Cell(50,10,number_format($row->BIAYA + ($row->VAT),0,',','.'),0,0,'L',true);$this->fpdf->Cell(50,5,'',0,0,'L',false);
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(75,10,$data_kontes->wilayah_kontes.','.$this->tanggal(date('Y-m-d')),0,1,'C',false);
						
						if($row->status_lunas == "")
						{
							$this->fpdf->SetFont('Arial','B',10);
							$this->fpdf->Cell(100,5,'Pembayaran : Belum Lunas',0,1,'L',false);
						}
						else
						{
							$this->fpdf->SetFont('Arial','B',10);
							$this->fpdf->Cell(100,5,'Pembayaran : '.$row->status_lunas.'('.$row->cara_bayar.')',0,1,'L',false);
						}
						
						$this->fpdf->SetFont('Arial','',10);
						$this->fpdf->Cell(20,5,'User Input  ',0,0,'L',false);$this->fpdf->Cell(90,5,$row->nama_karyawan,0,1,'L',false);
						
						
						
						$this->fpdf->Cell(200,5,'',0,1,'L',false);
						
						//$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,$data_kontes->ketua_pelaksana,'I',1,'C',false);
						$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,"ANANG",'I',1,'C',false);
						$this->fpdf->Cell(110,5,'',0,0,'L',false);$this->fpdf->Cell(75,5,'BENDAHARA',0,1,'C',false);
						
						$this->fpdf->SetFont('Arial','I',10);
						$this->fpdf->Cell(110,5,'No Rekening Resmi : 3770 2238 30 - BCA Atas nama REGGI AGUSTIAN',0,1,'C',false);
						$no++;
					}
				}
				else
				{
					echo'<center>';
					echo'Tidak Ada Data Yang Ditampilkan !';
					echo'</center>';
				}
			//MEMULAI LOOPIING
			
			$this->fpdf->Output($data_member->nama_member.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-member');
		}
	}
	
	public function rooter_sertifikat()
	{
		$id_juara = $_GET['id_juara'];
		$nama_juara = $_GET['nama_juara'];
		if(strpos($nama_juara,'BREEDER') > 0)
		{
			$this->print_sertifikat_breeder($id_juara);
		}
		else
		{
			$this->print_sertifikat($id_juara);
		}
	}
	
	public function print_sertifikat($id_juara)
	{
		$id_juara = $id_juara; //$_GET['id_juara'];
		// echo $id_member;
		//DATA JUARA
			$data_juara = $this->M_penutupan->list_juara_penutupan_limit(" WHERE A.id_juara = '".$id_juara."' " ,1,0);
		//DATA JUARA
		if(!empty($data_juara))
		{
			//DATA JUARA
				$data_juara = $data_juara->row();
			//DATA JUARA
			
			
			//HEADER
				$this->fpdf->AddPage('L','A4');
				
				
				if ($data_juara->avatar == "")
				{
					$src = base_url().'assets/global/ikan/loading.gif';
				}
				else
				{
					$src = base_url().'assets/global/ikan/'.$data_juara->avatar;
					$this->fpdf->Image('assets/global/ikan/'.$data_juara->avatar,50,50,75,125);
					
				}
				
			
				$this->fpdf->SetFont('times','B',15);
				$this->fpdf->Ln(3);
				$this->fpdf->SetY(50); //Set posisi top
				
				
				$this->fpdf->Cell(140,10,'',0,0,'L',false);$this->fpdf->Cell(50,10,'Menerangkan Bahwa :',0,1,'L',false);
				
				$this->fpdf->Cell(140,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Varietas',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(50,10,$data_juara->nama_jikan,0,1,'L',false);
				
				$this->fpdf->Cell(140,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Ukuran',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(50,10,$data_juara->nama_uikan,0,1,'L',false);
				
				$this->fpdf->Cell(140,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Gelar',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(100,10,$data_juara->nama_juara,0,1,'L',false);
				
				$this->fpdf->Cell(210,10,'',0,1,'L',false);
				
				$this->fpdf->Cell(130,10,'',0,0,'L',false);$this->fpdf->Cell(150,10,'Pada Acara 9th Sukabumi Nishikigoi Show',0,1,'C',false);
				$this->fpdf->Cell(130,10,'',0,0,'L',false);$this->fpdf->Cell(150,10,'Gedung "Yayasan Al-Masthuriyah',0,1,'C',false);
				$this->fpdf->Cell(130,10,'',0,0,'L',false);$this->fpdf->Cell(150,10,'03 - 04 November 2017',0,1,'C',false);
				
				
				/*$this->fpdf->Cell(210,10,'',0,1,'L',false);
				$this->fpdf->Cell(130,10,'',0,0,'L',false);$this->fpdf->Cell(75,10,'Penyelenggara',0,0,'C',false);$this->fpdf->Cell(75,10,'Juri',0,1,'C',false);
				
				$this->fpdf->Cell(210,15,'',0,1,'L',false);
				$this->fpdf->Cell(130,10,'',0,0,'L',false);$this->fpdf->Cell(75,10,'(MISBAH)',0,0,'C',false);$this->fpdf->Cell(75,10,'(Reggi Mubarok',0,1,'C',false);*/
				
				$this->fpdf->Image('assets/global/images/ttd.png',150,130,50,50);
				$this->fpdf->Image('assets/global/images/ttd2.png',220,130,50,50);
				
			//HEADER
			
			$this->fpdf->Output($data_juara->no_d_pendaftaran.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-laporan-juara');
		}
	}
	
	
	public function print_sertifikat_breeder($id_juara)
	{
		$id_juara = $id_juara; //$_GET['id_juara'];
		// echo $id_member;
		//DATA JUARA
			$data_juara = $this->M_penutupan->list_juara_penutupan_limit(" WHERE A.id_juara = '".$id_juara."' " ,1,0);
		//DATA JUARA
		if(!empty($data_juara))
		{
			//DATA JUARA
				$data_juara = $data_juara->row();
			//DATA JUARA
			
			
			//HEADER
				$this->fpdf->AddPage('L','Legal');
				
				
				if ($data_juara->avatar == "")
				{
					$src = base_url().'assets/global/ikan/loading.gif';
				}
				else
				{
					$src = base_url().'assets/global/ikan/'.$data_juara->avatar;
					$this->fpdf->Image('assets/global/ikan/'.$data_juara->avatar,30,50,100,125);
					
				}
				
			
				$this->fpdf->SetFont('times','B',15);
				$this->fpdf->Ln(3);
				$this->fpdf->SetY(60); //Set posisi top
				
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(150,10,'Menerangkan Bahwa :',0,1,'C',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Varietas',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(50,10,$data_juara->nama_jikan,0,1,'L',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Ukuran',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(50,10,$data_juara->nama_uikan,0,1,'L',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Gelar',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(100,10,$data_juara->nama_juara,0,1,'L',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Pemilik',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(100,10,$data_juara->nama_pemilik,0,1,'L',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Showroom',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(100,10,$data_juara->showroom,0,1,'L',false);
				
				$this->fpdf->Cell(160,10,'',0,0,'L',false);$this->fpdf->Cell(30,10,'Breeder',0,0,'L',false);$this->fpdf->Cell(5,10,':',0,0,'L',false);$this->fpdf->Cell(100,10,$data_juara->breeder,0,1,'L',false);
				
				$this->fpdf->Cell(230,10,'',0,1,'L',false);
				
				$this->fpdf->Cell(150,5,'',0,0,'L',false);$this->fpdf->Cell(150,5,'Pada Acara 7th Sukabumi Breeder KOI Show 2017',0,1,'C',false);
				$this->fpdf->Cell(150,5,'',0,0,'L',false);$this->fpdf->Cell(150,5,'Gedung "Yayasan Al-Masthuriyah',0,1,'C',false);
				$this->fpdf->Cell(150,5,'',0,0,'L',false);$this->fpdf->Cell(150,5,'03 - 04 November 2017',0,1,'C',false);
				
				
				/*$this->fpdf->Cell(230,10,'',0,1,'L',false);
				$this->fpdf->Cell(150,10,'',0,0,'L',false);$this->fpdf->Cell(75,10,'Penyelenggara',0,0,'C',false);$this->fpdf->Cell(75,10,'Juri',0,1,'C',false);
				
				$this->fpdf->Cell(230,15,'',0,1,'L',false);
				$this->fpdf->Cell(150,10,'',0,0,'L',false);$this->fpdf->Cell(75,10,'(MISBAH)',0,0,'C',false);$this->fpdf->Cell(75,10,'(Reggi Mubarok',0,1,'C',false);*/
				
				$this->fpdf->Image('assets/global/images/ttd.png',150,155,50,50);
				$this->fpdf->Image('assets/global/images/ttd2.png',265,155,50,50);
				
			//HEADER
			
			$this->fpdf->Output($data_juara->no_d_pendaftaran.'.pdf','D');
		}
		else
		{
			header('Location: '.base_url().'admin-laporan-juara');
		}
	}
	
	public function print_label()
	{
		// ob_start();
		// $data['siswa'] = $this->siswa_model->view_row();
		// $this->load->view('print', $data);
		// $html = ob_get_contents();
		// ob_end_clean();
		
		// require_once('./assets/html2pdf/html2pdf.class.php');
		// $pdf = new HTML2PDF('P','A4','en');
		// $pdf->WriteHTML($html);
		// $pdf->Output('Data Siswa.pdf', 'D');
		
		//ob_start();
		
		$id_kontes = $this->M_kontes->get_default_kontes()->id_kontes;
		if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
		{
			$cari = "WHERE A.id_kontes = '".$id_kontes."' AND A.id_h_pendaftaran = '".$_GET['id_h_pendaftaran']."' AND (A.no_d_pendaftaran LIKE '%".str_replace("'","",$_GET['cari'])."%' OR COALESCE(B.nama_pemilik,'') LIKE '%".str_replace("'","",$_GET['cari'])."%' OR A.nama_handling LIKE '%".str_replace("'","",$_GET['cari'])."%')";
		}
		else
		{
			$cari = "WHERE A.id_kontes = '".$id_kontes."' AND A.id_h_pendaftaran = '".$_GET['id_h_pendaftaran']."'";
		}
		$data_d_pendaftaran = $this->M_d_pendaftaran->list_d_pendaftaran_limit($cari,10000,0);
		$data_kontes = $this->M_kontes->get_default_kontes();
		$data = array('data_d_pendaftaran'=>$data_d_pendaftaran,'data_kontes'=>$data_kontes);
		$this->load->view('admin/page/king_admin_print_lap_ikan.html',$data);
		
		/*$html = ob_get_contents();
		ob_end_clean();
		
		require_once('./assets/html2pdf/html2pdf.class.php');
		$pdf = new HTML2PDF('P','A4','en');
		$pdf->WriteHTML($html);
		$pdf->Output('Data Siswa.pdf', 'D');*/
	}
	
	
	public function print_form()
	{
		// ob_start();
		// $data['siswa'] = $this->siswa_model->view_row();
		// $this->load->view('print', $data);
		// $html = ob_get_contents();
		// ob_end_clean();
		
		// require_once('./assets/html2pdf/html2pdf.class.php');
		// $pdf = new HTML2PDF('P','A4','en');
		// $pdf->WriteHTML($html);
		// $pdf->Output('Data Siswa.pdf', 'D');
		
		//ob_start();
		
		$id_kontes = $this->M_kontes->get_default_kontes()->id_kontes;
		if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
		{
			$cari = "WHERE A.id_kontes = '".$id_kontes."' AND A.id_h_pendaftaran = '".$_GET['id_h_pendaftaran']."' AND (A.no_d_pendaftaran LIKE '%".str_replace("'","",$_GET['cari'])."%' OR COALESCE(B.nama_pemilik,'') LIKE '%".str_replace("'","",$_GET['cari'])."%' OR A.nama_handling LIKE '%".str_replace("'","",$_GET['cari'])."%')";
		}
		else
		{
			$cari = "WHERE A.id_kontes = '".$id_kontes."' AND A.id_h_pendaftaran = '".$_GET['id_h_pendaftaran']."'";
		}
		$data_d_pendaftaran = $this->M_d_pendaftaran->list_d_pendaftaran_limit($cari,10000,0);
		$data_kontes = $this->M_kontes->get_default_kontes();
		$data = array('data_d_pendaftaran'=>$data_d_pendaftaran,'data_kontes'=>$data_kontes);
		$this->load->view('admin/page/king_admin_print_form.html',$data);
		
		/*$html = ob_get_contents();
		ob_end_clean();
		
		require_once('./assets/html2pdf/html2pdf.class.php');
		$pdf = new HTML2PDF('P','A4','en');
		$pdf->WriteHTML($html);
		$pdf->Output('Data Siswa.pdf', 'D');*/
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_pdf.php */
