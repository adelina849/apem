<?php
	Class M_hak_akses extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function list_hak_akses_limit($id_jabatan,$cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_fasilitas,A.group1,A.main_group,A.sub_group,A.nama_fasilitas,A.keterangan,COALESCE(B.JUMLAH,0) AS JUMLAH
										FROM tb_fasilitas AS A
										LEFT JOIN
										(
											SELECT id_fasilitas,COUNT(id_hak_akses) AS JUMLAH 
											FROM tb_hak_akses
											WHERE id_jabatan = ".$id_jabatan."
											AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
											GROUP BY id_fasilitas
										) AS B
										ON A.id_fasilitas = B.id_fasilitas
										".$cari." ORDER BY  COALESCE(B.JUMLAH,0) DESC,id_fasilitas ASC,nama_fasilitas ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_hak_akses_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(A.id_fasilitas) AS JUMLAH
										FROM tb_fasilitas AS A
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
		
		function get_akses_fasilitas($id_jabatan,$id_fasilitas)
        {
            $query = $this->db->query("SELECT * FROM tb_hak_akses WHERE id_fasilitas = ".$id_fasilitas." AND id_jabatan = ".$id_jabatan." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';");
            if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
        }
		
		function simpan($id_jabatan,$id_fasilitas,$id_user,$kode_kantor)
		{
			/*$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   'id_fasilitas' => $id_fasilitas,
			   'tgl_updt' => $jam,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_hak_akses', $data);*/
			
			$query = "INSERT INTO tb_hak_akses
			 (
				 id_hak_akses
				 ,id_jabatan
				 ,id_fasilitas
				 ,tgl_updt
				 ,tgl_ins
				 ,kode_kantor
				 ,user_updt
			 )
			 Values
			 (
				 (
					 SELECT CONCAT('JBTN',FRMTGL,ORD) AS id_hak_akses
					 From
					 (
						 SELECT CONCAT(Y,M) AS FRMTGL
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
							 COALESCE(MAX(CAST(RIGHT(id_hak_akses,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_hak_akses
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 )
				 ,'".$id_jabatan."'
				 ,'".$id_fasilitas."'
				 ,NOW()
				 ,NOW()
				 ,'".$kode_kantor."'
				 ,'".$id_user."'
			 )";
			$this->db->query($query);
		}
		
		
		function hapus($id_jabatan,$id_fasilitas)
		{
			$this->db->query("DELETE FROM tb_hak_akses WHERE id_jabatan = ".$id_jabatan." AND id_fasilitas = ".$id_fasilitas." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
	}
?>