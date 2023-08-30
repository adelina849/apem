<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_pilih_tahapan_naskah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_tahapan','M_tahapan_naskah','M_jenis_naskah'));
		
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
				/*if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = str_replace("'","",$_GET['cari']);
				}
				else
				{
					$cari = "";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-tahapan-dokumen?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-tahapan-dokumen/');
				$config['total_rows'] = $this->M_tahapan_naskah->count_tahapan_naskah_limit($cari)->JUMLAH;
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
				
				
				$this->pagination->initialize($config);
				$halaman = $this->pagination->create_links();
				$list_tahapan_naskah = $this->M_tahapan_naskah->list_tahapan_naskah_limit($cari,$config['per_page'],$this->uri->segment(2,0));
				$data = array('page_content'=>'king_admin_tahapan_naskah','halaman'=>$halaman,'list_tahapan_naskah'=>$list_tahapan_naskah);
				$this->load->view('admin/container',$data);*/
				
				$id_jenis_naskah = $this->uri->segment(2,0);
				$data_jenis_naskah = $this->M_jenis_naskah->get_jenis_naskah('id_jenis_naskah',$id_jenis_naskah);
				if(!empty($data_jenis_naskah))
				{
					$data_jenis_naskah = $data_jenis_naskah->row();
					$list_tahapan = $this->M_tahapan_naskah->list_tahapan_all_to_naskah($id_jenis_naskah);
					$list_tahapan_sdh_pilih = $this->M_tahapan_naskah->list_tahapan_naskah_sdh_pilih($id_jenis_naskah);
					if(!empty($list_tahapan_sdh_pilih))
					{
						$jum_tahapan_sdh_pilih = $list_tahapan_sdh_pilih->num_rows();
					}
					else
					{
						$jum_tahapan_sdh_pilih = 0;
					}
					
					
					$data = array('page_content'=>'king_admin_pilih_tahapan_naskah','id_jenis_naskah' => $id_jenis_naskah,'data_jenis_naskah' => $data_jenis_naskah,'list_tahapan' => $list_tahapan,'list_tahapan_sdh_pilih' => $list_tahapan_sdh_pilih,'jum_tahapan_sdh_pilih' => $jum_tahapan_sdh_pilih);
					$this->load->view('admin/container',$data);
				}
				else
				{
					header('Location: '.base_url().'admin-tahapan-dokumen');
				}
				
				
			}
			else
			{
				header('Location: '.base_url().'admin-login');
			}
		}
	}
	
	function simpan()
	{
		$id_jenis_naskah = $_POST['id_jenis_naskah'];
		$id_tahapan = $_POST['id_tahapan'];
		//echo $id_jenis_naskah.''.$id_tahapan;
		$cek_tahapan_naskah = $this->M_tahapan_naskah->get_tahapan_naskah_from_naskah_dn_tahapan($id_jenis_naskah,$id_tahapan);
		if(!empty($cek_tahapan_naskah))
		{
			$cek_tahapan_naskah = $cek_tahapan_naskah->row();
			$this->M_tahapan_naskah->hapus($cek_tahapan_naskah->id_tahapan_naskah);
		}
		else
		{
			$this->M_tahapan_naskah->simpan
			(
			$id_jenis_naskah
			,$id_tahapan
			,$this->session->userdata('ses_id_karyawan')
			,$this->session->userdata('ses_kode_kantor')
			,'KAB'
			);
		}
		
		$list_tahapan_sdh_pilih = $this->M_tahapan_naskah->list_tahapan_naskah_sdh_pilih($id_jenis_naskah);
		
			if(!empty($list_tahapan_sdh_pilih))
			{
					$list_result = $list_tahapan_sdh_pilih->result();
					$no2 =1;
					$jum_tahapan_sdh_pilih = $list_tahapan_sdh_pilih->num_rows();
					echo'<input type="hidden" name="jum_tahapan" id="jum_tahapan" value="'.$jum_tahapan_sdh_pilih.'"/>';
					echo'<table class="table table-hover">
									<tr>
									  <th width="10%" style="text-align:center;">No</th>
									  <th width="80%" style="text-align:center;">Nama Tahapan</th>
									  <th width="10%" style="text-align:center;">Urutan</th>
									</tr>';
					foreach($list_result as $row)
					{
						echo'<tr>';
							echo'<td>'.$no2.'</td>';
							echo'<td>'.$row->nama_tahapan.'</td>';
							echo'<td><input size="3" type="text" id="ordr_index_'.$no2.'" name="ordr_index_'.$no2.'" value="'.$no2.'" /></td>';
							echo'<input type="hidden" id="id_tahapan2_'.$no2.'" name="id_tahapan2_'.$no2.'" value="'.$row->id_tahapan.'" />';
							
							//echo'<td>'.$row->nama_tahapan.'</td>';
						echo'</tr>';
						$no2++;
					}
					echo'</table>';
					
			}
		
		
	}
	
	function update()
	{
		//echo $_POST['jum_tahapan'];
		$id_jenis_naskah = $_POST['id_jenis_naskah'];
		$jum_tahapan = $_POST['jum_tahapan'];
		for($i=1;$i<=$jum_tahapan;$i++)
		{
			//echo $_POST['id_tahapan2_'.$i].'<br/>';
			$this->M_tahapan_naskah->edit(
			$id_jenis_naskah
			,$_POST['id_tahapan2_'.$i]
			,$_POST['ordr_index_'.$i]);
			//echo $_POST['id_tahapan2_'.$i];
		}
		header('Location: '.base_url().'admin-pilih-tahapan-dokumen/'.$id_jenis_naskah);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/c_admin_tahapan_naskah.php */