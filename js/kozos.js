function aktualis_ev(){ //az aktuális dátum évének kiszámolása hogy ne lehessen nygobb értéket megadni
    var today = new Date();
    var yyyy = today.getFullYear(); 
    today = yyyy;
    return today;
}