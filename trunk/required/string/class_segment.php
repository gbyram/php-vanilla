<?php

 class Tool_segment{
  
/*
  public static function quicksort(&$_array_seg){
    $len = count($_array_seg);        
    if(!$len) return;// $_array_seg;
    $x = $y = array();
    for($i=1; $i < $len; $i++){
     if(self::usort_segment_new($_array_seg[$i],$_array_seg[0])<0)
        $x[]=$_array_seg[$i];
     else $y[]=$_array_seg[$i];
    }
    self::quicksort($x); 
    self::quicksort($y); 
    $_array_seg=Tool_array::array_merge($x,array($_array_seg[0]),$y);
  }
*/

  public static function usort_segment($_seg1,$_seg2){
    $leni=$_seg1->__get_len();
    $lenj=$_seg2->__get_len();
    $lenmin=min($leni,$lenj);
    $offseti=$_seg1->__get_offset();//+$lenmin;
    $offsetj=$_seg2->__get_offset();//+$lenmin;
    while($lenmin and
          ($_seg1->__get_char($offseti)==$_seg2->__get_char($offsetj))){
      $lenmin--;$offseti++;$offsetj++;
    }
    if(!$lenmin){return $leni-$lenj;}
    elseif($_seg1->__get_char($offseti) < $_seg2->__get_char($offsetj)){
     return -1;
    }
    else return 1;
  }

  public static function usort_segment_new($_seg1,$_seg2){
    $leni=$_seg1->__get_len();
    $lenj=$_seg2->__get_len();
    $commun_len=self::get_same_prefix($_seg1,$_seg2);
    $offseti=$_seg1->__get_offset()+$commun_len;
    $offsetj=$_seg2->__get_offset()+$commun_len;
    $lenmin=min($leni,$lenj)-$commun_len;
    if(!$lenmin) return $leni-$lenj;
    if($_seg1->__get_char($offseti) < $_seg2->__get_char($offsetj)){
     return -1;
    }
    else{return 1;}
  }

  public static function usort_segment_new2($_seg1,$_seg2){
    return strcmp($_seg1->__tostring(),$_seg2->__tostring());
  }

  public static function usort_rev_segment($_seg1,$_seg2){
   $leni=$_seg1->__get_len();
   $lenj=$_seg2->__get_len();
   $lenmin=min($leni,$lenj);
   $offseti=$leni+$_seg1->__get_offset()-1;
   $offsetj=$lenj+$_seg2->__get_offset()-1;
   while($lenmin and
         ($_seg1->__get_char($offseti)==$_seg2->__get_char($offsetj))){
    $lenmin--;$offseti--;$offsetj--;
   }
   if(!$lenmin){return $leni-$lenj;}
   else{
     return ($_seg1->__get_char($offseti)<$_seg2->__get_char($offsetj))? -1 : 1;
   }
  }

  public static function usort_rev_segment_new($_seg1,$_seg2){
    $leni=$_seg1->__get_len();
    $lenj=$_seg2->__get_len();
    $commun_len=self::get_same_suffix($_seg1,$_seg2);
    $offseti=$_seg1->__get_offset_end()-$commun_len-1;
    $offsetj=$_seg2->__get_offset_end()-$commun_len-1;
    $lenmin=min($leni,$lenj)-$commun_len;
    if(!$lenmin) return $leni-$lenj;
    
    if($_seg1->__get_char($offseti) < $_seg2->__get_char($offsetj)){
     return -1;
    }
    else return 1;
  }

  public static function usort_rev_segment_new2($_seg1,$_seg2){
    return strcmp($_seg1->__get_reverse_tostring(),$_seg2->__get_reverse_tostring());
  }

 public static function usort_array_rev_segment($_array_seg1,$_array_seg2){
//  return self::usort_rev_segment_new2($_array_seg1[0],$_array_seg2[0]);
//  return self::usort_rev_segment_new2($_array_seg1[0],$_array_seg2[0]);
  return self::usort_rev_segment($_array_seg1[0],$_array_seg2[0]);
 }


  public static function get_same_prefix($_segment1,$_segment2){
    $first=$min=0;
    $last=$max=min($_segment1->__get_len(),$_segment2->__get_len());
    $flag=false;
    while($first<=$last){
//      $mid=round(($first+$last)/2,0);
      if(!$flag) $mid=round($first+(($last-$first)/4),0);
      else $mid=round($first+((($last-$first)/4)*3),0);
      $prefix_seg1=$_segment1->__get_prefix_str($mid);
      $prefix_seg2=$_segment2->__get_prefix_str($mid);
      if((string)$prefix_seg1 !== (string)$prefix_seg2){
        if($mid<$max) $max=$mid;
        $last=$mid-1;
        $flag=false;
      }
      else{
        if($mid>$min) $min=$mid;
        $first=$mid+1;
        $flag=true;
      }
      if($max<=$min) return ($flag) ? $mid : $last;
//      echo "[$first $last - $min $mid $max]\n";
    }

   return ($flag) ? $mid : $last;
  }

 public static function get_same_prefix2($_segment1,$_segment2){
  $len=min($_segment1->__get_len(),$_segment2->__get_len());
  $o1=$_segment1->__get_offset(); $o2=$_segment2->__get_offset();
  for($i=0;$i<$len && ($_segment1->__get_char($i+$o1)==$_segment2->__get_char($i+$o2));$i++);
  return $i;
 }


  public static function get_same_suffix($_segment1,$_segment2){
    $first=$min=0;
    $last=$max=min($_segment1->__get_len(),$_segment2->__get_len());
    $flag=false;
//    echo "($_segment1)($_segment2)\n";
    while($first<=$last){
//      $mid=round(($first+$last)/2,0);
      if(!$flag) $mid=round($first+(($last-$first)/4),0);
      else $mid=round($first+((($last-$first)/4)*3),0);
      $suffix_seg1=$_segment1->__get_suffix_str($mid);
      $suffix_seg2=$_segment2->__get_suffix_str($mid);
      if((string)$suffix_seg1 !== (string)$suffix_seg2){
        if($mid<$max) $max=$mid;
        $last=$mid-1;
        $flag=false;
      }
      else{
        if($mid>$min) $min=$mid;
        $first=$mid+1;
        $flag=true;
      }
      if($max<=$min) break;
    }
//    if($flag) echo "   =>[".$_segment1->__get_suffix_str($mid)."][".$_segment2->__get_suffix_str($mid)."]\n\n";
//    else echo "   =>[".$_segment1->__get_suffix_str($last)."][".$_segment2->__get_suffix_str($last)."]\n\n";
   return ($flag) ? $mid : $last;
  }

 }



 function usort_arrray_segment($_array_seg1,$_array_seg2){
  return usort_segment($_array_seg1[0],$_array_seg2[0]);
 }

 function ursort_segment($_seg1,$_seg2){
  return -usort_segment($_seg1,$_seg2);
 }

 function usort_segment($_seg1,$_seg2){
  $leni=$_seg1->__get_len();
  $lenj=$_seg2->__get_len();
  $lenmin=min($leni,$lenj);
  $offseti=$_seg1->__get_offset();
  $offsetj=$_seg2->__get_offset();
  while($lenmin and ($_seg1->__get_char($offseti)==$_seg2->__get_char($offsetj))){
   $lenmin--;$offseti++;$offsetj++;
  }
  if(!$lenmin){return $leni-$lenj;}
  else{return ($_seg1->__get_char($offseti)<$_seg2->__get_char($offsetj))? -1 : 1;}
 }

//tri par longueur
 function array_segment_usort1($i,$j){
  if($i->__get_len() < $j->__get_len()) return -1;
  else return ($i->__get_len() > $j->__get_len()) ? 1 : 0;
 }

//tri par position
 function array_segment_usort2($i,$j){
  if($i->__get_offset() < $j->__get_offset()) return -1;
  else return ($i->__get_offset()>$j->__get_offset()) ? 1 : 0;
 }

 function usort_array_segment_idstr($i,$j){
  if($i->__get_attribute("id_str") < $j->__get_attribute("id_str")) return -1;
  else return ($i->__get_attribute("id_str") > $j->__get_attribute("id_str")) ? 1 : array_segment_usort2($i,$j);
 }

 class segment{
  protected $str;
//  protected $offset;
//  protected $len;
  protected $array_attributes=array();

  public function __construct(&$_str,$_offset,$_len,$_array_attributes=array()){
   $this->str=&$_str;
   $this->array_attributes["offset"]=$_offset;
   $this->array_attributes["len"]=$_len;
//   $this->offset=$_offset;
//   $this->len=$_len;
   foreach($_array_attributes as $name=>$value)
      $this->array_attributes[$name]=$value;
  }

  public function __destruct(){
   unset($this->str);
   $copy_array=$this->array_attributes;
   foreach($copy_array as $name=>$value) unset($this->array_attributes[$name]);
   unset($this->array_attributes);
  }

  public function &__get_str(){return $this->str;}
  public function __get_char($_offset){return mb_substr($this->str,$_offset,1);}
  public function __get_first_char(){
    return mb_substr($this->str,$this->array_attributes["offset"],1);
  }
  public function __get_nieme_char($_n){
    return mb_substr($this->str,$this->array_attributes["offset"]+$_n,1);
  }
  public function __get_offset(){return $this->array_attributes["offset"];}
  public function __get_offset_end(){return $this->__get_offset()+$this->__get_len();}
  public function __get_len(){return $this->array_attributes["len"];}
  public function __get_attribute($_name){return $this->array_attributes[$_name];}

  public function __get_prefix_str($_n){
    return mb_substr($this->str,$this->array_attributes["offset"],$_n);
  }

  public function __get_suffix_str($_n){
    return mb_substr($this->str,$this->__get_offset_end()-$_n,$_n);
  }

  public function __get_reverse_tostring(){
    $str="";
    for($i=$this->array_attributes["len"];$i>0;$i--){
      $str.=mb_substr($this->str,$this->array_attributes["offset"]+$i,1);
    }
    return $str;
  }

  public function __set_len($_len){
    $this->array_attributes["len"]=$_len;
  }

  public function __set_attribute($_name,$_value){
    $this->array_attributes[$_name]=$_value;
  }

  public function __set_array_attributes($_array_attr){
    foreach($_array_attr as $name=>$value)
      $this->array_attributes[$name]=$value;
  }

  public function __unset_attribute($_name){
    unset($this->array_attributes[$_name]);
  }

  public function __tostring(){
   return mb_substr($this->str,$this->array_attributes["offset"],$this->array_attributes["len"]);
  }

  public function __tostring_verbose(){
    $str_attrs="";
    foreach($this->array_attributes as $nom_attr=>$val_attr){
        $str_attrs.=$nom_attr.":".$val_attr." ";
    }
    $str_attr=rtrim($str_attr);
    return "[$str_attrs]";
  }

  public function __fusion($_segment){
   $this->array_attributes["offset"]=min($this->array_attributes["offset"],$_segment->__get_offset());
   $this->array_attributes["len"]=max($this->__get_offset_end(),$_segment->__get_offset_end())-$this->array_attributes["offset"];
  }

 public function __does_include($_segment){
  return ($this->__get_offset_end()>=$_segment->__get_offset()) ? true : false;
 }

 public function __does_include2($_segment){
  $seg_start=$_segment->__get_offset();
  $seg_end=$seg_start+$_segment->__get_len();
  $start=$this->__get_offset();
  $end=$start+$this->__get_len();

  if($end>$seg_end and $start<$seg_end) return true;
  elseif($start<$seg_start and $end>$seg_start) return true;
  else return false;
 }

 public function __does_include3($_segment){
  $seg_start=$_segment->__get_offset();
  $seg_end=$seg_start+$_segment->__get_len();
  $start=$this->__get_offset();
  $end=$start+$this->__get_len();
  
  if($this->__get_attribute("id_str")!=$_segment->__get_attribute("id_str")) return false;
  elseif($seg_start>$start) return ($seg_start<$end) ? true : false;
  elseif($seg_end<$end) return ($seg_end<$end) ? true : false;
  elseif($seg_start<$start) return ($seg_end>$start) ? true : false;
  elseif($seg_end>$end) return ($seg_start<$end) ? true : false;
  else return false;
 }

  public function __does_include_strict($_segment){
   $offset_start=$_segment->__get_offset();
   $offset_end=$offset_start+$_segment->__get_len();
   $start=$this->__get_offset();
   $end=$start+$this->__get_len();
   return ($end>=$offset_end and $start<=$offset_start) ? true : false;
  }

 }

//****************************************
//****************************************
// MOT
//****************************************
//****************************************


 class mot extends segment{
  public function __construct(&$_str,$_offset,$_len,$_array_attributes=array()){
   parent::__construct($_str,$_offset,$_len,$_array_attributes);
   $this->array_attributes["type"]="i";
  }

  public function __equal($_mot){}

  public function __is_same($_mot){
   return (mb_strtolower($_mot->__tostring())==mb_strtolower($this->__tostring()));
  }

  public function __unset_str(){unset($this->str);}

  public function __get_distance_levenshtein($_mot){
   return levenshtein(mb_strtolower($this->__tostring()),mb_strtolower($_mot->__tostring()));
  }

  public function __get_id_word(){
    return $this->array_attributes["id_word"];
  }

  public function __get_type(){
    return $this->array_attributes["type"];
  }

  public function __get_entropy(){
   $str_len=$this->__get_len();
   $str=$this->__tostring();
   $alphabet=array();
   for($i=0;$i<$str_len;$i++){
    $letter=mb_substr($str,$i,1);
    (isset($alphabet[$letter])) ? $alphabet[$letter]++ : $alphabet[$letter]=1;
   }
   $array_prob=array();
   foreach($alphabet as $letter=>$cpt){$array_prob[$letter]=$cpt/$str_len;}
   $entropy=0;
   foreach($array_prob as $letter=>$proba){$entropy-=$proba*log($proba,2);}
   return round($entropy,3);
  }

  public function __get_fl(){
   $f1=$this->__get_attribute("freq");
   $l1=$this->__get_len();
   return $l1*$f1;
  }

  public function __get_fl2(){
   $f1=$this->__get_attribute("freq");
   $l1=$this->__get_len();
   return $f1/$l1;
  }


  public function __tostring_verbose(){
    $str_attrs  = $this->array_attributes["type"].",";
    $str_attrs .= $this->array_attributes["id_word"].",";
    $str_attrs .= $this->array_attributes["id_bloc"].",";
    $str_attrs .= $this->array_attributes["id_doc"];
    return $this."[$str_attrs]";
  }

  public function __tostring_verbose2(){
    $str_attrs=$this->array_attributes["type"];
    return $this."[$str_attrs]";
  }


  /*
  public function __get_avis4($_mot){
   $f1=$this->__get_attribute("freq");
   $f2=$_mot->__get_attribute("freq");
   $l1=$this->__get_len();
   $l2=$_mot->__get_len();
   $s1=$l1*$f1;
   $s2=$l2*$f2;
   $t1=$this->__get_attribute("type");
   if($t1=="P" and $f1>$f2){return "P";}
   elseif($t1=="v" and $f1<$f2){return "v";}
   else{return "?";}
  }
  */

 }


//****************************************
//****************************************
// CHUNK
//****************************************
//****************************************

class chunk extends segment{

  protected $array_word;

  public function __construct($_array_word){
    $this->array_word=$_array_word;
    $first=reset($_array_word);
    $this->str=$first->__get_str();
    $first_offset=$first->__get_offset();
    $this->array_attributes["offset"]=$first_offset;
    $last=end($_array_word);
    $last_len=$last->__get_len();
    $last_offset=$last->__get_offset();
    $this->array_attributes["len"]=$last_offset-$first_offset+$last_len;
  }  

}

//****************************************
//****************************************
// FACTORY
//****************************************
//****************************************

class factory_mot{
  private $array_id_doc=array();
  private $array_str=array();
  private $array_segment=array(); //[id_segment]=>segment
  private $repartition_segment=array(); //[id_doc][id_bloc][id_segment]=>id_segment
  private $dictionnaire=array(); //[str][id_doc][id_bloc]=>id_segment
  private $dictionnaire_doc_freq=array(); //[id_doc][str]=>fréquence
  private $dictionnaire_doc_bloc_freq=array(); //[id_doc][str][$id_bloc]=>fréquence
  private $dictionnaire_doc=array(); //[id_doc][str][]=>id_segment
  private $repartition_chunk=array();//

  public function __construct(){}
  public function __get_dictionnaire(){
    return $this->dictionnaire;
  }

  public function __get_dictionnaire_doc_freq(){
    return $this->dictionnaire_doc_freq;
  }
  public function __get_dictionnaire_doc_bloc_freq(){
    return $this->dictionnaire_doc_bloc_freq;
  }

  public function __get_relative_repartition_bloc(){
    $repartition_relative=array();
    foreach($this->repartition_segment as $id_doc=>$array_bloc){
      $repartition_relative[$id_doc]=array_keys($array_bloc);
    }
    return $repartition_relative;
  }

  public function __get_relative_doc_bloc_freq(){
    $relative_bloc=$this->__get_relative_repartition_bloc();
    $relative=array();
    foreach($this->dictionnaire_doc_bloc_freq as $id_doc=>$array_str){
      foreach($array_str as $str=>$array_bloc){
        foreach($array_bloc as $id_bloc=>$freq){
          $new_id_bloc=array_search($id_bloc,$relative_bloc[$id_doc]);
          $relative[$id_doc][$str][$new_id_bloc]=$freq;
        }
      }
    }
    return $relative;
  }

  public function __get_filtered_dictionnaire($_filtered_doc,$_filtered_bloc){
    $res_dico=array();
    foreach($this->dictionnaire as $str=>$array_doc){
      foreach($array_doc as $id_doc=>$array_bloc){
        if($_filtered_doc==null or in_array($id_doc,$_filtered_doc)){
          foreach($array_bloc as $id_bloc=>$array_id_segment){
            if($_filtered_bloc==null or in_array($id_bloc,$_filtered_bloc)){
              $res_dico[$str][$id_doc][$id_bloc]=$array_id_segment;
            }
          }
        }
      }
    }
    return $res_dico;
  }

  public function __get_filtered_dictionnaire_doc($_id_doc){
    $dictionnaire=$this->dictionnaire;
    foreach($this->dictionnaire as $str=>$array_doc){
      foreach($array_doc as $id_doc=>$array_bloc){
        if($_id_doc!=$id_doc) unset($dictionnaire[$str][$id_doc]);
      }
    }
  }

  public function __get_array_str(){
    return $this->array_str;
  }
  public function __get_repartition_segment(){
    return $this->repartition_segment;
  }
  public function __get_repartition_chunk(){
    return $this->repartition_chunk;
  }
  public function __get_array_segment(){
    return $this->array_segment;
  }
  public function __get_segment($_id_segment){
    return $this->array_segment[$_id_segment];
  }
  public function __get_array_id_segment($_id_doc,$_id_bloc){
    return $this->repartition_segment[$_id_doc][$_id_bloc];
  }
  public function __get_word($_id_word){
    return $this->array_segment[$_id_word];
  }
  public function __add_word(&$_mot,$_id_doc,$_id_bloc){
//    $array=array($_id_bloc,$_id_doc,$_mot->__get_offset(),$_mot->__get_len(),"i");

    $new_id=array_push($this->array_segment,$_mot)-1;
//    $new_id=array_push($this->array_segment,$array)-1;
//    $new_id=array_push(array(),$_mot)-1;
    
    $str=mb_strtolower($_mot->__tostring());
    $this->dictionnaire[$str][$_id_doc][$_id_bloc][]=$new_id;
    $this->repartition_segment[$_id_doc][$_id_bloc][]=$new_id;
    $this->dictionnaire_doc_freq[$_id_doc][$str]++;
    $this->dictionnaire_doc[$_id_doc][$str][]=$new_id;
    $this->dictionnaire_doc_bloc_freq[$_id_doc][$str][$_id_bloc]++;
//    $this->dictionnaire_tmp[$_id_bloc][$]
//    unset($_mot);
  }

  public function get_frequence_word($_word,$_dictionnaire=null){
    $str_word=mb_strtolower($_word->__tostring());
    if($_dictionnaire==null){
      return Tool_array::count_recursive($this->dictionnaire[$str_word]);
    }
    else{
      return Tool_array::count_recursive($_dictionnaire[$str_word]);
    }
  }

  public function get_frequence_word_doc(mot $_mot){
    $id_doc=$_mot->__get_attribute("id_doc");
    $str=mb_strtolower($_mot);
    return $this->dictionnaire_doc_freq[$id_doc][$str];
  }

  public function get_avis1($_mot1,$_mot2,$_dictionnaire=null){
   $l1=$_mot1->__get_len();
   $l2=$_mot2->__get_len();
   if($_dictionnaire==null){
    $f1=$this->get_frequence_word_doc($_mot1);
    $f2=$this->get_frequence_word_doc($_mot2);
   }
   else{
    $f1=$this->get_frequence_word($_mot1,$_dictionnaire);
    $f2=$this->get_frequence_word($_mot2,$_dictionnaire);
   }
   if($l1<$l2 and $f1>$f2) return "p";
   if($l1>$l2 and $f1<$f2) return "v";
   else return "i";
  }

  public function get_avis2($_mot1,$_mot2,$_dictionnaire=null){
   if($_dictionnaire==null){
    $f1=$this->get_frequence_word_doc($_mot1);
    $f2=$this->get_frequence_word_doc($_mot2);
   }
   else{
    $f1=$this->get_frequence_word($_mot1,$_dictionnaire);
    $f2=$this->get_frequence_word($_mot2,$_dictionnaire);
   }

   $t1=$_mot1->__get_attribute("type");
  
   if($t1=="p" and $f1<$f2){return "v";}
   elseif($t1=="v" and $f1>$f2){return "p";}
   else{return "i";}
  }

  public function get_avis3($_mot1,$_mot2){
   $l1=$_mot1->__get_len();
   $l2=$_mot2->__get_len();
   $t1=$_mot1->__get_attribute("type");
  
   if($t1=="p" and $l1>$l2){return "v";}
   elseif($t1=="v" and $l1<$l2){return "p";}
   else{return "i";}
  }

  public function get_all_mots(&$_str,$_id_doc=0){
    $id_bloc=array_push($this->array_str,$_str)-1;
    $rule="[^ ]+";
    mb_ereg_search_init($_str,$rule,"utf-8");
    $cpt_mot=0;
    while($res=mb_ereg_search_pos()){
//      $o=mb_strlen(mb_strcut($_str,0,$res[0]));
//      $l=mb_strlen(mb_strcut($_str,$res[0],$res[1]));
      $mot=new mot($this->array_str[$id_bloc],
                   mb_strlen(mb_strcut($_str,0,$res[0])),
                   mb_strlen(mb_strcut($_str,$res[0],$res[1])),
                   array("id_bloc"=>$id_bloc,"id_doc"=>$_id_doc));
//      echo $mot."[$o $l] [$res[0] $res[1]]\n";
      $this->__add_word($mot,$_id_doc,$id_bloc);
      ++$cpt_mot;
    }
    return $cpt_mot;
//    return $id_bloc;
  }

  public function init_etiquetage1($_array_id_segment,$_dictionnaire=null){
    $max=count($_array_id_segment);
    $cpt0=0;$cpt1=1;$cpt2=2;
    $array_type=array();
    $array_type["i"][]=0;
    while($cpt2<$max){
      $seg0=$this->array_segment[$_array_id_segment[$cpt0]];
      $seg1=$this->array_segment[$_array_id_segment[$cpt1]];
      $seg2=$this->array_segment[$_array_id_segment[$cpt2]];
      $avis0=$this->get_avis1($seg0,$seg1,$_dictionnaire);
      $avis2=$this->get_avis1($seg2,$seg1,$_dictionnaire);
//      $avis0=$this->get_avis1($seg0,$seg1);
//      $avis2=$this->get_avis1($seg2,$seg1);
//      echo $avis0." ".$avis2."\n";
      if($avis0==$avis2 and $seg1->__get_attribute("type")=="i"){
        $seg1->__set_attribute("type",$avis0);
      }
      $array_type[$seg1->__get_attribute("type")][]=$cpt1;
      $cpt0++;$cpt1++;$cpt2++;
    }
    return $array_type;
  } 

  public function init_etiquetage2($_array_id_segment,$_repartition_type,$_dictionnaire=null){
    $array_type=array();
    $nb_changes=1;
    $max=count($_array_id_segment);
    $repartition_type=$_repartition_type["i"];
    while($nb_changes>0){
      $nb_changes=0;
      $new_repartition_type=array();
      foreach($repartition_type as $cpt){
        $seg1=$this->array_segment[$_array_id_segment[$cpt]];
        $res=array();
        if($cpt-1>=0){
          $seg0=$this->array_segment[$_array_id_segment[$cpt-1]];
          $type0=$seg0->__get_attribute("type");
          if($type0!="i"){
            $res[$this->get_avis2($seg0,$seg1,$_dictionnaire)]++;
            $res[$this->get_avis3($seg0,$seg1)]++;
          }
          else{$res["i"]+=2;}
        }
        else{$res["i"]+=2;}
        if($cpt+1<$max){
          $seg2=$this->array_segment[$_array_id_segment[$cpt+1]];
          $type2=$seg2->__get_attribute("type");
          if($type2!="i"){
            $res[$this->get_avis2($seg2,$seg1,$_dictionnaire)]++;
            $res[$this->get_avis3($seg2,$seg1)]++;
          }
          else{$res["i"]+=2;}
        }
        else{$res["i"]+=2;}
        if($res["p"]>$res["v"]){
          $nb_changes++;
          $seg1->__set_attribute("type","p");
        }
        elseif($res["p"]<$res["v"]){
          $nb_changes++;
          $seg1->__set_attribute("type","v");
        }
        else $new_repartition_type[]=$cpt;
      }
      $repartition_type=$new_repartition_type;
    }

  }

  public function get_all_type_word($_word,$_dictionnaire){
    $str=mb_strtolower($_word->__tostring());
    $array_type=array("p"=>array(),"v"=>array(),"i"=>array());
    foreach($_dictionnaire[$str] as $id_doc=>$array_bloc){
      foreach($array_bloc as $id_bloc=>$array_id_segment){
        foreach($array_id_segment as $id_segment){
          $type=$this->array_segment[$id_segment]->__get_attribute("type");
          $array_type[$type][]=$id_segment;
        }
      }
    }
    return $array_type;
  }

  public function get_all_type_array_id_segment($_array_id_segment){
    $array_type=array();
    foreach($_array_id_segment as $id_segment){
      $type=$this->array_segment[$id_segment]->__get_attribute("type");
      $array_type[$type][]=$id_segment;
    }
    return $array_type;
  }

  public function get_maj_type_word($_word,$_dictionnaire){
    $array_type=$this->get_all_type_word($_word,$_dictionnaire);
    if(count($array_type["p"])>count($array_type["v"])) $type="p";
    elseif(count($array_type["p"])<count($array_type["v"])) $type="v";
    else $type="i";
    $all_id_segment=array();
    foreach($array_type as $array_id_segment){
     $all_id_segment=Tool_array::array_merge($all_id_segment,$array_id_segment);
    }
    return array(0=>$type,1=>$all_id_segment);
  }

  public function get_maj_type_array_id_segment($_array_id_segment){
    $array_type=$this->get_all_type_array_id_segment($_array_id_segment);
    if(count($array_type["p"])>count($array_type["v"])) $type="p";
    elseif(count($array_type["p"])<count($array_type["v"])) $type="v";
    else $type="i";
    return $type;
  }

  public function uniformize_etiquetage($_array_id_segment,$_dictionnaire=null,$array_done=array()){
    if($_dictionnaire==null) $_dictionnaire=$this->dictionnaire;
    foreach($_array_id_segment as $id_segment){
      if(!isset($array_done[$id_segment])){
        $seg=$this->array_segment[$id_segment];
        list($type,$array_chg)=$this->get_maj_type_word($seg,$_dictionnaire);
        foreach($array_chg as $id_seg_chg){
          $array_done[$id_seg_chg]=true;
          $this->array_segment[$id_seg_chg]->__set_attribute("type",$type);
        }
      }
    }
    return $array_done;
  }

  /*
   * uniformize_etiquetage_doc($id_doc)
   * pour un document $id_doc
   *  - pour chacun de ses segments (ie : ses mots)
   *  - les étiquetés en fonction de leurs étiquetages majoritaires dans le doc
   */

  public function uniformize_etiquetage_doc($id_doc){
    $dictionnaire_doc=$this->dictionnaire_doc;
    foreach($dictionnaire_doc[$id_doc] as $str=>$array_id_segment){
      $type=$this->get_maj_type_array_id_segment($array_id_segment);
      foreach($array_id_segment as $id_segment){
        $this->array_segment[$id_segment]->__set_attribute("type",$type);
      }
    }
  }

  /*
   * $_array_etiquette de la forme :
   * - clef = pattern
   * - valeur = etiquette
   * - exemple $_array_etiquette["\[0-9]\"] = "v"
   *
   */

  public function brute_etiquetage_doc($_id_doc,&$_array_etiquette){
    $dictionnaire_doc=$this->dictionnaire_doc;
    foreach($dictionnaire_doc[$_id_doc] as $str=>$array_id_segment){
//      $type=$this->get_maj_type_array_id_segment($array_id_segment);  
      foreach($_array_etiquette as $regexp=>$type){
        $match = array();
        if(preg_match($regexp,$str,$match)){
//          echo "$regexp :: $str --> $type\n";
          foreach($array_id_segment as $id_segment){
            $this->array_segment[$id_segment]->__set_attribute("type",$type);
          }
        }
      }
    }

  }

  public function param_etiquetage1(&$_array_etiquette){
    $repartition_type=array();
    $new_repartition_type=array();
    foreach($this->repartition_segment as $id_doc=>$array_bloc){
      $dico_filtre=$this->__get_filtered_dictionnaire_doc($id_doc);
      foreach($array_bloc as $id_bloc=>$array_id_segment){
        $repartition_type[$id_doc][$id_bloc]=$this->init_etiquetage1($array_id_segment,$dico_filtre);
      }
      $this->brute_etiquetage_doc($id_doc,$_array_etiquette);
      $this->uniformize_etiquetage_doc($id_doc);
      $array_done=array();
    }

  }

  public function init_etiquetage(){
    //array_equiv[id_doc]=array(id_segment)
    //premiere passe d'étiquetage
    $repartition_type=array();
    $new_repartition_type=array();
//    print_r($this->repartition_segment);
//    die();
    foreach($this->repartition_segment as $id_doc=>$array_bloc){
      $dico_filtre=$this->__get_filtered_dictionnaire_doc($id_doc);

      foreach($array_bloc as $id_bloc=>$array_id_segment){
        $repartition_type[$id_doc][$id_bloc]=$this->init_etiquetage1($array_id_segment,$dico_filtre);
      }

      $this->uniformize_etiquetage_doc($id_doc);
/*
      foreach($array_bloc as $id_bloc=>$array_id_segment){
        $new_repartition_type[$id_doc][$id_bloc]=$this->init_etiquetage2($array_id_segment,$repartition_type[$id_doc][$id_bloc]);
      }
*/
      $array_done=array();
//      $start=microtime(true);
/*
      foreach($array_bloc as $id_bloc=>$array_id_segment){
        $dico_filtre=$this->__get_filtered_dictionnaire(array($id_doc),null);
        $array_done=$this->uniformize_etiquetage($array_id_segment,$dico_filtre,$array_done);
      }
*/

//      $this->uniformize_etiquetage_doc($id_doc);

//      $end=microtime(true)-$start;
//      echo "uniformisation ($id_doc) :: $end\n";
    }
  }

  public function init_etiquetage_local($array_doc_bloc){
    foreach($array_doc_bloc as $id_doc=>$array_array_bloc){
      foreach($array_array_bloc as $array_bloc){
        $dico_filtre=$this->__get_filtered_dictionnaire(array($id_doc),$array_bloc);
        foreach($array_bloc as $id_bloc){
          $list_id_segment=$this->repartition_segment[$id_doc][$id_bloc];
          $repartition_type[$id_doc][$id_bloc]=$this->init_etiquetage1($list_id_segment,$dico_filtre);
        }
      }

      $this->uniformize_etiquetage_doc($id_doc);
 /* 
      foreach($array_array_bloc as $array_bloc){
        $dico_filtre=$this->__get_filtered_dictionnaire(array($id_doc),$array_bloc);
        foreach($array_bloc as $id_bloc){
          $list_id_segment=$this->repartition_segment[$id_doc][$id_bloc];
          $new_repartition_type[$id_doc][$id_bloc]=$this->init_etiquetage2($list_id_segment,$repartition_type[$id_doc][$id_bloc],$dico_filtre);
        }
      }
 */
      $this->uniformize_etiquetage_doc($id_doc);

    }

  }


  function get_array_chunk($_array_id_segment){
    $tab_chunks=array();
    $chunk=array();
    $flag=false;
    foreach($_array_id_segment as $id_segment){
      $seg=$this->array_segment[$id_segment];
      $type=$seg->__get_attribute("type");
      if($type!="v"){$flag=true;}
      elseif($flag){
        $tab_chunks[]=$chunk;
        $chunk=array();
        $flag=false;
      }
      $chunk[]=$id_segment;
    }
    if($chunk)$tab_chunks[]=$chunk;
    return $tab_chunks;
  }

  public function init_chunk(){
    foreach($this->repartition_segment as $id_doc=>$array_bloc){
      foreach($array_bloc as $id_bloc=>$array_id_segment){
        $array_chunk=$this->get_array_chunk($array_id_segment);
        $this->repartition_chunk[$id_doc][$id_bloc]=$array_chunk;
      }
    } 
  }

/*
  public function get_array_words($_string){
    $seg_start=tool_string::get_all_segments($_string,"#start");
    $seg_space=tool_string::get_all_segments($_string," ");
    $seg_end=tool_string::get_all_segments($_string,"#end");
    $all_seg=array_merge($seg_start,$seg_space,$seg_end);
    $all_seg=tool_string::array_segment_clean($all_seg);
    $array_words=$this->get_all_segments_bar($_string,$all_seg);
    return $array_words;
  }

   public function get_all_segments_bar(&$_str,$_array_segment_delim,$_array_attr=array("id_doc"=>0,"id_bloc"=>0)){
    $array_word=array();
    foreach($_array_segment_delim as $segment){
      if(isset($last_segment)){
        $last_offset=$last_segment->__get_offset();
        $last_len=$last_segment->__get_len();
        $new_offset=$last_offset+$last_len;
        $new_len=$segment->__get_offset()-$new_offset;
        $new_mot=new mot($_str,$last_offset+$last_len,$new_len,$_array_attr);
        $array_word[]=$new_mot;
        $this->dictionnaire[mb_strtolower($new_mot->__tostring())][]=$new_mot;
      }
      $last_segment=$segment;
    }
    return $array_word;
   }

   public static function get_mot_bar(&$_str,$_last_seg,$_current_seg,$_array_attr=array("id_doc"=>0,"id_bloc"=>0)){
    $last_offset=$_last_seg->__get_offset();
    $last_len=$_last_seg->__get_len();
    $current_offset=$_current_seg->__get_offset();
    $current_len=$_current_seg->__get_len();
    $new_offset=$last_offset+$last_len;
    $new_len=$current_offset-$new_offset;
    $new_mot=new Mot($_str,$new_offset,$new_len,$_array_attr);
    //$this->dictionnaire[mb_strtolower($new_mot->__tostring())][]=$new_mot;
    return $new_mot;
   }
*/

}



 class Tool_factory_mot{

    public static function factory2dictionnairetexte($_factory){
        $dictionnaire=$_factory->__get_dictionnaire();
        foreach($dictionnaire as $str=>$array_occ){
            echo $str."\n";
            foreach($array_occ as $occ){
                echo "    $occ\n";
            }
        }
    }

    public static function factory2print($_factory){
      $repartition=$_factory->__get_repartition_chunk();
      foreach($repartition as $id_doc=>$array_doc){
        echo "$id_doc\n";  
        foreach($array_doc as $id_bloc=>$array_bloc){
          echo "+--".$id_bloc."\n";  
          foreach($array_bloc as $chunk){
            echo " [ ";
            foreach($chunk as $id_word){
              echo $_factory->__get_segment($id_word)->__tostring_verbose()." ";
            }
            echo "] ";
          }
          echo "\n";
        }
      }

    }

    public static function factory2texte($_factory){
//      print_r($_factory->__get_array_type());  
//die();
      $repartition=$_factory->__get_repartition_segment();
//    print_r($_factory);
      foreach($repartition as $id_doc=>$array_doc){
        echo "$id_doc\n";  
        foreach($array_doc as $id_bloc=>$array_bloc){
          echo "+--".$id_bloc."\n";  
          foreach($array_bloc as $id_word){
            echo "  +--".$id_word." ";
            echo $_factory->__get_segment($id_word)." ";
            echo $_factory->__get_segment($id_word)->__tostring_verbose();
            echo "\n";
          }
        }
      }
    }
 }

?>
