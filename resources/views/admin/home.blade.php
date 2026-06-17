@extends('layouts.admin')

@section('title','Welcome - IPA Center of Excellence')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; min-height: 100vh; padding: 60px 20px;">
    <div class="container">
        <!-- Header Section -->
        <div style="text-align: center; margin-bottom: 80px; padding-top: 40px;">
            <h1 style="font-weight: 700; margin-bottom: 10px; font-size: 42px;">Welcome to IPA</h1>
            <h2 style="font-size: 28px; margin-bottom: 20px; font-weight: 600;">Center of Excellence Expression of Interest Portal</h2>
            <p style="font-size: 18px; opacity: 0.95; margin-bottom: 40px;">Submit your Expression of Interest to join our Center of Excellence program</p>
            
            <!-- Quick Action Buttons -->
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-bottom: 60px;">
                <a href="{{ route('admin.register') }}" class="btn btn-success btn-round btn-lg" style="padding: 15px 40px; font-size: 16px; font-weight: 600; background-color: #28a745;">
                    📝 CREATE NEW ACCOUNT
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-round btn-lg" style="padding: 15px 40px; font-size: 16px; font-weight: 600;">
                    🔐 SIGN IN
                </a>
            </div>
        </div>

        <!-- Complete Application Process -->
        <!-- Registration & Login Section -->
        <div style="margin-bottom: 80px;">
            <h3 style="text-align: center; margin-bottom: 50px; font-weight: 600; font-size: 24px;">PART 1: Account Setup</h3>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; max-width: 1000px; margin: 0 auto;">
                
                <!-- Step 1: Sign Up -->
                <div style="flex: 1; min-width: 160px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 32px;">
                        📝
                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 8px; font-size: 16px;">Create Account</h5>
                    <p style="font-size: 13px; line-height: 1.5; opacity: 0.9;">
                        Register with your email and create a password
                    </p>
                </div>

                <div style="flex: 0 0 auto; text-align: center; padding-top: 50px; font-size: 24px; opacity: 0.5;">→</div>

                <!-- Step 2: Verify -->
                <div style="flex: 1; min-width: 160px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 32px;">
                        ✉️
                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 8px; font-size: 16px;">Verify Email</h5>
                    <p style="font-size: 13px; line-height: 1.5; opacity: 0.9;">
                        Check your email for verification link
                    </p>
                </div>

                <div style="flex: 0 0 auto; text-align: center; padding-top: 50px; font-size: 24px; opacity: 0.5;">→</div>

                <!-- Step 3: Login -->
                <div style="flex: 1; min-width: 160px; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 32px;">
                        🔐
                    </div>
                    <h5 style="font-weight: 600; margin-bottom: 8px; font-size: 16px;">Sign In</h5>
                    <p style="font-size: 13px; line-height: 1.5; opacity: 0.9;">
                        Login with your email and password
                    </p>
                </div>
            </div>
        </div>

        <!-- Application Form Section -->
        <div style="margin-bottom: 60px;">
            <h3 style="text-align: center; margin-bottom: 50px; font-weight: 600; font-size: 24px;">PART 2: Fill Application (Single Form - All Fields Below)</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
                
                <!-- Personal Information -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">👤</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Personal Information</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Full Name *</li>
                        <li style="margin-bottom: 8px;">• Email Address *</li>
                        <li style="margin-bottom: 8px;">• Contact Number *</li>
                        <li style="margin-bottom: 8px;">• Address *</li>
                    </ul>
                </div>

                <!-- Categories -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">📋</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Categories & Expertise</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Select Main Categories *</li>
                        <li style="margin-bottom: 8px;">• Choose Subcategories *</li>
                        <li style="margin-bottom: 8px;">• Can select multiple areas</li>
                        <li style="margin-bottom: 8px;">• Based on your expertise</li>
                    </ul>
                </div>

                <!-- Education -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">🎓</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Educational Qualifications</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Qualification (Degree) *</li>
                        <li style="margin-bottom: 8px;">• Institution/University *</li>
                        <li style="margin-bottom: 8px;">• Passing Year *</li>
                        <li style="margin-bottom: 8px;">• Add multiple entries</li>
                    </ul>
                </div>

                <!-- Experience -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">💼</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Professional Experience</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Organization Name</li>
                        <li style="margin-bottom: 8px;">• Role/Designation</li>
                        <li style="margin-bottom: 8px;">• From Year & Month</li>
                        <li style="margin-bottom: 8px;">• To Year & Month & Details</li>
                    </ul>
                </div>

                <!-- Documents -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">📄</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Documents (Optional)</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Resume/CV (PDF, DOC, DOCX)</li>
                        <li style="margin-bottom: 8px;">• Max 2 MB</li>
                        <li style="margin-bottom: 8px;">• Additional Document</li>
                        <li style="margin-bottom: 8px;">• Max 5 MB</li>
                    </ul>
                </div>

                <!-- Submit -->
                <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 36px; margin-bottom: 12px;">✅</div>
                    <h6 style="font-weight: 700; margin-bottom: 12px; font-size: 16px;">Final Step: Submit</h6>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">• Review all information</li>
                        <li style="margin-bottom: 8px;">• Click Submit button</li>
                        <li style="margin-bottom: 8px;">• One-time submission</li>
                       <!-- <li style="margin-bottom: 8px;">• Cannot be edited later</li>-->
                    </ul>
                </div>
            </div>

            <!-- Timeline Info -->
            <div style="text-align: center;">
                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <div style="display: inline-block; padding: 15px 25px; background: rgba(255,255,255,0.1); border-radius: 25px; font-size: 14px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        ⏱ <strong>Time Required:</strong> 10-15 minutes
                    </div>
                    <div style="display: inline-block; padding: 15px 25px; background: rgba(255,255,255,0.1); border-radius: 25px; font-size: 14px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        📋 <strong>Total Fields:</strong> All in one page
                    </div>
                    <div style="display: inline-block; padding: 15px 25px; background: rgba(255,255,255,0.1); border-radius: 25px; font-size: 14px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        ✓ <strong>Status:</strong> Submit once
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div style="text-align: center; margin-top: 80px; padding: 60px 20px;">
            <h3 style="font-size: 28px; font-weight: 600; margin-bottom: 30px;">Ready to Get Started?</h3>
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('admin.register') }}" class="btn btn-success btn-round btn-lg" style="padding: 15px 40px; font-size: 16px; font-weight: 600; background-color: #28a745;">
                    📝 CREATE ACCOUNT NOW
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-round btn-lg" style="padding: 15px 40px; font-size: 16px; font-weight: 600;">
                    🔐 SIGN IN HERE
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.2);">
            <div class="container">
                <nav>
                    <ul style="display: flex; justify-content: center; gap: 40px; flex-wrap: wrap;">
                        <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=2" style="color: white; text-decoration: none;">Contact Us</a></li>
                        <li><a href="https://www.ipa.nic.in/index1.cshtml?lsid=341" style="color: white; text-decoration: none;">About Us</a></li>
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</div>

@endsection
