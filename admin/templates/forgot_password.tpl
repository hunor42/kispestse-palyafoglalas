{include file="header.tpl"}

<h3>Elfelejtett jelszó</h3>

<p>Írd be a felhasználóneved és a regisztrációkor megadott email címedre új jelszót küldünk!</p>

{if count($errors)>0}
	<ul class="errorlist">
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
{/if}
{if $success==true}
	<p>Sikeresen elküldtük új jelszavad!</p>
{/if}

<form method="post" id="forgot_form">
	<input type="text" name="username" value="{$username}" placeholder="Felhasználónév" /><br />
	<input type="submit" name="sbm" value="Elfelejtettem a jelszavam!" />
</form>

{include file="footer.tpl"}
