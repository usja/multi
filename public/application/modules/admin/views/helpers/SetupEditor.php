<?php
class Zend_View_Helper_SetupEditor {

    function setupEditor( $textareaId ) {
        
        return "<script type=\"text/javascript\">
   var ed". $textareaId ." =  CKEDITOR.replace( '". $textareaId ."' );

        </script>";
    }
}
?>
