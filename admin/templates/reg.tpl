{include file="header.tpl"}

<h3>Regisztráció</h3>

<div id="statusbar"></div>

<form method="post" id="reg_form">
	<input type="text" name="full_name" value="" placeholder="Teljes név" /><br />
	<input type="text" name="username" value="" placeholder="Felhasználónév" /><br />
	<input type="password" name="password" value="" placeholder="Jelszó" /><br />
	<input type="password" name="password2" value="" placeholder="Jelszó újra" /><br />
	<input type="text" name="email" value="" placeholder="E-mail cím" /><br />
	<input type="text" name="phone" value="" placeholder="Telefonszám" /><br />
	<input type="text" name="birthday" value="" placeholder="Születésnap" /><br />
	<img src="{$captcha.image_src}" /><br /> <input type="text" name="captcha" value="" placeholder="Ellenörző kód" /><br />
	<input type="checkbox" name="tos" value="true" /> Elfogadom az ÁSZF-et<br />
	<input type="button" id="sbm" value="Regisztráció" />
</form>

<script type="text/javascript">
	{literal}
	$("#sbm").click(function(){
		var data = $("#reg_form").serialize();
		$.ajax({
			type: "POST",
			url: "/reg/",
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/reg/success/';
				}else{
					$("#statusbar").html(resp);
				}
			}
		});
	});
	{/literal}
</script>

{include file="footer.tpl"}
