<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-profile" >
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Felhasználói adatok</h2>
            <hr>
            <form class="clearfix" id="profile-form">
                <div class="form-group">
                    <label for="username">Bejelentkezési név</label>
                    <input type="text" class="form-control" name="username" disabled>
                </div>
                <div class="form-group">
                    <label for="full_name">Név</label>
                    <input type="text" class="form-control" name="full_name">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" disabled>
                </div>
                <div class="form-group">
                    <label for="phone">Telefonszám</label>
                    <input type="tel" class="form-control" name="phone">
                </div>
                <div class="form-group">
                    <label for="birthday">Születési idő</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="birthday">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                    </div>
                </div>
                <button type="reset" class="btn btn-link pull-right">Mégsem</button>
                <button type="submit" class="btn btn-success pull-right">Adatmódosítás</button>
            </form>
            <h3>Jelszó módosítás</h3>
            <hr>
            <form id="new-pass-form">
                <div class="form-group">
                    <label for="password">Új jelszó</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="password2">Új jelszó mégegyszer</label>
                    <input type="password" class="form-control" name="password2">
                </div>
                <button type="submit" class="btn btn-success pull-right">Jelszó módosítás</button>
            </form>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>