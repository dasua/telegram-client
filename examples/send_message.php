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
 * @link
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
	 */
	public function __construct($key)
	{
		$this->_client = new Telegram($key);
	}

	/**
	 * Send sendmessage
	 *
	 * @url https://core.telegram.org/bots/api#sendmessage
	 * @return void
	 */
	public function run()
	{
		$result = $this->_client->send_message(00000000,'This a simple <i>test</i> at '.date('Y/m/d H:i:s'),array('parse_mode'=>'HTML'));
		var_export($result);
	}
}

$client = new Telegram_client('BOT-KEY');
$result = $client->run();
