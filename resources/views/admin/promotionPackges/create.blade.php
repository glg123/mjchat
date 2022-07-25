@extends('admin.layout.app')
@push('css')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css"
          type="text/css" media="screen" charset="utf-8">

@endpush
@section('content')
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__body">
                <form method="post" action="{{$form_url}}" enctype="multipart/form-data">
                    @csrf
                    @method('post')

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
                                                    <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i>
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
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.title_ar')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input type="text" value="{{old('title_ar')}}" name="title_ar"
                                                               class="form-control"
                                                               placeholder="{{ trans('views.title_ar') }}">
                                                        <div
                                                            class="invalid-feedback">{{$errors->first('title_ar')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.title_en')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input type="text" value="{{old('title_en')}}" name="title_en"
                                                               class="form-control"
                                                               placeholder="{{ trans('views.title_en') }}">
                                                        <div
                                                            class="invalid-feedback">{{$errors->first('title_en')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.description_ar')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <textarea class="editable" name="description_ar" cols="30"
                                                                  rows="10">{{__('views.description_ar')}}</textarea>


                                                        <div
                                                            class="invalid-feedback">{{$errors->first('description_ar')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.description_en')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <textarea class="editable" name="description_en" cols="30"
                                                                  rows="10">{{__('views.description_en')}}</textarea>


                                                        <div
                                                            class="invalid-feedback">{{$errors->first('description_en')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.type')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <select id="type" class="form-control" name="type">
                                                            <option
                                                                value="appearance_number">{{__('views.appearance_number')}}</option>
                                                            <option
                                                                value="number_days">{{__('views.number_days')}}</option>
                                                        </select>
                                                        <div class="invalid-feedback">{{$errors->first('type')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="views_package" class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.count_views_package')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                                            type="text" value="{{ old('count_views') }}"
                                                            name="count_views" class="form-control"
                                                            placeholder="{{ trans('views.count_views') }}">
                                                        <div
                                                            class="invalid-feedback">{{$errors->first('count_views')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="views_day" class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.count_days_package')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                                            type="text" value="{{ old('count_days') }}"
                                                            name="count_days" class="form-control"
                                                            placeholder="{{ trans('views.count_days') }}">
                                                        <div
                                                            class="invalid-feedback">{{$errors->first('count_days')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.price')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                                            type="text" value="{{ old('price') }}" name="price"
                                                            class="form-control"
                                                            placeholder="{{ trans('views.price') }}">
                                                        <div class="invalid-feedback">{{$errors->first('price')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.total_price')}} </label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <input
                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"
                                                            type="text" value="{{ old('total_price') }}"
                                                            name="total_price" class="form-control"
                                                            placeholder="{{ trans('views.total_price') }}">
                                                        <div
                                                            class="invalid-feedback">{{$errors->first('total_price')}}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{__('views.status')}}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group validated">
                                                        <select id="gender" class="form-control" name="status">
                                                            <option value="active">{{__('views.active')}}</option>
                                                            <option value="closed">{{__('views.closed')}}</option>
                                                        </select>
                                                        <div class="invalid-feedback">{{$errors->first('status')}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div
                                        class="kt-separator kt-separator--space-lg kt-separator--fit kt-separator--border-solid"></div>

                                    <div class="kt-form__actions">
                                        <div class="row">
                                            <div class="col-xl-3">
                                                <button class="btn btn-label-brand btn-bold"
                                                        type="submit">{{__('views.Save Changes')}}</button>

                                            </div>
                                            <div class="col-lg-9 col-xl-6">
                                                <a class="btn btn-clean btn-bold"
                                                   href="{{$index_url}}">{{__('views.Cancel')}}</a>
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

    <script src="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/js/medium-editor.min.js"></script>
    <script>var editor = new MediumEditor('.editable');
        $('#views_package').show();
        $('#views_day').hide();
        const select = document.getElementById('type');

        select.addEventListener('change', function handleChange(event) {
            var value = event.target.value; // 👉️ get selected VALUE
            if (value === 'number_days') {
                $('#views_package').hide();
                $('#views_day').show();
            }
            if (value === 'appearance_number') {
                $('#views_package').show();
                $('#views_day').hide();
            }
        });

    </script>

@endpush
