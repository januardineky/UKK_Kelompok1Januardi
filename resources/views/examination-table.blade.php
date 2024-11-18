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
      <title>Pluto - Responsive Bootstrap Admin Panel Templates</title>
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
                              <a href="/home/inputstudent">> <span>Siswa</span></a>
                           </li>
                           <li>
                              <a href="/home/inputassessor">> <span>Penguji</span></a>
                           </li>
                           <li>
                            <a href="/home/inputmajor">> <span>Jurusan</span></a>
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
               <div class="midde_cont">
                <div class="container-fluid">
                   <div class="row column_title">
                      <div class="col-md-12">
                         <div class="page_title">
                            <h2>Laporan Ujian {{ $student->user->full_name }}</h2>
                         </div>
                      </div>
                   </div>
                   <!-- row -->
                   <div class="row">
                      <!-- table section -->
                      <div class="col-md-12">
                         <div class="white_shd full margin_bottom_30">
                            <div class="table_section padding_infor_info">
                               <div class="table-responsive-sm">
                                  <table class="table">
                                     <thead>
                                        <tr>
                                           <th>Jurusan</th>
                                           <th>Standar Kompetensi</th>
                                           <th>Penguji</th>
                                           <th>Elemen Kompetensi</th>
                                           <th>Penilaian</th>
                                        </tr>
                                     </thead>
                                     <tbody>
                                        @if($student->examinations->isNotEmpty())
                                            @foreach($student->examinations as $examination)
                                                <tr>
                                                    @if ($loop->first) <!-- Check if it's the first iteration -->
                                                        <td rowspan="{{ $student->examinations->count() }}">{{ $student->major->major_name }}</td>
                                                    @endif
                                                    <td>
                                                        <ul>
                                                            <li>{{ $examination->competencyElement->competencyStandard->unit_title }}</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>{{ $examination->assessor->user->full_name ?? 'N/A' }}</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>{{ $examination->competencyElement->criteria }}</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>{{ $examination->status == 1 ? 'Kompeten' : 'Belum Kompeten' }}</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>{{ $student->major->major_name }}</td>
                                                <td>Tidak Ada</td>
                                                <td>Tidak Ada</td>
                                                <td>Tidak Ada</td>
                                                <td>Tidak Ada</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                  </table>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
                <!-- footer -->
                <div class="container-fluid">
                   <div class="footer">
                      <p>Copyright © 2018 Designed by html.design. All rights reserved.</p>
                   </div>
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
