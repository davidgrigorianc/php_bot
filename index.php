<?php
require 'classes/Crawler.php';
    $url = 'https://trovaunposto.it/';
    $crawler = new Crawler($url);
    $links = $crawler->init();
    var_dump($links);
    