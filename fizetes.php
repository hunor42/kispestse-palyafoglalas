<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-checkout" >
    <div class="row">
        <div class="col-md-3 sidebar col-sm-4">
            <div class="user-name-data">
                <span class="small-label">Belépve</span><br>
                <span id="userFullNameDisplay"></span> (<a href="adataim.php"><span id="userNameDisplay"></span></a>)
            </div>

            <h2>Fizetés</h2>
            <ol>
                <li>Tekintse át az összeállított foglalási listáját.</li>
                <li>Ha bármelyik foglalásra nincs szüksége, törölheti azt.</li>
                <li>Az Új foglalás menüpontot használva további elemeket adhat a listához.</li>
                <li>A véglegesítés gombra kattintva továbbléphet a fizetési mód választáshoz.</li>
            </ol>
        </div>

        <div class="col-md-9 col-md-offset-3 col-sm-8 col-sm-offset-4">
            <div class="row" id="checkout-content">
                <div class="clearfix">
                    <div class="col-xs-6 col-sm-6 col-md-6 display basket-value-display text-left">
                        <span class="small-label"><span id="basket-itemcount">0</span> Függő tétel</span><br>
                        <span id="basket-value" class="value">0</span><span class="currency"> Ft</span>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 display last balance-display text-right pull-right">
                        <span class="small-label">Egyenleged</span><br>
                        <span id="balance-value" class="value">N/A</span><span class="currency"> Ft</span>
                    </div>
                </div>
                <div class="col-md-12 checkout-table-wrapper" id="checkout-table-wrapper"></div>
                <div class="col-md-12">
                    <p class="text-right">
                        <button type="button" class="btn btn-success checkout-btn">Fizetés</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>