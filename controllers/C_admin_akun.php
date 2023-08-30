<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_akun extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_karyawan','M_akun','M_pertanyaan'));
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
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
				$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND B.nama_karyawan LIKE '%".str_replace("'","",$_GET['cari'])."%'";
				}
				else
				{
				$cari = "WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-akun?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-akun/');
				$config['total_rows'] = $this->M_akun->count_akun_limit($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
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
				$list_pertanyaan1 = $this->M_pertanyaan->list_pertanyaan(1);
				$list_pertanyaan2 = $this->M_pertanyaan->list_pertanyaan(2);
				$list_karyawan = $this->M_karyawan->list_karyawan_no_akun('',10,0);
				$list_akun = $this->M_akun->list_akun_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'king_admin_akun','list_karyawan'=>$list_karyawan,'list_pertanyaan1'=>$list_pertanyaan1,'list_pertanyaan2'=>$list_pertanyaan2,'list_akun'=>$list_akun,'halaman'=>$halaman);
				$this->load->view('admin/container',$data);
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
			$this->M_akun->edit
			(
				$_POST['stat_edit'],
				$_POST['id_karyawan'],
				$_POST['pertanyaan1'],
				$_POST['jawaban1'],
				$_POST['pertanyaan2'],
				$_POST['jawaban2'],
				$_POST['user'],
				MD5($_POST['pass']),
				$_POST['keterangan'],
				$this->session->userdata('ses_kode_kantor'),
				1
			);
			header('Location: '.base_url().'admin-akun');
		}
		else
		{
			$this->M_akun->simpan
			(
				$_POST['id_karyawan'],
				$_POST['pertanyaan1'],
				$_POST['jawaban1'],
				$_POST['pertanyaan2'],
				$_POST['jawaban2'],
				$_POST['user'],
				MD5($_POST['pass']),
				$_POST['keterangan'],
				$this->session->userdata('ses_kode_kantor'),
				1
			);
			header('Location: '.base_url().'admin-akun');
		}
	}
	
	function cek_table_karyawan()
	{
		if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		{
			$cari = ' AND nama_karyawan LIKE "%'.$_POST['cari'].'%"';
		}
		else
		{
			$cari='';
		}
		
		$list_karyawan = $this->M_karyawan->list_karyawan_no_akun($cari,10,0);
		if(!empty($list_karyawan))
		{
			echo'<table width="100%" id="example2" class="table table-bordered table-hover">';
			echo '<thead>
	<tr>';
			echo '<th width="5%">No</th>';
			echo '<th width="15%">Avatar</th>';
			echo '<th width="20%">NIK</th>';
			echo '<th width="35%">Nama</th>';
			echo '<th width="20%">Jabatan</th>';
			echo '<th width="5%">Aksi</th>';
			echo '</tr>
	</thead>';
			$list_result = $list_karyawan->result();
			$no =1;
			echo '<tbody>';
			foreach($list_result as $row)
			{
				echo'<tr>';
				echo'<td><input type="hidden" id="no_'.$row->id_karyawan.'" value="'.$row->id_karyawan.'" />'.$no.'</td>';
				if ($row->avatar == "")
				{
					$src = base_url().'assets/global/karyawan/loading.gif';
					echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
					
					echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
				}
				else
				{
					$src = base_url().'assets/global/karyawan/'.$row->avatar;
					echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
					
					echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
				}
				echo'<td><input type="hidden" id="nik_'.$row->id_karyawan.'" value="'.$row->nik_karyawan.'" />'.$row->nik_karyawan.'</td>';
				echo'<td><input type="hidden" id="nama_'.$row->id_karyawan.'" value="'.$row->nama_karyawan.'" />'.$row->nama_karyawan.'</td>';
				
				echo'<td><input type="hidden" id="nama_jabatan_'.$row->id_karyawan.'" value="'.$row->nama_jabatan.'" />'.$row->nama_jabatan.'</td>';
				echo'<input type="hidden" id="id_jabatan_'.$row->id_karyawan.'" value="'.$row->id_jabatan.'" />';
				
				echo'<td>
	<button type="button" onclick="insert('.$row->id_karyawan.')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Pilih</button>
	</td>';
				
				echo'</tr>';
				$no++;
			}
			
			echo '</tbody>';
			echo'</table>';
		}
	}
	
	
	// function cek_table_karyawan_ALL()
	// {
		// if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		// {
			// $cari = ' WHERE A.nama_karyawan LIKE "%'.$_POST['cari'].'%"';
		// }
		// else
		// {
			// $cari='';
		// }
		
		// $list_karyawan = $this->M_karyawan->list_karyawan_limit($cari,10,0);
		// if(!empty($list_karyawan))
		// {
			// echo'<table width="100%" id="example2" class="table table-bordered table-hover">';
			// echo '<thead>
	// <tr>';
			// echo '<th width="5%">No</th>';
			// echo '<th width="15%">Avatar</th>';
			// echo '<th width="20%">NIK</th>';
			// echo '<th width="35%">Nama</th>';
			// echo '<th width="20%">Jabatan</th>';
			// echo '<th width="5%">Aksi</th>';
			// echo '</tr>
	// </thead>';
			// $list_result = $list_karyawan->result();
			// $no =1;
			// echo '<tbody>';
			// foreach($list_result as $row)
			// {
				// echo'<tr>';
				// echo'<td><input type="hidden" id="no_'.$row->id_karyawan.'" value="'.$row->id_karyawan.'" />'.$no.'</td>';
				// if ($row->avatar == "")
				// {
					// $src = base_url().'assets/global/karyawan/loading.gif';
					// echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
					
					// echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
				// }
				// else
				// {
					// $src = base_url().'assets/global/karyawan/'.$row->avatar;
					// echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
					
					// echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
				// }
				// echo'<td><input type="hidden" id="nik_'.$row->id_karyawan.'" value="'.$row->nik_karyawan.'" />'.$row->nik_karyawan.'</td>';
				// echo'<td><input type="hidden" id="nama_'.$row->id_karyawan.'" value="'.$row->nama_karyawan.'" />'.$row->nama_karyawan.'</td>';
				
				// echo'<td><input type="hidden" id="nama_jabatan_'.$row->id_karyawan.'" value="'.$row->nama_jabatan.'" />'.$row->nama_jabatan.'</td>';
				// echo'<input type="hidden" id="id_jabatan_'.$row->id_karyawan.'" value="'.$row->id_jabatan.'" />';
				
				// echo'<td>
	// <button type="button" onclick="insert('.$row->id_karyawan.')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Pilih</button>
	// </td>';
				
				// echo'</tr>';
				// $no++;
			// }
			
			// echo '</tbody>';
			// echo'</table>';
		// }
	// }
	
	function cek_table_karyawan_ALL()
	{
		if((!empty($_POST['cari'])) && ($_POST['cari']!= "")  )
		{
			$cari = " WHERE  A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND (A.nama_karyawan LIKE '%".$_POST['cari']."%' OR A.no_karyawan LIKE '%".$_POST['cari']."%') ";
		}
		else
		{
			$cari= " WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ";
		}
		
		$list_karyawan = $this->M_karyawan->list_karyawan_limit($cari,10,0);
		if(!empty($list_karyawan))
		{
			echo'<table width="100%" id="example2" class="table table-bordered table-hover">';
				echo '<thead>
<tr>';
							echo '<th width="5%">No</th>';
							echo '<th width="15%">Avatar</th>';
							echo '<th width="20%">No ID Karyawan</th>';
							echo '<th width="35%">Nama</th>';
							echo '<th width="20%">Jabatan</th>';
							echo '<th width="5%">Aksi</th>';
				echo '</tr>
</thead>';
				$list_result = $list_karyawan->result();
				$no =1;
				echo '<tbody>';
				foreach($list_result as $row)
				{
					echo'<tr>';
						echo'<td><input type="hidden" id="no_'.$row->id_karyawan.'" value="'.$row->id_karyawan.'" />'.$no.'</td>';
						if ($row->avatar == "")
						{
							$src = base_url().'assets/global/karyawan/loading.gif';
							echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
							
							echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
						}
						else
						{
							$src = base_url().'assets/global/karyawan/'.$row->avatar;
							echo '<td><img id="img_'.$row->id_karyawan.'"  width="75px" height="75px" style="border:1px solid #C8C8C8; padding:5px; float:left; margin-right:20px;" src="'.$src.'" /></td>';
							
							echo'<input type="hidden" id="avatar_url_'.$row->id_karyawan.'" value="'.$src.'" />';
						}
						
						
						echo'<input type="hidden" id="nik_'.$row->id_karyawan.'" value="'.$row->nik_karyawan.'" />';
						
						echo'<td><input type="hidden" id="no_karyawan_'.$row->id_karyawan.'" value="'.$row->no_karyawan.'" />'.$row->no_karyawan.'</td>';
						echo'<td><input type="hidden" id="nama_'.$row->id_karyawan.'" value="'.$row->nama_karyawan.'" />'.$row->nama_karyawan.'</td>';
						
						
						echo'<td><input type="hidden" id="nama_jabatan_'.$row->id_karyawan.'" value="'.$row->nama_jabatan.'" />'.$row->nama_jabatan.'</td>';
						echo'<input type="hidden" id="id_jabatan_'.$row->id_karyawan.'" value="'.$row->id_jabatan.'" />';
						
						echo'<td>
<button type="button" onclick="insert('.$row->id_karyawan.')" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Pilih</button>
</td>';
						
					echo'</tr>';
					$no++;
				}
				
				echo '</tbody>';
			echo'</table>';
		}
	}
	
	function cek_akun()
	{
		$hasil_cek = $this->M_akun->get_akun('user',$_POST['user']);
		echo $hasil_cek;
	}
	
	function cek_login()
	{
		$hasil_cek = $this->M_akun->get_login($_POST['user'],$_POST['pass']);
		echo $hasil_cek;
	}
	
	public function hapus()
	{
		$id = $this->uri->segment(2,0);
		$this->M_akun->hapus($id);
		header('Location: '.base_url().'admin-akun');
	}
}

/* End of file c_admin_akun.php */
/* Location: ./application/controllers/c_admin_akun.php */