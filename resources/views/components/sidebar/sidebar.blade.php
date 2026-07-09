  <div class="sidenav-menu">
      <div class="nav accordion" id="accordionSidenav">
          <!-- Sidenav Menu Heading (Account)-->
          <!-- * * Note: * * Visible only on and above the sm breakpoint-->
          <div class="sidenav-menu-heading d-sm-none">Account</div>
          <!-- Sidenav Link (Alerts)-->
          <!-- * * Note: * * Visible only on and above the sm breakpoint-->
          <a class="nav-link d-sm-none" href="#!">
              <div class="nav-link-icon"><i data-feather="bell"></i></div>
              Alerts
              <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
          </a>
          <!-- Sidenav Link (Messages)-->
          <!-- * * Note: * * Visible only on and above the sm breakpoint-->
          <a class="nav-link d-sm-none" href="#!">
              <div class="nav-link-icon"><i data-feather="mail"></i></div>
              Messages
              <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
          </a>
          <!-- Sidenav Menu Heading (Core)-->
          <div class="sidenav-menu-heading">Core</div>
          <!-- Sidenav Accordion (Dashboard)-->
          <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
              data-bs-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
              <div class="nav-link-icon"><i data-feather="activity"></i></div>
              Dashboards
              <div class="sidenav-collapse-arrow">
                  <i class="fas fa-angle-down"></i>
              </div>
          </a>
          <div class="collapse" id="collapseDashboards" data-bs-parent="#accordionSidenav">
              <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                  <a class="nav-link" href="dashboard-1.html">Default</a>
                  <a class="nav-link" href="dashboard-2.html">Multipurpose</a>
                  <a class="nav-link" href="dashboard-3.html">Affiliate</a>
              </nav>
          </div>


          {{-- ---------------------- Add and view users ----------------------  --}}

          <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
              data-bs-target="#collapseAddUser" aria-expanded="false" aria-controls="collapseAddUser">
              <div class="nav-link-icon"><i data-feather="activity"></i></div>
              Users
              <div class="sidenav-collapse-arrow">
                  <i class="fas fa-angle-down"></i>
              </div>
          </a>
          <div class="collapse" id="collapseAddUser" data-bs-parent="#accordionSidenav">
              <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                  <a class="nav-link" href="">Add User</a>
                  <a class="nav-link" href="">View Users</a>
              </nav>
          </div>



          {{-- <a class="nav-link" href="charts.html">
              <div class="nav-link-icon"><i data-feather="bar-chart"></i></div>
              Add User
          </a>
          <!-- Sidenav Link (Tables)-->
          <a class="nav-link" href="tables.html">
              <div class="nav-link-icon"><i data-feather="filter"></i></div>
              Tables
          </a> --}}
      </div>
  </div>
