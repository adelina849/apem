<?php

	Class M_statistik Extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function stat_by_jenis_laporan($dari,$sampai)
		{
			$query = "
					SELECT COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah
						,COUNT(A.id_jenis_naskah) AS jum_naskah
					FROM tb_pengajuan AS A
					LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah
					WHERE A.tgl_surat_masuk BETWEEN '".$dari."' AND '".$sampai."'
					GROUP BY COALESCE(B.nama_jenis_naskah,'');
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
		
		function stas_by_hasil($dari,$sampai)
		{
			$query = "
					SELECT COALESCE(A.hasil_pengajuan,'') AS hasil_pengajuan
						,COUNT(A.id_jenis_naskah) AS jum_naskah
					FROM tb_pengajuan AS A
					WHERE A.tgl_surat_masuk BETWEEN '".$dari."' AND '".$sampai."'
					GROUP BY COALESCE(A.hasil_pengajuan,'');
					";
			$query = $this->db->query($query);
			if($query->num_rows() > 0 )
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function stat_by_perhari($dari,$sampai)
		{
			$query = "
					SELECT COALESCE(DATE(A.tgl_surat_masuk),'') AS tgl_surat_masuk
						,COUNT(A.id_jenis_naskah) AS jum_naskah
					FROM tb_pengajuan AS A
					WHERE A.tgl_surat_masuk BETWEEN '".$dari."' AND '".$sampai."'
					GROUP BY COALESCE(DATE(A.tgl_surat_masuk),'') ORDER BY COALESCE(DATE(A.tgl_surat_masuk),'') DESC;
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
	}

?>