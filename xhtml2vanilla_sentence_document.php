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
 require "./core.php";

(isset($argv[1])) ? $path = $argv[1] : $path = './exemples/celex_IP-04-901.el.xml';

$factory_mot = Core::path2fm($path);
$rep = $factory_mot->__get_repartition_segment();

$str_tok = "";
foreach($rep as $id_doc => $doc){
  foreach($doc as $array_segments){
    foreach($array_segments as $id_segment){
      $mot = $factory_mot->__get_word($id_segment);
      ($mot == ".") ?$str_tok .= ".\n.SB\n.EOS\n" : $str_tok .= "$mot\n";
    }
    if($mot != ".") $str_tok .= ".\n.SB\n.EOS\n";
  }
}
$str_tok .= ".EOP\n";

$path_tok = $path.".sd.tok";
if(tool_files::file_write($path_tok,$str_tok)){
  print "\n>> tokens created in $path_tok";
}
?>
