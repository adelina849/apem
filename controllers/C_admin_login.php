<?php

	Class C_admin_login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(array('captcha','array'));
            $this->load->library(array('form_validation'));
            $this->config->load('cap');
		}
		
		function index()
		{
			// $this->auto_remove_captcha();
			// $user = $this->input->post('user');
			// $pass = $this->input->post('pass');
			
            // if($this->validasi_input_captcha() == false)
            // {
                // $data = array();
				
				// $text = ucfirst(random_element($this->config->item('captcha_word')));
					// $number = random_element($this->config->item('captcha_num'));
					// $word = $text.$number;

					// $expiration = time()-300; //batas waktu 5 menit
					// $this->db->query('DELETE FROM tb_captcha WHERE captcha_time < '.$expiration);
					// //konfigurasi captcha
					// $vals = array(
						// 'font_path' => 'system/fonts/texb.ttf',
						// 'img_path' => './assets/captcha/',
						// 'img_url' => base_url().'assets/captcha/',
						// 'img_width' => '235',
						// 'img_height' => 50,
						// 'word' => $word,
						// 'expiration' => $expiration,
						// 'time' => date('Y-m-d H:i:s')
						// );
					
					// $cap = create_captcha($vals);
					// $data['captcha_img'] = $cap['image'];
					
					 // $captcha = array(   'captcha_id' => '',
										// 'captcha_time' => date('Y-m-d H:i:s'),
										// 'ip_address' => $this->input->ip_address(),
										// 'word' => "TEST"
										// );
					 // $query = $this->db->insert_string('tb_captcha',$captcha);
					// $this->db->query($query);	
					
					
					// $this->db->query('OPTIMIZE TABLE `tb_captcha` ');
                    // $this->load->view('admin/login.html',$data);
                    
            // }
            // else
            // {
                    // $this->cek_login();
            // }
			
			
			// if(($this->session->userdata('user_admin') == null) or ($this->session->userdata('pass_admin') == null))
			// {
				// //redirect('index.php/admin-login','location');
				// header('Location: '.base_url().'admin-login');
			// }
			// else
			// {
				// $data_login = $this->M_a_akun->cek_login($this->session->userdata('user_admin'),md5(base64_decode($this->session->userdata('pass_admin'))),'0');
				// if (!empty($data_login))
				// {
                    // $listKategori = $this->M_kat_produk->listKategori();
                    // $listPrduk = $this->M_produk->listproduk('','');
					// $data = array('page_content'=>'adm_produk','title'=>'Elga Network | Dashboard Admin Produk','listKategori'=>$listKategori,'listPrduk'=>$listPrduk);
					// $this->load->view('admin/container',$data);
				// //}
				// // else
				// // {
					// // header('Location: '.base_url().'admin-login');
				// // }
				
				$this->load->view('admin/login.html');
				////}
			////}
		}
        
        public function cek_login()
        {
            $user = htmlentities($_POST['user'], ENT_QUOTES, 'UTF-8');
            $pass = htmlentities($_POST['pass'], ENT_QUOTES, 'UTF-8');
            $data_login = $this->M_akun->get_login($user,md5($pass));
    		if(!empty($data_login))
    		{
                if ($data_login->avatar <> "")
                {
                    $src = $data_login->avatar_url;
                }
                else
                {
                	$src = base_url().'assets/global/users/loading.gif';
                }
				
				$user = array(
					'ses_user_admin'  => $user,
					'ses_pass_admin'  => base64_encode($pass),
					'ses_pass_admin_pure'  => ($pass),
					'ses_id_karyawan' => $data_login->id_karyawan,
					'ses_id_jabatan' => $data_login->id_jabatan,
					'ses_nama_jabatan' => $data_login->nama_jabatan,
					'ses_nama_karyawan' => $data_login->nama_karyawan,
					'ses_kode_kantor' => $data_login->kode_kantor,
					'ses_avatar_url' => $src,
					'ses_tgl_ins' => $data_login->tgl_ins
				);
				
    			
    
    			$this->session->set_userdata($user);
    			//redirect('index.php/admin','location');
				header('Location: '.base_url().'admin');
    		}
    		else
    		{
    			//redirect('index.php/login','location');
				header('Location: '.base_url().'admin-login');
    		}
        }
        
        public function validasi_input_captcha()
        {
            $this->form_validation->set_rules('captcha','Captcha','required|callback_check_captcha');
            return ($this->form_validation->run() == false)? False : true;
        }
        
        
        function logout()
		{
			$this->session->unset_userdata('ses_user_admin');
			$this->session->unset_userdata('ses_pass_admin');
			$this->session->unset_userdata('ses_id_karyawan');
            $this->session->unset_userdata('ses_id_jabatan');
			$this->session->unset_userdata('ses_nama_jabatan');
			$this->session->unset_userdata('ses_nama_karyawan');
			$this->session->unset_userdata('ses_avatar_url');
			$this->session->unset_userdata('ses_tgl_ins');
			
			//redirect('index.php/login','location');
			header('Location: '.base_url().'admin-login');
		}
        
        
        
        
        function auto_remove_captcha()
		{
			list($usec,$sec) = explode(" ",microtime());
			$now = ((float)$usec + (float)$sec);
			$expiration = 60;//10menit
			$captcha_dir = @opendir("./assets/captcha/");
			while($filename = @readdir($captcha_dir))
			{
				if($filename != "." and $filename != ".." and $filename != "index.php")
				{
					$name = str_replace(".jpg","",$filename);
					if($name+$expiration < $now)
					{
						@unlink("./assets/captcha/".$filename);
					}
				}
			}
			@closedir($captcha_dir);
			//redirect(base_url(),'localtion');
		}
        
        function check_captcha()
		{
			//batas waktu
			$expiration = time()-300;
			//hapus berkas cptcha yang kadaluarsa dalam direktori
			//hapus data captcah yang kadaluarsa pada database
			$this->db->query("DELETE FROM tb_captcha where captcha_time < ".$expiration);
			//$this->db->query("DELETE FROM tb_captcha");
			$sql = "select count(*) as count from tb_captcha where word = ? and ip_address = ? and captcha_time > ?";
			$binds = array($this->input->post('captcha'),$this->input->ip_address(),$expiration);
			
			$query = $this->db->query($sql,$binds);
			$row = $query->row();
			
			if($row->count == 0)
			{
				$this->form_validation->set_message('check_captcha','Captcha yang anda masukan salah atau sudah kadaluarsa');
				return false;
			}
			else
			{
				return true;
				
			}
		}
	}

?>