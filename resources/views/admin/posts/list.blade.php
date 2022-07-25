@extends('admin.layout.app')
@push('css')

@endpush
@section('content')
    <!-- begin:: Content -->

    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--Begin::Section-->
        @foreach($posts->chunk(4) as $postItems)
            <div class="row">
                @foreach($postItems as $post)
                    <div class="col-xl-3">

                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">
                                        <span class="btn btn-bold btn-sm btn-font-sm  btn-label-success">Success</span>

                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <a href="#" class="btn btn-icon" data-toggle="dropdown">
                                        <i class="flaticon-more-1 kt-font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <ul class="kt-nav">
                                            <li class="kt-nav__item">
                                                <a class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-document"></i>


                                                    <span id="comments_show" data-id="{{$post->id}}"
                                                          class="kt-nav__link-text comments_show">  {{__('views.comments')}}</span>
                                                </a>
                                            </li>
                                            <li class="kt-nav__item">
                                                <a href="{{route('admin.posts.show',$post->id)}}" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-settings"></i>
                                                    <span class="kt-nav__link-text">{{__('views.show')}}</span>
                                                </a>
                                            </li>

                                            <li class="kt-nav__item flaticon2-percentage">


                                                <a href="http://maps.google.com/maps?q={{$post->lat}},{{$post->long}}" class="kt-nav__link">
                                                    <i class="kt-nav__link-icon flaticon2-map"></i>
                                                    <span class="kt-nav__link-text">{{__('views.clickLocation')}}</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__body">

                                <!--begin::Widget -->
                                <div class="kt-widget kt-widget--user-profile-2">
                                    <div class="kt-widget__head">
                                        <div class="kt-widget__media">
                                            <img class="kt-widget__img kt-hidden-" src="{{$post->owner->img}}"
                                                 alt="image">
                                            <div
                                                class="kt-widget__pic kt-widget__pic--success kt-font-success kt-font-boldest kt-hidden">
                                                ChS
                                            </div>
                                        </div>
                                        <div class="kt-widget__info">
                                            <a href="#" class="kt-widget__username">
                                                {{$post->owner->first_name}} {{$post->owner->last_name}}
                                            </a>
                                            <span class="kt-widget__desc">
													{{$post->owner->user_name}}
														</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__body">
                                        <div class="kt-widget__section">
                                            {{$post->comment}}
                                        </div>
                                        <div class="kt-widget__item">
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">{{__('views.email')}}:</span>
                                                <a href="#" class="kt-widget__data"> {{$post->owner->email}}</a>
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">{{__('views.mobile')}}:</span>
                                                <a href="#" class="kt-widget__data">{{$post->owner->mobile}}</a>
                                            </div>
                                            <div class="kt-widget__contact">
                                                <span class="kt-widget__label">{{__('views.status')}}:</span>
                                                <span
                                                    class="kt-widget__data btn-label-brand">{{$post->owner->status_text}}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!--end::Widget -->
                            </div>
                        </div>

                        <!--End::Portlet-->
                    </div>
                @endforeach
            </div>

        @endforeach
        <div class="row">
            <div class="col-xl-12">

                <!--begin:: Components/Pagination/Default-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">

                        <!--begin: Pagination-->

                        {{ $posts->links('vendor.pagination.default3') }}


                        <!--end: Pagination-->
                    </div>
                </div>

                <!--end:: Components/Pagination/Default-->
            </div>
        </div>
    </div>
    <div class="modal fade- modal-sticky-bottom-right" id="kt_chat_modal" role="dialog"
         data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="kt-chat">
                    <div class="kt-portlet kt-portlet--last">
                        <div class="kt-portlet__head">
                            <div class="kt-chat__head ">
                                <div class="kt-chat__left">
                                    <div class="kt-chat__label">
                                        <a href="#" id="user_name" class="kt-chat__title"></a>
                                        <span class="kt-chat__status">
												<span id="user_status" class="kt-badge ">

                                                </span>

											</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-scroll kt-scroll--pull" data-height="410" data-mobile-height="300">
                                <div id="comment_pannel" class="kt-chat__messages kt-chat__messages--solid">


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content -->
@endsection

@push('css')
@endpush


@push('scripts')
    <script>

        $('.comments_show').on('click', function (e) {
            $('#kt_chat_modal').modal('hide');
            $('#kt_chat_modal #comment_pannel').text('');
            $('#kt_chat_modal #user_name').text('');
            $('#kt_chat_modal #user_status').text('');
            var dataId = $(this).attr("data-id");
            var url = '{{route('admin.postComment.show')}}';
            var csrf_token = '{{csrf_token()}}';
            $.ajax({
                url: url,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrf_token},
                data: {post_id: dataId, _token: csrf_token},
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {

                    var status_text = '';
                    var comment_texts = '';
                    if (response.post.owner.status == 1) {
                        status_text = '{{__('views.Active')}}';
                    } else if (response.post.owner.status == 2) {
                        status_text = '{{__('views.Not_Active')}}';
                    } else if (response.post.owner.status == 3) {
                        status_text = '{{__('views.Block')}}';
                    }
                    $('#kt_chat_modal #user_name').text(response.post.owner.first_name + ' ' + response.post.owner.last_name)
                    $('#kt_chat_modal #user_name').text(response.post.owner.first_name + ' ' + response.post.owner.last_name)
                    $('#kt_chat_modal #user_status').text(status_text)

                    if (response.comments.length > 0) {
                        for (var i = 0; i < response.comments.length; i++) {

                            if (response.comments[i].perant_id == null) {
                                comment_texts += '<div  class="kt-chat__message kt-chat__message--success">\
                                \  <span style="float: left" class="fa-align-right text-lg-right">\
                               <button  id="deleteComment' + response.comments[i].id +'" type="button" class="deleteComment" data-id="' + response.comments[i].id + '" >حذف</button>\
                                  </span>\
                                <div class="kt-chat__user">\
                        <span class="kt-media kt-media--circle kt-media--sm">\
                        <img src="' + response.comments[i].user.img + '" alt="image">\
                        </span>\
                        <a href="#" class="kt-chat__username">' + response.comments[i].user.first_name + '  ' + response.comments[i].user.last_name + ' </span></a>\
                        <span class="kt-chat__datetime">' + response.comments[i].id + '</span>\
                        </div>\
                        <div class="kt-chat__text">' + response.comments[i].text + '\
                        </div>';

                                if (response.comments[i].comments.length > 0) {
                                    for (var y = 0; y < response.comments[i].comments.length; y++) {


                                        comment_texts += '<div class="kt-chat__message kt-chat__message--right kt-chat__message--brand">\
                                            \ <span style="float: left" class="fa-align-right text-lg-right">\
                               <button  id="deleteComment' + response.comments[i].comments[y].id +'" type="button" class="deleteComment" data-id="' + response.comments[i].comments[y].id + '" >حذف</button>\
                                  </span>\
                                            <div class="kt-chat__user">\
                        <span class="kt-media kt-media--circle kt-media--sm">\
                        <img src="assets/media/users/100_12.jpg" alt="image">\
                        </span>\
                        <a href="#" class="kt-chat__username">' + response.comments[i].comments[y].user.first_name + '  ' + response.comments[i].comments[y].user.last_name + ' </span></a>\
                        <span class="kt-chat__datetime">' + response.comments[i].comments[y].id + '</span>\
                        </div>\
                        <div class="kt-chat__text">' + response.comments[i].comments[y].text + '\
                        </div>\
                        </div>';


                                    }

                                }


                            }

                            comment_texts += '</div>';
                        }

                    }

                    $('#kt_chat_modal #comment_pannel').append(comment_texts);

                    $('#kt_chat_modal').modal('show');

                    // $('#kt_chat_modal').modal('toggle');
                },
                error: function () {
                    $('#kt_chat_modal').modal('hide');
                    alert("error");
                }
            });


        });


        $('#generalSearch').on('keypress', function (e) {


            var search = $(this).val();

            if (search.length >= 4) {
                var base = '{!! route('admin.posts.index') !!}';

                var url = base + '?search=' + search;

                window.location.href = url;
                // var url = '{{ route("admin.posts.index", ":search") }}';
                url = url.replace(':search', search);

                window.location.href = url;
                alert(url);
                alert(search);
                return false;
                $(location).attr('href', url);
            }


        });

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
                        $('#deleteComment'+id).parent().parent().hide();
                        console.log(element.parent().parent());

                    } else {
                        alert(response.msg)
                    }


                },
                error: function () {
                    $('#kt_chat_modal').modal('hide');
                    alert("error");
                }
            });

        });

    </script>
@endpush
