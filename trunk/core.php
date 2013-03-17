<?php

class Core{

    public static function path2fm($_path){
        $contenu   = tool_files::file_load($_path);
        $document  = new document_xml($_path,$contenu);
        $structure = $document->__get_structure();
        //    $signature = $document->_get_signature();
        $test = new RecursiveIteratorIterator($structure,RecursiveIteratorIterator::SELF_FIRST);
        $factory_mot = new factory_mot();
        foreach($test as $id_bloc=>$bloc_xhtml){
            $texte = tool_tree::tree_xhtml2texte($bloc_xhtml->__get_value());
            $texte = tool_string::clean_guill($texte);
            $texte = tool_string::clean_non_end($texte);
            $texte = tool_string::clean_digit($texte);
            $texte = tool_string::exception_lg($texte, Document_xml::path2lg($_path));
            $texte = tool_string::clean_strict_end_separator($texte);
            $texte = tool_string::clean_end_separator($texte);
            $factory_mot->get_all_mots($texte);
        }
        return $factory_mot;
    }

}
?>
