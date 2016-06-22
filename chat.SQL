CREATE DATABASE IF NOT EXISTS OpenChat;

CREATE TABLE IF NOT EXISTS total_message (
identifier varchar(255) unique not null,
total_messages int not null,
user1 varchar(255) not null,
user2 varchar(255) not null,
unread int not null,
time varchar(30) not null
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS messages (
identifier_message_number varchar(255) not null,
message text not null,
sent_by varchar(255) not null,
time varchar(30) not null,
id int auto_increment primary key not null
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS register (
id int primary key auto_increment unique not null,
email varchar(255) unique not null,
username varchar(255) unique not null,
password varchar(255) not null
) ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS login (
login_id int primary key not null unique,
name varchar(255) not null,
email varchar(255) unique not null,
username varchar(255) unique not null,
mobile varchar(255) not null,
FOREIGN KEY (login_id) REFERENCES register(id)
) ENGINE=INNODB;