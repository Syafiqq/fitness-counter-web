@extends('root.root-boilerplate')
<?php
/** @var \Illuminate\Session\Store $session */
$session = \Illuminate\Support\Facades\Session::getFacadeRoot();
$flashdata = ['notify' => []];
if (isset($errors))
{
    $flashdata['notify'] = array_merge($flashdata['notify'], $errors->all());
}
if (!is_null($session->get('cbk_msg')))
{
    $flashdata = array_merge($flashdata, $session->get('cbk_msg'));
}
?>

@section('head-css-pre')
    @parent
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{asset('/vendor/font-awesome/css/font-awesome.min.css')}}">
    {{-- Ionicons --}}
    <link rel="stylesheet" href="{{asset('/vendor/ionicons/dist/css/ionicons.min.css')}}">
    {{-- Toastr --}}
    <link rel="stylesheet" href="{{asset('/vendor/toastr/build/toastr.min.css')}}">
    {{-- NProgress --}}
    <link rel="stylesheet" href="{{asset('/vendor/nprogress/nprogress.min.css')}}">
    {{-- CommonStyle --}}
    <link rel="stylesheet" href="{{asset('/css/common/common_style_default.min.css')}}">
@endsection

@section('body-js-lower-pre')
    @parent
    <script type="text/javascript">
        {!! 'var sessionFlashdata = ' . json_encode($flashdata)!!}
    </script>
    {{-- FastClick --}}
    <script type="text/javascript" src="{{asset('/vendor/fastclick/lib/fastclick.min.js')}}"></script>
    {{-- HTML5Shiv --}}
    <script type="text/javascript" src="{{asset('/vendor/html5shiv/dist/html5shiv.min.js')}}"></script>
    {{-- RespondJS --}}
    <script type="text/javascript" src="{{asset('/vendor/respond.js/dest/respond.min.js')}}"></script>
    {{-- Toastr --}}
    <script type="text/javascript" src="{{asset('/vendor/toastr/build/toastr.min.js')}}"></script>
    {{-- NProgress --}}
    <script type="text/javascript" src="{{asset('/vendor/nprogress/nprogress.min.js')}}"></script>
    {{-- CommonFunction --}}
    <script type="text/javascript" src="{{asset('/js/common/common_function_default.min.js')}}"></script>
@endsection
