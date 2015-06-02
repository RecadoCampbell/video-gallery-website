<?php require('inc/common.php');

if(!empty($_POST)) {
    $output = array();
    $list = parse_str($_POST['list'], $output);
    $imploded_list = implode(',', $output['id']);
    echo $imploded_list;
    
    $query = "UPDATE video_order SET data = :order WHERE id = 1";
    $query_params = array(':order' => $imploded_list);
    try { 
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex) {
        die($ex->getMessage());
    }
}