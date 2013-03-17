<?php
  include_once("./required/files/tool_files_dirs.php");

  function get_lg($path){
    $res = array();
    preg_match('/([a-z]{2})\.xml/i', $path, $res);
    return $res[1];
  }

  (isset($argv[1])) ? $path1 = $argv[1] : $path1 = './exemples/celex_IP-04-901.fr.xml';
  (isset($argv[2])) ? $path2 = $argv[2] : $path2 = './exemples/celex_IP-04-901.el.xml';

  $el1 = mb_eregi("\.(el|ru)\.",$path1);
  $el2 = mb_eregi("\.(el|ru)\.",$path2);
  if($el1) list($path1,$path2) = array($path2,$path1);

  $lg1 = get_lg($path1);
  $lg2 = get_lg($path2);
  
  $array_files=tool_dir::dir_regexp("./","xhtml2.*\.php");
  $dict_file = array(
    "xhtml2vanilla_sentence_alinea.php" => ".sa.tok",
    "xhtml2vanilla_alinea_document.php" => ".ad.tok",
    "xhtml2vanilla_sentence_document.php" => ".sd.tok"
  );

  foreach($array_files["php"] as $path){
    $r    = explode("/",$path);
    $file = array_pop($r);
    $dir  = implode("/",$r)."/";

    $fo1  = $path1.$dict_file[$file];
    if(!is_file($fo1)){
      $cmd1 = "php $file $path1";
      $ret1 = system($cmd1);
    }

    $fo2  = $path2.$dict_file[$file];
    if(!is_file($fo2)){
      $cmd2 = "php $file $path2";
      $ret2 = system($cmd2);
    }

    if($el1 or $el2)
      $cmd_vanilla = "./src/align2 -D '.EOP' -d '.EOS' $fo1 $fo2";
    else
      $cmd_vanilla = "./src/align -D '.EOP' -d '.EOS' $fo1 $fo2";
    $fo_vanilla = $path1.$dict_file[$file].".al";
    $ret = system($cmd_vanilla);
    echo "\n>> $fo_vanilla";

    $root = Tool_Files::file_getRoot($path1);
    $path_align_svg = sprintf("%s/%s.%s%s.svg", $root, $lg1, $lg2, $dict_file[$file]);
//    $path_align_svg = $path1.$dict_file[$file].".svg";
    $cmd_svg = "php read_al.php $fo_vanilla > $path_align_svg";
    $ret = system($cmd_svg);
    echo "\n[svg created] $path_align_svg\n";
  }

?>

