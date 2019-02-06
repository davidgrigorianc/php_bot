<?php
require 'classes/Crawler.php';
    $url = 'asfsaf';
    $crawler = new Crawler($url);
    $html = $crawler->getHtml();
    var_dump($html);
    