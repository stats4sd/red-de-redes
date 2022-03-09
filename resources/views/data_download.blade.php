
@extends('layouts.app')
@section('content')

<div class="container" id="app">
    <data-download-page></data-download-page>
</div>

@endsection

@section('javascript')

    <!-- to include Axios CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>

    <script src="js/data_download.js"></script>
    
@endsection
