<?php
	class M_penduduk extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_penduduk_limit($cari,$limit,$offset)
		{
			$query = "SELECT * FROM tb_penduduk ".$cari." ORDER BY nama ASC LIMIT ".$offset.",".$limit;
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
		
		function count_penduduk($cari)
		{
			$query = "SELECT COUNT(id_penduduk) AS JUMLAH FROM tb_penduduk ".$cari;
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
			
		}
		
		function get_data_penduduk($cari)
		{
			$query = "SELECT * FROM tb_penduduk ".$cari;
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
		
		function hapus_data_penduduk($cari)
		{
			$query = "DELETE FROM tb_penduduk ".$cari;
			$query = $this->db->query($query);
		}
		
		function simpan
		(
			$nik,
			$nama,
			$jenis_kelamin,
			$status_menikah,
			$tempat_lahir,
			$tgl_lahir,
			$tlp,
			$email,
			$alamat,
			$from_db,
			$kode_kantor

		)
		{
			$strquery = "
				INSERT INTO tb_penduduk
				(
					id_penduduk,
					nik,
					nama,
					jenis_kelamin,
					status_menikah,
					tempat_lahir,
					tgl_lahir,
					tlp,
					email,
					alamat,
					from_db,
					kode_kantor,
					tgl_ins
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
								COALESCE(MAX(CAST(RIGHT(id_penduduk,5) AS UNSIGNED)) + 1,1) AS ORD
								From tb_penduduk
								
								WHERE DATE(tgl_ins) = DATE(NOW())
								AND kode_kantor = '".$kode_kantor."'
							) AS A
						) AS AA
					),
					'".$nik."',
					'".$nama."',
					'".$jenis_kelamin."',
					'".$status_menikah."',
					'".$tempat_lahir."',
					'".$tgl_lahir."',
					'".$tlp."',
					'".$email."',
					'".$alamat."',
					'".$from_db."',
					'".$kode_kantor."',
					NOW()
				)
			";
			
			/*SIMPAN DAN CATAT QUERY*/
				//$this->M_gl_log->simpan_query($strquery);
				$this->db->query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
		
		function edit
		(
			$id_penduduk,
			$nik,
			$nama,
			$jenis_kelamin,
			$status_menikah,
			$tempat_lahir,
			$tgl_lahir,
			$tlp,
			$email,
			$alamat,
			$kode_kantor
		)
		{
			$strquery = "
					UPDATE tb_penduduk SET
						
						id_penduduk = '".$id_penduduk."',
						nik = '".$nik."',
						nama = '".$nama."',
						jenis_kelamin = '".$jenis_kelamin."',
						status_menikah = '".$status_menikah."',
						tempat_lahir = '".$tempat_lahir."',
						tgl_lahir = '".$tgl_lahir."',
						tlp = '".$tlp."',
						email = '".$email."',
						alamat = '".$alamat."'
						
					WHERE kode_kantor = '".$kode_kantor."' AND id_penduduk = '".$id_penduduk
					."'
					";
					
			/*SIMPAN DAN CATAT QUERY*/
				//$this->M_gl_log->simpan_query($strquery);
				$this->db->query($strquery);
			/*SIMPAN DAN CATAT QUERY*/
		}
	}
?>