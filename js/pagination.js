function pagination(listazando, oldal) {

    var listazando = listazando;
    var oldal = oldal;

    $.ajax({
        url     : 'pagination.php', //Target URL for JSON file
        type    : 'POST',
        data    : {
            'listazando': listazando,
            'oldal': oldal
        },
        cache   : false,
        dataType: 'json',
        async   : true,
        success : function(result, status, xhr){

            //ezzel itt eltüntetem a tartalom listázásnál az alert üzenetet.
            $('#alert2').remove();

            //ezzel törlöm az elöbb kreált div-eket, mert ujboli div feltöltéskor nem az elöző adatok helye
            //kerűlne felülírásra hanem  folyamatosan bekerülne a másik a lá a tartalom
            for (var i = 0; i < result.size; i++) {

                $("#div" + i).remove();
                
            }

            if (result.content == "") {
                $('#ures').html("<div id='alert2' class='alert alert-info' role='alert'> Nincs megjeleníthető tartalom.</div>");
            }
           /* $("#a1").removeAttr("href");
            $("#a1").removeClass("leptet");
            $("#a2").removeAttr("href");
            $("#a2").removeClass("leptet");
            $("#a3").removeAttr("href");
            $("#a4").removeAttr("href");
            $("#a4").removeClass("leptet");
            $("#a5").removeAttr("href");
            $("#a5").removeClass("leptet");*/
            


            //it töltöm fel a pagination href elemeit és olykor az a tag szövegét
            $("#a1").attr("href", "#"+ result.listazando + "&#" + (result.page - 1));
            $("#a1").addClass("leptet");
            $("#a2").attr("href", "#"+ result.listazando + "&#" + 1 );
            $("#a2").addClass("leptet");
            $("#a3").attr("href", "#");
            $("#a3").html(result.page);
            $("#a4").attr("href", "#"+ result.listazando + "&#" + (result.lastPage));
            $("#a4").html(result.lastPage);
            $("#a4").addClass("leptet");
            $("#a5").attr("href", "#"+ result.listazando + "&#" + (result.page + 1));
            $("#a5").addClass("leptet");

            
            if (result.page <= 1) {
                $("#nav1").addClass("disabled pointer");
                $("#nav2").addClass("nav-elem-eltuntet");
                $("#a1").removeClass("leptet");
            }
            else {
                $("#nav1").removeClass("disabled pointer");
                $("#nav2").removeClass("nav-elem-eltuntet");
            }
            if (result.page <= 2) {
                $("#nav3").addClass("nav-elem-eltuntet");
            }
            else {
                $("#nav3").removeClass("nav-elem-eltuntet");
            }
            if (result.page >= (result.lastPage - 1)) {
                $("#nav4").addClass("nav-elem-eltuntet");
            }
            else {
                $("#nav4").removeClass("nav-elem-eltuntet");
            }
            if (result.page >= result.lastPage) {
                $("#nav5").addClass("nav-elem-eltuntet");
                $("#nav6").addClass("disabled pointer");
                $("#a5").removeClass("leptet");
            }
            else {
                $("#nav6").removeClass("disabled pointer");
                $("#nav5").removeClass("nav-elem-eltuntet");
            }

            //divek dinamikus feltöltése
            for (var i = 0; i < result.content.length; i++) {
                
                $('<div id="div' + i + '" class="div-adatok">').appendTo('#elsodiv');
                $('<a href="/dokumentum/'+ result.content[i].id +'" id="link'+ i +'" class="tartalom-a">').appendTo('#div'+ i);
                $('<h6>' +"Feltöltés dátuma: "+ result.content[i].feltoltes_datuma +'</h6>').appendTo('#link'+ i); 
                $('<h4>'+ result.content[i].dokumentum_cime +'</h4>').appendTo('#link'+ i);
                $('<h5>' +"Tantárgy: "+ result.content[i].kategoria +" Kategória: "+ result.content[i].targy +'</h5>').appendTo('#link'+ i);
                $('</a>').appendTo('#link'+ i);
                $('</div>').appendTo('#div'+ i); 
                //alert('<p id="p' + i +'">'+ result.content[i].dokumentum_cime +'</p>');
                
            }

           
            
            

        },	// success 
        error : function(xhr, status){
            alert("Sikertelen");

        }	// error
    }); // $.ajax	

    


}