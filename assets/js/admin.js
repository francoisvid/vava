window.supprUsr =   function (id){

            $.ajax({
       type: "GET",
       url: window.location+"user/del/"+id,
       dataType: "json",
       success: function (datas) {
           console.log(datas)
       },
       error: function (xhr, status, error) {
           alert(xhr.responseText);
       }
   });
        };
        
window.unbanUsr = function (id){
            $.ajax({
                method: "get",
                url: window.location+"user/unb/"+id,
                dataType: "json",
            }).done( function(response) {
                alert("success");
            }).fail(function(xhr, status, error){
                alert(xhr.responseText)
                console.log(status);
                console.log(error);
            });
        }
        
        
/*  Creation categorie   */
    $("#createCat").submit(function (e) {
        
        alert('submit intercepted');
        e.preventDefault(e);
        alert($('#formcat').val());
        $.ajax({
            method: "post",
            url: window.location + "cat/create",
            data: {type: $('#formcat').val()},
        }).done(function (response) {
            console.log((response))
        }).fail(function (xhr, status, error) {
            alert(xhr.responseText)
            console.log(status);
            console.log(error);
        });

    });
    window.go = function (){
        alert('submit intercepted');
        alert($('#nomEnt').val());
        var genre;
        if ($('#homme').is(":checked")){
              genre = "Homme";
            }else if($('#femme').is(":checked")){
                genre = "Femme";
            }
        var data = { 
                        "nom": $('#nomEnt').val(),
                        "categorie": $('#entselect').val(),
                        "tel": $('#numEnt').val(),
                        "mail": $('#mailEnt').val(),
                        "logo": $('#logoEnt').val(),
                        "site": $('#siteEnt').val(),
                        "salarie": $('#salarieEnt').val(),
                        "remarque": $('#remEnt').val(),
                        "adresse": {
                                    "numero": $('#numAdEnt').val(),
                                    "rue": $('#rueEnt').val(),
                                    "ville": $('#villeEnt').val(),
                                    "cp": $('#cpEnt').val()},
                        "contact":{
                                    "nom": $('#nomCon').val(),
                                    "prenom": $('#prenomCon').val(),
                                    "fonction": $('#fonctionCon').val(),
                                    "mail": $('#mailCon').val(),
                                    "tel": $('#telCon').val(),
                                    "remarque": $('#remCon').val(),
                                    "genre": genre
                                }
                        
                    };
                    console.log(data);
            if (true){
                        alert();
                var request = {
                    address: $(this).val()
                }
                        alert();

                geocoder.geocode(request, function(results, status){
                    if(status == google.maps.GeocoderStatus.OK){

                        var pos = results[0].geometry.location;
                    }
                });
                return false;
            }
            
//        console.log(data);

        $.ajax({
            method: "post",
            url: window.location + "company/create",
            data: data,
        }).done(function (response) {
            alert("ok");
            console.log((response));
        }).fail(function (xhr, status, error) {
            alert(xhr.responseText)
            console.log(status);
            console.log(error);
        });
    }

window.showContact = function(id){
    
    $.ajax({
                method: "get",
                url: window.location+"user/unb/"+id,
                dataType: "json",
            }).done( function(response) {
//                alert("success");
            }).fail(function(xhr, status, error){
                alert(xhr.responseText)
                console.log(status);
                console.log(error);
            });
}