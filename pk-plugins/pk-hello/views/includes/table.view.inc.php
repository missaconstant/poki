<div class="row">
    <div class="col-12">

        <?= ViewProvider::table([
            "title"   => "Show Table",
            "description" => "This a table to show you how to use this kind of things",
            "headers" => [ ["label" => "One", "key" => "two"], ["label" => "Three", "key" => "four"] ],
            "lines"   => [
                ["two" => "Something", "four" => "I dont know"],
                ["two" => "Other thing", "four" => "I dont really know"],
                ["two" => "Another thing", "four" => "Finally, I dont know"]
            ]
        ]) ?>

    </div>
</div>

<br>

<div class="row">
    <div class="col-12">

        <?= ViewProvider::table([
            "title"       => "Data Table",
            "description" => "This a table to show you how to use this kind of things",
            "headers"     => [ ["label" => "One", "key" => "two"], ["label" => "Three", "key" => "four"] ],
            "datatable"   => true,
            "lines"       => [
                ["two" => "Something", "four" => "I dont know"],
                ["two" => "Other thing", "four" => "I dont really know"],
                ["two" => "Another thing", "four" => "Finally, I dont know"]
            ],
        ]) ?>

    </div>
</div>