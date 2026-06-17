<aside id="leftsidebar" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboard"><i class="zmdi zmdi-home m-r-5"></i>IPA</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user"><i class="zmdi zmdi-account m-r-5"></i>User</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="image"><a href="#"><img src="/html/light/assets/images/profile_av.png" alt="User"></a></div>
                            <div class="detail">
                                <h4>{{ auth('admin')->user()->name ?? 'Admin' }}</h4>
                                <small>@if(auth('admin')->user()->is_super) Super Admin @else User @endif</small>
                            </div>
                        </div>
                    </li>
                    <li class="header">MAIN</li>
                    @if(!auth('admin')->user()->is_super)
                        <li> <a href="{{ route('admin.applicants.create') }}"><i class="zmdi zmdi-file-text"></i><span>Applicant Form</span></a></li>
                    @endif
                    @if(auth('admin')->check() && auth('admin')->user()->is_super)
                            <li class="nav-item">
                                <a href="{{ route('admin.applicants.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                                    <span><i class="menu-icon fas fa-users"></i> <span>Applicants List</span></span>
                                    <span class="badge bg-success text-white ms-2">{{ \App\Models\Applicant::count() }}</span>
                                </a>
                            </li>
                    @endif
                    <!--<li class="active open"> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a>
                        <ul class="ml-menu">
                            <li class="active"><a href="{{ route('admin.dashboard') }}">Main</a> </li>
                            <li><a href="javascript:void(0);">RTL</a></li>
                            <li><a href="javascript:void(0);">Horizontal</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-apps"></i><span>App</span> </a>
                        <ul class="ml-menu">
                            <li><a href="#">Inbox</a></li>
                            <li><a href="#">Chat</a></li>
                            <li><a href="#">Calendar</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-shopping-cart"></i><span>Ecommerce</span> </a>
                        <ul class="ml-menu">
                            <li> <a href="#">Dashboard</a></li>
                            <li> <a href="#">Product</a></li>
                            <li> <a href="#">Product List</a></li>
                        </ul>
                    </li>
                    <li class="header">FORMS, CHARTS, TABLES</li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>Forms</span> </a>
                        <ul class="ml-menu">
                            <li><a href="#">Basic Elements</a> </li>
                            <li><a href="#">Advanced Elements</a> </li>
                        </ul>
                    </li>-->
                    <li class="header">ACCOUNT</li>
                    <li><a href="{{ route('admin.password.change') }}"><i class="zmdi zmdi-lock"></i><span>Change Password</span></a></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 text-left" data-no-validate><i class="zmdi zmdi-power"></i> Logout</button>
                        </form>
                    </li>
                    <!--<li class="header">EXTRA</li>
                    <li>
                        <div class="progress-container progress-primary m-t-10">
                            <span class="progress-badge">Traffic this Month</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;">
                                    <span class="progress-value">67%</span>
                                </div>
                            </div>
                        </div>
                    </li>-->
                </ul>
            </div>
        </div>
        <div class="tab-pane stretchLeft" id="user">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info m-b-20 p-b-15">
                            <div class="image"><a href="#"><img src="/html/light/assets/images/profile_av.png" alt="User"></a></div>
                            <div class="detail">
                                <h4>{{ auth('admin')->user()->name ?? 'Admin' }}</h4>
                                <small>Administrator</small>
                            </div>
                        </div>
                    </li>
                    <li>
                        <small class="text-muted">Email address: </small>
                        <p>{{ auth('admin')->user()->email ?? '—' }}</p>
                        <hr>
                       <!-- <small class="text-muted">Phone: </small>
                        <p>{{ auth('admin')->user()->email ?? '—' }}</p>-->
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
