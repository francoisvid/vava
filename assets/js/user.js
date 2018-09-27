$(document).ready(function() {
    $('#formUser').submit(function () {
        return false;
    });
});

// ----------------------------------------------Mise a jour infos Utilisateur

window.update = function(id){
    data = {

        numero : $('#numero').val(),
        rue : $('#rue').val(),
        ville : $('#ville').val(),
        codepostal : $('#codepostal').val(),
        nom : $('#nom').val(),
        prenom : $('#prenom').val(),
        mail : $('#mail').val(),
        tel : $('#tel').val(),
        sexe : $('#sexe').val(),
        date : $('#datenaissance').val()
        // date : $(this).find("input[name=date]").val(),
    }
    console.log(data);
    console.log(window.location + "/update/" + id);

    $.ajax({
        type: 'post',
        url : window.location + "/update/" + id,
        data : data,
        success: function (response) {
            $('#monCompte').text(data.nom);
            console.log(response);
        },
        error(xhr, status, error){
            alert(xhr.responseText)
            console.log(status);
            console.log(error);
        }
    });
}

// ----------------------------------------------menu espace user class active
$("#info").click(function () {
    $(".item").removeClass("active");
    $(".map").hide();
    $(".fav").hide();
    $("#info").addClass("active");
    $(".info").show();
});

$("#fav").click(function () {
    $(".item").removeClass("active");
    $(".map").hide();
    $(".info").hide();
    $("#fav").addClass("active");
    $(".fav").show();
});

$("#map").click(function () {
    $(".item").removeClass("active");
    $(".info").hide();
    $(".fav").hide();
    $("#map").addClass("active");
    $(".map").show();
});


// ----------------------------------------------Modal delete





