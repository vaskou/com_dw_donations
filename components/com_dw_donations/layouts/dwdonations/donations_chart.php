<?php
defined('JPATH_BASE') or die;

JHtml::stylesheet(Juri::base().'components/com_dw_donations/assets/css/ajax_loader.css');

$data=$displayData;
$userId=$data['userId'];
$isBeneficiaryDonations=$data['isBeneficiaryDonations'];
?>

<?php

$current_month=0;//JFactory::getDate('now')->format('m');
$current_year=JFactory::getDate('now')->format('Y');

$date=array('year'=>$current_year,'month'=>$current_month);

$data=array(JSession::getFormToken()=>'1','date'=>$date);
$jsonData=json_encode($data);

?>

<div id="chart_div" style="position:relative;width:100%;height:200px;">
	<div class="ajax-loader">
		<img src="<?php echo JUri::base().COMPONENT_PATH.'/assets/images/loader.gif';?>" />
    </div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
		var data=<?php echo $jsonData; ?>;
		var jsonData = jQuery.ajax({
			url: 'index.php?option=com_dw_donations&task=dwdonations.fn_get_annualy_chart_data',
			type:'POST',
			data:data,
			dataType:"json",
			async: false
			}).responseText;
		
		try{
			response=jQuery.parseJSON(jsonData);
			if(response.success){
				respData=response.data;
				jsonData=JSON.stringify(respData);
			}
		}catch(e){
			document.open();
			document.write(jsonData);
			document.close();
		}
		  
		// Create our data table out of JSON data loaded from server.
		 var data = new google.visualization.DataTable(jsonData);
		
		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
		chart.draw(data, {width: '100%', height: 200});
    }

</script>
