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
            $('#all_rates').attr('data-status', 1).html(response);
        });

        $.get('../src/AjaxAction.class.php?action=getDepositRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_deposit_rates').attr('data-status', 1).html(response);
        });

        $.get('../src/AjaxAction.class.php?action=getTransferRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_transfer_rates').attr('data-status', 1).html(response);
        });

        $.get('../src/AjaxAction.class.php?action=getGovTreasuryRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_gov_rates').attr('data-status', 1).html(response);
        });


    }, 5000);

    // set an interval that will cycle the rate types
    function hideAllRateFrames(){
      jQuery('.cctx').each(function(i){
        var $r = jQuery(this);
        $r.hide();
      });

    };

    function getFrames(){
      var frames = [];
      jQuery('.cctx').each(function(i){
        var $r = jQuery(this);
        // if this has the data-status attr we track it
        if(parseInt($r.attr('data-status')) > 0){
          frames.push($r.attr('id'));
        }
      });

      return frames;
    };
    window.flippers = getFrames();
    setInterval(function(){
      // get a frame and switch to it to make it active
      var target = null;
      if(window.flippers.length){
        target = window.flippers.pop();
      }else{
        window.flippers = getFrames();
        target = windoe.flippers.pop();
      }

      // hide all other rates
      hideAllRateFrames();
      // make this one active
      jQuery('#'+target).show();
    }, 18000);
}
