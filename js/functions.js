jQuery(document).ready(function($){

    //$(".entry-content").fitVids();
    //$(".excerpt-content").fitVids();

    $('#toggle-navigation').bind('tap', toggleNav);

    /* slide over the main, footer, and sidebar content when clicked */
    function toggleNav() {
        if ($('#site-header').hasClass('toggled')) {
            $('#site-header').removeClass('toggled');
        } else {
            var bodyHeight = $('body').height();
            $('#menu-primary').css('height', bodyHeight);
            $('#site-header').addClass('toggled')
        }
    }

    var titleRightSide = $('#title-info').offset().left + $('#title-info').width();
    var menuLeftSide = $('#menu-primary-items').offset().left;
    var menuRightSide = $('#menu-primary-items').offset().left + $('#menu-primary-items').outerWidth();
    var socialIconsLeftSide = $('#menu-social-icons').offset().left;

    if(menuLeftSide - titleRightSide < 48) {
        $('#site-header').addClass('cramped');
    }
    if(socialIconsLeftSide - menuRightSide < 48) {
        $('#site-header').addClass('cramped');
    }

});