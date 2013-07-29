<?php

$devmode = true;

if($devmode===true){
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);
}

function pre_print_r($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

// include the class to make calls to linode
require_once('linode-api.php');

// set the access key
$linode_access_key = 'supply-key';

// create a new linode request instance, passing the access key, then request the action and include any params you may need
$linode = new Linode();
$linode->setKey($linode_access_key);
$linode->makeRequest('linode.list');

$linode->debug();

// if theres no errors
if(empty($linode->resultData->ERRORARRAY)){
    // grab the data
    foreach($linode->resultData->DATA as $k=>$v){
        echo 'Linode: '.$v->LABEL.'<br />';
    }
}
else{
    echo 'dude, something is wrong!';
    pre_print_r($linode->resultData->ERRORARRAY);
}


?>