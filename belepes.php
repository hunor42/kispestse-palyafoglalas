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
                    <form class="clearfix" id="login-form" novalidate>
                        <div class="form-group">
                            <label for="username">Felhasználónév</label>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Jelszó</label>
                            <input type="password" name="password" class="form-control">
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
                    <form class="clearfix" id="forgotten-form">
                        <div class="form-group">
                            <label for="username">Felhasználónév</label>
                            <input type="text" class="form-control" name="username">
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