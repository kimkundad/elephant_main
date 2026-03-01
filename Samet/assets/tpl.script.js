$(function () {
    /*****  LOADER  *****/
    setTimeout(function () {
        $('.item-logo-loader img ').addClass("opacity");
        $('body').addClass("loaded");
    }, 400);

    $(window).scroll(function () {
        if ($(window).scrollTop() > 80) {
            $(".scroll_nonefix").addClass("scroll-fixed")
        } else {
            $(".scroll_nonefix").removeClass("scroll-fixed")
        }
    });



});

$(document).ready(function () {
    /*****  POPUP BANNER  *****/
    setTimeout(function () {
        $('.popup-banner').addClass("animation");
    }, 500);

    $('.closed-banner').click(function () {
        $('.popup-banner').removeClass("animation");
        $('body').removeClass('overf');
    });

    /*****  MENU *****/
    $('.hamburger').click(function () {
        $(this).toggleClass('active');
        $('.nav-menu').toggleClass('active');
        $("header").toggleClass("menu-active");
        $('body').toggleClass('over_f');

        setTimeout(function () {
            $('nav').toggleClass("fade_fix");
            $('.reveal-block').toggleClass("fade_images");
        }, 500);
    });
    $('.btn-book-head').click(function () {
        $('.reservation-box').addClass('active');
        $('body').addClass('over_f');
        $('.hamburger').removeClass('active');
        $('.nav-menu').removeClass('active');
        $('header').removeClass('menu-active');
        $('nav').removeClass('fade_fix');
        $('.reveal-block').removeClass('fade_images');
    });
    $('.close_box').click(function () {
        $('.reservation-box').removeClass('active');
        $('body').removeClass('over_f');
    });
    $(".c_plus").click(function () {
        var findAc = $("nav").find(".menu_mobile");
        if (findAc.length) {
            if ($(this).children(".ui-icon-plus").hasClass("active")) {
                $(this).children(".ui-icon-plus").removeClass("active");
            } else {
                $(".ui-icon-plus").removeClass("active");
                $(this).children(".ui-icon-plus").addClass("active");
            }

            if ($(this).next().hasClass("active")) {
                $(this).next().removeClass("active");
            } else {
                $(".info-detail").removeClass("active");
                $(this).next().addClass("active");
            }
        }
    });

    /***** MENU HOVER IMAGES *****/
    $('nav').hover(function () {
        $('.image-hover-active').addClass('fade-out');
    }, function () {
        $('.image-hover-active').removeClass('fade-out');
    });

    let navLink = document.querySelectorAll(".nav-link"),
        imgWrap = document.querySelector(".image-hover"),
        imgItem = document.querySelector(".image-hover figure img");

    function linkHover(e) {
        if (e.type === "mouseenter") {
            let imgSrc = e.target.dataset.src;
            let t1 = gsap.timeline();

            t1.set(imgItem, {
                attr: {src: imgSrc}
            }).to(imgWrap, {
                autoAlpha: 1,
                scale: 1,

            });
        } else if (e.type === "mouseleave") {
            let t1 = gsap.timeline();
            t1.set(imgItem, {
                attr: {src: ""}
            }).to(imgWrap, {
                autoAlpha: 0,
                scale: 0.9,
            })
        }
    }
    function initAnimation() {
        navLink.forEach(link => {
            link.addEventListener('mouseenter', linkHover);
            link.addEventListener('mouseleave', linkHover);
        })
    }
    function init() {
        initAnimation();
    }
    window.addEventListener('load', function () {
        init();
    });

    /**  MUTED VIDEO **/
    $('video').on('loadeddata', function(e) {
        $(this).prop("muted", true);
    });
    var isMuted = true;
    $('.video_layer').click(function(e) {
        isMuted = !isMuted;
        $('video').prop("muted", isMuted);
        $('.muted_layer').toggleClass('active');
    });

    /*****  BODY *****/
    $('.collapse').collapse();

    $('.lang-thai').click(function (e) {
        $('.nav-lang a:nth-child(1)').addClass('hide');
    });


    // $('.map').mouseover(function (e) {
    //     $(this).addClass('active');
    // }, function (e) {
    //     $(this).removeClass('active');
    // });

    $('.map iframe').mouseover(function (e) {
        $(this).addClass('active');
    });
    $('.map').click(function (e) {
        $('.map iframe').removeClass('active');
    });
    // $('.place-card').mouseover(function (e) {
    //     $('.map').removeClass('active');
    // });



    // CLICK SHOW IMAGES
    $('.card-header').click(function (e) {
        $('.images-room-cover').addClass('hide');
    });

    const accordions = document.querySelectorAll(".btn-room-name");
    const contents = document.querySelectorAll(".images-room");

    const active = (item, index) => {
        contents.forEach((content, i) => {
            if (i !== index) {
                content.className = "photo-active";
            }
        });
        item.className = "images-room photo-active";

    };
    accordions.forEach((accordion, i) => {
        accordion.addEventListener("click", () => active(contents[i], i));
    });

    /*****  SLICK *****/
    var $status = $('.slider_counter');
    var $slickElement = $('.slider_facilities');

    if ($slickElement.length && $.fn.slick) {
        // Handle HTML snapshots that already contain rendered slick DOM.
        if ($slickElement.hasClass('slick-initialized') && $slickElement.data('slick')) {
            $slickElement.slick('unslick');
        }

        var $slides = $slickElement.children('.item');
        if (!$slides.length) {
            $slides = $slickElement.find('.slick-track > .item');
        }

        $slickElement
            .removeClass('slick-initialized slick-slider')
            .find('.slick-arrow, .slick-list, .slick-track')
            .remove();

        if ($slides.length) {
            $slides.appendTo($slickElement);
        }

        $slickElement.on('init reInit afterChange', function (event, slick, currentSlide) {
            var i = (currentSlide ? currentSlide : 0) + 1;
            $status.text(('0' + i).slice(-2) + '/' + ('0' + slick.slideCount).slice(-2));
        });

        if ($slickElement.children('.item').length) {
            $slickElement.slick({
                infinite: false,
                dots: false,
                arrows: true,
                autoplay: false,
                autoplaySpeed: 3000,
                speed: 2000,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<div><span class="slick-icon slick-prev" aria-hidden="true">&#8592;</span></div>',
                nextArrow: '<div><span class="slick-icon slick-next" aria-hidden="true">&#8594;</span></div>',
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    }
                ],
            });
        }
    }
    var $offersSlider = $('.slide_item1');
    if ($offersSlider.length && $.fn.slick && !$offersSlider.data('slick')) {
        var $offerSlides = $offersSlider.children('.item');
        if (!$offerSlides.length) {
            $offerSlides = $offersSlider.find('.slick-track > .item');
        }

        $offersSlider
            .removeClass('slick-initialized slick-slider')
            .find('.slick-arrow, .slick-list, .slick-track')
            .remove();

        if ($offerSlides.length) {
            $offerSlides.appendTo($offersSlider);
        }

        $offersSlider.slick({
            infinite: true,
            dots: false,
            arrows: true,
            autoplay: false,
            autoplaySpeed: 3000,
            speed: 1000,
            // fade: true,
            adaptiveHeight: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: '<div><span class="slick-icon slick-prev" aria-hidden="true">&#8592;</span></div>',
            nextArrow: '<div><span class="slick-icon slick-next" aria-hidden="true">&#8594;</span></div>',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }

    /***** LOCOMOTIVE SCROLL *****/
    // let locoScroll;
    // new ResizeObserver(() => scroll.update()).observe(
    //     document.querySelector("[data-scroll-container]")
    // );

    // const scroll = new LocomotiveScroll({
    //     el: document.querySelector("[data-scroll-container]"),
    //     smooth: true,
    //     repeat: false,
    //     getDirection: true,
    //     reloadOnContextChange: true,
    //     lerp: 1,
    //     tablet: {
    //         smooth: true,
    //         lerp: .1,
    //         getDirection: true,
    //         reloadOnContextChange: true,
    //         breakpoint: 768,
    //     },
    //     smartphone: {
    //         smooth: true,
    //         getDirection: true,
    //         direction: "vertical",
    //         gestureDirection: "vertical",
    //     }
    //
    // });


    // scroll.on("scroll", (obj) => {
    //     if ((obj.scroll.y) < 80) {
    //         $("header").removeClass("scroll-fixed");
    //         $("body").removeClass("scroll-up");
    //     } else if (obj.direction === 'down') {
    //         $("header").addClass("scroll-fixed");
    //     } else if (obj.direction === 'up') {
    //         $("body").addClass("scroll-up");
    //         $("header").removeClass("scroll-fixed");
    //     } else {
    //         $("header").removeClass("scroll-fixed");
    //         $("body").removeClass("scroll-up");
    //     }
    // });


});
