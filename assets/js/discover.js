$(function () {

    /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
    $("#advanced-search").click(function () {
        openAdvancedSearch();
    });

    $("#hide-advanced-search").click(function () {
        $("#search-sidebar").removeClass("col col-xl-3 col-md-4").addClass("col-xs-0");
        $(this).hide();
        $("#advanced-search").show();
    });

    $("#close-search-results").click(function () {
        $("#search-results-sidebar").hide();
    });

// Age slider
    $("#age-advanced-search").slider({});

    if (location.search === '?advanced-search')
        openAdvancedSearch();
});

function openAdvancedSearch() {
    $("#search-sidebar").removeClass("col-xs-0").addClass("col col-xl-3 col-md-4");
    $("#advanced-search").hide();
    $("#hide-advanced-search").show();
    $("#search-results-sidebar").hide();
}

function expandSearchedUser(match) {
    $("#discover-out-of-matches").hide();
    $("#discover-main").show();

    $('#match-name').text(match.first_name + ' ' + match.last_name);
    $('#match-age').text(match.Age);
    $('#match-bio').text(match.Description);
    $('#match-photo').attr('src', 'user_images/' + match.Photo); //match.Photo
    $('#match_id_hidden').val(match.user_id);
    $('#report_match_id_hidden').val(match.user_id);
}

function openChat(match_user_id) {
    $.redirectPost("messaging.php", {match_user_id: match_user_id});
}

// jquery extend function
$.extend(
    {
        redirectPost: function (location, args) {
            let form = '';
            $.each(args, function (key, value) {
                value = value.split('"').join('\"')
                form += '<input type="hidden" name="' + key + '" value="' + value + '">';
            });
            $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
        }
    });