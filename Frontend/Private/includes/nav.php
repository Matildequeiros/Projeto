<!-- NAVBAR -->
    <header class="container-fluid" style="background-color: #acd6d0;">
        <div class="row align-items-center">

            <!-- LOGO + TÍTULO -->
            <div class="col-6 d-flex align-items-center p-3">
                <a href="index.html">
                    <img src="../assets/img/Logo.png" alt="Logo HospitalGest" height="50" class="me-3">
                </a>
                <h3 class="mb-0 logo-text">
                    <span class="verde"><?php echo explode("Gest", APP_NAME)[0]; ?></span>
                    <span class="azul">Gest</span>
                </h3>


            </div>

            <!-- UTILIZADOR (dropdown) -->
            <div class="col-6 text-end p-3">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        style="background-color: #86B0AA; color: white; border: none;">
                        <i class="fa-regular fa-user me-2"></i> Utilizador
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">
                                <i class="fa-solid fa-key me-2"></i> Alterar password
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li><a class="dropdown-item" href="../Login/login.html">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </header>