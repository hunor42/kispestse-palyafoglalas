{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-12">
        	<h2>Aktuális foglalások</h2>
        	<hr>
			{if is_array($reservations) && count($reservations)>0}
				<table class="table table-striped">
				<thead>
					<tr>
						<th>Név</th>
						<th>Pálya</th>
						<th>Dátum</th>
						<th>Idősáv</th>
						<th>Ár</th>
						<th>Státusz</th>
						<th>Foglalás dátuma</th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$reservations item=reservation}
					<tr>
						<td title="{$reservation->user->username}">{$reservation->user->full_name}</td>
						<td>{$reservation->timeunit->court->name}{if $reservation->timeunit->court->subtitle!=""} ({$reservation->timeunit->court->subtitle}){/if}</td>
						<td>{$reservation->timeunit->date}</td>
						<td>{$reservation->timeunit->from} - {$reservation->timeunit->to}</td>
						<td class="r">{$reservation->price} Ft</td>
						<td>{if $reservation->status==1}aktív{else}lemondva{/if}</td>
						<td>{$reservation->add_date}</td>
					</tr>
				{/foreach}
				</tbody>
				</table>
			{else}
				<p>Nincs egyetlen aktuális (jövőbeli) foglalás sem!</p>
			{/if}
        </div>
    </div>
</div>

{include file="footer.tpl"}
