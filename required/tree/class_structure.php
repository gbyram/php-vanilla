<?php
class Structure extends Tree{

/*
 public function __get_clone_rename_mfm($array_mfm){
   $tree_clean=new tree($this->__get_idn());
   foreach($this->array_tree as $son){
    $son_clean=$son->__clone_idn();
    $tree_clean->__add_tree($son_clean);
   }
   return $tree_clean;
 }
*/
 public function __get_signature(){
  $array_tree=$array_tampon=array();
  foreach($this->array_tree as $tree){
    $value=$tree->__get_value();
    $height=$tree->__get_height();
    $mfm=$value->__get_const_mfm();
    if($height==1){
      if(count($array_tampon)>0){
        $last_tree=$array_tampon[count($array_tampon)-1];
        $last_mfm=$last_tree->__get_const_mfm();
        if($last_mfm==$mfm) $array_tampon[]=$tree->__get_value();
        else{
          $array_tree[]=$this->__get_standart_signature($array_tampon);
          $array_tampon=array($tree->__get_value());
        }
      }
      else $array_tampon[]=$tree->__get_value();
    }
    else{
      if(count($array_tampon)>0){
        $array_tree[]=$this->__get_standart_signature($array_tampon);
        $array_tampon=array();
      }
      $signature_tree=$tree->__get_signature();
      $new_value=new Tree_xhtml(new Tag_div(),array($signature_tree->__get_value()));
      $signature_tree->__set_value($new_value);
      $new_const_mfm=$new_value->__get_const_mfm();
      if(count($array_tree)>0){
        $last_value=$array_tree[count($array_tree)-1]->__get_value();
        $last_const_mfm=$last_value->__get_const_mfm();
        $last_height=$array_tree[count($array_tree)-1]->__get_height();
        if($last_height==1 and $last_const_mfm==$new_const_mfm){
          $add=$signature_tree->__get_array_tree();
          $val=new Tree_xhtml(new Tag_div());
          $val->__add_array_tree($last_value->__get_array_tree());
          $val->__add_array_tree($new_value->__get_array_tree());
          $array_tree[count($array_tree)-1]->__add_array_tree($add);
          $array_tree[count($array_tree)-1]->__set_value($val);
        }
        else $array_tree[]=$signature_tree;
      }
      else $array_tree[]=$signature_tree;
    }
  }
  if(count($array_tampon)>0){
    $array_tree[]=$this->__get_standart_signature($array_tampon);
  }

  return new Structure($this->value,$array_tree);
 }

 private function __get_standart_signature($_tampon=array()){
  $tree_xhtml=new Tree_xhtml(new Tag_div(),$_tampon);
  return new Structure($tree_xhtml,array());
 }







}

?>
