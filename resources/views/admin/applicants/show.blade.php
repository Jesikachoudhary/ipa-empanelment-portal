@extends('layouts.admin_inner')

@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>Applicant Details</h2>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="body">
            <h4>{{ $applicant->name ?? '—' }}</h4>
            <p><strong>Email:</strong> {{ $applicant->email }}</p>
            <p><strong>Contact:</strong> {{ $applicant->contact_number }}</p>
            <p><strong>Address:</strong> {{ $applicant->address }}</p>
            <p><strong>Domain Knowledge:</strong> {!! nl2br(e($applicant->domain_knowledge)) !!}</p>

            <h5>Education</h5>
            <ul>
                @foreach($applicant->educations as $edu)
                    <li>{{ $edu->qualification }} @if($edu->institution) - {{ $edu->institution }} @endif @if($edu->year_of_passing) ({{ $edu->year_of_passing }}) @endif</li>
                @endforeach
            </ul>

            <h5>Experience</h5>
            <ul>
                @foreach($applicant->experiences as $exp)
                    <li>{{ $exp->organization }} @if($exp->role) - {{ $exp->role }} @endif @if($exp->from_year || $exp->to_year) ({{ $exp->from_year }} - {{ $exp->to_year }}) @endif
                        @if($exp->details)<div>{{ $exp->details }}</div>@endif
                    </li>
                @endforeach
            </ul>

            @if($applicant->categories && count($applicant->categories) > 0)
                <h5>Areas & Subcategories</h5>
                @php
                    $categories = config('coe_categories');
                    $selected = $applicant->categories;
                @endphp
                <div>
                    @foreach($categories as $mainKey => $mainCat)
                        @php
                            $mainSelected = in_array($mainKey, $selected);
                            $subSelected = array_filter($selected, function($item) use ($mainKey) { return strpos($item, $mainKey.':') === 0; });
                        @endphp
                        @if($mainSelected || count($subSelected) > 0)
                            <div style="margin-bottom: 15px;">
                                <strong>{{ $mainCat['label'] }}</strong>
                                @if(count($subSelected) > 0)
                                    <ul style="margin-top: 5px; margin-bottom: 5px;">
                                        @foreach($subSelected as $subItem)
                                            @php
                                                $subKey = str_replace($mainKey.':', '', $subItem);
                                                $subLabel = $mainCat['subs'][$subKey] ?? $subKey;
                                            @endphp
                                            <li>{{ $subLabel }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($applicant->resume_path)
                <a href="{{ asset('storage/'.$applicant->resume_path) }}" class="btn btn-sm btn-primary" target="_blank">Download Resume</a>
            @endif

            <a href="{{ route('admin.applicants.edit', $applicant) }}" class="btn btn-sm btn-warning">Edit</a>
        </div>
    </div>
</div>

@endsection
