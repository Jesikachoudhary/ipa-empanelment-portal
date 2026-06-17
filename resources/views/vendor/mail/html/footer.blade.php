<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center" style="font-size:12px;color:#6b7280;">
@php
	$year = date('Y');
	$appName = config('app.name');
	$customFooter = config('app.email_footer');
@endphp

@if(! empty($customFooter))
	{!! Illuminate\Mail\Markdown::parse($customFooter) !!}
@else
	&copy; {{ $year }} {{ $appName }}. All rights reserved.
@endif

</td>
</tr>
</table>
</td>
</tr>
