<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009-2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../system/initialize.php');


class PwRecovery extends Backend
{
	
	public function __construct()
	{
		parent::__construct();
	}
	

	/**
	 * Run controller and parse the login template
	 */
	public function run()
	{
		$this->loadLanguageFile('default');
		$this->loadLanguageFile('tl_user');
		
		if (strlen($this->Input->post('username')) && strlen($this->Input->post('email')))
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE username=? AND email=?")
									  ->limit(1)
									  ->execute($this->Input->post('username'), $this->Input->post('email'));
			
			if ($objUser->numRows)
			{
				$strPassword = $this->generatePassword();
				
				$this->Database->prepare("UPDATE tl_user SET loginCount=3, locked=0, password=? WHERE id=?")->execute(sha1($strPassword), $objUser->id);
				
				// Send mail
				$objEmail = new Email();
				$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
				$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoverySubject'], $this->Environment->base);
				$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoveryMessage'], $this->Environment->base, $objUser->username, $strPassword);
				$objEmail->sendTo($objUser->email);
				
				$this->log('Password for user '.$objUser->username.' has been resetted.', 'PwRecovery run()', TL_GENERAL);
				
				$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess'];
				$this->redirect('contao/index.php');
			}
			else
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed'];
			}
			
			$this->reload();
		}
		
		$this->Template = new BackendTemplate('be_pwrecovery');

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = $this->getMessages();
		$this->Template->base = $this->Environment->base;
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->isMac = preg_match('/mac/i', $this->Environment->httpUserAgent);
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pwrecovery'];
		$this->Template->curUsername = $this->Input->post('username') ? $this->Input->post('username') : $this->Input->get('username');
		$this->Template->curEmail = $this->Input->post('email') ? $this->Input->post('email') : '';
		$this->Template->uClass = ($_POST && !$this->Input->post('username')) ? ' class="login_error"' : '';
		$this->Template->eClass = ($_POST && !$this->Input->post('email')) ? ' class="login_error"' : '';
		$this->Template->recoverButton = specialchars($GLOBALS['TL_LANG']['MSC']['recoverBT']);
		$this->Template->username = $GLOBALS['TL_LANG']['tl_user']['username'][0];
		$this->Template->email = $GLOBALS['TL_LANG']['tl_user']['email'][0];
		$this->Template->beLogin = $GLOBALS['TL_LANG']['MSC']['beLogin'];

		$this->Template->output();
	}
	
	
	private function generatePassword()
	{
		$strPassword = '';
		$strChars = "0123456789abcdfghjkmnpqrstuvwxyz"; 
		$i = 0; 
		
		// add random characters to $password until $length is reached
		while ($i < 8)
		{
			// pick a random character from the possible ones
			$char = substr($strChars, mt_rand(0, strlen($strChars)-1), 1);
		
			// we don't want this character if it's already in the password
			if (!strstr($strPassword, $char))
			{
				$strPassword .= $char;
				$i++;
			}
		}
		
		return $strPassword;
	}
}


/**
 * Instantiate controller
 */
$objPwRecovery = new PwRecovery();
$objPwRecovery->run();

