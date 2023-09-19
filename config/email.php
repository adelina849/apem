<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $config = array(
//     'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
//     'smtp_host' => 'mail.megafire-indonesia.com', 
//     'smtp_port' => 465,  //587 tls
//     'smtp_user' => 'admin@megafire-indonesia.com',
//     'smtp_pass' => '@AdminMegaFire-Indonesia2023.',
//     'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
//     'mailtype' => 'html', //plaintext 'text' mails or 'html'
//     'smtp_timeout' => '4', //in seconds
//     'charset' => 'utf-8',
//     'wordwrap' => TRUE,
//     'newline' => "\r\n",
// );

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    //'smtp_host' => 'mail.nead.danet', 
    'smtp_host' => 'smtp.gmail.com', 
    'smtp_port' => 465,  //587 tls
    'smtp_user' => 'megafire.indonesia2023@gmail.com',
    'smtp_pass' => 'chyndmjkxnlynnml',
    'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE,
    'newline' => "\r\n",
);