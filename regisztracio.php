<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-profile" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Regisztráció</h2>
            <hr>
            <form id="reg-form" novalidate>
                <div class="form-group">
                    <label for="username">Bejelentkezési név</label>
                    <input type="text" class="form-control" name="username">
                </div>
                <div class="form-group">
                    <label for="full_name">Név</label>
                    <input type="text" class="form-control" name="full_name">
                </div>
                <div class="form-group">
                    <label for="password">Jelszó</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="password2">Jelszó még egyszer</label>
                    <input type="password" class="form-control" name="password2">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label for="phone">Telefonszám</label>
                    <input type="tel" class="form-control" name="phone">
                </div>
                <div class="form-group">
                    <label for="born">Születési idő</label>
                    <div class="input-group date">
                        <input type="text" value="" class="form-control" name="born">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                    </div>
                </div>
                <div class="checkbox form-group">
                    <label>
                        <input type="checkbox" name="tos" value="true"> Elfogadom ÁSZF feltételeit
                    </label>
                    (<a href="aszf.php" target="_blank">ÁSZF megtekintése</a>).
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-md-6 col-sm-6">
                        <img id="captcha"/><br>
                        <button style="padding-left: 0; padding-right:0" type="button" class="btn btn-link" id="btn-new-captcha">Új képet kérek</button>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <label for="phone">Írja be a képen látható szöveget</label><br>
                        <input type="text" class="form-control" name="captcha">
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success pull-right">Regisztráció</button>
            </form>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>