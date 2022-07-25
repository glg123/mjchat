@extends('admin.layout.app')

@section('content')
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__body">
                <form method="post" action="{{$update_url}}" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
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
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.promotion_package_name')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $order->promotion_package_name }}" name="promotion_package_name" class="form-control" placeholder="{{ trans('views.promotion_package_name') }}">
                                                        <div class="invalid-feedback">{{$errors->first('promotion_package_name')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.full_name')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input readonly type="text" value="{{ $order->user_name }}" name="full_name" class="form-control" placeholder="{{ trans('views.last_name') }}">
                                                        <div class="invalid-feedback">{{$errors->first('full_name')}}</div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.post')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <a href="{{route('admin.posts.show',$order->post_id)}}">{{$order->post_name}}</a>

                                                   <div class="invalid-feedback">{{$errors->first('post_id')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.start_date')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->start_date }}" name="start_date" class="form-control" placeholder="{{ trans('views.last_name') }}">
                                                            <div class="invalid-feedback">{{$errors->first('start_date')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.end_date')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->end_date }}" name="end_date" class="form-control" placeholder="{{ trans('views.last_name') }}">
                                                            <div class="invalid-feedback">{{$errors->first('end_date')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.count_views')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->count_views }}" name="count_views" class="form-control" placeholder="{{ trans('views.count_views') }}">
                                                            <div class="invalid-feedback">{{$errors->first('count_views')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.remaining_views')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->remaining_views }}" name="remaining_views" class="form-control" placeholder="{{ trans('views.remaining_views') }}">
                                                            <div class="invalid-feedback">{{$errors->first('remaining_views')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.order_create_at')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->order_create_at }}" name="order_create_at" class="form-control" placeholder="{{ trans('views.order_create_at') }}">
                                                            <div class="invalid-feedback">{{$errors->first('order_create_at')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.order_updated_at')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->order_updated_at }}" name="order_create_at" class="form-control" placeholder="{{ trans('views.order_updated_at') }}">
                                                            <div class="invalid-feedback">{{$errors->first('order_updated_at')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.total_price')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->total_price }}" name="total_price" class="form-control" placeholder="{{ trans('views.total_price') }}">
                                                            <div class="invalid-feedback">{{$errors->first('total_price')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.currency_code')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->currency_code }}" name="currency_code" class="form-control" placeholder="{{ trans('views.currency_code') }}">
                                                            <div class="invalid-feedback">{{$errors->first('currency_code')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.payer_name')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->payer_name }}" name="payer_name" class="form-control" placeholder="{{ trans('views.payer_name') }}">
                                                            <div class="invalid-feedback">{{$errors->first('payer_name')}}</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.payer_email_address')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->payer_email_address }}" name="payer_email_address" class="form-control" placeholder="{{ trans('views.payer_email_address') }}">
                                                            <div class="invalid-feedback">{{$errors->first('payer_email_address')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.payer_id')}} </label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <input readonly type="text" value="{{ $order->payer_id }}" name="payer_id" class="form-control" placeholder="{{ trans('views.payer_id') }}">
                                                            <div class="invalid-feedback">{{$errors->first('payer_id')}}</div>

                                                    </div>
                                                </div>








                                        </div>
                                                <div class="form-group row">
                                                    <label class="col-xl-3 col-lg-3 col-form-label">{{__('views.status')}}</label>
                                                    <div class="col-lg-9 col-xl-6">
                                                        <div class="input-group validated">
                                                            <select id="gender" class="form-control" name="status">
                                                                <option @if($order->status=='active') selected @endif value="active">{{__('views.active')}}</option>
                                                                <option @if($order->status=='closed') selected @endif value="closed">{{__('views.closed')}}</option>
                                                            </select>
                                                            <div class="invalid-feedback">{{$errors->first('status')}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>

                                </div>

                                <div class="kt-separator kt-separator--space-lg kt-separator--fit kt-separator--border-solid"></div>

                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <button class="btn btn-label-brand btn-bold" type="submit">{{__('views.Save Changes')}}</button>

                                        </div>
                                        <div class="col-lg-9 col-xl-6">
                                            <a class="btn btn-clean btn-bold" href="{{$index_url}}">{{__('views.Cancel')}}</a>
                                        </div>
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
