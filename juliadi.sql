/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : 127.0.0.1:3306
Source Database       : juliadi

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2018-07-27 18:12:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for m_config
-- ----------------------------
DROP TABLE IF EXISTS `m_config`;
CREATE TABLE `m_config` (
  `index` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_config
-- ----------------------------
INSERT INTO `m_config` VALUES ('kode_satuan_kerja', '12345');
INSERT INTO `m_config` VALUES ('kantor_satuan_kerja', 'RS. Bhayangkara TK I Sumatera Utara');
INSERT INTO `m_config` VALUES ('nomor_dipa', '678910');
INSERT INTO `m_config` VALUES ('tanggal_dipa', '01 Januari 2017');
INSERT INTO `m_config` VALUES ('klasifikasi_anggaran', '1.22.333.4444.55555');
INSERT INTO `m_config` VALUES ('unit_kerja', 'RS. Bhayangkara TK I Sumatera Utara');
INSERT INTO `m_config` VALUES ('alamat_unit_kerja', 'Jalan K.H. Wahid Hasyim No.1, Merdeka, Medan Baru, Kota Medan, Sumatera Utara 20222');

-- ----------------------------
-- Table structure for m_golongan
-- ----------------------------
DROP TABLE IF EXISTS `m_golongan`;
CREATE TABLE `m_golongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_pegawai` int(11) DEFAULT NULL,
  `golongan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_golongan
-- ----------------------------
INSERT INTO `m_golongan` VALUES ('1', '1', 'IV C');
INSERT INTO `m_golongan` VALUES ('2', '1', 'IV B');
INSERT INTO `m_golongan` VALUES ('3', '1', 'IV A');
INSERT INTO `m_golongan` VALUES ('4', '1', 'III C');
INSERT INTO `m_golongan` VALUES ('5', '1', 'II F');
INSERT INTO `m_golongan` VALUES ('6', '1', 'II E');
INSERT INTO `m_golongan` VALUES ('7', '1', 'II D');
INSERT INTO `m_golongan` VALUES ('8', '1', 'II B');
INSERT INTO `m_golongan` VALUES ('9', '1', 'IV/A');
INSERT INTO `m_golongan` VALUES ('10', '1', 'III/D');
INSERT INTO `m_golongan` VALUES ('11', '1', 'III/C');
INSERT INTO `m_golongan` VALUES ('12', '1', 'III/B');
INSERT INTO `m_golongan` VALUES ('13', '1', 'III/A');
INSERT INTO `m_golongan` VALUES ('14', '1', 'II/D');
INSERT INTO `m_golongan` VALUES ('15', '1', 'II/C');
INSERT INTO `m_golongan` VALUES ('16', '1', 'II/B');
INSERT INTO `m_golongan` VALUES ('17', '1', 'II/A');
INSERT INTO `m_golongan` VALUES ('18', '1', 'I/D');
INSERT INTO `m_golongan` VALUES ('19', '1', 'I/C');
INSERT INTO `m_golongan` VALUES ('20', '1', 'I/C');

-- ----------------------------
-- Table structure for m_jenis_pajak
-- ----------------------------
DROP TABLE IF EXISTS `m_jenis_pajak`;
CREATE TABLE `m_jenis_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_jenis_pajak
-- ----------------------------
INSERT INTO `m_jenis_pajak` VALUES ('1', 'Konsultan, Tenaga Ahli, Badan Hukum');
INSERT INTO `m_jenis_pajak` VALUES ('2', 'Jasa Teknik, Manajemen, Penelitian');
INSERT INTO `m_jenis_pajak` VALUES ('3', 'Sewa Peralatan dan Perlengkapan');
INSERT INTO `m_jenis_pajak` VALUES ('4', 'Sewa Kendaraan');

-- ----------------------------
-- Table structure for m_jenis_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `m_jenis_pegawai`;
CREATE TABLE `m_jenis_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_pegawai` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_jenis_pegawai
-- ----------------------------
INSERT INTO `m_jenis_pegawai` VALUES ('1', 'RS. Bhayangkara Tk II Medan');

-- ----------------------------
-- Table structure for m_memiliki_pajak
-- ----------------------------
DROP TABLE IF EXISTS `m_memiliki_pajak`;
CREATE TABLE `m_memiliki_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_pajak` int(11) DEFAULT NULL,
  `id_pajak` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_memiliki_pajak
-- ----------------------------
INSERT INTO `m_memiliki_pajak` VALUES ('1', '1', '1');
INSERT INTO `m_memiliki_pajak` VALUES ('2', '1', '4');
INSERT INTO `m_memiliki_pajak` VALUES ('3', '2', '1');
INSERT INTO `m_memiliki_pajak` VALUES ('4', '2', '4');
INSERT INTO `m_memiliki_pajak` VALUES ('5', '3', '1');
INSERT INTO `m_memiliki_pajak` VALUES ('6', '3', '4');
INSERT INTO `m_memiliki_pajak` VALUES ('7', '4', '1');
INSERT INTO `m_memiliki_pajak` VALUES ('8', '4', '4');

-- ----------------------------
-- Table structure for m_pajak
-- ----------------------------
DROP TABLE IF EXISTS `m_pajak`;
CREATE TABLE `m_pajak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pajak` varchar(255) DEFAULT NULL,
  `besar` double DEFAULT NULL,
  `tipe` enum('PPN','PPh') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_pajak
-- ----------------------------
INSERT INTO `m_pajak` VALUES ('1', 'PPN', '10', 'PPN');
INSERT INTO `m_pajak` VALUES ('2', 'PPh 21', '15', 'PPh');
INSERT INTO `m_pajak` VALUES ('3', 'PPh 22', '1.5', 'PPh');
INSERT INTO `m_pajak` VALUES ('4', 'PPh 23', '2', 'PPh');
INSERT INTO `m_pajak` VALUES ('5', 'PPh 4 (2) Final', '4', 'PPh');

-- ----------------------------
-- Table structure for m_pangkat_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `m_pangkat_pegawai`;
CREATE TABLE `m_pangkat_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_pegawai` int(11) DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_pangkat_pegawai
-- ----------------------------
INSERT INTO `m_pangkat_pegawai` VALUES ('1', '1', 'KOMBES');
INSERT INTO `m_pangkat_pegawai` VALUES ('2', '1', 'AKBP');
INSERT INTO `m_pangkat_pegawai` VALUES ('3', '1', 'KOMPOL');
INSERT INTO `m_pangkat_pegawai` VALUES ('4', '1', 'AKP');
INSERT INTO `m_pangkat_pegawai` VALUES ('5', '1', 'AIPTU');
INSERT INTO `m_pangkat_pegawai` VALUES ('6', '1', 'AIPDA');
INSERT INTO `m_pangkat_pegawai` VALUES ('7', '1', 'BRIPKA');
INSERT INTO `m_pangkat_pegawai` VALUES ('8', '1', 'BRIPTU');

-- ----------------------------
-- Table structure for m_role_user
-- ----------------------------
DROP TABLE IF EXISTS `m_role_user`;
CREATE TABLE `m_role_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m_role_user
-- ----------------------------
INSERT INTO `m_role_user` VALUES ('1', 'Admin');
INSERT INTO `m_role_user` VALUES ('2', 'Operator');

-- ----------------------------
-- Table structure for temp_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `temp_pegawai`;
CREATE TABLE `temp_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pegawai` varchar(255) DEFAULT NULL,
  `kelas_jabatan` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `pangkat_golongan` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of temp_pegawai
-- ----------------------------
INSERT INTO `temp_pegawai` VALUES ('1', 'dr.A.NYOMAN EDDY P.,W.,DFM.,SpF', '13', '68070471', '88.738.751.2-615.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMBES / IV C', 'KA RUMKIT BHAYANGKARA TK. II MEDAN');
INSERT INTO `temp_pegawai` VALUES ('2', 'Drs. EDISON SEMBIRING', '11', '61040831', '77.241.804.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'KASUBBAG WASINTERN');
INSERT INTO `temp_pegawai` VALUES ('3', 'ATIN NURYATIN', '11', '63050758', '69.564.610.9-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('4', 'REPINA SIHOTANG', '11', '66110520', '48.337.320.5-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('5', 'DRS MUHAMMAD PAHRI', '11', '65110908', '15.297.442.2-128.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'KASUBBID JANGMEDUM');
INSERT INTO `temp_pegawai` VALUES ('6', 'MARASI SINAGA', '11', '69060418', '77.243.665.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('7', 'DR ZULKHAIRI', '12', '67060686', '79.358.168.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'WAKA RUMKIT BHAYANGKARA');
INSERT INTO `temp_pegawai` VALUES ('8', 'SUGENG', '11', '65080862', '08.029.934.0-114.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'KASUBBID YANMEDDOKPOL');
INSERT INTO `temp_pegawai` VALUES ('9', 'DR SITI NURIMANTA', '11', '68050676', '25.974.618.8-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('10', 'DR YAMATO SATRIA DHA', '11', '67020517', '08.140.611.8-212.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('11', 'drg PITA VIOLENTA SITORUS', '11', '73020690', '78.647.285.2-952.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKBP / IV B', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('12', 'ZULKARNAEN SIREGAR', '10', '61090389', '77.246.677.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMPOL / IV A', 'KAUR YANDOKPOL');
INSERT INTO `temp_pegawai` VALUES ('13', 'HERMINA', '10', '62060187', '79.353.549.3-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMPOL / IV A', 'KSSBG RENMIN');
INSERT INTO `temp_pegawai` VALUES ('14', 'MARINA BANGUN', '10', '67090123', '14.465.034.8-727.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMPOL / IV A', 'KAUR WASBIN');
INSERT INTO `temp_pegawai` VALUES ('15', 'MAHYU DANIL NOOR SSI', '10', '73010712', '08.035.634.8-114.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMPOL / IV A', 'KAUR WASOPSYAN');
INSERT INTO `temp_pegawai` VALUES ('16', 'EVI MELVA FRIDA MANURUNG', '10', '78031187', '79.926.968.3-926.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'KOMPOL / IV A', 'KSSBG BINFUNG');
INSERT INTO `temp_pegawai` VALUES ('17', 'HASAN MUDA NASUTION', '9', '61060626', '66.861.797.0-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKP / III C', 'PAUR 3 SUBBIDYANDOKPOL');
INSERT INTO `temp_pegawai` VALUES ('18', 'RUSDI', '10', '62070192', '00.319.998.1-118.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AKP / III C', 'KAUR JANGUM ');
INSERT INTO `temp_pegawai` VALUES ('19', 'RAHMAD HIDAYAT', '8', '67010270', '77.244.246.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AIPTU / II F', 'PS.PAMIN 6');
INSERT INTO `temp_pegawai` VALUES ('20', 'OSMAN SIAGIAN', '8', '69100329', '45.495.744.0-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AIPTU / II F', 'PAMIN WASBIN');
INSERT INTO `temp_pegawai` VALUES ('21', 'ANI ARIANI', '8', '77030431', '79.803.493.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AIPTU / II F', 'PS.PAMIN 1 BINFUNG');
INSERT INTO `temp_pegawai` VALUES ('22', 'JUITA F.MAGDALENA', '7', '80010269', '58.958.027.3-922.001', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AIPDA / II E', 'BAMIN');
INSERT INTO `temp_pegawai` VALUES ('23', 'TIRAS GESTI', '8', '79020496', '77.243.666.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'AIPDA / II E', 'PS. PAMIN 2 SUBBAGRENMIN');
INSERT INTO `temp_pegawai` VALUES ('24', 'RINTO HADI NASUTION', '6', '82110544', '00.000.000.0-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'BRIPKA / II D', 'BAMIN');
INSERT INTO `temp_pegawai` VALUES ('25', 'JULIADI', '8', '84110420', '78.277.574.6-118.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'BRIPKA / II D', 'KAUR KEU');
INSERT INTO `temp_pegawai` VALUES ('26', 'HENDRO NAINGGOLAN', '5', '82100796', '00.000.000.0-112.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'BRIPTU / II B', 'BAMIN');
INSERT INTO `temp_pegawai` VALUES ('27', 'DRG TITIK WAHYU WARDANI', '11', '196902141993122001', '77.244.566.4-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'IV/a', 'AHLI MADYA');
INSERT INTO `temp_pegawai` VALUES ('28', 'DR YASIN LEONARDI Sp', '9', '196908211996031005', '37.504.680.2-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'IV/a', 'AHLI PERTAMA');
INSERT INTO `temp_pegawai` VALUES ('29', 'RIMENDA BR KARO', '9', '196011241981032001', '77.244.245.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('30', 'RATNA NEFO SEMBIRING', '10', '196306111987032002', '77.244.571.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'KAUR JANGMED');
INSERT INTO `temp_pegawai` VALUES ('31', 'ARTA R.SIHOMBING', '10', '196512041989032002', '77.244.250.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PS. KAURYANWAT');
INSERT INTO `temp_pegawai` VALUES ('32', 'DRG HERTAMINA L SIHN', '10', '197103262005012003', '66.408.096.7-113.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'AHLI MUDA 1');
INSERT INTO `temp_pegawai` VALUES ('33', 'dr. UTAMI DEWI', '9', '197607152008012001', '35.895.261.2-517.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'AHLI PERTAMA 3');
INSERT INTO `temp_pegawai` VALUES ('34', 'JUNIATY', '9', '196006251981032004', '77.244.568.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('35', 'Dr SUPERIDA BR GINTING', '9', '197405042006042002', '35.790.465.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'AHLI PERTAMA 9');
INSERT INTO `temp_pegawai` VALUES ('36', 'TIORLINA PURBA', '9', '196212061988032001', '77.243.672.1-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PAUR 1 YANDOKPOL');
INSERT INTO `temp_pegawai` VALUES ('37', 'JALILAH', '9', '196502151989032001', '77.243.673.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'AHLI PERTAMA 6');
INSERT INTO `temp_pegawai` VALUES ('38', 'ROSMIANNA BR PURBA', '9', '196605111988032003', '77.244.253.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('39', 'NURANI BR TARIGAN', '9', '196003041992012001', '77.244.251.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('40', 'MISNAWATI PARDEDE', '10', '196806081990032010', '77.244.254.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PS. KAUR YANMED');
INSERT INTO `temp_pegawai` VALUES ('41', 'J U L I A N A', '9', '196807131990032003', '77.244.255.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/d', 'PAUR 1 JANGMED');
INSERT INTO `temp_pegawai` VALUES ('42', 'KASTA BR GINTING', '9', '196108051987032003', '77.244.569.8-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('43', 'SUMANGELI LAOWO', '9', '196204061989031005', '77.244.572.2-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'AHLI PERTAMA');
INSERT INTO `temp_pegawai` VALUES ('44', 'BETTI MURNI', '9', '196501111987032003', '77.244.248.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA 7');
INSERT INTO `temp_pegawai` VALUES ('45', 'SALMIYAH PANJAITAN', '9', '196502201987032003', '77.244.256.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'KAUR MIN');
INSERT INTO `temp_pegawai` VALUES ('46', 'MARLAINI', '9', '196503081989032002', '77.244.584.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'KAUR DIKLIT');
INSERT INTO `temp_pegawai` VALUES ('47', 'RISMA SIAGIAN', '9', '196507181988032003', '77.244.249.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('48', 'MARSELINA MANIK', '9', '196109101987032002', '77.244.252.1-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('49', 'ROSMITA KARO-KARO', '9', '196808181989032003', '77.241.809.1-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('50', 'MIHAL GINTING', '8', '196603041989032003', '48.895.720.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PELAKSANA LANJUTAN');
INSERT INTO `temp_pegawai` VALUES ('51', 'ARUS MALEM', '9', '196008081988032001', '09.705.519.8-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('52', 'PARISMAIDA NABABAN', '9', '196605211988032002', '77.244.238.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PAUR YANMED');
INSERT INTO `temp_pegawai` VALUES ('53', 'SRIANA ROSA', '8', '196510141996032001', '77.243.661.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/c', 'PAMIN JANGMED');
INSERT INTO `temp_pegawai` VALUES ('54', 'YANA ULINA', '8', '196304101987032002', '25.540.712.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'PAMIN 2 JANGMED');
INSERT INTO `temp_pegawai` VALUES ('55', 'JEMONO', '9', '196606231988031005', '77.244.257.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'PAUR JANGMED');
INSERT INTO `temp_pegawai` VALUES ('56', 'MARIANTA BR SEBAYANG', '8', '196108281988032004', '77.241.812.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'PAMIN 1 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('57', 'JUMILAH', '9', '196707051992032003', '77.241.807.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'KAURTU');
INSERT INTO `temp_pegawai` VALUES ('58', 'RAKUTTA TARIGAN', '8', '197111111992031003', '77.244.242.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'PAMIN YANDOKPOL');
INSERT INTO `temp_pegawai` VALUES ('59', 'DESY IDA TOBING', '9', '197405151994032002', '77.243.660.6-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'KAUR SIM & RM');
INSERT INTO `temp_pegawai` VALUES ('60', 'ULI F SIMBOLON AMD', '9', '197708192006042012', '57.783.400.5-113.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'KAUR REN');
INSERT INTO `temp_pegawai` VALUES ('61', 'dr. IFAN EKA SYAHPUTRA', '9', '196806132014121001', '08.003.068.7-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/b', 'AHLI PERTAMA 4');
INSERT INTO `temp_pegawai` VALUES ('62', 'ELVIDA', '8', '197610041999032003', '77.244.579.7-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PAMIN YANMED');
INSERT INTO `temp_pegawai` VALUES ('63', 'TUSIYAH', '8', '197504072007012020', '35.517.432.7-118.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PAMIN 2 BINFUNG');
INSERT INTO `temp_pegawai` VALUES ('64', 'INDRI LESTARI', '8', '197906181999032001', '77.244.234.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PS PAMIN WASOPSYAN');
INSERT INTO `temp_pegawai` VALUES ('65', 'LASRIA PANJAITAN', '8', '196609121988032001', '45.454.422.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PAMIN 2 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('66', 'WARTI BR BRAHMANA', '8', '196811121992022001', '44.720.460.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PELAKSANA');
INSERT INTO `temp_pegawai` VALUES ('67', 'BERLIANA C M SITORUS', '8', '197511301999032003', '77.243.662.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PS PAMIN 5 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('68', 'DEWI DAMAYANTI HSB', '8', '197805231999032003', '45.487.322.5-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PELAKSANA');
INSERT INTO `temp_pegawai` VALUES ('69', 'RONNIDA NABABAN', '8', '197708261999032001', '77.244.578.9-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PS PAMIN 4 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('70', 'HENNY SINULINGGA', '8', '197710101999032003', '77.243.663.0-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PAMIN 3 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('71', 'EVI IRAWATI S AMD', '7', '197506272006042001', '07.379.121.2-122.001', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PELAKSANA');
INSERT INTO `temp_pegawai` VALUES ('72', 'ROTUA B F SARAGIH', '8', '197708292005012002', '46.041.671.2-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PS PAMIN 7 RENMIN');
INSERT INTO `temp_pegawai` VALUES ('73', 'HERLINA ROSWATI', '9', '196505091987112000', '46.041.671.2-122.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'III/a', 'PENYELIA');
INSERT INTO `temp_pegawai` VALUES ('74', 'P EVA C SIMAREMARE', '8', '196709041992012001', '77.241.808.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'PELAKSANA LANJUTAN');
INSERT INTO `temp_pegawai` VALUES ('75', 'MARIA NOVA', '7', '196710201998032001', '77.244.577.1-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'PELAKSANA');
INSERT INTO `temp_pegawai` VALUES ('76', 'YANTI ENITA BR GINTING', '7', '198108102003121003', '57.403.983.0-085.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('77', 'LENA SINTAULI NAPITU', '7', '198210212003122001', '77.241.810.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('78', 'BELSIDA TAMBUNAN', '7', '198006082005012019', '57.828.688.2-211.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('79', 'ROHANA M SIAHAAN', '7', '198112262005012004', '77.244.237.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('80', 'EDI FAHRIZAL', '6', '196504012007011002', '35.912.122.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('81', 'K U N I N G', '6', '196510121991032001', '77.244.236.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('82', 'A B D I', '6', '196612011989031005', '77.244.580.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('83', 'NUR \'AINI', '6', '196409271994032001', '67.789.198.8-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('84', 'RUMIRIS D I PANJAITAN', '6', '197510262007012001', '35.927.586.4-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('85', 'TETY APRILTA BR SEMBIRING Amd.Kep', '6', '197704092014122001', '73.255.852.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('86', 'JUNITA EVERIDA SILALAHI,Amd.Kep', '6', '197906302014122002', '73.249.296.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('87', 'ROSNALIA PURBA', '6', '197705152014122001', '73.249.374.7-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('88', 'SERITA BR SEBAYANG A.Md.Kep', '6', '197803232014122001', '73.255.843.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('89', 'ENDANG ERLITNA A.Md.Kep', '6', '198009182014122001', '09.892.395.6-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('90', 'SARIANNA KOTO A.Md.Kep', '6', '197903272014122002', '73.249.204.6-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('91', 'SYAMSIAH TAMBUNAN, A.Md.Kep', '6', '198012312014122002', '73.324.536.9-124.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('92', 'JULIANTA BR.SEMBIRING MELIALA', '6', '197907232002122003', '77.241.813.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('93', 'ARIFIN HUTASOIT', '6', '197705062006041011', '36.387.399.3-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('94', 'LOUPIGA KISSE DERIPASLA', '6', '197705052014121001', '73.240.790.3-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('95', 'SRIYANTI', '6', '197607222008102001', '66.417.946.2-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('96', 'MANONGGOR PANJAITAN', '6', '197005012008101001', '45.922.791.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/c', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('97', 'N A R D A T I', '5', '198008082009102001', '44.772.584.7-113.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/b', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('98', 'SRI WINARTI', '5', '197205182007012002', '35.912.062.3-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/a', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('99', 'PAINI ERMIYATI', '5', '197008162014122004', '73.255.951.3-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/a', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('100', 'ADNIZEN', '5', '196604072014121002', '73.248.745.9-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/a', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('101', 'ISMAINI', '5', '198204242014122002', '73.255.931.5-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'II/a', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('102', 'EMPINA LASMAIDA SILITONGA', '4', '198208292009102001', '45.499.222.3-125.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'I/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('103', 'SYAMSURIA HUTASOIT', '3', '196710201998032001', '73.248.979.4-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'I/d', 'BANUM RUMKIT');
INSERT INTO `temp_pegawai` VALUES ('104', 'INDRANI', '1', '197110272014122002', '73.249.092.5-121.000', 'Jl. K.H. Wahid Hasim no. 1 Medan', 'I/c', 'BANUM RUMKIT');

-- ----------------------------
-- Table structure for t_detail_permohonan_dana
-- ----------------------------
DROP TABLE IF EXISTS `t_detail_permohonan_dana`;
CREATE TABLE `t_detail_permohonan_dana` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_permohonan_dana` int(11) DEFAULT NULL,
  `id_jenis_pajak` int(11) DEFAULT NULL,
  `penerima` varchar(255) DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `harga_satuan` double DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `uraian` varchar(255) DEFAULT NULL,
  `user_insert` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_detail_permohonan_dana
-- ----------------------------
INSERT INTO `t_detail_permohonan_dana` VALUES ('1', '1', '0', 'Pegawai', '1', 'Unit', '50000000', '0', 'Gaji Pegawai', '1');

-- ----------------------------
-- Table structure for t_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `t_pegawai`;
CREATE TABLE `t_pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jenis_pegawai` int(11) DEFAULT NULL,
  `id_pangkat` int(11) DEFAULT NULL,
  `nama_pegawai` varchar(255) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `id_golongan` int(11) DEFAULT NULL,
  `no_rekening` varchar(255) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_pegawai
-- ----------------------------
INSERT INTO `t_pegawai` VALUES ('1', '1', '1', 'dr.A.NYOMAN EDDY P.,W.,DFM.,SpF', '68070471', '1', '', '88.738.751.2-615.000', 'KA RUMKIT BHAYANGKARA TK. II MEDAN');
INSERT INTO `t_pegawai` VALUES ('2', '1', '2', 'Drs. EDISON SEMBIRING', '61040831', '2', '', '77.241.804.2-121.000', 'KASUBBAG WASINTERN');
INSERT INTO `t_pegawai` VALUES ('3', '1', '2', 'ATIN NURYATIN', '63050758', '2', '', '69.564.610.9-122.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('4', '1', '2', 'REPINA SIHOTANG', '66110520', '2', '', '48.337.320.5-124.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('5', '1', '2', 'DRS MUHAMMAD PAHRI', '65110908', '2', '', '15.297.442.2-128.000', 'KASUBBID JANGMEDUM');
INSERT INTO `t_pegawai` VALUES ('6', '1', '2', 'MARASI SINAGA', '69060418', '2', '', '77.243.665.5-121.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('7', '1', '2', 'DR ZULKHAIRI', '67060686', '2', '', '79.358.168.7-121.000', 'WAKA RUMKIT BHAYANGKARA');
INSERT INTO `t_pegawai` VALUES ('8', '1', '2', 'SUGENG', '65080862', '2', '', '08.029.934.0-114.000', 'KASUBBID YANMEDDOKPOL');
INSERT INTO `t_pegawai` VALUES ('9', '1', '2', 'DR SITI NURIMANTA', '68050676', '2', '', '25.974.618.8-121.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('10', '1', '2', 'DR YAMATO SATRIA DHA', '67020517', '2', '', '08.140.611.8-212.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('11', '1', '2', 'drg PITA VIOLENTA SITORUS', '73020690', '2', '', '78.647.285.2-952.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('12', '1', '3', 'ZULKARNAEN SIREGAR', '61090389', '3', '', '77.246.677.7-121.000', 'KAUR YANDOKPOL');
INSERT INTO `t_pegawai` VALUES ('13', '1', '3', 'HERMINA', '62060187', '3', '', '79.353.549.3-122.000', 'KSSBG RENMIN');
INSERT INTO `t_pegawai` VALUES ('14', '1', '3', 'MARINA BANGUN', '67090123', '3', '', '14.465.034.8-727.000', 'KAUR WASBIN');
INSERT INTO `t_pegawai` VALUES ('15', '1', '3', 'MAHYU DANIL NOOR SSI', '73010712', '3', '', '08.035.634.8-114.000', 'KAUR WASOPSYAN');
INSERT INTO `t_pegawai` VALUES ('16', '1', '3', 'EVI MELVA FRIDA MANURUNG', '78031187', '3', '', '79.926.968.3-926.000', 'KSSBG BINFUNG');
INSERT INTO `t_pegawai` VALUES ('17', '1', '4', 'HASAN MUDA NASUTION', '61060626', '4', '', '66.861.797.0-124.000', 'PAUR 3 SUBBIDYANDOKPOL');
INSERT INTO `t_pegawai` VALUES ('18', '1', '4', 'RUSDI', '62070192', '4', '', '00.319.998.1-118.000', 'KAUR JANGUM ');
INSERT INTO `t_pegawai` VALUES ('19', '1', '5', 'RAHMAD HIDAYAT', '67010270', '5', '', '77.244.246.3-121.000', 'PS.PAMIN 6');
INSERT INTO `t_pegawai` VALUES ('20', '1', '5', 'OSMAN SIAGIAN', '69100329', '5', '', '45.495.744.0-125.000', 'PAMIN WASBIN');
INSERT INTO `t_pegawai` VALUES ('21', '1', '5', 'ANI ARIANI', '77030431', '5', '', '79.803.493.0-121.000', 'PS.PAMIN 1 BINFUNG');
INSERT INTO `t_pegawai` VALUES ('22', '1', '6', 'JUITA F.MAGDALENA', '80010269', '6', '', '58.958.027.3-922.001', 'BAMIN');
INSERT INTO `t_pegawai` VALUES ('23', '1', '6', 'TIRAS GESTI', '79020496', '6', '', '77.243.666.3-121.000', 'PS. PAMIN 2 SUBBAGRENMIN');
INSERT INTO `t_pegawai` VALUES ('24', '1', '7', 'RINTO HADI NASUTION', '82110544', '7', '', '00.000.000.0-122.000', 'BAMIN');
INSERT INTO `t_pegawai` VALUES ('25', '1', '7', 'JULIADI', '84110420', '7', '', '78.277.574.6-118.000', 'KAUR KEU');
INSERT INTO `t_pegawai` VALUES ('26', '1', '8', 'HENDRO NAINGGOLAN', '82100796', '8', '', '00.000.000.0-112.000', 'BAMIN');
INSERT INTO `t_pegawai` VALUES ('27', '1', '0', 'DRG TITIK WAHYU WARDANI', '196902141993122001', '9', '', '77.244.566.4-124.000', 'AHLI MADYA');
INSERT INTO `t_pegawai` VALUES ('28', '1', '0', 'DR YASIN LEONARDI Sp', '196908211996031005', '9', '', '37.504.680.2-122.000', 'AHLI PERTAMA');
INSERT INTO `t_pegawai` VALUES ('29', '1', '0', 'RIMENDA BR KARO', '196011241981032001', '10', '', '77.244.245.5-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('30', '1', '0', 'RATNA NEFO SEMBIRING', '196306111987032002', '10', '', '77.244.571.4-121.000', 'KAUR JANGMED');
INSERT INTO `t_pegawai` VALUES ('31', '1', '0', 'ARTA R.SIHOMBING', '196512041989032002', '10', '', '77.244.250.5-121.000', 'PS. KAURYANWAT');
INSERT INTO `t_pegawai` VALUES ('32', '1', '0', 'DRG HERTAMINA L SIHN', '197103262005012003', '10', '', '66.408.096.7-113.000', 'AHLI MUDA 1');
INSERT INTO `t_pegawai` VALUES ('33', '1', '0', 'dr. UTAMI DEWI', '197607152008012001', '10', '', '35.895.261.2-517.000', 'AHLI PERTAMA 3');
INSERT INTO `t_pegawai` VALUES ('34', '1', '0', 'JUNIATY', '196006251981032004', '10', '', '77.244.568.0-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('35', '1', '0', 'Dr SUPERIDA BR GINTING', '197405042006042002', '10', '', '35.790.465.5-121.000', 'AHLI PERTAMA 9');
INSERT INTO `t_pegawai` VALUES ('36', '1', '0', 'TIORLINA PURBA', '196212061988032001', '10', '', '77.243.672.1-121.000', 'PAUR 1 YANDOKPOL');
INSERT INTO `t_pegawai` VALUES ('37', '1', '0', 'JALILAH', '196502151989032001', '10', '', '77.243.673.9-121.000', 'AHLI PERTAMA 6');
INSERT INTO `t_pegawai` VALUES ('38', '1', '0', 'ROSMIANNA BR PURBA', '196605111988032003', '10', '', '77.244.253.9-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('39', '1', '0', 'NURANI BR TARIGAN', '196003041992012001', '10', '', '77.244.251.3-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('40', '1', '0', 'MISNAWATI PARDEDE', '196806081990032010', '10', '', '77.244.254.7-121.000', 'PS. KAUR YANMED');
INSERT INTO `t_pegawai` VALUES ('41', '1', '0', 'J U L I A N A', '196807131990032003', '10', '', '77.244.255.4-121.000', 'PAUR 1 JANGMED');
INSERT INTO `t_pegawai` VALUES ('42', '1', '0', 'KASTA BR GINTING', '196108051987032003', '11', '', '77.244.569.8-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('43', '1', '0', 'SUMANGELI LAOWO', '196204061989031005', '11', '', '77.244.572.2-125.000', 'AHLI PERTAMA');
INSERT INTO `t_pegawai` VALUES ('44', '1', '0', 'BETTI MURNI', '196501111987032003', '11', '', '77.244.248.9-121.000', 'PENYELIA 7');
INSERT INTO `t_pegawai` VALUES ('45', '1', '0', 'SALMIYAH PANJAITAN', '196502201987032003', '11', '', '77.244.256.2-121.000', 'KAUR MIN');
INSERT INTO `t_pegawai` VALUES ('46', '1', '0', 'MARLAINI', '196503081989032002', '11', '', '77.244.584.7-121.000', 'KAUR DIKLIT');
INSERT INTO `t_pegawai` VALUES ('47', '1', '0', 'RISMA SIAGIAN', '196507181988032003', '11', '', '77.244.249.7-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('48', '1', '0', 'MARSELINA MANIK', '196109101987032002', '11', '', '77.244.252.1-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('49', '1', '0', 'ROSMITA KARO-KARO', '196808181989032003', '11', '', '77.241.809.1-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('50', '1', '0', 'MIHAL GINTING', '196603041989032003', '11', '', '48.895.720.0-121.000', 'PELAKSANA LANJUTAN');
INSERT INTO `t_pegawai` VALUES ('51', '1', '0', 'ARUS MALEM', '196008081988032001', '11', '', '09.705.519.8-121.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('52', '1', '0', 'PARISMAIDA NABABAN', '196605211988032002', '11', '', '77.244.238.0-121.000', 'PAUR YANMED');
INSERT INTO `t_pegawai` VALUES ('53', '1', '0', 'SRIANA ROSA', '196510141996032001', '11', '', '77.243.661.4-121.000', 'PAMIN JANGMED');
INSERT INTO `t_pegawai` VALUES ('54', '1', '0', 'YANA ULINA', '196304101987032002', '12', '', '25.540.712.4-121.000', 'PAMIN 2 JANGMED');
INSERT INTO `t_pegawai` VALUES ('55', '1', '0', 'JEMONO', '196606231988031005', '12', '', '77.244.257.0-121.000', 'PAUR JANGMED');
INSERT INTO `t_pegawai` VALUES ('56', '1', '0', 'MARIANTA BR SEBAYANG', '196108281988032004', '12', '', '77.241.812.5-121.000', 'PAMIN 1 RENMIN');
INSERT INTO `t_pegawai` VALUES ('57', '1', '0', 'JUMILAH', '196707051992032003', '12', '', '77.241.807.5-121.000', 'KAURTU');
INSERT INTO `t_pegawai` VALUES ('58', '1', '0', 'RAKUTTA TARIGAN', '197111111992031003', '12', '', '77.244.242.2-121.000', 'PAMIN YANDOKPOL');
INSERT INTO `t_pegawai` VALUES ('59', '1', '0', 'DESY IDA TOBING', '197405151994032002', '12', '', '77.243.660.6-121.000', 'KAUR SIM & RM');
INSERT INTO `t_pegawai` VALUES ('60', '1', '0', 'ULI F SIMBOLON AMD', '197708192006042012', '12', '', '57.783.400.5-113.000', 'KAUR REN');
INSERT INTO `t_pegawai` VALUES ('61', '1', '0', 'dr. IFAN EKA SYAHPUTRA', '196806132014121001', '12', '', '08.003.068.7-124.000', 'AHLI PERTAMA 4');
INSERT INTO `t_pegawai` VALUES ('62', '1', '0', 'ELVIDA', '197610041999032003', '13', '', '77.244.579.7-125.000', 'PAMIN YANMED');
INSERT INTO `t_pegawai` VALUES ('63', '1', '0', 'TUSIYAH', '197504072007012020', '13', '', '35.517.432.7-118.000', 'PAMIN 2 BINFUNG');
INSERT INTO `t_pegawai` VALUES ('64', '1', '0', 'INDRI LESTARI', '197906181999032001', '13', '', '77.244.234.9-121.000', 'PS PAMIN WASOPSYAN');
INSERT INTO `t_pegawai` VALUES ('65', '1', '0', 'LASRIA PANJAITAN', '196609121988032001', '13', '', '45.454.422.4-121.000', 'PAMIN 2 RENMIN');
INSERT INTO `t_pegawai` VALUES ('66', '1', '0', 'WARTI BR BRAHMANA', '196811121992022001', '13', '', '44.720.460.3-121.000', 'PELAKSANA');
INSERT INTO `t_pegawai` VALUES ('67', '1', '0', 'BERLIANA C M SITORUS', '197511301999032003', '13', '', '77.243.662.2-121.000', 'PS PAMIN 5 RENMIN');
INSERT INTO `t_pegawai` VALUES ('68', '1', '0', 'DEWI DAMAYANTI HSB', '197805231999032003', '13', '', '45.487.322.5-125.000', 'PELAKSANA');
INSERT INTO `t_pegawai` VALUES ('69', '1', '0', 'RONNIDA NABABAN', '197708261999032001', '13', '', '77.244.578.9-122.000', 'PS PAMIN 4 RENMIN');
INSERT INTO `t_pegawai` VALUES ('70', '1', '0', 'HENNY SINULINGGA', '197710101999032003', '13', '', '77.243.663.0-121.000', 'PAMIN 3 RENMIN');
INSERT INTO `t_pegawai` VALUES ('71', '1', '0', 'EVI IRAWATI S AMD', '197506272006042001', '13', '', '07.379.121.2-122.001', 'PELAKSANA');
INSERT INTO `t_pegawai` VALUES ('72', '1', '0', 'ROTUA B F SARAGIH', '197708292005012002', '13', '', '46.041.671.2-122.000', 'PS PAMIN 7 RENMIN');
INSERT INTO `t_pegawai` VALUES ('73', '1', '0', 'HERLINA ROSWATI', '196505091987112000', '13', '', '46.041.671.2-122.000', 'PENYELIA');
INSERT INTO `t_pegawai` VALUES ('74', '1', '0', 'P EVA C SIMAREMARE', '196709041992012001', '14', '', '77.241.808.3-121.000', 'PELAKSANA LANJUTAN');
INSERT INTO `t_pegawai` VALUES ('75', '1', '0', 'MARIA NOVA', '196710201998032001', '14', '', '77.244.577.1-121.000', 'PELAKSANA');
INSERT INTO `t_pegawai` VALUES ('76', '1', '0', 'YANTI ENITA BR GINTING', '198108102003121003', '14', '', '57.403.983.0-085.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('77', '1', '0', 'LENA SINTAULI NAPITU', '198210212003122001', '14', '', '77.241.810.9-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('78', '1', '0', 'BELSIDA TAMBUNAN', '198006082005012019', '14', '', '57.828.688.2-211.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('79', '1', '0', 'ROHANA M SIAHAAN', '198112262005012004', '14', '', '77.244.237.2-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('80', '1', '0', 'EDI FAHRIZAL', '196504012007011002', '15', '', '35.912.122.5-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('81', '1', '0', 'K U N I N G', '196510121991032001', '15', '', '77.244.236.4-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('82', '1', '0', 'A B D I', '196612011989031005', '15', '', '77.244.580.5-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('83', '1', '0', 'NUR \'AINI', '196409271994032001', '15', '', '67.789.198.8-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('84', '1', '0', 'RUMIRIS D I PANJAITAN', '197510262007012001', '15', '', '35.927.586.4-124.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('85', '1', '0', 'TETY APRILTA BR SEMBIRING Amd.Kep', '197704092014122001', '15', '', '73.255.852.3-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('86', '1', '0', 'JUNITA EVERIDA SILALAHI,Amd.Kep', '197906302014122002', '15', '', '73.249.296.2-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('87', '1', '0', 'ROSNALIA PURBA', '197705152014122001', '15', '', '73.249.374.7-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('88', '1', '0', 'SERITA BR SEBAYANG A.Md.Kep', '197803232014122001', '15', '', '73.255.843.2-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('89', '1', '0', 'ENDANG ERLITNA A.Md.Kep', '198009182014122001', '15', '', '09.892.395.6-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('90', '1', '0', 'SARIANNA KOTO A.Md.Kep', '197903272014122002', '15', '', '73.249.204.6-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('91', '1', '0', 'SYAMSIAH TAMBUNAN, A.Md.Kep', '198012312014122002', '15', '', '73.324.536.9-124.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('92', '1', '0', 'JULIANTA BR.SEMBIRING MELIALA', '197907232002122003', '15', '', '77.241.813.3-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('93', '1', '0', 'ARIFIN HUTASOIT', '197705062006041011', '15', '', '36.387.399.3-125.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('94', '1', '0', 'LOUPIGA KISSE DERIPASLA', '197705052014121001', '15', '', '73.240.790.3-125.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('95', '1', '0', 'SRIYANTI', '197607222008102001', '15', '', '66.417.946.2-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('96', '1', '0', 'MANONGGOR PANJAITAN', '197005012008101001', '15', '', '45.922.791.9-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('97', '1', '0', 'N A R D A T I', '198008082009102001', '16', '', '44.772.584.7-113.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('98', '1', '0', 'SRI WINARTI', '197205182007012002', '17', '', '35.912.062.3-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('99', '1', '0', 'PAINI ERMIYATI', '197008162014122004', '17', '', '73.255.951.3-125.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('100', '1', '0', 'ADNIZEN', '196604072014121002', '17', '', '73.248.745.9-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('101', '1', '0', 'ISMAINI', '198204242014122002', '17', '', '73.255.931.5-125.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('102', '1', '0', 'EMPINA LASMAIDA SILITONGA', '198208292009102001', '18', '', '45.499.222.3-125.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('103', '1', '0', 'SYAMSURIA HUTASOIT', '196710201998032001', '18', '', '73.248.979.4-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('104', '1', '0', 'INDRANI', '197110272014122002', '19', '', '73.249.092.5-121.000', 'BANUM RUMKIT');
INSERT INTO `t_pegawai` VALUES ('105', '1', '0', 'INDRANI', '197110272014122002', '20', '', '73.249.092.5-121.000', 'BANUM RUMKIT');

-- ----------------------------
-- Table structure for t_permohonan_dana
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan_dana`;
CREATE TABLE `t_permohonan_dana` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime DEFAULT NULL,
  `nomor` varchar(255) DEFAULT NULL,
  `na_nomor` int(11) DEFAULT NULL,
  `na_bulan` varchar(3) DEFAULT NULL,
  `na_tahun` int(11) DEFAULT NULL,
  `na_divisi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_pegawai_ybs` int(11) DEFAULT NULL,
  `diketahui_oleh` int(11) DEFAULT NULL,
  `kuasa_pengguna_anggaran` int(11) DEFAULT NULL,
  `no_sptjb` varchar(255) DEFAULT NULL,
  `jenis_belanja` varchar(255) DEFAULT NULL,
  `menyatakan` varchar(1000) DEFAULT NULL,
  `user_insert` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_permohonan_dana
-- ----------------------------
INSERT INTO `t_permohonan_dana` VALUES ('1', '2018-07-26 00:00:00', '', '1', 'VII', '2018', 'URKEU', 'Membayar gaji pegawai', '81', '97', '99', '-', 'Belanja Barang', '-', '1');

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(1000) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_role` (`id_role`,`username`,`password`(255))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', '1', 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 'Administrator Aplikasi');
