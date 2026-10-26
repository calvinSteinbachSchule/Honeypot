CREATE TABLE `angreifer` (
  `ip` varchar(45) NOT NULL,
  `land` varchar(255) DEFAULT NULL,
  `netzbetreiber` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `angreifer` VALUES ('103.158.121.21','Indonesia','CDN');
INSERT INTO `angreifer` VALUES ('45.138.12.68','Hong Kong','TC DATACENTER LIMITED');
INSERT INTO `angreifer` VALUES ('45.45.237.39','United States','Infraly, LLC');
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
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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

CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `benutzername` varchar(255) NOT NULL,
  `passworthash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `benutzername` (`benutzername`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
