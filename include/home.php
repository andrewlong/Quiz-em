<?php
//check browser
if(eregi("chrome", $_SERVER['HTTP_USER_AGENT']) || eregi("firefox", $_SERVER['HTTP_USER_AGENT']))
{
	//using firefox or chrome
}else
{	$html = "Please download <a href='http://www.google.com/chrome/'>Chrome</a> or 
	<a href='http://www.mozilla.org/en-US/firefox/'>Firefox</a> for enhanced viewing.";
	print alert('danger',$html);
}
?>
<form id="getquiz">
<div class="panel panel-default">
 
  <div class="panel-heading">Quizes
  <select id="year" class="form-control-xs pull-right input-xs col-lg-3">
  <?php 
  	$year = mktime(0, 0, 0, 1, 1, date("Y"));

	while ($year > mktime(0, 0, 0, 1, 1, 2011) ) //first year before start
	{
		print "<option value=\"" . date("Y",$year) . "\">" . date("Y",$year) . "</option>";
		$year = strtotime ( "-1 year" , $year ) ;	
	}
	?>
	</select>
  </div>
	</form>
   <div class="panel-body">
   These are your quizes
   </div>
   <div id="quizes"></div>
 </div> <!-- /container -->
 <script>
$("#year").on("change", function() {
	$( "#quizes" ).html( "<table><tr><td>Loading...</td></tr></table>");
    $.ajax({
        url: "include/get_user_quizes.php",
        data: {year:$("#getquiz option:selected").val()},
        type: "post",
        success: function(data, textStatus, jqXHR)
        {
        	$( "#quizes" ).html( data );
        }
    });
    
}).trigger('change');

</script>