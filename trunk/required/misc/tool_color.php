<?php

class tool_color{
   public $step_color=6;

   public $generator_grey=array(
    "r"=>0, "g"=>0, "b"=>0
   );

   public $id_color=0;

   public static $array_color=array(
   "#ff0000","#00ff00","#0000ff","#ffff00","#00ffff","#ff00ff",
   "#ff2222","#22ff22","#2222ff","#ffff22","#22ffff","#ff22ff",
   "#ff4444","#44ff44","#4444ff","#ffff44","#44ffff","#ff44ff",
   "#ff6666","#66ff66","#6666ff","#ffff66","#66ffff","#ff66ff",
   "#ff8888","#88ff88","#8888ff","#ffff88","#88ffff","#ff88ff",
   "#ffaaaa","#aaffaa","#aaaaff","#ffffaa","#aaffff","#ffaaff",
   "#ffcccc","#ccffcc","#ccccff","#ffffcc","#ccffff","#ffccff",
   "#ffeeee","#eeffee","#eeeeff","#ffffee","#eeffff","#ffeeff",
   "#000000","#333333","#666666","#999999","#bbbbbb","#eeeeee"
    );

   public function __get_next_lighter_grey(){
    $res="#";
    foreach($this->generator_grey as $value) $res.=dechex($value);
   
    if($this->generator_grey["r"]<255){
      foreach($this->generator_grey as $prim => $value){
        $this->generator_grey[$prim]+=$this->step_color;
        if($this->generator_grey[$prim]>255) $this->generator_grey[$prim]=255;
      }
    }
    return $res;
   }

   public function __get_color($_id){
     return self::$array_color[$_id];
   }

   public function __set_id($_id){
    $this->id_color=$_id;
   }

   public function __get_next_color(){
    return $this->array_color[$this->id_color++];
   }

}

?>
