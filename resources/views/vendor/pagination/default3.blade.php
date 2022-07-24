
@if ($paginator->hasPages())
<div class="kt-pagination kt-pagination--brand">
    <ul class="kt-pagination__links">
        @if ($paginator->onFirstPage())
        <li class="kt-pagination__link--first">
            <a ><i class="fa fa-angle-double-left kt-font-brand"></i></a>
        </li>

            @else
            <li class="kt-pagination__link--next">
                <a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left kt-font-brand"></i></a>
            </li>
            @endif


            @foreach ($elements as $element)

                @if (is_string($element))
                    <li disabled="true" class="kt-pagination__link--prev">
                        <a >$element<i class="fa fa-angle-right kt-font-brand"></i></a>
                    </li>

                @endif



                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())


                                <li class="kt-pagination__link--active">
                                    <a >{{ $page }}</a>
                                </li>
                        @else


                                <li>
                                    <a href="{{ $url }}">{{ $page }}</a>
                                </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())

                <li class="kt-pagination__link--prev">
                    <a href="{{ $paginator->nextPageUrl() }}"><i class="fa fa-angle-right kt-font-brand"></i></a>
                </li>
            @else


                <li  class="kt-pagination__link--last">
                    <a ><i class="fa fa-angle-double-right kt-font-brand"></i></a>
                </li>
            @endif


    </ul>

</div>
@endif
