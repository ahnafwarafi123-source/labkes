-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: lebkes_db
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `active_sessions`
--

DROP TABLE IF EXISTS `active_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `active_sessions` (
  `session_id` varchar(128) NOT NULL,
  `user_id` int NOT NULL,
  `username` varchar(50) DEFAULT '',
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `last_activity` int unsigned NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_valid` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `table_name` varchar(100) NOT NULL DEFAULT '',
  `record_id` int NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `module` varchar(100) DEFAULT '',
  `old_values` longtext,
  `new_values` longtext,
  `user_agent` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ambil_sampel`
--

DROP TABLE IF EXISTS `ambil_sampel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ambil_sampel` (
  `id_ambil_sampel` int NOT NULL AUTO_INCREMENT,
  `id_pasien` int NOT NULL,
  `jenis_sampel` varchar(100) NOT NULL,
  `kelayakan` enum('Layak','Tidak Layak') NOT NULL,
  `alasan_tidak_layak` varchar(255) DEFAULT NULL,
  `lokasi_pengambilan` varchar(100) NOT NULL,
  `jam_pengambilan` time NOT NULL,
  `volume` varchar(50) NOT NULL,
  `tanggal_permintaan` date NOT NULL,
  `informasi_tambahan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ambil_sampel`),
  KEY `fk_ambil_sampel_pasien` (`id_pasien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `data_pending`
--

DROP TABLE IF EXISTS `data_pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_pending` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `data_type` varchar(50) DEFAULT NULL,
  `data_id` int DEFAULT '0',
  `user_id` int DEFAULT NULL,
  `requested_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `description` varchar(255) DEFAULT NULL,
  `rejection_reason` text,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `diagnosa`
--

DROP TABLE IF EXISTS `diagnosa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `diagnosa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_diagnosa_kode` (`kode`),
  KEY `idx_diagnosa_kode` (`kode`),
  KEY `idx_diagnosa_nama` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dokter_pengirim`
--

DROP TABLE IF EXISTS `dokter_pengirim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokter_pengirim` (
  `id_dokter_pengirim` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokter_pengirim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_pengambilan_klinik`
--

DROP TABLE IF EXISTS `form_pengambilan_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_pengambilan_klinik` (
  `id_pengambilan_klinik` int NOT NULL AUTO_INCREMENT,
  `id_form` int NOT NULL,
  `id_kunjungan` int unsigned DEFAULT NULL,
  `id_petugas` int DEFAULT NULL,
  `petugas_pengambilan` varchar(100) DEFAULT NULL,
  `tgl_jam_pengambilan` datetime DEFAULT NULL,
  `tgl_jam_pemeriksaan` datetime DEFAULT NULL,
  `tgl_jam_selesai` datetime DEFAULT NULL,
  `tgl_jam_pelaporan` datetime DEFAULT NULL,
  `note` text,
  `petugas_hasil` varchar(100) DEFAULT NULL,
  `verifikator_hasil` varchar(100) DEFAULT NULL,
  `penanggung_jawab_teknis` varchar(100) DEFAULT NULL,
  `sip_penanggung` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengambilan_klinik`),
  KEY `fk_form` (`id_form`),
  KEY `fk_petugas_pengambilan` (`id_petugas`),
  KEY `fk_kunjungan_lab` (`id_kunjungan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_permintaan_klinik`
--

DROP TABLE IF EXISTS `form_permintaan_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_permintaan_klinik` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kunjungan_id` int unsigned DEFAULT NULL,
  `id_pasien` int DEFAULT NULL,
  `no_register` varchar(50) DEFAULT NULL,
  `no_rm` varchar(50) DEFAULT NULL,
  `nama_dokter` varchar(100) DEFAULT NULL,
  `alamat_pengirim` text,
  `telp_pengirim` varchar(20) DEFAULT NULL,
  `tgl_form` date DEFAULT NULL,
  `diagnosa_klinis` text,
  `obat_dikonsumsi` text,
  `asal_sampel` text,
  `puasa` varchar(20) DEFAULT NULL,
  `lokasi_pengambilan` text,
  `lokasi_lainnya` varchar(100) DEFAULT NULL,
  `jenis_spesimen` text,
  `spesimen_lainnya` varchar(100) DEFAULT NULL,
  `tgl_permintaan` date DEFAULT NULL,
  `volume_spesimen` varchar(20) DEFAULT NULL,
  `tgl_pengambilan` date DEFAULT NULL,
  `jam_pengambilan` time DEFAULT NULL,
  `prioritas` varchar(20) DEFAULT NULL,
  `info_tambahan` text,
  `kelayakan` enum('Layak','Tidak Layak','Belum Diperiksa') NOT NULL DEFAULT 'Belum Diperiksa',
  `informasi_lain` text,
  `alasan_tidak_layak` varchar(255) NOT NULL,
  `jumlah_biaya` decimal(15,2) DEFAULT '0.00',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `jenis_pemeriksaan` text,
  `volume_sampel_kaji_ulang` varchar(50) DEFAULT NULL,
  `petugas_pendaftaran` varchar(100) DEFAULT NULL,
  `petugas_pengambil` varchar(100) DEFAULT NULL,
  `petugas_verifikasi` varchar(100) DEFAULT NULL,
  `petugas_validasi` varchar(100) DEFAULT NULL,
  `keterangan_puasa` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pasien` (`id_pasien`),
  KEY `fk_kunjungan_permintaan_baru` (`kunjungan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_permintaan_klinik_detail`
--

DROP TABLE IF EXISTS `form_permintaan_klinik_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `form_permintaan_klinik_detail` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_form` int NOT NULL,
  `id_kunjungan` int unsigned DEFAULT NULL,
  `id_jenis` int NOT NULL,
  `nama_jenis` varchar(100) DEFAULT NULL,
  `sub_kategori` varchar(50) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `hasil` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT CURRENT_TIMESTAMP,
  `satuan` varchar(50) DEFAULT NULL,
  `nilai_rujukan` varchar(100) DEFAULT NULL,
  `metode` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_form` (`id_form`),
  KEY `fk_jenis` (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `item_pemeriksaan`
--

DROP TABLE IF EXISTS `item_pemeriksaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_pemeriksaan` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_jenis` int NOT NULL,
  `satuan` varchar(50) DEFAULT '-',
  `nilai_rujukan` varchar(100) DEFAULT '-',
  `metode` varchar(100) DEFAULT '-',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_item`),
  KEY `id_jenis` (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jenis_pemeriksaan`
--

DROP TABLE IF EXISTS `jenis_pemeriksaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_pemeriksaan` (
  `id_jenis` int NOT NULL AUTO_INCREMENT,
  `kode_jenis` varchar(50) DEFAULT NULL,
  `nama_jenis` varchar(100) NOT NULL,
  `sub_kategori` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `nilai_rujukan` varchar(1000) DEFAULT NULL,
  `metode` varchar(100) DEFAULT NULL,
  `tipe` enum('paket','subitem','single') DEFAULT 'single',
  `parent_id` int DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '1',
  `kode_tampil` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=312 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_hasil_pengujian`
--

DROP TABLE IF EXISTS `kesmas_hasil_pengujian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_hasil_pengujian` (
  `id_pengujian` int NOT NULL,
  `id_master_pemeriksaan` int NOT NULL,
  `hasil` varchar(255) DEFAULT NULL,
  `baku_mutu` varchar(255) DEFAULT NULL,
  `metode` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pengujian`,`id_master_pemeriksaan`),
  KEY `id_master_pemeriksaan` (`id_master_pemeriksaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_item_permintaan`
--

DROP TABLE IF EXISTS `kesmas_item_permintaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_item_permintaan` (
  `id_permintaan` int NOT NULL,
  `id_master_pemeriksaan` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permintaan`,`id_master_pemeriksaan`),
  KEY `id_master_pemeriksaan` (`id_master_pemeriksaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_master_pemeriksaan`
--

DROP TABLE IF EXISTS `kesmas_master_pemeriksaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_master_pemeriksaan` (
  `id_master_pemeriksaan` int NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) DEFAULT NULL,
  `kelompok_pemeriksaan` varchar(100) DEFAULT NULL,
  `nama_pemeriksaan` varchar(255) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `baku_mutu` varchar(255) DEFAULT NULL,
  `metode` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_master_pemeriksaan`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_puskesmas_wilayah`
--

DROP TABLE IF EXISTS `kesmas_puskesmas_wilayah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_puskesmas_wilayah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_puskesmas` varchar(255) NOT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kelurahan_list` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Table structure for table `kesmas_pelanggan`
--

DROP TABLE IF EXISTS `kesmas_pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_pelanggan` (
  `id_pelanggan` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text,
  `telp` varchar(50) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `catatan_kaki` text,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kelurahan` varchar(255) DEFAULT NULL,
  `objek` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_pembayaran`
--

DROP TABLE IF EXISTS `kesmas_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `no_registrasi` varchar(100) DEFAULT NULL,
  `tgl_pembayaran` date DEFAULT NULL,
  `biaya` decimal(15,2) DEFAULT '0.00',
  `metode_pembayaran` varchar(100) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id_pembayaran`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_penanggung_jawab`
--

DROP TABLE IF EXISTS `kesmas_penanggung_jawab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_penanggung_jawab` (
  `id_penanggung_jawab` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penanggung_jawab`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_penerimaan_sampel`
--

DROP TABLE IF EXISTS `kesmas_penerimaan_sampel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_penerimaan_sampel` (
  `no_registrasi` varchar(100) NOT NULL,
  `tgl_registrasi` datetime DEFAULT CURRENT_TIMESTAMP,
  `tindakan_sampel` varchar(255) DEFAULT NULL,
  `kelayakan` varchar(50) DEFAULT NULL,
  `alasan` text,
  `status` varchar(50) DEFAULT NULL,
  `id_permintaan` int DEFAULT NULL,
  `id_petugas` int DEFAULT NULL,
  `id_petugas_2` int DEFAULT NULL,
  PRIMARY KEY (`no_registrasi`),
  KEY `id_permintaan` (`id_permintaan`),
  KEY `id_petugas` (`id_petugas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_pengujian_sampel`
--

DROP TABLE IF EXISTS `kesmas_pengujian_sampel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_pengujian_sampel` (
  `id_pengujian` int NOT NULL AUTO_INCREMENT,
  `tgl_mulai` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `tgl_pelaporan` datetime DEFAULT NULL,
  `keterangan` text,
  `catatan_kaki` text,
  `id_pembayaran` int DEFAULT NULL,
  `id_petugas` int DEFAULT NULL,
  `id_penanggung_jawab` int DEFAULT NULL,
  PRIMARY KEY (`id_pengujian`),
  KEY `id_pembayaran` (`id_pembayaran`),
  KEY `id_petugas` (`id_petugas`),
  KEY `id_penanggung_jawab` (`id_penanggung_jawab`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_permintaan`
--

DROP TABLE IF EXISTS `kesmas_permintaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_permintaan` (
  `id_permintaan` int NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int NOT NULL,
  `petugas_pendaftaran_id` int DEFAULT NULL,
  `nama_sampel` varchar(255) DEFAULT NULL,
  `tgl_permintaan` date NOT NULL,
  `ttd` varchar(255) DEFAULT NULL,
  `kategori_sampel` varchar(100) DEFAULT NULL,
  `jenis_sampel` varchar(100) DEFAULT NULL,
  `volume_ml` int DEFAULT NULL,
  `tgl_pengambilan` date DEFAULT NULL,
  `jam_pengambilan` time DEFAULT NULL,
  `lokasi_pengambilan` varchar(255) DEFAULT NULL,
  `info_tambahan` text,
  PRIMARY KEY (`id_permintaan`),
  KEY `id_pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_petugas`
--

DROP TABLE IF EXISTS `kesmas_petugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_petugas` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_petugas`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kesmas_survei_kepuasan`
--

DROP TABLE IF EXISTS `kesmas_survei_kepuasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kesmas_survei_kepuasan` (
  `id_survei` int NOT NULL AUTO_INCREMENT,
  `id_permintaan` int NOT NULL DEFAULT '0',
  `jam_survei` time DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `usia` varchar(20) DEFAULT NULL,
  `jenis_layanan` varchar(100) DEFAULT NULL,
  `q1` int DEFAULT '0',
  `q2` int DEFAULT '0',
  `q3` int DEFAULT '0',
  `q4` int DEFAULT '0',
  `q5` int DEFAULT '0',
  `q6` int DEFAULT '0',
  `q7` int DEFAULT '0',
  `q8` int DEFAULT '0',
  `q9` int DEFAULT '0',
  `komentar_saran` text,
  `tgl_survei` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_survei`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kunjungan_rm`
--

DROP TABLE IF EXISTS `kunjungan_rm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kunjungan_rm` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(50) DEFAULT NULL,
  `id_pasien` int DEFAULT NULL,
  `tanggal_kunjungan` datetime DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `status_soap` varchar(50) DEFAULT NULL,
  `icd_10` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `keluhan_utama` text,
  `riwayat_penyakit` text,
  `gcs` varchar(50) DEFAULT NULL,
  `tensi_sistole` varchar(50) DEFAULT NULL,
  `tensi_diastole` varchar(50) DEFAULT NULL,
  `nadi` varchar(50) DEFAULT NULL,
  `pernapasan` varchar(50) DEFAULT NULL,
  `suhu` varchar(50) DEFAULT NULL,
  `berat_badan` text,
  `tinggi_badan` text,
  `pemeriksaan_jantung` text,
  `pemeriksaan_paru` text,
  `pemeriksaan_abdomen` text,
  `catatan_tambahan` text,
  `asesmen_diagnosa` text,
  `plan_rencana` text,
  `hasil_uji_klinik` text,
  `nama_dokter_pemeriksa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `username` varchar(50) DEFAULT '',
  `status` enum('success','failed','locked') NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(500) NOT NULL DEFAULT '',
  `failure_reason` varchar(255) DEFAULT '',
  `login_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_logs_klinik`
--

DROP TABLE IF EXISTS `login_logs_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs_klinik` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('success','failed','locked') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'failed',
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `failure_reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `username` (`username`),
  KEY `status` (`status`),
  KEY `login_at` (`login_at`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pasien`
--

DROP TABLE IF EXISTS `pasien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien` (
  `id_pasien` int NOT NULL AUTO_INCREMENT,
  `no_rm` varchar(50) DEFAULT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `gender` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_telp` varchar(20) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `status_nikah` varchar(50) DEFAULT NULL,
  `pendidikan` varchar(100) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `petugas_pendaftaran` varchar(100) DEFAULT NULL,
  `umur` int NOT NULL,
  `status_pasien` enum('Rujukan','Mandiri') DEFAULT 'Mandiri',
  `id_dokter_pengirim` int DEFAULT NULL,
  `diagnosa` text,
  `obat` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `puskesmas_wilayah` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pasien`),
  UNIQUE KEY `no_rm` (`no_rm`),
  UNIQUE KEY `idx_no_rm` (`no_rm`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_pasien` int DEFAULT NULL,
  `no_register` varchar(50) DEFAULT NULL,
  `keterangan` text,
  `total_biaya` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` enum('Tunai','BPJS','Lain-lain') DEFAULT NULL,
  `status` enum('Belum Lunas','Lunas') DEFAULT NULL,
  `tanggal_pelunasan` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `penanggung_teknis_klinik`
--

DROP TABLE IF EXISTS `penanggung_teknis_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penanggung_teknis_klinik` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `sip` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_petugas`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `petugas_dokter`
--

DROP TABLE IF EXISTS `petugas_dokter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_dokter` (
  `id_dokter` int NOT NULL AUTO_INCREMENT,
  `nama_dokter` varchar(255) NOT NULL,
  `spesialisasi` varchar(255) NOT NULL,
  `sip` varchar(100) NOT NULL,
  PRIMARY KEY (`id_dokter`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `petugas_sampel_klinik`
--

DROP TABLE IF EXISTS `petugas_sampel_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_sampel_klinik` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_petugas`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `petugas_validasi`
--

DROP TABLE IF EXISTS `petugas_validasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_validasi` (
  `id_validasi` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_validasi`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `petugas_verifikasi`
--

DROP TABLE IF EXISTS `petugas_verifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `petugas_verifikasi` (
  `id_verifikasi` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_verifikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `puskesmas_wilayah`
--

DROP TABLE IF EXISTS `puskesmas_wilayah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `puskesmas_wilayah` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_puskesmas` varchar(255) NOT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kelurahan_list` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_kesmas`
--

DROP TABLE IF EXISTS `user_kesmas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_kesmas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(255) NOT NULL DEFAULT '',
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','petugas','viewer') NOT NULL DEFAULT 'petugas',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `klinik_role` enum('admin','petugas pendaftaran','pemeriksa sampel','petugas rm') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `verifikator_klinik`
--

DROP TABLE IF EXISTS `verifikator_klinik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verifikator_klinik` (
  `id_petugas` int NOT NULL AUTO_INCREMENT,
  `nama_petugas` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_petugas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-12 15:09:43
