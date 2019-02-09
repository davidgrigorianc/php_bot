<?php

require 'classes/Crawler.php';
require 'classes/Model/Page.php';
    
//    
//    $page = new Page();
//    $term = 'trova';
//    $a = $page->search($term);
//    var_dump($a);
?>
<!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Crowled pages</title>
            </head>
            <body>
                <?php

                    if(isset($_POST['url']) && !empty($_POST['url'])){
                        $url = $_POST['url'];
                        echo '<h1>Crawler</h1>';
                        $crawler = new Crawler($url,360);
                        $crawled_pages = $crawler->init();
                        if($crawled_pages){ 
                            echo  '<a href="search.php">Go to search Page</a>';
                        }else{
                             echo  '<p>Url is not correct</p>';
                            echo  '<a href="search.php">Go to search Page</a>';
                        }
                    }else{
                        header('Location: index.php');

                    }
                    ?>
                        <a href="index.php"> Back </a>
            </body>
        </html>


   
        

    