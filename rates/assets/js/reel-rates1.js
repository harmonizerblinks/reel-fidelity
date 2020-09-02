$(document).ready(function() {                
            
        //Load all rates
        refreshAllRates();

        setInterval(function(){
           refreshAllRates();
            }, 1000*60*RATES_API_REFRESH_INTERVAL);
           
            $('.rates_container').dblclick(function() {
                $(this).addClass('animated flash');
                refreshAllRates();                    
            });
});

function refreshAllRates(){
     if(SHOW_RATES){
         $.get('../src/AjaxAction.class.php?action=getRates', function(response){
            if(response.length < 1) return;
            $('#rates').html(response);
            $('#rates .marquee').marquee();
        });
         $('#feeds').hide();
    }else if(SHOW_SOCIAL_NETWORK_FEEDS){
        $.get('../src/AjaxAction.class.php?action=getSocialFeeds', function(response){
            if(response.length < 1) return;
            $('#feeds').html(response);
            $('#feeds .marquee').marquee();
        });
        $('#rates').hide();
    }

    setTimeout(function(){
        $.get('../src/AjaxAction.class.php?action=getTabbedAllRates', function(response){
            $('.rates_container').removeClass('animated flash');                    
            if(response.length < 1) {return;}
            $('#all_rates').html(response);
        });
    }, 5000);            
}

        