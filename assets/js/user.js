// Mise ajour de l'utilisateur

$('#formUser').submit(function(){
    nom = $(this).find("input[name=nom]").val();
    prenom = $(this).find("input[name=prenom]").val();
    alert(nom + ' ' + prenom);
});