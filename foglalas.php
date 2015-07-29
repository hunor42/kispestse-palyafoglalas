<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-reservation" >
    <div class="row">
        <div class="col-md-3 sidebar col-sm-4">
            <div class="user-name-data">
                <span class="small-label">Belépve</span><br>
                <span id="userFullNameDisplay"></span> (<a href="adataim.php"><span id="userNameDisplay"></span></a>)
            </div>
            <h2>Új foglalás</h2>
            <label class="hidden-sm hidden-md hidden-lg">Válassz dátumot</label>
            <div class="datepicker-wrapper"></div>
            <ol class="hidden-xs">
                <li>Válassza ki a naptárból a napot amikor foglalni szeretne</li>
                <li>A pálya listában kattintson a foglalás gombra. A foglalása függő státuszba kerül.</li>
                <li>Több időpontot is foglalhat, a fent leírtak szerint.</li>
                <li>Véglegesítse foglalását a <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ikonra kattintva vagy az alábbi gombra kattintva.<br></li>
            </ol>
            <p class="hidden-xs text-right"><a href="fizetes.php" class="btn btn-warning" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Foglalás véglegesítése</a></p>
        </div>

        <div class="col-md-9 col-md-offset-3 col-sm-8 col-sm-offset-4">
            <div class="row">
                <div class="clearfix">
                    <div class="col-md-6 display text-center">
                        <span class="small-label">Dátum</span><br>
                        <strong><span id="selected-date" class="value"></span></strong>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 display basket-value-display text-right pull-left">
                        <a href="fizetes.php" title="Foglalás áttekintése és véglegesítése" class="pull-left" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        <span class="small-label"><span id="basket-itemcount">0</span> Függő tétel</span><br>
                        <span id="basket-value" class="value">0</span><span class="currency"> Ft</span>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 display last balance-display text-right pull-right">
                        <span class="small-label">Egyenleged</span><br>
                        <span id="balance-value" class="value">N/A</span><span class="currency"> Ft</span>
                    </div>
                </div>

                <div class="col-md-12 availability-table-wrapper" id="availability-table-wrapper" data-opened-initially="<?php echo $_GET['court'] ? $_GET['court'] : ''?>">
                </div>

                <div class="col-md-12">
                    <p class="text-right"><a href="fizetes.php" class="btn btn-warning" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Foglalás véglegesítése</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>