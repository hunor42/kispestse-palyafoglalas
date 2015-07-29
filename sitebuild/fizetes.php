<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-checkout" >
    <div class="row">
        <div class="col-md-3 sidebar">
            <h2>Fizetés</h2>
            <ol>
                <li>Tekintse át az összeállított foglalási listáját.</li>
                <li>Ha bármelyik foglalásra nincs szüksége, törölheti azt.</li>
                <li>Az Új foglalás menüpontot használva további elemeket adhat a listához.</li>
                <li>A véglegesítés gombra kattintva továbbléphet a fizetési mód választáshoz.</li>
            </ol>
        </div>

        <div class="col-md-9 col-md-offset-3">
            <div class="row">
                <div class="col-md-12 display basket-value-display text-right">
                    <span class="small-label"><span id="basket-itemcount">0</span> Függő tétel</span><br>
                    <span id="basket-value" class="value">0</span><span class="currency"> Ft</span>
                </div>
                <div class="col-md-12 checkout-table-wrapper" id="checkout-table-wrapper">
                    <table class="table table-striped">
                        <tr>
                            <td>
                                <span><strong>2012. május 25.</strong></span>
                                <span><strong>12:00 - 14:00</strong></span>
                                <span>2. pálya (Strandröplabda)</span>
                                <button type="button" class="btn btn-danger btn-xs delete-btn pull-right" data-reservationid="4584764329756237653"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Törlés</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><strong>2012. május 25.</strong></span>
                                <span><strong>12:00 - 14:00</strong></span>
                                <span>2. pálya (Strandröplabda)</span>
                                <button type="button" class="btn btn-danger btn-xs delete-btn pull-right" data-reservationid="4584764329756237653"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Törlés</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span><strong>2012. május 25.</strong></span>
                                <span><strong>12:00 - 14:00</strong></span>
                                <span>2. pálya (Strandröplabda)</span>
                                <button type="button" class="btn btn-danger btn-xs delete-btn pull-right" data-reservationid="4584764329756237653"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Törlés</button>
                            </td>
                        </tr>
                    </table>
                    <p class="text-right">
                        <a href="" class="btn btn-success checkout-btn">Tovább a fizetéshez</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>