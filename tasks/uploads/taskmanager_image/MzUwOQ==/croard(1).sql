-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2021 at 05:50 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m561034_tasks`
--

-- --------------------------------------------------------

--
-- Table structure for table `croard`
--

CREATE TABLE IF NOT EXISTS `croard` (
  `id` int(11) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `company_name` text NOT NULL,
  `cro_ard` varchar(200) NOT NULL,
  `notes` longtext NOT NULL,
  `filename` text NOT NULL,
  `url` text NOT NULL,
  `signaure` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=531 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `croard`
--

INSERT INTO `croard` (`id`, `client_id`, `company_name`, `cro_ard`, `notes`, `filename`, `url`, `signaure`, `updatetime`) VALUES
(1, 'GBS551', 'DANGAN SECURITY LIMITED', '24/08/2021', '', '', '', 0, '2021-02-12 22:58:32'),
(2, 'GBS552', 'PARAIC MCDONAGH FORMWORK LIMITED', '27/12/2021', '', '', '', 0, '2021-02-12 23:01:27'),
(3, 'GBS556', 'CMD FORMWORK LIMITED', '18/12/2021', '', '', '', 0, '2021-02-12 23:01:49'),
(4, 'GBS002', 'BALLYVAUGHAN BAY HOP LIMITED', '20/06/2014', '', '', '', 0, '2021-02-12 23:09:08'),
(5, 'GBS003', 'CASES WINES BEERS AND SPIRITS LIMITED', '15/07/2016', '', '', '', 0, '2021-02-12 23:09:08'),
(6, 'GBS004', 'INSPIRE CONTROL LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:09'),
(7, 'GBS005', 'WESFILE LIMITED', '29/05/2019', '', '', '', 0, '2021-02-12 23:09:09'),
(8, 'GBS006', 'MURRAY BROTHERS ENTERTAINMENT LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:10'),
(9, 'GBS007', 'J.G.MANNION & ASSOCIATES LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:10'),
(10, 'GBS008', 'CHARLES MADUKA LIMITED', '15/08/2018', '', '', '', 0, '2021-02-12 23:09:10'),
(11, 'GBS009', 'BALLYGLAS DEVELOPMENTS LIMITED', '31/07/2019', '', '', '', 0, '2021-02-12 23:09:11'),
(12, 'GBS010', 'GOB ELECTRICAL LIMITED', '04/06/2018', '', '', '', 0, '2021-02-12 23:09:11'),
(13, 'GBS013', 'INSTINCTIVE CLEANING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:12'),
(14, 'GBS014', 'TOMMY ELLIS PAINTING & DECORATING LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:12'),
(15, 'GBS016', 'MOYLETTE I.T. SERVICES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:12'),
(16, 'GBS017', 'ONE-STOP MARKETING SOLUTIONS LIMITED', '01/09/2021', '', '', '', 0, '2021-02-12 23:09:13'),
(17, 'GBS018', 'CURRAGH CONSTRUCTION LIMITED', '31/08/2021', '', '', '', 0, '2021-02-12 23:09:13'),
(18, 'GBS019', 'DALACO ENGINEERING LIMITED', '12/03/2018', '', '', '', 0, '2021-02-12 23:09:14'),
(19, 'GBS021', 'THE PROFESSIONAL TRAINING ACADEMY LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:14'),
(20, 'GBS022', 'THE PURPLE DOOR LIMITED', '06/08/2018', '', '', '', 0, '2021-02-12 23:09:14'),
(21, 'GBS024', 'EXPEDITIONS, ADVENTURES AND SAFARIS LIMITED', '24/08/2021', '', '', '', 0, '2021-02-12 23:09:15'),
(22, 'GBS026', 'BARKEEPER LIMITED', '30/09/2015', '', '', '', 0, '2021-02-12 23:09:15'),
(23, 'GBS028', 'JOHNNY KENNY ELECTRICAL LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:16'),
(24, 'GBS029', 'AIDAN MORRISSEY LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:16'),
(25, 'GBS030', 'ADRIAN COYNE LIMITED', '18/08/2021', '', '', '', 0, '2021-02-12 23:09:17'),
(26, 'GBS035', 'PV TECH LIMITED', '08/06/2018', '', '', '', 0, '2021-02-12 23:09:17'),
(27, 'GBS036', 'O''ROURKE M.C.J. LIMITED', '31/07/2018', '', '', '', 0, '2021-02-12 23:09:18'),
(28, 'GBS040', 'SACRA DONNELLAN LIMITED', '04/09/2020', '', '', '', 0, '2021-02-12 23:09:18'),
(29, 'GBS042', 'CLOONASEE LIMITED', '20/09/2021', 'B1B73 registered', '', '', 0, '2021-02-12 23:09:19'),
(30, 'GBS043', 'SEAN MALONEY & ASSOCIATES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:19'),
(31, 'GBS044', 'FOREST DINERS LIMITED', '06/06/2015', '', '', '', 0, '2021-02-12 23:09:20'),
(32, 'GBS045', 'GEAROID''S CAR WASH LIMITED', '17/04/2018', '', '', '', 0, '2021-02-12 23:09:20'),
(33, 'GBS046', 'CONNAUGHT PRINTED LABELS LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:21'),
(34, 'GBS047', 'STREAMLINE DATA LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:21'),
(35, 'GBS048', 'GERRY MC INERNEY CONSTRUCTION LIMITED', '30/09/2019', '', '', '', 0, '2021-02-12 23:09:22'),
(36, 'GBS049', 'FSG DESIGN PRODUCTS LIMITED', '10/09/2022', '', '', '', 0, '2021-02-12 23:09:23'),
(37, 'GBS050', 'BM AIR CONDITIONING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:23'),
(38, 'GBS051', 'JOYCE MOTORS LIMITED', '30/09/2017', '', '', '', 0, '2021-02-12 23:09:23'),
(39, 'GBS052', 'GREEN OLIVE CATERING LIMITED', '22/09/2021', '', '', '', 0, '2021-02-12 23:09:24'),
(40, 'GBS053', 'STEVE MOYLAN LIMITED', '01/08/2021', '', '', '', 0, '2021-02-12 23:09:24'),
(41, 'GBS054', 'DECLAN KEANE CARPENTRY & PLANT HIRE LIMITED', '09/09/2021', '', '', '', 0, '2021-02-12 23:09:24'),
(42, 'GBS056', 'WEATHER WARM INSULATION LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:25'),
(43, 'GBS057', 'MARK DWYER LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:25'),
(44, 'GBS058', 'HAZELABBEY DEVELOPMENTS LIMITED', '22/08/2019', '', '', '', 0, '2021-02-12 23:09:26'),
(45, 'GBS059', 'DERMOT FREEMAN LIMITED', '22/06/2021', 'Do we still do accounts for this guy?', '', '', 0, '2021-02-12 23:09:26'),
(46, 'GBS062', 'OUTSELL LIMITED', '05/01/2015', '', '', '', 0, '2021-02-12 23:09:26'),
(47, 'GBS063', 'DONNELLY FIRE SECURITY LIMITED', '08/08/2019', '', '', '', 0, '2021-02-12 23:09:26'),
(48, 'GBS064', 'FES FUSION ENGINEERING SERVICES LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:27'),
(49, 'GBS066', 'THE WINDOW DOCTOR LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:27'),
(50, 'GBS068', 'HAULMAC TRANSPORT LIMITED', '09/07/2017', '', '', '', 0, '2021-02-12 23:09:28'),
(51, 'GBS069', 'M.I. SECURITY SYSTEMS LIMITED', '30/06/2015', '', '', '', 0, '2021-02-12 23:09:28'),
(52, 'GBS072', 'ARMOUR INTERACTIVE LIMITED', '04/09/2021', '', '', '', 0, '2021-02-12 23:09:29'),
(53, 'GBS073', 'KINVARA AUTO REPAIRS LIMITED', '20/09/2021', 'B1B73 Registered', '', '', 0, '2021-02-12 23:09:29'),
(54, 'GBS074', 'JOHN CASSERLY ENGINEERING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:29'),
(55, 'GBS075', 'CITY VILLA (GALWAY) LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:30'),
(56, 'GBS076', 'GERAGHTY MOTORS LIMITED', '01/09/2021', '', '', '', 0, '2021-02-12 23:09:30'),
(57, 'GBS078', 'ORAN INTERNATIONAL TRANSPORT LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:31'),
(58, 'GBS079', 'MATT BRILLY LIMITED', '15/04/2019', '', '', '', 0, '2021-02-12 23:09:32'),
(59, 'GBS081', 'PATRICK HURLEY LIMITED', '27/07/2019', '', '', '', 0, '2021-02-12 23:09:32'),
(60, 'GBS082', 'GERRY MCINERNEY SPORTS LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:32'),
(61, 'GBS083', 'PALLET DROP IRELAND LIMITED', '30/04/2016', '', '', '', 0, '2021-02-12 23:09:33'),
(62, 'GBS084', 'BILL IN IRELAND SENSORY PARTICIPATION LIMITED', '16/05/2016', '', '', '', 0, '2021-02-12 23:09:33'),
(63, 'GBS086', 'AB PORT CONSULTANTS LIMITED', '22/08/2019', '', '', '', 0, '2021-02-12 23:09:33'),
(64, 'GBS089', 'HAIRMONY LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:34'),
(65, 'GBS092', 'APPNOESIS LIMITED', '26/07/2021', 'do we still do accounts for these?', '', '', 0, '2021-02-12 23:09:34'),
(66, 'GBS094', 'MCARDLE IT SERVICES LIMITED', '06/09/2017', '', '', '', 0, '2021-02-12 23:09:34'),
(67, 'GBS098', 'GRIFFIN GAUGHAN LIMITED', '21/08/2021', '', '', '', 0, '2021-02-12 23:09:35'),
(68, 'GBS099', 'RILMEDIA TEORANTA', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:35'),
(69, 'GBS113', 'AOIFE O DRISCOLL LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:35'),
(70, 'GBS139', 'JOTTY CONSULTING LIMITED', '05/12/2020', '', '', '', 0, '2021-02-12 23:09:36'),
(71, 'GBS144', 'SLÍ NA HAITEANN OWNERS MANAGEMENT COMPANY COMPANY LIMITED BY GUARANTEE', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:36'),
(72, 'GBS147', 'HILDEGARD HEALTH LIMITED', '06/09/2021', '', '', '', 0, '2021-02-12 23:09:37'),
(73, 'GBS156', 'CARNMORE HEAVY EQUIPMENT, PLANT & HAULAGE LIMITED', '15/08/2021', '', '', '', 0, '2021-02-12 23:09:37'),
(74, 'GBS158', 'DAISE BUSINESS SOLUTIONS LIMITED', '21/10/2021', '', '', '', 0, '2021-02-12 23:09:38'),
(75, 'GBS164', 'FRENCH & VANOLI LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:39'),
(76, 'GBS166', 'T.K. (GALWAY CAR SALES) LIMITED', '20/07/2021', '', '', '', 0, '2021-02-12 23:09:40'),
(77, 'GBS168', 'JENS KOSAK DESIGN LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:40'),
(78, 'GBS169', 'ODM ENERGY LIMITED', '30/01/2022', '', '', '', 0, '2021-02-12 23:09:41'),
(79, 'GBS170', 'IDIR LÁMHA CUIDEACHTA FAOI THEORAINN RÁTHAÍOCHTA', '13/04/2019', '', '', '', 0, '2021-02-12 23:09:41'),
(80, 'GBS173', 'TSS BODY BUILDERS LIMITED', '04/10/2021', '', '', '', 0, '2021-02-12 23:09:42'),
(81, 'GBS174', 'WESTERN AUTOSTOP LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:42'),
(82, 'GBS175', 'IVOR CURLEY LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:43'),
(83, 'GBS195', 'DECLAN ROCHE PLANT HIRE LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:43'),
(84, 'GBS196', 'A.H. DRIVES & CONTROLS LIMITED', '27/08/2021', '', '', '', 0, '2021-02-12 23:09:43'),
(85, 'GBS197', 'INTERCONTINENTAL COMMODITIES GLOBAL LIMITED', '14/10/2012', '', '', '', 0, '2021-02-12 23:09:44'),
(86, 'GBS198', 'ATLAS AUDIO VISUAL LIMITED', '25/09/2021', '', '', '', 0, '2021-02-12 23:09:44'),
(87, 'GBS199', 'SDM CAD DESIGN SERVICES LIMITED', '22/08/2021', '', '', '', 0, '2021-02-12 23:09:45'),
(88, 'GBS200', 'VIDAVIA IRELAND LIMITED', '25/04/2017', '', '', '', 0, '2021-02-12 23:09:45'),
(89, 'GBS202', 'CROOM ELECTRICAL CONTRACTING LIMITED', '29/05/2019', '', '', '', 0, '2021-02-12 23:09:45'),
(90, 'GBS203', 'CREVA INDUSTRIES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:46'),
(91, 'GBS205', 'LINK DIGITAL INNOVATIONS LIMITED', '08/09/2021', '', '', '', 0, '2021-02-12 23:09:46'),
(92, 'GBS206', 'NEIL DEVANE ELECTRICAL LIMITED', '20/09/2021', 'B1B73 Registered', '', '', 0, '2021-02-12 23:09:46'),
(93, 'GBS209', 'DIGITAL B2B SERVICES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:47'),
(94, 'GBS214', 'STEPHEN ROWE LIMITED', '18/03/2022', '', '', '', 0, '2021-02-12 23:09:47'),
(95, 'GBS216', 'FINBAR KEAVENEY LIMITED', '08/10/2021', '', '', '', 0, '2021-02-12 23:09:48'),
(96, 'GBS218', 'C.M.D. CONSTRUCTION LIMITED', '31/03/2022', '', '', '', 0, '2021-02-12 23:09:48'),
(97, 'GBS221', 'GLEEFUL LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:49'),
(98, 'GBS225', 'O2L''S (FRESH MEATS) LIMITED', '06/02/2020', '', '', '', 0, '2021-02-12 23:09:49'),
(99, 'GBS256', 'MACMARA MARINE LIMITED', '16/05/2017', '', '', '', 0, '2021-02-12 23:09:49'),
(100, 'GBS257', 'TILES BATHROOMS WOOD FLOORING LIMITED', '02/10/2021', '', '', '', 0, '2021-02-12 23:09:49'),
(101, 'GBS258', 'RICHARD MCWALTER LIMITED', '21/09/2021', '', '', '', 0, '2021-02-12 23:09:50'),
(102, 'GBS262', 'DAVID HARTY LIMITED', '05/08/2021', '', '', '', 0, '2021-02-12 23:09:50'),
(103, 'GBS264', 'TCEC ELECTRICAL CONTRACTING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:50'),
(104, 'GBS266', 'FANALYTICS LIMITED', '01/04/2022', '', '', '', 0, '2021-02-12 23:09:51'),
(105, 'GBS268', 'AREEVAY LIMITED', '20/09/2021', 'b1b73 submitted 14/05/2021', '', '', 0, '2021-02-12 23:09:52'),
(106, 'GBS274', 'BRANNELLY LOGISTICS LIMITED', '15/09/2021', '', '', '', 0, '2021-02-12 23:09:52'),
(107, 'GBS276', 'ROBUSTAHEALTH LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:53'),
(108, 'GBS286', 'MAMABUD LIMITED', '24/03/2018', '', '', '', 0, '2021-02-12 23:09:53'),
(109, 'GBS288', 'NTA TANA MEDICAL LIMITED', '30/09/2016', '', '', '', 0, '2021-02-12 23:09:53'),
(110, 'GBS290', 'LASER VISION CORRECTION LIMITED', '16/09/2021', '', '', '', 0, '2021-02-12 23:09:53'),
(111, 'GBS291', 'DARAGH WHYTE LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:54'),
(112, 'GBS294', 'NOEL MCWALTER LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:54'),
(113, 'GBS300', 'JAMES O TOOLE MOTORS LIMITED', '18/09/2021', '', '', '', 0, '2021-02-12 23:09:54'),
(114, 'GBS301', 'ITALIAN LIFESTYLE LIMITED', '10/09/2019', '', '', '', 0, '2021-02-12 23:09:55'),
(115, 'GBS309', 'ERRIGAL TRANSPORT SERVICES LIMITED', '22/09/2021', '', '', '', 0, '2021-02-12 23:09:55'),
(116, 'GBS313', 'BISHOP O''DONNELL ROAD MANAGEMENT COMPANY COMPANY LIMITED BY GUARANTEE', '30/08/2021', '', '', '', 0, '2021-02-12 23:09:55'),
(117, 'GBS314', 'WILLOWS (ATHENRY) MANAGEMENT COMPANY COMPANY LIMITED BY GUARANTEE', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:56'),
(118, 'GBS315', 'SECURE4U FIRE & SECURITY LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:56'),
(119, 'GBS316', 'BERRY LANE GARDEN LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:09:56'),
(120, 'GBS318', 'MAC GIOLLARNAITH LIMITED', '26/07/2021', 'ARD can not be changed until 06/01/2025. ARD remains at 26/07/2021', '', '', 0, '2021-02-12 23:09:57'),
(121, 'GBS319', 'EZ STOCK CONTROL LIMITED', '19/08/2021', '', '', '', 0, '2021-02-12 23:09:57'),
(122, 'GBS322', 'ALEX & ALEX LIFESTYLE LIMITED', '24/08/2019', '', '', '', 0, '2021-02-12 23:09:58'),
(123, 'GBS323', 'ACTIO ANIMUS LIMITED', '04/09/2021', '', '', '', 0, '2021-02-12 23:09:58'),
(124, 'GBS324', 'MY TAX ONLINE LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:09:58'),
(125, 'GBS328', 'LANDLORD INSPECTIONS LIMITED', '05/04/2019', '', '', '', 0, '2021-02-12 23:09:59'),
(126, 'GBS329', 'DARRAGH DALY LIMITED', '25/10/2021', '', '', '', 0, '2021-02-12 23:09:59'),
(127, 'GBS330', 'MATTHEW HAVERTY LIMITED', '04/11/2021', '', '', '', 0, '2021-02-12 23:10:00'),
(128, 'GBS332', 'HAUGH FAMILY BUTCHERS LIMITED', '23/09/2021', '', '', '', 0, '2021-02-12 23:10:00'),
(129, 'GBS334', 'WHYTE REFRIGERATION LIMITED', '11/04/2020', '', '', '', 0, '2021-02-12 23:10:01'),
(130, 'GBS339', 'MICHAEL FITZGERALD (CONSULTANT ELECTRICAL ENGINEERS CROOM) LIMITED', '02/05/2019', '', '', '', 0, '2021-02-12 23:10:01'),
(131, 'GBS340', 'KEVIN HAYES (CONSULTANT ENGINEERS CROOM) LIMITED', '03/05/2019', '', '', '', 0, '2021-02-12 23:10:02'),
(132, 'GBS343', 'BERSIH RESTAURANTS LIMITED', '03/11/2017', '', '', '', 0, '2021-02-12 23:10:03'),
(133, 'GBS344', 'SPORTING ORACLE LIMITED', '21/09/2017', '', '', '', 0, '2021-02-12 23:10:04'),
(134, 'GBS346', 'CHANNELSIGHT LIMITED', '25/03/2022', 'need directors DOB info for this client', '', '', 0, '2021-02-12 23:10:04'),
(135, 'GBS347', 'DATUM PROJECT MANAGEMENT LIMITED', '26/03/2018', '', '', '', 0, '2021-02-12 23:10:04'),
(136, 'GBS348', 'VANDIEKEN LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:05'),
(137, 'GBS350', 'JAMES WESTBOURNE LIMITED', '16/03/2019', '', '', '', 0, '2021-02-12 23:10:05'),
(138, 'GBS351', 'O BOYLE ELECTRICAL CONTRACTING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:06'),
(139, 'GBS353', 'CREW, CAST & PERFORMERS LIMITED', '07/09/2021', '', '', '', 0, '2021-02-12 23:10:06'),
(140, 'GBS359', 'EASCA RECRUITMENT LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:07'),
(141, 'GBS360', 'AGRI-PLASTICS GRASSIECREVA LIMITED', '10/09/2021', '', '', '', 0, '2021-02-12 23:10:07'),
(142, 'GBS363', 'BROGAN''S BAKERY LIMITED', '30/09/2019', '', '', '', 0, '2021-02-12 23:10:08'),
(143, 'GBS365', 'DOBRILA MARIUS ELECTRICAL CONTRACTING LIMITED', '28/09/2019', '', '', '', 0, '2021-02-12 23:10:09'),
(144, 'GBS368', 'RYANTOWN TOYS & GIFTS LIMITED', '24/08/2021', '', '', '', 0, '2021-02-12 23:10:09'),
(145, 'GBS371', 'KILBANE & FAHY FLOOR SCREED LIMITED', '10/07/2021', 'Can not change ARD until 10/01/2024. ARD stays at 10/07/21', '', '', 0, '2021-02-12 23:10:10'),
(146, 'GBS372', 'THE NOGRA DEVELOPMENT COMPANY LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:10'),
(147, 'GBS373', 'DAIRY SPARES LIMITED', '31/07/2021', 'Can not change ARD until 31/01/2024. ARD stays at 31/07/21', '', '', 0, '2021-02-12 23:10:11'),
(148, 'GBS374', 'LULU''S LOCAL PRODUCE LIMITED', '31/07/2021', 'Cannot file till 06/01/2025', '', '', 0, '2021-02-12 23:10:12'),
(149, 'GBS377', 'INNOLED EUROPE LIMITED', '12/07/2018', '', '', '', 0, '2021-02-12 23:10:12'),
(150, 'GBS378', 'DEREK BOWENS LIMITED', '30/07/2020', '', '', '', 0, '2021-02-12 23:10:13'),
(151, 'GBS387', 'TESTSTATE LIMITED', '09/09/2021', 'B10 Submitted', '', '', 0, '2021-02-12 23:10:13'),
(152, 'GBS389', 'SENSHIN SPORTS IRELAND LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:13'),
(153, 'GBS390', 'CRUINNIU NA MBAD CUIDEACHTA FAOI THEORAINN RÁTHAÍOCHTA', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:14'),
(154, 'GBS391', 'AOQOD LIMITED', '07/06/2021', 'we do not seem to be tax agents for this company anymore', '', '', 0, '2021-02-12 23:10:14'),
(155, 'GBS392', 'JONAS PFANNSCHMIDT LIMITED', '15/06/2019', '', '', '', 0, '2021-02-12 23:10:14'),
(156, 'GBS393', 'DOOLIN BREWING COMPANY LIMITED', '12/07/2021', 'Struck off', '', '', 0, '2021-02-12 23:10:15'),
(157, 'GBS394', 'EASYTEX ALUMINIUM LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:15'),
(158, 'GBS398', 'MOLANIC LIMITED', '23/09/2020', '', '', '', 0, '2021-02-12 23:10:16'),
(159, 'GBS399', 'FITZ-HAY ELECTRICAL CONTRACTING LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:16'),
(160, 'GBS400', 'VIRTUAL REALITY WORLD LIMITED', '01/11/2021', '', '', '', 0, '2021-02-12 23:10:17'),
(161, 'GBS404', 'DOMINIC-MARKUS FORESTRY LIMITED', '20/08/2021', '', '', '', 0, '2021-02-12 23:10:17'),
(162, 'GBS405', 'LINK DIRECTORY LIMITED', '05/09/2018', '', '', '', 0, '2021-02-12 23:10:17'),
(163, 'GBS406', 'SKELECT LIMITED', '20/08/2021', '', '', '', 0, '2021-02-12 23:10:18'),
(164, 'GBS407', 'PLANET PAYMENT.IE LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:18'),
(165, 'GBS408', 'STICKY TAPE PRODUCTIONS LIMITED', '25/01/2022', '', '', '', 0, '2021-02-12 23:10:19'),
(166, 'GBS409', 'FK FURNITURE MANAGEMENT SYSTEMS LIMITED', '14/03/2019', '', '', '', 0, '2021-02-12 23:10:20'),
(167, 'GBS411', 'INNOVATIVE SMT LIMITED', '16/10/2021', 'B1 Submitted', '', '', 0, '2021-02-12 23:10:20'),
(168, 'GBS414', 'CORLEY SOFTWARE SOLUTIONS LIMITED', '24/08/2021', '', '', '', 0, '2021-02-12 23:10:21'),
(169, 'GBS416', 'CANDLESTICK CONSULTANCY LIMITED', '11/01/2021', '', '', '', 0, '2021-02-12 23:10:21'),
(170, 'GBS419', 'QUICKLYT GALWAY LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:22'),
(171, 'GBS422', 'GORT NA CARRAIGE MANAGEMENT COMPANY COMPANY LIMITED BY GUARANTEE', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:22'),
(172, 'GBS426', 'DIOCESAN MANAGED SERVICES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:23'),
(173, 'GBS428', 'MARIO DELIVERY SERVICE LIMITED', '23/01/2020', '', '', '', 0, '2021-02-12 23:10:23'),
(174, 'GBS429', 'LITTLE OAK SOFTWARE LIMITED', '03/08/2021', '', '', '', 0, '2021-02-12 23:10:24'),
(175, 'GBS430', 'OLP OFFICE LINE PRODUCTS INTERNATIONAL LIMITED', '12/06/2022', '', '', '', 0, '2021-02-12 23:10:24'),
(176, 'GBS431', 'WOI PLANT HIRE & SUPPLIES LIMITED', '03/06/2020', '', '', '', 0, '2021-02-12 23:10:25'),
(177, 'GBS432', 'SSLP CONSULTANCY LIMITED', '27/01/2022', '', '', '', 0, '2021-02-12 23:10:25'),
(178, 'GBS433', 'BLOOM FORCE LIMITED', '28/09/2021', '', '', '', 0, '2021-02-12 23:10:25'),
(179, 'GBS434', 'AK TRANSPORT SERVICES LIMITED', '27/10/2020', '', '', '', 0, '2021-02-12 23:10:26'),
(180, 'GBS435', 'RK SURVEYS LIMITED', '11/08/2021', '', '', '', 0, '2021-02-12 23:10:26'),
(181, 'GBS436', 'BCR PROPERTY CONSULTANTS LIMITED', '13/02/2021', 'You cannot file this annual return type until 13-Aug-2023 ', '', '', 0, '2021-02-12 23:10:26'),
(182, 'GBS437', 'OAK VENTURES LIMITED', '18/09/2021', '', '', '', 0, '2021-02-12 23:10:27'),
(183, 'GBS438', 'CHELGAR LIMITED', '14/07/2018', '', '', '', 0, '2021-02-12 23:10:27'),
(184, 'GBS440', 'MAGNUM CARPET & FLOORING LIMITED', '04/09/2021', '', '', '', 0, '2021-02-12 23:10:28'),
(185, 'GBS442', 'G & P MARINE VICTOR LIMITED', '05/06/2020', '', '', '', 0, '2021-02-12 23:10:28'),
(186, 'GBS443', 'FRENCH VANOLI KNOCKNACARRA LIMITED', '01/06/2020', '', '', '', 0, '2021-02-12 23:10:29'),
(187, 'GBS444', 'BUNDLE SUBSCRIPTION BOXES LIMITED', '28/09/2019', '', '', '', 0, '2021-02-12 23:10:30'),
(188, 'GBS445', 'GBS & CO (I.T. SERVICES) LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:30'),
(189, 'GBS447', 'KQURAM LIMITED', '31/08/2021', '', '', '', 0, '2021-02-12 23:10:31'),
(190, 'GBS451', 'ANIMIS LABS LIMITED', '30/11/2021', '', '', '', 0, '2021-02-12 23:10:31'),
(191, 'GBS453', 'AAAMBER MOTORS LIMITED', '09/09/2021', '', '', '', 0, '2021-02-12 23:10:31'),
(192, 'GBS454', 'BLOOM FORCE LIMITED', '28/09/2021', '', '', '', 0, '2021-02-12 23:10:32'),
(193, 'GBS455', 'BUNDLE SUBSCRIPTION BOXES LIMITED', '28/09/2019', '', '', '', 0, '2021-02-12 23:10:32'),
(194, 'GBS456', 'PSS RECRUITMENT LIMITED', '29/09/2019', '', '', '', 0, '2021-02-12 23:10:33'),
(195, 'GBS457', 'REDHEAD VENTURES LIMITED', '24/12/2021', '', '', '', 0, '2021-02-12 23:10:34'),
(196, 'GBS458', 'MISSION NATURE COMPANY LIMITED BY GUARANTEE', '09/10/2021', '', '', '', 0, '2021-02-12 23:10:34'),
(197, 'GBS460', 'BVS ASSET RENTALS LIMITED', '03/09/2021', '', '', '', 0, '2021-02-12 23:10:34'),
(198, 'GBS465', 'DIGITAL BAINISTEOIR NA GAILLIMHE TEORANTA', '23/03/2021', 'we do not seem to be tax agents for this company anymore', '', '', 0, '2021-02-12 23:10:35'),
(199, 'GBS466', 'PEB TRADING COMPANY LIMITED', '15/09/2021', '', '', '', 0, '2021-02-12 23:10:35'),
(200, 'GBS468', 'AMERICA VILLAGE APOTHCARY LIMITED', '26/09/2021', '', '', '', 0, '2021-02-12 23:10:35'),
(201, 'GBS469', 'GANNON MECHANICAL & ENGINEERING SERVICES LIMITED', '11/04/2022', '', '', '', 0, '2021-02-12 23:10:36'),
(202, 'GBS471', 'CRB PROPERTY CONSULTANTS LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:36'),
(203, 'GBS472', 'ALAN BAKERY LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:37'),
(204, 'GBS473', 'ROOTS CAFE CLAREGALWAY LIMITED', '29/04/2022', '', '', '', 0, '2021-02-12 23:10:37'),
(205, 'GBS482', 'AIRWAVE TECHNOLOGY LIMITED', '29/09/2021', '', '', '', 0, '2021-02-12 23:10:38'),
(206, 'GBS483', 'MY FEST APP LIMITED', '21/09/2021', '', '', '', 0, '2021-02-12 23:10:40'),
(207, 'GBS484', 'TEAM MOTORS (MECHANICAL SERVICES) LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:41'),
(208, 'GBS485', 'GUILFOYLE MULLEN DEVELOPMENTS LIMITED', '27/11/2020', '', '', '', 0, '2021-02-12 23:10:42'),
(209, 'GBS486', 'JAMES O TOOLE (CAR SALES) LIMITED', '23/08/2020', '', '', '', 0, '2021-02-12 23:10:42'),
(210, 'GBS488', 'JUST ART IT LIMITED', '19/12/2021', '', '', '', 0, '2021-02-12 23:10:42'),
(211, 'GBS489', 'CLOVERLEAF COTTAGES LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:43'),
(212, 'GBS490', 'O''CONNOR GROUP LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:43'),
(213, 'GBS493', 'CLOVERLEAF TRAVEL LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:44'),
(214, 'GBS496', 'BAKE BOX LIMITED', '19/08/2021', '', '', '', 0, '2021-02-12 23:10:44'),
(215, 'GBS497', 'O DRISCOLL CRECHE SERVICES (GORT) LIMITED', '19/08/2021', '', '', '', 0, '2021-02-12 23:10:44'),
(216, 'GBS500', 'PARNELL FURNITURE LIMITED', '10/09/2021', '', '', '', 0, '2021-02-12 23:10:45'),
(217, 'GBS501', 'O DRISCOLL CRECHE SERVICES (ATHENRY) LIMITED', '09/09/2021', '', '', '', 0, '2021-02-12 23:10:45'),
(218, 'GBS503', 'VISION FLOORING LIMITED', '27/10/2020', '', '', '', 0, '2021-02-12 23:10:46'),
(219, 'GBS506', 'MI WATCH LIMITED', '08/07/2021', 'Can not change this ARD until 08/01/2024. ', '', '', 0, '2021-02-12 23:10:46'),
(220, 'GBS507', 'CONDOMINIA NET LIMITED', '02/01/2022', '', '', '', 0, '2021-02-12 23:10:46'),
(221, 'GBS508', 'ROOTS CAFE NEWTOWNSMITH LIMITED', '24/12/2021', '', '', '', 0, '2021-02-12 23:10:46'),
(222, 'GBS513', 'MARCIN & MAGDA RESTAURANT LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:47'),
(223, 'GBS514', 'ORDÚCLOUD LIMITED', '13/02/2022', '', '', '', 0, '2021-02-12 23:10:47'),
(224, 'GBS515', 'MULANNON RECRUITMENT LIMITED', '13/02/2022', '', '', '', 0, '2021-02-12 23:10:48'),
(225, 'GBS516', 'LAWLESS MULRYAN MASONRY LIMITED', '18/02/2022', '', '', '', 0, '2021-02-12 23:10:48'),
(226, 'GBS517', 'SIMBAS CHILDCARE LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:49'),
(227, 'GBS520', 'TEAM PRECISION PIPE ASSEMBLIES LIMITED', '24/03/2021', 'They looking after their own per Ciaran', '', '', 0, '2021-02-12 23:10:49'),
(228, 'GBS522', 'RENOVATE MY HOME LIMITED', '16/03/2022', '', '', '', 0, '2021-02-12 23:10:50'),
(229, 'GBS523', 'KILCAHILL ENTERPRISES LIMITED', '20/09/2021', 'B1B73 Registered', '', '', 0, '2021-02-12 23:10:50'),
(230, 'GBS525', 'ALS CON LIMITED', '30/03/2022', '', '', '', 0, '2021-02-12 23:10:51'),
(231, 'GBS528', 'HQURKO LIMITED', '14/07/2021', 'b1b73 submitted 02/02/2021', '', '', 0, '2021-02-12 23:10:51'),
(232, 'GBS529', 'WMMC COMPLEX MANAGEMENT COMPANY LIMITED BY GUARANTEE', '10/05/2022', '', '', '', 0, '2021-02-12 23:10:52'),
(233, 'GBS530', 'A WESTLINK SERVICE CENTRE LIMITED', '20/09/2021', '', '', '', 0, '2021-02-12 23:10:52'),
(234, 'GBS531', 'WESTERN AUTOSTOP MECHANICAL SERVICES LIMITED', '21/09/2021', 'B2 Submitted', '', '', 0, '2021-02-12 23:10:53'),
(235, 'GBS532', 'TAZA ECOMMERCE LIMITED', '13/02/2022', '', '', '', 0, '2021-02-12 23:10:53'),
(236, 'GBS534', 'O''CONNOR HOLDING COMPANY (DEMESNE) LIMITED', '28/09/2021', '', '', '', 0, '2021-02-12 23:10:53'),
(237, 'GBS536', 'ROOTS CAFE KILCOLGAN LIMITED', '18/05/2022', '', '', '', 0, '2021-02-12 23:10:54'),
(238, 'GBS537', 'COREPRIME ELECTRICAL CONTRACTING LIMITED', '13/05/2022', '', '', '', 0, '2021-02-12 23:10:54'),
(239, 'GBS542', 'ROOTS CAFE BRIARHILL LIMITED', '23/05/2022', '', '', '', 0, '2021-02-12 23:10:54'),
(240, 'GBS543', 'REDHEAD TECHNOLOGY HOLDINGS LIMITED', '09/05/2022', '', '', '', 0, '2021-02-12 23:10:55'),
(241, 'GBS544', 'WHEN THEN LIMITED', '13/05/2022', '', '', '', 0, '2021-02-12 23:10:55'),
(242, 'GBS545', 'HH OCSW MANAGEMENT COMPANY LIMITED BY GUARANTEE', '03/06/2022', '\n\n\n', '', '', 0, '2021-02-12 23:10:56'),
(243, 'GBS546', 'ROOTS CAFE SHOPSTREET LIMITED', '01/06/2022', '', '', '', 0, '2021-02-12 23:10:56'),
(244, 'GBS900', 'G.B.S. & CO (ACCOUNTING) LIMITED', '30/09/2021', '', '', '', 0, '2021-02-12 23:10:56'),
(245, 'GBS548', 'SUNSHINE PROFESSIONAL CLEANING SERVICES LIMITED', '31/03/2020', '', '', '', 0, '2021-02-12 23:10:57'),
(246, 'GBS549', 'OUGHTER HOLDINGS LIMITED', '04/05/2021', 'waiting to be struck off', '', '', 0, '2021-02-12 23:10:57'),
(247, 'GBS550', 'TRIBECA PROPERTY INVESTMENT LIMITED', '29/03/2021', 'waiting to be struck off', '', '', 0, '2021-02-12 23:10:58'),
(248, 'GBS558', 'CURRAGH (CONSTRUCTION/ELECTRIC) LIMITED', '31/08/2021', '', '', '', 0, '2021-02-12 23:10:59'),
(249, 'GBS561', 'O RUADHAIN COURT MANAGEMENT LIMITED', '19/04/2022', '', '', '', 0, '2021-02-14 13:20:43'),
(250, 'GBS001', 'Wkc Land Transports Activities Limited', '12/09/2021', 'B10 Submitted', 'Appoinment Letter.pdf', 'uploads/croard_uploads/GBS001', 0, '2021-02-14 15:13:35'),
(251, 'GBS560', 'TREVOR LAWLOR CARPENTERS LIMITED', '16/08/2021', '', '', '', 0, '2021-02-22 09:47:25'),
(253, 'GBS557', 'RUCKUS BRANDING LIMITED', '01/08/2021', '', '', '', 0, '2021-02-26 10:52:37'),
(254, 'GBS555', 'CROSSFIT GALWAY SPORTS ACADEMY LIMITED', '04/08/2021', '', '', '', 0, '2021-02-26 10:52:37'),
(255, 'GBS553', 'E-CIG IRELAND LIMITED', '20/09/2021', '', '', '', 0, '2021-02-26 10:52:38'),
(256, 'GBS562', 'SHARPER FITNESS LIMITED', '11/11/2021', '', '', '', 0, '2021-02-26 11:00:50'),
(257, 'GBS563', 'QCX MANAGEMENT LIMITED', '21/07/2021', 'You cannot file a B1B73 annual return type as this is your company''s first annual return.', '', '', 0, '2021-03-04 15:28:05'),
(279, 'GBS570', 'PLAYLASTMAN LIMITED', '20/10/2021', '', '', '', 0, '2021-04-21 14:32:55'),
(282, 'GBS576', 'DMS ADVANCE FORESTRY MANAGEMENT LIMITED', '26/10/2021', 'B10 registered', '', '', 0, '2021-05-05 07:45:13'),
(283, 'GBS575', 'BLACK 11 ENTERTAINMENT LIMITED', '04/11/2021', '', '', '', 0, '2021-05-05 14:34:14'),
(390, 'GBS571', 'ROOTS CENTRAL ADMINISTRATIVE SERVICES LIMITED', '20/10/2021', '', '', '', 0, '2021-05-25 07:46:37'),
(391, 'GBS572', 'ROOTS CAFE LOUGHREA LIMITED', '20/10/2021', '', '', '', 0, '2021-05-25 07:46:37'),
(528, 'GBS578', 'GRIFFIN PHYSIOTHERAPY LIMITED', '28/11/2021', '', '', '', 0, '2021-06-14 12:56:55'),
(529, 'GBS573', 'CURATED CONSULTING LIMITED', '28/11/2021', '', '', '', 0, '2021-06-14 12:56:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `croard`
--
ALTER TABLE `croard`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `croard`
--
ALTER TABLE `croard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=531;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;