<?php

/**
 * Rule on bad words to check if given message contains any.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
class BadWordRule implements IRule
{
	private $_badWords = NULL;

	public function __construct(array $badWords)
	{
		$this->_badWords = $badWords;
	}

	public function excercise($msg)
	{
		foreach ($this->_badWords as $badWord)
		{
			if(stripos($msg, $badWord))
			{
				return 'No bad words allowed.';
			}
		}

		return 'Message clean of bad words.';
	}
}
