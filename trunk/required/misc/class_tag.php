<?php
$global_array_tag_bloc=array("p","h1","h2","h3","h4","h5","div","ul","ol","table","hr");
$global_array_tag_strless=array("hr","br");
$global_array_tag_marque=array("a","br","guill","paren","croch","img","sup","ins","li","delim","i","em","b","strong","u");
$global_array_tag_mfm=array("i","em","b","strong","u");
$global_array_tag_meta=array("head","creator");
$global_array_tag_separator=array("br");

class Tag{
 protected $name;
 protected $array_attributes;

 public function __construct($_name=null,$_array_attributes=array()){
  $this->name=$_name;
  $this->array_attributes=$_array_attributes;
 }

 public function __get_name(){return $this->name;}
 public function __get_array_attributes(){return $this->array_attributes;}

 public function __set_name($_name){$this->name=$_name;}
 public function __set_array_attributes($_array_attributes){
    $this->array_attributes=$_array_attributes;
 }

 public function __set_from_token_xml($_xml_token){
  //print_r($_xml_token);
  if(array_key_exists("attributes",$_xml_token)){
    $this->array_attributes=$_xml_token["attributes"];
  }
  if(array_key_exists("tag",$_xml_token)){
    $this->name=strtolower($_xml_token["tag"]);
  }
 }
 
 public function __tostring(){return $this->name;}
}

class Tag_document extends Tag{
 function __construct($_name="document",$_array_attributes=array()){
  $this->__set_name($_name);
  $this->__set_array_attributes($_array_attributes);
 }
}

class Tag_str extends Tag{
 function __construct($_str){
  $this->__set_name("str");
  $this->__set_array_attributes($_str);
 }

 function __is_empty(){return ($this->array_attributes=="") ? true : false;}
}

class Tag_cdata extends Tag{
 function __construct($_str){
  $this->__set_name("cdata");
  $this->__set_array_attributes($_str);
 }
}

class Tag_div extends Tag{
 function __construct($_name="div",$_array_attributes=array()){
  $this->__set_name($_name);
  $this->__set_array_attributes($_array_attributes);
 }
}

?>
