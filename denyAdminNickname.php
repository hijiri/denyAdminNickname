<?php
/**
 * Loggix_Plugin - Deny Admin Nickname
 *
 * @copyright Copyright (C) UP!
 * @author    hijiri
 * @link      http://tkns.homelinux.net/
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since     2010.05.04
 * @version   10.5.6
 */

$this->plugin->addAction('before-receive-comment', 'denyAdminNickname');

function denyAdminNickname($referId) 
{
    // $sessionState....
    global $userName, $userPass, $sessionState, $app;

    $adminNicknameList = $app->getAdminNicknameListArray();

    // Found Nickname
    if (in_array($userName, $adminNicknameList)) {

        // Get Password List
        $sql = 'SELECT '
                  .     ' user_pass '
                  . 'FROM ' 
                  .     USER_TABLE . ' '
                  . 'WHERE '
                  .     "user_nickname = '" . $userName . "'";

        $res = $app->db->query($sql);
        foreach ($res as $row) {
            $bdUserPassList[] = $row['user_pass'];
        }

        // Password not found
        if (!in_array($userPass, $bdUserPassList)) {
            
            // Additional Title
            $additionalTitle = 'Not Allowed';

            // Contents
            $content = "<h2>Request Not Allowed</h2>\n"
                     . "<p>Administrator password is required.</p>\n"
                     . '<p>管理者のパスワードが必要です。</p>';

            // Set Variables
            $item = array(
                'title'    => $app->setTitle($additionalTitle),
                'contents' => $content,
                'result'   => '',
                'pager'    => ''
            );

            // Display Status
            $app->display($item, $sessionState);
            exit;
        }
    }

}
