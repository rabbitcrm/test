

<?php /*?><script>

$(function () {
    $("#scheduler").kendoScheduler({
        date: new Date(Date.now()),
        startTime: new Date(2013, 5, 13, 9, 0, 0, 0),
        height: 800,
        timezone: "Etc/UTC",
        dataSource: {
            transport: {
                read: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    type: "POST"
                },
                update: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    type: "POST"
                },
                create: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    type: "POST"
                },
                destroy: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    type: "POST"
                },
                parameterMap: function (options, operation) {
                    if (operation === "read") {
                        var scheduler = $("#scheduler").data("kendoScheduler");
                        var result = {
                            start: scheduler.view().startDate(),
                            end: scheduler.view().endDate()
                        }
                        return kendo.stringify(result);
                    }
                    return kendo.stringify(options);
                }
            },
            error: error_handler,
                schema: {
                    model: {
                        id: "ID",
                fields: {
                    ID: { type: "number" },
                    title: { from: "Title", defaultValue: "No title", validation: { required: true } },
                    start: { type: "date", from: "Start" },
                    end: { type: "date", from: "End" },
                    description: { from: "Description" },
                    recurrenceId: { from: "RecurrenceID" },
                    recurrenceRule: { from: "RecurrenceRule" },
                    recurrenceException: { from: "RecurrenceException" },
                    ownerId: { from: "OwnerID", defaultValue: 1 },
                    isAllDay: { type: "boolean", from: "IsAllDay" }
                        }
                    }
                }

            },

        });
    });

</script> 




<script>
			$(function() {

  $('.k-event-template').click(function()
  {
	  $url = $(this).data("url");
	  window.location.replace($url);
  });
    $('.k-event-template').dblclick(function()
  {
	  $url = $(this).data("url");
	  window.location.replace($url);
  });
  });
			</script>
<script>
$("#scheduler").kendoScheduler({
	toolbar: ["pdf"],
  pdf: {
    author: "RabbitCRM"
  },
	 views: [
            "day",
           
            "week",
            "workWeek",
			 {
			type: "month", selected: true },
            "agenda",
            { type: "timeline", eventHeight: 50}
        ],
  dataSource: [
  
  <?php if ($tasks[0]) { foreach($tasks as $task) {
	   $str = str_replace('"','\'',$task->associate_to."-".$task->description);$str = str_replace("'",'\'',$str); ?>
    {
      id: <?=$task->task_id;?>,
      start: new Date("<?=$task->due_date;?> "),
      end: new Date("<?=$task->due_date;?> "),
      title: "<?=$str;?> ",
	  url:"<?=$task->url;?>",
	  description: "<?=$task->task_name;?> "
    },
	<?php } } ?>
	
	
  ]
  ,
  editable: {
    resize: false
  }
});
var scheduler = $("#scheduler").data("kendoScheduler");
scheduler.date(new Date("<?=$getCurrTime;?>"));



  

</script>


<?php */?>

<?php /*?><script>

$.getScript('<?=base_url()?>assets/js/calendar/cal.js',function(){
  
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  
  $('#calendar').fullCalendar(
  
  {
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    editable: true,
    events: [
	
	  <?php if ($tasks[0]) { foreach($tasks as $task) {
	   $str = str_replace('"','\'',$task->associate_to."-".$task->description);$str = str_replace("'",'\'',$str);
	   $str =" (".$task->due_time.") ".$str;
	    ?>
	
      {
        title: '<?=$str;?>',
        start: new Date('<?=$task->due_date;?>'),
        end: new Date('<?=$task->due_date;?>'),
        url: '<?=$task->url;?>'
      },
	  
	  	<?php } } ?>
		
		
     
    ]
  });
})
</script><?php */?>


<link href='<?=base_url()?>assets/js/calendar/fullcalendar.css' rel='stylesheet' />
<link href='<?=base_url()?>assets/js/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?=base_url()?>assets/js/calendar/lib/moment.min.js'></script>
<script src='<?=base_url()?>assets/js/calendar/lib/jquery.min.js'></script>
<script src='<?=base_url()?>assets/js/calendar/fullcalendar.min.js'></script>


<script>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: "<?=date("Y-m-d")?>",
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				  <?php if ($tasks[0]) { foreach($tasks as $task) {
	   $str = str_replace('"','\'',$task->associate_to."-".$task->description);$str = str_replace("'",'\'',$str);
	 //  $str =" (".$task->due_time.") ".$str;
	    ?>
				{
				 title: '<?=$str;?>',
        start: '<?=$task->Start;?>',
    url: '<?=$task->url;?>',
	 color: '<?=$task->color;?>' 
 
					},
				
				<?php } } ?>
				
				
			],

			 timeFormat: 'h:mmT'
		});
		
	});

</script>




<?php /*?><script>


$(function() {
    $("#scheduler").kendoScheduler({
        date: new Date("<?=$getCurrTime;?>"),
		dateHeaderTemplate: kendo.template("<strong>#=kendo.toString(date, 'd')#</strong>"),
        startTime: new Date("<?=$getCurrTime?> 01:00 AM"),
        height: 600,
        views: [
            "day",
            { type: "workWeek", selected: true },
            "week",
            "month",
            "agenda",
            { type: "timeline", eventHeight: 50}
        ],
        timezone: "Etc/UTC",
        dataSource: {
            batch: true,
            transport: {
                read: {
                    url: "<?=base_url()?>calendar/json",
                    dataType: "json"
                },
                update: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks/update",
                    dataType: "jsonp"
                },
                create: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks/create",
                    dataType: "jsonp"
                },
                destroy: {
                    url: "http://demos.telerik.com/kendo-ui/service/tasks/destroy",
                    dataType: "jsonpp"
                },
                parameterMap: function(options, operation) {
                    if (operation !== "read" && options.models) {
                        return {models: kendo.stringify(options.models)};
                    }
                }
            },
            schema: {
                model: {
                    id: "taskId",
                    fields: {
                        taskId: { from: "TaskID", type: "number" },
                        title: { from: "Title", defaultValue: "No title", validation: { required: true } },
                        start: { type: "date", from: "Start" },
                        end: { type: "date", from: "End" },
                        startTimezone: { from: "StartTimezone" },
                        endTimezone: { from: "EndTimezone" },
                        description: { from: "Description" },
                        recurrenceId: { from: "RecurrenceID" },
                        recurrenceRule: { from: "RecurrenceRule" },
                        recurrenceException: { from: "RecurrenceException" },
                        ownerId: { from: "OwnerID", defaultValue: 1 },
                        isAllDay: { type: "boolean", from: "IsAllDay" }
                    }
                }
            },
            filter: {
                logic: "or",
                filters: [
                    { field: "ownerId", operator: "eq", value: 1 },
                    { field: "ownerId", operator: "eq", value: 2 }
                ]
            }
        },
        resources: [
            {
                field: "ownerId",
                title: "Owner",
                dataSource: [
                    { text: "Alex", value: 1, color: "#f8a398" },
                    { text: "Bob", value: 2, color: "#51a0ed" },
                    { text: "Charlie", value: 3, color: "#56ca85" }
                ]
            }
        ]
    });

    $("#people :checkbox").change(function(e) {
        var checked = $.map($("#people :checked"), function(checkbox) {
            return parseInt($(checkbox).val());
        });
		
        var scheduler = $("#scheduler").data("kendoScheduler");

        scheduler.dataSource.filter({
            operator: function(task) {
                return $.inArray(task.ownerId, checked) >= 0;
            }
        });
    });
});
</script><?php */?>


