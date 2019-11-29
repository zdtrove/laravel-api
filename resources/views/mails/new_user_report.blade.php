<table>
    <tbody>
    @foreach($data['profiles'] as $profile)
        <tr>
            <td>
                {{date('Y.m.d', strtotime($profile['created_at']))}}
            <td>
            <td style="padding: 0 5px">
                <img src="{{$profile['image_url']}}" alt="" width="20" height="20"/></td>
            <td>
                <a href="{{url('/archive/profile/' . $profile['id'])}}">{{$profile['name_kanji']}}
                    @if($profile['occupation'])（{{$profile['occupation']}}）@endif</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>