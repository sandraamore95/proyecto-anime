<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- css-->

  <link href="css/style.css" rel="stylesheet">
  <style>
    .no-mobile {
      display: none;
    }

    @media (min-width: 700px) {
      .no-mobile {
        display: revert;
      }

      .no-desktop {
        display: none;
      }
    }
  </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">




    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->




        <?php if (isset($_SESSION['user_logeado'])) : ?>
          <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">



            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

              <?php if ($_SESSION['acceso'] == 1) : ?>

                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link" href="index.php?ctl=inicio">
                    <span class="no-mobile">Home</span>
                    <span class="no-desktop"><i class="fa-solid fa-house"></i></span>
                  </a>
                </li>

                <!-- Nav Item - Messages -->

                <?php
                $m = Model::GetInstance();

                // TOTAL REQUESTS
                $get_req_num = $m->request_notification($_SESSION['id'], false);
                // TOTAL FRIENDS
                $get_frnd_num = $m->getFriends($_SESSION['id'], false);
                $get_all_req_sender = $m->request_notification($_SESSION['id'], true);
                ?>

                <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="no-mobile">Request</span> <span class="no-desktop"><i class="fa-solid fa-user-plus"></i></span> <span class="badge <?php if ($get_req_num > 0) {
                                                                                                                                                      echo 'redBadge';
                                                                                                                                                    } ?>"><?php echo $get_req_num; ?></span>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                      <h6 class="dropdown-header">
                        Message Center
                      </h6>

                      <?php if ($get_req_num > 0) {
                        foreach ($get_all_req_sender as $row) {
                          echo '
                        
                        
                <a class="dropdown-item d-flex align-items-center" href="index.php?ctl=profile&id=' .
                            $row->sender .
                            '">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="' .
                            $row->foto .
                            '" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I wanna be your friend</div>
                  </div>
                </a>
                        
                        ';
                        }
                      } ?>
                    </div>
                  </a>
                </li>
                <?php
                $m = Model::GetInstance();

                //NOTIFICACIONES
                $getnotificaciones = $m->getnotificaciones($_SESSION['id']);
                ?>

                <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="no-mobile">Notificaciones</span> <span class="no-desktop"><i class="fas fa-comment-medical"></i></i></span> <span class="badge <?php if (count($getnotificaciones) > 0) {
                                                                                                                                                                  echo 'redBadge';
                                                                                                                                                                } ?>"><?php echo count($getnotificaciones); ?></span>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                      <h6 class="dropdown-header">
                        Message Center
                      </h6>


                      <?php if (count($getnotificaciones) > 0) {
                        foreach ($getnotificaciones as $value) {
                          echo '        
                          <a class="dropdown-item d-flex align-items-center" href="index.php?ctl=dibujo&id_dibujo=' .
                            $value['id_dibujo'] . '&leido=1&receiver=' . $value['receiver'] . '"><div class="dropdown-list-image mr-3">
                            <img class="" src="images/comentarios.png" alt="">
                            <div class="status-indicator bg-success"></div>
                            </div>
                            <div class="font-weight-bold">
                              <div class="text-truncate">New comments !</div>
                            </div>
                          </a>
                        ';
                        }
                      } ?>


                    </div>
                  </a>
                </li>

                <li class="nav-item dropdown no-arrow mx-1">
                  <a class="nav-link" href="index.php?ctl=profile&id=<?php echo $_SESSION['id']; ?>">
                    <span class="no-mobile">Mi Perfil</span>
                    <!-- Counter - Alerts -->
                    <span class="no-desktop"><i class="fa-solid fa-address-card"></i></span>
                  </a>

                </li>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                  <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo $_SESSION['avatar']; ?>" width="65" height="60" class="rounded-circle" alt="User Image" />


                  </a>
                  <!-- Dropdown - User Information -->
                  <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="index.php?ctl=editprofile">
                      <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                      Edit Profile
                    </a>

                    <a class="dropdown-item" href="index.php?ctl=resetPassw">
                      <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                      Reset Password
                    </a>


                  </div>
                </li>
                <div class="topbar-divider d-none d-sm-block"></div>

              <?php endif; ?>

              <li class="nav-item">
                <a class="nav-link" href="" data-toggle="modal" data-target="#logoutModal">

                  <span class="no-mobile">Cerrar Sesión</span>
                  <span class="no-desktop"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                </a>

              </li>
            </ul>


            <!-- End of Topbar -->
          </nav>
        <?php endif; ?>


        <?php if (!isset($_SESSION['user_logeado'])) : ?>
          <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link" href="index.php?ctl=inicio">
                  Home
                </a>
              </li>
              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link" href="index.php?ctl=login">
                  Sign In
                </a>
              </li>

              <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link" href="index.php?ctl=register">
                  Sign Up
                </a>
              </li>

              <!-- Nav Item - Messages -->
            </ul>
          <?php endif; ?>
          </nav>