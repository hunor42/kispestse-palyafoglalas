<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Kispest SE pálya foglalás</a><br>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="foglalas.php">Foglalás</a></li>
				<?php
					if ($_SESSION['login_users_logined']=='true'){
				?>
					<li><a href="foglalasaim.php">Foglalásaim</a></li>
					<li><a href="adataim.php">Adataim</a></li>
					<li><a href="kilepes.php" id="logout-btn">Kilépés</a></li>
                <?php
					}else{
				?>
					<li><a href="belepes.php">Bejelentkezés</a></li>
					<li><a href="regisztracio.php">Regisztráció</a></li>
				<?php
					}
                ?>
                <li style="font-size: 1.25em; margin-top: -2px;"><a href="http://kispestse.hu" target="_blank"><span class="label label-warning">kispestse.hu &rarr;</span></a></li>
            </ul>
        </div>
    </div>
</nav>