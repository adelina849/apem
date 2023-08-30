<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
// if ( ! function_exists('tanggal'))
// {
	function tanggal($var = '')
	{
	$tgl = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$pecah = explode("-", $var);
	return $pecah[2]." ".$tgl[$pecah[1] - 1]." ".$pecah[0];
	}
	
	function Terbilang($x)
	{
	  $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	  if ($x < 12)
		return " " . $abil[$x];
	  elseif ($x < 20)
		return Terbilang($x - 10) . " Belas";
	  elseif ($x < 100)
		return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
	  elseif ($x < 200)
		return " seratus" . Terbilang($x - 100);
	  elseif ($x < 1000)
		return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
	  elseif ($x < 2000)
		return " seribu" . Terbilang($x - 1000);
	  elseif ($x < 1000000)
		return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
	  elseif ($x < 1000000000)
		return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);
	}
//}

