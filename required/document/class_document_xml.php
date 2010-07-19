<?php
mb_internal_encoding("UTF-8");

class Document_xml extends Document{

 public function __get_vals_token(){
  $parser_token=xml_parser_create();
  xml_parse_into_struct($parser_token,$this->container,$vals_token,$index_token);
/*
  $config = array(
    'indent' => true,
    'output-xhtml' => true ,
    'wrap' => 200,
    'escape-cdata'=>false
  );
  $tidy = new tidy;
  $tidy->parseString($this->contenu,$config);
  $tidy->cleanRepair();
  //$tidy->value
*/
  $parser_token=xml_parser_create();
  xml_parse_into_struct($parser_token,$this->container,$vals_token,$index_token);
  $array=array();
  mb_regex_encoding('UTF-8');
  foreach($vals_token as $id=>$token){
   switch($token["type"]){
    case "cdata" :
     if(!(isset($token["value"]) and !mb_eregi("[[:alnum:]\(\)\[\]\.,;!?]",$token["value"]))) array_push($array,$token);
     //if(!(isset($token["value"]) and !mb_eregi("[[:alnum:]\(\)\[\]\.,;!?]",$token["value"]))) array_push($array,$token);
     break;
    case "open" :
     if(isset($token["value"]) and !mb_eregi("[[:alnum:]\(\)\[\]\.,;!?]",$token["value"])){unset($token["value"]);}
     array_push($array,$token);
     break;
    default :
     array_push($array,$token);
     break;
   }
  }
  //return $vals_token;
  return $array;
 }

 public function __get_xhtml_tree(){
 //$parser_token=xml_parser_create();
 //xml_parse_into_struct($parser_token,$this->contenu,$vals_token,$index_token);
  $vals_token=$this->__get_vals_token();
  $stack=array();
  $profondeur=0;
  foreach($vals_token as $id=>$token){
   $tag=new Tag();
   $tag->__set_from_token_xml($token);

   switch($token["type"]){
    case "cdata" :
     if(isset($token["value"])) $stack[$profondeur-1]->__add_tree(new Tree_xhtml(new Tag_str($token["value"])));
     break;

    case "open" :
     $stack[$profondeur]=new Tree_xhtml($tag);
     if(isset($token["value"])) $stack[$profondeur]->__add_tree(new Tree_xhtml(new Tag_str($token["value"])));
     $profondeur++;
     break;

    case "close" :
     $profondeur--;
     if(isset($stack[$profondeur-1])){
      $stack[$profondeur-1]->__add_tree($stack[$profondeur]);
      unset($stack[$profondeur]);
     }
     break;

    case "complete" :
     $tree=new Tree_xhtml($tag);
     if(isset($token["value"])){
        $tree->__add_tree(new Tree_xhtml(new Tag_str($token["value"])));
     }
     $stack[$profondeur-1]->__add_tree($tree);
     break;

    default : break;
   }
  }

  return $stack[0];
 }

 public function __get_all_blocs(){
  $tree_xhtml=$this->__get_xhtml_tree();
  $tree_xhtml->__recalc_idn();
  return $tree_xhtml->__get_all_bloc_min();
 }

//renvoie un tableau avec
//$array["all_blocs"]=array(id=>bloc);
//$array["all_const_mfm"]=array(id=>const_mfm);
//ou pour un id, on a la correspondance dans les deux tableaux entre le bloc et sa const_mfm

 public function __get_all_const_mfm($_all_bloc=null){
  if($_all_bloc==null) $_all_bloc=self::__get_all_blocs();
  $all_const_mfm=array();
  foreach($_all_bloc as $bloc){
    $all_const_mfm[]=$bloc->__get_const_mfm();
  }
  //$all_bloc=self::__get_all_blocs();
  return array("all_bloc"=>$_all_bloc,"all_const_mfm"=>$all_const_mfm);
 }

//pour chaque bloc généré, on compte sa fréquence
//la méthode est à refaire car la double boucle est de trop : ne pas compter ce qui a déjà compté !

 public function __get_tab_frequence_bloc(){
  $all_const_mfm=self::__get_all_const_mfm();
//  print_r($all_const_mfm);
//  die();
  $array_freq=array();
  //print_r($all_const_mfm);
  foreach($all_const_mfm["all_const_mfm"] as $id=>$const_mfm){
   $array_freq[$id]=0;
   foreach($all_const_mfm["all_const_mfm"] as $id_bis=>$const_mfm_bis){
     if(Tool_array::array_equal($const_mfm,$const_mfm_bis)) $array_freq[$id]++;
   }
  }
  return $array_freq;
 }

//révèle le corps de texte (cdt) du document en fonction du classement des blocs :
//le cdt peut être désigné par les critères suivant :
//la connexité
//la fréquence
//-->la connexité devrait suffir : donc il doit être possible de trouver plusieurs mfm différentes se référant au cdt
//---->avoir plusieurs cdt modifie la chaine de traitement : pour le moment, un seul cdt
 public function __get_mfm_cdt($_all_bloc=null){
  $all=self::__get_all_const_mfm($_all_bloc);
  $all_consts=$all["all_const_mfm"];
  $all_blocs=$all["all_bloc"];

//  print_r($all_consts);
//  die();
  $mt="";
  $cpt=0;
  $cpt_res=$res=$cpt_diff_mfm=$cpt_diff_ids_mfm=array();

//on sélectionne tout d'abord les mfms connexes
  foreach($all_blocs as $id=>$bloc){
   //on effectue ici un classement des mfms par fréquence :
   //  cpt_diff_ids_mfm=array($id_const_mfm);
   if(!in_array($all_consts[$id],$cpt_diff_mfm)){
    $cpt_diff_mfm[$id]=$all_consts[$id];
    $unique=$id;
   }
   else{
     foreach($cpt_diff_mfm as $id_unique=>$const_mfm_unique)
       if($const_mfm_unique==$all_consts[$id])
         $unique=$id_unique;
   }
   array_push($cpt_diff_ids_mfm,$unique);

   if($mt=="")$mt=$all_consts[$id];
   else{
    if($all_consts[$id]==$mt){
     if(!in_array($mt,$res)){
      $res[$cpt]=$mt;
      $cpt_res[$cpt]=2;
      $cpt++;
     }
     else{foreach($res as $id=>$mfm) if($mfm==$mt)$cpt_res[$id]++;}
    }
    else{$mt=$all_consts[$id];}
   }
  }

  if(count($cpt_res)>0){ //si une mfm est contigue
   $id_max=0;
   $cpt_max=0;
   foreach($cpt_res as $id=>$cpt){
    if($cpt>=$cpt_max){
     $id_max=$id;
     $cpt_max=$cpt;
    }
   }
//   print_r(array($res[$id_max]));
//  die();
   return array($res[$id_max]);
  }
  else{ //s'il n'y a pas de mfm contigue
   $res=array_count_values($cpt_diff_ids_mfm);
   $cpt_temp=0;
   $cdt=array();
   foreach($res as $id=>$cpt){
    if($cpt>$cpt_temp){
     $cpt_temp=$cpt;
     $cdt=array($all_consts[$id]);
    }
   }
   return $cdt;
  }
 }

//A chaque bloc, on associe sa profondeur ==> (bloc,profondeur)

 public function __get_structure_aux($_all_bloc=null){
  $all=self::__get_all_const_mfm($_all_bloc);
  $all_cdt=self::__get_mfm_cdt($_all_bloc);

  $pile=$res=array();

  foreach($all["all_bloc"] as $id=>$bloc){
   $const_mfm=$all["all_const_mfm"][$id];
   if(in_array($const_mfm,$all_cdt)) $res[]=array(0=>$bloc,1=>count($pile));
   else{
    if(in_array($const_mfm,$pile))
      $pile=Tool_array::array_depiler($pile,$const_mfm);
    $res[]=array(0=>$bloc,1=>count($pile));
    $pile[]=$const_mfm;
   }
  }
  return $res;
 }

//Une fois que l'on a l'association (bloc,profondeur), on construit la structure du document

 public function __get_structure($all_bloc=null,$_cdt=null){
  $all=self::__get_all_const_mfm($all_bloc);
  $all_deep=self::__get_structure_aux($all_bloc);
  $all_consts=$all["all_const_mfm"];
  $all_blocs=$all["all_bloc"];

  $old_deep=-1;
  $tampon=array();

  $tag_document=new Tag_document();
  $tree_document=new Tree_xhtml($tag_document);
  $structure_document=new Structure($tree_document);
  $tampon[-1]=$structure_document; //structure qui sera renvoyée

  foreach($all_deep as $unit){
   $curent_bloc=$unit[0]; $curent_deep=$unit[1];
   $structure=new Structure($curent_bloc);

   if($old_deep==$curent_deep){
    if(array_key_exists($old_deep,$tampon)){
     $tampon[$old_deep-1]->__add_tree($tampon[$old_deep]);
     $tampon[$old_deep]=$structure;
    }
    else{$tampon[$curent_deep]=$structure;}
   }

   if($old_deep>$curent_deep){
    $cpt_deep=$old_deep;
    while($cpt_deep>=$curent_deep){
     $tampon[$cpt_deep-1]->__add_tree($tampon[$cpt_deep]);
     unset($tampon[$cpt_deep]);
     $cpt_deep--;
    }
    $tampon[$curent_deep]=$structure;
   }

   else{$tampon[$curent_deep]=$structure;}
   $old_deep=$curent_deep;
  }

  $max=max(array_keys($tampon));
  while($max>-1){
   $tampon[$max-1]->__add_tree($tampon[$max]);
   $max--;
  }

  return $tampon[-1];
 }





 public function __tostring_html_indent($_flag=true,$_deep=0){
  $value=$this->__get_value();
  if($value instanceof Tree){$html_value=$value->__tostring_html();}
  else $html_value="";
  $margin_left=$_deep*15;
  $array_tree=$this->__get_array_tree();
  $tmp="";
  foreach($array_tree as $id_tree=>$tree){
   $tmp.=$tree->__tostring_html_indent(false,$_deep+1);
   $_y_cpt++;
  }
  if($_flag)
  return "\n<document>\n<bloc class=\"bloc\">\n$tmp\n</bloc>\n</document>\n";
  else return "
   <div class=\"bloc\" style=\"border:1px dashed black;margin:2px 2px 5px {$margin_left}px;\">$html_value</div>
   $tmp
  ";
 }


}

?>
