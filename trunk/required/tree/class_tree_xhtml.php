<?php
mb_internal_encoding("UTF-8");

class Tree_xhtml extends Tree{

//***************************************************************************
//***************************************************************************
// accesseurs
//***************************************************************************
//***************************************************************************

 
 public function __traverse_deep_strlen(){
  ($this->value instanceof tag_str) ? $res=array($this->__get_strlen()) : $res=array();
  foreach($this->__get_array_tree() as $id_tree=>$tree) $res=array_merge($res,$tree->__traverse_deep_strlen());
  return $res;
 }

 public function __get_str(){
  ($this->value instanceof tag_str) ? $res=$this->value->__get_array_attributes() : $res="";
  foreach($this->__get_array_tree() as $id_tree=>$tree) $res.=" ".$tree->__get_str()." ";
  return $res;
 }

 public function __get_strlen(){
  ($this->value instanceof tag_str) ? $res=mb_strlen($this->value->__get_array_attributes()) : $res=0;
  foreach($this->__get_array_tree() as $id_tree=>$tree) $res+=$tree->__get_strlen();
  return $res;
 }

 public function __get_all_bloc_min(){
  if($this->__is_bloc_min()){
//   if($this->__has_separator()) return $this->__clean_separator_tag();
//   else return array($this);
    return array($this);
  }
  $res=array();
  foreach($this->array_tree as $struct)
    $res=array_merge($res,$struct->__get_all_bloc_min());
  return $res;
 }

 public function __get_const_mfm(){
  $last=$this->__get_last_str_mfm();
  $first=$this->__get_first_str_mfm();

  $array=array_intersect($last,$first);
//  print_r($first);
//  print_r($last);
//  echo $this;
//  echo "\n\n\n";
  $res=array();
  foreach($array as $elt) $res[]=$elt; //pour être certain que les clefs soient numérotées de 0 à taille(array)-1
//  print_r($res);
  return $res;
 }



 public function __get_first_str_mfm($_array_mfm=array()){
  if($this->value instanceof Tag_str){
   //echo $this->value;
   //echo "\n";
   if($this->value->__is_empty()) return $_array_mfm;
//   elseif(!mb_eregi("[[:alnum:]]",$this->value->__get_array_attributes())){
   elseif(!mb_eregi("[[^\s]]",$this->value->__get_array_attributes())){
    return $_array_mfm;
   }
   else $_array_mfm[]=$this->value->__get_name();
  }
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_mfm']) or
     in_array($this->value->__get_name(),$GLOBALS['global_array_tag_bloc'])){
    $_array_mfm[]=$this->value->__get_name();
  }
  foreach($this->array_tree as $tree){
   $res=$tree->__get_first_str_mfm($_array_mfm);
   if(in_array("str",$res) or in_array("hr",$res)) return $res;
  }
  return $_array_mfm;
 }


 public function __get_last_str_mfm($_array_mfm=array()){
  if($this->value instanceof Tag_str){
   if($this->value->__is_empty()) return $_array_mfm;
//   elseif(!mb_eregi("[[:alnum:]]",$this->value->__get_array_attributes())){
   elseif(!mb_eregi("[[^\s]]",$this->value->__get_array_attributes())){
    return $_array_mfm;
   }
   else $_array_mfm[]=$this->value->__get_name();
  }
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_mfm']) or
     in_array($this->value->__get_name(),$GLOBALS['global_array_tag_bloc'])){
    $_array_mfm[]=$this->value->__get_name();
  }
  for($i=count($this->array_tree)-1;$i>=0;--$i){
    $res=$this->array_tree[$i]->__get_last_str_mfm($_array_mfm);
    if(in_array("str",$res) or in_array("hr",$res)) return $res;
  }
  return $_array_mfm;
 }

/*
 public function __get_last_str_mfm(){
  $reverse=$this->__get_reverse();
  return $reverse->__get_first_str_mfm();
 }
*/
 public function __get_all_different_tag($_tampon=array()){
  if(!in_array($this->value->__get_name(),$_tampon))
    array_push($_tampon,$this->value->__get_name());
  foreach($this->array_tree as $tree){
    $res=$tree->__get_all_different_tag($_tampon);
    foreach($res as $tag_name){
      if(!in_array($tag_name,$_tampon)) array_push($_tampon,$tag_name);
    }
  }
  return $_tampon;
 }
//***************************************************************************
//***************************************************************************
// prédicats
//***************************************************************************
//***************************************************************************

 public function __has_separator(){
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_separator']))
    return true;
  else
    foreach($this->array_tree as $struct)
      if($struct->__has_separator()) return true;
  return false;

 }
 public function __is_separator(){
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_separator'])) return true;
  else return false;
 }

 public function __has_bloc(){
  foreach($this->array_tree as $tree) if($tree->__has_bloc_aux()) return true;
  return false;
 }

 public function __has_bloc_aux(){
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_bloc']))
    return true;
  else
    foreach($this->array_tree as $struct) if($struct->__has_bloc()) return true;
  return false;
 }

 public function __is_bloc_min(){
  if(in_array($this->value->__get_name(),$GLOBALS['global_array_tag_bloc']) and !$this->__has_bloc()) return true;
  else return false;
 }

//***************************************************************************
//***************************************************************************
// affichages
//***************************************************************************
//***************************************************************************
/*
 public function __tostring_html(){
  $tag=$this->value;
  $tag_name=$tag->__get_name();
  if($tag instanceof Tag_str) return $tag->__get_array_attributes();
  else{
   $res="";
   foreach($this->array_tree as $tree){$res.=$tree->__tostring_html();}
   $res="<$tag_name>$res</$tag_name>";
   return $res;
  }
 }
 
*/ 
//***************************************************************************
//***************************************************************************
// méthodes
//***************************************************************************
//***************************************************************************

 public function __clean_separator_tag(){
  if($this->__is_separator()) return array(null);
  $clone=new tree_xhtml($this->value,array());
  $array_clone=array($clone);
  foreach($this->array_tree as $tree){
    if(!$tree->__has_separator()){
      $array_clone[count($array_clone)-1]->__add_tree($tree);
      $flag=false; 
    }
    else{
      $res=$tree->__clean_separator_tag();
      foreach($res as $new){
        if($new!=null){
          $array_clone[count($array_clone)-1]->__add_tree($new);
        }
        $new_clone=new tree_xhtml($this->value,array());
        $array_clone[]=$new_clone;        
      }
      $flag=true;
    }
  }
  if($flag) return array_slice($array_clone,0,count($array_clone)-1);
  else return $array_clone ;
 }

}

?>
