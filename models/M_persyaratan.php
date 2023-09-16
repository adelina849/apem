<?php
	class M_persyaratan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_persyaratan()
		{
			$query = $this->db->query("SELECT * FROM tb_persyaratan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_syarat ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_persyaratan_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_persyaratan ".$cari." ORDER BY tgl_ins ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_persyaratan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_syarat) AS JUMLAH FROM tb_persyaratan ".$cari);
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
			$nama_syarat
			,$ket_syarat
			,$user_updt
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
				INSERT INTO tb_persyaratan (
					id_syarat
					,nama_syarat
					,ket_syarat
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
				)
				VALUES
				(
					(
						SELECT CONCAT('".$this->session->userdata('isLocal')."','".$kode_kantor."',FRMTGL,ORD) AS id_penduduk
						From
						(
							SELECT CONCAT(Y,M,D) AS FRMTGL
							 ,CASE
								WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
								WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
								WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
								WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
								ELSE CONCAT('0000',CAST(ORD AS CHAR))
								END As ORD
							From
							(
								SELECT
								CAST(LEFT(NOW(),4) AS CHAR) AS Y,
								CAST(MID(NOW(),6,2) AS CHAR) AS M,
								MID(NOW(),9,2) AS D,
								COALESCE(MAX(CAST(RIGHT(id_syarat,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_persyaratan
								
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					)
					,'".$nama_syarat."'
					,'".$ket_syarat."'
					,NOW()
					,NOW()
					,'".$user_updt."'
					,'".$kode_kantor."'
				);
			";
			
			$this->db->query($query);
		}
		
		function edit(
			$id_syarat
			,$nama_syarat
			,$ket_syarat
			,$user_updt
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
				UPDATE tb_persyaratan SET 
					nama_syarat = '".$nama_syarat."'
					,ket_syarat = '".$ket_syarat."'
					,tgl_updt = NOW()
					,user_updt = '".$user_updt."'
				WHERE id_syarat = '".$id_syarat."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_persyaratan WHERE id_syarat = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		
		function get_persyaratan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_persyaratan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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