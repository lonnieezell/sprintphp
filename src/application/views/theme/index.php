<?php $this->load->view('theme/header'); ?>

<?php $this->load->view('theme/main_nav'); ?>

    <?php if (isset($page_title)) :?>
    <header class="container">
        <h1><?php echo $page_title ?></h1>
    </header>
    <?php endif; ?>

    <div id="main-content" class="container">
        <?= $view_content ?>
    </div>

<?php $this->load->view('theme/footer'); ?>
