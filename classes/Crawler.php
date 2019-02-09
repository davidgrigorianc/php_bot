<?php
class Crawler{
    public $Page;
    public $start_url,$active_url,$depth,$active_id, $parent_id, $baseUrl , $limit ,$crawled_urls = array(), $errors=array();
    
    public function __construct($url, $max_execution){
        // set maximum execution time
        ini_set('max_execution_time', $max_execution);
        // model page
        $this->Page = new Page();
        //setting given url
        $this->setUrl($url);
//        $this->limit = $limit;
    }
    
    private function setUrl($url) {
        //check is url correct
        if($this->isUrlCorrect($url)){            
            $this->start_url = $url;
            // setting baseurl for checking is url internal
            $this->baseUrl = $this->baseUrl($url);
        }else{
            //collect errors
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
        // checking is url correct if yes returning true
        return filter_var($url, FILTER_VALIDATE_URL);        
    }
    
    private function url_exists($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); 
        if($httpcode>=200 && $httpcode<300){
                return true;
        } else {
                return false;
        }
    }
    
    private function isExternal($url) {        
        return (!empty(parse_url($url, PHP_URL_HOST)) && parse_url($url, PHP_URL_HOST) != parse_url($this->baseUrl, PHP_URL_HOST) );     
    }
    
    private function checkedInternal($url) {
        // return internal url in baseurl+
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
        unset($html);
        return false;;
        
    }
    
    private function processLinks($links,$depth,$parent_id){     
        foreach ($links as $key => $link) {
                $url = $link->getattribute('href');
                $url = rtrim($url, "/");
                $url = rtrim($url, "#");
                if(!$this->isExternal($url)){             
                    $url = $this->checkedInternal($url);
                }
                if($this->isUrlCorrect($url)){               
                    if(!in_array($url, $this->crawled_urls) && !($this->isExternal($url)) && $this->url_exists($url) && !$this->isMailTo($url)){
                        $parent_id = $this->active_id;
                        $this->crawlPage($url, $parent_id, $depth++);
                    }  
                }
                 
        }       
    }
    
    private function isMailTo($url) {
        return (strpos($url, 'mailto:'));
    }
    
    private function crawlPage($url,$parent_id, $depth) { 
        $url = $this->checkedInternal($url);
        $html = $this->getHtml($url);        
        $this->crawled_urls[] = $url;
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');
        $content = $this->getText($dom);
        $title = $this->getTitle($dom);
        $baseUrl = $this->baseUrl;
        $parent_id = $this->savePageToDB($content,$title,$url,$depth,$baseUrl,$parent_id);    
        if($depth == 0){
            $depth++;
        }
        if(!empty($links)){
             $this->processLinks($links,$depth,$parent_id);   
        }            
        
    }
    
    
    
    
    
    private function savePageToDB($content,$title,$url,$depth,$baseUrl,$parent_id) {
        
        $this->_printResult($content, $title, $url, $depth, $parent_id);
        
        $db_page = $this->Page->getByUrl($url);
        if(isset($db_page["rows"][0]['id'])){
            return $db_page["rows"][0]['id'];
        }
        
        $save = $this->Page->savePage($content,$title,$url,$baseUrl,$depth,$parent_id);
        
        if($save['success']){
            return $save['insert_id'];
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

     
    
    public function getErrors() {
        return $this->errors;
    }
    
    
    
    public function init() {
        $start_url = rtrim($this->start_url, "/");
        $this->crawlPage($start_url,$this->parent_id , $depth = 0);
    }

    protected function _printResult($content, $title, $url, $depth, $parent_id){
        ob_end_flush();
        $count = count($this->crawled_urls);
        echo "N::$count,Url::$url,DEPTH::$depth <br>";
        ob_start();
        flush();
    }
    
    
}
