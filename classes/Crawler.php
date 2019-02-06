<?php

class Crawler{
    public $url;
    public $errors;
    
    public function __construct($url){
        $this->setUrl($url);
    }
    
    private function setUrl($url) {
        if($this->isUrlCorrect($url)){
            $this->url = $url;
        }else{
             $this->errors['url'][] = 'url is incorrect';
        }
    }
    private function isUrlCorrect($url){
        return filter_var($url, FILTER_VALIDATE_URL);
        
    }
    
    public function getHtml() {
        $ch = curl_init();
        $url = $this->url;
        if($this->isUrlCorrect($url)) {               
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            $html = curl_exec($ch);
            if(!$html) {
                 $this->errors['url'][] = 'no html found';
            } else {
                return $html;
            }
        }
        return $this->errors;
        
    }
    
    
}
