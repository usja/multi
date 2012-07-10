<?php
class Zend_View_Helper_SetupDateTimePicker{

    function setupDateTimePicker( $textareaId ) {
        
        return "<script>
	$(function() {
		$('#".$textareaId."').datetimepicker();
	});
	</script>";
    }
}
?>
