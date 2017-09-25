@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <board game="{{ $game }}"></board>
        </div>
    </div>
</div>
@endsection
