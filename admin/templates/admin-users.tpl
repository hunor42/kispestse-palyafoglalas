{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-12">
        	<div class="row">
	        	<div class="col-md-6">
	        		<h2>Felhasználók</h2>
	        	</div>
	        	<div class="col-md-6" style="padding-top: 20px;">
		        	<form action="" class="form-inline text-right">
						Gyorskeresés: <input type="text" name="q" value="{$q}" class="form-control">
						<input type="submit" name="sbm" value="Keres" class="btn btn-warning"/>
		        	</form>
	        	</div>
	        </div>
        	<hr>
			{if is_array($users) && count($users)>0}
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Felhasználónév</th>
						<th>Regisztráció dátuma</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$users item=user}
					<tr>
						<td><strong>{$user->username}</strong></td>
						<td>{$user->reg_date}</td>
						<td class="text-right">
							<a href="/admin/users/modify/?did={$user->username}" class="btn btn-success btn-xs">Módosítás</a>
							<a href="/admin/users/balance/?did={$user->username}" class="btn btn-success btn-xs">Egyenleg</a>
							<a href="/admin/report/?did={$user->username}" class="btn btn-default btn-xs">Riport</a>
						</td>
					</tr>
				{/foreach}
				</tbody>
				</table>

				<nav>
				  <ul class="pagination">
	  				{foreach from=$page_range item=p name=pagerfe}
						<li><a href="?page={$p}">{$p}</a></li>
					{/foreach}
				  </ul>
				</nav>
			{else}
				<p>
					{if $q==""}Nincs még egyetlen felhasználó sem regisztrálva!{else}
					Nincs a keresésnek megfelelő felhasználó. </p><p><a href="/admin/users/?q=" class="btn btn-warning">Szűrés megszüntetése</a>{/if}
				</p>
			{/if}
        </div>
    </div>
</div>

{include file="footer.tpl"}
