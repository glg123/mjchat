@extends('admin.layout.app')

@section('content')
    <!-- begin:: Content -->
    <div class="kt-container   kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Portlet-->

        <div class="kt-portlet kt-portlet--mobile">

            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-form kt-form--label-align-right kt-margin-t-20 collapse"
                     id="kt_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-2 m--align-left">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-6">
                                    <div class="m-form__group m-form__group--inline">
                                        <div class="m-form__label">
                                            <label>{{__('views.status')}}:</label>
                                        </div>
                                        <div class="m-form__control">
                                            <select style="opacity: 1 !important;"
                                                    class="form-control m-bootstrap-select" id="m_form_status">
                                                <option value="no">{{__('views.All')}}</option>
                                                <option value="active">{{__('views.Active')}}</option>
                                                <option value="closed">{{__('views.Close')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">


                            <a style=""
                               class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill event"
                               id="active">
                                      <span>

                                        <span>{{__('views.Active')}}</span>
                                    </span>
                            </a>
                            <a style=""
                               class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill event"
                               id="closed">
                                      <span>

                                        <span>{{__('views.Close')}}</span>
                                    </span>

                            </a>


                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                        </div>
                    </div>
                </div>
                <!--begin: Datatable -->
                <div class="kt-datatable" id="kt_category_list_datatable"></div>
                <!--end: Datatable -->
            </div>
        </div>
        <!--end::Portlet-->
    </div>
    <div class="modal fade" id="users" tabindex="-1" role="dialog" aria-labelledby="users" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sharemodel">{{__('ملف المستخدم')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                </div>
            </div>
        </div>
    </div>
    <div id="kt_modal_fetch_id_server" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="kt_modal_fetch_id_server" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <h4 class="modal-title">{{__('views.delete')}}</h4>
                </div>
                <div class="modal-body">
                    <p>{{__('views.confirm')}} </p>
                    <div class="kt-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-height="200">
                        <ul class="kt-datatable_selected_ids"></ul>
                    </div>
                    <input type="hidden" value="" id="seleted_id">
                </div>
                <div class="modal-footer">
                    <button class="btn default" data-dismiss="modal"
                            aria-hidden="true">{{__('views.cancel')}}</button>
                    <a  id="delete_ids">
                        <button class="btn btn-danger">{{__('views.delete')}}</button>
                    </a>
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


        "use strict";
        var KTUserListDatatable = function () {

            // variables
            var datatable;

            // init
            var init = function () {
                if ($('#kt_category_list_datatable').length === 0) {
                    return;
                }
                $('#kt_datatable_check_all').on('click', function () {
                    // datatable.setActiveAll(true);
                    $('.kt-datatable').KTDatatable('setActiveAll', true);
                });

                // init the datatables. Learn more: https://keenthemes.com/metronic/?page=docs&section=datatable
                datatable = $('#kt_category_list_datatable').KTDatatable({
                        // datasource definition

                        extensions: {
                            checkbox: {},
                        },
                        data: {
                            type: 'remote',
                            source: {
                                read: {
                                    url: '{{$index_url}}',
                                    params: {
                                        _token: '{{csrf_token()}}',
                                    },
                                },
                            },
                            pageSize: 10, // display 20 records per page
                            serverPaging: true,
                            serverFiltering: true,
                            serverSorting: true,
                            saveState: {
                                cookie: false,
                            },
                        },

                        // layout definition
                        layout: {
                            scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                            footer: false, // display/hide footer
                        },

                        // column sorting
                        sortable: true,

                        pagination: true,

                        search: {
                            input: $('#generalSearch'),
                            delay: 400,
                        },

                        rows: {
                            afterTemplate: function (element, data, rowNumber) {
                                element.find('#user-model').on('click', function (event) {
                                    var element = $(this);
                                    var id = element.attr('data-id');
                                    var url = '{{route('admin.orders.show',':id')}}';
                                    url = url.replace(':id', id);


                                    $.ajax({
                                        url: url,
                                        method: 'post',
                                        type: 'post',
                                        data: {
                                            _token: '{{csrf_token()}}',

                                        },
                                    })
                                        .done(function (data) {


                                            if (data.status == true) {
                                                $('#users .modal-body').html('');

                                                var str = '';

                                                // $.map(data.data, function (key,value) {

                                                str += '<div class="col-md-12 kt-margin-b-20-tablet-and-mobile">' +
                                                    '<div class="form-group">' +
                                                    '<label>اسم المكتب</label>' +
                                                    '<input readonly="" value="' + data.data.name + '" type="text" class="form-control" id="kt_datepicker_1" readonly="" >' +
                                                    '</div> </div>' +
                                                    '<div class="col-md-12 kt-margin-b-20-tablet-and-mobile">' +
                                                    '<div class="form-group">' +
                                                    '<label>البريد الإلكتروني</label>' +
                                                    '<input readonly="" value="' + data.data.email + '" type="text" class="form-control" id="kt_datepicker_1" readonly="" >' +
                                                    '</div> </div>' +
                                                    '<div class="col-md-12 kt-margin-b-20-tablet-and-mobile">' +
                                                    '<div class="form-group">' +
                                                    '<label>رقم الجوال</label>' +
                                                    '<input readonly="" value="' + data.data.mobile + '" type="text" class="form-control" id="kt_datepicker_1" readonly="" >' +
                                                    '</div>' +
                                                    ' </div>' +
                                                    '<div class="col-md-12 kt-margin-b-20-tablet-and-mobile">' +
                                                    '<div class="form-group">' +
                                                    '<label class="kt-checkbox">\n' +
                                                    '<input checked type="checkbox"> البروفايل مكتمل\n' +
                                                    '<span></span>\n' +
                                                    '</label>' +
                                                    '</div>' +
                                                    ' </div>';


                                                //   });


                                                $('#users .modal-body').append(str);

                                                $('#users').modal('toggle');

                                            } else if (data.status === 'cant_delete') {
                                                toastr.warning('@lang('cant_deleted')');
                                            } else {
                                                toastr.warning('@lang('not_deleted')');
                                            }

                                        })
                                        .fail(function () {
                                            toastr.error('@lang('something_wrong')');
                                        });


                                });
                            },
                        },

                        // columns definition
                        columns: [
                            {
                                field: 'id',
                                title: '#',
                                sortable: false,
                                width: 20,
                                selector: {
                                    class: 'kt-checkbox--solid'
                                },
                                textAlign: 'center',

                            },


                            {
                                field: 'user_name',
                                title: '{{__('views.full_name')}}',
                                sortable: false,
                                width: 100,
                                template: function (row, index, datatable) {
                                    var user_url = '{{route('admin.users.show',':id')}}'.replace(':id', row.user_id);
                                    return '<span ><a href="'+user_url+'" class="far fa-share-alt mr-1"></a>\n' + row.user_name +
                                        '                </span>';
                                },
                            },
                            {
                                field: 'post_name',
                                title: '{{__('views.post')}}',
                                sortable: false,
                                width: 100,
                                template: function (row, index, datatable) {
                                    var post_url = '{{route('admin.posts.show',':id')}}'.replace(':id', row.post_id);
                                    return '<span ><a href="'+post_url+'" class="far fa-share-alt mr-1"></a>\n' + row.post_name +
                                        '                </span>';
                                },
                            },

                            {
                                field: 'count_views',
                                title: '{{__('views.count_views')}}',
                                sortable: false,
                                width: 100,
                            },

                            {
                                field: 'remaining_views',
                                title: '{{__('views.remaining_views')}}',
                                sortable: false,
                                width: 100,

                            },

                            {
                                field: 'payer_id',
                                title: '{{__('views.payer_id')}}',
                                sortable: false,
                                width: 100,

                            },


                            {
                                field: 'created_at',
                                sortable: false,
                                title: '{{__('views.Date of Creation')}}',
                                template: function (row, index, datatable) {
                                    return moment(row.created_at).format("YYYY/MM/DD HH:mm");
                                },
                            },
                            {
                                field: "status",
                                title: "{{__('views.status')}}",
                                width: 100,
                                // callback function support for column rendering
                                template: function (row) {
                                    var status = {
                                        'active': {
                                            'title': '{{__('views.Active')}}',
                                            'class': 'm-badge--brand'
                                        },
                                        'closed': {
                                            'title': '{{__('views.Close')}}',
                                            'class': ' m-badge--danger'
                                        },

                                    };
                                    return '<span  id="label-' + row.id + '" class="m-badge ' + status[row.status].class + ' m-badge--wide">' + status[row.status].title + '</span>';
                                }
                            },

                            {
                                field: "Actions",
                                width: 80,
                                title: "{{__('views.Actions')}}",
                                sortable: false,
                                autoHide: false,
                                overflow: 'visible',
                                template: function (row) {

                                    var edit_url = '{{$edit_url}}'.replace('id', row.id);


                                    return '\
              <div class="dropdown">\
                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
                  <i class="flaticon-more-1"></i>\
                </a>\
                <div class="dropdown-menu dropdown-menu-right">\
                  <ul class="kt-nav">\
                    <li class="kt-nav__item">\
                      <a href="' + edit_url + '" class="kt-nav__link">\
                        <i class="kt-nav__link-icon flaticon2-contract"></i>\
                        <span class="kt-nav__link-text">{{__('views.show')}}</span>\
                      </a>\
                    </li>\
                  </ul>\
                </div>\
              </div>';

                                },
                            },

                        ],
                    }
                )
                ;
            };


            var filter = function () {


                $('#m_form_status').on('change', function () {
                    datatable.search($(this).val(), 'status');
                });


            };
            // selection
            var selection = function () {
                // init form controls
                //$('#kt_form_status, #kt_form_type').selectpicker();

                // event handler on check and uncheck on records
                datatable.on('kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated', function (e) {
                    var checkedNodes = datatable.rows('.kt-datatable__row--active').nodes(); // get selected records
                    var count = checkedNodes.length; // selected records count

                    $('#kt_subheader_group_selected_rows').html(count);

                    if (count > 0) {
                        $('#kt_subheader_search').addClass('kt-hidden');
                        $('#kt_subheader_group_actions').removeClass('kt-hidden');
                    } else {
                        $('#kt_subheader_search').removeClass('kt-hidden');
                        $('#kt_subheader_group_actions').addClass('kt-hidden');
                    }
                });
            };

            // selected records delete


            var updateTotal = function () {
                datatable.on('kt-datatable--on-layout-updated', function () {
                    $('#kt_subheader_total').html('{{__('views.:number Total', ['number' => 0])}}'.replace("0", datatable.getTotalRows()));
                });
            };


            var checkBoxCheck = function () {

                datatable.on(
                    'kt-datatable--on-click-checkbox kt-datatable--on-layout-updated',
                    function (e) {
                        // datatable.checkbox() access to extension methods
                        var ids = datatable.checkbox().getSelectedId();
                        var count = ids.length;
                        $('#kt_datatable_selected_number1').html(count);

                        var IDArray = [];
                        for (var i = 0; i < ids.length; i++) {
                            IDArray.push(ids);
                            console.log(ids);
                            $('#kt_datatable_group_action_form').collapse('show');
                        }

                        if (IDArray.length == 0) {
                            $('.event').attr('disabled', 'disabled');
                            $('#kt_datatable_group_action_form').collapse('hide');
                        } else {
                            //  $('.event').removeAttr('disabled');
                            //  $('#kt_datatable_group_action_form').collapse('hide');
                        }
                    });
            };
            $('.event').on('click', function (e) {
                var event = $(this).attr('id');

                var ids = datatable.checkbox().getSelectedId();
                var url = '{{route('admin.orders.status')}}';
                var csrf_token = '{{csrf_token()}}';
                var IDArray = [];
                for (var i = 0; i < ids.length; i++) {
                    IDArray.push(ids[i]);

                    $('#kt_datatable_group_action_form').collapse('show');
                }

                if (IDArray.length > 0) {


                    $.ajax({
                        url: url,
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        data: {event: event, IDArray: IDArray, _token: csrf_token},
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            if (response === 'active') {

                                $.each(IDArray, function (index, value) {
                                    $('#label-' + value).removeClass('m-badge--danger');
                                    $('#label-' + value).addClass('m-badge--brand');
                                    var r = '{{app()->getLocale()}}';
                                    if (r == 'ar') {
                                        $('#label-' + value).text('فعال ');
                                    } else {
                                        $('#label-' + value).text('active');

                                    }
                                });
                            } else if (response === 'closed') {

                                $.each(IDArray, function (index, value) {

                                    $('#label-' + value).removeClass('m-badge--brand');
                                    $('#label-' + value).addClass('m-badge--danger');
                                    var r = '{{app()->getLocale()}}';
                                    if (r == 'ar') {
                                        $('#label-' + value).text('مغلق');
                                    } else {
                                        $('#label-' + value).text('Close');

                                    }
                                });
                            }
                        },
                        error: function () {
                            alert("error");
                        }
                    });
                } else {
                    $('.event').removeAttr('disabled');
                    $('#kt_datatable_group_action_form').collapse('hide');
                }


            });

            $('#delete_ids').on('click', function (e) {


                var ids = datatable.checkbox().getSelectedId();
                var url = '{{route('admin.users.status')}}';
                var csrf_token = '{{csrf_token()}}';
                var IDArray = [];
                for (var i = 0; i < ids.length; i++) {
                    IDArray.push(ids[i]);

                    $('#kt_datatable_group_action_form').collapse('show');
                }

                if (IDArray.length > 0) {


                    $.ajax({
                        url: url,
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        data: {event: 'delete', IDArray: IDArray, _token: csrf_token},
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {
                            if (response === 'delete') {

                                $.each(IDArray, function (index, value) {


                                    $('#label-' + value).parent().parent().parent().hide(500);
                                    $('#kt_modal_fetch_id_server').modal('toggle');
                                });
                            }
                        },
                        error: function () {
                            alert("error");
                        }
                    });
                } else {
                    $('.event').removeAttr('disabled');
                    $('#kt_datatable_group_action_form').collapse('hide');
                }


            });


            var localSelectorDemo = function () {


                datatable.on(
                    'kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated',
                    function (e) {
                        var checkedNodes = datatable.rows('.kt-datatable__row--active').nodes();
                        var count = checkedNodes.length;
                        $('#kt_datatable_selected_number').html(count);
                        if (count > 0) {
                            $('#kt_datatable_group_action_form').collapse('show');
                        } else {
                            $('#kt_datatable_group_action_form').collapse('hide');
                        }
                    });

                $('#kt_modal_fetch_id').on('show.bs.modal', function (e) {
                    var ids = datatable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                        return $(chk).val();
                    });
                    var c = document.createDocumentFragment();
                    for (var i = 0; i < ids.length; i++) {
                        var li = document.createElement('li');
                        li.setAttribute('data-id', ids[i]);
                        li.innerHTML = 'Selected record ID: ' + ids[i];
                        c.appendChild(li);
                    }
                    $(e.target).find('.kt-datatable_selected_ids').append(c);
                }).on('hide.bs.modal', function (e) {
                    $(e.target).find('.kt-datatable_selected_ids').empty();
                });

            };
            return {
                // public functions
                init: function () {
                    $('#kt_modal_fetch_id_server').on('show.bs.modal', function (e) {

                        var ids = datatable.rows('.kt-datatable__row--active').nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function (i, chk) {
                            return $(chk).val();
                        });
                        var c = document.createDocumentFragment();
                        var seleted_id = '';
                        for (var i = 0; i < ids.length; i++) {

                            var li = document.createElement('li');
                            li.setAttribute('data-id', ids[i]);
                            li.innerHTML = '{{__('views.Selected record ID: ')}}' + ids[i];
                            if (i == ids.length - 1) {
                                seleted_id += ids[i];
                            } else {
                                seleted_id += ids[i] + ',';
                            }

                            c.appendChild(li);
                        }
                        $(e.target).find('.kt-datatable_selected_ids').append(c);
                        $(e.target).find('#seleted_id').val(seleted_id);
                    }).on('hide.bs.modal', function (e) {
                        $(e.target).find('.kt-datatable_selected_ids').empty();
                        $(e.target).find('#seleted_id').val('');
                    });
                    init();
                    selection();
                    filter();
                    checkBoxCheck();
                    localSelectorDemo();

                    updateTotal();
                },
            };
        }
        ();

        // On document ready
        KTUtil.ready(function () {
            KTUserListDatatable.init();
        });


        function delete_adv(id, e) {


            e.preventDefault();
            console.log(id);
            var url = '{{url("/admin/user/delete")}}/' + id;
            var csrf_token = '{{csrf_token()}}';
            $.ajax({
                type: 'delete',
                headers: {'X-CSRF-TOKEN': csrf_token},
                url: url,
                data: {_method: 'delete'},
                success: function (response) {
                    console.log(response);
                    if (response === 'success') {
                        $('#deleteButton' + id).parent().parent().parent().hide(500);

                        $('#myModal' + id).modal('toggle');
                        //swal("القضية حذفت!", {icon: "success"});
                    } else {
                        // swal('Error', {icon: "error"});
                    }
                },
                error: function (e) {
                    // swal('exception', {icon: "error"});
                }
            });

        }
    </script>
@endpush
