$(function () {

    /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
    $("#advanced-search").click(function () {
        $("#search-sidebar").removeClass("col-xs-0").addClass("col col-xl-3 col-md-4");
        $(this).hide();
        $("#hide-advanced-search").show();
        $("#search-results-sidebar").hide();
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

});

function expandSearchedUser(match) {
    console.log(match);
    $('#match-name').text(match.first_name + ' ' + match.last_name);
    $('#match-age').text(match.Age);
    $('#match-bio').text(match.Description);
    $('#match-photo').attr('src', 'user_images/' + match.Photo); //match.Photo
    $('#match_id_hidden').val(match.user_id);
    $('#report_match_id_hidden').val(match.user_id);
}