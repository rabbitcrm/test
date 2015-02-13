<script src="<?=base_url()?>assets/js/highcharts.js"></script>
<script src="<?=base_url()?>assets/js/funnel.js"></script>
<script src="<?=base_url()?>assets/js/exporting.js"></script>


<script>

$(function () {
    $('#sales_pipeline').highcharts({
		
		
        chart: {
            type: 'funnel',
            marginRight: 100
        },
        title: {
            text: '',
            x: -50
        },
        plotOptions: {
            series: {
				
                dataLabels: {
                    enabled: true,
                    format: '{point.name} ({point.y:,.0f})',
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                    softConnector: true,
					
					
          },
		 
                cursor: 'pointer',
                events: {
                    click: function () {
                       
                    }
                },
		
                neckWidth: '30%',
                neckHeight: '25%'
                
                //-- Other available options
                // height: pixels or percent
                // width: pixels or percent
            }
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Opportunities',
			dataType: "json",
            data: [ <?php if ($pipelinechart[0]) { 
	for($h=0;count($pipelinechart)>$h;$h++)
	{
		echo "['".$pipelinechart[$h]['Department']."',".$pipelinechart[$h]['Budget']."],";
	 } ?><?php } else { ?>
 				['Negotiation',15654],
                ['Purchasing',4064],
                ['Won',1987],
                ['Lost',976],
                ['Archieved',846]
              <?php } ?> 
            ]
        }]
    });
	
	
	
	$('.highcharts-series-group path').hover(
    function(){
		var div=$(this).attr('id');
		var fill=$(this).attr('fill');
		
		var tspan=$('#'+div+'tspan').html();
		
		//alert(tspan);
		$('#color').val(fill);
		//$('#'+div).css('fill', '#00C8C8');
		$('#'+div).css('stroke-width', '10px'); }, // over
    function(){
		var div=$(this).attr('id');
		var fill=$(this).attr('fill');
		 $('#'+div).css('fill', fill);
		$('#'+div).css('stroke-width', '1px');
		}  // out
);
$('.highcharts-series-group path').click(function(){
	var div=$(this).attr('id');
	//window.location.replace(appBaseUrl+'deals');

});
});
</script>



<table id="datatable" style="display:none">
	<thead>
		
	</thead>
    
    <?php if ($PieChart[0]!="") {
		?>
	<tbody>
		
        <?php foreach($PieChart as $PieCharts){ ?>
        <?php if($PieCharts[1]!="") {?>
        
          <tr>
			<th><?=$PieCharts[0]?></th>
			<td><?=$PieCharts[1]?></td>
			
		</tr>
         <?php } } ?> </tbody><?php } else{ ?>
        

	
    
    <tbody>
		
        
                            
          <tr>
			<th>High</th>
			<td>84</td>
			
		</tr>
                         
          <tr>
			<th>Low</th>
			<td>48</td>
			
		</tr>
                         
          <tr>
			<th>Medium</th>
			<td>60</td>
			
		</tr>
                 

	</tbody>
    <?php } ?>
</table>

<script>

$(function () {
    $('#cases_priority12').highcharts({
        data: {
            table: document.getElementById('datatable')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Units'
            }
        },
        tooltip: {
            formatter: function () {
                return this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
	
	
});
</script>


<script src="http://code.highcharts.com/modules/data.js"></script>
<script>

$(document).ready(function () {    

           // Radialize the colors
            RenderPieChart( <?php if($datatablecharts[0]!=""){ ?>'container',[<?php foreach($datatablecharts as $datatablechart){ ?>
		["<?=$datatablechart[1]?>",<?=$datatablechart[0]?>], <?php } ?>]<?php }else { ?>'container',[		["Alibaba",960], 		["Cold Call",171], 		["Direct Mail",150], 		["EC21",200], 		["FaradayIns site",200], 		["FaradayInstruments site",180], 		["Import",500] ] <?php } ?>);     
     
            function RenderPieChart(elementId, dataList) {
				
                new Highcharts.Chart({
                    chart: {
                        renderTo: elementId,
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    }, title: {
                        text: ''
                    },
                    tooltip: {
                        formatter: function () {
							
							
                            return '<b>' + this.point.name + '</b>: ' + this.point.y+ '</b>: cases/' +this.point.y;
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                color: '#000000',
                                connectorColor: '#000000',
                                formatter: function () {
									
                                    return this.point.name + ': ' + this.point.y ;
                                }
                            }
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: '',
                        data: dataList
                    }]
                });
            };
			
			
        });




</script>


<script>
$(function () {
	
    $('#cases_priority1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
			<?php if($PieChart==""){ ?>
                'Jan',
                'Feb',
                'Mar',
                'Apr'
				<?php }else{ 
				$count=0;
				 $datasHigh="";
						 $datasMedium="";
						  $datasLow="";
			$m=0;		
foreach($PieChart as $PieCharts){
			if($m!=0)
		{
		if($g==1)
		{
			$datasMedium.="0,";
			$datasLow.="0,";
		}
		}
	$PieCharts1data=array();
		$priority=array();
		
		$count=0;


		$g=0;
	foreach($PieCharts as $PieCharts1){
		if (!in_array($PieCharts1['1'],$PieCharts1data)) {
			$m++;
			$count++;
		if($PieCharts1[0]=="High") {
			$datasHigh.= $PieCharts1['2'].",";
			$g=1;
		}
		else if($PieCharts1['0']=='Medium'){
			$datasMedium.= $PieCharts1['2'].",";
			$g=2;
			$datasHigh.="0,";
			$y=2;
		}
		else if($PieCharts1['0']=='Low'){
			 $datasLow.= $PieCharts1['2'].",";
			 $g=4;
			 $datasHigh.="0,";
			$datasMedium.="0,";
		}	
		$priority[]=$PieCharts1['0'];	
		$PieCharts1data[]=$PieCharts1['1'];	
		$PieCharts1data1=$PieCharts1['1'];	
		?>'<?=$PieCharts1data1?>',<?php
		}
		else
		{
			if($PieCharts1['0']=='Medium'){
			if($g=='1'){
			$datasMedium.= $PieCharts1['2'].",";
			$g=4;
				}
		}
		else if($PieCharts1['0']=='Low'){

			if($g==1)
			{
			$datasMedium.="0,";
			}
			$g=3;

			 $datasLow.= $PieCharts1['2'].",";
		}
		}
		
						
						 }} }?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall ()'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        
			<?php if($PieChart==""){ ?>
			series: [{
                name: 'Tokyo',
            data: [49.9, 71.5, 106.4, 106.4]

        }, {
            name: 'New York',
            data: [83.6, 78.8, 98.5, 106.4]

        }, {
            name: 'London',
            data: [48.9, 38.8, 39.3, 106.4]}]
				<?php }else{ ?>
				
				series: [
			   {
            name: ' High',
            data: [ <?=$datasHigh; ?>]

        },
		 {
            name: ' Medium',
            data: [<?=$datasMedium; ?>]

        },
		 {
            name: ' Low',
            data: [<?=$datasLow; ?>]

        }
			   
			   ] <?php }  ?>
			   
           

        });
});
</script>
<style>
.highcharts-legend-item
{
    display:none;
}
#line-chat
{
	display:none;
}
tspan {
font-size: 9px;
}

</style>