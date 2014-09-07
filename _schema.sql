create database map default charset=utf8;
create user 'map'@'localhost' identified by 'password';
grant all on map.* to 'map'@'localhost';

use map;

drop table if exists history;
create table history (
	id			serial,
	query		nvarchar(255) unique,
	result		longtext,
	timestamp	timestamp
);

















--
