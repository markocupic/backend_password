<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2015 Leo Feyer
 *
 * @package Backend Password
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Errors
 */
$GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed'] = 'No user found with this username or email address.';
$GLOBALS['TL_LANG']['ERR']['invalidBackendLogin'] = 'Login failed! <a href="contao/index.php?pwrecovery=1">Password lost?</a>';
$GLOBALS['TL_LANG']['ERR']['accountLocked'] = 'This account has been locked! You can log in again in %d minutes. <a href="contao/index.php?pwrecovery=1">Password lost?</a>';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['emailOrUsername'] = 'Please enter your email address or your username.';
$GLOBALS['TL_LANG']['MSC']['newPassword'] = 'Please enter your new password';
$GLOBALS['TL_LANG']['MSC']['beLogin'] = 'Contao back end login';
$GLOBALS['TL_LANG']['MSC']['pwrecovery'] = 'Reset password';
$GLOBALS['TL_LANG']['MSC']['recoverBT'] = 'Reset password';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess'] = 'We\'ve sent you an email explaining how to reset your password.';
$GLOBALS['TL_LANG']['MSC']['pwrecoveryText'] = array('Your password request on %s', "You have requested a new password for %s.\n\nPlease click %s to set the new password. If you did not request this e-mail, please contact the website administrator.\n");

