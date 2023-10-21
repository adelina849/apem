<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_api_samsat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}
	
	
	public function index()
	{
		//$this->get_json_pajak($_GET['nik']);
		//$path = 'data/movies-10.json';
		//$jsonString = file_get_contents($path);
		//$jsonString = $this->get_json_pajak($_GET['nik']);
		//var_dump(json_decode($jsonString));
		
		//$jsonobj = '{"Peter":35,"Ben":37,"Joe":43}';
		$jsonobj = $this->get_json_pajak($_GET['nik']);
		$obj = json_decode($jsonobj);
		//$obj = json_decode($jsonobj,true); //true : agar jadi array

		//echo $obj->code;
		//echo $obj->message;
		//echo $obj->data;
		

		//foreach($obj as $key => $value) {
		  ////echo $key . " => " . $value . "<br>";
		  //echo '</br>'.$value;
		//}
		
		foreach ($obj->data as $item) 
		{
			echo '<br/>Nama Pemilik : '.($item->namaPemilik);
			echo '<br/>Alamat Pemilik : '.($item->alamatPemilik);
			echo '<br/>Nomor Polisi : '.($item->nomorPolisi);
			echo '<br/>Status Tunggakan : '.($item->statusTunggakan);
			echo '<br/>Merek : '.($item->merek);
			echo '<br/>Jenis : '.($item->jenis);
			echo '<br/>tanggalAkhirPajak : '.($item->tanggalAkhirPajak);
			echo '<br/>Status Blokir : '.($item->statusBlokir);
			echo '<br/>Keterangan Blokir : '.($item->keteranganBlokir);
			echo '<br/>==============================================';
		}
		
	}
	
	public function get_json_pajak($nik)
	{		
		// production
		$client_id = "_NrXe69JftV9Bm4HcENriwglz0ga";
		$client_credential = "PGq7P6WhU5uFvIVVwU8F8DOXZyMa";
		$user_samsat = "cianjur";
		$pass_samsat = "Ru4c1JpkL0";

		// url get Token SPLP
		$urlTokenSPLP = 'https://splp.layanan.go.id/oauth2/token';
		// url API SamsAT pada SPLP
		$urlSPLPSIPANDU = 'https://api-splp.layanan.go.id/t/jabarprov.go.id/samsatkabkot/1/cianjur/get_vehicle_list_by_nik';
		
	
		$token = "Bearer " . $this->token_splp();
		//echo $token;
			
		// Header Request SIPANDU
		$basic_auth = base64_encode($user_samsat . ':' . $pass_samsat);
		function headerInput($token, $basic_auth)
		{
			$headers      = [
				'Content-Type:application/json',
				'Authorization: ' . $token,
				'auth: Basic ' . $basic_auth

			];
			return $headers;
		}
		
		/*
		$postDataGet = '{
			"nik": [
				3203121503700005,
				3204122506880001,
				3203042104900001,
				3203035310910004
			],
		 "isAssociated": false
		}';
		*/
		
		$postDataGet = '{
			"nik": [
				'.$nik.'
			],
		 "isAssociated": false
		}';

		$postdata = $postDataGet;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $urlSPLPSIPANDU,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_POSTFIELDS => $postdata,
			CURLOPT_HTTPHEADER => headerInput($token, $basic_auth)
		));
		$response = curl_exec($curl);
		curl_close($curl);
		//echo $response;
		return $response;
	}
	
	//function token_splp($client_id, $client_credential, $urlTokenSPLP)
	public function token_splp()
	{
		
		// production
		$client_id = "_NrXe69JftV9Bm4HcENriwglz0ga";
		$client_credential = "PGq7P6WhU5uFvIVVwU8F8DOXZyMa";
		$user_samsat = "cianjur";
		$pass_samsat = "Ru4c1JpkL0";

		// url get Token SPLP
		$urlTokenSPLP = 'https://splp.layanan.go.id/oauth2/token';
		// url API SamsAT pada SPLP
		$urlSPLPSIPANDU = 'https://api-splp.layanan.go.id/t/jabarprov.go.id/samsatkabkot/1/cianjur/get_vehicle_list_by_nik';

		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $urlTokenSPLP);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

		$headers = array();
		$headers[] = 'Content-Type:application/x-www-form-urlencoded';
		$headers[] = 'Authorization:Basic ' . base64_encode($client_id . ':' . $client_credential);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		//ignore SSL
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

		$result = curl_exec($ch);
		$result1 = json_decode($result);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$tokenResponse = $result1->access_token;
		return $tokenResponse;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/C_api_samsat.php */