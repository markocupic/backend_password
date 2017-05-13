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
 * Run in a custom namespace, so the class can be replaced
 */

namespace MCupic\BackendPassword;

/**
 * Class BackendPassword
 *
 * Provides methods for handling new password requests
 *
 * @copyright  Andreas Schempp transferred to Marko Cupic 2015
 * @author     Marko Cupic, Oberkirch, Switzerland ->  mailto: m.cupic@gmx.ch
 * @package    Backend Password
 */
class BackendPassword extends \Backend
{

    /**
     * @param $strBuffer
     * @param $strTemplate
     * @return mixed
     */
    public function handleLoginScreen($strBuffer, $strTemplate)
    {

        if (\BackendUser::getInstance()->id == 0 && \Input::get('pwrecovery'))
        {
            if (\Input::get('token') != '')
            {
                return $this->setNewPassword();
            }
            elseif (\Input::post('FORM_SUBMIT') == 'tl_pwrecovery' && \Input::post('emailOrUsername') != '')
            {
                $this->sendPasswordLink();
            }
            else
            {
                //
            }

            // modify headline
            $html = preg_replace('/<h2>(.*)<\/h2>/', '<h2>' . $GLOBALS['TL_LANG']['MSC']['pwrecovery'] . '</h2>', $strBuffer);

            // show messages
            $html = preg_replace('/<table class="tl_login_table">/', $this->getPasswordMessages() . '<table class="tl_login_table">', $html);

            //modify FORM_SUBMIT value
            $html = preg_replace('/name="FORM_SUBMIT" value="tl_login"/', '/name="FORM_SUBMIT" value="tl_pwrecovery"/', $html);

            // modify formbody table
            $pw_table = '
<table class="tl_login_table">
       <tbody>
       <tr>
              <td><label for="emailOrUsername">%s</label></td>
              <td style="text-align:right"><input type="text" name="emailOrUsername" id="emailOrUsername" class="tl_text" value="" maxlength="255" required=""></td>
       </tr>
       </tbody>
</table>
                    ';
            $pw_table = sprintf($pw_table, $GLOBALS['TL_LANG']['MSC']['emailOrUsername']);
            $html = preg_replace('/\<table((?!<table).)class="tl_login_table(.*)\<\/table\>/iUs', $pw_table, $html);

            // modify submit button
            $search = '<input(.*)type="submit"(.*)name="login"(.*)class="tl_submit"(.*)>';
            $replace = sprintf('<input type="submit" name="recover" id="recover" class="tl_submit" value="%s">', $GLOBALS['TL_LANG']['MSC']['recoverBT']);
            $html = preg_replace('/' . $search . '/', $replace, $html);

            return $html;
        }

        return $strBuffer;
    }

    /**
     * Send password reset email
     */
    protected function sendPasswordLink()
    {

        $time = time();

        $objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE (email=? OR username=?) AND disable='' AND (start='' OR start<$time) AND (stop='' OR stop>$time)")->limit(1)->execute(\Input::post('emailOrUsername'), \Input::post('emailOrUsername'));

        if ($objUser->numRows)
        {
            $confirmationId = md5(uniqid(mt_rand(), true));

            $this->Database->prepare("UPDATE tl_user SET activation=? WHERE id=?")->execute($confirmationId, $objUser->id);

            $strLink = $this->Environment->base . $this->Environment->script . '?pwrecovery=1&token=' . $confirmationId;

            // Send mail
            $objEmail = new \Email();
            $objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
            $objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoveryText'][0], $this->Environment->base);
            $objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['pwrecoveryText'][1], $this->Environment->base, $strLink);
            $objEmail->sendTo($objUser->email);

            $this->log('Password for user ' . $objUser->username . ' has been reset.', __METHOD__, TL_GENERAL);

            \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pwrecoverySuccess']);
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

        $objUser = $this->Database->prepare("SELECT * FROM tl_user WHERE activation=? AND disable='' AND (start='' OR start<$time) AND (stop='' OR stop>$time)")->limit(1)->execute(\Input::get('token'));

        if (!$objUser->numRows)
        {
            header('HTTP/1.1 404 Not Found');
            die('Not found');
        }

        if (\Input::post('FORM_SUBMIT') == 'tl_password')
        {
            $pw = \Input::postUnsafeRaw('password');
            $cnf = \Input::postUnsafeRaw('confirm');

            // Do not allow special characters
            if (preg_match('/[#\(\)\/<=>]/', html_entity_decode(\Input::post('password'))))
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
                list($strPassword, $strSalt) = explode(':', $objUser->password);
                $strPassword = ($strSalt == '') ? sha1(\Input::postUnsafeRaw('password')) : sha1($strSalt . \Input::postUnsafeRaw('password'));

                // Make sure the password has been changed
                if ($strPassword . ':' . $strSalt == $objUser->password)
                {
                    $_SESSION['PW_ERROR'][] = $GLOBALS['TL_LANG']['MSC']['pw_change'];
                }
                else
                {
                    $strSalt = substr(md5(uniqid(mt_rand(), true)), 0, 23);
                    $strPassword = sha1($strSalt . $pw);

                    $this->Database->prepare("UPDATE tl_user SET password=?, activation='', loginCount=3, locked=0 WHERE id=?")->execute($strPassword . ':' . $strSalt, $objUser->id);

                    \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['pw_changed']);
                    $this->redirect('contao/index.php');
                }
            }

            $this->reload();
        }

        $this->Template = new \BackendTemplate('be_password');

        $this->Template->theme = $this->getTheme();
        $this->Template->messages = $this->getPasswordMessages();
        $this->Template->base = $this->Environment->base;
        $this->Template->language = $GLOBALS['TL_LANGUAGE'];
        $this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
        $this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
        $this->Template->action = ampersand($this->Environment->request);
        $this->Template->headline = $GLOBALS['TL_LANG']['MSC']['pw_change'];
        $this->Template->submitButton = specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
        $this->Template->password = $GLOBALS['TL_LANG']['MSC']['newPassword'];
        $this->Template->confirm = $GLOBALS['TL_LANG']['MSC']['confirm'][0];
        $this->Template->disableCron = $GLOBALS['TL_CONFIG']['disableCron'];

        return $this->Template->parse();
    }

    /**
     * Return all messages as HTML
     * @return string
     */
    protected function getPasswordMessages()
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

    /**
     * Do not show pw-recovery link, when FE-authentication fails
     * @see: https://github.com/markocupic/backend_password/issues/4
     */
    public function setLanguage()
    {
        if (TL_MODE == 'BE')
        {
            $GLOBALS['TL_LANG']['ERR']['invalidLogin'] = $GLOBALS['TL_LANG']['ERR']['invalidBackendLogin'];
        }
    }
}