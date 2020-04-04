$(document).ready(function()
{

    
    var today = new Date();
    var ev = today.getFullYear()-9; //-9 az az 2020-9 = 2010 minimum 10 év a használati határ.
                                    // azért 9 mert a 12/31 et nem lehet hasnálni de ha -9 akkor ingen persze igy (01-01)
    
    today = ev + '/01/01'; //teljes honap , nap
    


    $('#form-registration').bootstrapValidator({

        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            felhasznalonev: {

                validators: {
                     notEmpty: {
                          message : 'A felhasználónév megadása kötelező!'
                        },
                     different: {
                            field: 'jelszo',
                            message: 'Nem lehet ugyan az a felhasználónév és a jelszó!'
                        }
            }
            },//vezeteknev_end
            nem: {

                validators: { notEmpty: { message : 'A nemet kötelező megadni!'}}
            },//keresztnev_end
            szdatum: {
                validators: {
                    notEmpty: {
                         message : 'A születési dátum megadása kötelező!'
                    },
                    date: {
                        message: 'A megadott dátum nem érvényes!',
                        format: 'YYYY-MM-DD'
                    },
                    callback: {
                        message: 'A dátum nincs a tartományban! 1950/01/01<->'+ (ev-1)+'/12/31',
                        callback: function(value, validator) {
                            var m = new moment(value, 'YYYY-MM-DD', true);
                            if (!m.isValid()) {
                                return false;
                            }
                            return m.isAfter('1949/12/31') && m.isBefore(today);
                        }
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Az e-mail cím kötelező és nem lehet üres!'
                    },
                    emailAddress: {
                        message: 'Nem megfelelő e-mail cím formátum!'
                    }
                }
            },//email_end
            jelszo: {
                validators: {
                    notEmpty: {
                        message: 'A jelszó kötelező és nem lehet üres'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'A jelszónak 6 és 30 karakter hosszuságunak kell lennie'
                    },
                    different: {
                        field: 'felhasznalonev',
                        message: 'Nem lehet ugyan az a felhasználónév és a jelszó!'
                    }
                }

            },//password_end
            jelszo_ismet: {

                validators: {
                    notEmpty: {
                        message: 'A jelszó megerősítése kötelező és nem lehet üres'
                    },
                    identical: {
                        field: 'jelszo',
                        message: 'A jelszó mintáknak eggyezniie kell'
                    }
                }// validators_end
            } //passwor_confirmation_end

        
        }// fields_end
    }).on('success.form.bv', function(e) { //itt végzem el az ürlap küldését
        
        e.preventDefault();

        $.ajax({
            url     : 'register.php', //Target URL for JSON file
            type    : 'POST',
            data    : $("#form-registration :input").serialize(),
            cache   : false,
            dataType: 'json',
            async   : true,
            success : function(result, status, xhr){
                alert("Sikeres");
                console.log(result.valid);

                if (result.valid == true) {

                    $( "#elr" ).empty();
                    $('#alert').html("<div class='alert alert-success' role='alert'>A regisztráció sikeresen megtőrtént, a bejelentkezéshez nyomj a gombra!</div>");
                    $('#alert').show();
                    $("#tovabb_gomb").removeClass("eltuntet");
                    
                }
                else {
                    $("#uzenet2").html(result.message2);
                    $("#uzenet2" ).show( "slow" );
                    $("#uzenet3").html(result.message3);
                    $("#uzenet3" ).show( "slow" );
                }

            },	// success 
            error : function(xhr, status){
                alert("Sikertelen");
                $("#messageText_registration").text(status);

                $("#form-registration").bootstrapValidator('disableSubmitButtons', false);
                $("#messageBox_registration").fadeOut();
            }	// error
        }); // $.ajax	
    });//bootstrap_validators_end

}) // document_ready
