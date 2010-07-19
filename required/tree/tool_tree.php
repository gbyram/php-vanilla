<?php

//*****************************
//*****************************
// TREE
//*****************************
//*****************************

 class Tool_tree{

    public static function tree2txt($_tree){
     $res=$_tree->__get_value()."->".$_tree->__tostring()."\n";
     $test=new RecursiveIteratorIterator($_tree,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $res.=str_repeat("  ",$test->getDepth()+1);
        $res.=$itree->__get_value()."->".$itree->__tostring()."\n";
     }
     return $res;
    }

    public static function tree2inlinetxt($_tree){
     $array_son=$_tree->__get_array_tree();
     $res="";
     foreach($array_son as $son){$res.=Tool_tree::tree2inlinetxt($son).",";}
     $res=rtrim($res,",");
     $res="[".$_tree->__get_value()."($res)]";
     return $res;
    }

    public static function tree_xhtml2texte($_tree_xhtml){
      $test=new RecursiveIteratorIterator($_tree_xhtml,RecursiveIteratorIterator::SELF_FIRST);
      $res="";
      foreach($test as $tree){
        $value=$tree->__get_value();
        if($value instanceof tag_str){
          $res.=$value->__get_array_attributes()." ";
        }        
      }
      return $res;
    }

    public static function tree2svg(&$_tree){
      $drawer=new drawer_svg();
      return "  <svg xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"
        width=\"".$drawer->dsvg_tree_get_html_width($_tree)."\" 
        height=\"".$drawer->dsvg_tree_get_html_height($_tree)."\">
        ".$drawer->dsvg_tree_draw($_tree)."
      </svg>";
    }

    public static function tree_numeric2svg(&$_tree){
      $drawer=new drawer_svg();
      return "  <svg xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"
        width=\"".$drawer->dsvg_tree_get_html_width($_tree)."\" 
        height=\"".$drawer->dsvg_tree_get_html_height($_tree)."\">
        ".$drawer->dsvg_tree_numeric_draw($_tree)."
      </svg>";
    }

    public static function tree_group_numeric2svg(&$_tree,$_option){
      $drawer=new drawer_svg();
      return "  <svg xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"
        width=\"".$drawer->dsvg_tree_get_html_width($_tree)."\" 
        height=\"".$drawer->dsvg_tree_get_html_height($_tree)."\">
        ".$drawer->dsvg_tree_factory($_tree,$_option)."
      </svg>";
    }

/*
    public static function tree_xhtml2texte($_tree_xhtml){
      $value=$_tree_xhtml->__get_value();
      foreach($_tree_xhtml->__get_array_tree() as $tree){
        $tree_texte=tool_tree::tree_xhtml2texte($tree);
        if(mb_strlen($tree_texte)>0) $res.="$tree_texte ";        
      }
      if($value instanceof tag_str){return $value->__get_array_attributes();}
      else return rtrim($res);
    }
*/
    public static function tree_xhtml2xhtml($_tree_xhtml){
      $value=$_tree_xhtml->__get_value();
      foreach($_tree_xhtml->__get_array_tree() as $tree){
        $res.=self::tree_xhtml2xhtml($tree);
      }
      if($value instanceof tag_str){return $value->__get_array_attributes();}
      else{return " <$value>$res</$value> ";}
    }

    public static function tree_xhtml2signature($_tree_xhtml){
       $tag=$_tree_xhtml->__get_value();
       $signature=$tag->__get_name();
       foreach($_tree_xhtml->__get_array_tree() as $tree){
        $signature.=self::tree_xhtml2signature($tree);
       }
       return "[$signature]";
    }

    public static function tree_xhtml2mfm($_tree_xhtml){
       $array_mfm=$_tree_xhtml->__get_const_mfm();
       // natsort($array_mfm);
       //$signature=$tag->__get_name();
       return "[".implode(",",$array_mfm)."]";
    }

    public static function tree_xhtml2indentsignature($_tree_xhtml){
      $this_tag=$_tree_xhtml->__get_value();
      $indent_signature=$this_tag->__get_name()."\n";
      $test=new RecursiveIteratorIterator($_tree_xhtml,RecursiveIteratorIterator::SELF_FIRST);
      foreach($test as $tree){
        $tag=$tree->__get_value();
        $indent_signature.=str_repeat("----",$test->getDepth()+1).$tag->__get_name()."\n";
      }
      return $indent_signature;
    }

    public static function cleantreexhtml(&$_tree_xhtml){
     $test=new RecursiveIteratorIterator($_tree_xhtml,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $tree){
       $value=$tree->__get_value();
       if($value instanceof tag_str){
         $str=tool_string::clean($value->__get_array_attributes());
         $value->__set_array_attributes($str);
       }
     }

    }

    public static function cleanstructure(&$_structure){
      self::cleantreexhtml($_structure->__get_value());
      $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
      foreach($test as $son){self::cleantreexhtml($son->__get_value());}
    }

    public static function structure2txt($_structure){
     $res=self::tree_xhtml2signature($_structure->__get_value())."\n";
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $res.=str_repeat("  ",$test->getDepth()+1);
        $res.=self::tree_xhtml2signature($itree->__get_value())."\n";
     }
     return $res;
    }


/*
    public static function structure2mfm($_structure){
     echo self::tree_xhtml2mfm($_structure->__get_value())."\n";
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        echo str_repeat("  ",$test->getDepth()+1);
        echo get_class($itree->__get_value());
        echo self::tree_xhtml2mfm($itree->__get_value())."\n";
     }
//     return $res;

    }
*/

    public static function structure2mfm($_structure){
     $res=self::tree_xhtml2mfm($_structure->__get_value())."\n";
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $res.=str_repeat("  ",$test->getDepth()+1);
        $res.=self::tree_xhtml2mfm($itree->__get_value())."\n";
//        $res.=self::tree_xhtml2texte($itree->__get_value())."\n";
     }
     return $res;

    }

    public static function structure2texte($_structure){
     $res=self::tree_xhtml2texte($_structure->__get_value())."\n\n";
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $res.=tool_tree::tree_xhtml2texte($itree->__get_value())."\n\n";
     }
     return $res;
    }

    public static function structure2xhtml($_structure){
     $res = "<html>\n\n";
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $res.=tool_tree::tree_xhtml2xhtml($itree->__get_value())."\n\n";
     }
     $res .= "\n\n</html>";
     return $res;
    }


    public static function structure2segment($_structure){
     $array_res=array();
//     $array_res[]=$_structure->__get_value();
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $array_res[]=$itree->__get_value();
     }
     return $array_res;
    }

    public static function structure2strlen($_structure){
     $array_res=array();
     $str=self::tree_xhtml2texte($_structure->__get_value());
     $array_res[]=mb_strlen($str);
     $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
     foreach($test as $itree){
        $str=tool_tree::tree_xhtml2texte($itree->__get_value());
        $array_res[]=mb_strlen($str);
     }
     return $array_res;
    }

    public static function structure2struct_idn($_structure){
     $_structure->__recalc_idn();
     return $_structure->__clone_idn();
    }

    public static function structure2array_mfm($_structure,$_array_mfm=array()){
      $test=new RecursiveIteratorIterator($_structure,RecursiveIteratorIterator::SELF_FIRST);
      foreach($test as $itree){
        $tree_xhtml=$itree->__get_value();
        $_array_mfm[tool_tree::tree_xhtml2mfm($tree_xhtml)]++;
      }
      return $_array_mfm;    
    }

    public static function structure_mfm2structure_renamed($_structure,$_array_mfm){
      $clone = clone $_structure;
      $test=new RecursiveIteratorIterator($clone,RecursiveIteratorIterator::SELF_FIRST);
      foreach($test as $itree){
        $tree_xhtml=$itree->__get_value();
        $mfm=tool_tree::tree_xhtml2mfm($tree_xhtml);
        $rename=$_array_mfm[$mfm];
        $itree->__set_value($rename);
      }
      return $clone;
    }

    public static function structure_segment2structure($_structure,$_array_seg){
      $clone = clone $_structure;
      $test=new RecursiveIteratorIterator($clone,RecursiveIteratorIterator::SELF_FIRST);
      $cpt=0;
      foreach($test as $itree){
        //$tree_xhtml=$itree->__get_value();
        //$mfm=tool_tree::tree_xhtml2mfm($tree_xhtml);
        //$rename=$_array_mfm[$mfm];
        $itree->__set_value($_array_seg[$cpt++]);
      }
      return $clone;
    }
 }

//*****************************
//*****************************
// FOREST
//*****************************
//*****************************


 class Tool_forest{
    public static function forest2txt($_forest){
     $test=new RecursiveIteratorIterator($_forest,RecursiveIteratorIterator::SELF_FIRST);
     $res="***$_forest***\n";
     foreach($test as $itree){
        $res.=str_repeat("  ",$test->getDepth()+1);
        $res.=$itree->__get_value()."->".$itree->__tostring()."\n";
     }
     return $res;
    }

    public static function forest2txt_light($_forest){
     $test=new RecursiveIteratorIterator($_forest,RecursiveIteratorIterator::SELF_FIRST);
     $res="***$_forest***\n";
     foreach($test as $itree){
        $res.=str_repeat("  ",$test->getDepth()+1);
        $res.=$itree->__get_idn()."->".$itree->__tostring()."\n";
     }
     return $res;
    }

    public static function forest2svg($_forest,$_option){
      $drawer=new drawer_svg();
      $drawer->__set_x_tree_space(1);
      $drawer->__set_y_tree_space(18);
      $drawer->__set_r_tree_circle(5);
      $drawer->__set_font1(8);
      $w=$drawer->dsvg_forest_get_html_width($_forest);
      $h=$drawer->dsvg_forest_get_html_height($_forest);
      $str="<svg xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"
        width=\"$w\" height=\"$h\">
        ".$drawer->dsvg_forest_draw($_forest,$_option)."
      </svg>";
      return array("w"=>$w,"h"=>$h,"str"=>$str); 
    }


    public static function forest2rightdec($_forest){
        if(!$_forest->is_empty()){
            $forest_del_right=$_forest->__del_right();
            $res=self::forest2rightdec($forest_del_right);
        }
        else $res=array(); 
        return array_merge($res,array("$_forest"));
    }

    public static function forest2rightdec2($_forest){
        if(!$_forest->is_empty()){
            $forest_del_right=$_forest->__del_right();
            $res=self::forest2rightdec2($forest_del_right);
        }
        else $res=array(); 
        return array_merge($res,array($_forest));
    }
 }


//*****************************
//*****************************
// TED
//*****************************
//*****************************

 class Tool_ted{
    public static function ted2txt($_ted,$_f,$_g){
        $f_decright=tool_forest::forest2rightdec($_f);
        $g_decright=tool_forest::forest2rightdec($_g);
        $array_res=$_ted->__get_array_res();
        $res="";
        foreach($f_decright as $fdec){
            foreach($g_decright as $gdec) $res.=$array_res[$fdec][$gdec]." ";
            $res.="\n";
        } 
        return $res;
    }
    public static function ted2html($_ted,$_f,$_g){
        $f_decright=tool_forest::forest2rightdec($_f);
        $g_decright=tool_forest::forest2rightdec($_g);
        $array_res=$_ted->__get_array_res();
        $head = $res = "";
        $flag=true;
        foreach($f_decright as $fdec){
            $tr_res="";
            foreach($g_decright as $gdec){
                $tr_res.="<td>".$array_res[$fdec][$gdec]."</td>";
                if($flag) $head.="<th>$gdec</th>";
            }
            $flag=false;
            $res.="<tr><th>$fdec</th>$tr_res</tr>";
        }
        $head="<tr><td/>$head</tr>"; 
        return "<table>$head $res</table>";
    }

   public static function ted2svg($_ted,$_f,$_g,$_option){

     $drawer=new drawer_svg();
     $f_decright=tool_forest::forest2rightdec2($_f);
     $g_decright=tool_forest::forest2rightdec2($_g);
     $array_res=$_ted->__get_array_res();
     $head = $res = "";
     $flag=true;
     foreach($f_decright as $fdec){
       $f_dec_svg=tool_forest::forest2svg($fdec,$_option);
       $w_fdec=$f_dec_svg["w"];
//       $w_fdec=$drawer->dsvg_forest_get_html_width($fdec);
       $fdec="$fdec";
       $tr_res="";
       foreach($g_decright as $gdec){
         $g_dec_svg=tool_forest::forest2svg($gdec,$_option);
//         $w_gdec=$drawer->dsvg_forest_get_html_width($gdec);
         $gdec="$gdec";
         $tr_res.="<td>".$array_res[$fdec][$gdec]."</td>";
         if($flag){
           $head.="
            <td style=\"min-width:".$g_dec_svg["w"]."px;\">
            $g_dec_svg[str]
            </td>
           ";
          }
       }
       $flag=false;
       $res.="
        <tr>
          <td style=\"min-width:".$f_dec_svg["w"]."px;\">
          $f_dec_svg[str]
          </td>$tr_res
        </tr>
       ";
     }
     return "
      <table style=\"border-spacing:0px; border:solid black 1px;\">
        <tr><td/>$head</tr>$res
      </table>
     ";
   }

   public static function ted_trace2svg($_ted,$_f,$_g,$_option){
     $drawer=new drawer_svg();
     $f_decright=tool_forest::forest2rightdec2($_f);
     $g_decright=tool_forest::forest2rightdec2($_g);
     $array_res=$_ted->__get_array_trace();
     $head = $res = "";
     $flag=true;
     foreach($f_decright as $fdec){
       $f_dec_svg=tool_forest::forest2svg($fdec,$_option);
       $w_fdec=$f_dec_svg["w"];
//       $w_fdec=$drawer->dsvg_forest_get_html_width($fdec);
       $fdec="$fdec";
       $tr_res="";
       foreach($g_decright as $gdec){
         $g_dec_svg=tool_forest::forest2svg($gdec,$_option);
//         $w_gdec=$drawer->dsvg_forest_get_html_width($gdec);
         $gdec="$gdec";
         $tr_res.="<td>".$array_res[$fdec][$gdec]."</td>";
         if($flag){
           $head .= "<td style=\"min-width:".$g_dec_svg["w"]."px;\"> $g_dec_svg[str] </td>";
         }
       }
       $flag = false;
       $res .= "<tr><td style=\"min-width:".$f_dec_svg["w"]."px;\">$f_dec_svg[str]</td>$tr_res</tr>";
     }
     return "<table style=\"border-spacing:0px; border:solid black 1px;\"><tr><td/>$head</tr>$res</table>";
   }

 }

?>
