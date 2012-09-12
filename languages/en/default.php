<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Errors
 */
$GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed']	= 'No user found with this username and email address.';

if (TL_MODE == 'BE')
{
	$GLOBALS['TL_LANG']['ERR']['invalidLogin']		= 'Login failed! <a href="contao/index.php?pwrecovery=1">Password lost?</a>';
	$GLOBALS['TL_LANG']['ERR']['accountLocked']		= 'This account has been locked! You can log in again in %d minutes. <a href="contao/index.php?pwrecovery=1">Password lost?</a>';
}


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['beLogin']			= 'Contao back end login';
$GLOBALS['TL_LANG']['MSC']['pwrecovery']		= 'Reset password';
$GLOBALS['TL_LANG']['MSC']['recoverBT']			= 'Reset password';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']	= 'We\'ve sent you an email explaining how to reset your password.';
$GLOBALS['TL_LANG']['MSC']['pwrecoveryText']	= array('Your password request on %s', "You have requested a new password for %s.\n\nPlease click %s to set the new password. If you did not request this e-mail, please contact the website administrator.\n");

