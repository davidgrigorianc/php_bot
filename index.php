<?php
require 'classes/Crawler.php';
require 'classes/Model/Page.php';

//    $url = 'https://trovaunposto.it/';
//    $crawler = new Crawler($url);
//    $links = $crawler->init();
//    var_dump($links);
    
    $page = new Page();
    $term = 'basta';
    $a = $page->search($term);
    var_dump($a);