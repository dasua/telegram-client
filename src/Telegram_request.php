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
 * Telegram_request Class
 *
 * Implements Telegram request class
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 */
class Telegram_request {

	/**
	 * Data received in JSON format
	 * @var string
	 */
	private $_json_data;

	/**
	 * Indicates whether the received request is in correct JSON format
	 * @var boolean
	 */
	var $valid_request;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->_json_data    = file_get_contents("php://input");
		$data                = json_decode($this->_json_data,TRUE);
		$this->valid_request = (json_last_error() === JSON_ERROR_NONE) && !empty($data['update_id']);
		if ($this->valid_request)
		{
			foreach ($data as $key => $val)
			{
				switch ($key)
				{
					case 'update_id':
						$this->$key = (int)$val;
						break;
					case 'message' :
					case 'edited_message' :
					case 'channel_post' :
					case 'edited_channel_post' :
						$this->$key = new Telegram_message($val);
						break;
					default:
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
}
