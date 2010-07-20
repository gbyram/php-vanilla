<?php
  include_once("./required/files/tool_files_dirs.php");

  (isset($argv[1])) ? $path1 = $argv[1] : $path1 = './exemples/celex_IP-04-901.fr.xml';
  (isset($argv[2])) ? $path2 = $argv[2] : $path2 = './exemples/celex_IP-04-901.el.xml';

  $el1 = mb_eregi("\.el\.",$path1);
  $el2 = mb_eregi("\.el\.",$path2);
  if($el1) list($path1,$path2) = array($path2,$path1);
  
  $array_files=tool_dir::dir_regexp("./","xhtml2.*\.php");
  $dict_file = array(
    "xhtml2vanilla_sentence_alinea.php" => ".sa.tok",
    "xhtml2vanilla_alinea_document.php" => ".ad.tok",
    "xhtml2vanilla_sentence_document.php" => ".sd.tok"
  );


  foreach($array_files["php"] as $path){
    $r=explode("/",$path);
    $file=array_pop($r);
    $dir=implode("/",$r)."/";

    $cmd1 = "php $file $path1";
    $fo1 = $path1.$dict_file[$file];
    $cmd2 = "php $file $path2";
    $fo2 = $path2.$dict_file[$file];

    $ret1 = system($cmd1);
    $ret2 = system($cmd2);

    if($el1 or $el2)
      $cmd_vanilla = "./src/align2 -D '.EOP' -d '.EOS' $fo1 $fo2";
    else
      $cmd_vanilla = "./src/align -D '.EOP' -d '.EOS' $fo1 $fo2";
    $fo_vanilla = $path1.$dict_file[$file].".al";
    $ret = system($cmd_vanilla);
    echo "\n$cmd_vanilla\n  >> $fo_vanilla";

    $fo_read = $path1.$dict_file[$file].".svg";
    $cmd_svg = "php read_al.php $fo_vanilla > $fo_read";
    $ret = system($cmd_svg);
    echo "\n[svg created] $fo_read\n";
  }

?>

