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
