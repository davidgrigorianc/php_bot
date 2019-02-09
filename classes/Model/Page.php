<?php
include __DIR__ . '/../db.php';

class Page extends Db{
     public function __construct(){
        parent::__construct();
    }
    
    public function search($term) {
        $query = "SELECT * FROM
        ( 
           SELECT 1 AS rnk,  id, parent_id, title as 'desc', content, url FROM pages 
           WHERE title LIKE '%".$term."%'
           UNION 
           SELECT 2 AS rnk,  id, parent_id, title as 'desc', content, url FROM pages 
           WHERE content LIKE '%".$term."%' 
        ) tab
        ORDER BY rnk
        LIMIT 10;";
        return $this->query($query);
    }
    
    public function savePage($content,$title,$url,$baseUrl,$depth,$parent_id){
        $query = ("INSERT INTO pages(content, title ,url, baseurl ,parent_id, depth) 
        VALUES('$content','$title','$url','$baseUrl','$parent_id','$depth')");
        return $this->query($query);
    }
    
    public function getByUrl($url) {
        $query = "SELECT id FROM pages WHERE url = '$url' LIMIT 1";
        return $this->query($query);
    }
    
    
}
