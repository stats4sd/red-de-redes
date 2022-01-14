
@extends('layouts.app')
@section('content')

<div class="container" id="app">
    <data-download-page></data-download-page>
</div>

@endsection

@section('after_scripts')
    <script src="js/data_download.js"></script>
@endsection
