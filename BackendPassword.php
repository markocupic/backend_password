<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
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
 * @copyright  terminal42 gmbh 2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class BackendPassword extends Backend
{

	public function handleLoginScreen($strBuffer, $strTemplate)
	{
		if (BackendUser::getInstance()->id == 0 && $this->Input->get('pwrecovery'))
		{
			if ($this->Input->get('token') != '')
			{
				return $this->setNewPassword();
			}
			elseif ($this->Input->post('FORM_SUBMIT') == 'tl_pwrecovery' && $this->Input->post('username') != '' && $this->Input->post('email') != '')
			{
				$this->sendPasswordLink();
			}

			$this->Template = new BackendTemplate('be_pwrecovery');

			$this->Template->theme = $this->getTheme();
			$this->Template->messages = $this->getMessages();
			$this->Template->base = $this->Environment->base;
			$this->Template->language = $GLOBALS['TL_LANGUAGE'];
			$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
			$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
			$this->Template->action = ampersand($this->Environment->request);
			$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pwrecovery'];
			$this->Template->curUsername = $this->Input->post('username') ? $this->Input->post('username') : '';
			$this->Template->curEmail = $this->Input->post('email') ? $this->Input->post('email') : '';
			$this->Template->uClass = ($_POST && !$this->Input->post('username')) ? ' class="login_error"' : '';
			$this->Template->eClass = ($_POST && !$this->Input->post('email')) ? ' class="login_error"' : '';
			$this->Template->recoverButton = specialchars($GLOBALS['TL_LANG']['MSC']['recoverBT']);
			$this->Template->username = $GLOBALS['TL_LANG']['tl_user']['username'][0];
			$this->Template->email = $GLOBALS['TL_LANG']['tl_user']['email'][0];
			$this->Template->beLogin = $GLOBALS['TL_LANG']['MSC']['beLogin'];
			$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];
			$this->Template->ie6warning = sprintf($GLOBALS['TL_LANG']['ERR']['ie6warning'], '<a href="http://ie6countdown.com">', '</a>');

			return $this->Template->parse();
		}

		return $strBuffer;
	}


	/**
	 * Send password reset email
	 */
	protected function sendPasswordLink()
	{
		$time = time();

		$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE username=? AND email=? AND disable='' AND (start='' OR start<$time) AND (stop='' OR stop>$time)")
								  ->limit(1)
								  ->execute($this->Input->post('username'), $this->Input->post('email'));

		if ($objUser->numRows)
		{
			$confirmationId = md5(uniqid(mt_rand(), true));

			$this->Database->prepare("UPDATE tl_user SET activation=? WHERE id=?")->execute($confirmationId, $objUser->id);

			$strLink = $this->Environment->base . $this->Environment->script . '?pwrecovery=1&token=' . $confirmationId;

			// Send mail
			$objEmail = new Email();
			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoveryText'][0], $this->Environment->base);
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoveryText'][1], $this->Environment->base, $strLink);
			$objEmail->sendTo($objUser->email);

			$this->log('Password for user '.$objUser->username.' has been reset.', __METHOD__, TL_GENERAL);

			$this->addConfirmationMessage($GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']);
			$this->redirect($this->Environment->script);
		}
		else
		{
			$_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed'];
		}

		$this->reload();
	}


	/**
	 * Present the form to set a new password
	 */
	protected function setNewPassword()
	{
		$time = time();

		$objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE activation=? AND disable='' AND (start='' OR start<$time) AND (stop='' OR stop>$time)")
								  ->limit(1)
								  ->execute($this->Input->get('token'));

		if (!$objUser->numRows)
		{
			header('HTTP/1.1 404 Not Found');
			die('Not found');
		}

		if ($this->Input->post('FORM_SUBMIT') == 'tl_password')
		{
			$pw = $this->Input->post('password');
			$cnf = $this->Input->post('confirm');

			// Do not allow special characters
			if (preg_match('/[#\(\)\/<=>]/', html_entity_decode($this->Input->post('password'))))
			{
				$_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['extnd'];
			}
			// Passwords do not match
			elseif ($pw != $cnf)
			{
				$_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['passwordMatch'];
			}
			// Password too short
			elseif (utf8_strlen($pw) < $GLOBALS['TL_CONFIG']['minPasswordLength'])
			{
				$_SESSION['PW_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['passwordLength'], $GLOBALS['TL_CONFIG']['minPasswordLength']);
			}
			// Password and username are the same
			elseif ($pw == $objUser->username)
			{
				$_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['passwordName'];
			}
			// Save the data
			else
			{
				list(, $strSalt) = explode(':', $objUser->password);
				$strPassword = sha1($strSalt . $pw);

				// Make sure the password has been changed
				if ($strPassword . ':' . $strSalt == $objUser->password)
				{
					$_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['MSC']['pw_change'];
				}
				else
				{
					$strSalt = substr(md5(uniqid(mt_rand(), true)), 0, 23);
					$strPassword = sha1($strSalt . $pw);

					$this->Database->prepare("UPDATE tl_user SET password=?, activation='', loginCount=3, locked=0 WHERE id=?")
								   ->execute($strPassword . ':' . $strSalt, $objUser->id);

					$this->addConfirmationMessage($GLOBALS['TL_LANG']['MSC']['pw_changed']);
					$this->redirect('contao/index.php');
				}
			}

			$this->reload();
		}

		$this->Template = new BackendTemplate('be_password');

		$this->Template->theme = $this->getTheme();
		$this->Template->messages = $this->getMessages();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pw_change'];
		$this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
		$this->Template->password = $GLOBALS['TL_LANG']['MSC']['password'][0];
		$this->Template->confirm = $GLOBALS['TL_LANG']['MSC']['confirm'][0];
		$this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];

		return $this->Template->parse();
	}


	/**
	 * Return all messages as HTML
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function getMessages($blnNoWrapper=false)
	{
		$strMessages = '';

		// Regular messages
		foreach (array('PW_ERROR', 'PW_CONFIRM') as $strType)
		{
			if (!is_array($_SESSION[$strType]))
			{
				continue;
			}

			$strClass = strtolower($strType);
			$_SESSION[$strType] = array_unique($_SESSION[$strType]);

			foreach ($_SESSION[$strType] as $strMessage)
			{
				$strMessages .= sprintf('<p class="%s">%s</p>%s', str_replace('pw_', 'tl_', $strClass), $strMessage, "\n");
			}

			if (!$_POST)
			{
				$_SESSION[$strType] = array();
			}
		}

		$strMessages = trim($strMessages);

		// Wrapping container
		if ($strMessages != '')
		{
			$strMessages = sprintf('%s<div class="tl_message">%s%s%s</div>%s', "\n", "\n", $strMessages, "\n", "\n");
		}

		return $strMessages;
	}
}

