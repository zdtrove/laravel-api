@extends('mails.layouts.app')

@section('title', $mailDataObject->subject)

@section('content')
<p>Xin chào <b> {{$mailDataObject->name}} </b>, <b></b></p>

<p>
   Vui lòng click vào <a href="{{$mailDataObject->urlActive}}"><b> link này </b></a> để cập nhật mật khẩu.
</p>
@endsection