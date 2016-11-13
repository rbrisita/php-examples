<?php

/**
 * Rule on slang to check if given message contains any.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */
class SlangWordRule implements IRule
{
	private $_slangWords = NULL;

	public function __construct(array $slangWords)
	{
		$this->_slangWords = $slangWords;
	}

	public function excercise($msg)
	{
		foreach ($this->_slangWords as $slangWord)
		{
			if(stripos($msg, $slangWord))
			{
				return 'No slang words allowed.';
			}
		}

		return 'Message clean of slang words.';
	}
}
