<?php
	class M_status_naskah extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_status_naskah_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
				SELECT
					AA.id_pengajuan,AA.id_jenis_naskah,AA.nama_jenis_naskah,AA.no_pengajuan,AA.kode_pengajuan,AA.sumber,AA.perihal,AA.diajukan_oleh,AA.penting
					,AA.tandatangan_oleh,AA.tgl_surat_dibuat,AA.tgl_surat_masuk,AA.ket_pengajuan
					,AA.ket_hasil,AA.hasil_pengajuan,AA.user_updt
					,AA.tahapan,AA.sts, ROUND(AA.sts/(AA.tahapan/100),2) AS prsn
				FROM
				(
					SELECT 
					A.id_pengajuan,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tandatangan_oleh,A.tgl_surat_dibuat,A.tgl_surat_masuk,A.ket_pengajuan
					,A.user_updt,A.tgl_ins
						,COALESCE(A.ket_hasil,'') AS ket_hasil
						,COALESCE(A.hasil_pengajuan,'') AS hasil_pengajuan
						,
							(
								SELECT COUNT(id_tahapan_naskah) AS tahapan 
								FROM tb_tahapan_naskah AS B 
								WHERE B.id_jenis_naskah = A.id_jenis_naskah
								AND B.kode_kantor = A.kode_kantor
							) 
							AS tahapan
						,
							(
								SELECT COUNT(id_status_naskah) AS sts 
								FROM tb_status_naskah AS C 
								WHERE C.id_pengajuan = A.id_pengajuan
								AND C.kode_kantor = A.kode_kantor
							) AS sts
						,A.kode_kantor
						,A.id_jenis_naskah
						,COALESCE(B.nama_jenis_naskah,'') AS nama_jenis_naskah
					FROM tb_pengajuan AS A
					LEFT JOIN tb_jenis_naskah AS B 
						ON A.id_jenis_naskah = B.id_jenis_naskah 
						AND A.kode_kantor = B.kode_kantor
					GROUP BY A.id_pengajuan,A.id_jenis_naskah,B.nama_jenis_naskah,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.user_updt,A.tgl_ins,A.ket_hasil,A.hasil_pengajuan,A.kode_kantor
				) AS AA
				".$cari." 
				ORDER BY AA.tgl_ins DESC 
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
		
		function count_status_naskah_limit($cari)
		{
			$query = $this->db->query("
				SELECT COUNT(id_pengajuan) AS JUMLAH 
				FROM
				(
					SELECT A.id_pengajuan,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_ins
						,(SELECT COUNT(id_tahapan_naskah) AS tahapan FROM tb_tahapan_naskah AS B WHERE B.id_jenis_naskah = A.id_jenis_naskah) AS tahapan
						,(SELECT COUNT(id_status_naskah) AS sts FROM tb_status_naskah AS C WHERE C.id_pengajuan = A.id_pengajuan) AS sts
						,A.kode_kantor
					FROM tb_pengajuan AS A
					GROUP BY A.id_pengajuan,A.no_pengajuan,A.kode_pengajuan,A.sumber,A.perihal,A.diajukan_oleh,A.penting,A.tgl_ins,A.kode_kantor
				) AS AA ".$cari);
				
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		
		function detail_status_naskah($id_pengajuan,$id_jenis_naskah,$kode_kantor)
		{
			$query = "SELECT
						A.id_tahapan_naskah
						,A.id_jenis_naskah
						,A.id_tahapan
						,COALESCE(COALESCE(B.nama_tahapan,C.nama_tahapan),'') AS nama_tahapan
						,COALESCE(COALESCE(C.ordr_index,A.ordr_index),'') AS ordr_index
						,COALESCE(C.id_status_naskah,'') AS id_status_naskah
						,COALESCE(C.id_pengajuan,'') AS id_pengajuan
						,COALESCE(C.tgl_updt_status,'') AS tgl_update_status
						,COALESCE(C.di_proses_oleh,'') AS di_proses_oleh
						,COALESCE(C.jenis_keputusan,'') AS jenis_keputusan
						,COALESCE(C.ket_status,'') AS ket_status
						,COALESCE(D.nama_karyawan,'') AS nama_karyawan
					FROM tb_tahapan_naskah AS A
					LEFT JOIN tb_tahapan AS B 
						ON A.id_tahapan = B.id_tahapan 
						AND A.kode_kantor = B.kode_kantor
					LEFT JOIN tb_status_naskah AS C 
						ON A.id_tahapan_naskah = C.id_tahapan_naskah 
						AND C.id_pengajuan = ".$id_pengajuan." 
						AND A.kode_kantor = C.kode_kantor
					LEFT JOIN tb_karyawan AS D 
						ON C.id_karyawan = D.id_karyawan 
						AND A.kode_kantor = D.kode_kantor
					WHERE A.id_jenis_naskah  = '".$id_jenis_naskah."'
					AND A.kode_kantor = '".$kode_kantor."' 
					
					ORDER BY COALESCE(C.ordr_index,A.ordr_index) ASC;";
					
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
		
		
		function get_status_naskah_terakhir_by_kode_kantor($id_pengajuan,$kode_kantor)
		{
			$query = "
						SELECT * 
						FROM tb_status_naskah 
						WHERE id_pengajuan = '".$id_pengajuan."' 
						AND kode_kantor = '".$kode_kantor."' 
						ORDER BY ordr_index DESC LIMIT 0,1";
			
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
		
		function get_status_naskah_terakhir($id_pengajuan)
		{
			$query = "SELECT * FROM tb_status_naskah WHERE id_pengajuan = ".$id_pengajuan." ORDER BY ordr_index DESC LIMIT 0,1";
			
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
			$id_tahapan_naskah
			,$nama_tahapan
			,$id_pengajuan
			,$id_karyawan
			,$user_insert
			,$tgl_updt_status
			,$di_proses_oleh
			,$jenis_keputusan
			,$ket_status
			,$ordr_index
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
				INSERT INTO tb_status_naskah (
					id_tahapan_naskah
					,nama_tahapan
					,id_pengajuan
					,id_karyawan
					,user_insert
					,tgl_updt_status
					,di_proses_oleh
					,jenis_keputusan
					,ket_status
					,ordr_index
					,tgl_ins
					,tgl_updt
					,user_updt
					,kode_kantor
					,status_kantor
				)
				VALUES
				(
					'".$id_tahapan_naskah."'
					,'".$nama_tahapan."'
					,'".$id_pengajuan."'
					,'".$id_karyawan."'
					,'".$user_insert."'
					,'".$tgl_updt_status."'
					,'".$di_proses_oleh."'
					,'".$jenis_keputusan."'
					,'".$ket_status."'
					,'".$ordr_index."'
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
			$id_tahapan_naskah
			,$id_pengajuan
			,$tgl_updt_status
			,$di_proses_oleh
			,$ket_status
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
				UPDATE tb_status_naskah SET 
					tgl_updt_status = '".$tgl_updt_status."'
					,di_proses_oleh = '".$di_proses_oleh."'
					,ket_status = '".$ket_status."'
					,tgl_updt = NOW()
					,user_updt = '".$user_updt."'
				WHERE id_tahapan_naskah = '".$id_tahapan_naskah."' 
				AND id_pengajuan = '".$id_pengajuan."'
				AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			";
			
			$this->db->query($query);
			
		}
		
		function hasil_pengajuan($id_pengajuan,$hasil_pengajuan,$ket_hasil)
		{
			$query = "
				UPDATE tb_pengajuan SET 
					hasil_pengajuan = '".$hasil_pengajuan."'
					,ket_hasil = '".$ket_hasil."'
					,tgl_updt = NOW()
					,user_updt = '".$this->session->userdata('ses_id_karyawan')."'
				WHERE id_pengajuan = '".$id_pengajuan."'
				AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			";
			
			$this->db->query($query);
		}
		
		function hapus($id_tahapan_naskah,$id_pengajuan)
		{
			$this->db->query("DELETE FROM tb_status_naskah WHERE id_tahapan_naskah = '".$id_tahapan_naskah."' AND id_pengajuan = '".$id_pengajuan."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ;");
		}
		
		function get_status_naskah_costumer($cari)
		{
			$query = "SELECT * FROM tb_status_naskah ".$cari;
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
		
		function get_status_naskah($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_status_naskah', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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