<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: $
 */


/**
 * Errors
 */
$GLOBALS['TL_LANG']['ERR']['invalidLogin']		= 'Login failed! <a href="typolight/pwrecovery.php?username=' . $this->Input->post('username') . '">Password lost?</a>';
$GLOBALS['TL_LANG']['ERR']['accountLocked']		= 'This account has been locked! You can log in again in %d minutes. <a href="typolight/pwrecovery.php?username=' . $this->Input->post('username') . '">Password lost?</a>';
$GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed']	= 'No user found with this username and email address.';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['pwrecovery']		= 'Reset password';
$GLOBALS['TL_LANG']['MSC']['recoverBT']			= 'Send new password';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']	= 'A new password has been sent to you by email. Please sign in with the new password.';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySubject']	= 'New password for %s';
$GLOBALS['TL_LANG']['MSC']['pwrecoveryMessage']	= "Your password at %s for user %s has been resetted..\n\nYou new password is: %s";

