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

   $.get('../src/AjaxAction.class.php?action=getRates', function(response){
      if(response.length < 1) return;
      $('#all_rates1').attr('data-status', 1);
      $('#all_rates_data1').html(response);

  });

     if(SHOW_RATES){

        //  $.get('../src/AjaxAction.class.php?action=getRates', function(response){
        //     if(response.length < 1) return;
        //     $('#rates').html(response);
        //     $('#rates .marquee').marquee();
        // });
        //
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

        // $.get('../src/AjaxAction.class.php?action=getTabbedAllRates', function(response){
        //     $('.rates_container').removeClass('animated flash');
        //     if(response.length < 1) {return;}
        //     $('#all_rates').attr('data-status', 1);
        //     $('#all_rates_data').html(response);
        // });

        $.get('../src/AjaxAction.class.php?action=getInterestRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_transfer_rates').attr('data-status', 1);
            $('#all_transfer_rates_data').html(response);
        });

        $.get('../src/AjaxAction.class.php?action=getRates', function(response){
           if(response.length < 1) return;
           $('#all_rates2').attr('data-status', 1);
           $('#all_rates_data2').html(response);

       });

        $.get('../src/AjaxAction.class.php?action=getFixedDepositRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_deposit_rates').attr('data-status', 1);
            $('#all_deposit_rates_data').html(response);
        });

        $.get('../src/AjaxAction.class.php?action=getRates', function(response){
           if(response.length < 1) return;
           $('#all_rates3').attr('data-status', 1);
           $('#all_rates_data3').html(response);

       });

        $.get('../src/AjaxAction.class.php?action=getDailyIndicativeRates', function(response){

            if(response.length <= 1) {return;}
            $('#all_gov_rates').attr('data-status', 1);
            $('#all_gov_rates_data').html(response);
        });

        // finally refresh the base rates
        // $.get('../src/AjaxAction.class.php?action=getBaseRates', function(response){
        //   // get the base rates
        //   // build a mapping between the identities and the ids of the containers
        //   if(response.length <=0){
        //     return;
        //   }
        //
        //   response = JSON.parse(response);
        //
        //   var collections = [];
        //   collections['fxrate'] = 'all_rates_base_data';
        //   collections['fxtransferrate'] = 'all_transfer_rates_base_data';
        //   collections['fxdepositrate'] = 'all_deposit_rates_base_data';
        //   collections['fxgovrate'] = 'all_gov_rates_base_data';
        //
        //   for(var kk in collections){
        //     //alert(JSON.stringify(response['rates'][kk])+' :: for id '+collections[kk]);
        //     var ctr = response['rates'][kk];
        //     if(ctr && typeof ctr == 'object'){
        //       // we have an object load it
        //       var s = 'Base Rate : '+ctr.rate || null;
        //       var sid = collections[kk];
        //       var $d = jQuery('#'+sid);
        //       if($d){
        //         $d.empty().append(s);
        //       }
        //     }
        //   }
        //   return;
        // });


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
        target = window.flippers.pop();
      }

      // hide all other rates
      hideAllRateFrames();
      // make this one active
      console.log('changing view ...');
      jQuery('#'+target).show();
    }, 380000);
}
