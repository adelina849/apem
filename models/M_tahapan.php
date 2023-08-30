<?php
	class M_tahapan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_tahapan()
		{
			$query = $this->db->query("SELECT * FROM tb_tahapan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_tahapan ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_tahapan_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_tahapan ".$cari." ORDER BY tgl_ins ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_tahapan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_tahapan) AS JUMLAH FROM tb_tahapan ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan(
			$nama_tahapan
			,$ket_tahapan
			,$user_updt
			,$kode_kantor
			,$status_kantor
		)
		{
			/*$data = array
			(
			   'nama_jenis_naskah' => $nama,
			   'syarat_jenis_naskah' => $ket,
			   'ket_jabatan' => $ket,
			   'user' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_jabatan', $data); */
			
			$query = "
				INSERT INTO tb_tahapan (
					nama_tahapan
					,ket_tahapan
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					'".$nama_tahapan."'
					,'".$ket_tahapan."'
					,NOW()
					,NOW()
					,'".$user_updt."'
					,'".$kode_kantor."'
					,'".$status_kantor."'
				);
			";
			
			$this->db->query($query);
		}
		
		function edit(
			$id_tahapan
			,$nama_tahapan
			,$ket_tahapan
			,$user_updt)
		{
			/*$data = array
			(
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user
			);
			
			$this->db->update('tb_jabatan', $data,array('id_jabatan' => $id,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));*/
			
			$query = "
				UPDATE tb_tahapan SET 
					nama_tahapan = '".$nama_tahapan."'
					,ket_tahapan = '".$ket_tahapan."'
					,tgl_updt = NOW()
					,user_updt = '".$user_updt."'
				WHERE id_tahapan = '".$id_tahapan."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_tahapan WHERE id_tahapan = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		
		function get_tahapan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_tahapan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
	}
?>