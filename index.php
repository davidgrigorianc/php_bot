<!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

                <title>Php Bot</title>
            </head>
            <body>
                
                <form action="crawl.php" method="post">
                    <div class="field">
                            <label for="url">Url</label>
                            <input type="text" name="url" id="url_input" autocomplete="off" placeholder="https://www.example.ru/">
                    </div>
                    <input type="submit" value="Scrape">
                </form>
                
                <form action="search.php" method="post">
                    <div class="field">
                            <label for="url">Search keywords</label>
                            <input type="text" name="search" id="search" autocomplete="off">
                    </div>
                    <input type="submit" value="Search">
                </form>
                
                <form method="get" action="xml.php">
                    <button type="submit">Download Pages in XML</button>
                 </form>
                
                
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
            </body>
        </html>

    