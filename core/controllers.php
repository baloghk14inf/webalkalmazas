<?php

/**
 * controllers.php: Az egyes útvonalakat (route) lekezelő függvények.
 * Minden függvénynek egy tömböt kell visszaadnia, aminek az első eleme a nézet (view)  neve.
 * Második eleme egy assoc tömb, amiben minden kulcs-érték párnak szerepelnie kell, amit a nézet használni fog.
 * return ["viewname", ['key1' => 'value1', 'key2' => 'value2', ...]];
 */
 
/**
 * notFoundController()
 * Ha az adott oldal nem található akkor automatikusan erre azoldalra fog irányítani.
 * @return void
 */
function notFoundController() {
    return [
        '404',
        [
            'title' => 'The page you are lookgin for is not found.'
        ]
    ];
}

/**
 * homeController()
 *
 * @return void
 */
function homeController() {
    /**
     * Query string változók: $_GET[]
     * PHP 7 új operátora: null coalescing operator
     * A ternary operátor (felt ? true : false) és az isset() fv. együttes használatát helyettesíti.
     * A null coalescing operator az első (bal oldali) operandusát adja vissza, ha az létezik és nem null, különben a másodikat (jobb oldalit)
     * Az isset() fv. igazat ad vissza, ha a paraméterül adott változó létezik és nem null (gyakran használatos a $_GET-ben levő változók ellenőrzésére).
     * Tehát az
     *   isset($_GET["size"]) ? $pageSize = $_GET["size"] : $pageSize = 10;
     * helyettesíthető ezzel:
     *   $pageSize = $_GET["size"] ??  10;
     * ami lényegesen tömörebb...
     */
    $size = filter_input(INPUT_GET, 'size')?? 10;    // $size: lapozási oldalméret
    $page = filter_input(INPUT_GET, 'page') ?? 1;     // $page: oldalszám
 
    // $connection: Adatbázis kapcsolat
   /* global $config;
    $connection = getConnection($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']); */
 
    $connection = getConnection();
    // $total: a képek számának meghatározása
    $total = getTotal($connection);
 
    // $offset: eltolás kiszámítása
    $offset = ($page - 1) * $size;
 
    // $content: egy oldalnyi kép
    $content = getPhotosPaginated($connection, $size, $offset);

    $lastPage = $total % $size == 0 ? intdiv($total, $size) : intdiv($total, $size) + 1;
    //----------------------------------------------------------------------------------------
 
    return [
        'home',
        [
            'title' => 'Home page',
            'content' => $content,
            'total' => $total,
            'size' => $size,
            'page' => $page,
            'lastPage' => $lastPage
        ]
    ];
}

/**
 * singleImageController - Egy db kép megjelenitése 
 *
 * @param [type] $params
 * @return void
 */
function singleImageController($params)
{
    $connection = getConnection();
    $picture = getImageById($connection, $params['id']);

    return [
        'singleImage',
        [
            'title' => 'Image: ' . $picture['title'],
            'picture' => $picture
        ]
        ];
}

function aboutController()
{
    return[
        'about',
        [
            'title' => 'About page'
        ]
        ];

}

/**
 *singleImageEditController- Egy kép szerkesztése
 *
 * @return void
 */
function singleImageEditController($params) //át kell adni
{
    $connection = getConnection();
    $id = $params['id'];
    $title = $_POST['title'];

    updateImage($connection,$id,$title);

    return [
        "redirect:/image/$id",
        []
    ];


}

/**
 * singleImageDeleteController- Egy kép tőrlése
 *
 * @return void
 */
function singleImageDeleteController($params)
{
    $connection = getConnection();
    $id = $params['id'];

    deleteImage($connection,$id);

    return [
        "redirect:/",
        []
    ];
}

/**
 * Display Login form.
 * route: /login
 *
 * A /login route-ra két irányból lehet jönni:
 * - felhasználó be szeretne jelentkezni
 * - hibás belépési kísérlet után is ide irányítunk át
 * Utóbbi esetben jelezni is kell, hogy előtte hiba volt, ezért kell a
 * $containsError logikai változó, amit a nézetben fel tudunk használni
 * a figyelmeztető üzenet (alert) megjelenítésére.
 *
 * @return void
 */
function loginFormController()
{
    
    $connection = getConnection();

    // Volt-e hiba a lépésnél?
    $containsError = array_key_exists('containsError', $_SESSION); //megvizsgáljuk hogy va ne ilyen változo

    //Hiba változó törlése
    unset($_SESSION['containsError']);

    return [
        'login',
        [
            'title' => 'Bejelentkezés',
            'containsError' => $containsError
        ]
    ];
}

/**
 * Sending login form.
 *
 * @return void
 */
function loginSubmitController()
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user = loginUser(getConnection(), $email, $password);

    if ($user != null) {
        //setcookie('user', $email, time() + 900);
        $_SESSION['user'] = [
            'name' => $user['name'],
            'username' => $user['email']
        ];
        $view = 'redirect:/';
    } else {
        $_SESSION['containsError'] = 1;
        $view = 'redirect:/login';
    }

    return [
        $view,
        []
    ];
}

function logoutSubmitController()
{
    unset($_SESSION['user']);

    return [
        'redirect:/',
        []
    ];



}
function registrationFormController()
{
    return[
        'registration',
        [
            'title' => 'Regisztráció'
        ]
        ];

}
function feltolteseimController($parameter)
{

    $keres_id = !empty($parameter['id']) ? $parameter['id'] : "";
    $keres_megnevezese ="";
    $targy ="";
    $kategoria ="";
    $dokumentum_eve ="";

    
    
    if (!empty($keres_id)) {
        $connection = getConnection();

        
        $query_keres = "SELECT k.id id, k.keres_megnevezese keres_megnevezese, k.Targyak_id targy_id, k.Kategoriak_id kategoria_id , k.ev dokumentum_eve 
        FROM keresek k WHERE k.id = '{$keres_id}'";


        $keres = ujra_felhasznalhato_lekerdezes($connection, $query_keres); 

        $keres_megnevezese = $keres['keres_megnevezese'];
        $targy = $keres['targy_id'];
        $kategoria = $keres['kategoria_id'];
        $dokumentum_eve = $keres['dokumentum_eve'] != null ? $keres['dokumentum_eve'] : "";

    }

    return[
        'feltolteseim',
        [
            'title' => 'Feltöltéseim',
            'keres' => $keres_id,
            'keres_megnevezese' => $keres_megnevezese,
            'targy' => $targy,
            'kategoria' => $kategoria,
            'dokumentum_eve' => $dokumentum_eve
        ]
        ];

}

function kereseimController()
{

    return[
        'kereseim',
        [
            'title' => 'Kéréseim'
        ]
        ];

}
function aktiv_keresekController()
{

    return[
        'aktivkeresek',
        [
            'title' => ' Aktív kérések'
        ]
        ];

}

function logoutController()
{
    session_destroy();
    

    return [
        'redirect:/',
        []
    ]; 
}

function dokumentumController($parameter)
{
    $connection = getConnection();

    if ($_SESSION['jogkor'] == 3) {//3 felhasználó

        // a felhasználó a másik ellenörzetlen dokumentumán kívül mindenhez hozzáfér
        $feltetel= "d.id = '{$parameter["id"]}' AND ((d.Felhasznalok_id = '{$_SESSION["id"]}' AND d.Statuszok_id = 1)
                                                OR (d.Felhasznalok_id = '{$_SESSION["id"]}' AND d.Statuszok_id = 5)
                                                OR (d.Felhasznalok_id != '{$_SESSION["id"]}' AND d.Statuszok_id = 5))";
        

    }
    elseif ($_SESSION['jogkor'] == 1) {// 1 admin

        //it csak ahoz fér hozzá ami nem az övé és ellenörzetlen mivel ő az admin, és ő nem is tud feltölteni dokumentumot
        $feltetel= "d.id = '{$parameter["id"]}' AND d.Felhasznalok_id != '{$_SESSION["id"]}' AND d.Statuszok_id = 1";
    }
    elseif ($_SESSION['jogkor'] == 2) {// 2 szerkesztő

        //it nem vizsgálom különösen mert ö hozzáfér mind az ellenörzöttjöz és mind a nem ellenörzötthöz
        $feltetel= "d.id = '{$parameter["id"]}'"; 
    }
  /*  if ($_SESSION['jogkor'] == 3 || $_SESSION['jogkor'] == 2) {

        $keres_muvelet = ujra_felhasznalhato_lekerdezes($connection, "SELECT ker.id keres_id FROM teljesitett_keresek tk 
        INNER JOIN keresek ker ON ker.id = tk.Keresek_id WHERE tk.Dokumentumok_id =".$parameter['id']."
         AND ker.Felhasznalok_id = '{$_SESSION['id']}' AND ker.Statuszok_id = 6"); 
        
    }
    if ($_SESSION['jogkor'] == 2 || $_SESSION['jogkor'] == 1) {

        $dokumentum_muvelet = ujra_felhasznalhato_lekerdezes($connection, "SELECT ker.id keres_id, d.id dokumentum_id FROM dokumentumok d 
         INNER JOIN teljesitett_keresek tk ON tk.Dokumentumok_id = d.id INNER JOIN keresek ker ON ker.id = tk.Keresek_id WHERE tk.Dokumentumok_id =".$parameter['id']."
         AND d.Felhasznalok_id != '{$_SESSION['id']}' AND d.Statuszok_id = 1"); 
        
    } */



    
    $query = "SELECT d.id id, f.felhasznalonev feltolto, k.nev kategoria, t.nev targy, d.dokumentum_cime dok_cim, d.oldalszam oldalszam, d.Statuszok_id d_statusza,  
    d.dokumentum_eve dok_eve, d.forras forras, d.dokumentum eleresi_ut, d.feltoltes_datuma feltoltes_datuma, d.file_meret file_meret, AVG(e.pont) pont, ker.id keres_id, 
    ker.Statuszok_id keres_statusza, ker.Felhasznalok_id kero_id FROM dokumentumok d INNER JOIN felhasznalok f ON f.id = d.Felhasznalok_id INNER JOIN targyak t 
    ON t.id = d.Targyak_id INNER JOIN kategoriak k ON k.id = d.Kategoriak_id LEFT JOIN ertekelesek e ON e.Dokumentumok_id = d.id   
    LEFT JOIN teljesitett_keresek tk ON tk.Dokumentumok_id = d.id LEFT JOIN keresek ker ON ker.id = tk.Keresek_id 
    WHERE ".$feltetel." GROUP BY keres_statusza, kero_id, keres_id";
    

    $dokumentum =  ujra_felhasznalhato_lekerdezes($connection, $query);
    $feltoltes_datuma = explode(" ",$dokumentum['feltoltes_datuma']);


    if (!empty($dokumentum) AND $dokumentum['id'] != null) { //az átlag számítás miatt akkor us null értéket kapunk ha nincsa a keresénsnek megfelelő dokumentum
        
        return[
            'dokumentum',
            [
                'title' => 'Dokumentum | '.$dokumentum['dok_cim'], 
                'id' => $dokumentum['id'],
                'dokumentum_cime' => $dokumentum['dok_cim'],
                'feltolto' => $dokumentum['feltolto'],
                'kategoria' => $dokumentum['kategoria'],
                'targy' => $dokumentum['targy'],
                'oldalszam' => $dokumentum['oldalszam'] != null ? $dokumentum['oldalszam'] : "--",
                'dokumentum_eve' => $dokumentum['dok_eve'] != null ? $dokumentum['dok_eve'] : "--",
                'forras' => $dokumentum['forras'],
                'eleresi_ut' => str_replace("#","%23",$dokumentum['eleresi_ut']), //a # problémát okoz ezért történik meg a csere (hex)
                'dokumentum_statusza' => $dokumentum['d_statusza'],
                'feltoltes_datuma' => $feltoltes_datuma[0],
                'file_meret' => $dokumentum['file_meret'],
                'atlag_ertekeles' => round($dokumentum['pont']), //a legközelebbi egész szám felé kerekít
                'keres_id' => $dokumentum['keres_id'],
                'kero' => $dokumentum['kero_id'], // string. Nem kell átalakítani mert amikor az int-el összehasonlítom atomatikusan átalakításra kerül
                'keres_statusza' => $dokumentum['keres_statusza']

            ]
            ];
    }
    else {
    
        return notFoundController();
    }

}

function dokumentum_keresesController()
{

    return[
        'dokumentum-kereses',
        [
            'title' => 'Dokumentum keresése'
        ]
        ];

}

function feltoltesekController()
{

    return[
        'feltoltesek',
        [
            'title' => 'Friss feltoltesek'
        ]
        ];

}

