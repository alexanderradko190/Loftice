<?php
header('Content-Type: application/json');
$key = (int)$_GET['key'];
if ($key === -1) {
    $key = rand(0, 2);
}
$arr = [
    ['test','variable','politic','route','frame','select',11,-123],
    ['cos','11','ism','$amazon','inspect'],
    ['frame','cos','text','input','',null,false]
];
$rnd = rand(0, count($arr[$key]));

echo json_encode(['result' => $arr[$key][$rnd]]);