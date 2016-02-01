<?php
// allows hiding specific things from the rc feed (based on configuration)
// by default, this is the review and patrol logs

class IRCSpamFreeRCFeedFormatter extends IRCColourfulRCFeedFormatter {
	/**
	 * @see RCFeedFormatter::getLine
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		global $wgWWRCFeedHideLogs;
		$attribs = $rc->getAttributes();

		if ( $attribs['rc_type'] == RC_LOG && in_array( $attribs['rc_log_type'], $wgWWRCFeedHideLogs ) ) {
			return null;
		}

		// if we aren't hiding it, let the core class do all the heavy lifting
		return parent::getLine( $feed, $rc, $actionComment );
	}
}
