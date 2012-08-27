<?php
include_once('include/application_top.php');

$admin_logged_in = $_admin->isAdminLoggedIn();
if (!$admin_logged_in) {
//	var_dump(array_reverse(debug_backtrace())); exit;
    //$_SESSION['msg']['error']="";
    $_SESSION['messege'] = array('type' => 'fail', 'text' => 'please login first to access your account');
    $_admin->redirect('admin/index.php');
    $_admin->close();
    exit;
}

$users = $_admin->getUsers();
$page_flag='projects';

$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : LIMIT;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : PAGE;
$column='';
$project_data = $_admin->getProjectGridDetail($page, $limit, $column);
$no_records = count($project_data);
$total_records = $_core->getTotalRecords('project_overview');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Aureate - Projects</title>
        <link href="<?php echo $_core->getSkinUrl('jquery-ui.css')?>" rel="stylesheet" type="text/css"/>
        <script src="<?php echo $_core->getUrl('js/jQuery.min.js')?>" type="text/javascript"></script>
        <script src="<?php echo $_core->getUrl('js/jquery-ui-1.8.13.custom.min.js')?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/tiny_mce/tiny_mce.js')?>"></script>
        <script type="text/javascript" src="<?php echo $_core->getUrl('js/jquery.validate.js')?>"></script>
        <script type="text/javascript" charset="utf-8">
            $(function(){
                $("#start_at").datepicker();
                $("#end_at").datepicker();
                $("#deadline").datepicker();
            });
            
            $(function () {
                    var tabContainers = $('div.tabs > div');
                    tabContainers.hide().filter(':first').show();

                    $('div.tabs ul.tabNavigation a').click(function () {
                            tabContainers.hide();
                            tabContainers.filter(this.hash).show();
                            $('div.tabs ul.tabNavigation a').removeClass('selected');
                            $(this).addClass('selected');
                            return false;
                    }).filter(':first').click();
            });

            // Initialize TinyMCE with the new plugin and listbox
            tinyMCE.init({
                plugins : '-example', // - tells TinyMCE to skip the loading of the plugin
                mode : "textareas",
                theme : "advanced",
                theme_advanced_buttons1 : "mylistbox,mysplitbutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom"
            });
            
            $(document).ready(function(){
                $("#project_desc").validate();
            });
 
            function popUpContent(id){
                    var request = $.ajax({
                        type: "POST",
                        url: "<?php echo $_core->getUrl('admin/edit-project-desc.php')?>",
                        data: {id : id},
                        dataType: "html"
                        }).done( function(content){
                        $(".content").html(content);
                    });
            }
            $(function(){
                $("#blanket").hide();
                $(".edit-project-desc").hide();
                $(".edit-project-desc").css({"position" : "fixed" ,"top" : $(window).height()/2-150, "left": $(window).width()/2-162 });
            });
            
            function popUpActivityType(id){
                popUpContent(id);
                $(".edit-project-desc").fadeIn('fast');
                $("#blanket").show();
            }
	</script>
    </head>

    <body>
        <?php include_once ('include/header.php'); ?>
        <div class="projects">
            <div class="tabs">
                <ul class="tabNavigation">
                    <li><a href="#first">Overview</a></li>
                    <li><a href="#second">Description</a></li>
                    <li><a href="#third">To-do</a></li>
                    <li><a href="#fourth">Credentials</a></li>
                    <li><a href="#fifth">Files</a></li>
                </ul>
                <div id="first">
                    <div class="form manage-project">
                        <form id="project_desc" action="<?php echo $_admin->getUrl('admin/save-project.php')?>" method="post" name="">
                            <div class="field-row odd">
                                <div class="label"><label for="project_name">Project Name :</label></div>
                                <input type="text" id="project_name" class="field required" name="project_name">
                            </div>
                            <div class="field-row odd">
                                <div class="label"><label for="start_at">Start at :</label></div>
                                <input type="text" id="start_at" class="field required" name="start_at">
                            </div>
                            <div class="field-row even">
                                <div class="label"><label for="end_at">End at :</label></div>
                                <input type="text" id="end_at" class="field required" name="end_at">
                            </div>
                            <div class="field-row even">
                                <div class="label"><label for="deadline">Deadline :</label></div>
                                <input type="text" id="deadline" class="field required" name="deadline">
                            </div>
                            <div class="field-row odd">
                                <div class="label"><label for="meta_description">Assign To :</label></div>
                                <select multiple="multiple" class="required" name="assign_to[]">
                                    <?php foreach($users as $user): ?>
                                    <option value="<?php echo $user['user_id']?>"><?php echo $user['user_name']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="field-row clear meta-desc">
                                <div class="label"><label for="meta_description">Meta Description :</label></div>
                                <textarea name="meta_description" class="editor" id="meta_description" cols="50" rows="15"></textarea>
                            </div>
                            <div class="submit clear">
                                <input class="button" type="submit" name="submit" value="Submit" />
                            </div>
                        </form>
                    </div>
                </div>
                <div id="second">
                    <div class="paging">
                        <form action="" name="pagging" method="GET">
                            <strong>Items per Page :</strong>
                            <select onchange="pagging.submit()" name="limit">
                                <option selected="selected" value="10">10</option>
                                <option value="20" <?php if($limit==20):?>selected="selected"<?php endif;?> >20</option>
                                <option value="50" <?php if($limit==50):?>selected="selected"<?php endif;?>>50</option>
                                <option value="150">All</option>
                            </select>
                            <strong>Page :</strong>
                            <select onchange="pagging.submit()" name="page">
                                <?php $no_of_pages=$_core->getNumberOfPages($limit);
                                    $i=1;
                                    while($i<=$no_of_pages):
                                ?>
                                <option value="<?php echo $i;?>" <?php if($i==$page):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                                <?php $i++; endwhile;?>
                            </select>
                            <strong>Total Records : <?php echo $no_records?> out of <?php echo $total_records?></strong>
                        </form>
                    </div>
                    <div class="table-grid">
                        <table>
                            <colgroup></colgroup>
                            <colgroup></colgroup>
                            <colgroup></colgroup>
                            <colgroup></colgroup>
                            <colgroup span="3"></colgroup>
                            <colgroup span="2"></colgroup>
                            <colgroup></colgroup>
                            <thead>
                                <tr class="thead">
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Assign To</th>
                                    <th>Start At</th>
                                    <th>End At</th>
                                    <th>Deadline</th>
                                    <th>Created Time</th>
                                    <th>Updated Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach ($project_data as $data):
                                    extract($data);
                                ?>
                                    <tr class="<?php if($i%2==0) echo 'even'; else echo 'odd';?>">
                                        <td><?php echo $entity_id?></td>
                                        <td><?php echo $project_name?></td>
                                        <td><?php echo $_core->getShortString($meta_description)?></td>
                                        <td><?php $ids = $_core->explodeArray($assign_to);
                                            foreach($ids as $id)
                                                echo $_admin->getAssignedUserName($id)."<br />";
                                            ?>
                                        </td>
                                        <td><?php echo $assign_to?></td>
                                        <td><?php echo $_core->convertDateInLocalFormat($start_at)?></td>
                                        <td><?php echo $_core->convertDateInLocalFormat($end_at)?></td>
                                        <td><?php echo $_core->convertDateInLocalFormat($deadline)?></td>
                                        <td><?php echo $created_time?></td>
                                        <td><?php echo $updated_time?></td>
                                        <td class="edit-delete-user">
                                            <a href="javascript:popUpActivityType(<?php echo $entity_id?>)" title="edit activity type" ><img class="ico" src="<?php echo $_core->getSkinUrl('images/edit.png')?>" alt="edit" /></a>
                                            <a class="delete-activity-type" onclick="return confirmAction()" href="<?php echo $_core->getUrl('admin/manage-activity-type.php?id='.$entity_id)?>" title="delete activity type" ><img class="ico" src="<?php echo $_core->getSkinUrl('images/delete.png')?>" alt="delete" /></a>
                                        </td>
                                    </tr>
                                <?php $i++; endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="third">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
                <div id="fourth">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
                <div id="fifth">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
            </div>
        </div>
        <div id="blanket"></div>
        <div class="edit-project-desc">
            <div class="content"></div>
        </div>
</body>
</html>