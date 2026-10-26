CREATE TABLE `angreifer` (
  `ip` varchar(45) NOT NULL,
  `land` varchar(255) DEFAULT NULL,
  `netzbetreiber` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `angreifer` VALUES ('1.1.1.1','Australia','Cloudflare, Inc');
INSERT INTO `angreifer` VALUES ('101.101.101.101','Taiwan','Taiwan Network Information Center');
INSERT INTO `angreifer` VALUES ('103.158.121.21','Indonesia','CDN');
INSERT INTO `angreifer` VALUES ('194.25.0.68','Germany','Deutsche Telekom AG');
INSERT INTO `angreifer` VALUES ('45.138.12.68','Hong Kong','TC DATACENTER LIMITED');
INSERT INTO `angreifer` VALUES ('45.45.237.39','United States','Infraly, LLC');
INSERT INTO `angreifer` VALUES ('8.8.8.8','United States','Google LLC');
INSERT INTO `angreifer` VALUES ('80.80.80.80','San Marino','CheckMe anycast resolver');
INSERT INTO `angreifer` VALUES ('9.9.9.9','United States','Quad9');
INSERT INTO `angreifer` VALUES ('94.154.43.99','The Netherlands','Storm Industries LLC');

CREATE TABLE `versuch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `angreifer` varchar(45) NOT NULL,
  `zeitpunkt` datetime NOT NULL DEFAULT current_timestamp(),
  `pfad` varchar(255) NOT NULL,
  `benutzername` varchar(255) DEFAULT NULL,
  `passwort` varchar(255) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `angriffsmuster` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_angreifer` (`angreifer`),
  CONSTRAINT `fk_angreifer` FOREIGN KEY (`angreifer`) REFERENCES `angreifer` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `versuch` VALUES (33,'45.45.237.39','2026-10-26 18:29:11','/.env','','','Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)','keine');
INSERT INTO `versuch` VALUES (39,'45.138.12.68','2026-10-26 20:36:37','/.env.anthropic','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (40,'45.138.12.68','2026-10-26 20:36:37','/.env.aws','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (41,'45.138.12.68','2026-10-26 20:36:37','/.env.aws.local','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (42,'45.138.12.68','2026-10-26 20:36:37','/.env.ai','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (43,'45.138.12.68','2026-10-26 20:36:37','/.env.openai','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (44,'45.138.12.68','2026-10-26 20:36:37','/.env.openai.local','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (45,'45.138.12.68','2026-10-26 20:36:37','/.env.gpt','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (46,'45.138.12.68','2026-10-26 20:36:37','/.env.llm','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (47,'45.138.12.68','2026-10-26 20:36:37','/.aws/config','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (48,'45.138.12.68','2026-10-26 20:36:37','/.aws/config.json','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (49,'45.138.12.68','2026-10-26 20:36:37','/.aws/credentials','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (50,'45.138.12.68','2026-10-26 20:36:37','/.aws/credentials.json','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (51,'45.138.12.68','2026-10-26 20:36:38','/.aws/secret.json','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (52,'45.138.12.68','2026-10-26 20:36:38','/config/azure.php','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (53,'45.138.12.68','2026-10-26 20:36:38','/config/aws.json','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (54,'45.138.12.68','2026-10-26 20:36:38','/config/aws.php','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (55,'45.138.12.68','2026-10-26 20:36:38','/config/aws.yml','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (56,'45.138.12.68','2026-10-26 20:36:39','/.env','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (57,'45.138.12.68','2026-10-26 20:36:39','/.git/config','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (58,'45.138.12.68','2026-10-26 20:36:39','/.git/logs/HEAD','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (59,'45.138.12.68','2026-10-26 20:36:39','/.env.example','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (60,'45.138.12.68','2026-10-26 20:36:39','/.env.dev','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (61,'45.138.12.68','2026-10-26 20:36:39','/.env.save','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (62,'45.138.12.68','2026-10-26 20:36:40','/.git/HEAD','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (63,'45.138.12.68','2026-10-26 20:36:40','/.git/index','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (64,'45.138.12.68','2026-10-26 20:36:41','/config.js','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (65,'45.138.12.68','2026-10-26 20:36:41','/.env.backup','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (66,'45.138.12.68','2026-10-26 20:36:41','/.env.bak','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (67,'45.138.12.68','2026-10-26 20:36:41','/.env.debug','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (68,'45.138.12.68','2026-10-26 20:36:41','/.env.development','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (69,'45.138.12.68','2026-10-26 20:36:41','/.git/description','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (70,'45.138.12.68','2026-10-26 20:36:41','/.git/packed-refs','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (71,'45.138.12.68','2026-10-26 20:36:42','/.aws/config.bak','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (72,'45.138.12.68','2026-10-26 20:36:42','/config.php','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (73,'45.138.12.68','2026-10-26 20:36:42','/config.php.bak','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (74,'45.138.12.68','2026-10-26 20:36:42','/wp-config.php.old','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (75,'45.138.12.68','2026-10-26 20:36:42','/wp-config.php.save','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (76,'45.138.12.68','2026-10-26 20:36:42','/wp-config.php.txt','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (100,'103.158.121.21','2026-10-26 21:42:43','/admin/config.php','','','ivre-masscan/1.3 https://github.com/robertdavidgraham/','keine');
INSERT INTO `versuch` VALUES (108,'94.154.43.99','2026-10-26 22:02:19','/cgi-bin/luci','','','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36','keine');
INSERT INTO `versuch` VALUES (109,'8.8.8.8','2026-10-22 22:20:12','/wp-login.php','admin','123456','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (110,'8.8.8.8','2026-10-26 22:20:12','/wp-login.php','admin','password','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (111,'8.8.8.8','2026-10-25 22:20:12','/wp-login.php','admin','admin','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (112,'8.8.8.8','2026-10-24 22:20:12','/wp-login.php','administrator','123456','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (113,'1.1.1.1','2026-10-23 22:20:13','/wp-login.php','admin\' OR \'1\'=\'1','x','Testdaten (Vorstellung)','SQL-Injection');
INSERT INTO `versuch` VALUES (114,'1.1.1.1','2026-10-22 22:20:13','/wp-login.php','admin','\' OR 1=1 --','Testdaten (Vorstellung)','SQL-Injection');
INSERT INTO `versuch` VALUES (115,'1.1.1.1','2026-10-26 22:20:13','/admin?id=1%20UNION%20SELECT%20username,password%20FROM%20users','','','Testdaten (Vorstellung)','SQL-Injection');
INSERT INTO `versuch` VALUES (116,'9.9.9.9','2026-10-25 22:20:13','/wp-login.php','<script>alert(1)</script>','test','Testdaten (Vorstellung)','XSS');
INSERT INTO `versuch` VALUES (117,'9.9.9.9','2026-10-24 22:20:13','/wp-login.php','<img src=x onerror=alert(1)>','test','Testdaten (Vorstellung)','XSS');
INSERT INTO `versuch` VALUES (118,'101.101.101.101','2026-10-23 22:20:13','/wp-login.php','\' OR 1=1 --<script>','x','Testdaten (Vorstellung)','SQL-Injection, XSS');
INSERT INTO `versuch` VALUES (119,'101.101.101.101','2026-10-22 22:20:13','/wp-login.php','root','toor','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (120,'101.101.101.101','2026-10-26 22:20:13','/wp-login.php','root','123456','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (121,'194.25.0.68','2026-10-25 22:20:14','/.env','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (122,'194.25.0.68','2026-10-24 22:20:14','/wp-login.php','webmaster','Blog#Sommer2024','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (123,'80.80.80.80','2026-10-23 22:20:14','/phpmyadmin/','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (124,'80.80.80.80','2026-10-22 22:20:14','/xmlrpc.php','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (125,'80.80.80.80','2026-10-26 22:20:14','/wp-admin/','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (126,'80.80.80.80','2026-10-25 22:20:14','/wp-login.php?redirect_to=%2Fwp-admin%2F&reauth=1','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (127,'80.80.80.80','2026-10-24 22:20:14','/.git/config','','','Testdaten (Vorstellung)','keine');
INSERT INTO `versuch` VALUES (128,'80.80.80.80','2026-10-23 22:20:14','/wp-login.php','admin','qwerty','Testdaten (Vorstellung)','keine');

CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `benutzername` varchar(255) NOT NULL,
  `passworthash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `benutzername` (`benutzername`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
