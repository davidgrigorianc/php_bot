<?php

require 'classes/Model/Page.php';
    
?>
<!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Search results pages</title>
            </head>
            <body>
                <?php
                    function highlightKeywords($text, $keywords) {
                        $wordsArr = explode(" ", $keywords);
                        $wordsCount = count($wordsArr);
                        for($i=0;$i<$wordsCount;$i++) {
                                $highlighted_text = "<span style='font-weight:bold;background-color: #FFFF00'>$wordsArr[$i]</span>";
                                $text = str_ireplace($wordsArr[$i], $highlighted_text, $text);
                        }
                        return $text;
                    }
                   
                    ?>
                   <?php  if(isset($_POST['search']) && !empty($_POST['search'])){                       
                        
                        $page = new Page();
                        $results = $page->search($_POST['search']);
                        if(!empty($results)){ ?>
                       
                            <?php foreach ($results["rows"] as $key => $value) {  ?>  
                             <ul>
                                <li><?php echo  highlightKeywords($value['title'], $_POST['search'])  ?></li>
                                <?php var_dump($value['content'])?>
                                <li><?php echo highlightKeywords($value['content'], $_POST['search'])  ?></li>
                                <li><?php echo $value['url'] ?></li>                                    
                            </ul>
                          <?php } ?>
                            
                            

                       
                        <?php } ?>
                    <?php } ?>
            </body>
        </html>
