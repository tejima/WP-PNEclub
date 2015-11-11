<?php

/*
Plugin Name: PNE.club
Plugin URI: http://pne.club
Description: PNE.club premium user control for WordPress
Version: 1.0
Author: Mamoru Tejima
Author URI: http://twitter.com/tejima
License: Apache2.0
*/

add_option('pneclub_wordpresshost', 'http://blog.s1.cqc.jp/', null, 'yes');
add_option('pneclub_servicename', 'プレミアムプレス', null, 'yes');
add_option('pneclub_clubid', 'yx4ugVP6HA', null, 'yes');
add_option('pneclub_targetcourse', 'ANY', null, 'yes');
add_option('pneclub_readerlogin', 'reader', null, 'yes');
add_option('pneclub_readerpassword', 'gatagatamichi', null, 'yes');
add_option('pneclub_refreshtime', 3, null, 'yes');

add_option('pneclub_apihost', 'https://www.pne.club/', null, 'yes');

add_action('admin_post_nopriv_pnelogin', 'function_pnelogin');
add_action('admin_post_pnelogin', 'function_pnelogin');

function function_pnelogin()
{
    $url = 'https://www.pne.club/auth';
    $data = array(
        'email' => $_REQUEST['email'],
        'password' => $_REQUEST['password'],
        'club_id' => get_option('pneclub_clubid'),
        'course' => get_option('pneclub_targetcourse'),
    );
    $options = array('http' => array(
        'method' => 'POST',
        'content' => http_build_query($data),
    ));

    $contents_json = file_get_contents($url, false, stream_context_create($options));
    $contents = json_decode($contents_json, true);

    if(!isset($contents)){
        $contents = array('result' == '500');
    }

    switch ($contents['result']) {
        case '200':
            $creds = array();
            $creds['user_login'] = get_option('pneclub_readerlogin');
            $creds['user_password'] = get_option('pneclub_readerpassword');
            $creds['remember'] = true;
            $user = wp_signon($creds, false);
            $state = 'SUCCESS';
            break;

        case '401':
        case '402':
        case '403':
        case '500':
        default:
            $state = 'ERROR';
            break;
    }

    $wordpresshost = get_option('pneclub_wordpresshost');
    $servicename = get_option('pneclub_servicename');
    $refreshtime = get_option('pneclub_refreshtime');
    $referer =  $_SERVER["HTTP_REFERER"];

    if ($state == 'SUCCESS') {
        $result = <<<EOF
<HTML>
<HEAD>
<META http-equiv="refresh" content="{$refreshtime}; url={$referer}">
<TITLE></TITLE>
</HEAD>
<BODY bgcolor="#ffffff">
ログインしました。

{$refreshtime}秒で自動的にサイトが移動しない場合は、こちらをクリックして進んでください。
<a href="{$referer}">{$servicename}</a>

</BODY>
</HTML>
EOF;
    } elseif ($state == 'ERROR') {
        $result = <<<EOF
<HTML>
<HEAD>
<META http-equiv="refresh" content="{$refreshtime}; url={$referer}">
<TITLE></TITLE>
</HEAD>
<BODY bgcolor="#ffffff">
ログインに失敗しました。
<a href="{$referer}">{$servicename}</a>

</BODY>
</HTML>
EOF;
    }
    status_header(200);
    die($result);
}
