@extends('layouts.adminlayout')
@section('content')

    <!-- start: page toolbar -->
    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
      <div class="container-fluid">
        <div class="row g-3 mb-3 align-items-center">
          <div class="col">
            <ol class="breadcrumb bg-transparent mb-0">
              
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>
        </div> <!-- .row end -->
        <div class="row align-items-center">
          <div class="col">
            <h1 class="fs-5 color-900 mt-1 mb-0">Welcome back, {{auth()->user()->name}}!</h1>
            {{--<small class="text-muted">You have 12 new messages and 7 new notifications.</small>--}}
            <br>
            <img  src="{{asset('assets/admin/img/paaltu.jpg')}}">
          </div>
          {{--<div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-12 mt-2 mt-md-0">
            <!-- daterange picker -->
            <div class="input-group">
              <input class="form-control" type="text" name="daterange">
              <button class="btn btn-secondary" type="button" data-bs-toggle="tooltip" title="Send Report"><i class="fa fa-envelope"></i></button>
              <button class="btn btn-secondary" type="button" data-bs-toggle="tooltip" title="Download Reports"><i class="fa fa-download"></i></button>
              <button class="btn btn-secondary" type="button" data-bs-toggle="tooltip" title="Generate PDF"><i class="fa fa-file-pdf-o"></i></button>
              <button class="btn btn-secondary" type="button" data-bs-toggle="tooltip" title="Share Dashboard"><i class="fa fa-share-alt"></i></button>
            </div>
            <!-- Plugin Js -->
            <script src="{{ asset('assets/admin/js/bundle/daterangepicker.bundle.js') }}"></script>
            <!-- Jquery Page Js -->
            <script>
              // date range picker
              $(function() {
                $('input[name="daterange"]').daterangepicker({
                  opens: 'left'
                }, function(start, end, label) {
                  console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
              })
            </script>
          </div>--}}
        </div> <!-- .row end -->
      </div>
    </div>
    <!-- start: page body -->
    {{--<div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0 mt-lg-3">
      <div class="container-fluid">
        <div class="row g-3 row-deck">
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-dollar fa-lg"></i></div>
                <div class="flex-fill ms-3 text-truncate">
                  <div class="small">Revenue</div>
                  <span class="h5 mb-0">$18,925</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-credit-card fa-lg"></i></div>
                <div class="flex-fill ms-3 text-truncate">
                  <div class="small">Expense</div>
                  <span class="h5 mb-0">$11,024</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-smile-o fa-lg"></i></div>
                <div class="flex-fill ms-3 text-truncate">
                  <div class="small">Happy Clients</div>
                  <span class="h5 mb-0">8,925</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-eye fa-lg"></i></div>
                <div class="flex-fill ms-3 text-truncate">
                  <div class="small">Total Visitors</div>
                  <span class="h5 mb-0">18,925</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body">
                <span>Operation Income</span>
                <h4>7,12,326$</h4>
              </div>
              <div id="apexspark1"></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body">
                <span>Pharmacy Income</span>
                <h4>25,965$</h4>
              </div>
              <div id="apexspark2"></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body">
                <span>Hospital Expenses</span>
                <h4>14,965$</h4>
              </div>
              <div id="apexspark3"></div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card">
              <div class="card-body">
                <span>Hospital Expenses</span>
                <h4>14,965$</h4>
              </div>
              <div id="apexspark4"></div>
            </div>
          </div>
          <div class="col-xxl-4 col-xl-6 col-lg-4 col-md-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title m-0">Avg Treatment Costs</h6>
                <div class="dropdown morphing scale-left">
                  <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                  <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                  <ul class="dropdown-menu shadow border-0 p-2">
                    <li><a class="dropdown-item" href="#">File Info</a></li>
                    <li><a class="dropdown-item" href="#">Copy to</a></li>
                    <li><a class="dropdown-item" href="#">Move to</a></li>
                    <li><a class="dropdown-item" href="#">Rename</a></li>
                    <li><a class="dropdown-item" href="#">Block</a></li>
                    <li><a class="dropdown-item" href="#">Delete</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="h4 fw-bold mb-0">$105.5</div>
                <span class="text-muted small">Avg Treatment Costs All Ages</span>
                <div id="apex-ATCosts"></div>
              </div>
            </div>
          </div>
          <div class="col-xxl-4 col-xl-6 col-lg-4 col-md-6">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title m-0">Hospital Survey</h6>
                <div class="dropdown morphing scale-left">
                  <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                  <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                  <ul class="dropdown-menu shadow border-0 p-2">
                    <li><a class="dropdown-item" href="#">File Info</a></li>
                    <li><a class="dropdown-item" href="#">Copy to</a></li>
                    <li><a class="dropdown-item" href="#">Move to</a></li>
                    <li><a class="dropdown-item" href="#">Rename</a></li>
                    <li><a class="dropdown-item" href="#">Block</a></li>
                    <li><a class="dropdown-item" href="#">Delete</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div id="apex-HospitalSurvey"></div>
              </div>
            </div>
          </div>
          <div class="col-xxl-4 col-xl-12 col-lg-4 col-md-6">
            <div class="card">
              <div class="card-body d-flex align-items-start justify-content-between flex-column">
                <div>
                  <h6 class="mb-0">Top Rated Doctors</h6>
                  <small class="text-muted">9 Contacts</small>
                </div>
                <div class="pt-2">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar1.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar3.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar5.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar6.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar7.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar5.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                </div>
              </div>
              <div class="card-body d-flex align-items-start justify-content-between flex-column">
                <div>
                  <h6 class="mb-0">Top Rated Nurse</h6>
                  <small class="text-muted">14 Contacts</small>
                </div>
                <div class="pt-2">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar3.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar1.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar6.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar7.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar8.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar3.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar1.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar6.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar7.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar8.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                </div>
              </div>
              <div class="card-body d-flex align-items-start justify-content-between flex-column">
                <div>
                  <h6 class="mb-0">Contract base</h6>
                  <small class="text-muted">7 Contacts</small>
                </div>
                <div class="pt-2">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar7.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar8.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar7.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar8.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                  <img class="avatar sm rounded-circle m-1 lift" src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Doctors Name" alt="">
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8 col-lg-8 col-md-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title m-0">Patients Status</h6>
                <div class="dropdown morphing scale-left">
                  <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                  <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                  <ul class="dropdown-menu shadow border-0 p-2">
                    <li><a class="dropdown-item" href="#">File Info</a></li>
                    <li><a class="dropdown-item" href="#">Copy to</a></li>
                    <li><a class="dropdown-item" href="#">Move to</a></li>
                    <li><a class="dropdown-item" href="#">Rename</a></li>
                    <li><a class="dropdown-item" href="#">Block</a></li>
                    <li><a class="dropdown-item" href="#">Delete</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <table id="myDataTable" class="table card-table table-hover align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Patients</th>
                      <th>Adress</th>
                      <th>Admited</th>
                      <th>Discharge</th>
                      <th>Progress</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><img src="{{ asset('assets/admin/img/xs/avatar3.jpg') }}" class="avatar sm rounded-circle me-2" alt="profile-image"><span>John</span></td>
                      <td>70 Bowman St. South Windsor, CT 06074</td>
                      <td>Feb 13, 2022</td>
                      <td>Feb 16, 2022</td>
                      <td>
                        <div class="progress" style="height: 3px;">
                          <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"> <span class="sr-only">40% Complete</span> </div>
                        </div>
                      </td>
                      <td><span class="badge bg-info">Admit</span></td>
                    </tr>
                    <tr>
                      <td><img src="{{ asset('assets/admin/img/xs/avatar1.jpg') }}" class="avatar sm rounded-circle me-2" alt="profile-image"><span>Jack Bird</span></td>
                      <td>123 6th St. Melbourne, FL 32904</td>
                      <td>Feb 13, 2022</td>
                      <td>Feb 22, 2022</td>
                      <td>
                        <div class="progress" style="height: 3px;">
                          <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"> <span class="sr-only">100% Complete</span> </div>
                        </div>
                      </td>
                      <td><span class="badge bg-success">Discharge</span></td>
                    </tr>
                    <tr>
                      <td><img src="{{ asset('assets/admin/img/xs/avatar2.jpg') }}" class="avatar sm rounded-circle me-2" alt="profile-image"><span>Jack Bird</span></td>
                      <td>4 Shirley Ave. West Chicago, IL 60185</td>
                      <td>Feb 17, 2022</td>
                      <td>Feb 16, 2022</td>
                      <td>
                        <div class="progress" style="height: 3px;">
                          <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"> <span class="sr-only">100% Complete</span> </div>
                        </div>
                      </td>
                      <td><span class="badge bg-success">Discharge</span></td>
                    </tr>
                    <tr>
                      <td><img src="{{ asset('assets/admin/img/xs/avatar4.jpg') }}" class="avatar sm rounded-circle me-2" alt="profile-image"><span>Dean Otto</span></td>
                      <td>123 6th St. Melbourne, FL 32904</td>
                      <td>Feb 13, 2022</td>
                      <td>Feb 23, 2022</td>
                      <td>
                        <div class="progress" style="height: 3px;">
                          <div class="progress-bar bg-info" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: 15%;"> <span class="sr-only">15% Complete</span> </div>
                        </div>
                      </td>
                      <td><span class="badge bg-info">Admit</span></td>
                    </tr>
                    <tr>
                      <td><img src="{{ asset('assets/admin/img/xs/avatar5.jpg') }}" class="avatar sm rounded-circle me-2" alt="profile-image"><span>Hughe L.</span></td>
                      <td>4 Shirley Ave. West Chicago, IL 60185</td>
                      <td>Feb 18, 2022</td>
                      <td>Feb 18, 2022</td>
                      <td>
                        <div class="progress" style="height: 3px;">
                          <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;"> <span class="sr-only">85% Complete</span> </div>
                        </div>
                      </td>
                      <td><span class="badge bg-info">Admit</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-12">
            <div class="card">
              <div class="card-header">
                <h6 class="card-title m-0">Gender Overview</h6>
                <div class="dropdown morphing scale-left">
                  <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                  <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                  <ul class="dropdown-menu shadow border-0 p-2">
                    <li><a class="dropdown-item" href="#">File Info</a></li>
                    <li><a class="dropdown-item" href="#">Copy to</a></li>
                    <li><a class="dropdown-item" href="#">Move to</a></li>
                    <li><a class="dropdown-item" href="#">Rename</a></li>
                    <li><a class="dropdown-item" href="#">Block</a></li>
                    <li><a class="dropdown-item" href="#">Delete</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div id="apex-GenderOverview"></div>
              </div>
            </div>
          </div>
        </div> <!-- .row end -->
      </div>
    </div>--}}
    <!-- start: page footer -->
    

@endsection