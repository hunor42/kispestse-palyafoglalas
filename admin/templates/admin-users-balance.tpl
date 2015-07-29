{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-12">
        	<h2>Felhasználók <small>({$user->full_name})</small></h2>
        	<hr>

        	<h4>Aktuális egyenleg: {$user->get_balance()|price} Ft</h4>
			<hr>

			<div id="statusbar" class="alert alert-danger" role="alert"></div>

			<form id="balance_form" class="form-inline">
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Érték</th>
						<th>Típus</th>
						<th>Admin felhasználónév</th>
						<th>Hozzáadás dátuma</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="r" colspan="2">
							<div class="form-group">
								<input type="text" name="value" value="" class="valueinp form-control" style="max-width: 100px; display: inline-block; margin-right: 10px;"/> Ft
							</div>
						</td>
						<td><input type="button" name="sbm" id="sbm" value="Jóváírás"  class="btn btn-default btn-xs"/></td>
					</tr>
				{foreach from=$user->get_transactions() item=transaction}
					<tr>
						<td class="r" style="min-width: 140px">{$transaction->value|price} Ft</td>
						<td>{$transaction->get_type_display()}</td>
						<td>{$transaction->admin_username}</td>
						<td>{$transaction->add_date}</td>
						<td></td>
					</tr>
				{/foreach}
				{if !is_array($user->get_transactions()) || count($user->get_transactions())==0}
					<tr>
						<td colspan="5">Nincs még egyetlen tranzakciója sem ennek a felhasználónak!</td>
					</tr>
				{/if}
				</tbody>
				</table>

			</form>
        </div>
    </div>
</div>

<script type="text/javascript">
	{literal}
	$("#sbm").click(function(){
		var data = $("#balance_form").serialize();
		$.ajax({
			type: "POST",
			{/literal}url: "/admin/users/balance/add/?did={$user->username}",{literal}
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href=location.href;
					$("#statusbar").hide();
				}else{
					$("#statusbar").show().html(resp);
				}
			}
		});
	});
	{/literal}
</script>

{include file="footer.tpl"}
