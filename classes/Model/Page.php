<?php
include __DIR__ . '/../db.php';

class Page extends Db{
     public function __construct(){
        parent::__construct();
    }
    
    public function search($keywords) {
        $keywords = explode(" ", $_POST['search']);
        $query1 = "WHERE title LIKE '%".$keywords[0]."%'";
        $query2 = "WHERE content LIKE '%".$keywords[0]."%'";
        for($i = 1; $i < count($keywords); $i++) {
            if(!empty($keywords[$i])) {
                $query1 .= " OR title like '%" . $keywords[$i] . "%'";
                $query2 .= " OR content like '%" . $keywords[$i] . "%'";
            }
        }
        $query = "SELECT id, title, content, url FROM
        ( 
           SELECT 1 AS rnk, title, content, url, id FROM pages 
           $query1
           UNION 
           SELECT 2 AS rnk, title, content, url, id FROM pages 
           $query2
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
    
    public function selectAll() {
        $query = "SELECT id, parent_id, title, url, baseurl, LEFT(content, 200) AS content, depth FROM pages ORDER BY parent_id, depth";
        return $this->query($query);
    }
     public function getByUrl($url) {
        $query = "SELECT id FROM pages WHERE url = '$url' LIMIT 1";
        return $this->query($query);
    }
    
}
