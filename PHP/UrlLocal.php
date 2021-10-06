<?php
    
$xml = @simplexml_load_file('setting.xml');

$json = array(
    'url'=>''.$xml->setting[0]->LocalUrl,
);
$result = json_encode($json, JSON_NUMERIC_CHECK);
echo $result;