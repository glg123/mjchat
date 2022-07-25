@extends('admin.layout.app')

@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--Begin::Section-->
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="row row-no-padding row-col-separator-xl">
                    <div class="col-md-12 col-lg-12 col-xl-12">

                        <!--begin:: Widgets/Stats2-1 -->
                        <div class="kt-widget1">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">{{__('views.count_comments')}}</h3>

                                </div>
                                <span class="kt-widget1__number kt-font-brand">{{$post->comments_count}}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">{{__('views.count_show')}}</h3>

                                </div>
                                <span class="kt-widget1__number kt-font-danger">{{$post->views}}</span>
                            </div>
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">{{__('views.count_like')}}</h3>

                                </div>
                                <span class="kt-widget1__number kt-font-success">{{$post->count_likes}}</span>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-1 -->
                    </div>

                </div>
            </div>
        </div>

        <!--End::Section-->

        <!--Begin::Section-->
        <div class="row">
            <div class="col-xl-12">

                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{__('views.comments')}}
                            </h3>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                <div class="kt-widget2">
                                    @if($post->comments)
                                    @foreach($post->comments as $comments)
                                        <div class="kt-widget2__item kt-widget2__item--primary">
                                            <div class="kt-widget2__checkbox">
                                                <label class="">

                                                    <span></span>
                                                </label>
                                            </div>
                                        <div class="kt-widget2__info">
                                            <a href="#" class="kt-widget2__title">
                                                {{$comments->text}}
                                            </a>
                                            <a href="#" class="kt-widget2__username">
                                                {{$comments->user->first_name }}  {{$comments->user->last_name }}
                                            </a>
                                        </div>
                                        <div class="kt-widget2__actions">
                                            <a href="#" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown">
                                                <i class="flaticon-more-1"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
                                                <ul class="kt-nav">
                                                    <li class="kt-nav__item">
                                                        <a id="deleteComment{{$comments->id}}" data-id="{{$comments->id }}" class="kt-nav__link deleteComment">
                                                            <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                                            <span class="kt-nav__link-text">{{__('views.delete')}}</span>
                                                        </a>
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                        @endforeach
                                  @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Tasks -->
            </div>

        </div>


        <!--End::Section-->
    </div>
    <!-- end:: Content -->


@endsection

@push('css')
@endpush


@push('scripts')
    <script>

        $(document).on("click", ".deleteComment", function () {
            var id = $(this).data("id");
            var element = $(this);
            var url = '{{route('admin.delete.comment')}}';
            var csrf_token = '{{csrf_token()}}';
            $.ajax({
                url: url,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrf_token},
                data: {comment_id: id, _token: csrf_token},
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.msg)
                        $('#deleteComment'+id).parent().parent().parent().parent().parent().hide();
                     //   console.log(element.parent().parent());

                    } else {
                        alert(response.msg)
                    }


                },
                error: function () {

                    alert("error");
                }
            });

        });

      KTUtil.ready(function () {
        new KTAvatar('kt_user_avatar_1');
        new KTAvatar('kt_user_avatar_2');
      });
    </script>
@endpush
