@extends('admin.layout.app')

@section('content')
    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

        <!-- begin:: Subheader -->


        <!-- end:: Subheader -->

        <!-- begin:: Content -->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <div class="col-xl-6">
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
                                                        <div
                                                            class="alert alert-solid-danger alert-bold fade show kt-margin-t-20 kt-margin-b-40"
                                                            role="alert">
                                                            <div class="alert-icon"><i
                                                                    class="fa fa-exclamation-triangle"></i>
                                                            </div>
                                                            <div
                                                                class="alert-text">{{__('views.Oops, something went wrong! Please check the errors below.')}}
                                                                {!! implode('', $errors->all('<div>:message</div>')) !!}
                                                            </div>
                                                            <div class="alert-close">
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.first_name')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->first_name }}" name="first_name"
                                                                       class="form-control"
                                                                       placeholder="{{ trans('views.first_name') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('first_name')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.last_name')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->last_name }}" name="last_name"
                                                                       class="form-control"
                                                                       placeholder="{{ trans('views.last_name') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('last_name')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.email')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text" value="{{ $user->email }}"
                                                                       name="email" class="form-control"
                                                                       placeholder="{{ trans('views.email') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('email')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.mobile')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text" value="{{ $user->mobile }}"
                                                                       name="mobile" class="form-control"
                                                                       placeholder="{{ trans('views.mobile') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('mobile')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.iban_number')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->iban_number }}"
                                                                       name="iban_number" class="form-control"
                                                                       placeholder="{{ trans('views.iban_number') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('iban_number')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.facebook_link')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->facebook_link }}"
                                                                       name="facebook_link" class="form-control"
                                                                       placeholder="{{ trans('views.facebook_link') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('facebook_link')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.twitter_link')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->twitter_link }}"
                                                                       name="twitter_link" class="form-control"
                                                                       placeholder="{{ trans('views.twitter_link') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('twitter_link')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.instagram_link')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->instagram_link }}"
                                                                       name="instagram_link" class="form-control"
                                                                       placeholder="{{ trans('views.instagram_link') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('instagram_link')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.date_of_birth')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->date_of_birth }}"
                                                                       name="date_of_birth" class="form-control"
                                                                       placeholder="{{ trans('views.date_of_birth') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('date_of_birth')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.user_name')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text"
                                                                       value="{{ $user->user_name }}" name="user_name"
                                                                       class="form-control"
                                                                       placeholder="{{ trans('views.user_name') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('user_name')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.img')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <img width="100" src="{{$user->img}}" alt="">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('img')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.points')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text" value="{{ $user->points }}"
                                                                       name="points" class="form-control"
                                                                       placeholder="{{ trans('views.points') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('points')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.bio')}} </label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <input readonly type="text" value="{{ $user->bio }}"
                                                                       name="bio" class="form-control"
                                                                       placeholder="{{ trans('views.bio') }}">
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('bio')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label
                                                            class="col-xl-3 col-lg-3 col-form-label">{{__('views.gender')}}</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="input-group validated">
                                                                <select id="gender" class="form-control" name="gender">
                                                                    <option value="1">{{__('views.male')}}</option>
                                                                    <option value="2">{{__('views.female')}}</option>
                                                                </select>
                                                                <div
                                                                    class="invalid-feedback">{{$errors->first('gender')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                        </div>

                                        <div
                                            class="kt-separator kt-separator--space-lg kt-separator--fit kt-separator--border-solid"></div>

                                        <div class="kt-form__actions">
                                            <div class="row">
                                                <div class="col-xl-3"></div>
                                                <div class="col-lg-9 col-xl-6">
                                                    <a class="btn btn-clean btn-bold"
                                                       href="{{$index_url}}">{{__('views.Cancel')}}</a>
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
            <div class="col-xl-6">
                <div class="row">
                    <div class="col-xl-12">

                        <!--begin:: Widgets/Applications/User/Profile3-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget kt-widget--user-profile-3">
                                    <div class="kt-widget__top">
                                        <div class="kt-widget__media kt-hidden-">
                                            <img src="{{$user->img}}" alt="image">
                                        </div>
                                        <div
                                            class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                            JM
                                        </div>
                                        <div class="kt-widget__content">
                                            <div class="kt-widget__head">
                                                <a href="#" class="kt-widget__username">
                                                    {{$user->first_name}} {{$user->last_name}}
                                                    <i class="flaticon2-correct"></i>
                                                </a>

                                            </div>
                                            <div class="kt-widget__subhead">
                                                <a href="#"><i class="flaticon2-new-email"></i>{{$user->email}}</a>
                                                <a href="#"><i class="flaticon2-calendar-3"></i>{{$user->gender}} </a>
                                                <a href="#"><i class="flaticon2-placeholder"></i>{{$user->user_name}}
                                                </a>
                                            </div>
                                            <div class="kt-widget__info">
                                                <div class="kt-widget__desc">
                                                    {{$user->bio}}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-widget__bottom">
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-piggy-bank"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <span class="kt-widget__title">{{__('views.points')}}</span>
                                                <span class="kt-widget__value"><span>$</span>{{$user->points}}</span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-confetti"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <span class="kt-widget__title">{{__('views.comment_privacy')}}</span>
                                                <span class="kt-widget__value"><span>$</span>{{$user->comment_privacy}}</span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-pie-chart"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <span class="kt-widget__title">{{__('views.flowers')}}</span>
                                                <span class="kt-widget__value">{{@$user->count_flowers}}</span>
                                            </div>
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-pie-chart"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <span class="kt-widget__title">{{__('views.following')}}</span>
                                                <span class="kt-widget__value">{{@$user->count_following}}</span>
                                            </div>
                                        </div>

                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-chat-1"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <span
                                                    class="kt-widget__title">{{$user->count_post}} {{__('views.post')}}</span>
                                                <a href="#"
                                                   class="kt-widget__value kt-font-brand">{{__('views.view')}}</a>
                                            </div>
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__icon">
                                                <i class="flaticon-network"></i>
                                            </div>
                                            <div class="kt-widget__details">
                                                <div class="kt-section__content kt-section__content--solid">
                                                    <div class="kt-media-group">
                                                        @if($user->flowers)
                                                            @foreach($user->flowers as $flowersItem)

                                                                @if(!$loop->last)
                                                                    <a href="#" class="kt-media kt-media--sm kt-media--circle"
                                                                       data-toggle="kt-tooltip" data-skin="brand"
                                                                       data-placement="top" title=""
                                                                       data-original-title="{{$flowersItem->first_name}} {{$flowersItem->last_name}}">
                                                                        <img src="{{$flowersItem->img}}" alt="image">
                                                                    </a>
                                                                @else
                                                                    <a href="#" class="kt-media kt-media--sm kt-media--circle"
                                                                       data-toggle="kt-tooltip" data-skin="brand"
                                                                       data-placement="top" title=""
                                                                       data-original-title="Micheal York">
                                                                        <span>+3</span>
                                                                    </a>
                                                                    @endif
                                                            @endforeach
                                                                @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Applications/User/Profile3-->
                    </div>
                </div>

                <!--End::Section-->

                <!--Begin::Section-->


                <!--End::Section-->

                <!--Begin::Section-->
                <div class="row">

                    <div class="col-xl-12">

                        <!--begin:: Widgets/User Progress -->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        {{__('views.users')}}
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <ul class="nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab"
                                               href="#kt_widget31_tab1_content" role="tab">
                                                {{__('views.flowers')}}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_widget31_tab2_content"
                                               role="tab">
                                                {{__('views.following')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_widget31_tab1_content">
                                        <div class="kt-widget31">
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic">
                                                        <img src="assets/media/users/100_4.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Anna Strong
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Visual Designer,Google Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">

                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="kt_widget31_tab2_content">
                                        <div class="kt-widget31">
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic kt-widget4__pic--pic">
                                                        <img src="assets/media/users/100_11.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Nick Bold
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Web Developer, Facebook Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__progress">
                                                        <a href="#" class="kt-widget31__stats">
                                                            <span>13%</span>
                                                            <span>London</span>
                                                        </a>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                 style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic kt-widget4__pic--pic">
                                                        <img src="assets/media/users/100_1.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Wiltor Delton
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Project Manager, Amazon Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__progress">
                                                        <div class="kt-widget31__stats">
                                                            <span>93%</span>
                                                            <span>New York</span>
                                                        </div>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-danger" role="progressbar"
                                                                 style="width: 45%" aria-valuenow="45" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic">
                                                        <img src="assets/media/users/100_14.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Milano Esco
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Product Designer, Apple Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__progress">
                                                        <a href="#" class="kt-widget31__stats">
                                                            <span>33%</span>
                                                            <span>Paris</span>
                                                        </a>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-warning" role="progressbar"
                                                                 style="width: 55%" aria-valuenow="55" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic">
                                                        <img src="assets/media/users/100_6.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Sam Stone
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Project Manager, Kilpo Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__progress">
                                                        <div class="kt-widget31__stats">
                                                            <span>50%</span>
                                                            <span>New York</span>
                                                        </div>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                 style="width: 65%" aria-valuenow="65" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>
                                            <div class="kt-widget31__item">
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__pic">
                                                        <img src="assets/media/users/100_4.jpg" alt="">
                                                    </div>
                                                    <div class="kt-widget31__info">
                                                        <a href="#" class="kt-widget31__username">
                                                            Anna Strong
                                                        </a>
                                                        <p class="kt-widget31__text">
                                                            Visual Designer,Google Inc
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="kt-widget31__content">
                                                    <div class="kt-widget31__progress">
                                                        <a href="#" class="kt-widget31__stats">
                                                            <span>63%</span>
                                                            <span>London</span>
                                                        </a>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-brand" role="progressbar"
                                                                 style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <a href="#" class="btn-label-brand btn btn-sm btn-bold">Follow</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/User Progress -->
                    </div>
                </div>

                <!--End::Section-->

                <!--Begin::Section-->
                <div class="row">
                    <div class="col-xl-12">

                        <!--begin:: Widgets/Last Updates-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        {{__('views.groups')}}
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <a href="#" class="btn btn-label-brand btn-bold btn-sm dropdown-toggle"
                                       data-toggle="dropdown">
                                        Today
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-right">

                                        <!--begin::Nav-->
                                        <ul class="kt-nav">
                                            <li class="kt-nav__head">
                                                Export Options
                                                <span data-toggle="kt-tooltip" data-placement="right"
                                                      title="Click to learn more...">
																<svg xmlns="http://www.w3.org/2000/svg"
                                                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                     width="24px" height="24px" viewBox="0 0 24 24"
                                                                     version="1.1"
                                                                     class="kt-svg-icon kt-svg-icon--brand kt-svg-icon--md1">
																	<g stroke="none" stroke-width="1" fill="none"
                                                                       fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"/>
																		<circle fill="#000000" opacity="0.3" cx="12"
                                                                                cy="12" r="10"/>
																		<rect fill="#000000" x="11" y="10" width="2"
                                                                              height="7" rx="1"/>
																		<rect fill="#000000" x="11" y="7" width="2"
                                                                              height="2" rx="1"/>
																	</g>
																</svg> </span>
                                            </li>
                                            <li class="kt-nav__separator"></li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-drop"></i>
                                                    <span class="kt-nav__link-text">Activity</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
                                                    <span class="kt-nav__link-text">FAQ</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-telegram-logo"></i>
                                                    <span class="kt-nav__link-text">Settings</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="#" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-new-email"></i>
                                                    <span class="kt-nav__link-text">Support</span>
                                                    <span class="kt-nav__link-badge">
																	<span
                                                                        class="kt-badge kt-badge--success kt-badge--rounded">5</span>
																</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__separator"></li>
                                            <li class="kt-nav__foot">
                                                <a class="btn btn-label-danger btn-bold btn-sm" href="#">Upgrade
                                                    plan</a>
                                                <a class="btn btn-clean btn-bold btn-sm" href="#"
                                                   data-toggle="kt-tooltip" data-placement="right"
                                                   title="Click to learn more...">Learn more</a>
                                            </li>
                                        </ul>

                                        <!--end::Nav-->
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__body">

                                <!--begin::widget 12-->
                                <div class="kt-widget4">
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon-pie-chart-1 kt-font-info"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Metronic v6 has been arrived!
                                        </a>
                                        <span class="kt-widget4__number kt-font-info">+500</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon-safe-shield-protection  kt-font-success"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Metronic community meet-up 2019 in Rome.
                                        </a>
                                        <span class="kt-widget4__number kt-font-success">+1260</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon2-line-chart kt-font-danger"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Metronic Angular 8 version will be landing soon...
                                        </a>
                                        <span class="kt-widget4__number kt-font-danger">+1080</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon2-pie-chart-1 kt-font-primary"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            ale! Purchase Metronic at 70% off for limited time
                                        </a>
                                        <span class="kt-widget4__number kt-font-primary">70% Off!</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon2-rocket kt-font-brand"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Metronic VueJS version is in progress. Stay tuned!
                                        </a>
                                        <span class="kt-widget4__number kt-font-brand">+134</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon2-notification kt-font-warning"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Black Friday! Purchase Metronic at ever lowest 90% off for limited time
                                        </a>
                                        <span class="kt-widget4__number kt-font-warning">70% Off!</span>
                                    </div>
                                    <div class="kt-widget4__item">
													<span class="kt-widget4__icon">
														<i class="flaticon2-file kt-font-success"></i>
													</span>
                                        <a href="#" class="kt-widget4__title kt-widget4__title--light">
                                            Metronic React version is in progress.
                                        </a>
                                        <span class="kt-widget4__number kt-font-success">+13%</span>
                                    </div>
                                </div>

                                <!--end::Widget 12-->
                            </div>
                        </div>

                        <!--end:: Widgets/Last Updates-->
                    </div>

                </div>
            </div>

            <!--Begin::Section-->


            <!--End::Section-->
        </div>

        <!-- end:: Content -->
    </div>

    <!-- end:: Content -->

@endsection

@push('css')
@endpush


@push('scripts')
    <script src="{{asset('admin_assets/js/pages/dashboard.js')}}" type="text/javascript"></script>
@endpush
