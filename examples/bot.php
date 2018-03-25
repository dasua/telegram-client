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
	 * @var array
	 */
	private $_request;

	/**
	 * Class constructor
	 * @throws Telegram_exception
	 */
	public function __construct()
	{
		$this->_request = new Telegram_request();
		$this->_log     = new Telegram_logger();
		if (function_exists('apache_request_headers'))
		{
			$this->_log->write(apache_request_headers(),'HEADERS');
		}

		$this->_log->write($this->_request,'_request');
		if (! $this->_request->valid_request)
		{
			$txt_error = 'Request not valid';
			$this->_log->write($txt_error,'FATAL ERROR');
			unset($this->_log);
			throw new Telegram_exception($txt_error, 1);
		}

		$this->_client  = new Telegram('BOT-KEY');
	}

	/**
	 * Execute the bot
	 * @return void
	 */
	public function run()
	{
		if (!empty($this->_request->message))
		{
			$message = $this->_request->message;
		}
		elseif (!empty($this->_request->edited_message))
		{
			$message = $this->_request->edited_message;
		}
		else
		{
			$message = new Telegram_message();
		}

		$chat    = $message->chat;
		$chat_id = $chat ? $chat->id : 0;
		if ($chat)
		{
			$from = empty($message->from) ? FALSE : $message->from;
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
						switch ($message->text)
						{
							case 'Start':
									$response_text = "Hello <b>{$name}</b>. Your id is <i>{$from->id}</i>. Need help?";
								break;
							default:
									if ($command = $message->get_command())
									{
										$response_text = $this->do_command($command);
									}
									else
									{
										$response_text = "Pleased to see you again <b>".$from->get_name()."</b>.\nYour message was:\n<b>{$message->text}</b>";
									}
								break;
						}

						$result = $this->_client->send_message($from->id,$response_text,$options);
						$this->_log->write($result,'result');
					}
					break;
				case 'group':
						$bot_info = $this->_client->get_me();
						if ($message->new_chat_members)
						{
							foreach($message->new_chat_members as $member_info)
							{
								if (!$member_info->is_bot)
								{
									$member_name = empty($member_info->username) ? $member_info->first_name : $member_info->username;
									$message     = "Hello <a href=\"tg://user?id={$member_info['id']}\">{$member_name}</a>, need help?\nSend me private chat:\n@{$bot_info->result['username']}\n";
									$result      = $this->_client->send_message($chat->id,$message,array('parse_mode'=>'HTML'));
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

	/**
	 * Execute the command
	 *
	 * @param  object $command entered
	 * @return string          text generated
	 */
	private function do_command($command)
	{
		$name = isset($this->from) ? $this->from->get_name() : '';
		switch ($command->command)
		{
			case '/start':
					$result = "Hello <b>{$name}</b>. This is your frist visit. Nice to meet you.";
				break;
			case '/md5':
					$result = md5($command->text);
				break;
			case '/sha1':
					$result = sha1($command->text);
				break;
			case '/base64':
					$result = base64_encode($command->text);
				break;
			default:
					$result = "<b>{$name}</b> typed de command: <i>{$command->command}</i>\nAnd the parameters <b>{$command->text}</b>";
				break;
		}

		return $result;
	}
}

$bot = new Telegram_bot;
$bot->run();
