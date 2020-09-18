$(document).ready(function() {
// var slidesId = $("#slides");
// alert(slidesId.attr('data-cycle-timeout'));

    // resumeSound();

    $('.slider-toc').slideUp();
    $('.intro').slideUp().slideDown().delay(5000).slideUp();

    $('.slider-nav').addClass('minimised');

    $('.slider-nav .min-max').click(function() {
        if ($('.slider-nav.minimised').length) {
            $('.slider-nav').removeClass('minimised').addClass('maximised').addClass('animated dock_open_animation');
            $('.min-max .icon').removeClass('icon-plus-sign').addClass('icon-minus-sign');

        } else {
            $('.slider-nav').removeClass('maximised').addClass('minimised').addClass('animated dock_open_animation');
            $('.min-max .icon').removeClass('icon-minus-sign').addClass('icon-plus-sign');
        }
    });

    $('.slider-menu, .slider .cycle-slide').click(function() {
        $('.slider-toc').slideDown();

    });

    $('.slider-toc').click(function() {
        $(this).slideUp();
    });

    $('.intro').click(function() {
        $('.intro').stop().slideUp();
    });

    $('.arrow.help').click(function() {
        $('.intro').slideDown();
    });


    $(".fullscreen").click(function() {
        if (!isFullScreenEnabled()) {
            launchFullScreen(document.documentElement);
        }
    });

    $('.refresh').click(function(){
        window.location.reload(true);
    });

    $('.slider-toc .toc a').click(function(e) {
        e.preventDefault();
        slide_id = $(this).attr('data-slide-id');
        // slide_timer = $(this).attr('data-slide-timer') * 1000;
        // localStorage.setItem('timer',slide_timer);
        // var localValue = localStorage.getItem('timer');
        // $("#timer-slide").val(localValue)
        //         .trigger('change')
        // console.log($(this).attr('data-slide-timer'));
        if(slide_id != null & typeof slide_id !== 'undefined'){
            all_slides = $('#slides>div');
            console.log("slides length:"+all_slides.length);
            for(i=0; i<all_slides.length; i++){
                slide = all_slides[i];
                slide = $(slide);
                if(slide.attr('data-slide-id') == slide_id){
                    idx = (i == 0) ? 0 : i - 1;
                    $('#slides').cycle('goto', idx);
                }
            }
        }else{
            idx = parseInt($(this).attr('data-slide-index'));
            $('#slides').cycle('goto', idx);
        }
    });

    $("#pauseplay").click(function() {
        paused = $('#slides').is('.cycle-paused');
        icon = $(this).children('i:first');
        if (paused) {
            resumeSlides();

        } else {
            pauseSlides();

        }
    });

    $('#slides').on('cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
        
        $(document).scrollTop(0);
        //stop any previously playing video from previous slide
        console.log('outgoing.');
        $nxt = $(incomingSlideEl);
        content_url = $nxt.attr('data-slide-content');
    //    var  content_timer = $nxt.attr('data-slide-timer');
    //     content_timer = content_timer *1000;
    //     // $('#timer-slide').val(content_timer);
    //     localStorage.setItem('timer',content_timer)
    //     var localValue = localStorage.getItem('timer');
    //     $("#timer-slide").val(localValue)
    //                      .trigger('change')
        showSlideContent($nxt, content_url);
    });

    $('#slides').on('cycle-paused', function(event, optionHash) {
        $('.slider-nav #pauseplay i').removeClass('icon-pause').addClass('icon-play');
    });

    $('#slides').on('cycle-resumed', function(event, optionHash) {

        $('.slider-nav #pauseplay i').removeClass('icon-play').addClass('icon-pause');

    });

    //disable right click
    /*
     $(document)[0].oncontextmenu = function() { return false;}

     $(document).mousedown(function(e){
         if( e.button == 2 ) {
         return false;
         } else {
         return true;
         }

     });*/


var player = document.getElementById('player');
var audio_source = document.getElementById('audio_source');
var audio_source_alt = document.getElementById('audio_source_alt');
var current_track = 0;
var sound_root = "assets/sound/";

if(typeof sound_playlist !== 'undefined'){

    audio_source.src = sound_root + sound_playlist[current_track];
    audio_source.type = "audio/ogg";

    audio_source_alt.src = sound_root + sound_playlist_alt[current_track];
    audio_source_alt.type = "audio/mpeg";

    player.load();
    player.play();

    current_track++;

    player.addEventListener("ended", function() {

        audio_source.src = sound_root + sound_playlist[current_track];
        audio_source.type = "audio/ogg";

        audio_source_alt.src = sound_root + sound_playlist_alt[current_track];
        audio_source_alt.type = "audio/mpeg";

        player.load();
        player.play();

        console.log('[audio] Currently playing "' + sound_playlist[current_track] + '"');

        current_track++;

        if (current_track >= sound_playlist.length) {
            current_track = 0;
        }

        console.log('[audio] Next track "' + sound_playlist[current_track] + '"');

    });
}

$('#volume').click(function() {
    toggleSound();
});

$('#modal-email').on('shown.bs.modal', function() {
    $('form.contact').trigger('reset');
    $('form.contact input:visible:first').focus();
    sub = $('.cycle-slide-active .action-btn').attr('data-subject');
    $('form.contact input[name="subject"]').val(sub);
    initKeyboard();

});

$('#modal-email #submit').click(function() {
    $('form.contact').submit();
});

$("form.contact").submit(function(event) {
    event.preventDefault();
    if (!$(this).valid()) {
        return;
    }
    $('#modal-email #submit span').html('Sending...');
    var data = $(this).serialize();
    var form = $(this);
    url = form.attr('action') + '&rand=' + Math.random();

    $.blockUI(this);
    $.post(url, data, function(raw_response) {

        $.unblockUI();

        try {
            response = $.parseJSON(raw_response);
            alert(response.msg);
            if (response.status == 'OK') {
                        $("#modal-email").modal('hide'); //hide popup
                        if (response.forward) {
                            window.location = response.forward;
                        }
                    }

                } catch (e) {
                    alert(raw_response);
                }


            }).fail(function(data) {
                $.unblockUI();
                alert('Unable to communicate with backend server. Please try again later.');
            });

            $('#modal-email #submit span').html('Send');

        });

                //Load all rates
                if(SHOW_RATES){
                    refreshAllRates();
                    setInterval(function(){
                     refreshAllRates();
                 }, 1000*60*RATES_API_REFRESH_INTERVAL);

                    $('.footer').click(function() {
                        if ($(this).hasClass('maximised')) {
                            $(this).removeClass('maximised').addClass('minimised');
                        } else {
                            $(this).removeClass('minimised').addClass('maximised').addClass('animated dock_open_animation');
                        }
                    });

                    $('.footer').dblclick(function() {
                        $(this).addClass('animated flash');
                        refreshAllRates();
                    })
                }else{
                    $('#rates_container').hide();
                }

                //Load all social feeds
                if(SHOW_SOCIAL_NETWORK_FEEDS){
                    refreshAllSocialFeeds();
                    setInterval(function(){
                     refreshAllSocialFeeds();
                 }, 1000*60*RATES_API_REFRESH_INTERVAL);

                    $('.footer').click(function() {
                        if ($(this).hasClass('maximised')) {
                            $(this).removeClass('maximised').addClass('minimised');
                        } else {
                            $(this).removeClass('minimised').addClass('maximised').addClass('animated dock_open_animation');
                        }
                    });

                    $('.footer').dblclick(function() {
                        $(this).addClass('animated flash');
                        refreshAllSocialFeeds();
                    })
                }else{
                    $('#social_container').hide();
                }
            });

            function refreshAllRates(){
               $.get('./src/AjaxAction.class.php?action=getScrollingRates', function(response){
                    if(response.length < 1) return;
                    $('#rates').html(response);
                    $('#rates .marquee').marquee();
                });

               setTimeout(function(){
                $.get('./src/AjaxAction.class.php?action=getAllRates', function(response){
                    $('.footer').removeClass('animated flash');
                    if(response.length < 1) return;
                    $('#all_rates').html(response);
                });
                }, 5000);

            }

             function refreshAllSocialFeeds(){
               $.get('./src/AjaxAction.class.php?action=getSocialFeeds', function(response){
                    if(response.length < 1) return;
                    $('#feeds').html(response);
                    $('#feeds .marquee').marquee();
                });

               setTimeout(function(){
                $.get('./src/AjaxAction.class.php?action=getAllSocialFeeds', function(response){
                    $('.footer').removeClass('animated flash');
                    if(response.length < 1) return;
                    $('#all_feeds').html(response);
                });
                }, 5000);

            }

            // Find the right method, call on correct element
            function launchFullScreen(el) {
                if (el.requestFullScreen) {
                    el.requestFullScreen();
                } else if (el.mozRequestFullScreen) {
                    el.mozRequestFullScreen();
                } else if (el.webkitRequestFullScreen) {
                    el.webkitRequestFullScreen();
                } else if (el.msRequestFullscreen) {
                    el.msRequestFullscreen();
                }

            }

            function exitFullScreen() {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
                return;
            }

            function isFullScreenEnabled() {
                return document.fullscreenEnabled || document.mozFullscreenEnabled || document.webkitIsFullScreen ? true : false;
            }

            function initKeyboard() {
                jsKeyboard.init("virtualKeyboard");

                //first input focus
                var $firstInput = $(':input').first().focus();
                jsKeyboard.currentElement = $firstInput;
                jsKeyboard.currentElementCursorPosition = 0;
            }


            function showSlideContent(slide, content_url) {
                gc();
                if (content_url) {
                    pauseSlides();

                    //Get next slide with Ajax
                    $.get(content_url, function(data) {

                        resumeSlides();

                        $nxt_content = $(data);

                        col_left = $nxt_content.find('.col-left');
                        col_right = $nxt_content.find('.col-right');
                        slide_bg = $nxt_content.find('.slide-bg');
                        widget_collapse = $nxt_content.find('.widget-collapse');
                        action_btn = $nxt_content.find('.action-btn');

                        links = $nxt_content.find('a');

                        anims = [
                        ['fadeInLeft', 'slideInLeft', 'slideInRight', 'wobble'],
                        ['zoomIn', 'zoomInLeft', 'zoomInRight', 'wobble'],
                        ['fadeInRight', 'slideInDown', 'slideInUp', 'wobble'],
                        ['slideInLeft', 'rotateInUpLeft', 'rotateInDownRight', 'wobble']
                        ];

                        rand = Math.floor(Math.random() * 3);

                        anim = anims[rand];
                        slide_bg.addClass('animated ' + anim[0]);
                        col_left.addClass('animated ' + anim[1]);
                        col_right.addClass('animated ' + anim[2]);
                        widget_collapse.removeClass('animated').addClass('animated ' + anim[3]);

                        slide.html($nxt_content);

                        //col_left.height(col_right.height());

                        links.click(function(e){
                            slide_id = $(this).attr('data-slide-id');
                            if(slide_id != null & typeof slide_id !== 'undefined'){
                                all_slides = $('#slides>div');
                                for(i=0; i<all_slides.length; i++){
                                    slide = all_slides[i];
                                    slide = $(slide);
                                    if(slide.attr('data-slide-id') == slide_id){
                                        idx = (i == 0) ? 0 : i - 1;
                                        $('#slides').cycle('goto', idx);
                                    }
                                }
                            }else{
                                idx = parseInt($(this).attr('data-slide-index'));
                                $('#slides').cycle('goto', idx);
                            }
                        });

                        widget_collapse.click(function(e) {
                            c = $('.widget-content');
                            if (c.is(':hidden')) {
                                c.removeClass('hide');
                                c.fadeIn();
                                $(this).html('<i class="icon-angle-down"></i> Hide details');
                                nh = $(window).height() - 360;
                                $('.dynamic-slide .col-right').each(function() {
                                    $(this).animate({height: nh + 'px'});
                                });
                                                        //col_left.height(nh);
                                                        pauseSlides();
                                                    } else {
                                                        $('.widget-content').slideToggle();
                                                        nh = 210;
                                                        $('.dynamic-slide .col-right').animate({height: nh + 'px'});
                                                        $(this).html('<i class="icon-angle-up"></i> Show details');
                                                        //$('.dynamic-slide .col-left').height(nh);
                                                        resumeSlides();
                                                    }

                                                    return false;

                                                });

                        action_btn.click(function(e) {
                            $('#modal-email').modal('show');
                            return false;
                        });

                        });
                        }
                        }

function pauseSlides(){
    console.log('pausing slides...');
    paused = $('#slides').is('.cycle-paused');
    if (!paused) {
        console.log('slides paused');
        $('.slides').cycle('pause');

    }
}

function resumeSlides(){
    gc();
    paused = $('#slides').is('.cycle-paused');
    if (paused) {
     $('.slides').cycle('resume');
 }
}

function nextSlide(){

    resumeSlides();
    $('.slides').cycle('next');
}

function pauseSound(){
    console.log('pausing background sound...');
    icon = $('#volume').children('i:first');
    if (!player.paused) {
        player.pause();
        icon.removeClass('icon-volume-up');
        icon.addClass('icon-volume-off');
    }
    console.log('background sound paused');

}
function resumeSound(){
    console.log('resuming sound...');
    icon = $('#volume').children('i:first');
    if (player.paused) {
        player.play();
        icon.removeClass('icon-volume-off');
        icon.addClass('icon-volume-up');
    }
    console.log('sound resumed...');
}

function toggleSound(){
    if(player.paused){
        resumeSound();
    }else{
        pauseSound();
    }

}
//garbage collect any previously playing video
function gc(){
    $videos = $(".dynamic-slide video");
    console.log('Garbage collecting ' + $videos.length + ' videos...');
    $videos.each(function(){
        this.pause();
        delete(this);
        $(this).remove();
    });
}