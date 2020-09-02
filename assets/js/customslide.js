  window.addEventListener('load', function(){

    // setInterval(function(){
    //     window.location.reload(true)
    // }, 1800000)

      const slideShow = document.querySelectorAll(".dynamic-slides");
      let videosItems = document.querySelectorAll(".dynamic-slide-video");
      let currentSlideCounter = 0;
      slideShow[currentSlideCounter].style.display="block";
      
    //   interval = 3000;
      interval = $("#slider_time").val(); // initial condition from a section varaible on the index page
      var run = setInterval(nextSlide , interval); // start setInterval as "run"

    

        function nextSlide(){
            clearInterval(run); // stop the setInterval ()
            slideShow[currentSlideCounter].style.display= "none";
            currentSlideCounter = (currentSlideCounter +1) % slideShow.length;
            slideShow[currentSlideCounter].style.display = "block";

            slideShow[currentSlideCounter].addEventListener('click', function(){
                document.querySelector("#simpleModal").style.display = 'block';
            })
          
            if(slideShow[currentSlideCounter].getAttribute('timer') != null && slideShow[currentSlideCounter].getAttribute('src') != null){
                interval = slideShow[currentSlideCounter].getAttribute('timer')  ;
                // alert(interval)
            }else if(slideShow[currentSlideCounter].classList.contains('dynamic-slide-video')){
                videoDuration = slideShow[currentSlideCounter].duration;
                videoDuration = Math.floor(videoDuration);
                videoDuration *= 1000;
                interval = videoDuration ;
                // alert(interval)
            }else if(slideShow[currentSlideCounter].getAttribute('timer') == null ){
                interval = $("#slider_time").val();
            }
            
            if(slideShow[currentSlideCounter].classList.contains("dynamic-slide-video")){
                // slideShow[currentSlideCounter].setAttribute('autoplay',true);
                
                
                for(i=0;i<videosItems.length;i++){
                  if(videosItems[i].currentSrc == slideShow[currentSlideCounter].currentSrc){
                      videosItems[i].play();
                      videosItems[i] .addEventListener('playing', function(){
                          
                          if(slideShow[currentSlideCounter].muted==true){
                              slideShow[currentSlideCounter].muted = false;
                          }
                        
                    });
                        
                        // slideShow[currentSlideCounter].setAttribute('autoplay',true);
                    }else if(videosItems[i] != slideShow[currentSlideCounter]){
                        videosItems[i].currentTime =0;
                        videosItems[i].pause();
                        // videosItems[i].removeAttribute('autoplay')
                    }
                }
            }else{
            videosItems.forEach(function(item){
                // item.removeAttribute('autoplay');
                item.currentTime = 0;
                item.pause();
            })
            
        }
        run = setInterval(nextSlide, interval); // start the setInterval()
    
    }

})

