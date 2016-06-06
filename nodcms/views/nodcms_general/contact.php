<script type="text/javascript">
function check_request(){
	var input = $('#contact input.require');
	for(var i=0;i<input.length;i++){
		var each = input[i];
		if($(each).val()==''){
			alert('<?=_l('Please Fill Require Fealds',$this);?> '+ $(each).parent().parent().find('span.lbname').text()+'!');
			$(each).focus();
			return false;
		}
	}
}
</script>


 <!-- page start-->
   
  <div class="container">
	  <div class="row row-color">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <section class="panel">
                  <header class="panel-heading">
                      <?=_l("Map",$this)?>
                      <span class="tools pull-right">
                                <a href="javascript:;" class="fa fa-chevron-down"></a>
                                <a href="javascript:;" class="fa fa-remove"></a>
                            </span>
                  </header>
                  <div class="panel-body">
                      <div class="row">
                          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                              <h4 style="margin-top: 0;padding-top: 0"><?=$settings["company"]?></h4>
                              <p><i class="fa fa-phone-square"></i> <?=$settings["phone"]?></p>
                              <p><i class="fa fa-location-arrow"></i> <?=$settings["address"]?></p>
                          </div>
                          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                              <div id="gmap_marker" class="gmaps"></div>
                          </div>
                      </div>
                  </div>
              </section>
          </div>
      </div>
	  <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <section class="panel">
                  <header class="panel-heading">
                      <?=_l('Contact form',$this);?>
                  </header>
                  <div class="panel-body">
                      <?php if($this->session->flashdata('message_success')){?>
                      <div class="alert alert-success fade in">
                          <button type="button" class="close close-sm" data-dismiss="alert">
                              <i class="fa fa-times"></i>
                          </button>
                          <strong><?=_l('Success:',$this);?>  </strong> <?=_l('Your Request have been successfully sent!',$this);?>
                      </div>
                      <?php } ?>

                      <?php if($this->session->flashdata('message_error')){?>
                      <div class="alert alert-block alert-danger fade in">
                          <button type="button" class="close close-sm" data-dismiss="alert">
                              <i class="fa fa-times"></i>
                          </button>
                          <strong><?=_l('Oh snap!',$this);?></strong><?=_l('Problem with messages. Please notify the site administrator via the phone numbers listed',$this);?>
                      </div>
                      <?php } ?>

                      <form class="" id="contact" onsubmit="return check_request();" action="" method="post" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="name" class="control-label"><?=_l('Full Name',$this);?></label>
                                      <input type="text" id="name" name="data[name]" class="form-control require">

                                  </div>
                                  <div class="form-group">
                                      <label for="email" class="control-label"><?=_l('Email address',$this);?></label>
                                      <input type="text" id="email" name="data[email]" class="form-control require" placeholder="example@webmail.com">
                                  </div>

                                  <div class="form-group">
                                      <label for="subject" class="control-label"><?=_l('Subject',$this);?></label>
                                      <input type="text" id="subject" name="data[subject]" class="form-control require">
                                  </div>
                              </div>
                              <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="text" class="control-label"><?=_l('Request',$this);?></label>
                                      <textarea id="text" class="form-control" rows="10" name="data[text]"></textarea>
                                  </div>
                              </div>
                          </div>


                          <div class="form-group text-center">
                              <input type="submit" class="btn btn-danger" value="<?=_l('Send email',$this);?>"/>
                          </div>
                      </form>
                  </div>
              </section>
          </div>
	  </div>
 </div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?=base_url()?>assets/flatlab/js/gmaps.js"></script>

<script>
    var GoogleMaps = function () {
//
//        var mapBasic = function () {
//            new GMaps({
//                div: '#gmap_basic',
//                lat: -12.043333,
//                lng: -77.028333
//            });
//        }

        var mapMarker = function () {
            var map = new GMaps({
                div: '#gmap_marker',
                lat: <?php echo substr($settings["location"],0,10)?>,
                lng: <?php echo substr($settings["location"],11,10)?>
            });
            map.addMarker({
                lat: <?=substr($settings["location"],0,10)?>,
                lng: <?=substr($settings["location"],11,10)?>,
                title: 'Lima',
                details: {
                    database_id: 42,
                    author: 'HPNeo'
                },
                click: function (e) {
                    if (console.log) console.log(e);
                    alert('You clicked in this marker');
                }
            });
            map.addMarker({
                lat: -12.042,
                lng: -77.028333,
                title: 'Marker with InfoWindow',
                infoWindow: {
                    content: 'HTML Content!!!!'
                }
            });
        }
//
//        var mapPolylines = function() {
//            var map = new GMaps({
//                div: '#gmap_polylines',
//                lat: -12.043333,
//                lng: -77.028333,
//                click: function(e){
//                    console.log(e);
//                }
//            });
//
//            path = [[-12.044012922866312, -77.02470665341184], [-12.05449279282314, -77.03024273281858], [-12.055122327623378, -77.03039293652341], [-12.075917129727586, -77.02764635449216], [-12.07635776902266, -77.02792530422971], [-12.076819390363665, -77.02893381481931], [-12.088527520066453, -77.0241058385925], [-12.090814532191756, -77.02271108990476]];
//
//            map.drawPolyline({
//                path: path,
//                strokeColor: '#131540',
//                strokeOpacity: 0.6,
//                strokeWeight: 6
//            });
//        }
//
//        var mapGeolocation = function() {
//
//            var map = new GMaps({
//                div: '#gmap_geo',
//                lat: -12.043333,
//                lng: -77.028333
//            });
//
//            GMaps.geolocate({
//                success: function(position) {
//                    map.setCenter(position.coords.latitude, position.coords.longitude);
//                },
//                error: function(error) {
//                    alert('Geolocation failed: '+error.message);
//                },
//                not_supported: function() {
//                    alert("Your browser does not support geolocation");
//                },
//                always: function() {
//                    //alert("Geolocation Done!");
//                }
//            });
//        }
//
//        var mapGeocoding = function() {
//
//            var map = new GMaps({
//                div: '#gmap_geocoding',
//                lat: -12.043333,
//                lng: -77.028333
//            });
//
//            var handleAction = function() {
//                var text = $.trim($('#gmap_geocoding_address').val());
//                GMaps.geocode({
//                    address: text,
//                    callback: function(results, status){
//                        if(status=='OK'){
//                            var latlng = results[0].geometry.location;
//                            map.setCenter(latlng.lat(), latlng.lng());
//                            map.addMarker({
//                                lat: latlng.lat(),
//                                lng: latlng.lng()
//                            });
//                            App.scrollTo($('#gmap_geocoding'));
//                        }
//                    }
//                });
//            }
//
//            $('#gmap_geocoding_btn').click(function(e){
//                e.preventDefault();
//                handleAction();
//            });
//
//            $("#gmap_geocoding_address").keypress(function(e){
//                var keycode = (e.keyCode ? e.keyCode : e.which);
//                if(keycode == '13') {
//                    e.preventDefault();
//                    handleAction();
//                }
//            });
//
//        }
//
//        var mapPolygone = function() {
//            var map = new GMaps({
//                div: '#gmap_polygons',
//                lat: -12.043333,
//                lng: -77.028333
//            });
//
//            var path = [[-12.040397656836609,-77.03373871559225], [-12.040248585302038,-77.03993927003302],
//                [-12.050047116528843,-77.02448169303511],
//                [-12.044804866577001,-77.02154422636042]];
//
//            var polygon = map.drawPolygon({
//                paths: path,
//                strokeColor: '#BBD8E9',
//                strokeOpacity: 1,
//                strokeWeight: 3,
//                fillColor: '#BBD8E9',
//                fillOpacity: 0.6
//            });
//        }
//
//        var mapRoutes = function() {
//
//            var map = new GMaps({
//                div: '#gmap_routes',
//                lat: -12.043333,
//                lng: -77.028333
//            });
//            $('#gmap_routes_start').click(function(e){
//                e.preventDefault();
//                map.travelRoute({
//                    origin: [-12.044012922866312, -77.02470665341184],
//                    destination: [-12.090814532191756, -77.02271108990476],
//                    travelMode: 'driving',
//                    step: function(e){
//                        $('#gmap_routes_instructions').append('<li>'+e.instructions+'</li>');
//                        $('#gmap_routes_instructions li:eq('+e.step_number+')').delay(800*e.step_number).fadeIn(500, function(){
//                            map.setCenter(e.end_location.lat(), e.end_location.lng());
//                            map.drawPolyline({
//                                path: e.path,
//                                strokeColor: '#131540',
//                                strokeOpacity: 0.6,
//                                strokeWeight: 6
//                            });
//                            App.scrollTo($(this));
//                        });
//                    }
//                });
//            });
//        }

        return {
            //main function to initiate map samples
            init: function () {
//                mapBasic();
                mapMarker();
//                mapGeolocation();
//                mapGeocoding();
//                mapPolylines();
//                mapPolygone();
//                mapRoutes();
            }

        };

    }();
    jQuery(document).ready(function() {
        GoogleMaps.init();
    });
</script>


  <!-- page end-->


