<?php
 mb_internal_encoding("utf-8");
 require "./required/document/class_corpus.php";
 require "./required/document/class_document.php";
 require "./required/document/class_document_xml.php";

 require "./required/files/tool_files_dirs.php";

 require "./required/misc/class_tag.php";
 require "./required/misc/tool_color.php";

 require "./required/tree/class_tree.php";
 require "./required/tree/class_tree_xhtml.php";
 require "./required/tree/class_structure.php";
 require "./required/tree/tool_tree.php";

 require "./required/array/tool_array.php";

 require "./required/maths/tool_maths.php";

 require "./required/string/tool_string.php";
 require "./required/string/class_segment.php";

 require "./required/svg/tool_svg.php";

 require "./core.php";

  (isset($argv[1])) ? $path = $argv[1] : $path = './exemples/celex_IP-04-901.fr.xml.ad.tok.al';

  $data = Tool_files::file_load($path);
  $array_paquet = mb_split("\n\n",$data);
  $o1 = $o2 = 0;
  $taille1 = $taille2 = 0;
  $align1 = $align2 = array();

  foreach($array_paquet as $paquet){
    $split_paquet = mb_split("\n", $paquet);
    if(count($split_paquet) !== 3){
      continue;
    }
    list($link, $str1, $str2) = $split_paquet;
    if(!mb_ereg('\.EOP',$link)){
      $array_s1 = mb_split(' \.SB ',$str1);
      $array_s2 = mb_split(' \.SB ',$str2);
      $al = mb_split(" ",$link);
      $nb1 = $al[2];
      $nb2 = $al[4];
      $new_group1 = $new_group2 = array();
      for($i=0 ; $i<$nb1 ; ++$i){
        $new_group1[] = mb_substr($array_s1[$i],0,40) . "...";
      }
      $o1 += $nb1;
      for($j=0 ; $j<$nb2 ; ++$j){
        $new_group2[] = mb_substr($array_s2[$j],0,40) . "...";
      }
      $o2 += $nb2;
      if($new_group1 != array()){
        $align1[]=$new_group1;
        $taille1 += count($new_group1);
      }
      if($new_group2 != array()){
        $align2[]=$new_group2;
        $taille2 += count($new_group2);
      }
    }
  }

$u = 35;
$ml = 350;
$mt = 50;
$er = 100;

$repr1 = Tool_svg::line($ml,$mt,$ml,$mt + $u * $taille1);
$repr2 = Tool_svg::line($ml + $er,$mt,$ml + $er,$mt + $u * $taille2);

$o1 = $o2 = 0;
$out = "";
while(list($i,$a1) = each($align1) and list($j,$a2) = each($align2)){
  foreach($a1 as $cpt1=>$s1){
    foreach($a2 as $cpt2=>$s1){
      $out .= Tool_svg::lien($ml,$ml+$er,$mt,$u,$o1+$cpt1,$o2+$cpt2);
    }
  }

  foreach($a1 as $str){
    $out .= Tool_svg::tick($ml,$mt,$u,$o1);
    $out .= Tool_svg::ltxt($ml,$mt,$u,$o1,$str);
    $o1++;
  }
  foreach($a2 as $str){
    $out .= Tool_svg::tick($ml+$er,$mt,$u,$o2);
    $out .= Tool_svg::rtxt($ml+$er,$mt,$u,$o2,$str);
    $o2++;
  }
}

$out_svg = "<svg version=\"1.1\" baseProfile=\"full\" xmlns=\"http://www.w3.org/2000/svg\">

$repr1
$repr2
$out

</svg>";

print $out_svg;
?>
