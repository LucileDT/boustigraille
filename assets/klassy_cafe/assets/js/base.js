const headerHeight = Math.round($('header').height()) + 1;

// add `active` class to link and its parents
function markNavigationLinkAsActive(link) {
    $('#top-navbar a').removeClass('active');
    link.addClass('active');
    link.parents('li.submenu').addClass('active');
}

// remove `active` class to link and its parents
function markNavigationLinkAsInactive(link) {
    link.removeClass('active');
    let parentContainer = link.parents('li.submenu');
    if (parentContainer.find('a.active').length === 0) {
        parentContainer.removeClass('active');
    }
}

// add or remove `active` class on navbar menu entries depending on which one is active
function toggleNavigationLinksActiveStatus() {
    let currentScrollPosition = Math.round($(document).scrollTop()) + headerHeight;
    $('#top-navbar a:not(.dropdown-toggle)').each(function () {
        let link = $(this);
        let href = link.attr('href');

        if (href === '#') {
            // link is an anchor to the top of the page
            if (currentScrollPosition <= headerHeight) {
                markNavigationLinkAsActive(link);
            } else {
                markNavigationLinkAsInactive(link);
            }
        } else if ((href.startsWith('#') && $(href).length !== 0) || (href === $(location).attr('pathname') && href.indexOf('#') !== -1)) {
            // link is an anchor to the current page and there is a corresponding element
            let pageElementCorrespondingToHref = $(href);
            let elementTopPosition = Math.round(pageElementCorrespondingToHref.position().top);
            let elementBottomPosition = elementTopPosition + Math.floor(pageElementCorrespondingToHref.height());

            // check element position and update menu link active status accordingly
            if (currentScrollPosition >= elementTopPosition && currentScrollPosition <= elementBottomPosition) {
                markNavigationLinkAsActive(link);
            } else {
                markNavigationLinkAsInactive(link);
            }
        } else if (href === $(location).attr('pathname') && $(location).attr('hash') === '') {
            // link correspond to the current page and does not have an anchor
            markNavigationLinkAsActive(link);
        }
    });
}

// stick or unstick header to the top depending to the scroll position
function stickHeader() {
    let currentScrollPosition = $(window).scrollTop();
    let landingBoxHeight = $('.header-text').height();
    if (landingBoxHeight !== undefined) {
        // there is a landing box, header will only stick after it
        if (currentScrollPosition >= landingBoxHeight) {
            $('header').addClass('header-sticky');
        } else {
            $('header').removeClass('header-sticky');
        }
    } else {
        if (currentScrollPosition > 0) {
            $('header').addClass('header-sticky');
        } else {
            $('header').removeClass('header-sticky');
        }
    }
}

$(document).ready(function () {
    toggleNavigationLinksActiveStatus();

    stickHeader();
    $(window).scroll(function() {
        stickHeader();
    });

    // stop scrolling animation on wheel scroll
    document.addEventListener('wheel', function () {
        $('html, body').stop();
        $(document).off('scroll');
        $(document).on('scroll', toggleNavigationLinksActiveStatus);
    });

    // scroll page to the element location
    $('#top-navbar a:not(.dropdown-toggle)').on('click', function (event) {
        let link = $(this);
        let href = link.attr('href');
        if (href === '#' || (href.startsWith('#') && $(href).length !== 0) || (href === $(location).attr('pathname') && href.indexOf('#') !== -1)) {
            event.preventDefault();
            $(document).off('scroll');

            markNavigationLinkAsActive($(event.target));

            let positionToGo = 0;
            let hashForUrl = '';
            let target = this.hash;
            if (target !== '') {
                positionToGo = ($(target).offset().top) - headerHeight;
                hashForUrl = target;
            }

            $('html, body')
                .stop()
                .animate({ scrollTop: positionToGo + 'px' }, 400, 'swing')
                .promise()
                .then(function () {
                    window.location.hash = hashForUrl;
                    toggleNavigationLinksActiveStatus;
                })
            ;
        }
    });
});

// page loading animation
$(window).on('load', function() {
    $("#preloader").animate({
        'opacity': '0'
    }, 600, function(){
        setTimeout(function(){
            $('#preloader').css('visibility', 'hidden').fadeOut();
        }, 300);
    });
});
