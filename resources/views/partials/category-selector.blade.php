@php
    $applicantTypes = config('applicant_types.types', []);
    $selectedType = $selectedType ?? session('applicant_type') ?? null;
    $existingApplicant = $existingApplicant ?? null;
    $currentFy = $currentFy ?? \App\Models\Applicant::getCurrentFinancialYear();
@endphp

<style>
    .category-selector-container {
        width: 100%;
    }

    .category-selector {
        display: flex;
        gap: 20px;
        padding: 20px 15px;
        max-width: 100%;
        margin: 0 auto;
        flex-wrap: wrap;
        justify-content: center;
        align-items: stretch;
    }

    .category-card-form {
        flex: 1;
        min-width: 280px;
        max-width: 350px;
        display: flex;
    }

    .category-card-wrapper {
        display: flex;
        width: 100%;
        flex-direction: column;
    }

    .category-card-btn {
        display: flex;
        width: 100%;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-direction: column;
    }

    .category-card {
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
    }

    .category-card-btn:hover .category-card {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .category-card-header {
        background-size: 100% 100%;
        color: white;
        padding: 25px 20px;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .category-icon {
        font-size: 45px;
        margin-bottom: 12px;
        display: block;
    }

    .category-title {
        font-weight: 700;
        margin-bottom: 8px;
        font-size: 18px;
        color: white;
    }

    .category-description {
        font-size: 13px;
        opacity: 0.95;
        line-height: 1.4;
        color: white;
    }

    .category-card-footer {
        padding: 15px;
        text-align: center;
        background: rgba(0, 0, 0, 0.05);
    }

    .category-btn {
        font-weight: 600;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .category-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .badge-applied {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .edit-button-small {
        background: #28a745;
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        margin-top: 8px;
        transition: all 0.3s ease;
    }

    .edit-button-small:hover {
        background: #218838;
        transform: scale(1.05);
    }

    @media (max-width: 1024px) {
        .category-selector {
            gap: 15px;
        }

        .category-card-form {
            min-width: 260px;
            max-width: 320px;
        }

        .category-icon {
            font-size: 40px;
        }

        .category-title {
            font-size: 17px;
        }

        .category-description {
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .category-selector {
            flex-direction: column;
            gap: 15px;
        }

        .category-card-form {
            min-width: 100%;
            max-width: 100%;
        }

        .category-icon {
            font-size: 38px;
        }

        .category-title {
            font-size: 16px;
        }

        .category-description {
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .category-selector {
            gap: 12px;
            padding: 15px;
        }

        .category-card-form {
            min-width: 100%;
        }

        .category-card-header {
            padding: 20px 15px;
        }

        .category-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .category-title {
            font-size: 15px;
            margin-bottom: 6px;
        }

        .category-description {
            font-size: 11px;
        }

        .category-btn {
            padding: 8px 16px;
            font-size: 13px;
        }
    }
</style>

<div class="category-selector-container">
    <div class="category-selector">
        @foreach($applicantTypes as $typeKey => $typeInfo)
            @php
                $hasExisting = isset($existingApplicant[$typeKey]);
                $existingApp = $existingApplicant[$typeKey] ?? null;
            @endphp
            <form method="POST" action="{{ route('admin.applicants.store-category') }}" class="category-card-form">
                @csrf
                <input type="hidden" name="type" value="{{ $typeKey }}">
                <div class="category-card-wrapper">
                    <div class="category-card">
                        <div class="category-card-header" style="background: linear-gradient(135deg, {{ $typeInfo['color'] }} 0%, {{ $typeInfo['color'] }}cc 100%);">
                            <span class="category-icon">{{ $typeInfo['icon'] }}</span>
                            <h5 class="category-title">{{ $typeInfo['label'] }}</h5>
                            <p class="category-description">{{ $typeInfo['description'] }}</p>
                        </div>
                        <div class="category-card-footer">
                            @if($hasExisting)
                                <span class="status-badge badge-applied">✓ Applied for {{ $existingApp->application_fy ?? $currentFy }}</span>
                              <br/>  <a href="{{ route('admin.applicants.edit', $existingApp) }}" class="edit-button-small">View/Edit Application</a>
                            @else
                                <button type="submit" class="category-btn">Click to Apply for {{ $currentFy }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        @endforeach
    </div>
</div>
