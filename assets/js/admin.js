window.supprUsr =   function (id){
//           $.ajax({
//            type: "get",
//            url: "https://signe/admin/user/del/"+id,
//            dataType: 'json',
//
//            success: function (data) {
//                console.log(data);
//                alert('ya bon bb');
//
//            },
//error: function (xhr, status, error) {
//           alert(xhr.responseText);
//       }
//            })
//            return false;
            $.ajax({
       type: "GET",
       url: "https://signe/admin/user/del/"+id,
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
                url: "https://signe/admin/user/unb/"+id,
                dataType: "json",
            }).done( function(response) {
                alert("success");
                manageTableActif(response);
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
        
window.manageTableActif = function (row){
    alert('la')
    $('#actif').append($('tr').append($('td').text("row.date")));
}

