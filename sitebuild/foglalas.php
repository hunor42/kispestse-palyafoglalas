<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-reservation" >
    <div class="row">
        <div class="col-md-3 sidebar">
            <h2>Új foglalás</h2>
            <div data-date="12/03/2012" class="datepicker-wrapper"></div>
            <ol>
                <li>Válassza ki a naptárból a napot amikor foglalni szeretne</li>
                <li>A pálya listában kattintson a foglalás gombra. A foglalása függő státuszba kerül.</li>
                <li>Több időpontot is foglalhat, a fent leírtak szerint.</li>
                <li>Véglegesítse foglalását a <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> ikonra kattintva vagy az alábbi gombra kattintva.<br></li>
            </ol>
            <p><a href="fizetes.php" class="btn btn-warning" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Foglalás véglegesítése</a></p>
        </div>

        <div class="col-md-9 col-md-offset-3">
            <div class="row">
                <div class="col-md-3 display basket-value-display">
                    <a href="fizetes.php" title="Foglalás áttekintése és véglegesítése" class="pull-right" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                    <span class="small-label"><span id="basket-itemcount">0</span> Függő tétel</span><br>
                    <span id="basket-value" class="value">0</span><span class="currency"> Ft</span>
                </div>
                <div class="col-md-3 display last balance-display text-right pull-right">
                    <span class="small-label">Egyenleged</span><br>
                    <span class="value">150 000</span><span class="currency"> Ft</span>
                </div>
                <div class="col-md-6 display text-center">
                    <span class="small-label">Dátum</span><br>
                    <strong><span id="selected-date" class="value">2015. május 22.</span></strong>
                </div>

                <div class="col-md-12 availability-table-wrapper" id="availability-table-wrapper">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>