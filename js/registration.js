$(document).ready(function()
{

    
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); 
    var yyyy = today.getFullYear()-10; //-10 az az 2020-10 = 2010 minimum 10 év a használati határ.
    
    today = yyyy + '/' + mm + '/' + dd;
    


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
                        message: 'A dátum nincs a tartományban! 1950-01-01->napjainkig',
                        callback: function(value, validator) {
                            var m = new moment(value, 'YYYY-MM-DD', true);
                            if (!m.isValid()) {
                                return false;
                            }
                            return m.isAfter('1950/01/01') && m.isBefore(today);
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
    });//bootstrap_validators_end
    


    $('#form-registration').on('submit', function(event) {
        // Prevent form submission
        //event.preventDefault(); // To prevent following the link (optional)
        //$("#form-registration").bootstrapValidator('validate');
        event.preventDefault();

        

        if ($("#form-registration").data('bootstrapValidator').isValid()) {
            // Use Ajax to submit form data
            
            
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
                        $( ".uzenet" ).show( "slow" );
                        
                    }
                    else {
                        $("#uzenet2").html(result.message2);
                        $( "#uzenet2" ).show( "slow" );
                        $("#uzenet3").html(result.message3);
                        $( "#uzenet3" ).show( "slow" );
                    }
                    //if (result.valid == true || result.valid == "true") {
                 /*   if (status == "success") { // ugyan a hosszabb lefutású php nem fejeződik be, de a hívás legalább sikeres
                        $("#messageText_registration").text("Köszönöm, hogy regisztráltál!");
                        //$("#messageBox_registration").fadeIn();
                        
                        $(".openRegistration").toggle();   // change the open/close registration text
        

                        
                        // Reset all inputs
                        $("#form-registration :input").each(function() {
                            if (this.type != "submit")  // kivéve a submit
                                $(this).val('');
                        });
                        
                        $("#form-registration").data('bootstrapValidator').resetForm();
                        //$("#messageBox_registration").fadeOut();
                    }	// if
                    else {
                        $("#messageText_registration").text(result.message);
                    }	// if else

                    $("#form-registration").bootstrapValidator('disableSubmitButtons', false);
                    $("#messageBox_registration").fadeOut(); */
                },	// success 
                error : function(xhr, status){
                    alert("Sikertelen");
                    $("#messageText_registration").text(status);

                    $("#form-registration").bootstrapValidator('disableSubmitButtons', false);
                    $("#messageBox_registration").fadeOut();
                }	// error
            }); // $.ajax	
        }	// if

    });  // submit







}) // document_ready
