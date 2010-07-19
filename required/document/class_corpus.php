<?php

class Corpus{
 protected $id;
 protected $array_doc;

 public function __construct($_id=0,$_array_doc=array()){
  $this->id=$_id;
  $this->array_doc=$_array_doc;
 }

 public function __get_id(){return $this->id;}
 public function __get_array_doc(){return $this->array_doc;}
 public function __get_doc($_id){return $this->array_doc[$_id];}

 public function __push_document(Document $doc,$_id=null){
  if($_id==null)$this->array_doc[]=$doc;
  else $this->array_doc[$_id]=$doc;
 }

 public function __init($_path){
   $this->id=$_path;
   $all_files=Tool_dir::dir_getAll($_path);
   foreach($all_files as $pathfile){
    $document=new document();
    $document->__init($pathfile);
    $this->array_doc[]=$document;
   }
 }

}

class Tool_corpus{

 public static function mtg_get_all_ids($_dir){
   return self::get_all_ids($_dir,'/[^_\/]+_/');
 }

 public static function europa_get_all_ids($_dir){
   return self::get_all_ids($_dir,'/celex_IP-[0-9]+-[0-9]+./');
 }

 public static function get_all_ids($_dir,$_rule){
   $array_path=Tool_dir::dir_getAll($_dir);
   $all_ids=array();
   foreach($array_path as $path){
    if(preg_match($_rule,$path,$id)){
     if(!in_array($id[0],$all_ids)) array_push($all_ids,$id[0]);
    }
   }
   return $all_ids;
 }

 public static function get_corpus_regexp($_dir,$_id){
  $corpus=new corpus();
  $res=tool_dir::dir_regexp($_dir,$_id);

  foreach($res as $array_type){
   foreach($array_type as $type=>$path){
    $document_xml=new document_xml();
    $document_xml->__init("$path");
    //$id_doc=mb_strcut($document_xml->__get_id(),-6,2);
    $corpus->__push_document($document_xml);
   }
  }
  return $corpus;

 }

 public static function get_unified_europa($_dir,$_array_lg){
  $array_ids=self::europa_get_all_ids($_dir);
  $cpt_multidoc=0;
  $multidoc_lg=$array_lg=array();
  foreach($array_ids as $id_europa){
    $corpus=self::get_corpus_regexp($_dir,$id_europa);
    $array_doc=$corpus->__get_array_doc();
    ++$cpt_multidoc;
    foreach($array_doc as $document){
      $id_doc=mb_strcut($document->__get_id(),-6,2);
      $multidoc_lg[$id_europa][$id_doc]=1;
      ++$array_lg[$id_doc];
    }
  }

  $res=array();
  foreach($multidoc_lg as $multi=>$array_lg){
    $flag=true;
    foreach($_array_lg as $lg){
      if(!array_key_exists($lg,$array_lg)){
        $flag=false;
        break;
      }
    }
    if($flag)$res[]=$multi;
  }

  return $res;
 }
 
}

?>
