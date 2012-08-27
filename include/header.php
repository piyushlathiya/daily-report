<?php include_once('application_top.php');
$user_name = $_user->getUserName();
?>
<link rel="stylesheet" href="<?php echo $_core->getSkinUrl('style.css')?>" type="text/css" media="all" />
<div class="header">
    <div class="header-container">
        <div class="logo left">
            <a href="<?php echo $_core->getUrl('activity.php')?>"><img src="<?php echo $_core->getSkinUrl('images/logo.png') ?>" alt="Logo" /></a>
        </div>
        <div class="right profile-thumbnail">
            <a href="#" onclick="javascript:slideDown()">
                <span><?php echo $user_name;?></span>
                <img src="<?php echo $_user->getSkinUrl($_user->getUserProfileThumbnailImage())?>" alt="Profile Image" />
                <span class="triangle-down"></span>
            </a>
        </div>
        <div class="header-link clear header-slidedown">
            <span class="upper-triangle"></span>
            <div class="upper">
                <img src="<?php echo $_user->getSkinUrl($_user->getUserProfileImage())?>" alt="Profile Image" />
                <h4><?php echo $user_name;?></h4>
                <p><?php echo $_user->getUserEmail() ?></p>
            </div>
            <ul class="links">
                <?php if($page_flag=='manage-user'):?>
                <li><a class="button" href='<?php echo $_core->getUrl('')?>'><span>Home</span></a></li>
                <?php else:?>
                <li><a class="button" href='<?php echo $_core->getUrl('manage-user.php')?>'><span>Edit Profile</span></a></li>
                <?php endif;?>
                <li><a class="button" href='<?php echo $_core->getUrl('logout.php') ?>'><span>Logout</span></a></li>
            </ul>
        </div>
        <script type="text/javascript">
            $(".header-slidedown").hide();
            function slideDown(){
                $(".header-slidedown").slideToggle("slow");
            }
            $(document).mouseup(function (e)
            {
                var container = $(".header-slidedown");
                if (container.has(e.target).length === 0){
                    container.hide();
                }
            });
        </script>
    </div>
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