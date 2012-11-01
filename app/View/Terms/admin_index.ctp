<div class="span11">
    <div class="hero-unit">
        <?php echo $this->Form->create('Term', array('class' => 'form', 'action' => 'add')); ?>
        <fieldset>
            <legend><?php echo __('Semester anlegen'); ?></legend>
            <label><?php echo __('Semester-Name'); ?></label>
            <?php echo $this->Form->input('name', array('label' => false, 'div' => false, 'required' => true)); ?>
            <br/>
            <button type="submit" class="btn btn-primary"><?php echo __('Speichern'); ?></button>
        </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>

    <?php echo $this->Form->create('terms', array('class' => 'form hero-unit', 'action' => 'edit')); ?>
    <fieldset>

        <legend>
            <?php echo __('Angezeigte Reihenfolge'); ?>
            <p class="text-info"><?php echo __('Umsortierungen werden sofort gespeichert, bei Fehlern werden Sie benachrichtigt'); ?></p>
        </legend>

        <ul id="termList">
            <?php foreach ($terms as $termId => $termName): ?>
            <li id="term_<?php echo $termId; ?>" class="form-inline span6">
                <?php echo $this->Form->hidden('Term.' . $termId . '.id', array('value' => $termId)); ?>
                <?php echo $this->Form->input('Term.' . $termId . '.name', array('class' => 'span4', 'value' => $termName, 'label' => false, 'div' => false, 'required' => true)); ?>
                <button type="button" class="btn btn-danger btn-small"><?php echo __('Löschen'); ?></button>
                <span class="pull-right mover icon-move"></span>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-actions">
            <input type="submit" class="btn btn-primary" value="<?php echo __('Speichern'); ?>"/>
        </div>

    </fieldset>
    <?php echo $this->Form->end(); ?>
</div>

<style>
    ul#termList {
        display: table;
        list-style: decimal outside none;
    }

    ul#termList li input {
        margin-bottom: 0;
    }

    ul#termList li span {
        margin: 8px 6px;
    }

    ul#termList li {
        padding: 6px;
        margin-bottom: 5px;
        border: 1px solid lightgrey;
        border-radius: 3px;
        cursor: move;

        background: #f9fcf7;
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2Y5ZmNmNyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNWY5ZjAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(top, #f9fcf7 0%, #f5f9f0 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f9fcf7), color-stop(100%, #f5f9f0));
        background: -webkit-linear-gradient(top, #f9fcf7 0%, #f5f9f0 100%);
        background: -o-linear-gradient(top, #f9fcf7 0%, #f5f9f0 100%);
        background: -ms-linear-gradient(top, #f9fcf7 0%, #f5f9f0 100%);
        background: linear-gradient(to bottom, #f9fcf7 0%, #f5f9f0 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '#f9fcf7', endColorstr = '#f5f9f0', GradientType = 0);
    }
</style>

<script>
    $(function () {
        UFM.ep.terms = {};

        $("#termList").sortable({
            update: function (ui, item) {
                $.ajax({
                    type: 'POST',
                    url: UFM.ep.here + 'sort.json',
                    data: $("#termList").sortable('serialize'),
                    dataType: 'json',
                    success: function (response) {
                    },
                    error: function (response) {
                        alert(JSON.stringify(response));
                    }
                })
            }
        });

        UFM.ep.terms.remove = function (id) {
            var action = UFM.ep.here + 'delete/' + id;

            if (window.confirm('<?php echo __('Soll dieses Semester gelöscht werden?'); ?>')) {
                $('<form style="display:none;" action="' + action + '" method="POST"></form>').appendTo('body').submit();
            }
        }

        $('#termList li .btn-danger').click(function (event) {
            event.preventDefault();
            var elementId = $(this).parent('li').attr('id'),
                    id = elementId.split('_')[1];

            UFM.ep.terms.remove(id);
        });
    });
</script>