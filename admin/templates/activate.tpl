{include file="header.tpl"}

<h3>Aktiválás</h3>

{if $success==1}
	<p>Sikeres aktiválás! A bejelentkezéshez <a href="/login/">kattints ide</a>.</p>
{else}
	<p>Hibás aktiváló link!</p>
{/if}

{include file="footer.tpl"}
