<?php

require 'classes/Model/Page.php';
    
?>
<!DOCTYPE html>
        <html lang="en">
            <head>
                 <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

                <title>Search results pages</title>
            </head>
            <body>
                 <form action="search.php" method="post">
                    <div class="field">
                            <label for="url">Search keywords</label>
                            <input type="text" name="search" id="search" autocomplete="off">
                    </div>
                    <input type="submit" value="Search">
                </form>
                <?php
                    function highlightKeywords($text, $keyword) {
                        $wordsArr = explode(" ", $keyword);
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
                        <hgroup class="mb20">
                                <h1>Search Results</h1>
                                <h2 class="lead"><strong class="text-danger"><?php echo $results['count'] ?></strong> results were found for the search for <strong class="text-danger"><?php echo $_POST['search'] ?></strong></h2>								
                        </hgroup>
                       <section class="col-xs-12 col-sm-6 col-md-12">

                            <?php foreach ($results["rows"] as $key => $value) {  ?>  
                           <article class="search-result row">
                               <div class="col-xs-12 col-sm-12 col-md-7 excerpet">
                                  <a href="<?php echo $value['url'] ?>"> <h3><?php echo highlightKeywords($value['title'], $_POST['search'])  ?> </h3><p><?php echo $value['url'] ?></p> </a>
                                    <p><?php echo highlightKeywords($value['content'], $_POST['search'])  ?></p>
                                           
                               </div>                                                
                           </article>
                          <?php } ?>
                       </section>
                            

                       
                        <?php } ?>
                    <?php } ?>
                
                  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
            </body>
        </html>