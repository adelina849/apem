<?php
	class M_var_naskah extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_var_naskah()
		{
			$query = $this->db->query("SELECT * FROM tb_var_naskah 
										WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY idx ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_var_naskah_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_var_naskah ".$cari." ORDER BY idx ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_var_naskah_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_var_naskah) AS JUMLAH FROM tb_var_naskah ".$cari);
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
			$id_jenis_naskah
			,$nama_var
			,$tipe
			,$idx
			,$ket
			,$kode_kantor
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
				INSERT INTO tb_var_naskah (
					id_jenis_naskah
					,nama_var
					,tipe
					,idx
					,ket
					,kode_kantor
				)
				VALUES
				(
					'".$id_jenis_naskah."'
					,'".$nama_var."'
					,'".$tipe."'
					,(SELECT COUNT(id_var_naskah) AS CNT FROM tb_var_naskah AS A WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND kode_kantor ='".$kode_kantor."' ) + 1
					-- SUBQUERY HARUS PAKE ALIAS AGAR GK BENTROK DENGAN TABLE INDUk
					,'".$ket."'
					,'".$kode_kantor."'
				);
			";
			
			$this->db->query($query);
		}
		
		function edit(
			$id_var_naskah
			,$id_jenis_naskah
			,$nama_var
			,$tipe
			,$idx
			,$ket
			,$kode_kantor
		)
		{
			/*$data = array
			(
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user
			);
			
			$this->db->update('tb_jabatan', $data,array('id_jabatan' => $id,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));*/
			
			$query = "
				UPDATE tb_var_naskah SET 
					nama_var = '".$nama_var."'
					,tipe = '".$tipe."'
					-- ,idx = '".$idx."'
					,ket = '".$ket."'
				WHERE id_var_naskah = '".$id_var_naskah."' AND kode_kantor = '".$kode_kantor."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_var_naskah WHERE md5(id_var_naskah) = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		
		function get_var_naskah($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_var_naskah', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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