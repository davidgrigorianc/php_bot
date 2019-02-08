<?php
class Crawler{
    public $Page;
    public $start_url,$active_url, $parent_id, $baseUrl , $limit, $crawled_urls = array(), $errors=array();
    
    public function __construct($url, $limit){
        ini_set('max_execution_time', 90);
        $this->Page = new Page();
        $this->setUrl($url);
        $this->limit = $limit;
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
        $this->active_url = $url;
        $ch = curl_init();
        if($this->isUrlCorrect($url)) {               
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            $html = curl_exec($ch);
            if($html) {               
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
        
        $content = $this->getText($dom);
        $title = $this->getTitle($dom);
        $this->savePage($content,$title);
        
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
    
    private function savePage($content,$title) {
        $url =  $this->active_url;
        $baseUrl = $this->baseUrl;
        $parent_id = $this->parent_id;
        $save = $this->Page->savePage($content,$title,$url,$baseUrl,$parent_id);
        if($save['success']){
            $this->parent_id = $save['insert_id'];
        }
        return $save['success'];
    }
    
    
    private function crawlAllLinks($start_url, $depth = 0) {    
        $html = $this->getHtml($start_url);
        if($html){
            $this->crawled_urls[] = $start_url;
        }
        $urls = $this->collectLinks($html);
        foreach ($urls as $key => $url) {
            $this->pageUrls = $url; 
            if(!in_array($url, $this->crawled_urls) && !($this->isExternal($url)) && $depth <= $this->limit){
                $checkedUrl = $this->checkedInternal($url);
                $this->crawlAllLinks($checkedUrl,$depth++);
            }            
        }
        
    }
    
   
    
    private function getText($dom) {
        $xpath = new DOMXPath($dom);
        $textnodes = $xpath->query('//text()[normalize-space() and not(ancestor::a | ancestor::script | ancestor::style)]');
        $text = '';
        $numTextNodes = $textnodes->length;
        for ($i = 0; $i < $numTextNodes; $i++) {
            $node = $textnodes->item($i);
            $textContent = ' '  . $node->textContent;
            $textContent = preg_replace('/[^\w\d]+/m', ' ', $textContent);
            $text .= $textContent;
        }
        return strtolower($text);
    }
    
    private function getTitle($dom){
        $title = '';
        $list = $dom->getElementsByTagName("title");
      
        if ($list->length > 0) {
            $title = $list->item(0)->textContent;
        }
        return $title;
    }

     public function init() {
        $this->crawlAllLinks($this->start_url);
        return  $this->crawled_urls;
    }

//    public function savetoDb($title, $content, $url, $parent_id = null) {
//        
//    }
    
    
}
