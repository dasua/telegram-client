# PHP client for Telegram API
[![PHP >= 5.6](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
[![Software License][ico-license]](LICENSE.md)

> **Note:** This library is under development

Implements a simple (and non full) [Telegram bot API client](https://core.telegram.org/bots) (Bot API 3.6).

## Requirements
* php-curl

## Install
Clone or donwload this repositorie.

## How to create a Telegram bot?
All about bots: [An introduction for developers](https://core.telegram.org/bots)

If you know what is a bot and just want create one, you need the [BotFather](https://core.telegram.org/bots#6-botfather).

## Getting Started
Just need require the Telegram_autoloader Class.

``` php
<?php
require __DIR__ . '/src/Telegram_autoloader.php';
```
## Examples
File | Description
-----|------------
[examples/bot.php](examples/bot.php) | Implements a basic bot.
[examples/get_me.php](examples/get_me.php) | CLI program. Send getMe request and show the response.
[examples/set_webhook.php](examples/set_webhook.php) | CLI program. Set a webhook.
[examples/send_message.php](examples/send_message.php) | CLI program. Set a webhook.
[examples/get_webhook_info.php](examples/get_webhook_info.php) | CLI program. Get the webhook info.
[examples/delete_webhook_info.php](examples/delete_webhook_info.php) | CLI program. Delete the webhook.

## Credits
- [Jesús Guerreiro][link-author]

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-author]: https://github.com/dasua
