@extends('master')

@section('title', 'Success!')
@section('breadcrumbs', Breadcrumbs::render('website', $website))

@section('content')

<p>
    Now you have to add the JS code below to the &lt;head&gt; tag of your website.
    This code should be loaded in every page where you want to conduct tests.
</p>
<p>
<h3>Code</h3>
<p>
    {!! $website->jsCodeTextarea() !!}
<br>
<a href="{{ URL::route('websites.show', [$website->id]) }}" class="btn btn-primary">Done</a>
<p>
    <h4>OR send the instructions to your developer</h4>
    <form role="form" method="POST" action="{{ route('websites.send_instructions', [$website->id]) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
        <div class="col-md-6">
        <div class="form-group">
            <label for="email">Developer's email</label>
            <input name="email" id="email" type="text" class="form-control" value="{{ old('email', '') }}" placeholder="">
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
            <label for="title">Developer's name</label>
            <input name="name" id="name" type="text" class="form-control" value="{{ old('name', '') }}">
        </div>
        </div>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>

</p>
<p>

</p>


@endsection