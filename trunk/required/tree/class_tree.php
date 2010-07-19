<?php
class Tree implements RecursiveIterator{

 protected $value;
 protected $array_tree;
 protected $parent;
 protected $idn;
 protected $n;

 public function __construct($_value,$_children=Null,$_idn=Null,$_parent=Null){
  $this->value=$_value;
  $this->idn=$_idn;
  $this->parent=$_parent;
  $this->array_tree=new recursiveArrayIterator(array());
  $this->n=1;
  if($_children!=Null){
   foreach($_children as $ctree){
    $this->array_tree[]=$ctree;
    $this->n++;
   }
  }
 }

 public function __clone(){
   if(is_object($this->value)){$this->value=clone $this->value;}
   else $this->value=$this->value;
   $this->idn=$this->idn;
   $this->parent=$this->parent;
   $this->n=$this->n;
   $tmp_array_tree=new recursiveArrayIterator(array());
   foreach($this->array_tree as $tree){
    //$tree_clone=clone $tree;
    $tmp_array_tree[]=clone $tree;
   }
   $this->array_tree=$tmp_array_tree;
 }

 public function __clone_clean(){
   $tree_clean=new tree(null);
   foreach($this->array_tree as $son){
    $son_clean=$son->__clone_clean();
    $tree_clean->__add_tree($son_clean);
   }
   return $tree_clean;
 }

 public function __clone_idn(){
   $tree_clean=new tree($this->__get_idn());
   foreach($this->array_tree as $son){
    $son_clean=$son->__clone_idn();
    $tree_clean->__add_tree($son_clean);
   }
   return $tree_clean;  
 }
/*
 public function __recalc_idn(){
    $test=new RecursiveIteratorIterator($this,RecursiveIteratorIterator::SELF_FIRST);
    $this->idn=$i=0;
    foreach($test as $tree) $tree->__set_idn(++$i);
 }
*/


//recursiveiterator
 public function current(){return current($this->array_tree);}
 public function key(){return key($this->array_tree);}
 public function next(){return next($this->array_tree);}
 public function valid() {return ($this->current() !== false);}
 public function rewind(){reset($this->array_tree);}
 public function getChildren(){return $this->current();}
 public function hasChildren(){return (count($this->__get_array_tree())>0);}
// fin recursiveiterator

 public function __get_value(){return $this->value;}
 public function __get_idn(){return $this->idn;}
 public function __get_array_tree(){return $this->array_tree;}
 public function __get_n(){return $this->n;}
 public function __get_nb_children(){return count($this->array_tree);}
 public function __get_parent(){return $this->parent;}

 public function __get_clone_array_tree(){
  $clone=clone $this;
  return $clone->__get_array_tree();
 }

 public function __get_forest(){
  return new forest($this->array_tree);
 }

 public function __get_forest_sons(){
  return new forest($this->array_tree);
 }
  
 public function __get_width(){
  $traverse=$this->__traverse_large();
  $count=array();
  foreach($traverse as $id=>$array_value){$count[$id]=count($array_value);}
  return max($count);
 }

 public function __get_height(){
  $array_height=array(0);
  foreach($this->array_tree as $tree) $array_height[]=$tree->__get_height();
  return max($array_height)+1;
 }

 public function __get_reverse(){
  $tree=new tree($this->value);
  for($i=count($this->array_tree)-1;$i>=0;--$i){
    $tree->__add_tree($this->array_tree[$i]->__get_reverse());
  }
  return $tree;
 }

/*
 public function __get_reverse(){
  $copy=clone $this;
  $copy->__reverse();
  return $copy;
 }
*/

//traverse
 public function __traverse_deep(){
  $res=array($this->value);
  foreach($this->array_tree as $tree){
    $res=array_merge($res,$tree->__traverse_deep());
  }
  return $res;
 }

 public function __traverse_large(){
  $traverse=$this->__traverse_large_aux();
  $res=array();
  foreach($traverse as $id=>$unit){
   if(array_key_exists($unit[0],$res)) array_push($res[$unit[0]],$unit[1]);
   else $res[$unit[0]]=array($unit[1]);
  }
  return $res;
 }

 public function __traverse_large_aux($_deep=0){
  $unit0=array($_deep,$this->value);
  $tmp=array($unit0);
  foreach($this->array_tree as $tree){
    $tmp=array_merge($tmp,$tree->__traverse_large_aux($_deep+1));
  }
  return $tmp;
 }
//fin traverse

 public function __set_value($_value){$this->value=$_value;}
 public function __set_array_tree($_array_tree){
  $this->array_tree=new recursiveArrayIterator($_array_tree);
  //foreach($_array_tree as $val=>$tree) $this->array_tree[$val]=$tree;
 }
 public function __set_n($_n){$this->n=$_n;}
 public function __set_idn($_idn){$this->idn=$_idn;}

 public function __add_tree($_tree){$this->array_tree[]=$_tree;}
 public function __add_array_tree($_array_tree){
  foreach($_array_tree as $tree) $this->array_tree[]=$tree;
 }

 public function __destruct(){
  unset($this->parent);
  unset($this->array_tree);
 }

 public function __reverse(){
  foreach($this->array_tree as $id=>$tree) $tree->__reverse();
  $array_tree_temp=$this->array_tree->getArrayCopy();
  $array_tree_temp=array_reverse($array_tree_temp);
  $this->array_tree=new recursiveArrayIterator($array_tree_temp);
 }


 public function __tostring(){
  if(count($this->array_tree)>0)
    return "$this->idn:{$this->array_tree[0]->__get_idn()}.{$this->array_tree[count($this->array_tree)-1]->__get_idn()}";
  else return "$this->idn:N";
 }


 public function __recalc_idn(){
    $test=new RecursiveIteratorIterator($this,RecursiveIteratorIterator::SELF_FIRST);
    $this->idn=$i=0;
    foreach($test as $tree) $tree->__set_idn(++$i);
 }

 public function __is_same($_tree){
   $other_array_tree=$_tree->array_tree;
   if(count($this->array_tree)!=count($other_array_tree)) return false;
   foreach($this->array_tree as $id=>$son){
    if(!$son->__is_same($other_array_tree[$id])) return false;
   }
   return true;
 }

}

class Forest implements RecursiveIterator{
 private $array_root;
 private $n;
 private $heavy;

 public function __construct($_array_root=array(),$_n=0){
  $this->array_root=new recursiveArrayIterator(array());
  foreach($_array_root as $root) $this->array_root[]=$root;
  $this->n=$_n;
  if(!$this->is_empty()){
   foreach($this->array_root as $root) $tmp[]=$this->__get_n();
   $this->heavy=array_keys($tmp,max($tmp));
  }
 }

 public function __clone(){
  $this->n=$this->n;
  $this->heavy=$this->heavy;
  $tmp_array_root=new recursiveArrayIterator(array());
  foreach($this->array_root as $tree){
   $tree_clone=clone $tree;
   $tmp_array_root[]=$tree_clone;
  }
  $this->array_root=$tmp_array_root;
 }

 public function __get_clone_array_root(){
  $clone=clone $this;
  return $clone->__get_array_root();
 }

 public function __get_copy_array_root(){
  $array_root=$this->__get_clone_array_root();
  return $array_root->getArrayCopy();
 }

 public function __get_root_right(){
  return $this->array_root[count($this->array_root)-1];   
 }

//recursiveiterator
 public function current(){return current($this->array_root);}
 public function key(){return key($this->array_root);}
 public function next(){return next($this->array_root);}
 public function valid() {return ($this->current() !== false);}
 public function rewind(){reset($this->array_root);}
 public function getChildren(){return $this->current();}
 public function hasChildren(){return (count($this->__get_array_root())>0);}
//fin recursiveiterator

 public function __get_n(){return $this->n;}
 public function __get_array_root(){return $this->array_root;}
 public function is_empty(){return 0==count($this->array_root);}

 public function __destroy(){
  $iterator=new RecursiveIteratorIterator($this,RecursiveIteratorIterator::CHILD_FIRST);
  foreach($iterator as $tree) $tree->__free();
  unset($this->array_root);
 }


 public function __tostring(){
  $s = "";
  foreach($this->array_root as $root) $s.="$root,";
  return "(".rtrim($s,",").")";
 }

 public function __del_left(){
   if(count($this->array_root)>0){
    $copy_array_roots=$this->__get_copy_array_root();
    $root_left=array_shift($copy_array_roots);
    $clone_children_left = $root_left->__get_clone_array_tree();
    $new_roots=Tool_array::array_merge($clone_children_left,$copy_array_roots);
    return new Forest($new_roots,$this->n-1);
   }
 }

 public function __del_right(){
   if(count($this->array_root)>0){
    $copy_array_roots=$this->__get_copy_array_root();
    $root_right=array_pop($copy_array_roots);
    return new Forest(
     Tool_array::array_merge($copy_array_roots,$root_right->__get_array_tree()),
     $this->n-1
    );
   }
 }
/*
 public function __match_left(){
   if(count($this->array_root)>0){
    $root_left = $this->array_root[0];
    $children_left = clone $root_left->__get_array_tree();
    $copy_roots=$this->array_root->getArrayCopy();
    $all_but_first=array_slice($copy_roots,1);
    $res=array(new Forest($all_but_first,$this->n - $root_left->__get_n()));
    $res[]=new Forest($children_left,$root_left->__get_n() - 1);
    return $res;
   }
 }
*/
 public function __match_right(){
   if(count($this->array_root) > 0){
    $copy_roots = $this->__get_copy_array_root();
    $root_right = array_pop($copy_roots);
    return array(
     new Forest($copy_roots,$this->n - $root_right->__get_n()),
     new Forest($root_right->__get_array_tree(),$root_right->__get_n() - 1)
    );
   }
 }

 public function __change_root(){}

 public function __recalc_n(){
    $test=new RecursiveIteratorIterator($this,RecursiveIteratorIterator::CHILD_FIRST);
    foreach($test as $tree){
      $array_tree=$tree->__get_array_tree();
      if(count($array_tree)==0) $tree->__set_n(1);
      else{
        $sum=0;
        foreach($array_tree as $son) $sum+=$son->__get_n();
        $tree->__set_n(1+$sum);
      }
    }
 }

 public function __recalc_idn(){
    $test=new RecursiveIteratorIterator($this,RecursiveIteratorIterator::SELF_FIRST);
    $i=0;
    foreach($test as $tree) $tree->__set_idn($i++);
 }

 public function __is_same(Forest $_forest){
   $other_array_root=$_forest->__get_array_root();
   foreach($this->array_root as $id=>$root){
     if(!$root->__is_same($other_array_root[$id])) return false;
   }
   return true;
 }

}

 class TED{
    protected $array_res=array();
    protected $array_trace=array();
    protected $cost_add=1;
    protected $cost_replace=1;
    protected $cost_del=1;

    public function __construct(){}

    public function __get_array_res(){return $this->array_res;}
    public function __get_array_trace(){return $this->array_trace;}
 }

 class TED_shasha extends TED{

    public function __init($_f,$_g){ //$_f et $_g deux forets
      $f_str=$_f->__tostring();
      $g_str=$_g->__tostring();

      if(!isset($this->array_trace[$f_str][$g_str]))
        $this->array_trace[$f_str][$g_str] = "";
      
      if($_f->is_empty() and $_g->is_empty()){
        $this->array_res[$f_str][$g_str] = 0;
        $this->array_trace[$f_str][$g_str] = "S";
      } 
      elseif($_f->is_empty()){
        $g_del_right=$_g->__del_right();
        $g_del_right_str=$g_del_right->__tostring();
        if(!isset($this->array_res[$f_str][$g_del_right_str])){
          $this->__init($_f,$g_del_right);
        }
        $this->array_res[$f_str][$g_str]=$this->array_res[$f_str][$g_del_right_str]+1;
        $this->array_trace[$f_str][$g_str].="-- ";
      }
      elseif($_g->is_empty()){
        $f_del_right=$_f->__del_right();
        $f_del_right_str=$f_del_right->__tostring();
        if(!isset($this->array_res[$f_del_right_str][$g_str])){
          $this->__init($f_del_right,$_g);
        }
        $this->array_res[$f_str][$g_str]=$this->array_res[$f_del_right_str][$g_str]+1;
        $this->array_trace[$f_str][$g_str].="| ";
      }
      else{
        $f_del_right=$_f->__del_right();
        $f_del_right_str=$f_del_right->__tostring();
        $g_del_right=$_g->__del_right();
        $g_del_right_str=$g_del_right->__tostring();
        if(!isset($this->array_res[$f_str][$g_del_right_str])){
            $this->__init($_f,$g_del_right);
        }
        if(!isset($this->array_res[$f_del_right_str][$g_str])){
            $this->__init($f_del_right,$_g);
        }
        list($f3,$f4)=$_f->__match_right();
        list($g3,$g4)=$_g->__match_right();
        $f3_str=$f3->__tostring();
        $f4_str=$f4->__tostring();
        $g3_str=$g3->__tostring();
        $g4_str=$g4->__tostring();
        if(!isset($this->array_res[$f3_str][$g3_str])) $this->__init($f3,$g3);
        if(!isset($this->array_res[$f4_str][$g4_str])) $this->__init($f4,$g4);

        $f_root_right=$_f->__get_root_right();
        $g_root_right=$_g->__get_root_right();

        if($f_root_right->__get_value()==$g_root_right->__get_value()){
          $compare_val=0;
        }
        else{
          $compare_val=$this->cost_replace;
        }

        //$compare_val = 1 - ($f_root_right->__get_value()==$g_root_right->__get_value());
        $cout_remplacement=$this->array_res[$f3_str][$g3_str]+$this->array_res[$f4_str][$g4_str]+$compare_val;
          
        $min=min(
          $this->array_res[$f_str][$g_del_right_str]+1, //insert
          $this->array_res[$f_del_right_str][$g_str]+1, //suppro
          $cout_remplacement //renom
        );

        $this->array_res[$f_str][$g_str]=$min;

        if($min==$this->array_res[$f_str][$g_del_right_str]+1){
          $this->array_trace[$f_str][$g_str].="| ";
        }
        if($min==$this->array_res[$f_del_right_str][$g_str]+1){
          $this->array_trace[$f_str][$g_str].="-- ";
        }
        if($min==$cout_remplacement){
          $this->array_trace[$f_str][$g_str].="\ ";
        }


      }
    }

 }

?>
