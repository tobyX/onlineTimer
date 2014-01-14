<?php

namespace wcf\system\bbcode;

use wcf\system\WCF;
use wcf\data\user\UserProfile;

/**
 * Provides BBCode for user online timer
 *
 * @author Tobias Friebel <woltlab@tobyf.de>
 * @copyright 2014 Tobias Friebel
 * @license Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International
 *          <http://creativecommons.org/licenses/by-nc-nd/4.0/deed.en>
 * @package com.toby.wcf.onlinetimer
 */
class OnlineTimerBBCode extends AbstractBBCode
{
	/**
	 * @see	\wcf\system\bbcode\IBBCode::getParsedTag()
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser)
	{
		if ($content == "")
			$userID = WCF :: getUser()->userID;
		else
			$userID = intval($content);

		$user = UserProfile :: getUserProfile($userID);

		$userOption = $user->getFormattedUserOption('UserOptionOutputOnlinetime');

		return $userOption;
	}
}
