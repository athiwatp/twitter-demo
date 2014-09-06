
create database map default charset=utf8;
use map;



drop table if exists history;
create table history (
	id			serial,
	query		nvarchar(2047),
	result		longtext,
	timestamp	timestamp
);



















--
