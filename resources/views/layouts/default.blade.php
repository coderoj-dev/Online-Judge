<!DOCTYPE doctype html>
<html>
    <head>
        <!-- App Meta -->
        <meta charset="utf-8">
            <meta content="width=device-width,initial-scale=1,maximum-scale=1" name="viewport">
                <meta content="{{ csrf_token() }}" name="csrf-token"/>
                <meta content="{{ config('app.name') }}" name="app-name"/>
                <meta content="{{ base64_encode(env('PUSHER_APP_KEY')) }}" name="PAK"/>
                <meta content="{{ $layoutKey }}" name="layout-key"/>
                <link href="{{asset("assets/img/favicon.png")}}" rel="icon" sizes="16x16" type="image/gif">
                    <title>
                        @yield('title') - {{ config('app.name') }}
                    </title>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js">
                    </script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML" type="text/javascript">
                    </script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js" type="text/javascript">
                    </script>
                    
                        <script src="{{ asset('lib/ckeditor/4.13.1/ckeditor.js') }}">
                        </script>
                        <script src="https://js.pusher.com/7.0/pusher.min.js">
                        </script>
                        <script src="{{ mix('js/app.js') }}" type="text/javascript">
                        </script>
                        <script language="JavaScript" src="{{ asset('lib/datatable/jquery.dataTables.min.js') }}" type="text/javascript"></script>


                        <script language="JavaScript" src="{{ asset('lib/datatable/dataTables.bootstrap.js') }}"></script>

                        <link rel="stylesheet" type="text/css" href="{{ asset('lib/datatable/dataTables.bootstrap.css') }}">

                        <link href="{{ asset('flag/css/flag-icon.css') }}" rel="stylesheet">
                            @include('includes.head')
                        </link>
                        
                    </link>
                </link>
            </meta>
        </meta>
    </head>
    <body>
        <!-- <div id="pre-loader">@include('includes.preload')</div> -->
        <div id="body-area">
            <div id="modal-area">
                @include('includes.modal')
            </div>
            <div class="container-fluid content-body">
                <div id="app-body">
                    @include('includes.header')
                    <div class="container">
                        @yield('content')
                    </div>
                    @include('includes.footer')
                </div>
            </div>
        </div>
    </body>
</html>
