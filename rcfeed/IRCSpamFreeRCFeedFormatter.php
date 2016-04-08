<?php
// allows hiding specific things from the rc feed (based on configuration)
// by default, this is the review and patrol logs

class IRCSpamFreeRCFeedFormatter extends IRCColourfulRCFeedFormatter {
	/**
	 * @see RCFeedFormatter::getLine
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		global $wgWWRCFeedHideLogs, $wgWWRCFeedHideNamespaces;
		$attribs = $rc->getAttributes();
		if ( $attribs['rc_type'] == RC_LOG ) {
			$title = Title::newFromText( 'Log/' . $attribs['rc_log_type'], NS_SPECIAL );
		} else {
			$title =& $rc->getTitle();
		}

		if ( $attribs['rc_type'] == RC_LOG && in_array( $attribs['rc_log_type'], $wgWWRCFeedHideLogs ) ) {
			return null;
		} elseif ( in_array( $title->getNamespace(), $wgWWRCFeedHideNamespaces ) ) {
			return null;
		}

		// if we aren't hiding it, let the core class do all the heavy lifting
		return parent::getLine( $feed, $rc, $actionComment );
	}
}
