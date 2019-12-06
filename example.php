<?php

require 'Planfix_API.php';

$PF = new Planfix_API(array('apiUrl' => 'https://apiru.planfix.ru/xml', 'apiKey' => 'YOUR_API_KEY', 'apiSecret' => 'YOUR_API_SECRET'));

$PF->setAccount('YOUR_ACCOUNT');

session_start();

if (empty($_SESSION['planfixSid'])) {
    $PF->setUser(array('login' => 'YOUR_LOGIN', 'password' => 'YOUR_PASSWORD'));
    $PF->authenticate();
    $_SESSION['planfixSid'] = $PF->getSid();
}

$PF->setSid($_SESSION['planfixSid']);

$method = 'client.getList';
$params = array(
    'user' => array(
        array('id' => 1)
    ),
    'pageCurrent' => 1
);

$clients = $PF->api($method, $params);

if(!$PF->hasError()) {
  echo '<pre>'.print_r($clients, 1).'</pre>';
} else {
  echo 'Error! Code: '.$PF->getErrorCode().' / Message: '.$PF->getError();
}

$batch = array(
    array(
        'method' => 'project.getList',
        'params' => array(
            'user' => array(
                array('id' => 1)
            ),
            'pageCurrent' => 1
        )
    ),
    array(
        'method' => 'task.getList',
        'params' => array(
            'user' => array(
                array('id' => 1)
            ),
            'pageCurrent'   => 1
        )
    )
);

list($projects, $tasks) = $PF->api($batch);

if(!$PF->hasError()) {
  echo '<pre>'.print_r($tasks, 1).'</pre>';
  echo '<pre>'.print_r($projects, 1).'</pre>';
} else {
  echo 'Error! Code: '.$PF->getErrorCode().' / Message: '.$PF->getError();
}