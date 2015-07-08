var $ = jQuery;
$(function(){
    // TEST


    // EO TEST
    $("body").on("click", ".api", function(){
        var url = $(this).attr('url');
        var selector = $(this).attr('selector');
        var callback = $(this).attr('callback');

        if ( typeof callback == 'function' ) {
            //console.log("callback: " + callback);
            ajax_api(url, callback);
        }
        else if ( typeof selector != 'undefined' && selector != '' ) {
            //console.log("selector: " + selector);
            ajax_api(url, function(data){
                try {
                    if ( typeof data.error != 'undefined' ) alert('Error: ' + data.error);
                    else if ( typeof data.message != 'undefined' ) alert(data.message);
                    else $(selector).html(data.result);
                }
                catch (e) {
                    console.log("JSON.parse() error");
                    console.log(data);
                }
            });
        }
        else {
            //console.log("no callback and no selecotr");
        }
    });
});

/**
 * ajax api call for portal
 *
 * @param qs - query string or POST data
 * @param callback_function
 *
 * @code
 var qs = {};
 qs.method = 'scheduleTable';
 qs.teacher = teacher;
 ajax_api ( qs, function( re ) { } );

 * @Attention This method saved the returned-data from server into Web Storage IF qs.cache is set to true.
 *
 *  - and it uses the stored data to display on the web browser,
 *  - after that, it continues loading data from server
 *  - when it got new data from server, it display onto web browser and update the storage.
 *
 */
function ajax_api( url, callback_function )
{
    console.log('ajax_api:' + url);
    var promise = $.ajax( { url : url } );
    promise.done( function( re ) {
        //console.log("promise.done() : callback function : " + callback_function);
        try {
            var data = JSON.parse(re);
            callback_function( data )
        }
        catch (e) {
            alert(re);
        }
    });

    promise.fail( function( re ) {
        // alert('ajax call - promise failed');
        console.log("promise failed...");
        console.log(re);
    });
}