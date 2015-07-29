{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-12">
        	<h2>Pályák</h2>
        	<hr>

			{if is_array($courts) && count($courts)>0}
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Név</th>
						<th>Leírás</th>
						<th>Hozzáadása dátuma</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$courts item=court}
					<tr>
						<td>{$court->id}</td>
						<td>{$court->name}</td>
						<td>{$court->subtitle}</td>
						<td>{$court->add_date}</td>
						<td class="text-right"><a href="/admin/courts/modify/?did={$court->id}" class="btn btn-success btn-xs">Módosítás</a></td>
					</tr>
				{/foreach}
				</tbody>
				</table>
			{else}
				<p>Nincs még egyetlen pálya sem létrehozva!</p>
			{/if}

			<p class="text-right"><a href="/admin/courts/new/" class="btn btn-warning">Új pálya létrehozása</a></p>

        </div>
    </div>
</div>

{include file="footer.tpl"}
