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
 * @package Telegram Logger
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 * @copyright Copyright (c) 2018, Jesús Guerreiro Real de Asua
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link
 * @filesource
 */

/**
 * Telegram_logger Class
 *
 * Implements Telegram logger class
 *
 * @package Telegram Logger
 * @author Jesús Guerreiro Real de Asua <jesus@jesusguerreiro.es>
 */
class Telegram_logger {

	private $_path;

	/**
	 * Log's filename
	 * @var string
	 */
	private $_filename = '';

	/**
	 * Logs file descriptor
	 * @var resource
	 */
	private $_log_file;

	/**
	 * Indicates whether to generate log or not
	 * @var boolean
	 */
	private $_enable = TRUE;

	/**
	 * Class constructor
	 */
	public function __construct($options = array())
	{
		if (isset($options['path']) && is_dir($options['path']) && is_writable($options['path']))
		{
			$this->_path = $options['path'];
		}
		else
		{
			$this->_path = dirname(__FILE__).'/logs';
		}

		if (isset($options['enable']))
		{
			$this->_enable = (bool)$options['enable'];
		}

		if ($this->_enable)
		{
			$this->_filename = realpath($this->_path).'/';
			if ( ! is_writable($this->_filename))
			{
				throw new Telegram_exception("Path not writeable: {$this->_filename}", 1);
			}

			$this->_filename .= uniqid(date('Ymd-His-')).'.log';
			$this->_log_file = @fopen($this->_filename,'a+');
			if (empty($this->_log_file))
			{
				throw new Telegram_exception("Can't create Logger file: {$this->_filename}", 2);
			}
		}
	}

	/**
	 * Class desconstructor
	 */
	public function __destruct()
	{
		if ($this->_enable)
		{
			@fclose($this->_fd);
		}
	}

	/**
	 * Writes data and title into log
	 *
	 * @param  mixed $data data to save in the log
	 * @param  string $title title associated with the data
	 * @return void
	 */
	public function write($data,$title='')
	{
		if ($this->_enable)
		{
			if (!is_string($data))
			{
				$data = var_export($data,TRUE);
			}

			$data = date('Y-m-d H:i:s - ').($title ? "{$title}:\n{$data}\n" : "{$data}\n");
			fwrite($this->_log_file,$data);
		}
	}
}
