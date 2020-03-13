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

                    if (result.valid == false) {

                        $("#uzenet").html(result.message);
                        $( "#uzenet" ).show( "slow" );
                        //$("#form-login").data('bootstrapValidator').resetForm(); 
                    }
                   
                },	// success 
                error : function(xhr, status){
                    alert("Sikertelen");

                }	// error
            }); // $.ajax	
        }	// if

    });  // submit







}) // document_ready
