<?php
defined('JPATH_BASE') or die;

JHtml::stylesheet(Juri::base().'components/com_dw_donations/assets/css/ajax_loader.css');

$data=$displayData;
$userId=$data['userId'];
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
		<i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i>
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
			var n_options={status:"danger",timeout:2000,pos:"top-center"};
			jQuery.UIkit.notify(e,n_options);
		}
		  
		// Create our data table out of JSON data loaded from server.
		 var chart_data = new google.visualization.DataTable(jsonData);
		
		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		var options={
			width: '100%', 
			height: 200, 
			legend:{
				position: 'bottom', 
				textStyle: { fontName : 'Open Sans' }
			},
			hAxis:{ textStyle: { fontName : 'Open Sans' } },
			vAxis:{ textStyle: { fontName : 'Open Sans' } },
			tooltip:{ textStyle: { fontName : 'Open Sans' } },
			chartArea: {
				left:'30',
				right:'30',
				width:'100%'
			}
		};
		
		chart.draw(chart_data, options);
		
		jQuery(window).resize(function(e) {
			chart.draw(chart_data, options);
		});
    }
	

</script>
