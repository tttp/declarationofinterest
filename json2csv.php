<?php
$file ="doi";
$out = array();
$h = array('mep_id'
,'date'
,'occupation'
,'activity'
,'occasional'
,'membership'
,'holding'
,'other'
,'additional'
,'support'
,'url'
);

$fp = fopen($file.'.csv', 'w');
fputcsv($fp, $h);

$meps = json_decode(file_get_contents ("./ep_mep_active.json"));
$t = array ();
foreach ($meps as $mep) {
  $m = null;
  foreach ($mep->{"Financial Declarations"} as $tmp) {
    if (property_exists($tmp,"date")) {
      $m = $tmp;
      continue;
    } else {
      echo ("\n". $mep->Name->full. " has invalid doi (event participation?)");
    }
  }
  if (!$m) {
    echo ("\n". $mep->Name->full. " has unparsable or missing doi");
    continue;
  }
  $m->mep_id = $mep->UserID;
  $out[] = $m;
//  echo ("\n". $mep->Name->full. " ". $m->date . "->". $m->url);
  foreach ($h as $k) {
    if (!is_object ($m) || !property_exists($m,$k)) {
      $t[] ="";
      continue;
    }
    if (!is_array($m->$k)) {
      $t[] = preg_replace('/\s+/', ' ',str_replace ("\n",",",$m->$k));
    } else {
        $v = array();
      foreach ($m->$k as $l) {
        $v[] = $l[0] . "[".$l[1]."]";
      }
      $t[]=implode ($v,"| ");
    }
  }
  fputcsv($fp, $t);
}
/*if ($handle = opendir('./data')) {

    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {
            $m = json_decode(file_get_contents ("./data/$entry"));
            $t = array ();
            foreach ($h as $k) {
              if (!is_object ($m) || !property_exists($m,$k)) {
                $t[] ="";
                continue;
              }
              if (!is_array($m->$k)) {
                $t[] = preg_replace('/\s+/', ' ',str_replace ("\n",",",$m->$k));
              } else {
                  $v = array();
                foreach ($m->$k as $l) {
                  $v[] = $l[0] . "[".$l[1]."]";
                }
                $t[]=implode ($v,"| ");
              }
            }
  fputcsv($fp, $t);
//            echo file_get_contents ("./data/$entry");
        }
    }

 closedir($handle);
}

*/

fclose ($fp);
$fp = fopen($file.'.json', 'w');
fwrite($fp, "\xEF\xBB\xBF"); // UTF-8 BOM
fwrite($fp, json_encode($out,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
fclose ($fp);

