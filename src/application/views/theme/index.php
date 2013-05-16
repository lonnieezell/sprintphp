<?php $this->load->view('theme/header', null, false, true); ?>

    <!-- Top Navigation -->
    <nav class="top-bar">
        <ul class="title-area">
            <li class="name">
                <h1><a href="#">My Site</a></h1>
            </li>
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>

        <section class="top-bar-section">
            <ul class="right">
                <li class="divider"></li>
                <li><a href="#">Users</a></li>
            </ul>
        </section>
    </nav>

    <?php if (isset($page_title)) :?>
    <header>
        <div class="row">
            <div class="twelve columns">
                <h1><?php echo $page_title ?></h1>
            </div>
        </div>
    </header>
    <?php endif; ?>

    <section id="main-content">
        <div class="row">
            <div class="twelve columns">
                <?= $view_content ?>
            </div>
        </div>
    </section>

<?php $this->load->view('theme/footer', null, false, true); ?>
