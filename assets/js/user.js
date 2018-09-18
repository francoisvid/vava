// Mise ajour de l'utilisateur

$('#formUser').submit(function () {
    data = {
        nom : $(this).find("input[name=nom]").val(),
        prenom : $(this).find("input[name=prenom]").val(),
        mail : $(this).find("input[name=mail]").val(),
        tel : $(this).find("input[name=tel]").val(),
        sexe : $(this).find("input[name=sexe]").val(),
        // date : $(this).find("input[name=date]").val(),
    }
    $.ajax({
        method: 'post',
        url : window.location + "/update/" + utilisateur.id,
    data : data,
        success: function (response) {
        console.log(response);
    },
    error(xhr, status, error){
        // alert(xhr.responseText)
        console.log(status);
        console.log(error);
    }
});
    return response;
});