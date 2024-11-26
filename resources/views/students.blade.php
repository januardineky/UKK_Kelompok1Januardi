<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>UKK Januardi</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- site icon -->
      <link rel="icon" href="images/fevicon.png" type="image/png" />
      <!-- bootstrap css -->
      <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
      <!-- site css -->
      <link rel="stylesheet" href="{{ asset('style.css') }}" />
      <!-- responsive css -->
      <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
      <!-- color css -->
      <link rel="stylesheet" href="{{ asset('css/color_2.css') }}" />
      <!-- select bootstrap -->
      <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}" />
      <!-- scrollbar css -->
      <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}" />
      <!-- custom css -->
      <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body class="dashboard dashboard_2">
    @include('sweetalert::alert')

      <div class="full_container">
         <div class="inner_container">
            <!-- Sidebar  -->
            <nav id="sidebar">
               <div class="sidebar_blog_1">
                  <div class="sidebar_user_info">
                     <div class="icon_setting"></div>
                     <div class="user_profle_side">
                        <div class="user_info">
                           <h6>{{ $data->full_name }}</h6>
                           <p><span class="online_animation"></span> Online</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sidebar_blog_2">

                  <ul class="list-unstyled components">
                     <li class="active">
                        <a href="/home"><i class="fa fa-dashboard yellow_color"></i> <span>Dashboard</span></a>
                     </li>
                     <li><a href="/home/table"><i class="fa fa-table purple_color2"></i> <span>Laporan</span></a></li>
                     <li>
                        <a href="/home/students">
                        <i class="fa fa-mortar-board purple_color"></i> <span>Siswa</span></a>
                     </li>
                     <li class="active">
                        <a href="#additional_page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-plus-square-o green_color"></i> <span>Input</span></a>
                        <ul class="collapse list-unstyled" id="additional_page">
                            <li>
                                <a href="/home/inputadmin">> <span>Admin</span></a>
                            </li>
                           <li>
                              <a href="/home/inputstudent">> <span>Siswa</span></a>
                           </li>
                           <li>
                            <a href="/home/inputassessor">> <span>Penguji</span></a>
                         </li>
                           <li>
                              <a href="/home/inputmajor">> <span>Jurusan</span></a>
                           </li>
                           <li>
                            <a href="/home/inputcompetency">> <span>Standar Kompetensi</span></a>
                            </li>
                            <li>
                            <a href="/home/inputelement">> <span>Elemen Kompetensi</span></a>
                            </li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </nav>
            <!-- end sidebar -->
            <!-- right content -->
            <div id="content">
               <!-- topbar -->
               <div class="topbar">
                  <nav class="navbar navbar-expand-lg navbar-light">
                     <div class="full">
                        <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
                        <div class="right_topbar">
                           <div class="icon_info">
                              <ul class="user_profile_dd">
                                 <li>
                                    <a class="dropdown-toggle" data-toggle="dropdown"><span class="name_user">{{ $data->full_name }}</span></a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/home/profile">My Profile</a>
                                        <a class="dropdown-item" href="/home/logout"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </nav>
               </div>
               <!-- end topbar -->
               <!-- dashboard inner -->
               <div class="row column1">
                <div class="col-md-12">
                   <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="row align-items-center">
                            <div class="col-md-6 heading1 margin_0">
                                <h2>Siswa</h2>
                            </div>
                            <div class="col-md-6 text-end">
                                <form class="d-flex justify-content-end" action="/home/students/search" method="GET">
                                    @csrf
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="cari" style="max-width: 200px;">
                                    <input class="btn btn-outline-primary" type="submit"></input>
                                </form>
                            </div>
                        </div>
                    </div>
                      <div class="full price_table padding_infor_info">
                         <div class="row">
                            <!-- column contact -->
                            @foreach ($students as $student)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 profile_details margin_bottom_30">
                                <div class="contact_blog">
                                    <h4 class="brief">NISN : {{ $student->nisn }}</h4>
                                    <div class="contact_inner">
                                        <div class="left">
                                            <h3>{{ $student->user->full_name }}</h3>
                                            <p><strong>Jurusan: </strong>{{ $student->major->major_name ?? 'N/A' }}</p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-envelope-o"></i> : {{ $student->user->email }}</li>
                                                <li><i class="fa fa-phone"></i> : {{ $student->user->phone_number }}</li>
                                                <li><i class="fa fa-graduation-cap"></i> : {{ $student->grade_level }}</li>
                                            </ul>
                                        </div>
                                        <div class="bottom_list">
                                            <div class="right_button">
                                                <a href="/home/students/edit/{{ $student->id }}">
                                                    <button type="button" class="btn btn-primary btn-xs full-width">
                                                        <i class="fa fa-user"></i> Edit
                                                    </button>
                                                </a>
                                                <a href="/home/students/delete/{{ $student->id }}" onclick="return window.confirm('Yakin Hapus Data Siswa Ini?')">
                                                    <button type="button" class="btn btn-danger btn-xs full-width">
                                                        <i class="fa fa-user"></i> Delete
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                            <!-- end column contact blog -->
                         </div>
                      </div>
                   </div>
                </div>
                <!-- end row -->
             </div>
               </div>
               <!-- end dashboard inner -->
            </div>
         </div>
      </div>
      <!-- jQuery -->
      <script src="{{ asset('js/jquery.min.js') }}"></script>
      <script src="{{ asset('js/popper.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <!-- wow animation -->
      <script src="{{ asset('js/animate.js') }}"></script>
      <!-- select country -->
      <script src="{{ asset('js/bootstrap-select.js') }}"></script>
      <!-- owl carousel -->
      <script src="{{ asset('js/owl.carousel.js') }}"></script>
      <!-- chart js -->
      <script src="{{ asset('js/Chart.min.js') }}"></script>
      <script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
      <script src="{{ asset('js/utils.js') }}"></script>
      <script src="{{ asset('js/analyser.js') }}"></script>
      <!-- nice scrollbar -->
      <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
      <!-- custom js -->
      <script src="{{ asset('js/custom.js') }}"></script>
      <script src="{{ asset('js/chart_custom_style1.js') }}js/chart_custom_style1.js"></script>
   </body>
</html>
