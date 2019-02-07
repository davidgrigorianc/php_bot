<?php

class Crawler{
    public $start_url;
    public $baseUrl;
    public $urls = array();
    public $crawled_urls = array();
    public $errors;
    
    public function __construct($url){
        $this->setUrl($url);
    }
    
    private function setUrl($url) {
        if($this->isUrlCorrect($url)){
            $this->start_url = $url;
            $this->baseUrl = $this->baseUrl($url);
        }else{
             $this->errors['url'][] = 'url is incorrect';
        }
    }
    private function baseUrl($url) {
        $parsed_url = parse_url($url);
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
        $host    = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        return "$scheme$host";
    }
    private function isUrlCorrect($url){  
        return filter_var($url, FILTER_VALIDATE_URL);        
    }
    
    private function isExternal($url) {
        return (!empty(parse_url($url, PHP_URL_HOST)) && parse_url($url, PHP_URL_HOST) != parse_url($this->baseUrl, PHP_URL_HOST) );     
    }
    
    private function checkedInternal($url) {
        if(!$this->isExternal($url)){            
            return (!empty(parse_url($url, PHP_URL_HOST))) ? $url : $this->baseUrl.$url;
        }      
    }
    
    public function getHtml($url) {
        $ch = curl_init();
        if($this->isUrlCorrect($url)) {               
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            $html = curl_exec($ch);
            if($html) {
                $this->crawled_urls[] = $url;
                return $html;
            }
        }
        return false;;
        
    }
    
    private function collectLinks($html){
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');   
        $urls = array();
        foreach ($links as $key => $link) {
            $url = $link->getattribute('href');
             $url = rtrim($url, "/");
                $url = rtrim($url, "#");
            if(!$this->isExternal($url)){             
                $url = $this->checkedInternal($url);
            }
            if($this->isUrlCorrect($url)){               
                if(!in_array($url, $this->crawled_urls)){
                    $urls[] = $url;
                }
            }
        }
        return $urls;
       
    }
    
    
    
    private function getAllLinks($start_url, $depth = 0) {        
        $html = $this->getHtml($start_url);
        $urls = $this->collectLinks($html);
        foreach ($urls as $key => $url) {
            $this->pageUrls = $url; 
            if(!in_array($url, $this->crawled_urls) && !($this->isExternal($url))){
                $checkedUrl = $this->checkedInternal($url);
                $this->getAllLinks($checkedUrl,$depth++);
            }            
        }
        
        
    }
    
    public function init() {
        $this->getAllLinks($this->baseUrl);
        return  $this->crawled_urls;
    }
    
    
    
}
