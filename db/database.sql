create database if not exists blogwriter character set utf8 collate utf8_unicode_ci;
use blogwriter;

grant all privileges on blogwriter.* to 'blogwriter_user'@'localhost' identified by 'secret';
