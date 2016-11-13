<?php

/**
 * Rule on message length and return result string for display purposes.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
class MessageLengthRule implements IRule
{
	const SHORT = 0;
	const LONG = 1;
	const CORRECT = 2;

	private $_minLength;
	private $_maxLength;

	/**
	 * Result messages.
	 *
	 * @var array An array of result messages.
	 */
	private $_msgs = NULL;

	/**
	 * Construct class with minimum and maximum lengths.
	 *
	 * @param type $min Minimum a message should be.
	 * @param type $max Maximum a message should be.
	 */
	public function __construct($min, $max)
	{
		$this->_minLength = $min;
		$this->_maxLength = $max;

		$this->_msgs = array();
		$this->_msgs[] = 'Message too short.';
		$this->_msgs[] = 'Message too long.';
		$this->_msgs[] = 'Message length just right.';
	}

	/**
	 * Excercise rule on message length.
	 *
	 * @param string $msg Message to test.
	 * @return string Result outcome string to display.
	 */
	public function excercise($msg)
	{
		$res = '';
		$len = strlen($msg);

		if($len < $this->_minLength)
		{
			$res = $this->_msgs[static::SHORT];
		}
		else if($len > $this->_maxLength)
		{
			$res = $this->_msgs[static::LONG];
		}
		else
		{
			$res = $this->_msgs[static::CORRECT];
		}

		return $res;
	}
}
