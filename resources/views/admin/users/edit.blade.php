@extends('admin.layout.app')

@section('content')
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__body">
                <form method="post" action="{{$update_url}}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $user->id }}">
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.first_name')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $user->first_name }}" name="first_name" class="form-control" placeholder="{{ trans('views.first_name') }}">
                                                        <div class="invalid-feedback">{{$errors->first('first_name')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.last_name')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $user->last_name }}" name="last_name" class="form-control" placeholder="{{ trans('views.last_name') }}">
                                                        <div class="invalid-feedback">{{$errors->first('last_name')}}</div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.email')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $user->email }}" name="email" class="form-control" placeholder="{{ trans('views.email') }}">
                                                        <div class="invalid-feedback">{{$errors->first('email')}}</div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.mobile')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $user->mobile }}" name="mobile" class="form-control" placeholder="{{ trans('views.mobile') }}">
                                                        <div class="invalid-feedback">{{$errors->first('mobile')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.iban_number')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->iban_number }}" name="iban_number" class="form-control" placeholder="{{ trans('views.iban_number') }}">
                                                            <div class="invalid-feedback">{{$errors->first('iban_number')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.facebook_link')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->facebook_link }}" name="facebook_link" class="form-control" placeholder="{{ trans('views.facebook_link') }}">
                                                            <div class="invalid-feedback">{{$errors->first('facebook_link')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.twitter_link')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->twitter_link }}" name="twitter_link" class="form-control" placeholder="{{ trans('views.twitter_link') }}">
                                                            <div class="invalid-feedback">{{$errors->first('twitter_link')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.instagram_link')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->instagram_link }}" name="instagram_link" class="form-control" placeholder="{{ trans('views.instagram_link') }}">
                                                            <div class="invalid-feedback">{{$errors->first('instagram_link')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.date_of_birth')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->date_of_birth }}" name="date_of_birth" class="form-control" placeholder="{{ trans('views.date_of_birth') }}">
                                                            <div class="invalid-feedback">{{$errors->first('date_of_birth')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.user_name')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->user_name }}" name="user_name" class="form-control" placeholder="{{ trans('views.user_name') }}">
                                                            <div class="invalid-feedback">{{$errors->first('user_name')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.img')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <img width="100" src="{{$user->img}}" alt="">                                                            <div class="invalid-feedback">{{$errors->first('img')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.points')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->points }}" name="points" class="form-control" placeholder="{{ trans('views.points') }}">
                                                            <div class="invalid-feedback">{{$errors->first('points')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.bio')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $user->bio }}" name="bio" class="form-control" placeholder="{{ trans('views.bio') }}">
                                                            <div class="invalid-feedback">{{$errors->first('bio')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.gender')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <select id="gender" class="form-control" name="gender">
                                                            <option value="1">{{__('views.male')}}</option>
                                                            <option value="2">{{__('views.female')}}</option>
                                                        </select>
                                                        <div class="invalid-feedback">{{$errors->first('gender')}}</div>
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

@push('css')
@endpush


@push('scripts')
    <script>



      KTUtil.ready(function () {
        new KTAvatar('kt_user_avatar_1');
        new KTAvatar('kt_user_avatar_2');
      });
    </script>
@endpush
