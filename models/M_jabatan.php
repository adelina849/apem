<?php
	class M_jabatan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_jabatan()
		{
			$query = $this->db->query("SELECT * FROM tb_jabatan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_jabatan ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_jabatan_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_jabatan ".$cari." ORDER BY nama_jabatan ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT A.id_jabatan,A.nama_jabatan,A.ket_jabatan,A.tgl_insert,A.tgl_update,A.user,A.kode_kantor,COALESCE(B.JUMLAH,0) AS JUMLAH FROM tb_jabatan AS A
			LEFT JOIN
			(
				SELECT id_jabatan,COUNT(id_jabatan) AS JUMLAH FROM tb_hak_akses WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' GROUP BY id_jabatan
			) AS B
			ON A.id_jabatan = B.id_jabatan ".$cari." ORDER BY nama_jabatan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_jabatan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_jabatan) AS JUMLAH FROM tb_jabatan ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan($nama,$ket,$id_user,$kode_kantor)
		{
			$data = array
			(
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_jabatan', $data); 
		}
		
		function edit($id,$nama,$ket,$id_user)
		{
			$data = array
			(
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user
			);
			
			//$this->db->where('id_jabatan', $id);
			$this->db->update('tb_jabatan', $data,array('id_jabatan' => $id,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_jabatan WHERE id_jabatan = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		function get_jabatan_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_jabatan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_jabatan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_jabatan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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