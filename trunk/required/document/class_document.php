<?php

class Document{
 protected $id;
 protected $container;

 public function __construct($_id="default",$_container=""){
  $this->id=$_id;
  $this->container=$_container;
 }

 public function __destruct(){
  unset($this->id);
  unset($this->container);  
 }

 public function __set_id($_id){$this->id=$_id;}
 public function __set_container($_container){$this->container=$_container;}

 public function __get_id(){return $this->id;}
 public function __get_container(){return $this->container;}

 public function __init($_path){
  $this->id=$_path;
  $this->container=tool_files::file_load($_path);
 }

 public function __tostring(){
  return (is_object($this->container)) ? $this->container->__tostring() : $this->container;
 }

}

?>
