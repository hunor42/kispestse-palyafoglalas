<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-profile" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Regisztráció</h2>
            <hr>
            <form>
                <div class="form-group">
                    <label for="loginName">Bejelentkezési név</label>
                    <input type="text" class="form-control" id="loginName">
                </div>
                <div class="form-group">
                    <label for="name">Név</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="form-group">
                    <label for="pwd">Jelszó</label>
                    <input type="pasword" class="form-control" id="pwd">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email">
                </div>
                <div class="form-group has-error">
                    <label for="phone" class="text-danger">Telefonszám</label>
                    <input type="tel" class="form-control" id="phone">
                    <p class="help-block text-right">Nem megfelelő telefonszám formátum</p>
                </div>
                <div class="form-group">
                    <label for="born">Születési idő</label>
                    <div class="input-group date">
                        <input type="text" value="" class="form-control" id="born">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Elfogadom ÁSZF feltételeit
                    </label>
                    (<a href="aszf.php" target="_blank">ÁSZF megtekintése</a>).
                </div>
                <button type="submit" class="btn btn-success pull-right">Regisztráció</button>
            </form>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>