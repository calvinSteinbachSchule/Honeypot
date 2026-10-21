create table angreifer
(
  ip varchar(45) not null primary key,
  land varchar(255) null,
  netzbetreiber varchar(255) null

) default charset = utf8mb4;


create table versuch
(
  id int unsigned not null auto_increment primary key,
  angreifer varchar(45) not null,
  zeitpunkt datetime not null default current_timestamp,
  pfad varchar(255) not null,
  benutzername varchar(255),
  passwort varchar(255),
  useragent varchar(255),
  angriffsmuster varchar(255),
  constraint fk_angreifer foreign key(angreifer) references angreifer(ip)
) default charset = utf8mb4;

create table admin
(
  id int unsigned not null auto_increment primary key,
  benutzername varchar(255) not null unique,
  passworthash varchar(255) not null
) default charset = utf8mb4;

