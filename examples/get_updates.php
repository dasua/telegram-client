#!/usr/bin/php
<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 Jesús Guerreiro Real de Asua
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Telegram Client Examples
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @copyright Copyright (c) 2018, Jesús Guerreiro Real de Asua
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link https://github.com/dasua/telegram-client
 * @filesource
 */

require '../src/Telegram_autoloader.php';

/**
 * Telegram_client Class
 *
 * Implements a client to interact with Telegram
 *
 * @package Telegram Client Examples
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 */
class Telegram_client {
	/**
	 * Telegram Client
	 * @var Telegram
	 */
	private $_client;

	/**
	 * Class constructor
	 * @param string $key bot's private key
	 */
	public function __construct($key)
	{
		$this->_client = new Telegram($key);
	}
	/**
	 * Send getwebhookinfo
	 * @url https://core.telegram.org/bots/api#getupdates
	 * @return void
	 */
	public function run()
	{
		$response = $this->_client->get_updates();
		if ($response->ok !== TRUE)
		{
			echo PHP_EOL."Error detected: {$response->error_code} - {$response->description}".PHP_EOL;
			exit(1);
		}

		$result = $response->result;
		foreach ($result as $num_update => $new_update)
		{
			if (isset($new_update->message))
			{
				$message = $new_update->message;
			}
			elseif(isset($new_update->edited_message))
			{
				$message = $new_update->edited_message;
			}
			elseif(isset($new_update->channel_post))
			{
				$message = $new_update->channel_post;
			}
			elseif(isset($new_update->edited_channel_post))
			{
				$message = $new_update->edited_channel_post;
			}

			echo sprintf("Update: %s\n\tUpdate ID: %s\n\tMessage ID: %s\n\tMessage date: %s\n\tMessage text: %s\n",++$num_update,$new_update->update_id,$message->message_id,date('Y-m-d H:i:s',$message->date),$message->text);
		}
	}
}

$client = new Telegram_client('BOT-KEY');
$client->run();
