<?php

$file = "./data/96796_DFI_rev0_PL.pdf.json";
$mep = file_get_contents($file);
$header = array();
foreach (json_decode($mep) as $k => $v) {
  $header[] = $k; 
};
print_r($header);
echo "'".implode($header,"','")."'\n";
