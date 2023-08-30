<?php
	class M_dash extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function getTransaksiJual()
		{
			$query = $this->db->query('SELECT COUNT(A.id_h_penjualan) AS TRANSAKSI_PENJUALAN FROM tb_h_penjualan AS A
INNER JOIN tb_d_penjualan AS B ON A.id_h_penjualan = B.id_h_penjualan 
WHERE DATE(A.tgl_h_penjualan) = DATE(NOW());');
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function getTransaksiBeli()
		{
			$query = $this->db->query('SELECT COUNT(A.id_h_pembelian) AS TRANSAKSI_BELI FROM tb_h_pembelian AS A
INNER JOIN tb_d_pembelian AS B ON A.id_h_pembelian = B.id_h_pembelian WHERE DATE(A.tgl_h_pembelian) = DATE(NOW());');
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function getTotalJual()
		{
			$query = $this->db->query('SELECT SUM(A.TOTAL_JUAL) AS TOTAL_JUAL FROM 
								(
									SELECT COALESCE(SUM(nominal),0) AS TOTAL_JUAL 
									FROM tb_h_penjualan AS A
									INNER JOIN tb_d_penjualan_bayar AS B ON A.id_h_penjualan = B.id_h_penjualan
									WHERE DATE(A.tgl_h_penjualan) = DATE(NOW())
									UNION ALL
									SELECT COALESCE(SUM(nominal),0) AS TOTAL_UANG_MASUK FROM tb_uang_masuk AS A WHERE DATE(tgl_uang_masuk) = DATE(NOW())
								) AS A');
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function getTotalBeli()
		{
			$query = $this->db->query('SELECT SUM(A.TOTAL_BELI) AS TOTAL_BELI FROM
									(
										SELECT COALESCE(SUM(nominal),0) AS TOTAL_BELI 
										FROM tb_h_pembelian AS A
										INNER JOIN tb_d_pembelian_bayar AS B ON A.id_h_pembelian = B.id_h_pembelian 
										WHERE DATE(A.tgl_h_pembelian) = DATE(NOW())
										UNION ALL
										SELECT COALESCE(SUM(nominal),0) AS TOTAL_UANG_KELUAR FROM tb_uang_keluar AS A WHERE DATE(tgl_dikeluarkan) = DATE(NOW())
									) AS A;');
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function st_penjualan()
		{
			$query = $this->db->query("SELECT A.TAHUN_BULAN AS selected_date,COALESCE(B.TOTAL_JUAL,0) AS TOTAL_JUAL FROM
										(
											select DATE_FORMAT(selected_date,'%Y-%m') AS TAHUN_BULAN from 
											(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
												 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
												 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
												 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
												 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
												 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
												
												where DATE(selected_date) between DATE_FORMAT(NOW(),'%Y-01-01') and DATE_FORMAT(NOW(),'%Y-12-31')
											group by DATE_FORMAT(selected_date,'%Y-%m')
										) AS A
										LEFT JOIN
										(
											SELECT DATE_FORMAT(A.tgl_h_penjualan,'%Y-%m') AS tgl_h_penjualan,SUM(nominal) AS TOTAL_JUAL FROM tb_h_penjualan AS A
											INNER JOIN tb_d_penjualan_bayar AS B ON A.id_h_penjualan = B.id_h_penjualan 
											/*WHERE DATE(A.tgl_h_penjualan) = DATE(NOW())*/
											GROUP BY DATE_FORMAT(A.tgl_h_penjualan,'%Y-%m')
										) AS B ON A.TAHUN_BULAN = B.tgl_h_penjualan");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function st_uang_keluar()
		{
			$query = $this->db->query("SELECT A.TAHUN_BULAN AS selected_date,COALESCE(B.NOMINAL,0) AS NOMINAL FROM
									(
										select DATE_FORMAT(selected_date,'%Y-%m') AS TAHUN_BULAN from 
										(select adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date from
											 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
											 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
											 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
											 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
											 (select 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4) v
											
											where selected_date between DATE_FORMAT(NOW(),'%Y-01-01') and DATE_FORMAT(NOW(),'%Y-12-31')
										group by DATE_FORMAT(selected_date,'%Y-%m')
									) AS A
									LEFT JOIN
									(
										SELECT DATE_FORMAT(tgl_bayar,'%Y-%m') AS TGL,SUM(nominal) AS NOMINAL
										FROM
										(
											SELECT tgl_bayar,nominal FROM tb_h_pembelian AS A
											INNER JOIN tb_d_pembelian_bayar AS B ON A.id_h_pembelian = B.id_h_pembelian
											UNION ALL
											SELECT tgl_dikeluarkan,nominal FROM tb_uang_keluar
										) AS A GROUP BY DATE_FORMAT(tgl_bayar,'%Y-%m')
									) AS B ON A.TAHUN_BULAN = B.TGL;");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function akun_akun()
		{
			$query = $this->db->query("
			
				SELECT nama_kat_uang_masuk AS AKUN FROM
				(
					SELECT nama_kat_uang_masuk FROM tb_kat_uang_masuk
					UNION ALL
					SELECT nama_kat_uang_keluar FROM tb_kat_uang_keluar
					UNION ALL
					SELECT nama_kat_assets FROM tb_kat_assets
					UNION ALL
					SELECT '101 KAS' AS AKUN
					UNION ALL
					SELECT '102 PERSEDIAAN BARANG DAGANG' AS AKUN
					UNION ALL
					SELECT '103 PIUTANG USAHA' AS AKUN
					UNION ALL
					SELECT '104 PENYISIHAN PIUTANG USAHA' AS AKUN
					UNION ALL
					SELECT '105 WESEL/GIRO/CEK TAGIH' AS AKUN
					UNION ALL
					SELECT '112 AKUMULASI PENYUSUTAN PERALATAN' AS AKUN
					UNION ALL
					SELECT '114 AKUMULASI PENYUSUTAN PERALATANAN KENDARAAN' AS AKUN
					UNION ALL
					SELECT '116 AKUMULASI PENYUSUTAN GEDUNG' AS AKUN
					/*UNION ALL
					SELECT '201 UTANG USAHA/DAGANG' AS AKUN
					UNION ALL
					SELECT '202 UTANG WESEL' AS AKUN
					UNION ALL
					SELECT '203 UTANG GAJI' AS AKUN
					UNION ALL
					SELECT '203 UTANG SEWA GEDUNG' AS AKUN
					UNION ALL
					SELECT '204 UTANG PAJAK PENGHASILAN' AS AKUN
					UNION ALL
					SELECT '205 UTANG HIPOTEK' AS AKUN
					UNION ALL
					SELECT '206 UTANG OBLIGASI' AS AKUN*/
					UNION ALL
					SELECT '400 PENJUALAN' AS AKUN
					UNION ALL
					SELECT '401 RETUR PENJUALAN' AS AKUN
					UNION ALL
					SELECT '402 POTONGAN PENJUALAN' AS AKUN
					UNION ALL
					SELECT '500 PEMBELIAN' AS AKUN
					UNION ALL
					SELECT '502 POTONGAN PEMBELIAN' AS AKUN
				) AS A
				ORDER BY nama_kat_uang_masuk ASC
			
			");
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