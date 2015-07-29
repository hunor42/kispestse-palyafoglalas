{include file="header.tpl"}

{include file="admin-header.tpl"}

<div class="container-fluid main-container" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        	<div class="clearfix">
	            <h2>Felhasználók <small>({$user->full_name} módosítása)</small></h2>
	            <p>
	            	<strong>Felhasználónév:</strong> {$user->username}<br />
					<strong>E-mail cím:</strong> {$user->email}
				</p>
	            <hr>

				<div id="statusbar" class="alert alert-danger" role="alert"></div>

				{if $success==true}
					<div class="alert alert-success" role="alert">Sikeres adatmódosítás!</div>
				{/if}

	            <form class="form-horizontal" method="post" id="profile_form">
	                <div class="form-group">
	                    <label for="full_name" class="col-md-4 control-label">Teljes név</label>
	                    <div class="col-md-8">
	                    	<input type="text" name="name" value="{$user->full_name}" placeholder="Teljes név" class="form-control" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="phone" class="col-md-4 control-label">Telefonszám</label>
	                    <div class="col-md-8">
	                    	<input type="text" name="phone" value="{$user->phone}" placeholder="Telefonszám" class="form-control" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="birthday" class="col-md-4 control-label">Születésnap</label>
	                    <div class="col-md-8">
	                    	<input type="text" name="birthday" value="{$user->birthday}" placeholder="Születésnap" class="form-control" />
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="price" class="col-md-4 control-label">Kedvezmény mértéke (%)</label>
	                    <div class="col-md-2">
	                    	<input type="text" name="discount" value="{$user->discount}" placeholder="Kedvezmény mértéke (%)" class="form-control text-right" />
	                    </div>
	                </div>
					<input type="button" id="sbm" value="Adatmódosítás" class="btn btn-success pull-right"/>
	            </form>
	    	</div>

			<div class="clearfix">
				<h2>Jelszómódosítás</h2>
				<hr>

				{if $success2==true}
					<div id="statusbar2"  class="alert alert-success" role="alert">Sikeres jelszómódosítás!</div>
				{/if}

				<form class="form-horizontal" method="post" id="password_form">
					<div class="form-group">
						<div class="col-md-6">
							<input type="password" name="password" value="" placeholder="Új jelszó" class="form-control"/>
						</div>
						<div class="col-md-6">
							<input type="password" name="password2" value="" placeholder="Új jelszó újra" class="form-control" />
						</div>
					</div>
					<p>
						<input type="button" id="sbm2" value="Jelszó módosítás" class="btn btn-success pull-right"/>
					</p>

				</form>
	        </div>
        </div>
    </div>
</div>





<script type="text/javascript">
	var username="{$user->username}";
	{literal}
	$("#sbm").click(function(){
		var data = $("#profile_form").serialize();
		$.ajax({
			type: "POST",
			url: "/admin/users/modify/?did="+username,
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/admin/users/';
					$("#statusbar").hide();
				}else{
					$("#statusbar").show().html(resp);
				}
			}
		});
	});
	$("#sbm2").click(function(){
		var data = $("#password_form").serialize();
		$.ajax({
			type: "POST",
			url: "/admin/users/modify/?did="+username,
			data: data,
			success: function(resp){
				if(resp=="ok"){
					location.href='/admin/users/';
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
