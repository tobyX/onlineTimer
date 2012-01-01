<?php
/*
 * +------------------------------------------------+
 * | Copyright (c) 2012 Tobias Friebel       		|
 * +------------------------------------------------+
 * | Authors: Tobias Friebel <Entwicklung@TobyF.de>	|
 * +------------------------------------------------+
 *
 * CC Namensnennung-Keine kommerzielle Nutzung-Keine Bearbeitung
 * http://creativecommons.org/licenses/by-nc-nd/2.0/de/
 *
 * $Id$
 */

// wcf imports
require_once(WCF_DIR.'lib/data/message/bbcode/BBCodeParser.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/BBCode.class.php');
require_once(WCF_DIR.'lib/data/user/option/UserOptions.class.php');

class OnlineTimerBBCode implements BBCode
{
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser)
	{
        if ($content == "")
            $user = WCF :: getUser();
        else
            $user = new User(intval($content));

		$userOptions = new UserOptions();
        $userOptions->getOutputObject('UserOptionOutputOnlinetime');

        $option = $userOptions->getOptionValue('userOnlineTimerOnlinetime', $user);

        return $option['optionValue'];
	}
}
?>