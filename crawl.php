<?php

require 'classes/Crawler.php';
require 'classes/Model/Page.php';

    $url = $_POST['url'];
    if(!empty($url)){
        $crawler = new Crawler($url,10);
        $crawled_pages = $crawler->init();
        if($crawled_pages){
            echo count($crawled_pages).' crawled';
        }
    }
    
//    
//    $page = new Page();
//    $term = 'trova';
//    $a = $page->search($term);
//    var_dump($a);
    