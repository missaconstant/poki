<div class="card">
    <div class="card-body">

        <?php if ($formtitle): ?>
            <h5 class="card-title"><?= $formtitle ?></h5>
        <?php endif; if ($formdesc): ?>
            <p class="text-muted m-b-30"> <?= $formdesc ?> </p>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover <?= $datatable ?>" <?= $attrs ?>>
                <thead>
                    <tr>
                    <?php foreach ($headers as $k => $th): ?>
                        <th> <?= $th['label'] ?> </th>
                    <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lines as $k => $line): ?>
                    <tr>
                    <?php foreach ($headers as $t => $td): ?>
                        <td> <?= $line[$td['key']] ?> </td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>