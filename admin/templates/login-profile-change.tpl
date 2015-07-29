{include file="header.tpl"}

<h3>Profil</h3>

<div id="statusbar">{if $success==true}Sikeres adatmódosítás!{/if}</div>

<form method="post" id="profile_form">
	Felhasználónév: {$logined_user->username}<br />
	E-mail cím: {$logined_user->email}<br />
	Teljes név: <input type="text" name="full_name" value="{$logined_user->full_name}" placeholder="Teljes név" /><br />
	Telefonszám: <input type="text" name="phone" value="{$logined_user->phone}" placeholder="Telefonszám" /><br />
	Születésnap: <input type="text" name="birthday" value="{$logined_user->birthday}" placeholder="Születésnap" /><br />
	
	<input type="button" id="sbm" value="Adatmódosítás" />
</form>

<h3>Jelszómódosítás</h3>

<div id="statusbar2">{if $success2==true}Sikeres jelszómódosítás!{/if}</div>

<form method="post" id="password_form">
	<input type="password" name="password" value="" placeholder="Új jelszó" /><br />
	<input type="password" name="password2" value="" placeholder="Új jelszó újra" /><br />
	
	<input type="button" id="sbm2" value="Jelszó módosítás" />
</form>

<script type="text/javascript">
	{literal}
	$("#sbm").click(function(){
		var data = $("#profile_form").serialize();
		$.ajax({
			type: "POST",
			url: "/login/profile/change/",
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/login/profile/change/success/';
				}else{
					$("#statusbar").html(resp);
				}
			}
		});
	});
	$("#sbm2").click(function(){
		var data = $("#password_form").serialize();
		$.ajax({
			type: "POST",
			url: "/login/profile/changepassword/",
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/login/profile/changepassword/success/';
				}else{
					$("#statusbar2").html(resp);
				}
			}
		});
	});
	{/literal}
</script>


{include file="footer.tpl"}
