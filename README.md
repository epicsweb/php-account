# PHP - Account

This library prepares the data and send it to an API for manage account

## Installation

Use the composer to install this library

```bash
composer require epicsweb/php-account
```

## Configuration

### CodeIgniter

##### Account

Create or edit a file in your code igniter application folder and set this vars: **/application/config/config.php**

```php
<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

$config['api_epics'] = array(
	'server' 		=> 'https://api.url.com',
	'http_user' 	=> 'Http_User',
	'http_pass' 	=> 'Http_Pass',
);
```
##### Tracker

Create a file in your code igniter application folder and set this vars: **/application/config/epicsweb.php**

```php
<?php if( !defined('BASEPATH')) exit('No direct script access allowed');
$config['tracker']	= [
	'server'			=> 'http://tracker.epics/',
	'companies_tokens'	=> '3a532acb04cc795c97c518e327a278a9'
];
```


### Laravel

Set in your **.env** file

##### Account

```
AE_URL=YOUR_BASE_URL_API
AE_USER=YOUR_PWD_USERS
AE_PASS=YOUR_PWD_PASSWORD
```
##### Tracker

```
AET_URL=YOUR_BASE_URL_API
AET_TOKENR=YOUR_COMPANIE_TOKEN
```

## Usage

### CodeIgniter

Change file **/application/config/config.php**:

```php
$config['composer_autoload'] = FALSE;
↓
$config['composer_autoload'] = realpath(APPPATH . '../vendor/autoload.php');
```

#### Account

Call the "account_create or account_login" function of this library with an array like unique param

```php

$account = new PhpAccount( 'laravel' );			// 'laravel' framework params
$account = new Epicsweb\PhpAccount( 'ci' );		// 'ci' framework params (default)

$create = [
	'email'			=> 'user@account.com',
    'password'		=> 'password_encrypted', //SEND PASSWORD (ENCRYPTED) OR IDFACEBOOK
    'idFacebook'	=> '001122334455' // **
    'name'			=> 'User Name',
];
$account->account_create( $create );

$login = [
	'email'			=> 'user@account.com',
    'password'		=> 'password_encrypted', //SEND PASSWORD(ENCRYPTED) OR IDFACEBOOK
    'idFacebook'	=> '001122334455' // **
];
$account->account_login( $login )
```

#### Tracker

```php
$tracker = new PhpAccount( 'laravel' );			// 'laravel' framework params
$tracker = new Epicsweb\PhpTracker( 'ci' );     // 'ci' framework params (default)
$insert = [
	'application_id'	=> 0, 	// int (11) 		req, your application internal id
	'users_id'			=> 0,	// int (11) 		req you account user id
	'identifier_id'		=> 0,	// int (11) 		opt, contract id, user id, your control
	'resgiter_id'		=> 0,	// int (11) 		opt, page id, item id, your control
	'tag'				=> '',	// string (25) 		opt, control your tags
	'social_media_id'	=> 0,	// int (04)			opt, id for you control your social media actions
	'companies_token'	=> '',	// string(32)	 	opt, can be used in config
];
$tracker->insert( $insert );
```

### License
This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/epicsweb/mensagens-php/blob/master/LICENSE) file for details