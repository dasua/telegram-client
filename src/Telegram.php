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
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @copyright Copyright (c) 2018, Jesús Guerreiro Real de Asua
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link https://github.com/dasua/telegram-client
 * @filesource
 */

/**
 * Telegram Class
 *
 * This class allows you to interact with the Telegram API.
 *
 * It does not implement all the functionality of the API but only a part.
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @link https://core.telegram.org/bots/api
 */
class Telegram {
	/**
	 * URL Telegram's bot API
	 * @var string
	 */
	private $_url = 'https://api.telegram.org/bot';

	/**
	 * Bot's token
	 * @var string
	 */
	private $_token    = '';

	/**
	 * Request endpoint
	 * @var string
	 */
	private $_endpoit = '';

	/**
	 * Fileds to be sent in the request
	 * @var array
	 */
	private $_fields   = array();

	/**
	 * Request response
	 * @var mixed
	 */
	private $_response = NULL;

	/**
	 * Class constructor
	 * @param string $token bot's private key
	 */
	public function __construct($token = '')
	{
		if (!empty($token))
		{
			$this->_token = $token;
		}
	}

	/**
	 * Getter class
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'response':
				$resultado = $this->_response;
				break;
			case 'token':
				$resultado = $this->_token;
				break;
			default:
				$resultado = NULL;
				break;
		}

		return $resultado;
	}

	/**
	 * Setter class
	 */
	public function __set($name,$value)
	{
		switch ($name)
		{
			case 'token':
				$this->_token = is_string($value) ? $value : '';
				break;
		}
	}

	/**
	 * Send a new request.
	 * @throws Telegram_bot_exception on error
	 * @return Object with the response from API
	 */
	private function _send_request()
	{
		$fields = empty($this->_fields) ? '' : '?'.http_build_query($this->_fields);
		$url    = $this->_url.$this->_token.'/'.$this->_endpoit.$fields;
		$ch     = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		if ($result === FALSE)
		{
			$txt_error = curl_error($ch);
			$cod_error = curl_errno($ch);
		}
		else
		{
			$txt_error = '';
		}

		curl_close($ch);
		if ($txt_error)
		{
			throw new Telegram_bot_exception($txt_error, $cod_error);
		}

		return (object)json_decode($result,TRUE);
	}

	/**
	 * Use this method to send text messages.
	 * @url https://core.telegram.org/bots/api#sendmessage
	 * @return Object with the response from API
	 */
	public function send_message(string $chat_id, string $text, $options = array())
	{
		$this->_endpoit = 'sendMessage';
		$this->_fields  = array('chat_id' => $chat_id,'text' => $text);
		foreach($options as $key => $val)
		{
			switch ($key)
			{
				case 'parse_mode':
				case 'disable_web_page_preview':
				case 'disable_notification':
				case 'reply_to_message_id':
					$field = $val;
					break;
				case 'reply_markup':
					$field = json_encode($val);
					break;
			}

			$this->_fields[$key] = $field;
		}

		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$this->_response->result = new Telegram_message($this->_response->result);
		}

		return $this->_response;
	}

	/**
	 * Use this method to receive incoming updates using long polling
	 * @url https://core.telegram.org/bots/api#getupdates
	 * @return Object with the response from API
	 */
	public function get_updates()
	{
		$this->_endpoit  = 'getUpdates';
		$this->_fields   = array();
		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$result = array();
			foreach ($this->_response->result as $new_message)
			{
				$result[] = new Telegram_update($new_message);
			}

			if (!empty($result))
			{
				$this->_response->result = $result;
			}
		}

		return $this->_response;
	}

	/**
	 * A simple method for testing your bot's auth token.
	 * @url https://core.telegram.org/bots/api#getme
	 * @return Object with the response from API
	 */
	public function get_me()
	{
		$this->_endpoit  = 'getMes';
		$this->_fields   = array();
		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$this->_response->result = new Telegram_user($this->_response->result);
		}
		return $this->_response;
	}

	/**
	 * Use this method to get the number of members in a chat.
	 * @url https://core.telegram.org/bots/api#getchatmemberscount
	 * @return Object with the response from API
	 */
	public function get_chat_members_count(string $chat_id)
	{
		$this->_endpoit  = 'getChatMembersCount';
		$this->_fields   = array('chat_id'=>$chat_id);
		$this->_response = $this->_send_request();
		return $this->_response;
	}

	/**
	 * Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.)
	 * @url https://core.telegram.org/bots/api#getchat
	 * @return Object with the response from API
	 */
	public function get_chat(string $chat_id)
	{
		$this->_endpoit  = 'getChat';
		$this->_fields   = array('chat_id'=>$chat_id);
		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$this->_response->result = new Telegram_chat($this->_response->result);
		}
		return $this->_response;
	}

	/**
	 * Use this method to get a list of administrators in a chat.
	 * @url https://core.telegram.org/bots/api#getchatadministrators
	 * @return Object with the response from API
	 */
	public function get_chat_administrators(string $chat_id)
	{
		$this->_endpoit  = 'getChatAdministrators';
		$this->_fields   = array('chat_id' => $chat_id);
		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$result = array();
			foreach ($this->_response->result as $new_admin)
			{
				$result[] = new Telegram_chat_member($new_admin);
			}

			if(!empty($result))
			{
				$this->_response->result = $result;
			}
		}

		return $this->_response;
	}

	/**
	 * Use this method to specify a url and receive incoming updates via an outgoing webhook.
	 * @url https://core.telegram.org/bots/api#setwebhook
	 * @return Object with the response from API
	 */
	public function set_webhook(string $url)
	{
		$this->_endpoit   = 'setWebhook';
		$this->_fields   = array('url' => $url);
		$this->_response = $this->_send_request();
		return $this->_response;
	}

	/**
	 * Use this method to remove webhook integration if you decide to switch back to getUpdates.
	 * @url https://core.telegram.org/bots/api#deletewebhook
	 * @return Object with the response from API
	 */
	public function delete_webhook()
	{
		$this->_endpoit  = 'deleteWebhook';
		$this->_fields   = array();
		$this->_response = $this->_send_request();
		return $this->_response;
	}

	/**
	 * Use this method to get current webhook status.
	 * @url https://core.telegram.org/bots/api#getwebhookinfo
	 * @return Object with the response from API
	 */
	public function get_webhook_info()
	{
		$this->_endpoit  = 'getWebhookInfo';
		$this->_fields   = array();
		$this->_response = $this->_send_request();
		if ($this->_response->ok)
		{
			$this->_response->result = new Telegram_webhook_info($this->_response->result);
		}

		return $this->_response;
	}
}
