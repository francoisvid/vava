var pos = null;
window.supprUsr =   function (id){

            $.ajax({
       type: "GET",
       url: window.location+"user/del/"+id,
       dataType: "json",
       success: function (datas) {
           $.notify("Utilisateur désactivé", "info");
//           console.log(datas)
       },
       error: function (xhr, status, error) {
          $.notify("Une erreur est survenue", "error");
//           alert(xhr.responseText);
       }
   });
        };
        
window.unbanUsr = function (id){
            $.ajax({
                method: "get",
                url: window.location+"user/unb/"+id,
                dataType: "json",
            }).done( function(response) {
                $.notify("Utilisateur réactivé", "success");
//                alert("success");
            }).fail(function(xhr, status, error){
                $.notify("Une erreur est survenue", "error");
//                alert(xhr.responseText)
//                console.log(status);
//                console.log(error);
            });
        }
        
        
/*  Creation categorie   */
    $("#createCat").submit(function (e) {
        
//        alert('submit intercepted');
        e.preventDefault(e);
//        alert($('#formcat').val());
        $.ajax({
            method: "post",
            url: window.location + "cat/create",
            data: {type: $('#formcat').val()},
        }).done(function (response) {
            $.notify("Ajout effectué avec succès", "success");
//            console.log((response))
        }).fail(function (xhr, status, error) {
            $.notify("Une erreur est survenue", "error");
//            alert(xhr.responseText)
//            console.log(status);
//            console.log(error);
        });

    });
    window.go = function (){
        var newAdresse= $('#numAdEnt').val()+"+"+$('#rueEnt').val()+"+"+$('#villeEnt').val();
        $.ajax({
            url: "https://maps.googleapis.com/maps/api/geocode/json?address="+newAdresse+"&key=AIzaSyBZJOKIr4Z_Mu0cDkF4mUYRi5I6BtPmQs8",
            type: "GET",
            dataType: "json",
            async: false,

            success: function (data) {
//                console.log(data.results["0"].geometry.location);
                sendData(data.results["0"].geometry.location)
//                return data.results["0"].geometry.location;
            },
            error: function (param1, param2) {
                $.notify("Une erreur de géolocalisation est survenue", "error");
//                console.log("error");
            }
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
                $.notify("Une erreur est survenue", "error");
//                alert(xhr.responseText)
//                console.log(status);
//                console.log(error);
            });
}

window.sendData = function(pos) {

    var data = null;
    alert('submit intercepted');
    alert($('#nomEnt').val());
    var genre;
    if ($('#homme').is(":checked")) {
        genre = "Homme";
    } else if ($('#femme').is(":checked")) {
        genre = "Femme";
    }
    data = {
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
            "cp": $('#cpEnt').val(),
            "latitude": pos.lat,
            "longitute": pos.lng
        },
        "contact": {
            "nom": $('#nomCon').val(),
            "prenom": $('#prenomCon').val(),
            "fonction": $('#fonctionCon').val(),
            "mail": $('#mailCon').val(),
            "tel": $('#telCon').val(),
            "remarque": $('#remCon').val(),
            "genre": genre
        }

    };
//    console.log(data);


    $.ajax({
        method: "post",
        url: window.location + "company/create",
        data: data,
    }).done(function (response) {
        $.notify("Ajout effectué avec succès", "success");
//        alert("ok");
//        console.log((response));
    }).fail(function (xhr, status, error) {
        $.notify("Une erreur est survenue", "error");
//        alert(xhr.responseText)
//        console.log(status);
//        console.log(error);
    });

}

window.sendMail = function(pos) {
    var data = {
        from: $('#contact-name').val(),
        email : $('#contact-mail').val(),
        contenu : $('#contact-message').val(),
    }

    $.ajax({
            method: "post",
            url: window.location + "contact",
            data: data,
        }).done(function (response) {
            $.notify("Votre email a bien été envoyé", "success");
//            alert("ok");
//            console.log((response));
        }).fail(function (xhr, status, error) {
            $.notify("Une erreur est survenue, nous sommes désolés", "error");
//            alert(xhr.responseText)
//            console.log(status);
//            console.log(error);
        });

}