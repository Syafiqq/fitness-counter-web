@extends('root.root-theme-default')
@section('body-js-lower-post')
    @parent
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-app.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/firebase/firebase-auth.min.js')}}"></script>
    @stack('additional-firebase-library')
    <script type="text/javascript" src="{{asset('/js/model/firebase/PathMapper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/model/firebase/DataMapper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/model/firebase/PojsoMapper.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/model/firebase/CommonModel.min.js')}}"></script>
    <script type="text/javascript">
        firebase.initializeApp({
            apiKey: "AIzaSyD_xXi_xZo25ASGgFODWv9av5lLLPHRWeg",
            authDomain: "fitness-counter-research.firebaseapp.com",
            databaseURL: "https://fitness-counter-research.firebaseio.com",
            projectId: "fitness-counter-research",
            storageBucket: "fitness-counter-research.appspot.com",
            messagingSenderId: "691550840999"
        });
    </script>
@endsection
