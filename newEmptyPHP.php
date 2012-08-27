<div class="update-activity">
    <table border="0" width="100%">
        <tr class="first">
            <th>Time Slot</th>
            <th>Activity</th>
            <th>Edit</th>
        </tr>
        <div class="message"></div>
        <?php foreach ($slot_ids as $slot_id): /* Loop till end of availabe slots */ ?>
            <tr>
                <td><?php echo $slots[$slot_id]; ?></td>
                <td>
                    <?php echo $_user->getTruncateString($slot_id, $selected_date) . '...'; ?>
                </td>
                <td>
                    <a title="enter/edit activity" href="javascript:displayTab(<?php echo $slot_id ?>)"><img class="edit-image" src="<?php echo $_core->getSkinUrl('images/plus.png')?>" /></a>
                </td>
            </tr>
            <?php if($selected_date==$today_date):?>
            <tr id="tabs-content-<?php echo $slot_id ?>" class="tab-field">
                <td colspan="3">
                    <form action="" method="post" onsubmit="return activityEntry(<?php echo $slot_id ?>);">
                        <div>
                            <label id="label-<?php echo $slot_id ?>" for="activity-type-<?php echo $slot_id ?>" class="activity-type">Select Activity Type :</label>
                            <select id="activity-type-<?php echo $slot_id ?>">
                                <option id="0">Select type</option>
                                <?php
                                $i=0;
                                $selected_activity_id=$_user->getActivityTypeId($slot_id, $selected_date);
                                while ($i < $activity_counter):
                                ?>
                                <option id="<?php echo $activity_type_id[$i]; ?>" <?php if($activity_type_id[$i]==$selected_activity_id):?>selected="selected"<?php endif;?>)><?php echo $activity_types[$activity_type_id[$i]]; ?></option>
                                <?php $i++; endwhile;?>
                            </select>
                            <p><label>Add Activity :</label></p>
                            <textarea class="editor" id="activity-<?php echo $slot_id ?>" cols="50" rows="15"><?php echo $_user->getActivity($slot_id, $selected_date); ?></textarea>
                        </div>
                        <input type="submit" class="submit" value="submit" />
                        <a class="cancel-editor button" onclick="hideTab(<?php echo $slot_id ?>)"><span><img src="<?php echo $_core->getSkinUrl('images/cancel.png')?>" />cancel</span></a>
                    </form>
                </td>
            </tr>
            <?php else:?>
            <tr id="tabs-content-<?php echo $slot_id ?>" class="tab-field disabled">
                <td colspan="3">
                    <h5 class="activity-head">Activity :</h5>
                    <p class="activity-desc"><?php echo $_user->getActivity($slot_id, $selected_date); ?></p>
                    <a class="cancel-editor button" onclick="hideTab(<?php echo $slot_id ?>)"><span><img src="<?php echo $_core->getSkinUrl('images/minus.png')?>" />hide</span></a>
                </td>
            </tr>
            <?php endif;?>
    <?php endforeach; ?>
    </table>
    <div class="home-timeline"></div>
</div>