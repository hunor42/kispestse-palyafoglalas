<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-login" >
    <div class="row">

        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default login-panel">
                <div class="panel-heading">
                    <h2 class="panel-title">Belépés</h2>
                </div>
                <div class="panel-body">
                    <div class="alert alert-danger" role="alert">
                        <p>Nem megfelelő felhasználónév vagy jelszó!</p>
                    </div>
                    <form class="clearfix" novalidate>
                        <div class="form-group">
                            <label for="userName">Felhasználónév</label>
                            <input type="text" id="userName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pass">Jelszó</label>
                            <input type="password" id="pass" class="form-control">
                        </div>
                        <div class="form-group pull-right text-right">
                            <button type="submit" class="btn btn-success">Belépés</button>
                            <a href="regisztracio.php" class="btn btn-default">Regisztráció</a>
                        </div>
                        <div class="form-group pull-left">
                            <button type="button" class="btn btn-link panel-switcher">Elfelejtett jelszó</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-default forgotten-panel" style="display: none;">
                <div class="panel-heading">
                    <h2 class="panel-title">Elfelejtett jelszó</h2>
                </div>
                <div class="panel-body">
                    <div class="alert alert-success" role="alert">
                        <p>A megadott címre elküldük az új jelszó beállításához szükséges információkat.</p>
                    </div>
                    <form class="clearfix">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" class="form-control">
                            <p class="help-block">A regisztrációkor használt e-mail címet kell magadni.</p>
                        </div>
                        <button type="submit" class="btn btn-success pull-right">Küldés</button>
                        <button type="button" class="btn btn-link panel-switcher pull-left">Bejelentkezés/Regisztráció</button><br>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>