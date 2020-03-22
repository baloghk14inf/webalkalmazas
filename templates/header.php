<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">TIMMT</a>
    </div>
    <!-- Ezt itt majd dinamikusan kell feltölteni -->
    <div class="collapse navbar-collapse navbar-right" id="menu">
    <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Irja be a keresendőt">
        </div>
        <button type="submit" class="btn btn-default">Keresés</button>
      </form>
      <ul class="nav navbar-nav ">
          <?php
          $record = menupontok_feltoltese($connection);
          //dinamikusan töltöm fel a menüpontokat és aztán az url-lekértezésével szemléltetem hogy épp melyik menüpont az aktív
          foreach ($record as $row):?>
                <li class="<?=($_SERVER["REQUEST_URI"] == $row['mroute']) ? 'active' : '' ?>"><a href="<?=$row['mroute']?>"><?=$row['mnev']?></a></li>
          <?php endforeach; ?>
      </ul>

      <ul class="nav navbar-nav ">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>