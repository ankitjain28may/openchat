

# OpenChat

[![Join the chat at https://gitter.im/ankitjain28may/openchat](https://badges.gitter.im/ankitjain28may/openchat.svg)](https://gitter.im/ankitjain28may/openchat?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ankitjain28may/openchat/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ankitjain28may/openchat/?branch=master)
[![Build Status](https://travis-ci.org/ankitjain28may/openchat.svg?branch=master)](https://travis-ci.org/ankitjain28may/openchat)

[![Code Climate](https://codeclimate.com/github/ankitjain28may/openchat/badges/gpa.svg)](https://codeclimate.com/github/ankitjain28may/openchat)
[![Issue Count](https://codeclimate.com/github/ankitjain28may/openchat/badges/issue_count.svg)](https://codeclimate.com/github/ankitjain28may/openchat)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/23e0d72e208d4edfb08702b702bd9139)](https://www.codacy.com/app/ankitjain28may77/openchat?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ankitjain28may/openchat&amp;utm_campaign=Badge_Grade)


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

5. To start the websocket server type

	```php
	php cmd.php
	```

6. Open a new terminal window and type

	```js
	gulp
	```

That's it, now start development at [http://localhost:8888](http://localhost:8888) in your browser

## Contribution guidelines

If you are interested in contributing to OpenChat, Open Issues and send PR.
> Feel free to code and contribute
