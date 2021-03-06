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
 * Telegram_chat Class
 *
 * Implements Telegram chat class
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @link https://core.telegram.org/bots/api#chat
  */
class Telegram_chat {

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
					case 'id':
						$this->$key = (int)$val;
						break;
					//Boolean
					case 'reply_to_message':
					case 'can_set_sticker_set':
						$this->$key = bool($val);
						break;
					case 'pinned_message':
						$this->$key = new Telegram_message($val);
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
	 * @return mixed
	 */
	public function __get($name)
	{
		switch ($name)
		{
			//Telegram_message
			case 'pinned_message':
				$result = new Telegram_message();
				break;
			default:
				$result = NULL;
				break;
		}

		return $result;
	}
}
