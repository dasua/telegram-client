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
 * Telegram_message Class
 *
 * Implements Telegram request class
 *
 * @package Telegram
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @link https://core.telegram.org/bots/api#message
  */
class Telegram_message {

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
					case 'message_id':
					case 'date':
					case 'forward_from_message_id':
					case 'forward_date':
					case 'edit_date':
					case 'migrate_to_chat_id':
					case 'migrate_from_chat_id':
						$this->$key = (int)$val;
						break;
					//Telegram_message
					case 'reply_to_message':
					case 'pinned_message':
						$this->$key = new Telegram_message($val);
						break;
					//Telegram_chat
					case 'chat':
					case 'forward_from_chat':
						$this->$key = new Telegram_chat($val);
						break;
					//Telegram_user
					case 'from':
					case 'forward_from':
					case 'left_chat_member':
						$this->$key = new Telegram_user($val);
						break;
					case 'new_chat_members':
						$this->new_chat_members = array();
						foreach ($val as $new_member)
						{
							$this->new_chat_members[] = new Telegram_user($new_member);
						}
						break;
					case 'entities':
					case 'caption_entities':
							$this->$key = array();
							foreach ($val as $new_message_entitie)
							{
								$this->$key[] = new Telegram_message_entity($new_message_entitie);
							}
						break;
					default :
						$this->$key = $val;
						break;
				}
			}

			if (!empty($this->text))
			{
				if (isset($this->entities))
				{
					foreach($this->entities as $entity)
					{
						$entity->set_text($this->text);
					}
				}

				if (isset($this->caption_entities))
				{
					foreach($this->caption_entities as $entity)
					{
						$entity->set_text($this->text);
					}
				}
			}
		}
	}

	/**
	 * Get the command and its contents.
	 * A command is a message starting with /
	 * @return mixed Object on success and FALSE if not command found.
	 */
	public function get_command()
	{
		if (isset($this->entities) && $this->entities[0]->type === 'bot_command')
		{
			$result = new StdClass;
			$result->command = $this->entities[0]->text;
			$result->text = trim(substr_replace($this->text,'',$this->entities[0]->offset,$this->entities[0]->length));
		}
		else
		{
			$result = FALSE;
		}

		return $result;
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
			case 'reply_to_message':
			case 'pinned_message':
				$result = new Telegram_message();
				break;
			//Telegram_chat
			case 'chat':
			case 'forward_from_chat':
				$result = new Telegram_chat();
				break;
			//Telegram_user
			case 'from':
			case 'forward_from':
			case 'left_chat_member':
				$result = new Telegram_user();
				break;
			//Empty arrays
			case 'new_chat_members':
				$result = array();
				break;
			case 'entities':
			case 'caption_entities':
				$result = array();
				break;
			default:
				$result = NULL;
				break;
		}

		return $result;
	}
}
