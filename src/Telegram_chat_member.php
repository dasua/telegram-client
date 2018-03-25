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
 * @package Telegram Types
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @copyright Copyright (c) 2018, Jesús Guerreiro Real de Asua
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link https://github.com/dasua/telegram-client
 * @filesource
 */

/**
 * Telegram_chat_member Class
 *
 * Implements Telegram chat member class
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @link https://core.telegram.org/bots/api#chatmember
  */
class Telegram_chat_member {

	/**
	 * Class constructor
	 *
	 * @param array $data
	 */
	public function __construct($data = array())
	{
		if (is_array($data))
		{
			foreach($data as $key => $val)
			{
				switch ($key)
				{
					//Integers
					case 'until_date':
						$this->$key = (int)$val;
						break;
					//Boolean
					case 'can_be_edited':
					case 'can_change_info':
					case 'can_post_messages':
					case 'can_edit_messages':
					case 'can_delete_messages':
					case 'can_invite_users':
					case 'can_restrict_members':
					case 'can_pin_messages':
					case 'can_promote_members':
					case 'can_send_messages':
					case 'can_send_media_messages':
					case 'can_send_other_messages':
					case 'can_add_web_page_previews':
						$this->$key = (bool)$val;
						break;
					//Telegram_user
					case 'user':
						$this->$key = new Telegram_user($val);
						break;
					default :
						$this->$key = $val;
						break;
				}
			}
		}
	}

	/**
	 * Getter
	 * @param  string $name
	 * @return NULL
	 */
	public function __get($name)
	{
		return NULL;
	}

	public function get_name()
	{
		return empty($this->username) ? trim("{$this->first_name}") : $this->username;
	}
}
