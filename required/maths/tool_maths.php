<?php

  function round_log($_float){
    return round(log($_float),1);
  }

  class tool_maths{

    public static function match_value1($_i,$_j,$_percent){
      if($_i<$_j){
        $tmp=$_i;
        $_i=$_j;
        $_j=$tmp;
      }
      $il=$_i-abs(($_i*$_percent)/100);
      $jp=$_j+abs(($_j*$_percent)/100);
      return ($il<=$jp);

    }

    public static function match_value2($_i,$_j,$_margin){
      if($_i<$_j){
        $tmp=$_i;
        $_i=$_j;
        $_j=$tmp;
      }
      return (($_i-$_margin)<($_j+$_margin));
    }


    public static function ceil_with_step($_val,$_step){
      ($_val>=0) ? $_val-=($_val%$_step) : $_val-=($_step+($_val%$_step));
      return $_val;
    }

    public static function floor_with_step($_val,$_step){
      ($_val>=0) ? $_val+=($_step-($_val%$_step)) : $_val-=($_val%$_step);
      return $_val;
    }

    public static function get_ten($_val){
      $abs=abs($_val);
      $round=round($abs,0);
      $nb_zero=strlen($round);
      $res=(int)"1".str_repeat(0,$nb_zero);
      return $res;
    }

    public static function zipf($_array_vals){
      rsort($_array_vals);
      foreach($_array_vals as $rang=>$val){
        $res[$rang+1]=$val;
      }
      return $res;
    }

    public static function zipf_str_len($_array_vals){
      $tmp1=array();
      arsort($_array_vals);
      foreach($_array_vals as $str=>$val){
        $tmp1[$val][mb_strlen($str)][]=$str;
      }
      $res=array();
      foreach($tmp1 as $freq=>$array_str){
        krsort($array_str);
        foreach($array_str as $len=>$astr){
          foreach($astr as $str){
            $res[$str]=$freq;
          }
        }
      }
      return $res;
    }

    public static function own_zipf($_array_vals){
      arsort($_array_vals);
      $cpt=0;
      foreach($_array_vals as $str=>$val){
        $strlen=mb_strlen($str);
        $res1[++$cpt]=$val;
        $res2[$cpt]=strlen;
        $res3[$cpt]=strlen*$val;
        $res4[$str]=$cpt;
        $res5[$strlen]++;
      }
      ksort($res5);
      return array($res1,$res2,$res3,$res4,$res5);
    }

    public static function own_zipf_etiquette($_array_vals,$_array_etiquettes){
      arsort($_array_vals);
      $cpt=0;
      foreach($_array_vals as $str=>$val){
        $res1[]=array(++$cpt,$val);
        foreach($_array_etiquettes as $type=>$array_str){
          if(isset($array_str[$str])){
            $res5[$type][]=array($cpt,$array_str[$str]);
          }
          else{
            $res5[$type][]=array($cpt,0);
          }
        }
      }
      return array($res1,$res5);

    }

    public static function zipf_log2($_zipf){
      $x=$y=array();
      $flag=true;
      $cpt=0;
      foreach($_zipf as $array_coord){
        if($array_coord[0]!=0) $x[$cpt]="".log($array_coord[0]);
        else $x[$cpt]=0;

        if($array_coord[1]!=0){
          if(!$flag){ 
           $y[]=0;
           $x[]=$x[$cpt];
           $cpt++;
          }
          $y[$cpt]="".log($array_coord[1]);
          $flag=true;
        }
        else{
          if($flag){
           $y[$cpt]=0;
           $x[$cpt]=$x[$old_cpt];
           $cpt++;
          }
          $y[$cpt]=0;
          $flag=false;
        }
        $old_cpt=$cpt;
        ++$cpt;
      }
      return array($x,$y);
    }

    public static function zipf_log($_zipf){
      $res=array();
      foreach($_zipf as $id=>$val){
        if($val!=0)$res["".log($id+1)]=log($val);
        else $res["".log($id+1)]=0;
      }
      return $res;
    }

    public static function regression_lineaire_simple($_array_x,$_array_y){
      $Sx=$Sy=$Sxy=$Sx2=$Sy2=$n=0;
      $max=count($_array_x);
      for($i=0;$i<$max;$i++){
        $Sx+=$_array_x[$i];
        $Sy+=$_array_y[$i];
        $Sxy+=$_array_x[$i]*$_array_y[$i];
        $Sx2+=$_array_x[$i]*$_array_x[$i];
        $Sy2+=$_array_y[$i]*$_array_y[$i];
        $n++;
      }
      $mx=$Sx/$n;
      $my=$Sy/$n;
      $covariance_xy=($Sxy/$n)-($mx*$my);
	
      for($i=0;$i<$max;$i++){
        $variancex+=($_array_x[$i]-$mx)*($_array_x[$i]-$mx);
        $variancey+=($_array_y[$i]-$my)*($_array_y[$i]-$my);
      }
      $variancex=$variancex/$n;
      $variancey=$variancey/$n;
      $ecart_type_x=sqrt(abs($variancex));
      $ecart_type_y=sqrt(abs($variancey));
      $r=$covariance_xy/($ecart_type_x*$ecart_type_y);
      $a=$r*($ecart_type_y/$ecart_type_x);
      $b=$my-($a*$mx);
      return array("a"=>$a,"b"=>$b,"r"=>$r);
    }

   /*
    * $_array=array(x)
    */
    public static function ecart_type($_array){
      $max=count($_array);
      $Sx=$n=0;
      for($i=0;$i<$max;$i++){
        $Sx+=$_array[$i];
        $n++;
      }
      $moyenne=$Sx/$n;
      $variancex=0;
      for($i=0;$i<$max;$i++){
        $variancex+=pow($_array[$i]-$moyenne,2);
      }
      $variancex=$variancex/$n;
      $ecart_type=sqrt(abs($variancex));
      return $ecart_type;
    }

   /*
    * $_array[x]=y
    */
    public static function ecart_type2($_array){
      foreach($_array as $x=>$y){
        $Sx+=$x*$y;
        $n+=$y;
      }
      $moyenne=$Sx/$n;
  
      $varx=0;
      foreach($_array as $x=>$y){
        for($i=0;$i<$y;++$i){
          $varx+=pow($x-$moyenne,2);
        }
      }
      $varx/=$n;
      $ecart_type=sqrt(abs($varx));
      return $ecart_type;
    }

   /*
    * $_array[x]=y
    */
    public static function esperance($_array){
      $sum=array_sum($_array);
      $E=0;
      foreach($_array as $x=>$y) $E+=$x*($y/$sum);
      return $E;
    }

   /*
    * $_array[x]=y
    */
    public static function get_gaussienne($_array,$_vals_x){
      $keys=array_keys($_array);
      $min=min($keys);
      $new_array=array();
      for($min;;++$min){
        if(!isset($_array[$min])) break;
        else $new_array[$min]=$_array[$min];
      }
      $_array=$new_array;

//      print_r($_array);
//      die();
      $m=self::esperance($_array);
      $s=self::ecart_type2($_array);
      $sum=array_sum($_array);
      $res=array();
      $repr=array();
      foreach($_vals_x as $x){
        $m1=1/($s*sqrt(2*pi()));
        $m2=exp(-pow(($x-$m),2)/(2*pow($s,2)));
        $res[$x]=$m1*$m2;
        $repr[$x]=$res[$x]*$sum;
      }
      $h=2*sqrt(2*log(2))*$s;
      return array("m"=>$m,"s"=>$s,"h"=>$h,"gauss"=>$res,"repr"=>$repr);
    }

    /*
     * deux points $m et $n
     * $m=array("x","y");
     * $n=array("x","y");
     */

    public static function get_equation_droite($m,$n){
      $a=($n["y"]-$m["y"])/($n["x"]-$m["x"]);
      $b=$m["y"]-($a*$m["x"]);
      return array("a"=>$a,"b"=>$b); 
    }

    public static function get_array_pente($array_point){
      $array_res=array();
      foreach($array_point as $x=>$y){
        if(isset($old_x)){
          $p1=array("x"=>$old_x,"y"=>$old_y);
          $p2=array("x"=>$x,"y"=>$y);
          $equation=self::get_equation_droite($p1,$p2);
          $array_res[]=$equation["a"];
        }
        $old_x=$x;
        $old_y=$y;
      }
      $p1=array("x"=>$old_x,"y"=>$old_y);
      $p2=array("x"=>0,"y"=>0);
      $equation=self::get_equation_droite($p1,$p2);
      $array_res[]=$equation["a"];
      return $array_res;
    }

    public static function do_translation($_array_points,$_x=0,$_y=0){
      $array_trans=array();
      foreach($_array_points as $x=>$y){
        if($_x>0) $x+=$_x;
        if($_y>0) $y+=$_y;
        $array_trans[$x]=$y;
      }
      return $array_trans;
    }

    /*
     * $_x1, $_x2 deux points
     * $_x = array(dim_1,dim_2,...dim_n)
     * $_x1.n==$_x2.n
     * si($_p==2) distance Euclidienne
     * si($_p==1) distance Manhattan
     */

    public static function minkowski_metric($_x1,$_x2,$_p=1){
      $cpt=count($_x1);
      $res=0;
      for($i=0;$i<$cpt;++$i){
        $res+=pow(abs($_x1[$i]-$_x2[$i]),$_p);
      }
      return pow($res,1/$_p);
    }

    public static function euclidian_distance($_v1,$_v2){
      return pow(pow(abs($_v1-$_v2),2),0.5);
    }    

    public static function symetrical_distance($_v1,$_v2){
      return pow($_v1-$_v2,2);
    }

    /*
     * $_x1, $_x2 deux points
     * $_x = array(dim_1,dim_2,...dim_n)
     * $_x1.n==$_x2.n
     */
    public static function get_vector($_x1,$_x2){
      $vector=array();
      foreach($_x1 as $dim=>$v) $vector[$dim]=$_x2[$dim]-$v;
      return $vector;
    }

    public static function sum_vector($_x1,$_x2){
      $vector=array();
      foreach($_x1 as $dim=>$v) $vector[$dim]=$v+$_x2[$dim];
      return $vector;
    }

    public static function div_vector(&$_v,$_i){
      foreach($_v as $id=>$val) $_v[$id]/=$_i;
    }

    //normalise le vecteur $_v en fonction du nombre d'élément qu'il contient

    public static function get_proportion_vector($_v){
      $sum=array_sum($_v);
      $res=array();
      foreach($_v as $i=>$val) $res[$i]=$val/$sum;
      return $res;
    }

    //calcul du vecteur de recence
    //a faire après normalisation

    public static function get_vector_recence($_v){
      $res=array();
      $cpt=0;
      foreach($_v as $i=>$val){
        if(isset($old_i)) $res[]=$val-$_v[$old_i];
        else $res[]=$val;
        $old_i=$i;
      }
      return $res;
    }

    public static function get_IM($_vect1,$_vect2,&$_base,&$_count){
      $m00 = $m10 = $m11 = $m01 = 0;
      $c1 = $c2 = 0;
      for($i=0;$i<$_count;++$i){
        $dim=$_base[$i];
        if($_vect1[$dim]>0){
          ++$c1;
          if($_vect2[$dim]>0){
            ++$m11; ++$c2;
          }
          else ++$m10;
        }
        else{
          if($_vect2[$dim]>0){
            ++$m01; ++$c2;
          }
          else ++$m00;
        }
      }
      $p_s1s2 = $m11 / $_count;
      $p_s1 = $c1 / $_count; 
      $p_s2 = $c2 / $_count; 

      $im = log($p_s1s2 / ($p_s1 * $p_s2));
      if((string)$im === '-INF') $im = -999999;
      elseif((string)$im === 'INF') $im = 999999;
      return $im;

    }

    public static function get_jaccard($_vect1,$_vect2,&$_base,&$_count){
      $m00=$m10=$m11=$m01=0;
      for($i=0;$i<$_count;++$i){
        $dim=$_base[$i];
        if($_vect1[$dim]>0){
          if($_vect2[$dim]>0) ++$m11;
          else ++$m10;
        }
        else{
          if($_vect2[$dim]>0) ++$m01;
          else ++$m00;
        }
      }
      return ($m01+$m10) / ($m01+$m10+$m11);
    }

    public static function get_angle($_vecteur1,$_vecteur2,&$_base,&$_count){
      $up=$n1=$n2=0;
      for($i=0;$i<$_count;++$i){
        $dim=$_base[$i];
        $up+=$_vecteur1[$dim]*$_vecteur2[$dim];
        $n1+=$_vecteur1[$dim]*$_vecteur1[$dim];
        $n2+=$_vecteur2[$dim]*$_vecteur2[$dim];
      }
      return ($up/sqrt($n1*$n2));
    }

    public static function uniform_inner_zipf($_zipf,$_inner_rep){
      $max=1;
      $min=0;
      $uni_rep=array();
      foreach($_inner_rep as $rank=>$nb_occ){
        $uni_rep=$nb_occ/$zipf[$rank]*$max;
      }
      return $uni_rep;
    }

/*
    //$_v1 et $_v2 : deux vecteurs de recence
    public static function DTW($_v1,$_v2){
      $l1=count($_v1);
      $l2=count($_v2);
      $res[0][0]=0;
      $res[0]=array_fill(1,$l1,1);
      $res[1]=array_fill(1,$l2,1); 
      print_r($res);
    }
*/
  }

  class DTW{
    protected $vector1=array();
    protected $vector2=array();
    protected $array_dist=array();
    protected $array_trace=array();
    protected $array_res=array();
    //protected $maxint=PHP_INT_MAX;
    protected $maxint='wtf';
    protected $array_route=array();
    //protected $maxint=90;
    protected $cpt_route=0;


    public function __construct(){

    }
    public function __get_array_res(){return $this->array_res;}
    public function __get_array_trace(){return $this->array_trace;}
    public function __get_array_dist(){return $this->array_dist;}
    public function __get_array_route(){return $this->array_route;}
    public function __get_maxint(){return $this->maxint;}

    public function __get_window($_v){
      //return round(count($_v)/100)*95;
      return round(count($_v)/10)*9; // pour la comparaison des zipfs
      //return round(count($_v)/10)*9; // pour la comparaison des zipfs
      //return round(count($_v)/7)*6;
      //return round(count($_v)/3)*2;
      //return round(count($_v)/2)*1;
    }

    public function __init_array_windowing(){
      $w1=$this->__get_window($this->vector1);
      $w2=$this->__get_window($this->vector2);
      $v1=count($this->vector1);
      $v2=count($this->vector2);

      $a=array("x"=>$v2-$w2,"y"=>0);
      $b=array("x"=>$v2, "y"=>$w1);

      $equation1=tool_maths::get_equation_droite($a,$b);
      for($i=0;$i<$v1;++$i){
        $flag=true;
        for($j=$v2-1;$j>=0;--$j){
          if($equation1["a"]*$j+$equation1["b"]-$i>=0){
            $this->array_res[$i][$j]=$this->maxint;
            $this->array_res[$v1-1-$i][$v2-1-$j]=$this->maxint;
            $flag=false;
          }
          else break;
        }
        if($flag) break;
      }
    }

    public function __init_array_dist($_v1,$_v2){
      foreach($_v1 as $i=>$coord_v1){
        foreach($_v2 as $j=>$coord_v2){
          $dist=tool_maths::minkowski_metric($coord_v1,$coord_v2,2);
          $this->array_dist[$i][$j]=$dist;
        }
      }
      return $this->array_dist; 
    }

    public function __set_v1_v2($_v1,$_v2){
      $this->vector1=$_v1;
      $this->vector2=$_v2;
    }

    public function __init_start($_rule){
      switch($_rule){
        case "normal" :
	  $this->__init_start_normal();
	  break;
        case "sym" :
	  $this->__init_start_sym();
	  break;
	default :
	  $this->__init_start_sym();
	  break;
      }
    }


    public function __init_start_normal(){
      $this->array_res[0][0]=tool_maths::euclidian_distance($this->vector1[0][1],$this->vector2[0][1]);
//      $this->array_res[0][0]=tool_maths::minkowski_metric($this->vector1[0],$this->vector2[0],2);
      $v1=count($this->vector1);
      $v2=count($this->vector2);

      for($i=1;$i<$v1;++$i){
        if(!isset($this->array_res[$i][0])){
//          $dist=tool_maths::minkowski_metric($this->vector1[$i],$this->vector2[0],2);
          $dist=tool_maths::euclidian_distance($this->vector1[$i][1],$this->vector2[$j][1]);
          $this->array_res[$i][0]=$this->array_res[$i-1][0]+$dist;
        }
        else break;
      }

      for($j=1;$j<$v2;++$j){
        if(!isset($this->array_res[0][$j])){
          $dist=tool_maths::euclidian_distance($this->vector1[$i][1],$this->vector2[$j][1]);
//          $dist=tool_maths::minkowski_metric($this->vector1[0],$this->vector2[$j],2);
          $this->array_res[0][$j]=$this->array_res[0][$j-1]+$dist;
        }
        else break;
      }
    }


    public function __init_start_sym(){
      $this->array_res[0][0]=tool_maths::symetrical_distance($this->vector1[0][1],$this->vector2[0][1]);
      $v1=count($this->vector1);
      $v2=count($this->vector2);

      for($i=1;$i<$v1;++$i){
        if(!isset($this->array_res[$i][0])){
          $dist=tool_maths::symetrical_distance($this->vector1[$i][1],$this->vector2[0][1]);
          $this->array_res[$i][0]=$this->array_res[$i-1][0]+$dist;
        }
        else break;
      }

      for($j=1;$j<$v2;++$j){
        if(!isset($this->array_res[0][$j])){
          $dist=tool_maths::symetrical_distance($this->vector1[0][1],$this->vector2[$j][1]);
          $this->array_res[0][$j]=$this->array_res[0][$j-1]+$dist;
        }
        else break;
      }
    }

    public function __init_dtw($_rule){
      switch($_rule){
        case "normal" :
	  $res = $this->__init_dtw_normal();
	  break;
        case "sym" :	  
	  $res = $this->__init_dtw_sym();
	  break;
	default :
	  $res = $this->__init_dtw_sym();
	  break;
      }
      return $res;
    }

    public function __init_dtw_sym(){
      $v1=count($this->vector1);
      $v2=count($this->vector2);
      for($i=1;$i<$v1;++$i){
        for($j=1;$j<$v2;++$j){
          if(!isset($this->array_res[$i][$j])){
            $dist=tool_maths::symetrical_distance($this->vector1[$i][1],$this->vector2[$j][1]);
            $array_cmp=array();
            if($this->array_res[$i-1][$j]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i-1][$j]*1;
            
            if($this->array_res[$i][$j-1]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i][$j-1]*1;
            
            if($this->array_res[$i-1][$j-1]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i-1][$j-1];

            if(count($array_cmp)==0) $this->array_res[$i][$j]=$this->maxint; 
            else $this->array_res[$i][$j]=$dist+min($array_cmp);
 
          }
        }
      }
      return $this->array_res[$v1-1][$v2-1];
    }

    public function __init_dtw_normal(){
      $v1=count($this->vector1);
      $v2=count($this->vector2);
      for($i=1;$i<$v1;++$i){
        for($j=1;$j<$v2;++$j){
          if(!isset($this->array_res[$i][$j])){
//            $dist=tool_maths::minkowski_metric($this->vector1[$i],$this->vector2[$j],2);
            $dist=tool_maths::euclidian_distance($this->vector1[$i][1],$this->vector2[$j][1]);
            $array_cmp=array();
            if($this->array_res[$i-1][$j]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i-1][$j]*1;
            
            if($this->array_res[$i][$j-1]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i][$j-1]*1;
            
            if($this->array_res[$i-1][$j-1]!==$this->maxint)
              $array_cmp[]=$this->array_res[$i-1][$j-1];

            if(count($array_cmp)==0) $this->array_res[$i][$j]=$this->maxint; 
            else $this->array_res[$i][$j]=$dist+min($array_cmp);
 
          }
        }
      }
      return $this->array_res[$v1-1][$v2-1];
    }

    public function __dtw2txt(){
      ksort($this->array_res);
      $flag=true;
      $res=$res2="";
      foreach($this->array_res as $x=>$array_dist){
        ksort($array_dist);
        $res_tmp=' '.$x.' ';
        foreach($array_dist as $y=>$dist){
          if($dist===$this->maxint) $dist="X";
          if($flag) $res.=$y.' ';
          $res_tmp.=$dist.' ';
        }
        if($flag) $res="   $res\n";
        $flag=false;
        $res.="$res_tmp\n";
      }
      return $res;
    }

    public function __print_route($_route){
      $route="";
      $score=0;
      foreach($_route as $case){
        $score+=$this->array_res[$case[0]][$case[1]];
        $route.="(".$case[0]." ".$case[1].")";
      }
      echo "$score : $route";
    }

    public function __get_score_route($_route){
      $score=0;
      for($i=0;$i<count($_route);++$i){
        $score+=$this->array_res[$_route[$i][0]][$_route[$i][1]];
      }
      return $score;
    }

/*
    public function __trace_route($_pos=null,$_route=array()){
      if($_pos==null){
        $_pos=array(count($this->vector1)-1,count($this->vector2)-1);
      }
    }  
*/

    public function __trace_route($_pos=null,$_route=array()){
      if($_pos==null){
        $_pos=array(count($this->vector1)-1,count($this->vector2)-1);
      }

      $bordure_droite=0;
      $bordure_bas=0;
      if($_pos==array($bordure_bas,$bordure_droite)){
        $_route[]=$_pos;

        if(count($this->array_route)==0) $this->array_route[]=$_route;
        else{
          $new_score=self::__get_score_route($_route);
          $old_score=self::__get_score_route($this->array_route[0]);
          if($old_score==$new_score) $this->array_route[]=$_route;
          elseif($new_score<$old_score) $this->array_route=array($_route);
        }
        return;
      }
      $array_score=array();
      $array_dir=array();

      if($_pos[1]>$bordure_droite){
        if($this->array_res[$_pos[0]][$_pos[1]-1]!==$this->maxint){
          $array_dir['d']=array($_pos[0],$_pos[1]-1);
          $array_score['d']=$this->array_res[$_pos[0]][$_pos[1]-1];
        }
      }
      if($_pos[0]>$bordure_bas){
        if($this->array_res[$_pos[0]-1][$_pos[1]]!==$this->maxint){
          $array_dir['b']=array($_pos[0]-1,$_pos[1]);
          $array_score['b']=$this->array_res[$_pos[0]-1][$_pos[1]];
        }
      }
      if(isset($array_score['b']) and isset($array_score['d'])){
        if($this->array_res[$_pos[0]-1][$_pos[1]-1]!==$this->maxint){
          $array_dir['bd']=array($_pos[0]-1,$_pos[1]-1);
          $array_score['bd']=$this->array_res[$_pos[0]-1][$_pos[1]-1];
        }
      }
      if(count($array_score)==0) return;

      $min=min($array_score);

      if(isset($array_score['bd']) and $array_score['bd']==$min){
        $route=$_route;
        $route[]=$_pos;
        self::__trace_route($array_dir['bd'],$route);
        return;         
      }

      foreach($array_score as $dir=>$score){
        if($min==$score){
          $route=$_route;
          $route[]=$_pos;
          self::__trace_route($array_dir[$dir],$route);
        }
      }
    }


  }


?>
