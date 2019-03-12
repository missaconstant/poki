<div class="card">
    <div class="card-body">
        
        <?php if ($formtitle): ?>
            <h5 class="card-title"><?= $formtitle ?></h5>
        <?php endif; if ($formdesc): ?>
            <p class="text-muted m-b-30"> <?= $formdesc ?> </p>
        <?php endif; ?>

        <form action="<?= $action ?>" <?= $attrs ?>>
            
            <?php foreach ($lines as $k => $line): $line = (object) $line; ?>

            <div class="form-group">
                <?php if (!isset($line->no_label)): ?>
                    <label> <?= isset($line->label) ? $line->label : $line->name ?> </label>
                <?php endif; ?>

                <?php if (in_array($line->type, ['text', 'email', 'date', 'number', 'file'])): ?>
                    <!--  -->
                    <input type="<?= $line->type ?>" name="<?= $k . (isset($line->multiple) && $line->multiple ? '[]':'') ?>" id="<?= isset($line->id) ? $line->id:'' ?>" value="<?= isset($line->value) ? $line->value:'' ?>" <?= isset($line->multiple) && $line->multiple ? 'multiple':'' ?> class="form-control">
                    <!--  -->
                <?php elseif ($line->type == 'checkbox'): ?>
                    <!--  --> 
                    <br>
                    <?php foreach ($line->options as $p => $option): ?>
                        <input type="checkbox" name="<?= $option['name'] ?>" id="<?= isset($option['id']) ? $option['id']:'' ?>" class="" <?= isset($option['checked']) && $option['checked'] ? 'checked':'' ?>>
                        <?= isset($option['label']) ? $option['label'] : $option['name'] ?> <br>
                    <?php endforeach; ?>
                    <!--  -->
                <?php elseif ($line->type == 'radio'): ?>
                    <!--  --> 
                    <br>
                    <?php foreach ($line->options as $p => $option): ?>
                        <input type="radio" name="<?= $k ?>" id="<?= isset($option['id']) ? $option['id']:'' ?>" value="<?= isset($option['value']) ? $option['value']:'' ?>" class="" <?= isset($option['checked']) && $option['checked'] ? 'checked':'' ?>>
                        <?= isset($option['label']) ? $option['label'] : $option['value'] ?> <br>
                    <?php endforeach; ?>
                    <!--  -->
                <?php elseif ($line->type == 'select'): ?>
                    <!--  -->
                    <select name="<?= $k . (isset($line->multiple) && $line->multiple ? '[]':'') ?>" id="<?= isset($line->id) ? $line->id:'' ?>" <?= isset($line->multiple) && $line->multiple ? 'multiple':'' ?> class="form-control">
                        <?php foreach ($line->options as $p => $option): ?>
                            <option value="<?= $option['value'] ?>" <?= isset($option['selected']) && $option['selected'] ? 'selected':'' ?>><?= isset($option['label']) ? $option['label'] : $option['value'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!--  -->
                <?php elseif ($line->type == 'wysiwyg'): ?>
                    <!--  -->
                    <div class="summernote">Something</div>
                    <!--  -->
                <?php else: ?>
                    <!--  -->
                    <input type="text" name="<?= $k ?>" id="<?= isset($line->id) ? $line->id:'' ?>" value="<?= isset($line->value) ? $line->value:'' ?>" class="form-control">
                    <!--  -->
                <?php endif; ?>

            </div>

            <?php endforeach; ?>

            <div class="form-group">
                <button type="submit" class="btn btn-success"> <?= $buttons->submit['text'] ?> </button>
            </div>

        </form>

    </div>
</div>