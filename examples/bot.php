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

require "../src/Telegram_autoloader.php";

/**
 * Telegram_bot Class
 *
 * This class implement a simple bot.
 *
 * If detects a message from a user (private message), send a new message to user with his id and username or firstname.
 *
 * If detects a new group member, sends a private a wellcome message to this user.
 *
 * @package Telegram Client Examples
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 */
class Telegram_bot {
	/**
	 * Telegram Client
	 * @var Telegram
	 */
	private $_client;

	/**
	 * Data revided from POST
	 * @var ARRAY
	 */
	private $_data;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->_client = new Telegram('BOT-KEY');
		$this->_data   = json_decode(file_get_contents("php://input"),TRUE);
		$this->_log    = new Telegram_logger();
		$this->_log->write(apache_request_headers(),'HEADERS');
		$this->_log->write($this->_data,'_data');
	}

	/**
	 * Execute the bot
	 *
	 * @return void
	 */
	public function run()
	{
		$chat         = empty($this->_data['message']['chat']) ? FALSE : (object)$this->_data['message']['chat'];
		$chat_id      = $chat ? (int)$chat->id : 0;
		$recived_text = $this->_data['message']['text'];
		if ($chat)
		{
			$from = empty($this->_data['message']['from']) ? FALSE : (object)$this->_data['message']['from'];
			switch ($chat->type)
			{
				case 'private':
					if (!$from->is_bot)
					{
						$options = array('parse_mode'=>'HTML');
						$options['reply_markup'] = array
							(
								'keyboard'          => array
									(
										array('Start'),
										array('Help','News','Rules'),
									),
								'one_time_keyboard' => FALSE,
								'resize_keyboard'   => TRUE,
							);
						$name = empty($from->username) ? trim("{$from->first_name} {$from->last_name}") : $from->username;
						switch ($recived_text)
						{
							case '/start':
							case 'Start':
									$message = "Hello <b>{$name}</b>. Your id is <i>{$from->id}</i>. Need help?";
								break;
							default:
									$message = "Pleased to see you again <b>{$name}</b>.\nYour message was:\n<b>{$recived_text}</b>";
								break;
						}

						$result = $this->_client->send_message($from->id,$message,$options);
						$this->_log->write($result,'result');
					}
					break;
				case 'group':
						$new_members = empty($this->_data['message']['new_chat_members']) ? FALSE : (object)$this->_data['message']['new_chat_members'];
						if ($new_members)
						{
							$bot_info = $this->_client->get_me();
							foreach($new_members as $member_info)
							{
								if (!$member_info['is_bot'])
								{
									$member_name = empty($member_info['username']) ? $member_info['first_name'] : $member_info['username'];
									$message = "Hello <a href=\"tg://user?id={$member_info['id']}\">{$member_name}</a>, need help?\nSend me private chat:\n@{$bot_info->result['username']}\n";
									$result = $this->_client->send_message($chat->id,$message,array('parse_mode'=>'HTML'));
									$this->_log->write($result,'result');
								}
							}
						}
					break;
				default:
						$this->_log->write("Type {$type} not supported",'Error');
					break;
			}
		}
	}
}

$bot = new Telegram_bot;
$bot->run();
