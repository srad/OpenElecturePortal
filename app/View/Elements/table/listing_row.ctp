<tr id="list_<?php echo $video['Lecture']['id']; ?>" class="">
    <td class="center"><span class="icon-resize-vertical"></span></td>
    <td class="center"><span class="icon-arrow-left"></span></td>
    <td class="center"><span class="icon-arrow-right"></span></td>
    <td><input class="name" type="text" value="<?php echo $video['Lecture']['name']; ?>" /></td>
    <td><input class="code" type="text" value="<?php echo $video['Lecture']['code']; ?>" /></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Deaktiv.'); ?></label></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Dyn. expad.'); ?></label></td>
    <td><label class="checkbox"><input type="checkbox" /> <?php echo __('Aufst. Sort.'); ?></label></td>
    <td class="center"><button class="btn btn-mini"><?php echo __('Hinzuf.'); ?></button></td>
    <td class="center"><button class="btn btn-danger btn-mini"><?php echo __('Lösch.'); ?></button></td>
</tr>