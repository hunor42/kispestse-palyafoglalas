{include file="header.tpl"}

<h3>Bejelentkezés</h3>

{if is_numeric($error) && $error>1}
	<div class="errorlist">
	{if $error==2}
		Hibás felhasználónév vagy jelszó!
	{elseif $error==3}
		Inaktív felhasználó!
	{/if}
	</div>
{/if}

<form method="post" id="login_form">
	<input type="text" name="login_username" value="{$username}" placeholder="Felhasználónév" /><br />
	<input type="password" name="login_password" value="{$password}" placeholder="Jelszó" /><br />
	<input type="submit" name="sbm" value="Bejelentkezés" /><br /><br />

	<a href="/forgot_password/?username={$username}">Elfelejtett jelszó</a>
</form>

{include file="footer.tpl"}
