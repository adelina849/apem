-- 1.
		ALTER TABLE `tb_akun` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `user_updt`;
		ALTER TABLE `tb_data_pajak` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `tgl_ins`;
		ALTER TABLE `tb_fasilitas` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `keterangan`;
		ALTER TABLE `tb_hak_akses` ADD `kode_kantor_fix` INT(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_images` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_isi_syarat_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `kode_kantor`;
		ALTER TABLE `tb_isi_var_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `kode_kantor`;
		ALTER TABLE `tb_jabatan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_jenis_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_kantor` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `user_updt`;
		ALTER TABLE `tb_karyawan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_penduduk` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `tgl_ins`;
		ALTER TABLE `tb_pengajuan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_persyaratan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `kode_kantor`;
		ALTER TABLE `tb_persyaratan_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_pertanyaan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `ket_pertanyaan`;
		ALTER TABLE `tb_status_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_tahapan` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_tahapan_naskah` ADD `kode_kantor_fix` VARCHAR(25) NOT NULL AFTER `status_kantor`;
		ALTER TABLE `tb_var_naskah` ADD `tb_tahapan_naskah` VARCHAR(25) NOT NULL AFTER `kode_kantor`;

		ALTER TABLE `tb_data_pajak` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `tgl_ins`;
		-- UPDATE `tb_data_pajak` SET kode_kantor = 'KABCJR';
		ALTER TABLE `tb_fasilitas` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `keterangan`;
		-- UPDATE `tb_fasilitas` SET kode_kantor = 'KABCJR';
		-- UPDATE `tb_isi_var_naskah` SET kode_kantor = 'KABCJR';
		ALTER TABLE `tb_pertanyaan` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `ket_pertanyaan`;
		-- UPDATE `tb_pertanyaan` SET kode_kantor = 'KABCJR';

		ALTER TABLE `tb_data_pajak` DROP PRIMARY KEY, ADD PRIMARY KEY(`nik`, `nopol`, `noPol_ori`, `kode_kantor`);
		ALTER TABLE `tb_fasilitas` DROP PRIMARY KEY, ADD PRIMARY KEY(`id_fasilitas`, `kode_kantor`);
		ALTER TABLE `tb_penduduk` DROP PRIMARY KEY, ADD PRIMARY KEY(`id_penduduk`, `nik`, `kode_kantor`);
		ALTER TABLE `tb_pertanyaan` DROP PRIMARY KEY, ADD PRIMARY KEY(`id_pertanyaan`, `kode_kantor`);

-- admin
-- @Admin849949

-- 2.
		-- ALTER TABLE `tb_data_pajak` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `tgl_ins`;
		UPDATE `tb_data_pajak` SET kode_kantor = 'KABCJR';
		-- ALTER TABLE `tb_fasilitas` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `keterangan`;
		UPDATE `tb_fasilitas` SET kode_kantor = 'KABCJR';
		UPDATE `tb_isi_var_naskah` SET kode_kantor = 'KABCJR';
		-- ALTER TABLE `tb_pertanyaan` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `ket_pertanyaan`;
		UPDATE `tb_pertanyaan` SET kode_kantor = 'KABCJR';




		-- ALTER TABLE `tb_data_pajak` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `tgl_ins`;
		UPDATE `tb_data_pajak` SET kode_kantor = 'KABCJR';
		-- ALTER TABLE `tb_fasilitas` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `keterangan`;
		UPDATE `tb_fasilitas` SET kode_kantor = 'KABCJR';
		UPDATE `tb_isi_var_naskah` SET kode_kantor = 'KABCJR';
		-- ALTER TABLE `tb_pertanyaan` ADD `kode_kantor` VARCHAR(25) NOT NULL AFTER `ket_pertanyaan`;
		UPDATE `tb_pertanyaan` SET kode_kantor = 'KABCJR';





-- 3.
		UPDATE tb_akun SET kode_kantor = 'PSKD';
		UPDATE tb_data_pajak SET kode_kantor = 'PSKD';
		UPDATE tb_fasilitas SET kode_kantor = 'PSKD';
		UPDATE tb_hak_akses SET kode_kantor = 'PSKD';
		UPDATE tb_images SET kode_kantor = 'PSKD';
		UPDATE tb_isi_syarat_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_isi_var_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_jabatan SET kode_kantor = 'PSKD';
		UPDATE tb_jenis_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_kantor SET kode_kantor = 'PSKD';
		UPDATE tb_karyawan SET kode_kantor = 'PSKD';
		UPDATE tb_penduduk SET kode_kantor = 'PSKD';
		UPDATE tb_pengajuan SET kode_kantor = 'PSKD';
		UPDATE tb_persyaratan SET kode_kantor = 'PSKD';
		UPDATE tb_persyaratan_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_pertanyaan SET kode_kantor = 'PSKD';
		UPDATE tb_status_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_tahapan SET kode_kantor = 'PSKD';
		UPDATE tb_tahapan_naskah SET kode_kantor = 'PSKD';
		UPDATE tb_var_naskah SET kode_kantor = 'PSKD';



		INSERT INTO tb_akun	SELECT * FROM db_apem_2.tb_akun;
		INSERT INTO tb_data_pajak SELECT * FROM	db_apem_2.tb_data_pajak;
		INSERT INTO tb_fasilitas SELECT * FROM db_apem_2.tb_fasilitas;
		INSERT INTO tb_hak_akses SELECT * FROM db_apem_2.tb_hak_akses;
		INSERT INTO tb_images SELECT * FROM db_apem_2.tb_images;
		INSERT INTO tb_isi_syarat_naskah SELECT * FROM db_apem_2.tb_isi_syarat_naskah;
		INSERT INTO tb_isi_var_naskah SELECT * FROM db_apem_2.tb_isi_var_naskah;
		INSERT INTO tb_jabatan SELECT * FROM db_apem_2.tb_jabatan;
		INSERT INTO tb_jenis_naskah SELECT * FROM db_apem_2.tb_jenis_naskah;
		INSERT INTO tb_kantor SELECT * FROM db_apem_2.tb_kantor;
		INSERT INTO tb_karyawan SELECT * FROM db_apem_2.tb_karyawan;
		INSERT INTO tb_penduduk SELECT * FROM db_apem_2.tb_penduduk;
		INSERT INTO tb_pengajuan SELECT * FROM db_apem_2.tb_pengajuan;
		INSERT INTO tb_persyaratan SELECT * FROM db_apem_2.tb_persyaratan;
		INSERT INTO tb_persyaratan_naskah SELECT * FROM db_apem_2.tb_persyaratan_naskah;
		INSERT INTO tb_pertanyaan SELECT * FROM db_apem_2.tb_pertanyaan;
		INSERT INTO tb_status_naskah SELECT * FROM db_apem_2.tb_status_naskah;
		INSERT INTO tb_tahapan SELECT * FROM db_apem_2.tb_tahapan;
		INSERT INTO tb_tahapan_naskah SELECT * FROM db_apem_2.tb_tahapan_naskah;
		INSERT INTO tb_var_naskah SELECT * FROM db_apem_2.tb_var_naskah;






