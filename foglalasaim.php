<?php include('inc/head.inc'); ?>
<?php include('inc/navigation.inc'); ?>

<div class="container-fluid main-container page-my-reservations" >
    <div class="row">
        <div class="col-md-3 sidebar col-sm-4">
            <div class="user-name-data">
                <span class="small-label">Belépve</span><br>
                <span id="userFullNameDisplay"></span> (<a href="adataim.php"><span id="userNameDisplay"></span></a>)
            </div>
            <h2>Foglalások</h2>
            <ol>
                <li>Áttekintheti már véglegesített foglalásainak listáját.</li>
                <li>Az időpont előtt legalább 24 órával a foglalásokat lemondhatja.</li>
            </ol>
        </div>

        <div class="col-md-9 col-md-offset-3 col-sm-8 col-sm-offset-4">
            <div class="row">
                <div class="col-md-12 my-reservations-table-wrapper" id="my-reservations-table-wrapper">
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('inc/foot.inc'); ?>