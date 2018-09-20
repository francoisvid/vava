// Mise ajour de l'utilisateur


$(document).ready(function() {
    $('#formUser').submit(function () {
        return false;
    });
});

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
            $('#nomBarNav').text(data.nom);
            console.log(response);
        },
        error(xhr, status, error){
            alert(xhr.responseText)
            // console.log(status);
            // console.log(error);
        }
    });
}
