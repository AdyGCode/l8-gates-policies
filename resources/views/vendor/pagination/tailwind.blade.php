@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
         class="flex items-center justify-between">


        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="pt-1 text-sm text-gray-700 leading-5">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <spn class="relative z-0 inline-flex shadow-sm rounded-md">
                    @if ($paginator->onFirstPage())
                        <a href="" aria-disabled="true"
                           aria-label="{{ __('pagination.first') }}"
                           class="relative inline-flex items-center px-4 py-3 -mr-px
                            font-medium text-red-700 bg-white border border-gray-300
                            cursor-default rounded-l-md leading-5 ring-gray-300
                            transition-all duration-500 ease-in-out"
                           aria-hidden="true">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                        <a href="" aria-disabled="true"
                           aria-label="{{ __('pagination.previous') }}"
                           class="relative inline-flex items-center px-4 py-3 -mr-px
                            font-medium text-red-700 bg-white border border-gray-300
                            cursor-default leading-5
                            transition-all duration-500 ease-in-out" aria-hidden="true">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    @else
                        <a href="{{ \Request::url() }}" rel="first"
                           class="relative inline-flex items-center px-4 py-3 -mr-px
                           font-medium text-gray-500 bg-white border border-gray-300
                           rounded-l-md leading-5 hover:text-blue-100 hover:bg-gray-500 focus:z-10
                           focus:outline-none focus:ring ring-gray-300 focus:border-blue-300
                           active:bg-gray-200 active:text-gray-500 transition ease-in-out
                           duration-500" aria-label="{{ __('pagination.first') }}">
                            <i class="fa fa-angle-double-left"></i>
                        </a>

                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="relative inline-flex items-center px-4 py-3
                           font-medium text-gray-500 bg-white border border-gray-300
                           leading-5 hover:text-blue-100 hover:bg-gray-500 focus:z-10
                           focus:outline-none focus:ring ring-gray-300 focus:border-blue-300
                           active:bg-gray-200 active:text-gray-500 transition ease-in-out
                           duration-500" aria-label="{{ __('pagination.previous') }}">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-3 -ml-px
                                 font-medium text-gray-700 bg-white border
                                border-gray-300 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4
                                        py-3 -ml-px  font-semibold text-gray-50
                                        bg-blue-500 inset-y-1
                                        border border-blue-500 cursor-default leading-5">{{
                                        $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex
                                    items-center px-4  py-3 -ml-px  font-bold
                                    text-gray-700 bg-white border border-gray-300 leading-5
                                    ring-gray-300 transition ease-in-out duration-500
                                    hover:text-white hover:bg-gray-500
                                    focus:z-10 focus:outline-none focus:ring
                                    focus:border-blue-300
                                    active:bg-blue-200 active:text-blue-700 "
                                       aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                           rel="next"
                           class="relative
                        inline-flex items-center px-4 py-3 -ml-px font-medium
                        text-gray-700 bg-white border border-gray-300 leading-5
                        hover:text-blue-100 hover:bg-gray-500 focus:z-10 focus:outline-none
                        focus:ring ring-gray-300 focus:border-blue-300 active:bg-blue-200
                        active:text-gray-700 transition ease-in-out duration-500"
                           aria-label="{{ __('pagination.next') }}">
                            <i class="fa fa-angle-right"></i>
                        </a>

                        <a href="{{ \Request::url().'?page='.$paginator->lastPage() }}"
                           rel="last"
                           class="relative inline-flex items-center px-4 py-3 -ml-px
                           font-medium text-gray-700 bg-white border border-gray-300
                           rounded-r-md leading-5
                        hover:text-blue-100 hover:bg-gray-500 focus:z-10 focus:outline-none
                        focus:ring ring-gray-300 focus:border-blue-300 active:bg-blue-200
                        active:text-gray-700 transition-all ease-in-out duration-500"
                           aria-label="{{ __('pagination.last') }}">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    @else
                        <a aria-disabled="true"
                           aria-label="{{ __('pagination.next') }}"
                           class="relative inline-flex items-center px-4 py-3 -ml-px
                            font-medium text-red-700 bg-white border border-gray-300
                            cursor-default leading-5
                            transition-all duration-500 ease-in-out" aria-hidden="true">
                            <i class="fa fa-angle-right"></i>
                        </a>

                        <a aria-disabled="true"
                           aria-label="{{ __('pagination.last') }}"
                           class="relative inline-flex items-center px-4 py-3 -ml-px
                            font-medium text-red-700 bg-white border border-gray-300
                            cursor-default rounded-r-md leading-5
                            transition-all duration-500 ease-in-out" aria-hidden="true">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                @endif
            </div>
        </div>
    </nav>
@endif
