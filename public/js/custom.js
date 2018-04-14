/**
 * Function to setup the ajax scripts with the CSRF token.
 */
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

/**
 * Function that will toggle deletion on specified checkbox classname.
 * 
 * @Param      token / id
 * @Param      class name
 */
function toggleDeletionOn(categoryName, token) {
    // Define the checkbox:
    const checkbox = document.getElementsByClassName('delete_checkbox[' + token + ']');
    console.log('delete_checkbox[' + token + ']');
    // Define the checked state:
    var checkedState = null;
    // Looping through the elements:
    for (var i = 0; i < checkbox.length; i++) {
        // Get the element:
        checkedState = checkbox[i].checked;
    }
    // Check if the checked state in not set:
    if (checkedState != null) {
        // Execute the request:
        $.ajax({
            type: 'POST',
            url: '/admin/' + categoryName + '/delete/' + token,
            datatype: 'json',
            data: {
                'checkedState': checkedState
            },
            success: function (data, textStatus, xhr) {
                // Log an success message:
                console.log('Success: ' + xhr.status + ' url: ' + this.url);
            },
            error: function (request, status, errorThrown) {
                // Log an error message:
                console.log('Error: ' + status + ' = ' + errorThrown + ' url: ' + this.url);
            }
        });
    } else {
        // Log an error:
        console.log('Error: ' + 'The code could not retrieve the checked state of the checkbox');
    }
}

/**
 * Function to ban users.
 * Bootbox.js is used for modal.
 */
$("body").on("click",".ban",function(){

    var current_object = $(this);

    bootbox.dialog({
        // form in bootbox dialog
        message: "<form class='form-inline add-to-ban' method='POST'>" +
                    "<div class='form-group'>" +
                    "<textarea class='form-control reason' rows='4' style='width:465px' placeholder='Geef reden om gebruiker te bannen.'>" +
                    "</textarea>" +
                    "</div>" +
                "</form>",
        title: "Voeg toe aan ban lijst.",
        buttons: {
            success: {
                label: "Ban",
                className: "btn-success",
                callback: function() {
                    // assign all values of filled in fields to variable
                    var baninfo = $('.reason').val();
                    var token = $("input[name='_token']").val();

                    // gets data-action value and data-id to assign them to te variables.
                    var action = current_object.attr('data-action');
                    var id = current_object.attr('data-id');

                    // Makes border color red when reason not filled in.
                    if(baninfo == ''){
                        $('.reason').css('border-color','red');
                        return false;
                    }else{
                        // adds hidden inputfields to form.
                        $('.add-to-ban').attr('action',action);
                        $('.add-to-ban').append('<input name="_token" type="hidden" value="'+ token +'">');
                        $('.add-to-ban').append('<input name="id" type="hidden" value="'+ id +'">');
                        $('.add-to-ban').append('<input name="baninfo" type="hidden" value="'+ baninfo +'">');
                        $('.add-to-ban').submit();
                    }
                }
            },
            danger: {
                label: "Cancel",
                className: "btn-danger"
            }
        }
    })
});


/**
 * Function to toggle the boardgame deletion ajax script.
 */
function toggleJoinTournament(button){
    var checkedState = button.value;
    var tournamentId = $(button).data('tournament-id');
    var username = $(button).data('username');
    var userId = $(button).data('user_id');
    var data = { checkedState : checkedState, id : tournamentId };
    if(checkedState != null && tournamentId != null) {
        $.ajax({
            type: 'POST',
            url: '/tournament/join' ,
            datatype: 'json',
            data: data,
            success: function (data, textStatus, xhr) {
                // if(checkedState == 1) {
                //     $("#tournament_button_div").html("<input name=\"tournament_button\" id=\"tournament_button\" type=\"button\" data-tournament-id=\"" + tournamentId + "\" data-username=\"" + username + "\" data-user_id=\"" + userId + "\" style=\" display: none;\" value=\"0\" onclick=\"toggleJoinTournament(this)\"/><label for=\"tournament_button\" id=\"tournament_button-label\" class=\"btn btn-danger\">Niet meer meedoen</label>");
                //     $("#tournament_players").append("<li id=\"tournament_player[" + userId + "]\" class=\"list-group-item\">" + username +"</li>");
                // } else {
                //     $("#tournament_button_div").html("<input name=\"tournament_button\" id=\"tournament_button\" type=\"button\" data-tournament-id=\"" + tournamentId + "\" data-username=\"" + username + "\" data-user_id=\"" + userId + "\" style=\" display: none;\" value=\"1\" onclick=\"toggleJoinTournament(this)\"/><label for=\"tournament_button\" id=\"tournament_button-label\" class=\"btn btn-success\">Meedoen aan toernooi</label>");
                //     document.getElementById("tournament_player[" + userId + "]").remove()
                // }
                // $("#tournament_player_count").html($("#tournament_players > li").length);
                location.reload();
            },
            error: function (request, status, errorThrown) {
                // Log an error:
                console.log('Error: ' + status + ' = ' + errorThrown + 'url: ' + this.url);
            }
        });
    } else {
        // Log an error:
        console.log('Error: ' + 'The code could not retrieve the value of the input or the tournament id');
    }
}


/*
* Datatables at admin/user overview.
 */

$(document).ready(function() {
    // selects table id to run DataTable function on it
    $('#dataTablesTable').DataTable( {
        "info" : false,
        "lengthMenu": [ 5, 10, 15, 20],
        "language": {
            "lengthMenu": "Toon _MENU_ regels",
            "search": "Zoeken",
            "paginate": {
                "previous": "Vorige",
                "next": "Volgende",
            },
            "zeroRecords": "Er zijn geen regels gevonden",
            "infoEmpty": "Er zijn geen regels gevonden",
        }
    } );
    $( "#datepicker" ).datepicker({dateFormat : 'yy-mm-dd'});
} );