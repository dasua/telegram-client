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
 * Telegram_update Class
 *
 * Implements Telegram update class
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @link https://core.telegram.org/bots/api#update
  */
class Telegram_update {

	/**
	 * Class constructor
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
					case 'update_id':
						$this->$key = (int)$val;
						break;
					//Telegram_message
					case 'message':
					case 'edited_message':
					case 'channel_post':
					case 'edited_channel_post':
						$this->$key = new Telegram_message($val);
						break;
					default :
					// TODO: inline_query, chosen_inline_result, callback_query, shipping_query, pre_checkout_query
						$this->$key = $val;
						break;
				}
			}
		}
	}
}
