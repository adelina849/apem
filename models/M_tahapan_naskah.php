<?php
	class M_tahapan_naskah extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_tahapan_naskah_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT
				A.id_jenis_naskah
				,A.nama_jenis_naskah
				,COALESCE(B.tahapan,'') AS tahapan
			FROM tb_jenis_naskah AS A
			LEFT JOIN
			(
				SELECT A2.id_jenis_naskah
					,A2.naskah	
					,GROUP_CONCAT(
									DISTINCT A2.tahapan
									ORDER BY A2.ordr_index
									SEPARATOR ' - '
								) AS tahapan
					,A2.kode_kantor
				FROM
				(
					SELECT 
						A.id_tahapan_naskah 
						,A.id_tahapan,A.id_jenis_naskah,A.ordr_index
						,COALESCE(B.nama_jenis_naskah,'') AS naskah
						,COALESCE(C.nama_tahapan,'') AS tahapan
						,A.kode_kantor
					FROM tb_tahapan_naskah AS A
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
					LEFT JOIN tb_tahapan AS C ON A.id_tahapan  = C.id_tahapan  AND A.kode_kantor = C.kode_kantor
					WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%".$cari."%'
				) AS A2
				GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
			) AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
			WHERE COALESCE(A.nama_jenis_naskah,'') LIKE '%".$cari."%' ORDER BY A.nama_jenis_naskah ASC LIMIT ".$offset.",".$limit);
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_tahapan_naskah_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_jenis_naskah) AS JUMLAH FROM tb_jenis_naskah WHERE nama_jenis_naskah LIKE '%".$cari."%'");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_tahapan_all_to_naskah($id_jenis_naskah)
		{
			$query = "
				SELECT A.*,COALESCE(id_jenis_naskah,0) AS cek_pilih 
				FROM tb_tahapan AS A
				LEFT JOIN tb_tahapan_naskah AS B 
				ON A.id_tahapan = B.id_tahapan AND A.kode_kantor = B.kode_kantor AND B.id_jenis_naskah = '".$id_jenis_naskah."';";
				
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_tahapan_naskah_sdh_pilih($id_jenis_naskah)
		{
			$query = "
				SELECT 
					A.id_tahapan
					,A.nama_tahapan
					,COALESCE(B.id_tahapan_naskah,'') AS id_tahapan_naskah
					,COALESCE(B.ordr_index,0) AS ordr_index
				FROM tb_tahapan AS A
				LEFT JOIN tb_tahapan_naskah AS B ON A.id_tahapan = B.id_tahapan AND A.kode_kantor = B.kode_kantor AND B.id_jenis_naskah = '".$id_jenis_naskah."'
				WHERE B.id_tahapan IS NOT NULL
				ORDER BY COALESCE(B.ordr_index,0),A.nama_tahapan;";
				
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function simpan(
			$id_jenis_naskah
			,$id_tahapan
			,$user_updt
			,$kode_kantor
			,$status_kantor
		)
		{
			$query = "
				INSERT INTO tb_tahapan_naskah (
					id_jenis_naskah
					,id_tahapan
					,ordr_index
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					".$id_jenis_naskah."
					,".$id_tahapan."
					,(SELECT max_ordr_index FROM ( SELECT COALESCE(MAX(ordr_index),0) + 1 AS max_ordr_index FROM tb_tahapan_naskah WHERE id_jenis_naskah = '".$id_jenis_naskah."') AS A)
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
			,$id_tahapan
			,$ordr_index)
		{
			
			$query = "
				UPDATE tb_tahapan_naskah SET 
					ordr_index = '".$ordr_index."'
					,tgl_updt = NOW()
					,user_updt = '".$this->session->userdata('ses_id_karyawan')."'
				WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND id_tahapan = '".$id_tahapan."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_tahapan_naskah WHERE id_tahapan_naskah = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		function hapus_by_tahapan_dn_naskah($id_jenis_naskah,$id_tahapan)
		{
			$this->db->query("DELETE FROM tb_tahapan_naskah WHERE id_jenis_naskah = ".$id_jenis_naskah." AND id_tahapan = ".$id_tahapan." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		
		function get_tahapan_naskah($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_tahapan_naskah', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function get_tahapan_naskah_from_naskah_dn_tahapan($id_jenis_naskah,$id_tahapan)
        {
            $query = $this->db->get_where('tb_tahapan_naskah', array('id_jenis_naskah' => $id_jenis_naskah,'id_tahapan' => $id_tahapan,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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