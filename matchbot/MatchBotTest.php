<?php

/**
 * Test file for basic MatchBot.
 *
 * @author Robert Brisita <robert.brisita@gmail.com>
 */

require_once 'rules/IRule.php';
require_once 'rules/MessageLengthRule.php';
require_once 'rules/BadWordRule.php';
require_once 'rules/SlangWordRule.php';
require_once 'judges/IJudge.php';
require_once 'judges/AbstractJudge.php';
require_once 'judges/JudgeMessage.php';
require_once 'MatchBot.php';

// For purposes of the test, we are saying that 'msg' contains a profile message.
$aspect = 'msg';

// List of bad words to look for
$badWords = array();
$badWords[] = 'fuck';
$badWords[] = 'shit';

// List of slang words to look for
$slangWords = array();
$slangWords[] = 'fo\'sho';
$slangWords[] = 'amaze-balls';

// Construct judges.
// JudgeMessage just cares about the message part of a profile.
$judge = new JudgeMessage($aspect);
$mlr = new MessageLengthRule(10, 255);
$bwr = new BadWordRule($badWords);
$swr = new SlangWordRule($slangWords);

// Add rules.
$judge->addRule($mlr);
$judge->addRule($bwr);
$judge->addRule($swr);

// Construct array of judges.
$judges = array();
$judges[] = $judge;

// Construct MatchBot.
$matchBot = new MatchBot($judges);

// Judge profile given to MatchBot instance.
$profile = array();
$profile['msg'] = 'I\'m a little teapot. Fuck. Amaze-balls. Short and stout. Here is my handle. Here is my spout.';

$res = $matchBot->judge($profile);

print_r($res);

?>
