@if ($data['admin_name_kanji'] && $data['admin_email'])
    GUILD CREATIONを通じて、{{$data['admin_name_kanji']}}さんからお仕事の相談がありました。<br>
    内容をご確認の上、ご返信ください。<br>
    ※ このメールには直接返信できません。返信は、メール末尾「発注したユーザーの連絡先」までお願いいたします。<br>
    -----------------<br>
    件名：<br>
    {{$data['subject']}}<br>
    <br>
    内容：<br>
    {{$data['body']}}<br>
    <br>
    発注されたユーザー：{{$data['profile_name_kanji']}}<br>
    発注されたユーザーの連絡先 : {{$data['profile_email']}}<br>
    <br>
    発注したユーザー：{{$data['admin_name_kanji']}}<br>
    発注したユーザーの連絡先 : {{$data['admin_email']}}<br>
    ※ このメールには直接返信できません。返信は上記の連絡先までお願いいたします。
@else
    {{$data['body']}}<br>
    <br>
    発注されたユーザー：{{$data['profile_name_kanji']}}<br>
    発注されたユーザーの連絡先 : {{$data['profile_email']}}<br>
@endif