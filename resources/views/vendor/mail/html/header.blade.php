@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('img/tp_logo_small.png') }}" class="logo app-logo" alt="Talk and Play Marketplace Logo">
            <br>
            {{ $slot }}
        </a>
    </td>
</tr>
