{include file="header.tpl"}

{include file="admin-header.tpl"}

<h3>Pályafoglalás</h3>

Kiválasztott nap: <input type="text" name="date" id="date" value="{$selected_date}" />

<table border="1" cellspacing="0" cellpadding="2" class="timeline timeline_user">
<tr>
{foreach from=$courts item=court}
	<td>{$court->name}</td>
{/foreach}
</tr>
{foreach from=$times item=time}
	<tr>
	{foreach from=$courts item=court}
		{assign var="court_id" value=$court->id}	
		{assign var="timeunits_court" value=$timeunits[$court_id]}
		{assign var="timeunit" value=$timeunits_court[$time.0]}
		<td class="timetd{if is_object($timeunit)} {$timeunit->get_timetd_class($time.0)}{/if}" {if is_object($timeunit)}data-id="{$timeunit->id}" {/if}data-from="{$time.0}" data-court="{$court->id}">
			<div class="time">{$time.0}-{$time.1}</div>
			{if $timeunit->booked!=""}<div>{$timeunit->booked}</div>{/if}
			{if $timeunit->comment!=""}<div class="comment">{$timeunit->comment}</div>{/if}
			{if $timeunit->available==1 && $timeunit->booked==""}<div class="price">{$timeunit->price} Ft</div>{/if}
		</td>
	{/foreach}
	</tr>
{/foreach}
</table>

<script type="text/javascript">
	{literal}
	$("#date").datepicker({"dateFormat": "yy-mm-dd"});
	$("#date").change(function(){
		location.href='/timeunits/?selected_date='+$(this).val();
	});
	$(".timetd").click(function(){
/*		var id = $(this).attr('data-id');
		if(typeof(id)!="undefined"){
			var url = '/admin/timeline/modify/?did='+id;
		}else{
			var from = $(this).attr('data-from');
			var court = $(this).attr('data-court');
			var url = '/admin/timeline/new/?from='+from+'&court='+court;
		}
		$.facebox({ ajax: url });*/
	});
	{/literal}
</script>

{include file="footer.tpl"}
