<?php
require 'classes/Model/Page.php';
$page = new Page();
$pages = $page->selectAll();
if(!empty($pages['affected_rows'])){
    $xml = new SimpleXMLElement('<xml/>');
    $k = buildTree($pages['rows'],$xml);
    header('Content-type: text/xml');
    header('Content-Disposition: attachment; filename="text.xml"');
    echo $xml->asXML();
       



    
}

function buildTree(array $elements, $xml ,$parentId = 0) {
     
    $branch = array();

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            if($element['parent_id'] == 0){
                $website = $xml->addChild('website', $element['baseurl'] );
            }else{
                $website = $xml;
            } 
            $page =  $website->addChild('page');
            $page->addChild('url',  htmlspecialchars($element['url']) );
            $page->addChild('depth', htmlspecialchars($element['depth']) );
            $page->addChild('title',  htmlspecialchars($element['title']) );
            $page->addChild('content',  htmlspecialchars($element['content']) );
             
            $children = buildTree($elements,$website, $element['id']);
            
            if ($children) {
                $element['children'] = $children;                
                $website = $xml;
                
            }
            $branch[] = $element;
        }
    }
    return $branch;
}
