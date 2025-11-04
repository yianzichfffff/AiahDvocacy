-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 05:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aiahdvocacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `Activity_ID` int(11) NOT NULL,
  `Project_ID` int(11) DEFAULT NULL,
  `Activity_Name` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Start_Date` date DEFAULT NULL,
  `End_Date` date DEFAULT NULL,
  `Location` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`Activity_ID`, `Project_ID`, `Activity_Name`, `Description`, `Start_Date`, `End_Date`, `Location`) VALUES
(1, 2, 'Relief Goods Distribution', 'In response to the Cebu earthquake, relief goods were distributed to affected residents who lost their homes and access to basic necessities. The distribution included food packs, bottled water, hygiene kits, blankets, and other essential supplies to help families recover from the disaster. This initiative aimed to provide immediate assistance, comfort, and hope to the victims, showing the community’s unity and compassion in times of crisis.', '2025-10-31', '2025-11-10', 'Cebu City'),
(2, 3, 'Feeding Program', 'A weekly feeding program for malnourished children in the community to improve their nutritional status and promote good health. Volunteers prepare and distribute healthy meals while also conducting short educational talks about proper hygiene and balanced diets.', '2025-10-22', '2026-10-22', 'Calapan City'),
(3, 3, 'Educational Supplies Donation Drive', 'This initiative provides school supplies, learning materials, and used books to students from low-income families. It aims to encourage children to continue their studies and reduce school dropouts caused by financial difficulties.', '2025-10-24', '2025-10-30', 'Calapan City'),
(4, 3, 'Community Sports Festival', 'A sports event that promotes physical fitness, teamwork, and camaraderie among residents. Games such as basketball, volleyball, and fun runs are organized to encourage an active and healthy lifestyle.', '2025-10-31', '2025-11-14', 'Lipa Batangas'),
(5, 2, 'Medical and Psychological Mission', 'A team of volunteer doctors, nurses, and counselors provides free medical checkups and trauma debriefing sessions for survivors. This helps address both physical injuries and emotional stress caused by the earthquake.', '2025-10-01', '2025-10-14', 'Cebu City'),
(6, 3, 'Clothing Drive', 'Collect and distribute clothes, blankets, and shoes to families in need.', '2025-11-16', '2025-12-06', 'Calapan City');

-- --------------------------------------------------------

--
-- Table structure for table `activity_participation`
--

CREATE TABLE `activity_participation` (
  `Participation_ID` int(11) NOT NULL,
  `Activity_ID` int(11) DEFAULT NULL,
  `Project_ID` int(11) DEFAULT NULL,
  `Beneficiary_ID` int(11) DEFAULT NULL,
  `Sponsor_ID` int(11) DEFAULT NULL,
  `Volunteer_ID` int(11) DEFAULT NULL,
  `Donation_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Contact_No` int(11) NOT NULL,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `First_Name`, `Last_Name`, `Email`, `Contact_No`, `User_ID`) VALUES
(0, 'Mami Oni', 'Fouling', 'oni@gmail.com', 2147483647, 10),
(0, '123', '123', '123@gmail.com', 123, 15),
(0, '1234', '1234', '1234@gmail.com', 1234, 17);

-- --------------------------------------------------------

--
-- Table structure for table `beneficiary`
--

CREATE TABLE `beneficiary` (
  `Beneficiary_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Birthdate` date DEFAULT NULL,
  `Address` varchar(150) DEFAULT NULL,
  `Contact_No` varchar(15) DEFAULT NULL,
  `Activity_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiary`
--

INSERT INTO `beneficiary` (`Beneficiary_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Gender`, `Birthdate`, `Address`, `Contact_No`, `Activity_ID`, `User_ID`, `is_deleted`) VALUES
(1, 'Maria', 'Sacion', 'Dolores', 'Female', '2016-10-12', 'San Vicente, East Calapan City Oriental Mindoro', '09966354436', 3, 1, 0),
(2, 'Dick albert', 'Cebu', 'Mendoza', 'Male', '2015-04-03', 'Cadre Road, Calapan City, Oriental Mindoro', '09978333456', 3, 8, 1),
(5, 'Jack', 'San', 'Jong', 'Female', '2015-06-16', 'San Vicente,East Calapan City, Oriental Mindoro', '09735673628', 4, 8, 1),
(7, 'Khris Belle', 'Rayos', 'Ali', 'Female', '2003-12-12', 'Ibaba', '09876545678', 4, 11, 0),
(9, 'Shania', 'Mina', 'Cebu', 'Female', '2009-12-12', 'Calapan city', '09876786565', 3, NULL, 1),
(10, 'Jhoanna Christine', 'Vergara', 'Robles', 'Female', '2007-10-29', 'Calamba Laguna', '09876545678', 3, 12, 0),
(11, 'ROQUE DANIEL', 'CEBU', 'MENDOZA', 'Male', '2016-02-12', 'Cadre Road', '09876785490', 4, NULL, 0),
(12, 'Johnson', 'Johnny', 'Jonny', 'Male', '2015-10-20', 'Calapan city', '09876545635', 1, NULL, 0),
(13, '12345', '123', '123', 'Female', '2003-12-12', '123', '123', 1, NULL, 1),
(14, 'Marrianessss', 'Mina', 'Cebu', 'Female', '2003-12-02', 'Calapan city', '09876345637', 6, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `Donation_ID` int(11) NOT NULL,
  `Sponsor_ID` int(11) DEFAULT NULL,
  `Activity_ID` int(11) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Date_Donated` date DEFAULT NULL,
  `Mode_of_Payment` varchar(50) DEFAULT NULL,
  `Status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`Donation_ID`, `Sponsor_ID`, `Activity_ID`, `Amount`, `Date_Donated`, `Mode_of_Payment`, `Status`) VALUES
(3, 3, 1, 20000.00, '2025-10-28', 'GCash', 'Approved'),
(4, 3, 1, 3000.00, '2025-10-28', 'PayMaya', 'Approved'),
(5, 3, 1, 3000.00, '2025-10-28', 'PayMaya', 'Rejected'),
(6, 3, 3, 30000.00, '2025-10-30', 'GCash', 'Approved'),
(7, 3, 4, 100000.00, '2025-10-31', 'Cash', 'Approved'),
(8, 4, 1, 20000.00, '2025-10-31', 'GCash', 'Approved'),
(9, 5, 2, 500000.00, '2025-10-31', 'Bank Transfer', 'Approved'),
(10, 5, 6, 5000.00, '2025-11-03', 'Cash', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `Project_ID` int(11) NOT NULL,
  `Project_Name` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Start_Date` date DEFAULT NULL,
  `End_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`Project_ID`, `Project_Name`, `Description`, `Start_Date`, `End_Date`) VALUES
(1, 'Davao Earthquake', 'A powerful quake struck the waters off Davao Oriental on Friday morning, leaving at least two people dead and triggering a tsunami alert that was later lifted, the Philippine Institute of Volcanology and Seismology (PHIVOLCS) said.  PHIVOLCS said the magnitude 7.4 quake hit some 44 kilometers northeast of Manay town. The government seismology office earlier put the magnitude at 7.6, but subsequently lowered the figure to 7.4.', '2025-10-22', '2025-11-07'),
(2, 'Cebu Earthquake', 'Cebu has experienced significant seismic activity recently, including a magnitude 6.9 earthquake on September 30, 2025, and a magnitude 5.8 aftershock on October 13, 2025, resulting in casualties and widespread damage.', '2025-10-22', '2025-12-04'),
(3, 'Hand in Hand for Hope', 'The “Hand in Hand for Hope” project aims to uplift the living conditions of underprivileged families through collective community action. It focuses on education, health, environmental care, and livelihood support. The project encourages active volunteerism and strengthens the bond between community members by fostering compassion, teamwork, and social responsibility.', '2025-10-23', '2026-10-23');

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE `sponsor` (
  `Sponsor_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Address` varchar(150) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`Sponsor_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Gender`, `Email`, `Phone`, `Address`, `User_ID`) VALUES
(3, 'Jackes', 'San Jose', 'De Guzman', 'Male', 'jack@gmail.com', '09564764678', 'Davao City', 4),
(4, 'Mike', 'Joo', 'Sang', 'Male', 'mike@gmail.com', '09878765654', 'Lumangbayan Calapan City', 13),
(5, 'Eunice', 'San Jose', 'Caballero', 'Female', 'eunice@gmail.com', '09564764123', 'Davao City', 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('admin','volunteer','sponsor','beneficiary') NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `Username`, `Password`, `Role`, `Created_At`) VALUES
(1, 'jessy12', '$2y$10$Vcda8cyk8m7/Ia0cAGRj.uSM65FPNhPJmNQp0/XSnq689/oIqPAja', 'beneficiary', '2025-10-23 09:30:04'),
(4, 'jack12', '$2y$10$JhJcCdOfBj5GXdQ2Pqra6exd.0ClpHT1oiXSWklQM7hdRwg6qE2f6', 'sponsor', '2025-10-23 10:34:50'),
(8, 'marriane12', '$2y$10$OeuXFEyKYoDq7SYpCZSije5gm5xlaz4538i3s6lIvzOiBrLG2VG9C', 'volunteer', '2025-10-23 10:45:06'),
(10, 'olamami', '$2y$10$If2DvlvP8JaMMEfvX/ADyeC2WmOu.xt7seHbHwL52XbuKRPpknpoe', 'admin', '2025-10-23 12:14:52'),
(11, 'khrisbelle12', '$2y$10$WxW3eXPCShmIPuTA.Vhu2uHbG9NhKwJU0kosZC/JjSLimcXoEiTrq', 'beneficiary', '2025-10-27 03:42:51'),
(12, 'jhoanna', '$2y$10$kL/mZ5CzoD0/vp/QU5NMtephEi1nYxqqvTY4oZZDtQNRtTccQloCq', 'beneficiary', '2025-10-28 10:48:28'),
(13, 'mike', '$2y$10$UaiB/CyxTjSAaDzIKr03oOA.2TreAWEaYoI2NpHC/kkGwBaK51HZa', 'sponsor', '2025-10-31 01:42:18'),
(14, 'eunice', '$2y$10$GaIB5RTHcm2nOCNW.AysBO.41j.6Kv02D02bRPiYfumOxWR8txRo2', 'sponsor', '2025-10-31 01:54:17'),
(15, '123', '$2y$10$Rj2uQfzYJeQ/Vq0j7UG.3OKgrJisShrFEjH8E6FuOApj4PtfueSg.', 'admin', '2025-11-03 06:42:08'),
(17, '1234', '$2y$10$IL/ueTqR9sXUpMcedNMhfuoH0IrS9FZ4czbVVrC7wE5GE3CvGMnHa', 'admin', '2025-11-03 06:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `Volunteer_ID` int(11) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Activity_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `Status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `Date_Joined` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`Volunteer_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Gender`, `Email`, `Phone`, `Activity_ID`, `User_ID`, `Status`, `Date_Joined`) VALUES
(1, 'Marriane', 'Mina', 'Cebu', 'Female', 'marrianecebu112@gmail.com', '09871234536', 4, 8, 'Pending', '2025-10-30 00:33:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`Activity_ID`),
  ADD KEY `fk_project_activity` (`Project_ID`);

--
-- Indexes for table `activity_participation`
--
ALTER TABLE `activity_participation`
  ADD PRIMARY KEY (`Participation_ID`),
  ADD KEY `Activity_ID` (`Activity_ID`),
  ADD KEY `Project_ID` (`Project_ID`),
  ADD KEY `Beneficiary_ID` (`Beneficiary_ID`),
  ADD KEY `Sponsor_ID` (`Sponsor_ID`),
  ADD KEY `Volunteer_ID` (`Volunteer_ID`),
  ADD KEY `Donation_ID` (`Donation_ID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD KEY `fkaduse` (`User_ID`);

--
-- Indexes for table `beneficiary`
--
ALTER TABLE `beneficiary`
  ADD PRIMARY KEY (`Beneficiary_ID`),
  ADD KEY `actben` (`Activity_ID`),
  ADD KEY `fk_beneficiary_user` (`User_ID`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`Donation_ID`),
  ADD KEY `Sponsor_ID` (`Sponsor_ID`),
  ADD KEY `Activity_ID` (`Activity_ID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`Project_ID`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`Sponsor_ID`),
  ADD KEY `fk_sponsor_user` (`User_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`Volunteer_ID`),
  ADD KEY `Assigned_Activity_ID` (`Activity_ID`),
  ADD KEY `fk_volunteer_user` (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `Activity_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `activity_participation`
--
ALTER TABLE `activity_participation`
  MODIFY `Participation_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beneficiary`
--
ALTER TABLE `beneficiary`
  MODIFY `Beneficiary_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `Donation_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `Project_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `Sponsor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `Volunteer_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`Project_ID`) REFERENCES `projects` (`Project_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_project_activity` FOREIGN KEY (`Project_ID`) REFERENCES `projects` (`Project_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `activity_participation`
--
ALTER TABLE `activity_participation`
  ADD CONSTRAINT `activity_participation_ibfk_1` FOREIGN KEY (`Activity_ID`) REFERENCES `activities` (`Activity_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_participation_ibfk_2` FOREIGN KEY (`Project_ID`) REFERENCES `projects` (`Project_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_participation_ibfk_3` FOREIGN KEY (`Beneficiary_ID`) REFERENCES `beneficiary` (`Beneficiary_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_participation_ibfk_4` FOREIGN KEY (`Sponsor_ID`) REFERENCES `sponsor` (`Sponsor_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_participation_ibfk_5` FOREIGN KEY (`Volunteer_ID`) REFERENCES `volunteer` (`Volunteer_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_participation_ibfk_6` FOREIGN KEY (`Donation_ID`) REFERENCES `donations` (`Donation_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fkaduse` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `beneficiary`
--
ALTER TABLE `beneficiary`
  ADD CONSTRAINT `actben` FOREIGN KEY (`Activity_ID`) REFERENCES `activities` (`Activity_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_beneficiary_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`Sponsor_ID`) REFERENCES `sponsor` (`Sponsor_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`Activity_ID`) REFERENCES `activities` (`Activity_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD CONSTRAINT `fk_sponsor_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `fk_volunteer_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `volunteer_ibfk_1` FOREIGN KEY (`Activity_ID`) REFERENCES `activities` (`Activity_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
