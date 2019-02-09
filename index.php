<!DOCTYPE html>
        <html lang="en">
            <head>
                <title>Php Bot</title>
            </head>
            <body>
                
                <form action="crawl.php" method="post">
                    <div class="field">
                            <label for="url">Url</label>
                            <input type="text" name="url" id="url_input" autocomplete="off">
                    </div>
                    <input type="submit" value="Scrape">
                </form>
                
                <form action="search.php" method="post">
                    <div class="field">
                            <label for="url">Search keywords</label>
                            <input type="text" name="search" id="search" autocomplete="off">
                    </div>
                    <input type="submit" value="Scrape">
                </form>
            </body>
        </html>

    