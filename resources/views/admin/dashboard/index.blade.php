@extends('admin.layout.app')

@section('content')

    <!-- begin:: Content -->
    <div style="display: none" class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--Begin::Dashboard 1-->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <div class="row">
                <div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
                    <!--begin:: Widgets/Activity-->
                    <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                        <div class="kt-portlet__head kt-portlet__head--noborder kt-portlet__space-x">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    إحصائيات الطلبات والعروض
                                </h3>
                            </div>

                        </div>
                        <div class="kt-portlet__body kt-portlet__body--fit">
                            <div class="kt-widget17">
                                <div
                                        class="kt-widget17__visual kt-widget17__visual--chart kt-portlet-fit--top kt-portlet-fit--sides"
                                        style="background-color: #fd397a">
                                    <div class="kt-widget17__chart" style="height:320px;">
                                        <canvas id="kt_chart_activities"></canvas>
                                    </div>
                                </div>
                                <div class="kt-widget17__stats">
                                    <div class="kt-widget17__items">
                                        <div class="kt-widget17__item">
                                            <span class="kt-widget17__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                                     class="kt-svg-icon kt-svg-icon--brand">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path
                                                                d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                                                fill="#000000"/>
                                                        <rect fill="#000000" opacity="0.3"
                                                              transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) "
                                                              x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                                    </g>
                                                </svg>
                                            </span>

                                        </div>


                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Activity-->
                </div>




            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-12 order-lg-3 order-xl-1">
                    <!--begin:: Widgets/Best Sellers-->
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">

                        </div>
                        <div class="kt-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="kt_widget5_tab1_content" aria-expanded="true">
                                    <div class="kt-widget5">



                                    </div>


                                    <div class="container-paginator">


                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Best Sellers-->
                </div>


                <div class="col-xl-6 col-lg-6 order-lg-2 order-xl-1">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-widget14">
                            <div class="kt-widget14__header">
                                <h3 class="kt-widget14__title">
                                    {{__('views.most_offers')}}
                                </h3>
                                <span class="kt-widget14__desc">
                                    {{__('views.most_offers')}}
                                </span>
                            </div>
                            <div class="kt-widget14__content">
                                <div class="kt-widget14__chart">
                                    <div class="kt-widget14__stat">10</div>
                                    <canvas id="kt_chart_profit_share" style="height: 140px; width: 140px;"></canvas>
                                </div>
                                <div class="kt-widget14__legends">



                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>

                <div class="col-xl-6 col-lg-6 order-lg-2 order-xl-1">
                    <!--begin:: Widgets/Revenue Change-->
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-widget14">
                            <div class="kt-widget14__header">
                                <h3 class="kt-widget14__title">
                                    {{__('views.dont_have_offer')}}
                                </h3>
                                <span class="kt-widget14__desc">
                                    {{__('views.dont_have_offer')}}
                                </span>
                            </div>
                            <div class="kt-widget14__content">
                                <div class="kt-widget14__chart">

                                    <div id="kt_chart_revenue_change" style="height: 150px; width: 150px;"></div>
                                    <div class="kt-widget14__stat">20</div>
                                </div>
                                <div class="kt-widget14__legends">



                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Revenue Change-->
                </div>
            </div>
        </div><!-- /.content-wrapper -->


        <!--End::Dashboard 1-->
    </div>
    <!-- end:: Content -->

@endsection

@push('css')

@endpush


@push('scripts')

    <!-- Morris -->



    <script>


    </script>
@endpush
