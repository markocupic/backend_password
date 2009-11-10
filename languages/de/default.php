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
 * @version    $Id$
 */


/**
 * Errors
 */
$GLOBALS['TL_LANG']['ERR']['invalidLogin']		= 'Anmeldung fehlgeschlagen! <a href="typolight/pwrecovery.php?username=' . $this->Input->post('username') . '">Passwort vergessen?</a>';
$GLOBALS['TL_LANG']['ERR']['accountLocked']		= 'Das Konto wurde gesperrt! Sie können sich in %d Minuten erneut anmelden. <a href="typolight/pwrecovery.php?username=' . $this->Input->post('username') . '">Passwort vergessen?</a>';
$GLOBALS['TL_LANG']['ERR']['pwrecoveryFailed']	= 'Kein Benutzer mit diesem Namen und E-Mail-Adresse gefunden.';


/**
 * Miscellaneous
 */
$GLOBALS['TL_LANG']['MSC']['pwrecovery']		= 'Passwort zurücksetzen';
$GLOBALS['TL_LANG']['MSC']['recoverBT']			= 'Neues Passwort senden';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']	= 'Es wurde Ihnen ein neues Passwort per E-Mail zugesendet. Bitte melden Sie sich mit dem neuen Passwort an.';
$GLOBALS['TL_LANG']['MSC']['pwrecoverySubject']	= 'Neues Passwort für %s';
$GLOBALS['TL_LANG']['MSC']['pwrecoveryMessage']	= "Auf %s wurde Ihr Passwort für den Benutzer %s zurückgesetzt.\n\nDas neue Passwort lautet: %s";

