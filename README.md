# Yii2 async notifications

## Overview

Purpose of this package is to build easy to use asyncronious
notification system in yii2 project.

Package allowes to send e-mail, and push notifications to apple and 
google play cloud.

For queue management [Rabbit MQ](https://www.rabbitmq.com/) is used.

Package is pretty raw and has a lot of rooms for improvement.

We are open to new pull requests, suggestions.

## Install

The preferred way to install this extension is through composer.

Either run

```
php composer.phar require --prefer-dist the-real-start/yii2-async-notifications "*"
```

or add

```
"the-real-start/yii2-async-notifications": "*"
```

to the require section of your composer.json file.

## Configuration

In `common/config/main-local.php` add `amqp` component:

```
...
        'amqp'          => [
            'class'        => TRS\AsyncNotification\components\amqp\Amqp::className(),
            'host'         => '127.0.0.1',
            'port'         => 5672,
            'user'         => 'guest',
            'password'     => 'guest',
            'vhost'        => '/',
            'exchange'     => 'my-exchange-name',
            'exchangeType' => \TRS\AsyncNotification\components\amqp\Amqp::TYPE_DIRECT,
            'exchangeArgs' => []
        ],
...
```

Change parameters depending on your local environment.

This component set's up connection with rabbitmq.

Then setup notifications you'd like to proceed.

For instance mail:

```
...
        'mailer'        => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
...
```

Work in progress
