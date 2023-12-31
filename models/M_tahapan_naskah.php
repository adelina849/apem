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
				,CASE WHEN (LENGTH(A.format_naskah) > 3) THEN 'SUDAH' ELSE 'BELUM' END AS SDH_FORMAT
				,COALESCE(B.tahapan,'') AS tahapan
				,COALESCE(C.nama_syarat,'') AS nama_syarat
				,COALESCE(D.nama_var,'') AS nama_var
			FROM tb_jenis_naskah AS A
			LEFT JOIN
			(
				SELECT A2.id_jenis_naskah
					,A2.naskah	
					,GROUP_CONCAT(
									DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.tahapan)
									ORDER BY A2.ordr_index
									SEPARATOR ' <br/>'
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
					-- WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%".$cari."%'
				) AS A2
				GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
			) AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
			
			LEFT JOIN
			(
				SELECT A2.id_jenis_naskah
					,A2.naskah	
					,GROUP_CONCAT(
									DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.nama_syarat)
									ORDER BY A2.ordr_index
									SEPARATOR ' <br/>'
								) AS nama_syarat
					,A2.kode_kantor
				FROM
				(
					SELECT 
						A.id_syarat_naskah 
						,A.id_syarat,A.id_jenis_naskah,A.ordr_index
						,COALESCE(B.nama_jenis_naskah,'') AS naskah
						,COALESCE(C.nama_syarat,'') AS nama_syarat
						,A.kode_kantor
					FROM tb_persyaratan_naskah AS A
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
					LEFT JOIN tb_persyaratan AS C ON A.id_syarat  = C.id_syarat  AND A.kode_kantor = C.kode_kantor
					WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%".$cari."%'
				) AS A2
				GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
			) AS C ON A.id_jenis_naskah = C.id_jenis_naskah AND A.kode_kantor = C.kode_kantor
			
			
			LEFT JOIN
			(
				SELECT A2.id_jenis_naskah
					,A2.naskah	
					,GROUP_CONCAT(
									DISTINCT CONCAT('<b>',ordr_index,'. </b>',A2.nama_var)
									ORDER BY A2.ordr_index
									SEPARATOR ' <br/>'
								) AS nama_var
					,A2.kode_kantor
				FROM
				(
					SELECT 
						A.id_var_naskah 
						,A.id_jenis_naskah,A.idx AS ordr_index
						,COALESCE(B.nama_jenis_naskah,'') AS naskah
						,COALESCE(A.nama_var,'') AS nama_var
						,A.kode_kantor
					FROM tb_var_naskah AS A
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
					WHERE COALESCE(B.nama_jenis_naskah,'') LIKE '%".$cari."%'
				) AS A2
				GROUP BY A2.id_jenis_naskah,A2.naskah,A2.kode_kantor
			) AS D ON A.id_jenis_naskah = D.id_jenis_naskah AND A.kode_kantor = D.kode_kantor
			
			
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
					ON A.id_tahapan = B.id_tahapan 
					AND A.kode_kantor = B.kode_kantor 
					AND B.id_jenis_naskah = '".$id_jenis_naskah."'
				WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
				;";
				
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
				LEFT JOIN tb_tahapan_naskah AS B 
					ON A.id_tahapan = B.id_tahapan 
					AND A.kode_kantor = B.kode_kantor 
					AND B.id_jenis_naskah = '".$id_jenis_naskah."'
				WHERE B.id_tahapan IS NOT NULL
				AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
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
				WHERE id_jenis_naskah = '".$id_jenis_naskah."' 
				AND id_tahapan = '".$id_tahapan."'
				AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
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
	
		function list_syarat_naskah_sdh_pilih($id_jenis_naskah)
		{
			$query = "
				SELECT 
					A.id_syarat 
					,A.nama_syarat
					,COALESCE(B.id_syarat_naskah,'') AS id_syarat_naskah
					,COALESCE(B.ordr_index,0) AS ordr_index
				FROM tb_persyaratan AS A
				LEFT JOIN tb_persyaratan_naskah AS B ON A.id_syarat  = B.id_syarat  AND A.kode_kantor = B.kode_kantor AND B.id_jenis_naskah = '".$id_jenis_naskah."'
				WHERE B.id_syarat  IS NOT NULL
				AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
				ORDER BY COALESCE(B.ordr_index,0),A.nama_syarat;";
				
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
		
		function list_syarat_all_to_naskah($id_jenis_naskah)
		{
			$query = "
				SELECT A.*,COALESCE(id_jenis_naskah,0) AS cek_pilih 
				FROM tb_persyaratan AS A
				LEFT JOIN tb_persyaratan_naskah AS B 
					ON A.id_syarat = B.id_syarat 
					AND A.kode_kantor = B.kode_kantor 
					AND B.id_jenis_naskah = '".$id_jenis_naskah."'
				WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
				;";
				
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
	
		function get_syarat_naskah_from_naskah_dn_syarat($id_jenis_naskah,$id_syarat)
        {
            $query = $this->db->get_where('tb_persyaratan_naskah', array('id_jenis_naskah' => $id_jenis_naskah,'id_syarat' => $id_syarat,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function hapus_tahan_persyaratan($id)
		{
			$this->db->query("DELETE FROM tb_persyaratan_naskah WHERE id_syarat_naskah = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		function simpan_tahan_persyaratan(
			$id_jenis_naskah
			,$id_syarat
			,$user_updt
			,$kode_kantor
			,$status_kantor
		)
		{
			$query = "
				INSERT INTO tb_persyaratan_naskah (
					id_jenis_naskah
					,id_syarat
					,ordr_index
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					'".$id_jenis_naskah."'
					,'".$id_syarat."'
					,(SELECT max_ordr_index FROM ( SELECT COALESCE(MAX(ordr_index),0) + 1 AS max_ordr_index FROM tb_persyaratan_naskah WHERE id_jenis_naskah = '".$id_jenis_naskah."' AND id_syarat = '".$id_syarat."') AS A)
					,NOW()
					,NOW()
					,'".$user_updt."'
					,'".$kode_kantor."'
					,'".$status_kantor."'
				);
			";
			
			$this->db->query($query);
		}
		
		function edit_persyaratan_naskah(
			$id_jenis_naskah
			,$id_syarat
			,$ordr_index)
		{
			
			$query = "
				UPDATE tb_persyaratan_naskah SET 
					ordr_index = '".$ordr_index."'
					,tgl_updt = NOW()
					,user_updt = '".$this->session->userdata('ses_id_karyawan')."'
				WHERE id_jenis_naskah = '".$id_jenis_naskah."' 
				AND id_syarat = '".$id_syarat."'
				AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			";
			
			$this->db->query($query);
			
		}
	}
?>