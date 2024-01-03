<?php
	class M_karyawan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_karyawan_id($id_karyawan)
		{
			//$query = $this->db->get_where('tb_karyawan', array('id_karyawan' => $id_karyawan), $limit, $offset);
			$query = $this->db->get_where('tb_karyawan', array('id_karyawan' => $id_karyawan,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_karyawan_no_akun($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_karyawan,A.nik_karyawan, A.nama_karyawan,A.pnd,A.tlp,A.email,A.avatar,A.avatar_url,A.alamat,A.ket_karyawan,B.id_jabatan,B.nama_jabatan,C.id_akun,C.user,C.pass,C.pertanyaan1,C.pertanyaan2,C.jawaban1,C.jawaban2 
										FROM tb_karyawan AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan
										LEFT JOIN tb_akun AS C ON A.id_karyawan = C.id_karyawan
										 
										WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
										AND C.user IS NULL ".$cari." 
										ORDER BY nama_karyawan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_karyawan_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_karyawan AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan AND A.kode_kantor = B.kode_kantor
										".$cari." ORDER BY nama_karyawan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_karyawan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_karyawan) AS JUMLAH FROM tb_karyawan AS A ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$avatar
			,$avatar_url
			,$alamat
			,$keterangan
			,$kode_kantor
			,$user_updt
		)
		{
			//Tidak ditambah tgl_ins dan tgl_updt karena sudah current stamp di databasenya
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   'no_karyawan' => $no_karyawan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'kode_kantor' => $kode_kantor,
			   'user_updt' => $user_updt
			);

			$this->db->insert('tb_karyawan', $data); 
		}
		
		function edit_with_image
		(
			$id_karyawan
			,$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$avatar
			,$avatar_url
			,$alamat
			,$keterangan
			,$user_updt
		)
		{
			$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   //'no_karyawan' => $no_karyawan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			//$this->db->where('id_karyawan', $id_karyawan);
			$this->db->update('tb_karyawan', $data, array('id_karyawan' => $id_karyawan,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function edit_no_image
		(
			$id_karyawan
			,$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$alamat
			,$keterangan
			,$user_updt
		)
		{
			$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   //'no_karyawan' => $no_karyawan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			//$this->db->where('id_karyawan', $id_karyawan);
			$this->db->update('tb_karyawan', $data, array('id_karyawan' => $id_karyawan,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_karyawan WHERE id_karyawan = ".$id." AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';");
		}
		
		function get_karyawan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_karyawan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
	}
?>