<?php
	class M_pengajuan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_pengajuan_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
				SELECT A.*, COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah 
				FROM tb_pengajuan AS A
				LEFT JOIN tb_jenis_naskah AS B 
				ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
				".$cari." 
				ORDER BY tgl_ins DESC 
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
		
		function count_pengajuan_limit($cari)
		{
			$query = $this->db->query("
				SELECT COUNT(id_pengajuan) AS JUMLAH FROM tb_pengajuan AS A
				LEFT JOIN tb_jenis_naskah AS B 
				ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor ".$cari);
				
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
			,$kode_pengajuan
			,$diajukan_oleh
			,$perihal
			,$sumber
			,$tandatangan_oleh
			,$tgl_surat_dibuat
			,$tgl_surat_masuk
			,$ket_pengajuan
			,$penting
			,$user_updt
			,$kode_kantor
			,$status_kantor
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
				INSERT INTO tb_pengajuan (
					id_jenis_naskah
					,no_pengajuan
					,kode_pengajuan
					,diajukan_oleh
					,perihal
					,sumber
					,tandatangan_oleh
					,tgl_surat_dibuat
					,tgl_surat_masuk
					,ket_pengajuan
					,penting
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					'".$id_jenis_naskah."'
					-- ,(SELECT CONCAT(FRMTGL,ORD) AS no_pengajuan
					,(SELECT FRMTGL AS no_pengajuan
						FROM
						(
							-- SELECT CONCAT(Y,M,D,H,MM,DD) AS FRMTGL
							SELECT CONCAT(DD,D,M,Y,H,MM) AS FRMTGL
							 ,CASE
								WHEN ORD >= 10 THEN CONCAT('00',CAST(ORD AS CHAR))
								WHEN ORD >= 100 THEN CONCAT('0',CAST(ORD AS CHAR))
								WHEN ORD >= 1000 THEN CAST(ORD AS CHAR)
								ELSE CONCAT('000',CAST(ORD AS CHAR))
								END AS ORD
							FROM
							(
								SELECT 
								CAST(MID(NOW(),3,2) AS CHAR) AS Y,
								CAST(MID(NOW(),6,2) AS CHAR) AS M,
								MID(NOW(),9,2) AS D,
								CAST(MID(NOW(),12,2) AS CHAR) AS H,
								CAST(MID(NOW(),15,2) AS CHAR) AS MM,
								CAST(MID(NOW(),18,2) AS CHAR) AS DD,
								( COALESCE(MAX(CAST(RIGHT(no_pengajuan,4) AS UNSIGNED)),0) + 1) AS ORD FROM tb_pengajuan
								WHERE DATE(tgl_ins) = DATE(NOW())
							) AS A
						) AS AA)
					,'".$kode_pengajuan."'
					,'".$diajukan_oleh."'
					,'".$perihal."'
					,'".$sumber."'
					,'".$tandatangan_oleh."'
					,'".$tgl_surat_dibuat."'
					,'".$tgl_surat_masuk."'
					,'".$ket_pengajuan."'
					,'".$penting."'
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
			$id_pengajuan
			,$id_jenis_naskah
			,$no_pengajuan
			,$kode_pengajuan
			,$diajukan_oleh
			,$perihal
			,$sumber
			,$tandatangan_oleh
			,$tgl_surat_dibuat
			,$tgl_surat_masuk
			,$ket_pengajuan
			,$penting
			,$user_updt)
		{
			/*$data = array
			(
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user
			);
			
			$this->db->update('tb_jabatan', $data,array('id_jabatan' => $id,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));*/
			
			$query = "
				UPDATE tb_pengajuan SET 
					id_jenis_naskah = '".$id_jenis_naskah."'
					,no_pengajuan = '".$no_pengajuan."'
					,kode_pengajuan = '".$kode_pengajuan."'
					,diajukan_oleh = '".$diajukan_oleh."'
					,perihal = '".$perihal."'
					,sumber = '".$sumber."'
					,tandatangan_oleh = '".$tandatangan_oleh."'
					,tgl_surat_dibuat = '".$tgl_surat_dibuat."'
					,tgl_surat_masuk = '".$tgl_surat_masuk."'
					,ket_pengajuan = '".$ket_pengajuan."'
					,penting = '".$penting."'
					,tgl_updt = NOW()
					,user_updt = '".$user_updt."'
				WHERE id_pengajuan = '".$id_pengajuan."'
			";
			
			$this->db->query($query);
			
		}
		
		function hapus($id)
		{
			//$this->db->query("DELETE FROM tb_pengajuan WHERE id_pengajuan = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
			$this->db->query("DELETE FROM tb_pengajuan WHERE id_pengajuan = ".$id.";");
		}
		
		
		function get_pengajuan($berdasarkan,$cari)
        {
            //$query = $this->db->get_where('tb_pengajuan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			
			$query = $this->db->query("SELECT 
										A.* 
										,COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah
									FROM tb_pengajuan AS A
									LEFT JOIN tb_jenis_naskah AS B ON A.id_jenis_naskah = B.id_jenis_naskah AND A.kode_kantor = B.kode_kantor
									WHERE ".$berdasarkan." = '".$cari."'
									;
									
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