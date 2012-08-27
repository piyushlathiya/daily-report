<?php if(!isset($page_flag)) $page_flag=null;?>
<link rel="stylesheet" href="<?php echo $_core->getSkinUrl('style.css')?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo $_core->getSkinUrl('admin-style.css')?>" type="text/css" media="all" />
<div class="ply-logo">
    <a href="">Aureate Labs</a>
</div>
<div class="header">
    <div class="header-container">
        <div class="logo left">
            <a href="<?php echo $_core->getUrl('admin/index.php')?>"><img src="<?php echo $_core->getSkinUrl('images/logo.png') ?>" alt="Logo" /></a>
        </div>
        <div class="header-link right">
            <ul class="links">
                <?php if($page_flag=='manage-user'):?>
                <li><a class="button" href='<?php echo $_core->getUrl('admin/index.php')?>'><span>Home</span></a></li>
                <?php endif;?>
                <li><a href='<?php echo $_core->getUrl('admin/logout.php') ?>' title="Logout" ><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="navigation">
    <ul>
        <li <?php if($page_flag=='users'): ?>class="active"<?php endif;?>><a href="<?php echo $_core->getUrl('admin/users.php') ?>" ><span>Users</span></a></li>
        <li <?php if($page_flag=='activity-type'): ?>class="active"<?php endif;?>><a href="<?php echo $_core->getUrl('admin/activity-type.php') ?>" ><span>Activity Type</span></a></li>
        <li <?php if($page_flag=='projects'): ?>class="active"<?php endif;?>><a href="<?php echo $_core->getUrl('admin/projects.php') ?>" ><span>Projects</span></a></li>
    </ul>
</div>

<div class="clear"></div>
<div class="alert">
    <?php if (array_key_exists('messege', $_SESSION)): $type = ''; $text = '';
        extract($_SESSION['messege']);?>
        <?php if ($type && $text): ?>
            <div class="message-<?php echo $type ?>"><?php echo $text ?></div>
            <?php $_SESSION['messege'] = array(); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>