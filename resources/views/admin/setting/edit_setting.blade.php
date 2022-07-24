@extends('admin.layout.app')
@push('css')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css" type="text/css" media="screen" charset="utf-8">

@endpush

@section('content')

    @if (Session::has('success'))
        <div class="alert alert-success btn-sm alert-fonts" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('incorrect_pass'))
        <div class="alert alert-danger btn-sm alert-fonts" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('incorrect_pass') }}
        </div>
    @endif


    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__body">
                <form method="post" action="{{$update_url}}" enctype="multipart/form-data">
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
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.about_us_ar')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="about_us_ar" cols="30" rows="80">{{$settings['about_us']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('about_us')}}</div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.about_us_en')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="about_us_en" cols="30" rows="80">{{$settings_en['about_us']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('about_us_en')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.privacy_policy_ar')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="privacy_policy_ar" cols="30" rows="80">{{$settings['privacy_policy']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('privacy_policy_ar')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.privacy_policy_en')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="privacy_policy_en" cols="30" rows="80">{{$settings_en['privacy_policy']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('privacy_policy_en')}}</div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.refer_ar')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="refer_ar" cols="30" rows="80">{{$settings['refer']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('refer_ar')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.refer_en')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="refer_en" cols="30" rows="80">{{$settings_en['refer']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('refer_en')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.twitter')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="text" value="{{$settings['twitter']}}" name="twitter" class="form-control" placeholder="{{__('views.twitter')}}">

                                                            <div class="invalid-feedback">{{$errors->first('twitter')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.facebook')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="text" value="{{$settings['facebook']}}" name="facebook" class="form-control" placeholder="{{__('views.facebook')}}">

                                                            <div class="invalid-feedback">{{$errors->first('facebook')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.linkedin')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="text" value="{{$settings['linkedin']}}" name="linkedin" class="form-control" placeholder="{{__('views.linkedin')}}">

                                                            <div class="invalid-feedback">{{$errors->first('linkedin')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.instagram')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="text" value="{{$settings['instagram']}}" name="instagram" class="form-control" placeholder="{{__('views.instagram')}}">

                                                            <div class="invalid-feedback">{{$errors->first('instagram')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.FAQs_ar')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="FAQs_ar" cols="30" rows="80">{{$settings['FAQs']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('FAQs')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.FAQs_en')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="FAQs_en" cols="30" rows="80">{{$settings_en['FAQs']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('FAQs_en')}}</div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.tearms_ar')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="tearms_ar" cols="30" rows="80">{{$settings['tearms']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('tearms_ar')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.tearms_en')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <textarea class="editable" name="tearms_en" cols="30" rows="80">{{$settings_en['tearms']}}</textarea>


                                                            <div class="invalid-feedback">{{$errors->first('tearms_en')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.email')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="email" value="{{$settings['email']}}"  name="email" class="form-control" placeholder="{{__('views.email')}}">

                                                            <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.sharepoint')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="number" value="{{$settings['sharepoint']}}"  name="sharepoint" class="form-control" placeholder="{{__('views.sharepoint')}}">

                                                            <div class="invalid-feedback">{{$errors->first('sharepoint')}}</div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.storypoint')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="number" value="{{$settings['storypoint']}}"  name="storypoint" class="form-control" placeholder="{{__('views.storypoint')}}">

                                                            <div class="invalid-feedback">{{$errors->first('storypoint')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.addspoint')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="number"  value="{{$settings['addspoint']}}" name="addspoint" class="form-control" placeholder="{{__('views.addspoint')}}">

                                                            <div class="invalid-feedback">{{$errors->first('addspoint')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.storydays')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="number"  value="{{$settings['storydays']}}"  name="storydays" class="form-control" placeholder="{{__('views.storydays')}}">

                                                            <div class="invalid-feedback">{{$errors->first('storydays')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.sharePrice')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="number"   value="{{$settings['sharePrice']}}" name="sharePrice" class="form-control" placeholder="{{__('views.sharePrice')}}">

                                                            <div class="invalid-feedback">{{$errors->first('sharePrice')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.point_price')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  value="{{$settings['point_price']}}" type="number"  name="point_price" class="form-control" placeholder="{{__('views.point_price')}}">

                                                            <div class="invalid-feedback">{{$errors->first('point_price')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.dayPrice')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input value="{{$settings['dayPrice']}}" type="number"  name="dayPrice" class="form-control" placeholder="{{__('views.dayPrice')}}">

                                                            <div class="invalid-feedback">{{$errors->first('dayPrice')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.unreactstorytime')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input value="{{$settings['unreactstorytime']}}"  type="number"  name="unreactstorytime" class="form-control" placeholder="{{__('views.unreactstorytime')}}">

                                                            <div class="invalid-feedback">{{$errors->first('unreactstorytime')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.mobile')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input value="{{$settings['mobile']}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" type="text"  name="mobile" class="form-control" placeholder="{{__('views.mobile')}}">

                                                            <div class="invalid-feedback">{{$errors->first('mobile')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.whatsapp')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input value="{{$settings['whatsapp']}}" type="text"  name="whatsapp" class="form-control" placeholder="{{__('views.whatsapp')}}">

                                                            <div class="invalid-feedback">{{$errors->first('whatsapp')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.google_map_key')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input  type="text" value="{{$settings['google_map_key']}}"  name="google_map_key" class="form-control" placeholder="{{__('views.google_map_key')}}">

                                                            <div class="invalid-feedback">{{$errors->first('google_map_key')}}</div>
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
    <script src="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/js/medium-editor.min.js"></script>
    <script>var editor = new MediumEditor('.editable');</script>
@endpush
