<?php
$mainLink = ['link' => URL::route('dashboard'), 'title' => 'Go to A/B Testing Lab'];
?>
@extends('emails.master')

@section('title', 'Exception thrown')

@section('content')
<p>
Time: {{ date('Y-m-d H:i:s') }}
<br>
Url: {{ \Request::url() }}
</p>
@if(!is_null($user))
    <p>
    User:
    <?php var_dump($user->toArray()); ?>
    </p>
@endif

<table>
    {!! $debug !!}
</table>

@endsection