@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    Hi {{ Auth::user()->name }}, you are logged in!<br /><br />
                    Your userid is: <strong>{{ Auth::user()->id }}</strong><br />
                    Email is: <strong>{{ Auth::user()->email }}</strong><br />
                    Email status is:
                        @if( Auth::user()->verified == true)
                            <span class="label label-success btn-xs" title="All good... cheers! ;)">Verified</span>
                        @else
                            <span class="label label-danger btn-xs" title="Check your inbox for verification link! :P">Unverified</span>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
