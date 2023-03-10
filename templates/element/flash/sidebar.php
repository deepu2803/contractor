<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">

  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
      <?php //$this->Html->image($auth->image, ['class' => 'user_img']) 
      ?>
      <span class="ms-1 font-weight-bold text-white">Material Dashboard 2</span>
    </a>
  </div>


  <hr class="horizontal light mt-0 mb-2">

  <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">



      <li class="nav-item">
          <a class="nav-link text-white " href="/users/owner-profile">

            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person_outline</i>
            </div>
            <span class="nav-link-text ms-1">My Profile</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white " href="/projects/requested-project-list">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">format_list_numbered</i>
            </div>
            <span class="nav-link-text ms-1">Request Project List</span>
          </a>
      </li>

        <li class="nav-item">
          <a class="nav-link text-white " href="/projects/request-new-project">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">format_list_numbered</i>
            </div>
            <span class="nav-link-text ms-1">Request New Project</span>
          </a>
      </li>


      <li class="nav-item">
          <a class="nav-link text-white " href="/users/logout">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">logout</i>
            </div>
            <span class="nav-link-text ms-1">Logout</span>
          </a>
      </li>
    </ul>
  </div>

  <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    <div class="mx-3">
      <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to
        pro</a>
    </div>

  </div>

</aside>