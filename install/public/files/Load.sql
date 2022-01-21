-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Dumping structure for table db_queue_pda.counter
DROP TABLE IF EXISTS `counter`;
CREATE TABLE IF NOT EXISTS `counter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.counter: ~16 rows (approximately)
DELETE FROM `counter`;
/*!40000 ALTER TABLE `counter` DISABLE KEYS */;
INSERT INTO `counter` (`id`, `name`, `description`, `created_at`, `updated_at`, `status`) VALUES
  (1, '1', NULL, '2020-04-15', '2020-07-11', 1),
  (2, '2', NULL, '2020-04-15', '2018-07-27', 1),
  (3, '3', NULL, '2020-04-15', '2018-07-27', 1),
  (4, '4', NULL, '2020-04-15', '2018-07-27', 1),
  (5, '5', NULL, '2020-04-15', '2018-07-27', 1),
  (6, '6', NULL, '2020-04-15', '2018-07-27', 1),
  (7, '7', NULL, '2020-04-15', '2018-07-27', 1),
  (8, '8', NULL, '2020-04-15', '2018-07-27', 1),
  (9, '9', NULL, '2020-04-15', '2018-07-27', 1),
  (10, '10', NULL, '2020-04-15', '2018-07-27', 1),
  (11, '11', NULL, '2020-04-15', '2018-07-27', 1),
  (12, '12', NULL, '2020-04-15', '2018-07-27', 1),
  (13, '13', NULL, '2020-04-15', '2018-07-27', 1),
  (14, '14', NULL, '2020-04-15', '2018-07-27', 1),
  (15, '15', NULL, '2020-04-15', '2018-07-27', 1),
  (16, '16', NULL, '2020-04-15', '2018-07-27', 1);
/*!40000 ALTER TABLE `counter` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.department
DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `key` varchar(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.department: ~7 rows (approximately)
DELETE FROM `department`;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` (`id`, `name`, `description`, `key`, `created_at`, `updated_at`, `status`) VALUES
  (1, 'Department 1', 'Apple department', 'a', '2016-10-31 10:34:19', '2020-07-18 17:08:00', 1),
  (2, 'Department 2', 'Banana Department', 'b', '2016-11-09 07:18:01', '2020-07-18 17:08:17', 1),
  (3, 'Department 3', 'Coconut Department', 'c', '2016-11-10 08:02:44', '2020-07-18 17:08:32', 1),
  (4, 'Department 4', 'Orange Department', 'd', '2016-11-10 08:02:44', '2020-07-18 17:08:45', 1),
  (5, 'Department 5', NULL, 'e', '2020-05-15 12:36:12', '2020-07-18 17:08:58', 1),
  (6, 'Department 6', NULL, 'f', '2020-05-15 12:36:25', '2020-07-18 17:09:11', 1),
  (7, 'Department 7', NULL, 'q', '2020-05-15 12:36:39', '2020-07-18 19:14:13', 1);
/*!40000 ALTER TABLE `department` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.display
DROP TABLE IF EXISTS `display`;
CREATE TABLE IF NOT EXISTS `display` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `direction` varchar(10) DEFAULT 'left',
  `color` varchar(10) DEFAULT '#ffffff',
  `background_color` varchar(10) NOT NULL DEFAULT '#cdcdcd',
  `border_color` varchar(10) NOT NULL DEFAULT '#ffffff',
  `time_format` varchar(20) DEFAULT 'h:i:s A',
  `date_format` varchar(50) DEFAULT 'd M, Y',
  `updated_at` datetime DEFAULT NULL,
  `display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-single, 2/3-counter,4-department,5-hospital',
  `keyboard_mode` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-inactive,1-active',
  `sms_alert` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-inactive, 1-active ',
  `show_note` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-inactive, 1-active ',
  `show_officer` tinyint(1) NOT NULL DEFAULT '1',
  `show_department` tinyint(1) NOT NULL DEFAULT '1',
  `alert_position` int(2) NOT NULL DEFAULT '3',
  `language` varchar(20) DEFAULT 'English',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.display: ~0 rows (approximately)
DELETE FROM `display`;
/*!40000 ALTER TABLE `display` DISABLE KEYS */;
INSERT INTO `display` (`id`, `message`, `direction`, `color`, `background_color`, `border_color`, `time_format`, `date_format`, `updated_at`, `display`, `keyboard_mode`, `sms_alert`, `show_note`, `show_officer`, `show_department`, `alert_position`, `language`) VALUES
  (1, 'Token - Queue Management System', 'left', '#ff0404', '#000000', '#3c8dbc', 'H:i:s', 'd M, Y', '2020-07-18 13:21:04', 2, 1, 1, 0, 1, 1, 2, 'English');
/*!40000 ALTER TABLE `display` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.message
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(128) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `sender_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete',
  `receiver_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=unseen, 1=seen, 2=delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.message: ~8 rows (approximately)
DELETE FROM `message`;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` (`id`, `sender_id`, `receiver_id`, `subject`, `message`, `attachment`, `datetime`, `sender_status`, `receiver_status`) VALUES
  (1, 1, 5, 'Tedsf', 'gffg', NULL, '2019-07-29 05:54:00', 0, 1),
  (2, 1, 2, 'adf', 'ghdf', NULL, '2018-07-29 05:54:00', 0, 0),
  (3, 1, 3, 'hg', 'efff', NULL, '2018-07-29 05:54:00', 1, 1),
  (4, 1, 4, '3fsa', 'dasf', NULL, '2018-07-29 05:54:00', 0, 1),
  (5, 5, 1, '33', 'ewrf', NULL, '2018-07-29 05:54:00', 0, 1),
  (6, 2, 1, 'dc', 'afsc', NULL, '2018-07-29 05:54:00', 0, 1),
  (7, 3, 1, 'asdf', 'xcvs', NULL, '2018-07-29 05:54:00', 0, 1),
  (8, 4, 1, 'sx', 'exf', NULL, '2018-07-29 05:54:00', 0, 1),
  (9, 1, 6, 'AAA1', 'TAFD', NULL, '2020-07-09 22:25:00', 0, 0),
  (10, 2, 7, 'AAA1', 'TSFD', NULL, '2020-07-09 10:32:46', 0, 0),
  (11, 1, 5, 'dd', 'TEST', 'public/assets/attachments/69865.jpg', '2020-07-11 10:38:44', 0, 1),
  (12, 1, 7, 'ef', 'Test', 'public/assets/attachments/43195.jpg', '2020-07-13 00:11:47', 0, 0),
  (13, 1, 8, 'Test Subject', 'cy: No \'Access-Control-Allow-Origin\' head', 'public/assets/attachments/33884.jpg', '2020-07-13 15:23:44', 0, 1);
/*!40000 ALTER TABLE `message` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.setting
DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `favicon` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `copyright_text` varchar(255) DEFAULT NULL,
  `direction` varchar(10) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `timezone` varchar(32) NOT NULL DEFAULT 'Asia/Dhaka',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.setting: ~0 rows (approximately)
DELETE FROM `setting`;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` (`id`, `title`, `description`, `logo`, `favicon`, `email`, `phone`, `address`, `copyright_text`, `direction`, `language`, `timezone`) VALUES
  (1, 'Token - Queue Management System', 'Queue', 'public/assets/img/icons/logo.jpg', 'public/assets/img/icons/favicon.jpg', 'admin@example.com', '+325 252 222', 'Demo street, NY-10000', 'copyright@2022', NULL, 'en', 'Asia/Dhaka');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.sms_history
DROP TABLE IF EXISTS `sms_history`;
CREATE TABLE IF NOT EXISTS `sms_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) DEFAULT NULL,
  `to` varchar(20) DEFAULT NULL,
  `message` varchar(512) DEFAULT NULL,
  `response` varchar(512) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.sms_history: ~31 rows (approximately)
DELETE FROM `sms_history`;
/*!40000 ALTER TABLE `sms_history` DISABLE KEYS */;
INSERT INTO `sms_history` (`id`, `from`, `to`, `message`, `response`, `created_at`) VALUES
  (3, 'Queue Management Sys', '8801821742285', 'Test', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"status\\": \\"4\\",\\n        \\"error-text\\": \\"Bad Credentials\\"\\n    }]\\n}","message":"Test"}', '2020-04-28 16:03:09'),
  (4, 'Queue Management Sys', '8801821742285', 'Test', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"status\\": \\"3\\",\\n        \\"error-text\\": \\"Invalid from param\\"\\n    }]\\n}","message":"Test"}', '2020-04-28 22:05:19'),
  (5, 'Queue Management Sys', '8801821742285', 'TEST B', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"8801821742285\\",\\n        \\"status\\": \\"29\\",\\n        \\"error-text\\": \\"Non White-listed Destination - rejected\\"\\n    }]\\n}","message":"TEST B"}', '2020-04-28 23:25:59'),
  (6, 'Queue Management Sys', '3367019711', 'TEST B', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"3367019711\\",\\n        \\"status\\": \\"29\\",\\n        \\"error-text\\": \\"Non White-listed Destination - rejected\\"\\n    }]\\n}","message":"TEST B"}', '2020-04-28 23:27:20'),
  (7, 'Queue Management Sys', '0123456789', 'Token No: A106 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 2. \\r\\n 2020-05-14 23:44:49.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A106 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 2. \\\\r\\\\n 2020-05-14 23:44:49."}', '2020-05-14 23:59:49'),
  (8, 'Queue Management Sys', '0123456789', 'Token No: A204 \\r\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:52:00.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A204 \\\\r\\\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:52:00."}', '2020-05-14 23:59:50'),
  (9, 'Queue Management Sys', '0123456789', 'Token No: A304 \\r\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:52:06.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A304 \\\\r\\\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:52:06."}', '2020-05-14 23:59:51'),
  (10, 'Queue Management Sys', '0123456789', 'Token No: A107 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 1. \\r\\n 2020-05-14 23:45:24.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A107 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-14 23:45:24."}', '2020-05-15 00:00:06'),
  (11, 'Queue Management Sys', '0123456789', 'Token No: A203 \\r\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:45.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A203 \\\\r\\\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:45."}', '2020-05-15 00:00:07'),
  (12, 'Queue Management Sys', '0123456789', 'Token No: A303 \\r\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:49.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A303 \\\\r\\\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:49."}', '2020-05-15 00:00:07'),
  (13, 'Queue Management Sys', '0123456789', 'Token No: A202 \\r\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:29.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A202 \\\\r\\\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:29."}', '2020-05-15 00:00:11'),
  (14, 'Queue Management Sys', '0123456789', 'Token No: A302 \\r\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:39.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A302 \\\\r\\\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:39."}', '2020-05-15 00:00:12'),
  (15, 'Queue Management Sys', '0123456789', 'Token No: A201 \\r\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:07.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A201 \\\\r\\\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:07."}', '2020-05-15 00:00:16'),
  (16, 'Queue Management Sys', '0123456789', 'Token No: A301 \\r\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\r\\n Your waiting no is 3. \\r\\n 2020-05-14 23:51:23.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A301 \\\\r\\\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-14 23:51:23."}', '2020-05-15 00:00:16'),
  (17, 'Queue Management Sys', '0123456789', 'Token No: A101 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 2. \\r\\n 2020-05-15 00:20:34.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A101 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 2. \\\\r\\\\n 2020-05-15 00:20:34."}', '2020-05-15 00:48:46'),
  (18, 'Queue Management Sys', '0123456789', 'Token No: O502 \\r\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\r\\n Your waiting no is 3. \\r\\n 2020-05-15 00:20:39.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: O502 \\\\r\\\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\\\r\\\\n Your waiting no is 3. \\\\r\\\\n 2020-05-15 00:20:39."}', '2020-05-15 00:48:47'),
  (19, 'Queue Management Sys', '0123456789', 'Token No: A105 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:46.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A105 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:46."}', '2020-05-15 01:18:43'),
  (20, 'Queue Management Sys', '0123456789', 'Token No: O504 \\r\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:52.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: O504 \\\\r\\\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:52."}', '2020-05-15 01:18:44'),
  (21, 'Queue Management Sys', '0123456789', 'Token No: A106 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 01:09:26.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A106 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 01:09:26."}', '2020-05-15 01:18:54'),
  (22, 'Queue Management Sys', '0123456789', 'Token No: O505 \\r\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:57.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: O505 \\\\r\\\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:57."}', '2020-05-15 01:18:55'),
  (23, 'Queue Management Sys', '0123456789', 'Token No: A104 \\r\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:14.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A104 \\\\r\\\\n Department: Apple, Counter: 1 and Officer: Wane Willian. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:14."}', '2020-05-15 02:20:50'),
  (24, 'Queue Management Sys', '0123456789', 'Token No: A205 \\r\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 01:06:30.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A205 \\\\r\\\\n Department: Apple, Counter: 2 and Officer: Jane Doe. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 01:06:30."}', '2020-05-15 02:20:51'),
  (25, 'Queue Management Sys', '0123456789', 'Token No: A304 \\r\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:35.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: A304 \\\\r\\\\n Department: Apple, Counter: 3 and Officer: Annie Smith. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:35."}', '2020-05-15 02:20:52'),
  (26, 'Queue Management Sys', '0123456789', 'Token No: O502 \\r\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\r\\n Your waiting no is 1. \\r\\n 2020-05-15 00:54:26.', '{"status":true,"request_url":"https:\\/\\/rest.nexmo.com\\/sms\\/json?","success":"{\\n    \\"message-count\\": \\"1\\",\\n    \\"messages\\": [{\\n        \\"to\\": \\"0123456789\\",\\n        \\"status\\": \\"6\\",\\n        \\"error-text\\": \\"Unroutable message - rejected\\"\\n    }]\\n}","message":"Token No: O502 \\\\r\\\\n Department: Orange, Counter: 5 and Officer: Alex Smith. \\\\r\\\\n Your waiting no is 1. \\\\r\\\\n 2020-05-15 00:54:26."}', '2020-05-15 02:20:52'),
  (32, '01919742285', '8801821742285', 'test', '{"status":true,"request_url":"https:\\/\\/platform.clickatell.com\\/messages\\/http\\/send?","success":"{\\"messages\\":[],\\"errorCode\\":607,\\"error\\":\\"Invalid FROM number.\\",\\"errorDescription\\":\\"User specified FROM number, but integration isn\'t two-way.\\"}","message":"test"}', '2020-05-17 14:12:10'),
  (33, '8801919742285', '8801821742285', 'test', '{"status":true,"request_url":"https:\\/\\/platform.clickatell.com\\/messages\\/http\\/send?","success":"{\\"messages\\":[],\\"errorCode\\":607,\\"error\\":\\"Invalid FROM number.\\",\\"errorDescription\\":\\"User specified FROM number, but integration isn\'t two-way.\\"}","message":"test"}', '2020-05-17 14:18:48'),
  (34, '8801919742285', '8801821742285', 'TEST', '{"status":true,"request_url":"https:\\/\\/platform.clickatell.com\\/messages\\/http\\/send?","success":"{\\"messages\\":[{\\"apiMessageId\\":\\"d737eadad6f9476ca91924a8cf31a661\\",\\"accepted\\":true,\\"to\\":\\"8801821742285\\",\\"errorCode\\":null,\\"error\\":null,\\"errorDescription\\":null}]}","message":"TEST"}', '2020-05-17 14:24:49'),
  (35, '7082747358', '8801821742285', 'TEST', '{"status":true,"request_url":"https:\\/\\/platform.clickatell.com\\/messages\\/http\\/send?","success":"{\\"messages\\":[],\\"errorCode\\":607,\\"error\\":\\"Invalid FROM number.\\",\\"errorDescription\\":\\"User specified FROM number, but integration isn\'t two-way.\\"}","message":"TEST"}', '2020-05-17 14:30:49'),
  (36, '17082747358', '8801821742285', 'TEST', '{"status":true,"request_url":"https:\\/\\/platform.clickatell.com\\/messages\\/http\\/send?","success":"{\\"messages\\":[{\\"apiMessageId\\":\\"c5d7a69898ef43348e9b3cd7ce7a5096\\",\\"accepted\\":true,\\"to\\":\\"8801821742285\\",\\"errorCode\\":null,\\"error\\":null,\\"errorDescription\\":null}]}","message":"TEST"}', '2020-05-17 14:45:38');
/*!40000 ALTER TABLE `sms_history` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.sms_setting
DROP TABLE IF EXISTS `sms_setting`;
CREATE TABLE IF NOT EXISTS `sms_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(20) NOT NULL DEFAULT 'nexmo',
  `api_key` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `from` varchar(50) DEFAULT NULL,
  `sms_template` text,
  `recall_sms_template` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.sms_setting: ~0 rows (approximately)
DELETE FROM `sms_setting`;
/*!40000 ALTER TABLE `sms_setting` DISABLE KEYS */;
INSERT INTO `sms_setting` (`id`, `provider`, `api_key`, `username`, `password`, `from`, `sms_template`, `recall_sms_template`) VALUES
  (1, 'clickatell', '-K1xA==', 'marquisvirgo', '05kOeOvm', '11222747358', 'Token No: [TOKEN] \\r\\n Department: [DEPARTMENT], Counter: [COUNTER] and Officer: [OFFICER]. \\r\\n Your waiting no is [WAIT]. \\r\\n [DATE].', 'Please contact urgently. Token No: [TOKEN] \\r\\n Department: [DEPARTMENT], Counter: [COUNTER] and Officer: [OFFICER]. \\r\\n [DATE].');
/*!40000 ALTER TABLE `sms_setting` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.token
DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token_no` varchar(10) DEFAULT NULL,
  `client_mobile` varchar(20) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `counter_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `note` varchar(512) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_vip` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-pending, 1-complete, 2-stop',
  `sms_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-pending, 1-sent, 2-quick-send',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.token: ~14 rows (approximately)
DELETE FROM `token`;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;

INSERT INTO `token` (`id`, `token_no`, `client_mobile`, `department_id`, `counter_id`, `user_id`, `note`, `created_by`, `created_at`, `updated_at`, `is_vip`, `status`, `sms_status`) VALUES  
(1, 'A101', '0123456789', 1, 1, 2, NULL, 2, DATE_ADD(NOW(), INTERVAL -7 DAY), NOW(), NULL, 1, 1),
(2, 'A201', '0123456789', 1, 2, 2, NULL, 2, DATE_ADD(NOW(), INTERVAL -7 DAY), NOW(), NULL, 1, 1),
(3, 'A202', '0123456789', 1, 2, 4, NULL, NULL, DATE_ADD(NOW(), INTERVAL -7 DAY), NOW(), NULL, 1, 1),
(4, 'A301', '0123456789', 1, 3, 4, NULL, 2, DATE_ADD(NOW(), INTERVAL -6 DAY), NOW(), NULL, 1, 1),
(5, 'VA302', '0123456789', 1, 3, 4, NULL, 2, DATE_ADD(NOW(), INTERVAL -5 DAY), NULL, 1, 0, 0),
(6, 'VC501', '0123456789', 3, 5, 1, NULL, NULL, DATE_ADD(NOW(), INTERVAL -5 DAY), NULL, 1, 0, 0),
(7, 'VC502', '0123456789', 3, 5, 1, NULL, NULL, DATE_ADD(NOW(), INTERVAL -4 DAY), NULL, 1, 0, 0),
(8, 'B401', '0123456789', 2, 4, 5, NULL, 2, DATE_ADD(NOW(), INTERVAL -4 DAY), NULL, NULL, 0, 0),
(9, 'O601', '0123456789', 4, 6, 4, NULL, 1, DATE_ADD(NOW(), INTERVAL -4 DAY), NULL, NULL, 0, 0),
(10, 'O602', '0123456789', 4, 6, 5, NULL, 1, DATE_ADD(NOW(), INTERVAL -4 DAY), NOW(), NULL, 1, 0),
(11, 'A101', '0123456789', 1, 1, 2, NULL, 1, DATE_ADD(NOW(), INTERVAL -4 DAY), NULL, NULL, 0, 0),
(12, 'A201', '0123456789', 1, 2, 2, NULL, 1, DATE_ADD(NOW(), INTERVAL -4 DAY), NULL, NULL, 0, 0),
(13, 'VA202', '0123456789', 1, 2, 1, NULL, 1, DATE_ADD(NOW(), INTERVAL -3 DAY), NOW(), 1, 1, 0),
(14, 'A301', '0123456789', 1, 3, 4, NULL, 1, DATE_ADD(NOW(), INTERVAL -3 DAY), NULL, NULL, 0, 0),
(15, 'A302', '0123456789', 1, 3, 4, NULL, NULL, DATE_ADD(NOW(), INTERVAL -2 DAY), NULL, NULL, 0, 0),
(16, 'C501', '0123456789', 3, 5, 1, NULL, NULL, DATE_ADD(NOW(), INTERVAL -2 DAY), NOW(), NULL, 1, 0),
(17, 'C502', '0123456789', 3, 5, 1, NULL, 3, DATE_ADD(NOW(), INTERVAL -2 DAY), NULL, NULL, 0, 0),
(18, 'B401', '0123456789', 2, 4, 5, NULL, 4, DATE_ADD(NOW(), INTERVAL -2 DAY), NULL, NULL, 0, 0),
(19, 'O601', '0123456789', 4, 6, 8, NULL, 5, DATE_ADD(NOW(), INTERVAL -2 DAY), NOW(), NULL, 1, 0),
(20, 'O602', '0123456789', 4, 6, 2, NULL, NULL, DATE_ADD(NOW(), INTERVAL -1 DAY), NULL, NULL, 0, 0),
(22, 'A101', '0123456789', 1, 1, 2, NULL, 3, NOW(), NULL, NULL, 2, 0), 
(23, 'VA201', '0123456789', 1, 2, 4, NULL, 2, NOW(), NULL, 1, 1, 0),
(24, 'A202', '0123456789', 1, 2, 2, NULL, 1, NOW(), NULL, NULL, 0, 0),
(25, 'A301', '0123456789', 1, 3, 4, NULL, 2, NOW(), NULL, NULL, 1, 0),
(26, 'A302', '0123456789', 1, 3, 4, NULL, 4, NOW(), NULL, NULL, 0, 0),
(27, 'VC501', '0123456789', 3, 5, 1, NULL, 5, NOW(), NULL, 1, 1, 0),
(28, 'C502', '0123456789', 3, 5, 1, NULL, 3, NOW(), NULL, NULL, 2, 0),
(29, 'B401', '0123456789', 2, 4, 5, NULL, 6, NOW(), NULL, NULL, 0, 0),
(30, 'O601', '0123456789', 4, 6, 6, NULL, 7, NOW(), NULL, NULL, 2, 0),
(31, 'O602', '0123456789', 4, 6, 7, NULL, 8, NOW(), NULL, NULL, 2, 0);
/*!40000 ALTER TABLE `token` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.token_setting
DROP TABLE IF EXISTS `token_setting`;
CREATE TABLE IF NOT EXISTS `token_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `counter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.token_setting: ~8 rows (approximately)
DELETE FROM `token_setting`;
/*!40000 ALTER TABLE `token_setting` DISABLE KEYS */;
INSERT INTO `token_setting` (`id`, `department_id`, `counter_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
  (7, 1, 1, 2, '2020-05-14 23:43:49', NULL, 1),
  (8, 1, 2, 4, '2020-05-14 23:50:42', NULL, 1),
  (9, 1, 3, 5, '2020-05-14 23:50:55', NULL, 1),
  (10, 4, 5, 6, '2020-05-15 00:19:46', NULL, 1),
  (11, 5, 4, 7, '2020-05-15 14:54:00', NULL, 1),
  (12, 6, 6, 9, '2020-05-15 14:54:15', NULL, 1),
  (13, 6, 7, 8, '2020-05-15 14:54:35', NULL, 1),
  (14, 7, 8, 10, '2020-05-15 14:56:49', NULL, 1);
/*!40000 ALTER TABLE `token_setting` ENABLE KEYS */;

-- Dumping structure for table db_queue_pda.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(25) DEFAULT NULL,
  `lastname` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=officer, 2=staff, 3=client, 5=admin',
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,2=inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Dumping data for table db_queue_pda.user: ~11 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `department_id`, `mobile`, `photo`, `user_type`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
  (1, 'John', 'Doe', 'admin@codekernel.net', '$2y$10$2lEkxufgGpCmXG2UY272WO/Cv6vna3TM4qP3rTYMCr7GcFu2Mhqyq', 0, '0123456789', NULL, 5, '4hcPovxSEJZif73bbxV46hoc77SdEHkSEqslUWgpRhvmts2Xuo0PG5rCbFC8', '2016-10-30 00:00:00', '2020-07-17 00:22:22', 1),
  (2, 'Wane', 'Willian', 'officer@codekernel.net', '$2y$10$U.JTKdWTWSRAw.H6Z.ZS3uJZrWaq3PssflkEe0xNW3ddNu5XS.rZe', 1, '174222584', NULL, 1, 'SLj4LiZ4RUZCXdOIlJ0bt05XbVz5CQx8B5dh67jUF8JUyyuSSRheAkxYXpvm', '2016-10-30 00:00:00', '2020-07-18 01:11:14', 1),
  (3, 'Xada', 'Roe', 'receptionist@codekernel.net', '$2y$10$FcsPFyCggD1kfn91WSDhSeqHc7n7j9X/u/Zbyn8kEx6qGjMn7mup2', 2, '0123456789', NULL, 2, 'pYM7rlWoDzUmUuRRPv6NFz33dePk5eLkYUL8thjYCQI5yVZBPkzpq7oB5ZF0', '2016-10-30 00:00:00', '2020-07-16 15:37:17', 1),
  (4, 'Jane', 'Doe', 'jane@doe.com', '$2y$10$Rpanf/X2B272cwTgjmKRMeqTlyham0iRu6WmFIAR4b6gaI2Mvh54m', 3, '0123456789', NULL, 1, NULL, '2018-07-29 00:00:00', NULL, 1),
  (5, 'Annie', 'Smith', 'annie@example.com', '$2y$10$Rpanf/X2B272cwTgjmKRMeqTlyham0iRu6WmFIAR4b6gaI2Mvh54m', 3, '0123456789', NULL, 1, NULL, '2018-07-29 00:00:00', NULL, 1),
  (6, 'Alex', 'Smith', 'alex@codekernel.net', '$2y$10$5DwvyIRa5P4CYhAhTQkjeu3BmX.J5sbokQQUuHh/O4pNUv02QvOKq', 4, '01821742285', NULL, 1, NULL, '2020-05-15 00:00:00', '2020-07-18 01:11:23', 1),
  (7, 'Bob', 'Banny', 'bob@codekernel.net', '$2y$10$Zfby6SvTitbJ0bO9CZI3GubPiMtM6T/Xv1VIsDJoyzgg.edxSyE8.', 5, '0123456789', NULL, 1, NULL, '2020-05-15 00:00:00', NULL, 1),
  (8, 'Danniyel', 'Dan', 'dan@codekernel.net', '$2y$10$l09QqbcYQ3BXiiScfHlMHuhXJKbLm8GyZObj7SWJ6a3fSK7jwvp0O', 6, '0123456789', NULL, 1, NULL, '2020-05-15 00:00:00', NULL, 1),
  (9, 'Jennifer', 'Doe', 'jennifer@codekernel.net', '$2y$10$ztTEJRFdS42R9JueIEAgnumeH1Da99iWHGA5ove6zGjOxfDsTEEOe', 6, '0123456789', NULL, 1, NULL, '2020-05-15 00:00:00', NULL, 1),
  (10, 'Tylor', 'Ronnie', 'tylor@codekernel.net', '$2y$10$8f6GXVBrCDILL6SZcQs2aOuOrnCHweU1aPQ61Tz27tvqqv9htFOo.', 7, '0123456789', NULL, 1, NULL, '2020-05-15 00:00:00', NULL, 1);
 

-- Dumping structure for table db_queue_pda.user_social_accounts
DROP TABLE IF EXISTS `user_social_accounts`;
CREATE TABLE IF NOT EXISTS `user_social_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider_name` varchar(32) DEFAULT NULL,
  `provider_id` varchar(64) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Dumping structure for table db_queue.display_custom
DROP TABLE IF EXISTS `display_custom`;
CREATE TABLE IF NOT EXISTS `display_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `counters` varchar(64) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1-active, 2-inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table db_queue.display_custom: ~4 rows (approximately)
DELETE FROM `display_custom`;
/*!40000 ALTER TABLE `display_custom` DISABLE KEYS */;
INSERT INTO `display_custom` (`id`, `name`, `description`, `counters`, `status`, `created_at`, `updated_at`) VALUES
  (1, 'Floor 1', 'TEST 1', '1,2,3,6', 1, '2020-10-01 11:34:44', '2020-10-01 22:40:10'),
  (2, 'Floor 2', 'TEST 2', '6,7,8,9,10', 0, '2020-10-01 11:35:28', '2020-10-01 17:17:20'),
  (3, 'Floor 3', 'TEST 3', '8,9,10,11,12,13', 1, '2020-10-01 11:35:51', '2020-10-01 16:48:36'),
  (4, 'Floor 4', 'TESTS Floor', '4,5,6,7', 1, '2020-10-01 18:11:00', '2020-10-01 14:58:27');
/*!40000 ALTER TABLE `display_custom` ENABLE KEYS */;