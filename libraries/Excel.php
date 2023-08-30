<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'PHPExcel/Classes/PHPExcel.php';

class Excel extends PHPExcel{

   public function __construct(){

        parent::__construct();

   }

}

?>