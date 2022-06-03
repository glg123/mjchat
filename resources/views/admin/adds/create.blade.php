@extends('admin.layout.app')
@push('css')


@endpush
@section('content')
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__body">
                <form method="post" action="{{$create_url}}" enctype="multipart/form-data">
                    @csrf
                    @method('post')

                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_user_edit_tab_1" role="tabpanel">
                            <div class="kt-form kt-form--label-right">
                                <div class="kt-form__body">
                                    <div class="kt-section kt-section--first">
                                        <div class="kt-section__body">


                                            @if ($errors->any())
                                                <div class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40" role="alert">
                                                    <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i>
                                                    </div>
                                                    <div class="alert-text">{{__('views.Oops, something went wrong! Please check the errors below.')}}
                                                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                                                    </div>
                                                    <div class="alert-close">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.url')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input  type="url" value="" name="url" class="form-control" placeholder="{{ trans('views.url') }}">
                                                        <div class="invalid-feedback">{{$errors->first('url')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.image')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input  type="file" value="" name="img" class="form-control" placeholder="{{ trans('views.img') }}">
                                                        <div class="invalid-feedback">{{$errors->first('img')}}</div>
                                                    </div>
                                                </div>
                                            </div>


                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.image')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <fieldset>
                                                                <legend>{{""}}</legend>
                                                                <input id="searchInput" class="input-controls" type="text"
                                                                       placeholder="Enter a location">
                                                                <div class="map" id="map" style="width: 100%; height: 300px;"></div>
                                                                <div class="form_area">
                                                                    <input type="text" disabled value="" name="address" id="location">
                                                                    <input type="text" value="" name="latitude" id="lat">
                                                                    <input type="text" value="" name="longitude" id="lng">
                                                                </div>

                                                            </fieldset>

                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.type')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <select id="type" class="form-control" name="type">
                                                            <option value="1">{{__('1')}}</option>
                                                            <option value="2">{{__('2')}}</option>
                                                        </select>
                                                        <div class="invalid-feedback">{{$errors->first('type')}}</div>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>

                                </div>

                                <div class="kt-separator kt-separator--space-lg kt-separator--fit kt-separator--border-solid"></div>

                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-xl-3"></div>
                                        <div class="col-lg-9 col-xl-6">
                                            <button class="btn btn-label-brand btn-bold" type="submit">{{__('views.Save Changes')}}</button>

                                            <a class="btn btn-clean btn-bold" href="{{$index_url}}">{{__('views.Cancel')}}</a>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- end:: Content -->


@endsection




@push('scripts')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtdvEJAz8r2k5V6Q618BosZla0iGrdMqU&sensor=false"></script>
<script>
    function initialize() {
        var latlng = new google.maps.LatLng('{{$setting->latitude}}', '{{$setting->longitude}}');
        var map = new google.maps.Map(document.getElementById('map'), {
            center: latlng,
            zoom: 10,
            gestureHandling: 'greedy'
        });
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29)
        });
        var input = document.getElementById('searchInput');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        var geocoder = new google.maps.Geocoder();
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();
        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            bindDataToForm(place.formatted_address, place.geometry.location.lat(), place.geometry.location.lng());
            infowindow.setContent(place.formatted_address);
            infowindow.open(map, marker);

        });
        // this function will work on marker move event into map
        google.maps.event.addListener(marker, 'dragend', function () {
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        bindDataToForm(results[0].formatted_address, marker.getPosition().lat(), marker.getPosition().lng());
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                    }
                }
            });
        });
    }

    function bindDataToForm(address, lat, lng) {
        document.getElementById('location').value = address;
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
//                                                console.log('location = ' + address);
//                                                console.log('lat = ' + lat);
//                                                console.log('lng = ' + lng);
    }

    google.maps.event.addDomListener(window, 'load', initialize);

</script>
@endpush
