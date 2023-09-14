<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'C_admin_login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//LOGIN
	$route['admin-cek-login'] = "C_admin_login/cek_login";
	$route['admin-cek-login/(:any)'] = 'C_admin_login/cek_login';

	$route['admin-login'] = "C_admin_login/index";
	$route['admin-login/(:any)'] = 'C_admin_login/index';

	$route['admin'] = "C_admin/index";
	$route['admin/(:any)'] = 'C_admin/index';
	
	$route['admin-logout'] = "C_admin_login/logout";
	$route['admin-logout/(:any)'] = 'C_admin_login/logout';
	
	$route['warga-pelayanan'] = "C_admin_login/login_warga";
	$route['warga-pelayanan/(:any)'] = 'C_admin_login/login_warga';
	
	$route['warga-cek-nik'] = "C_admin_login/cek_nik";
	$route['warga-cek-nik/(:any)'] = 'C_admin_login/cek_nik';
	
	$route['warga-daftar'] = "C_admin_login/daftar_warga";
	$route['warga-daftar/(:any)'] = 'C_admin_login/daftar_warga';
	
	$route['warga-daftar-simpan'] = "C_admin_login/simpan_data_warga";
	$route['warga-daftar-simpan/(:any)'] = 'C_admin_login/simpan_data_warga';
//LOGIN

//JABATAN KARYAWAN
	$route['admin-jabatan-simpan'] = "C_admin_jabatan/simpan";
	$route['admin-jabatan-simpan/(:any)'] = 'C_admin_jabatan/simpan';

	$route['admin-jabatan'] = "C_admin_jabatan/index";
	$route['admin-jabatan/(:any)'] = 'C_admin_jabatan/index/$1';

	$route['admin-jabatan-hapus'] = "C_admin_jabatan/hapus";
	$route['admin-jabatan-hapus/(:any)'] = 'C_admin_jabatan/hapus/$1';
//JABATAN KARYAWAN

//KARYAWAN
	$route['admin-karyawan'] = "C_admin_karyawan/index";
	$route['admin-karyawan/(:any)'] = 'C_admin_karyawan/index';

	$route['admin-karyawan-simpan'] = "C_admin_karyawan/simpan";
	$route['admin-karyawan-simpan/(:any)'] = 'C_admin_karyawan/simpan';

	$route['admin-karyawan-hapus'] = "C_admin_karyawan/hapus";
	$route['admin-karyawan-hapus/(:any)'] = 'C_admin_karyawan/hapus';
//KARYAWAN

//JENIS NASKAH
	$route['admin-jenis-dokumen'] = "C_admin_jenis_naskah/index";
	$route['admin-jenis-dokumen/(:any)'] = 'C_admin_jenis_naskah/index';

	$route['admin-jenis-dokumen-simpan'] = "C_admin_jenis_naskah/simpan";
	$route['admin-jenis-dokumen-simpan/(:any)'] = 'C_admin_jenis_naskah/simpan';

	$route['admin-jenis-dokumen-hapus'] = "C_admin_jenis_naskah/hapus";
	$route['admin-jenis-dokumen-hapus/(:any)'] = 'C_admin_jenis_naskah/hapus';
//JENIS NASKAH

//TAHAPAN
	$route['admin-jenis-tahapan'] = "C_admin_tahapan/index";
	$route['admin-jenis-tahapan/(:any)'] = 'C_admin_tahapan/index';

	$route['admin-jenis-tahapan-simpan'] = "C_admin_tahapan/simpan";
	$route['admin-jenis-tahapan-simpan/(:any)'] = 'C_admin_tahapan/simpan';

	$route['admin-jenis-tahapan-hapus'] = "C_admin_tahapan/hapus";
	$route['admin-jenis-tahapan-hapus/(:any)'] = 'C_admin_tahapan/hapus';
//TAHAPAN

//DATA PENDUDUK
	$route['admin-penduduk'] = "C_admin_penduduk/index";
	$route['admin-penduduk/(:any)'] = 'C_admin_penduduk/index';

	$route['admin-penduduk-simpan'] = "C_admin_penduduk/simpan";
	$route['admin-penduduk-simpan/(:any)'] = 'C_admin_penduduk/simpan';

	$route['admin-penduduk-hapus'] = "C_admin_penduduk/hapus";
	$route['admin-penduduk-hapus/(:any)'] = 'C_admin_penduduk/hapus';
//DATA PENDUDUK


//TAHAPAN NASKAH
	$route['admin-tahapan-dokumen'] = "C_admin_tahapan_naskah/index";
	$route['admin-tahapan-dokumen/(:any)'] = 'C_admin_tahapan_naskah/index';
	
	$route['admin-pilih-tahapan-dokumen'] = "C_admin_pilih_tahapan_naskah/index";
	$route['admin-pilih-tahapan-dokumen/(:any)'] = 'C_admin_pilih_tahapan_naskah/index';
//TAHAPAN NASKAH

//PENGAJUAN NASKAH
	$route['admin-pengajuan-dokumen'] = "C_admin_pengajuan/index";
	$route['admin-pengajuan-dokumen/(:any)'] = 'C_admin_pengajuan/index';

	$route['admin-pengajuan-dokumen-simpan'] = "C_admin_pengajuan/simpan";
	$route['admin-pengajuan-dokumen-simpan/(:any)'] = 'C_admin_pengajuan/simpan';

	$route['admin-pengajuan-dokumen-hapus'] = "C_admin_pengajuan/hapus";
	$route['admin-pengajuan-dokumen-hapus/(:any)'] = 'C_admin_pengajuan/hapus';
	
	$route['admin-pengajuan-dokumen-images'] = "C_admin_images/index";
	$route['admin-pengajuan-dokumen-images/(:any)'] = 'C_admin_images/index';

	$route['admin-pengajuan-dokumen-images-simpan'] = "C_admin_images/simpan";
	$route['admin-pengajuan-dokumen-images-simpan/(:any)'] = 'C_admin_images/simpan';

	$route['admin-pengajuan-dokumen-images-hapus'] = "C_admin_images/hapus";
	$route['admin-pengajuan-dokumen-images-hapus/(:any)/(:any)'] = 'C_admin_images/hapus';
//PENGAJUAN NASKAH

//STATUS NASKAH
	$route['admin-status-dokumen'] = "C_admin_status_naskah/index";
	$route['admin-status-dokumen/(:any)'] = 'C_admin_status_naskah/index';

	$route['admin-status-dokumen-simpan'] = "C_admin_status_naskah/simpan";
	$route['admin-status-dokumen-simpan/(:any)'] = 'C_admin_status_naskah/simpan';

	$route['admin-status-dokumen-hapus'] = "C_admin_status_naskah/hapus";
	$route['admin-status-dokumen-hapus/(:any)'] = 'C_admin_status_naskah/hapus';
	
	$route['admin-detail-status-dokumen'] = "C_admin_detail_status_naskah/index";
	$route['admin-detail-status-dokumen/(:any)'] = 'C_admin_detail_status_naskah/index';
	
	$route['admin-detail-status-dokumen-update'] = "C_admin_detail_status_naskah/update";
	$route['admin-detail-status-dokumen-update/(:any)'] = 'C_admin_detail_status_naskah/update';
//STATUS NASKAH

//LAPORAN
	$route['admin-laporan-dokumen'] = "C_admin_laporan/index";
	$route['admin-laporan-dokumen/(:any)'] = 'C_admin_laporan/index';
	
	$route['admin-statistik-dokumen'] = "C_admin_statistik/index";
	$route['admin-statistik-dokumen/(:any)'] = 'C_admin_statistik/index';
//LAPORAN

//CETAK FAKTUR PENGAJUAN
	$route['admin-cetak-faktur'] = "C_admin_pdf/print_faktur_pengajuan";
	$route['admin-cetak-faktur/(:any)'] = 'C_admin_pdf/print_faktur_pengajuan';
//CETAK FAKTUR PENGAJUAN

//AKUN
	$route['admin-akun'] = "C_admin_akun/index";
	$route['admin-akun/(:any)'] = 'C_admin_akun/index';

	$route['admin-simpan'] = "C_admin_akun/simpan";
	$route['admin-simpan/(:any)'] = 'C_admin_akun/simpan';

	$route['admin-akun-hapus'] = "C_admin_akun/hapus";
	$route['admin-akun-hapus/(:any)'] = 'C_admin_akun/hapus';
//AKUN

//HAK AKSES

$route['admin-hak-akses'] = "C_admin_jabatan/list_hak_akses";
$route['admin-hak-akses/(:any)'] = 'C_admin_jabatan/list_hak_akses/$1';

//HAK AKSES

//CEK BARCODE
	$route['cek-barcode'] = "C_public_input_cek_barcode/index";
	$route['status-barcode'] = "C_public_input_cek_barcode/progress";
//CEK BARCODE

//PROFILE
	$route['profile'] = "C_profile/index";
	$route['profile/(:any)'] = 'C_profile/index';
	$route['profile/(:any)/(:any)'] = 'C_profile/index';
//PROFILE
