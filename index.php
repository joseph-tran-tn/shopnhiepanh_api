<?php
require_once( dirname(dirname(__FILE__)) . '/includes/config.php' );
require_once( dirname(dirname(__FILE__)) . '/includes/db.php' );
require_once( dirname(dirname(__FILE__)) . '/includes/fc.php' );

$rA = array();
$action = 'null';

$rA['status'] = 400;
$rA['message'] = 'Permision denied!';

if($_POST['key'] == 'f5506150e385e67375dcad2c4cadfbcd') {
  $rA['get'] = $_GET;
  $rA['post'] = $_POST;
  $action = trim($rA['get']['action'], "\/");
  $data = $rA['post'];
  $action_path = ACTION_PATH . $action . '.php';
  if(file_exists($action_path)) require $action_path;
  if(empty($rA['status'])) {
    switch ($action) {
      default:
        $rA['status'] = 300;
        $rA['message'] = 'Missing action!';
        break;
    }
  }
}

$logArr = array();
$logArr['post'] = $rA['post'];
$logArr['post']['Password'] = md5($logArr['post']['Password']);
$log = date('d-m-Y H:i:s') . ": [Key: ".$_POST['key']."] [Action: {$action}] [STATUS: ".$rA['message']."] JSON_POST_DATA: " . json_encode($logArr) . "\n";
$filename_log = dirname(__FILE__) . '/log/access.log';
Utils::printLog($log, $filename_log);

unset($rA['post']);
unset($rA['get']);
die(json_encode($rA, JSON_FORCE_OBJECT));