<?php

class Linode{
    
    public function __construct() {
        $this->url = 'https://api.linode.com/?api_key=';
    }

    function setKey($key){
        $this->key = $key;  
    }
    
    function makeRequest($api_action, $params=array()){
        
        // include the doo rest class
        require_once(dirname(__FILE__).'doophp/helper/DooRestClient.php');
            
        // build the action part of the url
        $api_action_part = '&api_action='.$api_action;
        
        // build the params part of the url
        $params_part = '';
        foreach ($params as $k=>$v){
            $params_part .= '&'.$k.'='.$v;    
        }
        
        // build the full url
        $url = $this->url.$this->key.$api_action_part.$params_part;
        
        // request the url
        $data = new DooRestClient;
        $data->connect_to($url);
        $data->get();
        
        // check if the result was a success
        if($data->isSuccess()){
            // return the data as an array
            $this->resultData = json_decode($data->result()); 
            return $this->resultData;
        }else{
            return false;
        }
        
    }
    
    function debug(){
        foreach($this->resultData as $k=>$v){    
            if(strtolower($k)==='errorarray'){
                $linode_errors = $v;
            }
            elseif(strtolower($k)==='data'){
                $linode_data = $v;
            }
            elseif(strtolower($k)==='action'){
                $linode_action = $v;
            }
            else{
                $linode_unknown = $v;
            }
        }
        
        if(!empty($linode_errors)){
            echo '<h1 style="background-color: pink;">How embarrassment.</h1>';    
        }
        else{
          echo '<h1 style="background-color: green; color: #EDDA74; padding: 5px; border-radius: 5px;">You hit the web-service, like a boss!</h1>';    
        }
        
        echo '<h2>Information:</h2>';
        echo "<p>The request was for the linode action <strong>$linode_action</strong></p>";
        echo '<p>The following data was recieved from linode:</p>';
        pre_print_r($linode_data);
        
        if(!empty($linode_errors)){
            echo '<hr /><h2 style="background-color: pink;">Errors...</h2>';
            echo '<p>The following errors were recieved from linode:</p>';
            pre_print_r($linode_errors);
        }
        
        if(!empty($linode_unknown)){
            echo '<hr /><h1 style="background-color: red;">DANGER DANGER!</h1>';
            echo '<p>I found the following unknown paramaters, which I must say, I wasn\'t expecting.</p>';
            pre_print_r($linode_unknown);
        }
    }
    
}

?>