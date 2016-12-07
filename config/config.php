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
 * Hooks
 */
if(TL_MODE == 'BE')
{
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('BackendPassword\\BackendPassword', 'handleLoginScreen');

}

