<?php

	Class M_laporan extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function lap_list_pengajuan($cari,$berdasarkan,$dari,$sampai,$limit,$offset)
		{
			$query = $this->db->query("
				SELECT
					AA.id_pengajuan,AA.id_jenis_naskah,AA.nama_jenis_naskah,AA.no_pengajuan,AA.kode_pengajuan,AA.sumber,AA.perihal,AA.diajukan_oleh,AA.penting,AA.ket_hasil,AA.hasil_pengajuan,AA.user_updt,AA.tgl_surat_dibuat,AA.tgl_surat_masuk
					,AA.tahapan,AA.sts, ROUND(AA.sts/(AA.tahapan/100),2) AS prsn
				FROM
				(
					SELECT A.id_pengajuan,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_surat_dibuat,A.tgl_surat_masuk,A.user_updt,A.tgl_ins
						,COALESCE(A.ket_hasil,'') AS ket_hasil
						,COALESCE(A.hasil_pengajuan,'') AS hasil_pengajuan
						,(SELECT COUNT(id_tahapan_naskah) AS tahapan FROM tb_tahapan_naskah AS B WHERE B.id_jenis_naskah = A.id_jenis_naskah) AS tahapan
						,(SELECT COUNT(id_status_naskah) AS sts FROM tb_status_naskah AS C WHERE C.id_pengajuan = A.id_pengajuan) AS sts
						,A.kode_kantor
						,A.id_jenis_naskah
						,COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah
					FROM tb_pengajuan AS A
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
					GROUP BY A.id_pengajuan,A.id_jenis_naskah,B.nama_jenis_naskah,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_surat_dibuat,A.tgl_surat_masuk,A.user_updt,A.tgl_ins,A.ket_hasil,A.hasil_pengajuan,A.kode_kantor
				) AS AA
				WHERE AA.tgl_surat_masuk BETWEEN '".$dari."' AND '".$sampai."'
				".$cari." 
				ORDER BY ".$berdasarkan." 
				LIMIT ".$offset.",".$limit);
				
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_list_pengajuan($cari,$dari,$sampai)
		{
			$query = $this->db->query("
				SELECT COUNT(id_pengajuan) AS JUMLAH
				FROM
				(
					SELECT AA.id_pengajuan,AA.id_jenis_naskah,AA.nama_jenis_naskah,AA.no_pengajuan,AA.kode_pengajuan,AA.sumber,AA.perihal,AA.diajukan_oleh,AA.penting,AA.ket_hasil,AA.hasil_pengajuan,AA.user_updt,AA.tgl_surat_dibuat,AA.tgl_surat_masuk
						,AA.tahapan,AA.sts, ROUND(AA.sts/(AA.tahapan/100),2) AS prsn
					FROM
					(
						SELECT A.id_pengajuan,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_surat_dibuat,A.tgl_surat_masuk,A.user_updt,A.tgl_ins
							,COALESCE(A.ket_hasil,'') AS ket_hasil
							,COALESCE(A.hasil_pengajuan,'') AS hasil_pengajuan
							,(SELECT COUNT(id_tahapan_naskah) AS tahapan FROM tb_tahapan_naskah AS B WHERE B.id_jenis_naskah = A.id_jenis_naskah) AS tahapan
							,(SELECT COUNT(id_status_naskah) AS sts FROM tb_status_naskah AS C WHERE C.id_pengajuan = A.id_pengajuan) AS sts
							,A.kode_kantor
							,A.id_jenis_naskah
							,COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah
						FROM tb_pengajuan AS A
						LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
						GROUP BY A.id_pengajuan,A.id_jenis_naskah,B.nama_jenis_naskah,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_surat_dibuat,A.tgl_surat_masuk,A.user_updt,A.tgl_ins,A.ket_hasil,A.hasil_pengajuan,A.kode_kantor
					) AS AA
					WHERE AA.tgl_surat_masuk BETWEEN '".$dari."' AND '".$sampai."'
					".$cari." 
				) AS A;
				");
				
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
	
		function list_pajak($cari,$limit,$offset)
		{
			$query = "
						SELECT
							A.*
							,COALESCE(B.nama,'') AS nama
							,COALESCE(B.jenis_kelamin,'') AS jenis_kelamin
							,COALESCE(B.status_menikah,'') AS status_menikah
							,COALESCE(B.tempat_lahir,'') AS tempat_lahir
							,COALESCE(B.tgl_lahir,'') AS tgl_lahir
							,COALESCE(B.tlp,'') AS tlp
							,COALESCE(B.email,'') AS email
							,COALESCE(B.alamat,'') AS alamat
						FROM tb_data_pajak AS A 
						LEFT JOIN tb_penduduk AS B ON A.nik = B.nik
						".$cari."
						ORDER BY A.tgl_ins DESC
						LIMIT ".$offset.",".$limit."
						;
					";
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
		
		function count_pajak($cari)
		{
			$query = "
						SELECT 
							COUNT(A.tgl_ins) AS JUMLAH
						FROM tb_data_pajak AS A 
						LEFT JOIN tb_penduduk AS B ON A.nik = B.nik
						".$cari."
						;
					";
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
	}

?>