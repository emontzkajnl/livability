// import * from 'all-places';
(function ($) {
    
    // console.dir('jsonObj: ',jsonObj);
    function printCoordinates(id, lat, lng) {
        const placeInfo = {
          id: id, 
          lat: lat,
          lng: lng,
          action: "writeJson"
        }
        $.ajax({
            url: params.ajaxurl,
            data: placeInfo,
            type: "POST",
            dataType: "JSON",
            success: data => {
                if (data) {
                    console.dir(data);
                }
            }
        });
    }
    // printCoordinates(49240, 20, 30);
    // printCoordinates(49250, 260, 30);
    function runAjax(element) {
        let title = element['title'].replace(/"/g,'').replace(/ /g,'+');
        let id = element['id'];
        $.ajax({
          url: `https://maps.googleapis.com/maps/api/geocode/json?address=${title}&key=AIzaSyAfO29tXYeeean_v42L4cnX_x0VH5EhnI0`,
        }).then(function( data ){
          console.log('data: ');
          console.dir(data);
          let lat = data['results'][0]['geometry']['location']['lat'];
          let lng = data['results'][0]['geometry']['location']['lng'];
          return  printCoordinates(id, lat, lng);
        }).fail(()=>console.log('geolocation failed'));
    }

  function get_coordinates() {
    jsonObj.forEach((element, i)  => {
        setTimeout(runAjax, i * 100, element);
      });
    }

    // Run entire function to get all coordinates and write to result file
    // get_coordinates();
    // https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyAfO29tXYeeean_v42L4cnX_x0VH5EhnI0
    // $.ajax({
    //   url: 'https://maps.googleapis.com/maps/api/geocode/json?address=1625+N+Central+Ave,+Phoenix,+AZ&key=AIzaSyAfO29tXYeeean_v42L4cnX_x0VH5EhnI0'
    // }).then(function(data){ 
    //   console.log('data: ');
    //   console.dir(data);
    // });
    // $.ajax({
    //   url: 'https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyAfO29tXYeeean_v42L4cnX_x0VH5EhnI0'
    // }).then(function(data){ 
    //   console.log('data: ');
    //   console.dir(data);
    // });
    console.log('encode url: ',encodeURI('Phoenix, AZ'));
})(jQuery);