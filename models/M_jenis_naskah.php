<?php
	class M_jenis_naskah extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_jenis_naskah()
		{
			$query = $this->db->query("SELECT * FROM tb_jenis_naskah WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_jenis_naskah ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_jenis_naskah_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_jenis_naskah ".$cari." ORDER BY nama_jenis_naskah ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_jenis_naskah_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_jenis_naskah) AS JUMLAH FROM tb_jenis_naskah ".$cari);
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
			$nama_jenis_naskah
			,$syarat_jenis_naskah
			,$ket_jenis_naskah
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
				INSERT INTO tb_jenis_naskah (
					nama_jenis_naskah
					,syarat_jenis_naskah
					,ket_jenis_naskah
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					'".$nama_jenis_naskah."'
					,'".$syarat_jenis_naskah."'
					,'".$ket_jenis_naskah."'
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
			$id_jenis_naskah
			,$nama_jenis_naskah
			,$syarat_jenis_naskah
			,$ket_jenis_naskah
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
				UPDATE tb_jenis_naskah SET 
					nama_jenis_naskah = '".$nama_jenis_naskah."'
					,syarat_jenis_naskah = '".$syarat_jenis_naskah."'
					,ket_jenis_naskah = '".$ket_jenis_naskah."'
					,tgl_updt = NOW()
					,user_updt = '".$user_updt."'
				WHERE id_jenis_naskah = '".$id_jenis_naskah."'
				AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_jenis_naskah WHERE id_jenis_naskah = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		
		function get_jenis_naskah($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_jenis_naskah', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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