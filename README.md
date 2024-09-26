# 46Elks notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/46Elks.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/46Elks)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/46Elks/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/46Elks)


[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/46Elks.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/46Elks)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/46Elks/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/46Elks/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/46Elks.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/46Elks)

This package makes it easy to send notifications using [46Elks](https://www.46elks.com) with Laravel.




46Elks has a whole bunch of phone oriented services. This package takes care of:
* SMS

With more endpoints to come. Feel free to contribute.  



## Contents

- [46Elks notification channel for Laravel](#46elks-notification-channel-for-laravel)
  - [Contents](#contents)
  - [Installation](#installation)
    - [Setting up the 46Elks service](#setting-up-the-46elks-service)
  - [Usage](#usage)
    - [Available mediums](#available-mediums)
      - [SMS](#sms)
    - [Available Message methods for sms](#available-message-methods-for-sms)
      - [MMS](#mms)
    - [Available Message methods](#available-message-methods)
    - [Error handling](#error-handling)
  - [Changelog](#changelog)
  - [Testing](#testing)
  - [Security](#security)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)


## Installation

```
composer require laravel-notification-channels/46elks
```

### Setting up the 46Elks service


add the following to your config/services.php
``` php
	'46elks' => [
		'username' => env('FORTY_SIX_ELKS_USERNAME'),
		'password' => env('FORTY_SIX_ELKS_PASSWORD'),
	],
```
	
Also remember to update your .env with correct information:
```
FORTY_SIX_ELKS_USERNAME=
FORTY_SIX_ELKS_PASSWORD=
```
You will find your username and password at https://46elks.se/account

## Usage


To use this channel simply create a notification that has the following content:
``` php
use NotificationChannels\FortySixElks\FortySixElksChannel;
use NotificationChannels\FortySixElks\FortySixElksSMS;

public function via($notifiable)
{
    return [FortySixElksChannel::class];
}


public function to46Elks($notifiable)
{
    return (new FortySixElksSMS())
        ->line('Testsms')
        ->line('Olle')
        ->to('+46701234567')
        ->from('Emil')
        // ->flash() - Optional
        // ->whenDelivered(URL) - Optional
        // ->dontLog() - Optional
        // ->dry() - Optional
}
```

Another example without the notification implementation.
``` php
use NotificationChannels\FortySixElks\FortySixElksSMS;

(new FortySixElksSMS())
    ->line('Testsms')
    ->line('Olle')
    ->to('+46701234567')
    ->from('Emil')
    // ->flash() - Optional
    // ->whenDelivered(URL) - Optional
    // ->dontLog() - Optional
    // ->dry() - Optional
    ->send();
```
### Available mediums
#### SMS
The FortySixElksSMS have the following methods, all chainable.
### Available Message methods for sms


``from($mixed)`` Accepts a string containing A-Z, a-z, 0-9 up to 11 characters or numbers. Space is not supported. Sms will be sent with that name.

``to($number)`` International phone number.

``line($string)`` Every string in a line will be on its own row.

``flash()`` Will set the message type to flash. Will not endup in sms inbox. See [This tweet](https://twitter.com/46elks/status/583183559420178432) to find out how it looks on an iphone.

``dry()`` Enable when you want to verify your API request without actually sending an SMS to a mobile phone.
            No SMS message will be sent when this is enabled. To be able inspect a dry() request you need to
            send your message to +4670000000 then you can inspect it at [https://46elks.com/logs](https://46elks.com/logs)

``whenDelivered('http://localhost.se/ping')`` This webhook URL will receive a POST request every time the delivery status changes. 

``dontLog()`` Enable to avoid storing the message text in your history.
               The other parameters will still be stored. 

#### MMS
To use MMS simply use `new FortySixElksMMS()` instead of `new FortySixElksSMS()`

The FortySixElksMMS have the following methods, all chainable.

### Available Message methods


``from($mixed)``. Accepts 'noreply' as a string or a MMS activated number

``to($number)``. International phone number.

``line($string)``. Every string in a line will be on its own row.

``image()``. URL to the image to send in mms.
 

### Error handling
> How to handle notification send errors

If for any reason there would be an error when sending a notification it will fire a 
`Illuminate\Notifications\Events\NotificationFailed` event. You can then listen for that.

Example:
``` php
Event::listen(NotificationFailed::class, function($event){
    info('Error while sending sms');
});
```
And the event has `$event->notifiable`, `$event->notification`, `$event->channel` and `$event->data`(where you have the exception at `$event->data['exception']`)


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email emil@dalnix.se instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Emil Österlund](https://github.com/larsemil)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
