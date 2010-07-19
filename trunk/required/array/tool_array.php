<?php

class Tool_array{

 public static function pretty_print(&$_array){
   $print = "";
   foreach($_array as $k=>$v){
     $print .= "  '$k'=>'$v',\n";
   }

   $print = rtrim($print,",\n");
   $print = "array(\n$print\n);";
   return $print;
 }

 public static function get_nieme(&$_array,$_n){
   foreach($_array as $k=>$v){
     if(--$_n==0) return $v;
   }
 }

 public static function array_neg($_array){
  $res=array();
  foreach($_array as $id=>$val) $res[$id]=-$val;
  return $res;
 }

 public static function printr($_a){
  return "[".implode(",",$_a)."]";
 }

 public static function rsort_count($_a,$_b){
   return -(self::count_recursive($_a)-self::count_recursive($_b));
 }

 public static function array_merge(){
  $res=func_get_arg(0);
  $num_args=func_num_args();
  for($i=1;$i<$num_args;$i++){
    $arg=func_get_arg($i);
    foreach($arg as $elt) $res[]=$elt;
  }
  return $res;
 }

 public static function array_merge_key(){
  $res=func_get_arg(0);
  $num_args=func_num_args();
  for($i=1;$i<$num_args;$i++){
    $arg=func_get_arg($i);
    foreach($arg as $k=>$elt) $res[$k]=$elt;
  }
  return $res;
 }

 public static function array_equal($_array1,$_array2){
  if(count($_array1)!=count($_array2)) return false;
  foreach($_array1 as $val1){
   if(!in_array($val1,$_array2)) return false;
  }
  return true;
 }

 public static function array_depiler($_array,$_value,$_direction="bottom2top"){
  switch($_direction){
   case "top2bottom" : $_array=array_reverse($_array,true); break;
   case "bottom2top" : break;
   default : break;
  }

  $array_temp=array();
  foreach($_array as $key=>$value){
   if($_value!=$value) $array_temp[$key]=$value;
   else return $array_temp;
  }
  return $array_temp;
}



 public static function array_values_recursive($_array){
  $res=array();
  foreach($_array as $value){
    (is_array($value)) ? $res=array_merge($res,self::array_values_recursive($value)) : $res[]=$value;
  }
  return $res;
 }

 public static function count_recursive($_array){
  $res=0;
  foreach($_array as $elt){
    if(is_array($elt))$res+=self::count_recursive($elt);
    else $res++;
  }
  return $res;
//  return count($_array,COUNT_RECURSIVE);
 }

 public static function access_dichotomie($_array,$_elt){
  $first=0;
  $last=count($_array)-1;
  while($first<=$last){
    $mid=round(($first+$last)/2,0);
    if($_elt>$_array[$mid]) $first=$mid+1;
    else ($_elt<$_array[$mid]) ? $last=$mid-1 : $first=$last+1;
  }
  $find = ($_elt==$_array[$mid]) ? 1 : 0;
  return array($mid,$find);
 }

 public static function access_dichotomie_round2lower($_array,$_elt){
   $res=Tool_array::access_dichotomie($_array,$_elt);
   if($res[1]) return $res;
   else return ($_array[$res[0]]<$_elt) ? $res : array($res[0]-1,$res[1]);
 }
/*
 public static function printr($_array){
    foreach($_array as $value){$res.=$value.",";}
    $res=rtrim($res,",");
    echo "[$res];";
 }
*/
}

/*
function array_values_recursive($_array){
 $res=array();
 foreach($_array as $value){
  if(is_array($value)) $res=array_merge($res,array_values_recursive($value));
  else $res[]=$value;
 }
 return $res;
}

function count_array_array($_array_array){
  return count($_array_array,COUNT_RECURSIVE);
// $cpt=0;
// foreach($_array_array as $array){$cpt+=count($array);}
// return $cpt;
}
*/
?>
