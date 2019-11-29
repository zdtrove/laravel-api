@extends('mails.layouts.app')

@section('title', $mailDataObject->subject)

@section('content')
<p>Xin chào <b> {{$mailDataObject->name}} </b>, <b></b></p>

<p>
   Cám ơn bạn đã đăng ký tài khoản trên hệ thống đặt hàng, vui lòng click vào <a href="{{$mailDataObject->urlActive}}"><b> link này </b></a> để kích hoạt tài khoản.
</p>
@endsection