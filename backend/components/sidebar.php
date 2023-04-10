        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-custom sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-ghost"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin<sup>2</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Content
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="article.php">
                    <i class="fas fa-newspaper"></i>
                    <span>Articles</span></a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                management
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="category.php">
                    <i class="fas fa-layer-group"></i>
                    <span>Category</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tag.php">
                    <i class="fas fa-hashtag"></i>
                    <span>Tag</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="video.php">
                    <i class="fas fa-hashtag"></i>
                    <span>Video</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="podcast.php">
                    <i class="fas fa-hashtag"></i>
                    <span>Podcast</span></a>
            </li>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <?php if ($userRole == '1') { ?>
                <div class="sidebar-heading">
                    user management
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="user.php">
                        <i class="fas fa-users"></i>
                        <span>User</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
            <?php } ?>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->