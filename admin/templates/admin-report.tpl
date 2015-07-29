{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">

        <div class="col-md-3 sidebar">
            <h2>Riport</h2>

            <form method="post" action="/admin/report/" id="reportform">
				<input type="hidden" name="xml" value="0" />
            	<div class="form-group">
            		<label>Kezdő időpont</label>
            		<input type="text" name="from_date" id="reportFrom" class="form-control" value="{$from_date}">
            	</div>
            	<div class="form-group">
            		<label>Záró időpont</label>
            		<input type="text" name="to_date" id="reportTo" class="form-control" value="{$to_date}">
            	</div>
            	<div class="form-group ui-widget">
            		<label>Felhasználók</label>
            		<input type="text" name="username" id="username" class="form-control" value="{$default_user}">
            	</div>
            	<div class="form-group">
            		<label>Pályák</label>
            		<div class="report-courts">
						{foreach from=$courts item=court name=courts}
	            		<div class="checkbox">
		            		<label>
		            			<input type="checkbox" name="courts[{$smarty.foreach.courts.index}]" value="{$court->id}"> {$court->name}
		            		</label>
		            	</div>
						{/foreach}
		            </div>
            	</div>
            	<input type="submit" id="getReport" value="Lekérdezés" class="btn btn-warning pull-right"/>
            </form>
        </div>

        <div class="col-md-9 col-md-offset-3">
            <div class="row" id="checkout-content">
                <div class="clearfix reports-display">
                    <div class="col-md-4 display text-left">
                        <span class="small-label">Alapadatok</span><br>
                        <strong>Készítette: </strong><span id="adminName" ></span><br>
                        <strong>Dátum: </strong><span id="reportDate" ></span><br>
                        <strong>Riport intervallum:</strong><br>
                        <span id="reportDateFrom" ></span> - <span id="reportDateTo" ></span><br>
                    </div>
                    <div class="col-md-4 display text-center">
                        <span class="small-label">Riport adatok</span><br>
                        <strong>Felhasználók:</strong><br>
                        <span id="reportUsers"></span><br/>
                        <strong>Pályák:</strong><br>
                        <span id="reportCourts"></span>
                    </div>
                    <div class="col-md-4 display last text-right">
                        <span class="small-label">Riport statisztika</span><br>
                        <span id="reservationsCount" class="value"></span> foglalás<br/>
                        <span id="reservationsIncome" class="value"></span> Ft értékben<br/>

                        <button type="button" class="btn btn-success checkout-btn btn-xs" style="margin-top: 10px;" onclick="document.forms['reportform'].elements['xml'].value='1'; document.forms['reportform'].submit();">Exportálás XLS-be</button>
                  	</div>
                </div>
            </div>

			<table class="table table-striped" id="reportTable">
				<thead>
					<tr>
						<th>Felhasználónév</th>
						<th>Foglalás dátuma</th>
						<th>Pálya</th>
						<th>Összeg</th>
					</tr>
				</thead>
				<tbody id="reportTableBody"></tbody>
			</table>

			<p id="emptyDataWarning" style="margin-top: 60px;" class="text-center">Nincs megjeleníthető adat.</p>
        </div>
    </div>
</div>

<script type="text/javascript">
	{literal}
	var updateData = function (data) {
		var tableContent = '';

		$('#adminName').text(data.adminUser);
		$('#reportDate').text(data.date);
		$('#reportDateFrom').text(data.dateFrom);
		$('#reportDateTo').text(data.dateTo);
		$('#reportUsers').text(data.users.join(', '));
		$('#reportCourts').text(data.courts.join(', '));
		$('#reservationsCount').text(data.reservations.length);
		$('#reservationsIncome').text(data.reservationsIncome);

		if (data.reservations && data.reservations.length > 0) {
			for (var i = 0; i < data.reservations.length; i++) {
				var res = data.reservations[i];
				tableContent += '<tr><td>' + res.username + '</td><td>' + res.timeunit.date + '</td><td>' + res.court.title + '</td><td>' + res.price + ' Ft</td></tr>';
			}

			$('#reportTableBody').html(tableContent);
			$('#reportTable').show();
			$('#emptyDataWarning').hide();
		} else {
			$('#reportTable').hide();
			$('#emptyDataWarning').show();
		}

	}

	// updateData({
	// 	"adminUser":"admin",
	// 	"date":"2015-07-07",
	// 	"dateFrom":"2015-01-01",
	// 	"dateTo":"2015-07-01",
	// 	"users": [],
	// 	"courts":[
	// 	  	"Verseny 3",
	// 	  	"Verseny 2",
	// 	  	"Verseny 1"
	// 	],
	// 	"reservationsCount":20,
	// 	"reservations":[
	// 	  	{
	// 	    	"id":"11",
	// 	      	"status":"1",
	// 		    "username":"fillter11",
	// 		    "price":"2000",
	// 		    "add_date":"2015-05-26 21:29:14",
	// 		    "last_status_update":"0000-00-00 00:00:00",
	// 		    "timeunit":{
	// 		        "interval":"03:00 - 04:30",
	// 		        "intervalBlocks":3,
	// 		        "reservationId":"24",
	// 		        "date":"2015-05-26",
	// 		        "status":"reservable",
	// 		        "price":2000,
	// 		        "orig_price":"2000",
	// 		        "reservedBy":null,
	// 		        "prereservedExpire":null,
	// 		        "message":"",
	// 		    },
	// 	     	"court":{
	// 	        	"id":"1",
	// 		        "title":"Verseny 3",
	// 		        "subtitle":""
	// 		    }
	// 	  	},
	// 	],
	// 	"reservationsIncome":27800
	// });

	$(function() {
	    var users = [
	    	'Géza',
	    	'József',
	    	'Jónás',
	    	'Jófej',
	    	'Elemér'
	    ];

	    $("#reportFrom, #reportTo").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});

	    function split( val ) {
	      	return val.split( /,\s*/ );
	    }

	    function extractLast( term ) {
	      	return split( term ).pop();
	    }

	    $( '#username' )
			// don't navigate away from the field on tab when selecting an item
		    .bind( 'keydown', function( event ) {
		        if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( 'instance' ).menu.active ) {
		        	event.preventDefault();
		        }
		    })
		    .autocomplete({
		        minLength: 0,
		        source: function( request, response ) {
		        	console.log(1)
		          	// delegate back to autocomplete, but extract the last term
		          	response( $.ui.autocomplete.filter(users, extractLast( request.term )));
		        },
		        focus: function() {
		        	console.log(2)
		          	// prevent value inserted on focus
		          	return false;
		        },
		        select: function( event, ui ) {
		        	console.log(3)
		          	var terms = split( this.value );
		          	// remove the current input
		          	terms.pop();
		          	// add the selected item
		          	terms.push( ui.item.value );
		          	// add placeholder to get the comma-and-space at the end
		          	terms.push( '' );
		          	this.value = terms.join( ', ' );
		          	return false;
		        }
      	});
  	});

	$("#getReport").click(function(e) {
		e.preventDefault();
		
		document.forms['reportform'].elements['xml'].value='0';
		var data = $("#reportform").serialize();

		$.ajax({
			type: "POST",
			url: "/admin/report/",
			data: data,
			success: function(resp){
				updateData(resp);
			}
		});
	});

	{/literal}
	{if $smarty.get.did!=""}
		$("#getReport").click();
	{/if}
</script>

{include file="footer.tpl"}
