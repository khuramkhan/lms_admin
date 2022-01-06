<!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="app-assets/images/backgrounds/02.jpg">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="#">
                        <h3 class="brand-text">Chemron</h3>
                    </a></li>
                <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
            </ul>
        </div>
        <div class="navigation-background"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item"><a href="{{url('/')}}"><i class="ft-home"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
                </li>
            </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a href="#"><i class="ft-user"></i></i><span class="menu-title" data-i18n="">Users</span></a>
                        <ul class="menu-content">
                            {{-- <li class="{{Request::url() == url('users') ? 'active' : ''}}"><a class="menu-item" href="{{ url('product/add') }}">Add Product</a> --}}
                            </li>
                                <li class="{{Request::url() == url('users') ? 'active' : ''}}"><a class="menu-item" href="{{ url('users') }}">View Users</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a href="#"><i class="ft-book"></i></i><span class="menu-title" data-i18n="">Courses</span></a>
                        <ul class="menu-content">
                            </li>
                                <li class="{{Request::url() == url('courses') ? 'active' : ''}}"><a class="menu-item" href="{{ url('courses') }}">View Courses</a>
                            </li>
                            <li class="{{Request::url() == url('course/add') ? 'active' : ''}}"><a class="menu-item" href="{{ url('course/add') }}">Add Course</a>
                        </ul>
                    </li>
                </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a href="{{ route('about-us') }}"><i class="ft-info"></i></i><span class="menu-title" data-i18n="">AboutUs</span></a>
                    </li>
                </ul>
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <li class=" nav-item"><a href="{{ route('contact-us') }}"><i class="ft-phone"></i></i><span class="menu-title" data-i18n="">FAQs</span></a>
                    </li>
                </ul>
        </div>
    </div>
    <!-- END: Main Menu-->
