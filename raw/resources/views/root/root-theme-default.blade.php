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
@section('head-meta')
    @parent
    <meta name="home" content="{!! url('/') !!}">
@endsection
@section('head-css-pre')
    @parent
    {{-- Generated App --}}
    <link rel="stylesheet" href="{{asset('/css/app.min.css')}}">
    {{-- CommonStyle --}}
    <link rel="stylesheet" href="{{asset('/css/common/common_style_default.min.css')}}">
@endsection

@section('body-js-lower-pre')
    @parent
    <script type="text/javascript">
        {!! 'var sessionFlashdata = ' . json_encode($flashdata)!!}
    </script>
    {{-- Generated App --}}
    <script type="text/javascript" src="{{asset('/js/app.min.js')}}"></script>
    {{-- CommonFunction --}}
    <script type="text/javascript" src="{{asset('/js/common/common_function_default.min.js')}}"></script>
@endsection
