# PHP - Account

This library prepares the data and send it to an API for manage account

## Installation

Use the composer to install this library

```bash
composer require epicsweb/php-account
```

## Configuration

#### CodeIgniter

Create or edit a file in your code igniter application folder and set this vars: **/application/config/config.php**

```php
<?php if( !defined('BASEPATH')) exit('No direct script access allowed');

$config['api_epics'] = array(
	'server' 		=> 'https://api.url.com',
	'http_user' 	=> 'Http_User',
	'http_pass' 	=> 'Http_Pass',
);
```

#### Laravel

Set in your **.env** file

```
AE_URL=YOUR_BASE_URL_API
AE_USER=YOUR_PWD_USERS
AE_PASS=YOUR_PWD_PASSWORD
```

## Usage

#### CodeIgniter

Change file **/application/config/config.php**:

```php
$config['composer_autoload'] = FALSE;
↓
$config['composer_autoload'] = realpath(APPPATH . '../vendor/autoload.php');
```

#### CodeIgniter & Laravel

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

### License
This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/epicsweb/mensagens-php/blob/master/LICENSE) file for details