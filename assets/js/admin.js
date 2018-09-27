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
//                manageTableActif(response);
//                console.log(response);
            }).fail(function(xhr, status, error){
                alert(xhr.responseText)
                console.log(status);
                console.log(error);
            });
//            $.ajax({
//            type: "GET",
//            url: "https://signe/admin/user/unb/"+id,
//            dataType: 'json',
//
//            success: function (data) {
//                console.log(data);
//                alert('ya bon bb');
//
//            },
//            error: function (param1, param2) {
//                console.log(data);
//                alert('ya PAS bon bb');
//
//            }
//            });
        }
        
//window.manageTableActif = function (row){
//    alert('la')
//    $('#actif').append($('tr').append($('td').text("row.date")));
//}

$('#news').submit(function (e) {
    e.preventDefault(e);
    data = {
        titre : $('#titre').val(),
        article : $('#contenu').val(),
    }
    $.ajax({
        method: 'post',
        url: window.location + 'news/create',
        data: data,
        success : function(response){
            console.log(response);
        },
        error(xhr, status, error){
            alert(xhr.responseText)
            console.log(status);
            console.log(error);
        }
    })

});
