{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-12">
        	<h2>Idősávok</h2>
        	<hr>
			<div class="form-inline">
				<div class="form-group">
					Kiválasztott nap: <input type="text" name="date" id="date" value="{$selected_date}" class="form-control"/>
				</div>
			</div>
			<hr>

			<form id="clone_form" class="form-inline">
				<table class="table table-striped timeline">
				<tbody>
					<tr>
					{foreach from=$courts item=court}
						<td><strong>{$court->name}</strong><br /><small>{$court->subtitle}</small></td>
					{/foreach}
					</tr>
					{foreach from=$times item=time}
						{if $time.0>=8 && $time.0<=20}
							<tr>
							{foreach from=$courts item=court}
								{assign var="court_id" value=$court->id}
								{assign var="timeunits_court" value=$timeunits[$court_id]}
								{assign var="timeunit" value=$timeunits_court[$time.0]}
								<td class="timetd{if is_object($timeunit)} {$timeunit->get_timetd_class($time.0)}{/if}" {if is_object($timeunit)}data-id="{$timeunit->id}" {/if}data-from="{$time.0}" data-court="{$court->id}">
									<div class="time">{$time.0}-{$time.1}</div>
									{if $timeunit->reserved_by!=""}<div>{$timeunit->reserved_by}</div>{/if}
									{if $timeunit->comment!=""}<div class="comment">{$timeunit->comment}</div>{/if}
									{if $timeunit->available==1 && $timeunit->booked==""}<div class="price">{$timeunit->price} Ft</div>{/if}
								</td>
							{/foreach}
							</tr>
						{/if}
					{/foreach}
					<tr>
					{foreach from=$courts item=court}
						<td>{$court->name}<br /><small>{$court->subtitle}</small><br /><input type="checkbox" name="clone_court[]" value="{$court->id}" /></td>
					{/foreach}
					</tr>
				</tbody>
				</table>

				<hr>

				<div class="row">
					<div class="form-group col-md-4">
						<h3>Klónozás</h3>
						<p>Kijelölt pályák klónozása a következő időszakra</p>
						<p><input type="text" name="clone_from" id="clone_from" value="" class="form-control" style="max-width: 30%"/> - <input type="text" name="clone_to" id="clone_to" value="" class="form-control" style="max-width: 30%" /></p>
						<input type="button" value="Mehet" id="clone_btn" class="btn btn-warning"/>
					</div>

					<div class="form-group col-md-4">
						<h3>Teljes nap törlése</h3>
						<p>Kijelölt pályák teljes kiválasztott nap idősávjainak törlése</p>
						<input type="button" value="Törlés" id="delete_btn" class="btn btn-danger"/>
					</div>

					<div class="form-group col-md-4">
						<h3>Rendezvény lérehozása</h3>
						<p>Rendezvény neve</p>
						<p><input type="text" name="competition_name" class="form-control" style="width: 80%"/></p>
						<p>Rendezény dátum intervalluma</p>
						<p><input type="text" name="competition_from" id="competition_from" value="" class="form-control" style="max-width: 30%"/> - <input type="text" name="competition_to" id="competition_to" value="" class="form-control" style="max-width: 30%" /></p>
						<p>Rendezvény idő intervalluma</p>
						<p><select class="form-control" name="competition_fromt">{foreach from=$times item=from}<option value="{$from.0}">{$from.0}</option>{/foreach}</select> - <select class="form-control" name="competition_tot">{foreach from=$times item=to}<option value="{$to.0}">{$to.0}</option>{/foreach}</select></p>
						<p class="competition_reservation_delete" style="display: none;">Foglalások törlése?</p>
						<p class="competition_reservation_delete" style="display: none;"><input type="checkbox" name="confirm" value="true" /> Igen, törölni szeretném a rendezvény ideje alatti foglalásokat</p>
						<input type="button" value="Mehet" id="competition_create_btn" class="btn btn-warning"/>
					</div>
				</div>
			</form>

        </div>
    </div>
</div>




<script type="text/javascript">
	{literal}
	$("#date").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});
	$("#date").change(function(){
		location.href='/admin/timeline/?selected_date='+$(this).val();
	});
	$("#clone_from").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});
	$("#clone_to").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});
	$("#competition_from").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});
	$("#competition_to").datepicker({"dateFormat": "yy-mm-dd","firstDay": "1"});
	$(".timetd").click(function(){
		var id = $(this).attr('data-id');
		if(typeof(id)!="undefined"){
			var url = '/admin/timeline/modify/?did='+id;
		}else{
			var from = $(this).attr('data-from');
			var court = $(this).attr('data-court');
			var url = '/admin/timeline/new/?from='+from+'&court='+court;
		}
		$.facebox({ ajax: url });
	});
	$("#clone_btn").click(function(){
		var data = $("#clone_form").serialize();
		$.ajax({
			type: "POST",
			url: "/admin/timeline/clone/",
			data: data,
			success: function(resp){
				alert(resp);
			}
		});
	});
	$("#delete_btn").click(function(){
		if(confirm('Biztosan törli a kijelölt pályákon lévő idősávokat?')){
			var data = $("#clone_form").serialize();
			$.ajax({
				type: "POST",
				url: "/admin/timeline/deleteday/",
				data: data,
				success: function(resp){
					if(resp=="ok"){
						alert('Sikeres törlés!');
						location.href='/admin/timeline/';
					}else{
						alert(resp);
					}
				}
			});
		}
	});
	$("#competition_create_btn").click(function(){
		var data = $("#clone_form").serialize();
		$.ajax({
			type: "POST",
			url: "/admin/timeline/competition/",
			data: data,
			dataType: "json",
			success: function(resp){
				if(resp.success==false){
					if(resp.error_type==5){
						$(".competition_reservation_delete").show();
					}
					alert(resp.error);
				}else{
					alert("Sikeres rendezvény létrehozás!");
					location.href='/admin/timeline/';
				}
			}
		});
	});
	{/literal}
</script>

{include file="footer.tpl"}
