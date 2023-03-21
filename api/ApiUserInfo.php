<?php
/**
 * userinfo API Module.
 * Reports information about the specified user (or current user if none specified).
 * Used by Chirpy for authn/authz purposes.
 * 
 * Copyright (c) 2016 Ryan Schmidt
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 * and associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use MediaWiki\User\UserGroupManager;

// Not an entry point
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

class ApiUserInfo extends ApiBase {
	/** @var UserGroupManager */
	private $userGroupManager;

	public function __construct( ApiMain $main, $action, UserGroupManager $userGroupManager ) {
		parent::__construct( $main, $action );
		$this->userGroupManager = $userGroupManager;
	}

	public function execute() {
		$userId = $this->getMain()->getVal( 'ui_user' );
		if ($userId !== null) {
			$user = User::newFromId( $userId );
			if ( !$user->loadFromId() ) {
				$this->dieUsage(
					wfMessage( 'werewolfwiki-userinfo-invalid-user', $userId )->text(),
					'invaliduser'
			   	);
			}
		} else {
			$user = $this->getUser();
		}
		$res = array();

		$res['id'] = $user->getId();
		$res['name'] = $user->getName();
		$res['effectiveGroups'] = $this->userGroupManager->getUserEffectiveGroups( $user );

		$this->getResult()->addValue( null, $this->getModuleName(), $res );
	}

	public function getDescription() {
		return wfMessage( 'werewolfwiki-userinfo-desc' )->text();
	}

	public function getAllowedParams() {
		return array_merge( parent::getAllowedParams(), array(
			'ui_user' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => false
			)
		) );
	}

	public function getParamDescription() {
		return array_merge( parent::getAllowedParams(), array(
			'ui_user' => wfMessage('werewolfwiki-userinfo-param-user')->text()
		) );
	}

	public function getExamplesMessages() {
		return array(
			'action=myinfo' => 'werewolfwiki-userinfo-example'
		);
	}
}
