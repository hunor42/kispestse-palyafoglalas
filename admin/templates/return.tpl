{if count($errors)==0}ok{else}
<ul class="errorlist">
	{foreach from=$errors item=error}
		{if is_array($error)}
			{foreach from=$error item=err}
				<li>{$err}</li>
			{/foreach}
		{else}
			<li>{$error}</li>
		{/if}
	{/foreach}
</ul>
{/if}