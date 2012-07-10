<?php
class Zend_View_Helper_SetupDatePicker{

    function setupDatePicker( $textareaId ) {
        
        return "<script>
	$(function() {
		$('#".$textareaId."').datepicker({dateFormat: 'dd.mm.yy'});
	});
	</script>";
    }
}
?>
