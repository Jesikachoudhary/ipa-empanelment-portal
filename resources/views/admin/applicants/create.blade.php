@extends('layouts.admin_inner')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: white !important; padding: 30px 15px !important; margin: -20px -20px 20px -20px !important; position: relative !important; z-index: 10 !important; display: block !important;">
    <div class="container-fluid">
        <h2 style="color: white !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; font-size: 28px !important; display: block !important;">Expression of Interest for Center of Excellence at IPA</h2>
        <!--<h5 style="color: rgba(255, 255, 255, 0.8) !important; margin: 0 !important; display: block !important;">Applicant Form</h5>-->
    </div>
</div>

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            @if(session('applicant'))
                &nbsp; <a href="{{ route('admin.applicants.show', session('applicant')) }}" class="btn btn-sm btn-light">View</a>
            @endif
        </div>
    @endif

    <form action="{{ route('admin.applicants.store') }}" method="POST" enctype="multipart/form-data" id="applicant-form">
        @csrf
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Full Name <span class="text-danger">*</span></label>
                        @php $admin = auth('admin')->user(); @endphp
                        <div class="form-group">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name ?? '') }}" >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Address <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Email <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email ?? '') }}" >
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <div class="form-group"><input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number') }}">
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            Resume (Optional)
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 2 MB"></i>
                        </label>
                        <div class="form-group"><input type="file" name="resume" class="form-control @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx"></div>
                        @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        
                        <label>
                            Additional Document (Optional)
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 5 MB"></i>
                        </label>
                        <div class="form-group"><input type="file" name="additional_document" class="form-control @error('additional_document') is-invalid @enderror" accept=".pdf,.doc,.docx"></div>
                        @error('additional_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr>
                <h5>Educational Qualifications <span class="text-danger">*</span></h5>
                <div id="educations">
                    @php $oldEducations = old('educations', [['qualification'=>'','institution'=>'','year'=>'']]); @endphp
                    @foreach($oldEducations as $i => $eduRow)
                        <div class="education-row row mb-2" data-index="{{ $i }}">
                            <div class="col-md-5">
                                <input type="text" name="educations[{{ $i }}][qualification]" placeholder="Qualification" class="form-control @if($errors->has('educations.'.$i.'.qualification')) is-invalid @endif" value="{{ $eduRow['qualification'] ?? '' }}">
                                @if($errors->has('educations.'.$i.'.qualification'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.qualification') }}</div>@endif
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="educations[{{ $i }}][institution]" placeholder="Institution" class="form-control @if($errors->has('educations.'.$i.'.institution')) is-invalid @endif" value="{{ $eduRow['institution'] ?? '' }}">
                                @if($errors->has('educations.'.$i.'.institution'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.institution') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="educations[{{ $i }}][year]" class="form-control ms @if($errors->has('educations.'.$i.'.year')) is-invalid @endif">
                                    <option value="">Passing Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($eduRow['year']) && $eduRow['year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('educations.'.$i.'.year'))<div class="invalid-feedback">{{ $errors->first('educations.'.$i.'.year') }}</div>@endif
                            </div>
                            <div class="col-md-2 remove-btn-col" style="display: @if($i == 0) none @else inline-block @endif;">
                                <button type="button" class="btn btn-sm btn-danger remove-education" title="Remove this education"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-education" class="btn btn-sm btn-primary">Add Education</button>

                <hr>
                <h5>Professional Experience</h5>
                <div id="experiences">
                    @php $oldExperiences = old('experiences', [['organization'=>'','role'=>'','from_year'=>'','to_year'=>'','details'=>'']]); @endphp
                    @foreach($oldExperiences as $i => $expRow)
                        <div class="experience-row row mb-2" data-index="{{ $i }}">
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $i }}][organization]" placeholder="Organization" class="form-control @if($errors->has('experiences.'.$i.'.organization')) is-invalid @endif" value="{{ $expRow['organization'] ?? '' }}">
                                @if($errors->has('experiences.'.$i.'.organization'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.organization') }}</div>@endif
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $i }}][role]" placeholder="Role/Designation" class="form-control @if($errors->has('experiences.'.$i.'.role')) is-invalid @endif" value="{{ $expRow['role'] ?? '' }}">
                                @if($errors->has('experiences.'.$i.'.role'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.role') }}</div>@endif
                            </div>
                            <div class="col-md-2 remove-btn-col" style="display: @if($i == 0) none @else inline-block @endif;">
                                <button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                            </div>
                        </div>
                        <div class="row mb-2 ms-2">
                            <div class="col-md-2">
                                <select name="experiences[{{ $i }}][from_year]" class="form-control ms from-year @if($errors->has('experiences.'.$i.'.from_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">From Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($expRow['from_year']) && $expRow['from_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$i.'.from_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.from_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $i }}][from_month]" class="form-control ms from-month @if($errors->has('experiences.'.$i.'.from_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($expRow['from_month']) && $expRow['from_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$i.'.from_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.from_month') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $i }}][to_year]" class="form-control ms to-year @if($errors->has('experiences.'.$i.'.to_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">To Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($expRow['to_year']) && $expRow['to_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$i.'.to_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.to_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $i }}][to_month]" class="form-control ms to-month @if($errors->has('experiences.'.$i.'.to_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($expRow['to_month']) && $expRow['to_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$i.'.to_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.to_month') }}</div>@endif
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="experiences[{{ $i }}][details]" placeholder="Key Projects / Details" class="form-control @if($errors->has('experiences.'.$i.'.details')) is-invalid @endif" value="{{ $expRow['details'] ?? '' }}">
                                @if($errors->has('experiences.'.$i.'.details'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$i.'.details') }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-experience" class="btn btn-sm btn-primary">Add Experience</button>

                <hr>
                <h5>Areas & Subcategories <span class="text-danger">*</span></h5>
                @php
                    $categories = config('coe_categories');
                    $selected = old('categories', []);
                @endphp
                <div class="form-group @error('categories') has-error @enderror">
                    @foreach($categories as $mainKey => $mainCat)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input main-cat @error('categories') is-invalid @enderror" id="cat_{{ $mainKey }}" name="categories[]" value="{{ $mainKey }}" @if(in_array($mainKey, $selected)) checked @endif>
                            <label class="form-check-label" for="cat_{{ $mainKey }}">
                                <strong>{{ $mainCat['label'] }}</strong>
                            </label>
                        </div>
                        <div class="subcats" id="subcats_{{ $mainKey }}" style="display: @if(in_array($mainKey, $selected)) block @else none @endif; margin-left: 20px; margin-bottom: 10px;">
                            @foreach($mainCat['subs'] as $subKey => $subLabel)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input sub-cat" id="cat_{{ $mainKey }}_{{ $subKey }}" name="categories[]" value="{{ $mainKey }}:{{ $subKey }}" @if(in_array($mainKey.':'.$subKey, $selected)) checked @endif>
                                    <label class="form-check-label" for="cat_{{ $mainKey }}_{{ $subKey }}">
                                        {{ $subLabel }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @error('categories')
                        <div class="invalid-feedback" style="display: block; color: #dc3545; margin-top: 10px;">
                            <strong>⚠️ {{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script>
    (function(){
        console.log('Form script loaded');
        const phoneField = document.querySelector('input[name="contact_number"]');
        const phoneRegex = /^[0-9\+\-\s\(\)]{10,}$/;

        function showFieldError(field, msg) {
            field.classList.add('is-invalid');
            let fb = field.parentNode.querySelector('.invalid-feedback');
            if (!fb) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                field.parentNode.appendChild(fb);
            }
            fb.textContent = msg;
        }
        function clearFieldError(field) {
            field.classList.remove('is-invalid');
            let fb = field.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.textContent = '';
        }

        // Main category toggle
        document.querySelectorAll('.main-cat').forEach(function(mainCheckbox){
            mainCheckbox.addEventListener('change', function(){
                const mainKey = this.value;
                const subcatsDiv = document.getElementById('subcats_' + mainKey);
                if (subcatsDiv) {
                    subcatsDiv.style.display = this.checked ? 'block' : 'none';
                }
            });
        });

        // Subcategory auto-check parent
        document.querySelectorAll('.sub-cat').forEach(function(subCheckbox){
            subCheckbox.addEventListener('change', function(){
                if (this.checked) {
                    const mainKey = this.value.split(':')[0];
                    const mainCheckbox = document.getElementById('cat_' + mainKey);
                    if (mainCheckbox && !mainCheckbox.checked) {
                        mainCheckbox.checked = true;
                        mainCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        });

        // Add Education
        document.getElementById('add-education').addEventListener('click', function(){
            const container = document.getElementById('educations');
            const idx = container.querySelectorAll('.education-row').length;
            
            // Generate year options
            let yearOptions = '<option value="">Passing Year</option>';
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1960; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
            }
            
            const html = `
                <div class="education-row row mb-2" data-index="${idx}">
                    <div class="col-md-5">
                        <input type="text" name="educations[${idx}][qualification]" placeholder="Qualification" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="educations[${idx}][institution]" placeholder="Institution" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <select name="educations[${idx}][year]" class="form-control ms">
                            ${yearOptions}
                        </select>
                    </div>
                    <div class="col-md-2 remove-btn-col">
                        <button type="button" class="btn btn-sm btn-danger remove-education" title="Remove this education"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            // Attach remove handler to new button
            container.querySelector('.remove-education:last-of-type').addEventListener('click', removeEducationRow);
        });

        // Add Experience
        document.getElementById('add-experience').addEventListener('click', function(){
            const container = document.getElementById('experiences');
            const idx = container.querySelectorAll('.experience-row').length;
            
            // Generate year options
            let yearOptions = '<option value="">Year</option>';
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1960; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
            }
            
            // Generate month options
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            let monthOptions = '<option value="">Month</option>';
            months.forEach((month, i) => {
                monthOptions += `<option value="${i + 1}">${month}</option>`;
            });
            
            const html = `
                <div class="experience-row row mb-2" data-index="${idx}">
                    <div class="col-md-5">
                        <input type="text" name="experiences[${idx}][organization]" placeholder="Organization" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="experiences[${idx}][role]" placeholder="Role/Designation" class="form-control">
                    </div>
                    <div class="col-md-2 remove-btn-col">
                        <button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                    </div>
                </div>
                <div class="row mb-2 ms-2">
                    <div class="col-md-2">
                        <select name="experiences[${idx}][from_year]" class="form-control ms from-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;">
                            <option value="">From Year</option>
                            ${yearOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="experiences[${idx}][from_month]" class="form-control ms from-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">
                            ${monthOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="experiences[${idx}][to_year]" class="form-control ms to-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;">
                            <option value="">To Year</option>
                            ${yearOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="experiences[${idx}][to_month]" class="form-control ms to-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">
                            ${monthOptions}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="experiences[${idx}][details]" placeholder="Key Projects / Details" class="form-control">
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            // Attach remove handler to new button
            container.querySelector('.remove-experience:last-of-type').addEventListener('click', removeExperienceRow);
            // Attach change handlers to newly added experience fields
            const newExperienceSelects = container.querySelectorAll('.experience-row:last-of-type + .row select.exp-change');
            newExperienceSelects.forEach(el => {
                el.addEventListener('change', calculateExperienceDuration);
            });
        });

        function validateYears() {
            let errors = [];
                document.querySelectorAll('#experiences .experience-row').forEach(function(row, idx){
                const from = row.querySelector('[name^="experiences"][name$="[from_year]"]');
                const to = row.querySelector('[name^="experiences"][name$="[to_year]"]');
                if (from && to && from.value && to.value) {
                    if (parseInt(from.value) > parseInt(to.value)) {
                        showFieldError(to, 'To Year must be >= From Year');
                        errors.push(to);
                    } else {
                        clearFieldError(to);
                    }
                }
            });
            return errors.length === 0;
        }

        document.getElementById('applicant-form').addEventListener('submit', function(e){
            console.log('Form submit event triggered');
            
            // Skip validation if this is a logout button or other non-form action
            if (e.submitter && e.submitter.dataset.noValidate) {
                console.log('Skipping validation for:', e.submitter.name || e.submitter.id);
                return true;
            }
            
            let valid = true;
            
            // Check if at least ONE main category is checked
            const mainCheckboxes = document.querySelectorAll('input[type="checkbox"].main-cat');
            let anyMainChecked = false;
            mainCheckboxes.forEach(function(cb){ if (cb.checked) anyMainChecked = true; });
            console.log('anyMainChecked:', anyMainChecked, 'Total mains:', mainCheckboxes.length);
            
            if (!anyMainChecked) {
                e.preventDefault();
                valid = false;
                const firstMain = document.querySelector('input[type="checkbox"].main-cat');
                if (firstMain) {
                    showFieldError(firstMain, '⚠️ Please select at least one main category');
                    firstMain.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
                console.log('BLOCKED: No main category selected');
                return false;
            }
            
            // Check if subcategories are selected for each checked main
            mainCheckboxes.forEach(function(mainCb){
                if (!mainCb.checked) return;
                const mainKey = mainCb.value;
                const subDiv = document.getElementById('subcats_' + mainKey);
                let hasSubCat = false;
                if (subDiv) {
                    const checked = subDiv.querySelectorAll('input[type="checkbox"].sub-cat:checked');
                    hasSubCat = checked.length > 0;
                }
                if (!hasSubCat) {
                    e.preventDefault();
                    valid = false;
                    showFieldError(mainCb, '⚠️ Please select at least one subcategory for: ' + mainCb.parentElement.textContent.trim());
                    mainCb.scrollIntoView({behavior: 'smooth', block: 'center'});
                    console.log('BLOCKED: No subcategory for', mainKey);
                }
            });
            
            // phone
            if (phoneField && !phoneRegex.test(phoneField.value)) {
                e.preventDefault();
                showFieldError(phoneField, '⚠️ Invalid phone number');
                valid = false;
            }
            // years
            if (!validateYears()) {
                e.preventDefault();
                valid = false;
            }

            if (!valid) {
                console.log('Form validation FAILED - preventing submission');
            }
            
            // Ensure first education qualification is present
            const firstQual = document.querySelector('#educations .education-row [name$="[qualification]"]');
            if (firstQual && firstQual.value.trim() === '') {
                e.preventDefault();
                showFieldError(firstQual, '⚠️ Qualification is required');
                valid = false;
            }

            // conditional completeness validation for educations: if any field in a row filled, require all
            document.querySelectorAll('#educations .education-row').forEach(function(row, idx){
                const q = row.querySelector('[name^="educations"][name$="[qualification]"]');
                const inst = row.querySelector('[name^="educations"][name$="[institution]"]');
                const yr = row.querySelector('[name^="educations"][name$="[year]"]');
                const any = (q && q.value.trim() !== '') || (inst && inst.value.trim() !== '') || (yr && yr.value.trim() !== '');
                if (any) {
                    if (q && q.value.trim() === '') { e.preventDefault(); showFieldError(q, '⚠️ Qualification is required'); valid = false; }
                    if (inst && inst.value.trim() === '') { e.preventDefault(); showFieldError(inst, '⚠️ Institution is required'); valid = false; }
                    if (yr && yr.value.trim() === '') { e.preventDefault(); showFieldError(yr, '⚠️ Year is required'); valid = false; }
                }
            });

            // conditional completeness validation for experiences
            document.querySelectorAll('#experiences .experience-row').forEach(function(row, idx){
                const org = row.querySelector('[name^="experiences"][name$="[organization]"]');
                const role = row.querySelector('[name^="experiences"][name$="[role]"]');
                const from = row.querySelector('[name^="experiences"][name$="[from_year]"]');
                const to = row.querySelector('[name^="experiences"][name$="[to_year]"]');
                const details = row.querySelector('input[name^="experiences"][name$="[details]"]') || row.querySelector('textarea[name^="experiences"][name$="[details]"]');
                const any = (org && org.value.trim() !== '') || (role && role.value.trim() !== '') || (from && from.value.trim() !== '') || (to && to.value.trim() !== '') || (details && details.value.trim() !== '');
                if (any) {
                    if (org && org.value.trim() === '') { e.preventDefault(); showFieldError(org, '⚠️ Organization is required'); valid = false; }
                    if (role && role.value.trim() === '') { e.preventDefault(); showFieldError(role, '⚠️ Role is required'); valid = false; }
                    if (from && from.value.trim() === '') { e.preventDefault(); showFieldError(from, '⚠️ From Year is required'); valid = false; }
                    if (to && to.value.trim() === '') { e.preventDefault(); showFieldError(to, '⚠️ To Year is required'); valid = false; }
                }
            });

            if (!valid) {
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
                    firstInvalid.focus();
                }
            }
        });

        // Add placeholder validation for year fields (user will enter 4-digit years manually)
        // Year fields are type="text" to allow manual entry of any 4-digit year

        // focus first invalid field if present
        window.addEventListener('load', function(){
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
        });

        // Reinitialize pickers when adding new rows (no picker library needed)
        document.getElementById('add-education').addEventListener('click', function(){
            // Year fields will use browser's native input handling
        });

        document.getElementById('add-experience').addEventListener('click', function(){
            // Year fields will use browser's native input handling
        });

        // Remove education row
        document.addEventListener('click', function(e){
            if (e.target.closest('.remove-education')) {
                e.preventDefault();
                const row = e.target.closest('.education-row');
                if (row) {
                    row.remove();
                }
            }
        });

        // Remove experience row
        document.addEventListener('click', function(e){
            if (e.target.closest('.remove-experience')) {
                e.preventDefault();
                const row = e.target.closest('.experience-row');
                const nextRow = row.nextElementSibling;
                if (row) {
                    row.remove();
                    // Also remove the related year/details row if it exists
                    if (nextRow && nextRow.classList.contains('row') && nextRow.classList.contains('mb-2')) {
                        nextRow.remove();
                    }
                }
            }
        });

        // Calculate experience duration function
        function calculateExperienceDuration(e) {
            const row = e.target.closest('.row.mb-2');
            if (!row) return;
            
            const fromYearSelect = row.querySelector('select.from-year');
            const fromMonthSelect = row.querySelector('select.from-month');
            const toYearSelect = row.querySelector('select.to-year');
            const toMonthSelect = row.querySelector('select.to-month');
            const calcField = row.querySelector('input.exp-calc');
            
            if (!fromYearSelect || !toYearSelect || !calcField) return;
            
            const fromYear = parseInt(fromYearSelect.value) || 0;
            const fromMonth = parseInt(fromMonthSelect.value) || 1;
            const toYear = parseInt(toYearSelect.value) || 0;
            const toMonth = parseInt(toMonthSelect.value) || 12;
            
            if (!fromYear || !toYear) {
                calcField.value = '';
                return;
            }
            
            // Calculate total months difference
            const totalMonths = ((toYear - fromYear) * 12) + (toMonth - fromMonth);
            
            if (totalMonths < 0) {
                calcField.value = 'Invalid range';
                return;
            }
            
            // Convert to years and months
            const years = Math.floor(totalMonths / 12);
            const months = totalMonths % 12;
            
            if (years === 0) {
                calcField.value = months + 'M';
            } else if (months === 0) {
                calcField.value = years + 'Y';
            } else {
                calcField.value = years + 'Y ' + months + 'M';
            }
        }

        // Attach change listeners to all experience fields
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('exp-change')) {
                calculateExperienceDuration(e);
            }
        });

        // Calculate on page load
        window.addEventListener('load', function() {
            document.querySelectorAll('.row.mb-2 select.exp-change').forEach(select => {
                const event = new Event('change', { bubbles: true });
                select.dispatchEvent(event);
            });
        });

        // Defensive: remove any plugin-inserted search inputs that appear before our year <select class="ms">
        window.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('select.ms').forEach(function(sel){
                try {
                    const prev = sel.previousElementSibling;
                    if (!prev) return;
                    const isAnonTextInput = prev.tagName === 'INPUT' && prev.type === 'text' && !prev.name;
                    const isBsSearch = prev.classList && (prev.classList.contains('bs-searchbox') || prev.className.includes('select2'));
                    if (isAnonTextInput || isBsSearch) {
                        prev.remove();
                    }
                } catch(e) { /* noop */ }
            });
        });
    })();
    </script>
@endpush
