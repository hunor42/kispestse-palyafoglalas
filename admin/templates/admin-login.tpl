{include file="header.tpl"}

<div class="container-fluid main-container page-login" >
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default login-panel">
                <div class="panel-heading">
                    <h2 class="panel-title"><strong>Belépés</strong> - Kispest SE adminisztrációs felület</h2>
                </div>
                <div class="panel-body">
                    <form class="clearfix" method="post" id="admin_login_form" >
						{if is_numeric($error) && $error>1}
							{if $error==2}
								<div class="alert alert-danger" role="alert">Hibás felhasználónév vagy jelszó!</div>
							{elseif $error==3}
								<div class="alert alert-danger" role="alert">Inaktív felhasználó!</div>
							{/if}
						{/if}
                        <div class="form-group">
                            <label for="username">Felhasználónév</label>
                            <input type="text" name="username" value="{$username}" placeholder="Felhasználónév" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="password">Jelszó</label>
                            <input type="password" name="password" value="{$password}" placeholder="Jelszó" class="form-control"/>
                        </div>
                        <div class="form-group pull-right text-right">
                        	<input type="submit" class="btn btn-success" name="sbm" value="Bejelentkezés" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


{include file="footer.tpl"}
