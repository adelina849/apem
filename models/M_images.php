<?php
	class M_images extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_images_limit($id,$group,$cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_images 
										WHERE id = '". $id ."' 
										AND group_by = '". $group ."'
										".$cari." ORDER BY tgl_ins ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_images_limit($id,$group,$cari)
		{
			$query = $this->db->query("
										SELECT COUNT(id) AS JUMLAH 
										FROM tb_images 
										WHERE id = '". $id ."' 
										AND group_by = '". $group ."'
										".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id
			,$group_by
			,$nama
			,$foto
			,$foto_url
			,$ket
			,$kode_kantor
			,$user_updt
		)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id' => $id,
			   'group_by' => $group_by,
			   'img_nama' => $nama,
			   'img_file' => $foto,
			   'img_url' => $foto_url,
			   'ket_img' => $ket,
			   'tgl_ins' => $jam,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_images', $data); 
		}
		
		function edit_with_image
		(
			$id_images
			,$id
			,$group_by
			,$nama
			,$foto
			,$foto_url
			,$ket
			,$user_updt
		)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id' => $id,
			   'group_by' => $group_by,
			   'img_nama' => $nama,
			   'img_file' => $foto,
			   'img_url' => $foto_url,
			   'ket_img' => $ket,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			//$this->db->where('id_images', $id_images);
			$this->db->update('tb_images', $data, array('id_images' => $id_images,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function edit_no_image
		(
			$id_images
			,$id
			,$group_by
			,$nama
			,$ket
			,$user_updt
		)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id' => $id,
			   'group_by' => $group_by,
			   'img_nama' => $nama,
			   'ket_img' => $ket,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt,
			);
			
			//$this->db->where('id_images', $id_images);
			$this->db->update('tb_images', $data, array('id_images' => $id_images,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_images WHERE id_images = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		function get_images($id,$group_by,$berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_images', array('id'=>$id,'group_by'=>$group_by,$berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function get_images_ajax($id,$group_by,$berdasarkan,$cari,$kode_kantor)
        {
            $query = $this->db->get_where('tb_images', array('id'=>$id,'group_by'=>$group_by,$berdasarkan => $cari,'kode_kantor' => $kode_kantor));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function do_upload($id,$cek_bfr)
		{
			$this->load->library('upload');

			if($cek_bfr != '')
			{
				@unlink('./assets/global/images/'.$cek_bfr);
			}
			
			if (!empty($_FILES['foto']['name']))
			{
				$config['upload_path'] = 'assets/global/images/';
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
?>