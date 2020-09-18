//relaod the browser every 45 minutes
setInterval(function(){
    window.location.reload(1);
 }, 2700000);

window.addEventListener('load', function(){
    $('.myModal').click(function(){ //hide the dropdown menu when clicked
        $(".myModal").hide('slow') ;
    })
    $('.dynamic-slides').click(function(){
        $(".myModal").show('slow') ;
    });

    const slideShow = document.querySelectorAll(".dynamic-slides");
    let videosItems = document.querySelectorAll(".dynamic-slide-video");
    let currentSlideCounter = 0;
    slideShow[currentSlideCounter].style.display="block";

// build up the dropdown to navigate to any slide that is clicked when the slide is still on the first index
    var index = 0;
      $( ".click-able-slide" ).one( "click", function() {
         index = $( ".click-able-slide" ).index( this );
         slideShow[currentSlideCounter].style.display="none";
         slideShow[index].style.display = "block";
        currentSlideCounter = index > 0? index-1 : index;
        if(slideShow[index].classList.contains("dynamic-slide-video")){
        for(i=0;i < videosItems.length; i++){
            if(videosItems[i].currentSrc == slideShow[index].currentSrc){
                videosItems[i].play();
                videosItems[i].muted = false;
            }else if(videosItems[i] != slideShow[index].currentSrc){
                videosItems[i].pause();
                videosItems[i].load();
                videosItems[i].currentTime =0;
                videosItems[i].muted = true;
            }

        }
    }
    index == 0?slideShow[0].style.display = "block": nextSlide();
    });

    console.info(currentSlideCounter);
    interval = $("#slider_time").val(); // initial condition from a section varaible on the index page
    var run = setInterval(nextSlide , interval); // start setInterval as "run"

  

    function nextSlide(){
        clearInterval(run); // stop the setInterval ()
        slideShow[currentSlideCounter].style.display= "none";
        currentSlideCounter = (currentSlideCounter +1) % slideShow.length;
        slideShow[currentSlideCounter].style.display = "block";


        if(slideShow[currentSlideCounter].classList.contains("dynamic-slide-video")){
            videoDuration = slideShow[currentSlideCounter].duration;
            videoDuration = Math.floor(videoDuration);
            videoDuration *= 1000;
            interval = videoDuration ;
            for(i=0;i<videosItems.length;i++){
                if(videosItems[i].currentSrc == slideShow[currentSlideCounter].currentSrc){
                    videosItems[i].setAttribute('autoplay',true);
                    videosItems[i].play();
                    videosItems[i].muted = false;
                    // videosItems[i].play();
                    videosItems[i].removeAttribute('muted');
                }else if(videosItems[i] != slideShow[currentSlideCounter]){
                    videosItems[i].currentTime =0;
                    videosItems[i].pause();
                    videosItems[i].removeAttribute('autoplay')
                }
            }
        }else if(slideShow[currentSlideCounter].getAttribute('timer') != null && slideShow[currentSlideCounter].getAttribute('src') != null){

            // videosItems.forEach(function(item){
            //     item.removeAttribute('autoplay');
            //     item.currentTime = 0;
            //     item.pause();
            // })
            interval = slideShow[currentSlideCounter].getAttribute('timer');

        } else if(slideShow[currentSlideCounter].getAttribute('timer') == null ){
            videosItems.forEach(function(item){
              item.removeAttribute('autoplay');
              item.currentTime = 0;
              item.pause();
          })
          
          interval = $("#slider_time").val();
    }
    // build up the dropdown to navigate to any slide that is clicked
        var index = 0;
        $( ".click-able-slide" ).one( "click", function() {
            index = $( ".click-able-slide" ).index( this );
            slideShow[currentSlideCounter].style.display="none";
            slideShow[index].style.display = "block";
        currentSlideCounter = index > 0? index-1 : index;
        if(slideShow[index].classList.contains("dynamic-slide-video")){
            for(i=0;i < videosItems.length; i++){
                if(videosItems[i].currentSrc == slideShow[index].currentSrc){
                    videosItems[i].play();
                    videosItems[i].muted = false;
                }else if(videosItems[i] != slideShow[index].currentSrc){
                    videosItems[i].pause();
                    videosItems[i].load();
                    videosItems[i].currentTime =0;
                    videosItems[i].muted = true;
                }

            }
        }if(index == 0){

            for(i=0;i < videosItems.length; i++){
                videosItems[i].pause();
                videosItems[i].load();
                videosItems[i].currentTime =0;
                videosItems[i].muted = true;
            }
        }
        index == 0?slideShow[0].style.display = "block": nextSlide();
    }); 
          
      run = setInterval(nextSlide, interval); // start the setInterval()
    }
    
  })
