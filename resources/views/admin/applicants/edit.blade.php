@extends('layouts.admin_inner')

@section('content')
<div style="color: white !important; padding: 30px 15px !important; margin: -20px -20px 20px -20px !important; position: relative !important; z-index: 10 !important; display: block !important;">
    <div class="container-fluid">
        <h2 style="color: white !important; font-weight: 600 !important; margin: 0 0 10px 0 !important; font-size: 28px !important; display: block !important;">Expression of Interest for Center of Excellence at IPA</h2>
       <!-- <h5 style="color: rgba(255, 255, 255, 0.8) !important; margin: 0 !important; display: block !important;">Edit Applicant</h5>-->
    </div>
</div>

<div class="container-fluid">

    @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form id="applicant-form" action="{{ route('admin.applicants.update', $applicant) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $applicant->name) }}" >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Address <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $applicant->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Email <span class="text-danger">*</span></label>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $applicant->email) }}" >
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <div class="form-group"><input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number', $applicant->contact_number) }}">
                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            Resume (Optional)
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 2 MB"></i>
                        </label>
                        <div class="form-group"><input type="file" name="resume" class="form-control @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx"></div>
                        @if($applicant->resume_path)
                            <div><a href="{{ asset('storage/'.$applicant->resume_path) }}" target="_blank">Download current resume</a></div>
                        @endif
                        @error('resume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        
                        <label>
                            Additional Document (Optional)
                            <i class="zmdi zmdi-info-outline" style="font-size: 14px; color: #0066cc; cursor: help; margin-left: 5px;" title="Accepted formats: PDF, DOC, DOCX. Maximum file size: 5 MB"></i>
                        </label>
                        <div class="form-group"><input type="file" name="additional_document" class="form-control @error('additional_document') is-invalid @enderror" accept=".pdf,.doc,.docx"></div>
                        @if($applicant->additional_document_path)
                            <div><a href="{{ asset('storage/'.$applicant->additional_document_path) }}" target="_blank">Download current additional document</a></div>
                        @endif
                        @error('additional_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr>
                <h5>Educational Qualifications <span class="text-danger">*</span></h5>
                <div id="educations">
                    @php
                        $oldEducations = old('educations');
                        if (is_null($oldEducations)) {
                            $oldEducations = $applicant->educations->map(function($e){
                                return ['qualification'=>$e->qualification,'institution'=>$e->institution,'year'=>$e->year_of_passing];
                            })->toArray();
                        }
                        if (empty($oldEducations)) { $oldEducations = [['qualification'=>'','institution'=>'','year'=>'']]; }
                    @endphp
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
                                    <option value="">Year</option>
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
                <h5>Experience (Organisation-wise)</h5>
                <div id="experiences">
                    @php
                        $oldExps = old('experiences');
                        if (is_null($oldExps)) {
                            $oldExps = $applicant->experiences->map(function($e){
                                return ['organization'=>$e->organization,'role'=>$e->role,'from_year'=>$e->from_year,'from_month'=>$e->from_month,'to_year'=>$e->to_year,'to_month'=>$e->to_month,'details'=>$e->details];
                            })->toArray();
                        }
                        if (empty($oldExps)) { $oldExps = [['organization'=>'','role'=>'','from_year'=>'','from_month'=>'','to_year'=>'','to_month'=>'','details'=>'']]; }
                    @endphp
                    @foreach($oldExps as $j => $exp)
                        <div class="experience-row row mb-2" data-index="{{ $j }}">
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $j }}][organization]" placeholder="Organization" class="form-control @if($errors->has('experiences.'.$j.'.organization')) is-invalid @endif" value="{{ $exp['organization'] ?? '' }}">
                                @if($errors->has('experiences.'.$j.'.organization'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.organization') }}</div>@endif
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="experiences[{{ $j }}][role]" placeholder="Role" class="form-control @if($errors->has('experiences.'.$j.'.role')) is-invalid @endif" value="{{ $exp['role'] ?? '' }}">
                                @if($errors->has('experiences.'.$j.'.role'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.role') }}</div>@endif
                            </div>
                            <div class="col-md-2 remove-btn-col" style="display: @if($j == 0) none @else inline-block @endif;">
                                <button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][from_year]" class="form-control ms from-year @if($errors->has('experiences.'.$j.'.from_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">From Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($exp['from_year']) && $exp['from_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$j.'.from_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.from_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][from_month]" class="form-control ms from-month @if($errors->has('experiences.'.$j.'.from_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($exp['from_month']) && $exp['from_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$j.'.from_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.from_month') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][to_year]" class="form-control ms to-year @if($errors->has('experiences.'.$j.'.to_year')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">To Year</option>
                                    @for($y = date('Y'); $y >= 1960; $y--)
                                        <option value="{{ $y }}" @if(isset($exp['to_year']) && $exp['to_year'] == $y) selected @endif>{{ $y }}</option>
                                    @endfor
                                </select>
                                @if($errors->has('experiences.'.$j.'.to_year'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.to_year') }}</div>@endif
                            </div>
                            <div class="col-md-2">
                                <select name="experiences[{{ $j }}][to_month]" class="form-control ms to-month @if($errors->has('experiences.'.$j.'.to_month')) is-invalid @endif" style="font-size: 16px; padding: 10px; height: 45px;">
                                    <option value="">Month</option>
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $idx => $month)
                                        <option value="{{ $idx + 1 }}" @if(isset($exp['to_month']) && $exp['to_month'] == ($idx + 1)) selected @endif>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('experiences.'.$j.'.to_month'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.to_month') }}</div>@endif
                            </div>
                            <div class="col-md-4">
                                <textarea name="experiences[{{ $j }}][details]" placeholder="Details" class="form-control @if($errors->has('experiences.'.$j.'.details')) is-invalid @endif">{{ $exp['details'] ?? '' }}</textarea>
                                @if($errors->has('experiences.'.$j.'.details'))<div class="invalid-feedback">{{ $errors->first('experiences.'.$j.'.details') }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <button type="button" id="add-experience" class="btn btn-sm btn-primary">Add Experience</button>

                <hr>
                <h5>Areas & Subcategories  <span class="text-danger">*</span></h5>
                @php
                    $categories = config('coe_categories');
                    $selected = old('categories', $applicant->categories ?? []);
                @endphp
                @error('categories')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="row">
                    @foreach($categories as $mainKey => $main)
                        @php $mainChecked = in_array($mainKey, $selected); @endphp
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input main-cat" type="checkbox" id="cat_{{ $mainKey }}" value="{{ $mainKey }}" name="categories[]" {{ in_array($mainKey, $selected) ? 'checked' : '' }} />
                                <label class="form-check-label font-weight-bold" for="cat_{{ $mainKey }}">{{ $main['label'] }}</label>
                            </div>
                            <div class="subcats mt-2" id="sub_{{ $mainKey }}" style="display: {{ $mainChecked ? 'block' : 'none' }}; padding-left:1rem;">
                                @foreach($main['subs'] as $subKey => $subLabel)
                                    @php $val = $mainKey.':'.$subKey; @endphp
                                    <div class="form-check">
                                        <input class="form-check-input sub-cat" type="checkbox" id="sub_{{ $mainKey }}_{{ $subKey }}" name="categories[]" value="{{ $val }}" {{ in_array($val, $selected) ? 'checked' : '' }} />
                                        <label class="form-check-label" for="sub_{{ $mainKey }}_{{ $subKey }}">{{ $subLabel }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <script>
                    document.addEventListener('click', function(e){
                        if (e.target && e.target.classList.contains('main-cat')) {
                            const id = e.target.id.replace('cat_','');
                            const sub = document.getElementById('sub_'+id);
                            if (!sub) return;
                            if (e.target.checked) sub.style.display = 'block'; else sub.style.display = 'none';
                        }
                    });
                </script>

                <hr>
                <div class="text-right">
                    <button class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    (function(){
        let eduIndex = document.querySelectorAll('#educations .education-row').length;
        document.getElementById('add-education').addEventListener('click', function(){
            const container = document.getElementById('educations');
            const div = document.createElement('div');
            div.className = 'education-row row mb-2';
            div.setAttribute('data-index', eduIndex);
            
            // Generate year options
            let yearOptions = '<option value="">Year</option>';
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= 1960; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
            }
            
            div.innerHTML = `
                <div class="col-md-5"><input type="text" name="educations[${eduIndex}][qualification]" placeholder="Qualification" class="form-control"></div>
                <div class="col-md-3"><input type="text" name="educations[${eduIndex}][institution]" placeholder="Institution" class="form-control"></div>
                <div class="col-md-2"><select name="educations[${eduIndex}][year]" class="form-control ms">${yearOptions}</select></div>
                <div class="col-md-2 remove-btn-col" style="display: inline-block;"><button type="button" class="btn btn-sm btn-danger remove-education" title="Remove this education"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button></div>
            `;
            container.appendChild(div);
            container.querySelector('.remove-education:last-of-type').addEventListener('click', removeEducationRow);
            eduIndex++;
        });

        let expIndex = document.querySelectorAll('#experiences .experience-row').length;
        document.getElementById('add-experience').addEventListener('click', function(){
            const container = document.getElementById('experiences');
            
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
            
            // First row: organization, role, remove button
            const div1 = document.createElement('div');
            div1.className = 'experience-row row mb-2';
            div1.setAttribute('data-index', expIndex);
            div1.innerHTML = `
                <div class="col-md-5"><input type="text" name="experiences[${expIndex}][organization]" placeholder="Organization" class="form-control"></div>
                <div class="col-md-5"><input type="text" name="experiences[${expIndex}][role]" placeholder="Role" class="form-control"></div>
                <div class="col-md-2 remove-btn-col" style="display: inline-block;"><button type="button" class="btn btn-sm btn-danger remove-experience" title="Remove this experience"><i class="zmdi zmdi-delete" style="font-size: 14px;"></i></button></div>
            `;
            container.appendChild(div1);
            
            // Second row: years, months, and details
            const div2 = document.createElement('div');
            div2.className = 'row mb-2';
            div2.innerHTML = `
                <div class="col-md-2"><select name="experiences[${expIndex}][from_year]" class="form-control ms from-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;"><option value="">From Year</option>${yearOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][from_month]" class="form-control ms from-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">${monthOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][to_year]" class="form-control ms to-year exp-change" style="font-size: 16px; padding: 10px; height: 45px;"><option value="">To Year</option>${yearOptions}</select></div>
                <div class="col-md-2"><select name="experiences[${expIndex}][to_month]" class="form-control ms to-month exp-change" style="font-size: 16px; padding: 10px; height: 45px;">${monthOptions}</select></div>
                <div class="col-md-4"><textarea name="experiences[${expIndex}][details]" placeholder="Details" class="form-control"></textarea></div>
            `;
            container.appendChild(div2);
            
            container.querySelector('.remove-experience:last-of-type').addEventListener('click', removeExperienceRow);
            expIndex++;
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

        // client-side validation
        const phoneField = document.querySelector('input[name="contact_number"]');
        const phoneRegex = /^[0-9]{10}$/;

        function showFieldError(field, msg) {
            if (!field) return;
            field.classList.add('is-invalid');
            const existing = field.parentNode.querySelector('.invalid-feedback');
            if (existing) existing.remove();
            const fb = document.createElement('div');
            fb.className = 'invalid-feedback';
            fb.textContent = msg;
            if (field.nextSibling) field.parentNode.insertBefore(fb, field.nextSibling); else field.parentNode.appendChild(fb);
        }

        function clearFieldError(field) {
            if (!field) return;
            field.classList.remove('is-invalid');
            const fb = field.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.remove();
        }

        if (phoneField) {
            phoneField.addEventListener('input', function(){
                if (phoneRegex.test(this.value)) clearFieldError(this); else showFieldError(this,'⚠️ Mobile number must be exactly 10 digits');
            });
        }

        function validateYears() {
            const errors = [];
            document.querySelectorAll('#experiences .experience-row').forEach(function(row, idx){
                const from = row.querySelector('input[name^="experiences"][name$="[from_year]"]');
                const to = row.querySelector('input[name^="experiences"][name$="[to_year]"]');
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
                const subDiv = document.getElementById('sub_' + mainKey);
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
                showFieldError(phoneField, '⚠️ Mobile number must be exactly 10 digits');
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

            // educations completeness
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

            // experiences completeness
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
                    if (details && details.value.trim() === '') { e.preventDefault(); showFieldError(details, '⚠️ Details/Reason is required'); valid = false; }
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

        window.addEventListener('load', function(){
            const firstInvalid = document.querySelector('.is-invalid');
            if (firstInvalid) { firstInvalid.scrollIntoView({behavior:'smooth', block:'center'}); firstInvalid.focus(); }
        });
    })();
</script>
@endpush

@endsection
