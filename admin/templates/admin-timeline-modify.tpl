<div class="dialog">
<h2>{if $smarty.get.a2id=="new"}Új idősáv létrehozása{else}Idősáv módosítása{/if}</h2>
<hr>

<div id="statusbar" class="alert alert-danger" role="alert"></div>

<form method="post" id="time_form" class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-4 control-label">Kezdési időpont</label>
		<div class="col-sm-4"><p class="form-control-static">{$timeunit->from}</p></div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">Vég időpont</label>
		<div class="col-sm-4"><select class="form-control" name="to">{foreach from=$timeunit->get_tos() item=to}<option x="{$timeunit->to}" value="{$to}"{if $to==$timeunit->to} selected{/if}>{$to}</option>{/foreach}</select></div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Ár</label>
		<div class="col-sm-4"><input class="form-control text-right" type="text" name="price" value="{$timeunit->price}" /></div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label" for="available">Foglalható</label>
		<div class="col-sm-8"><input class="form-control" type="checkbox" name="available" value="1"{if $timeunit->available==1} checked{/if} /></div>
	</div>

	<div class="form-group">
		<label class="col-sm-4 control-label">Megjegyzés<br><small>(pl. verseny neve)</small></label>
		<div class="col-sm-8"><input class="form-control" type="text" name="comment" value="{$timeunit->comment}" /></div>
	</div>

	<hr>

	<input type="button" id="sbm" value="Mentés" class="btn btn-success pull-right" />

	{if $timeunit->id}
		<input type="button" id="sbm_delete" value="Idősáv törlése" class="btn btn-danger pull-left"/>
		{if $timeunit->reserved_by!=""}<input type="button" id="sbm_delete_reservation" value="Foglalás törlése" class="btn btn-danger pull-left ml10" style="margin-left: 10px;" />{/if}
	{/if}
</form>
</div>
<script type="text/javascript">
	{literal}
	$("#sbm").click(function(){
		var data = $("#time_form").serialize();
		$.ajax({
			type: "POST",
			{/literal}url: "/admin/timeline/{$smarty.get.a2id}/?{if $timeunit->id}did={$timeunit->id}{else}from={$timeunit->from}&court={$timeunit->court->id}{/if}",{literal}
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/admin/timeline/';
					$("#statusbar").hide();
				}else{
					$("#statusbar").show().html(resp);
				}
			}
		});
	});
	$("#sbm_delete").click(function(){
		if(confirm('Biztosan törli az idősávot?')){
			$.ajax({
				type: "POST",
				{/literal}url: "/admin/timeline/delete/?did={$timeunit->id}",{literal}
				data: "",
				success: function(resp){
					if(resp=="ok"){
						location.href='/admin/timeline/';
						$("#statusbar").hide();
					}else{
						$("#statusbar").show().html(resp);
					}
				}
			});
		}
	});
	$("#sbm_delete_reservation").click(function(){
		if(confirm('Biztosan törli a foglalást?')){
			$.ajax({
				type: "POST",
				{/literal}url: "/admin/timeline/delete_reservation/?did={$timeunit->id}",{literal}
				data: "",
				success: function(resp){
					if(resp=="ok"){
						location.href='/admin/timeline/';
						$("#statusbar").hide();
					}else{
						$("#statusbar").show().html(resp);
					}
				}
			});
		}
	});
	{/literal}
</script>


{include file="footer.tpl"}
