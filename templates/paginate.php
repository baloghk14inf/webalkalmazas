<div class="col-sm-12 col-lg-8 "></div>
<div class="col-sm-12 col-lg-12 ">
<div class="col-sm-12 col-lg-4 "></div>
<nav>
    <ul class="pagination pagination-sm ">
        <li class="<?=$page<=1?"disabled":""?>"><a   href="?listazando=<?=$listazando?>&page=<?=$page - 1?>">Previous</a></li>
        <li class="<?=$page<=1?"nav-elem-eltuntet":""?>"><a href="?listazando=<?=$listazando?>&page=1">1</a></li>
        <li id="kis-meret" class="<?=$page<=2?"nav-elem-eltuntet":""?>"><a href="#">...</a></li>

        <li class="page-item active"><a class="page-link" href="#"><?=$page?></a></li>

        <li id="kis-meret" class="<?=$page>=$lastPage-1?"nav-elem-eltuntet":""?>"><a href="#">...</a></li>
        <li class="<?=$page>=$lastPage?"nav-elem-eltuntet":""?>"><a href="?listazando=<?=$listazando?>&page=<?=$lastPage?>"><?=$lastPage?></a></li>
        <li class="<?=$page>=$lastPage?"disabled":""?>"><a  href="?listazando=<?=$listazando?>&page=<?=$page + 1?>">Next</a></li>
    </ul>
</nav>
</div>
