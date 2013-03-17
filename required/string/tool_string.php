<?php

 class Tool_string{

    public static $regexp_maj = "[VBKEUPMFWDCSARJZHNTGŽIOÚŠŘLČÁYÆØÅßÜÖQÄΒΣΟΕΓΝΗΠΑΤΚΛΧΔΜΙΦΈΌΩΥΖΡΘΉΊÉXÓÕÀÍŐÈŪĮŅĀĪĢŚŁŻ]";
    public static $non_regexp_maj = "[^VBKEUPMFWDCSARJZHNTGŽIOÚŠŘLČÁYÆØÅßÜÖQÄΒΣΟΕΓΝΗΠΑΤΚΛΧΔΜΙΦΈΌΩΥΖΡΘΉΊÉXÓÕÀÍŐÈŪĮŅĀĪĢŚŁŻ]";
    public static $non_regexp_maj_guill = "[^VBKEUPMFWDCSARJZHNTGŽIOÚŠŘLČÁYÆØÅßÜÖQÄΒΣΟΕΓΝΗΠΑΤΚΛΧΔΜΙΦΈΌΩΥΖΡΘΉΊÉXÓÕÀÍŐÈŪĮŅĀĪĢŚŁŻ«»„””“\"]";

    public static $regexp_maj_or = '(V|B|K|E|U|P|M|F|W|D|C|S|A|R|J|Z|H|N|T|G|Ž|I|O|Ú|Š|Ř|L|Č|Á|Y|Æ|Ø|Å|ß|Ü|Ö|Q|Ä|Β|Σ|Ο|Ε|Γ|Ν|Η|Π|Α|Τ|Κ|Λ|Χ|Δ|Μ|Ι|Φ|Έ|Ό|Ω|Υ|Ζ|Ρ|Θ|Ή|Ί|É|X|Ó|Õ|À|Í|Ő|È|Ū|Į|Ņ|Ā|Ī|Ģ|Ś|Ł|Ż)';

    public static $regexp_maj_guill_or = '(V|B|K|E|U|P|M|F|W|D|C|S|A|R|J|Z|H|N|T|G|Ž|I|O|Ú|Š|Ř|L|Č|Á|Y|Æ|Ø|Å|ß|Ü|Ö|Q|Ä|Β|Σ|Ο|Ε|Γ|Ν|Η|Π|Α|Τ|Κ|Λ|Χ|Δ|Μ|Ι|Φ|Έ|Ό|Ω|Υ|Ζ|Ρ|Θ|Ή|Ί|É|X|Ó|Õ|À|Í|Ő|È|Ū|Į|Ņ|Ā|Ī|Ģ|Ś|Ł|Ż|«|»|„|”|”|“|\")';

    public static $global_array_end_separator=array(
            ":",
            "."
    );

    public static $global_array_separator=array(
            //left
            "â<80><98>"=>' " ',
            "â<80><9e>"=>' " ',
            
            //right
            ", "=>" , ",
            ";"=>" ; ",
            ";"=>" ;",
            ": "=>" : ",
            "\\. "=>" . ",
            "€"=>" € ",
            "\? "=>" ? ",
            "¿" => " ¿ ",
            //both
            "~" => " ~ ",
            "\+" => " + ",
            "\[" => " [ ",
            "\]" => " ] ",
            "\("=>" ( ",
            "\)"=>" ) ",
            "%"=>" % ",
            "'"=>"' ",
            "’"=>"' ",
            "â<80><99>"=>"' ",
            "\""=>' " ',
            "â<80><9d>"=>' " ',
            "â<80><9c>"=>' "  ',
            "\\$"=>" $ ",
            "â<82>¬"=>" â<82>¬ ",
            "Â£"=>" Â£ ",
            "Â»"=>" Â» ",
            "Â«"=>" Â« "
           );

    public static $global_guill=array(
        "«", "»",
        "„", "”", "”",
        "“", "“", "„",
        "”", "”", "“"
    );

    public static $regexp_guill='[«»„””“"]';
    public static $non_regexp_guill='[^«»„””“"]';

    public static $global_specar=array(
            "â<80><93>"=>"-"
           );

    public static function diff_len($_str1,$_str2){
      return mb_strlen($_str1)-mb_strlen($_str2);
    }

    public static function clean_space($_string){
        return trim(mb_ereg_replace("[\n\0\t\x0B\r ]+"," ",$_string));
    }

    public static function clean_guill($_string){
      foreach(self::$global_guill as $guill)
        $_string=mb_ereg_replace("$guill"," \" ",$_string);
      return $_string;
    }

    public static function clean_specar($_string){
     foreach(self::$global_specar as $specar=>$new_car)
        $_string=mb_ereg_replace("$specar","$new_car",$_string);
     return $_string;
    }

    public static function clean_separator($_string){
     $old="";
     while($old!=$_string){
        $old=$_string;
        $_string=self::clean_space($_string);
        foreach(self::$global_array_separator as $separator=>$changes){
          $_string=mb_eregi_replace("$separator","$changes",$_string);
        }
     }
     return $_string;
    }

    public static function clean_strict_end_separator($_string){
     $_string=mb_eregi_replace("\. "," . ",$_string);
     $_string=mb_eregi_replace(": "," : ",$_string);
     $_string=mb_eregi_replace(","," , ",$_string);
     $_string=mb_eregi_replace(";"," ; ",$_string);
     $_string=mb_eregi_replace("\+"," + ",$_string);
     $_string=mb_eregi_replace("~"," ~ ",$_string);
     $_string=mb_eregi_replace("\("," ( ",$_string);
     $_string=mb_eregi_replace("\)"," ) ",$_string);
     $_string=self::clean_space($_string);
     return $_string;
    }

    public static function clean_end_separator($_string){
      $last_carac=mb_substr($_string,-1,1);//$_string[strlen($_string)-1];
      if(in_array($last_carac,self::$global_array_end_separator)){
        return mb_substr($_string,0,mb_strlen($_string)-1)." ".$last_carac;
      }
      else return $_string." .";    
    }

    public static function clean_non_end($_string){
      $pattern="(.) (\.) (".self::$non_regexp_maj_guill.")";
      return preg_replace("/$pattern/u",'\\1\\2 \\3',$_string);
    }

    public static function clean_non_end2($_string){
      $pattern="(.) (\.) (".self::$non_regexp_maj_guill.")";
      return preg_replace("/$pattern/u",'\\1\\2 \\3',$_string);
    }

    public static function clean_digit($_string){
      $pattern = '([0-9]+) (\.) ([0-9]+)';
      $old_string = '';
      while($old_string !== $_string){
        $old_string = $_string;
        $_string = mb_ereg_replace($pattern,'\\1\\2\\3',$_string);
      }
      return $_string;
    }

    public static function exception_lg($_string,$_lg){
      switch($_lg){
        case 'da' :
          $pattern = " (mia) (\.) (EUR)";
          $_string = mb_ereg_replace($pattern,' \\1\\2 \\3',$_string);
          break;
        case "de" :
          $pattern = "([0-9]+) (\.) (Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)";
          $_string = mb_ereg_replace($pattern,'\\1\\2 \\3',$_string);
          $pattern = " (.) (\.) (".self::$regexp_maj."{1,}) (\.)";
          $_string = mb_ereg_replace($pattern,'\\1\\2 \\3\\4',$_string);
          $pattern = " (.) (\.) (.) (\.)";
          $_string = mb_ereg_replace($pattern,'\\1\\2 \\3\\4',$_string);
          break; 
        case "en" :
          $pattern = " (th) (\.) ";
          $_string = mb_ereg_replace($pattern,' \\1\\2 ',$_string);
          break;

        default : break;
      }
      return $_string;
    }

    public static function clean($_string){
      $_string=self::clean_space($_string);
      $_string=self::clean_specar($_string);
      $_string=self::clean_guill($_string);
      $_string=self::clean_separator($_string);
      $_string=self::clean_space($_string);
//      $_string=self::clean_non_end($_string);
      $_string=self::clean_end_separator($_string);
      return $_string;
    }


//********************************************
//********************************************
// SEGMENTS
//********************************************
//********************************************

    public static function get_all_segments(&$_str,$_rule,$_id_doc="0"){
      if($_rule=="#start")
        return array(new segment($_str,0,0,array("id_doc"=>$_id_doc)));
      if($_rule=="#end")
        return array(new segment($_str,mb_strlen($_str),0,array("id_doc"=>$_id_doc)));
      else{
        mb_ereg_search_init($_str,$_rule,"utf-8");
        $array_segment=array();
        while($res=mb_ereg_search_pos()){
            $array_segment[]=new segment($_str,
                                 mb_strlen(mb_strcut($_str,0,$res[0])),
                                 mb_strlen(mb_strcut($_str,$res[0],$res[1])),
                                 array("id_doc"=>$_id_doc));
        }
        return $array_segment;
      }
    }

    public static function array_segment_clean($_array_segment){
      $array_res=array();
      while(count($_array_segment)>0){
        $first=array_shift($_array_segment);
        $flag=true;
        foreach($array_res as $i=>$segment){
          if($segment->__does_include($first)){
            $segment->__fusion($first);
            $flag=false;
          } 
          elseif(!$flag){break;}
        }
        if($flag)$array_res[]=$first;
      }
      return $array_res;
    }

   public static function get_segment_bar(&$_str,$_last_seg,$_current_seg,$_array_attr=array("id_doc"=>0,"id_bloc"=>0)){
    $last_offset=$_last_seg->__get_offset();
    $last_len=$_last_seg->__get_len();
    $current_offset=$_current_seg->__get_offset();
    $current_len=$_current_seg->__get_len();
    $new_offset=$last_offset+$last_len;
    $new_len=$current_offset-$new_offset;
    return $new_mot=new segment($_str,$new_offset,$new_len,$_array_attr);
   }

   public static function get_all_segments_bar(&$_str,$_array_segment_delim,$_id_doc=0){
    $array_word=$temp=$dictionnaire=array();
    foreach($_array_segment_delim as $segment){
      if(isset($last_segment)){
        $last_offset=$last_segment->__get_offset();
        $last_len=$last_segment->__get_len();
        $current_offset=$segment->__get_offset();
        $current_len=$segment->__get_len();
        $new_offset=$last_offset+$last_len;
        $new_len=$current_offset-$new_offset;
        $new_mot=new mot($_str,$new_offset,$new_len,array("id_doc"=>$_id_doc));
        $array_word[]=$new_mot;
        $dictionnaire[mb_strtolower($new_mot->__tostring())][]=$new_mot;
      }
      $last_segment=$segment;
    }
    return array("array_word"=>$array_word,"dictionnaire"=>$dictionnaire);
   }

 }

?>
