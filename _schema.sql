create database map default charset=utf8;
use map;

drop table if exists history;
create table history (
	id			serial,
	query		nvarchar(255) unique,
	result		longtext,
	timestamp	timestamp
);

















--
