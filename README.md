

# OpenChat

[![Join the chat at https://gitter.im/ankitjain28may/openchat](https://badges.gitter.im/ankitjain28may/openchat.svg)](https://gitter.im/ankitjain28may/openchat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ankitjain28may/openchat/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ankitjain28may/openchat/?branch=master)
[![Build Status](https://travis-ci.org/ankitjain28may/openchat.svg?branch=master)](https://travis-ci.org/ankitjain28may/openchat)
[![Coverage Status](https://coveralls.io/repos/github/ankitjain28may/openchat/badge.svg?branch=master)](https://coveralls.io/github/ankitjain28may/openchat?branch=master)
[![Code Climate](https://codeclimate.com/github/ankitjain28may/openchat/badges/gpa.svg)](https://codeclimate.com/github/ankitjain28may/openchat)
[![Issue Count](https://codeclimate.com/github/ankitjain28may/openchat/badges/issue_count.svg)](https://codeclimate.com/github/ankitjain28may/openchat)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/23e0d72e208d4edfb08702b702bd9139)](https://www.codacy.com/app/ankitjain28may77/openchat?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ankitjain28may/openchat&amp;utm_campaign=Badge_Grade)
[![GitPitch](https://gitpitch.com/assets/badge.svg)](https://gitpitch.com/ankitjain28may/openchat/master?grs=github&t=moon)


> OpenChat is an Open Source messaging platform where people can send messages to anyone registered to this platform.

## How to Setup

Setting up OpenChat on your local machine is really easy.
Follow this guide to setup your development machine.

### Requirements :

1. PHP > 5.6
2. MySQL
3. Composer
4. npm
5. git
6. Supervisor


### Installation :

1. Get the source code on your machine via git.

	```shell
    git clone https://github.com/ankitjain28may/openchat.git
    ```

2. Install php and js dependencies

	```shell
	cd openchat
	composer install
	npm install
	```

3. Rename file `.env.example` to `.env` and change credentials.


4. Create an empty sql database and run import database.

	```mysql
	create database openchat;
	mysql -u[user] -p[password] [database name] < path\openchat\sql\openchat.sql
	```

5. open .env file and set `APP_URL` to your `external_ip` address at port 8888

6. Open a `message.js` file stored at `public\assests\js` and set external_ip

	```js
	var conn = new WebSocket("ws://external_ip:8080");
	```

7. Open `Supervisor\devserver.conf` and set `external_ip` address at port 8888

8. Add the conf files of the Supervisor folder to Supervisor by running following commands in terminal

	```shell
	sudo supervisorctl reread
	sudo supervisorctl update
	sudo supervisorctl start devserver
	sudo supervisorctl start server
	```

That's it, now start development at [http://external_ip:8888](http://external_ip:8888) in your browser

## Contribution guidelines

If you are interested in contributing to OpenChat, Open Issues and send PR.
> Feel free to code and contribute
