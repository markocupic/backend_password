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
$GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed']	= 'Kein Benutzer mit diesem Namen und E-Mail-Adresse gefunden.';

if (TL_MODE == 'BE')
{
	$GLOBALS['TL_LANG']['ERR']['invalidLogin']		= 'Anmeldung fehlgeschlagen! <a href="contao/index.php?pwrecovery=1">Passwort vergessen?</a>';
	$GLOBALS['TL_LANG']['ERR']['accountLocked']		= 'Das Konto wurde gesperrt! Sie können sich in %d Minuten erneut anmelden. <a href="contao/index.php?pwrecovery=1">Passwort vergessen?</a>';
}


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['beLogin']			= 'Contao Backend-Login';
$GLOBALS['TL_LANG']['MSC']['pwrecovery']		= 'Passwort zurücksetzen';
$GLOBALS['TL_LANG']['MSC']['recoverBT']			= 'Passwort zurücksetzen';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']	= 'Sie erhalten nun eine E-Mail mit Hinweisen um Ihr Passwort zu ändern.';
$GLOBALS['TL_LANG']['MSC']['pwrecoveryText']	= array('Ihre Passwort-Anforderung auf %s', "Sie haben ein neues Passwort für %s angefordert.\n\nBitte klicken Sie %s um das neue Passwort festzulegen. Wenn Sie diese E-Mail nicht angefordert haben, kontaktieren Sie bitte den Administrator der Webseite.\n");

