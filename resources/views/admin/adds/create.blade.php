@extends('admin.layout.app')
@push('css')
    <style>
        #map {
            height: 300px;
            border: 1px solid #000;
        }
    </style>

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
                                                            <div id="map"></div>
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

@endpush
