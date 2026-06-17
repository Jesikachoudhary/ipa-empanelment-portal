@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@php
	$appName = config('app.name');
	$logoConfig = config('app.logo');
	// Use absolute URL with public_path for email compatibility
	$defaultLogo = url('html/assets/logo.png');
	$logoUrl =  $defaultLogo;
@endphp

@if(! empty($logoUrl))
	<!--<img src="{{ $logoUrl }}" class="logo" alt="{{ $appName }} Logo" style="max-height:20px;">-->
@else
	<span style="font-size:16px;font-weight:600;color:#111">{{ $appName }}</span>
@endif

</a>
</td>
</tr>
