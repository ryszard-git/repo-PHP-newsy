create database newsy character set 'utf8' collate 'utf8_polish_ci';
use newsy;

create table uzytkownicy
(
id_user int unsigned not null auto_increment primary key,
login char(25) not null,
haslo char(40) not null,
czy_admin char(3) not null,
imie char(30) not null,
nazwisko char(50) not null
);

create table wiadomosci
(
id_news int unsigned not null auto_increment primary key,
id_user int unsigned not null,
data datetime not null,
temat char(150) not null,
tresc text
);


grant select, insert, update, delete on newsy.*
to 'news'@'localhost' identified by 'skok2012';


INSERT INTO uzytkownicy (login, haslo, czy_admin, imie, nazwisko)
	VALUES ("admin", sha1("admin"), "TAK", "Admin", "Administrator");

