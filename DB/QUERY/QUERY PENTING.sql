SELECt * FROM tb_pengajuan WHERE sumber = '3203042104900001' AND kode_kantor = 'PSKD';
SELECt * FROM tb_isi_var_naskah WHERE id_pengajuan = '1-3203042104900001-2023-09-17' AND kode_kantor = 'PSKD';
SELECt 
	*
    ,SUBSTRING(id_pengajuan,3,16) AS FIX 
    ,SUBSTRING_INDEX(id_pengajuan,'-', 1) AS SRC
    ,POSITION('-' IN id_pengajuan) AS SRC2
    ,LOCATE('-',id_pengajuan) AS SRC3
    ,SUBSTRING(id_pengajuan,( 
								(LOCATE('-',id_pengajuan)) + 1 
							),16) AS FIX 
FROM tb_isi_var_naskah 
-- WHERE id_pengajuan = '1-3203042104900001-2023-09-17' 
WHERE SUBSTRING(id_pengajuan,( 
								(LOCATE('-',id_pengajuan)) + 1 
							),16) = '3203042104900001' 
AND kode_kantor = 'PSKD';