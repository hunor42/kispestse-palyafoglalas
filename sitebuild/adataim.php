<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-profile" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Felhasználói adatok</h2>
            <hr>
            <form class="clearfix">
                <div class="form-group">
                    <label for="loginName">Bejelentkezési név</label>
                    <input type="text" class="form-control" id="loginName" value="kgeza" disabled>
                </div>
                <div class="form-group">
                    <label for="name">Név</label>
                    <input type="text" class="form-control" id="name" value="Kiss Géza">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" value="kgeza@example.com">
                </div>
                <div class="form-group has-error">
                    <label for="phone" class="text-danger">Telefonszám</label>
                    <input type="tel" class="form-control" id="phone" value="+36 20 123 4567">
                    <p class="help-block text-right">Nem megfelelő telefonszám formátum</p>
                </div>
                <div class="form-group">
                    <label for="born">Születési idő</label>
                    <div class="input-group date">
                        <input type="text" value="2015.05.21." class="form-control" id="born">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                    </div>
                </div>
                <button type="reset" class="btn btn-link pull-right">Mégsem</button>
                <button type="submit" class="btn btn-success pull-right">Adatmódosítás</button>
            </form>
            <h3>Jelszó módosítás</h3>
            <hr>
            <form>
                <div class="form-group">
                    <label for="pwd">Új jelszó</label>
                    <input type="pasword" class="form-control" id="pwd">
                </div>
                <div class="form-group">
                    <label for="pwd2">Új jelszó mégegyszer</label>
                    <input type="pasword" class="form-control" id="pwd2">
                </div>
                <button type="submit" class="btn btn-success pull-right">Jelszó módosítás</button>
            </form>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>