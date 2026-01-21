$(document).ready(function () {
    window.allowPlayVideo = false;
    window.scrollFlag = false;

    $('#video-container').click(function () {
        var multimedia = document.getElementById('main-video');
        multimedia.play();
    });

    // jQuery('#video').YTPlayer();

    // Reload page
    // setTimeout(function () {
    //     location.reload(true);
    // }, 900000);

    $('.styled').uniform();

    $('a.img-preview').fancybox({padding: 3});

    // var video = document.getElementById('main-video'),
        // simpleTileBg = $('#simple-tile-bg');

    // var clickToPlayInterval = setInterval(function () {
    //     simpleTileBg.animate({'opacity':0}, 2000, function () {
    //         $(this).animate({'opacity':1}, 2000);
    //     });
    // },7000);

    videoContainerHeight();
    $(window).resize(function() {
        videoContainerHeight();
    });

    // $('#video-container').click(function () {
    //     $('#video-container > div').remove();
    //     clearInterval(clickToPlayInterval);
    //     if (video.paused) {
    //         window.allowPlayVideo = true;
    //         video.play();
    //     } else {
    //         video.pause();
    //     }
    // });

    $('a[data-scroll]').click(function (e) {
        e.preventDefault();
        removeActiveInMainMenu();
        goToScroll($(this).attr('data-scroll'), 'image-block');
    });

    // $(document).mousewheel(function () {
    //     window.userInteract = true;
    // });

    // Drop down menu
    // $('li.main-menu').bind('mouseover',function () {
    //     $(this).find('ul.dropdown-menu').show();
    // }).bind('mouseleave',function () {
    //     $(this).find('ul.dropdown-menu').hide();
    // });

    // On-top button controls
    $(window).scroll(function() {
        let win = $(this);

        // if (win.scrollTop()) video.pause();
        // else if (window.allowPlayVideo) video.play();

        if (!window.scrollFlag) {
            $('.image-block[data-scroll]').each(function () {
                let scrollData = $(this).attr('data-scroll');
                if ($(this).offset().top <= win.scrollTop() && scrollData) {
                    removeActiveInMainMenu();
                    $('a[data-scroll=' + scrollData + ']').addClass('active');
                }
            });

            let button = $('#on_top_button');
            if (win.scrollTop() > win.outerHeight()) button.fadeIn();
            else button.fadeOut();
        }
    });

    $('#on_top_button').click(function() {
        removeActiveInMainMenu();
        $('.main-menu > a[data-scroll=home]').addClass('active');
        goToScroll('home', 'image-block');
        // $(window).scrollTop(0);
    });

    $('a[href=feedback_modal]').click(function (e) {
        e.preventDefault();
        $('#feedback_modal').modal('show');
    });
});

function goToScroll(scrollData, scrollClass) {
    window.scrollFlag = true;
    $('html,body').animate({
        scrollTop: $('.'+scrollClass+'[data-scroll=' + scrollData + ']').offset().top
    }, 1000, 'easeInOutQuint', function () {
        window.scrollFlag = false;
    });
}

function videoContainerHeight() {
    $('#video-container').css('height',$(window).height()/1.5);
}

function removeActiveInMainMenu() {
    $('.main-menu > a.active').removeClass('active');
}

function tolocalstring(string, dimm) {
    return string.toLocaleString().replace(/\,/g, ' ')+' '+dimm;
}
