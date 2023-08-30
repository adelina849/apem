<?php
	class M_pertanyaan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_pertanyaan($type)
		{
			$query = $this->db->query("SELECT * FROM tb_pertanyaan WHERE type = ".$type." ORDER BY pertanyaan ASC");
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