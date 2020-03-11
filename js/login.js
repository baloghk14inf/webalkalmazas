$(document).ready(function()
{

    $('#form-login').bootstrapValidator({

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
            jelszo: {
                validators: {
                    notEmpty: {
                        message: 'A jelszó kötelező és nem lehet üres'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'A jelszónak 6 és 30 karakter hosszuságunak kell lennie'
                    }

                }

            }


        
        }// fields_end
    });//bootstrap_validators_end
    


    $('#form-login').on('submit', function(event) {

        event.preventDefault();

        

        if ($("#form-login").data('bootstrapValidator').isValid()) {
            // Use Ajax to submit form data
            
            
            $.ajax({
                url     : 'login.ajax.php', //Target URL for JSON file
                type    : 'POST',
                data    : $("#form-login :input").serialize(),
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
