<div class="row">
    <div class="col-12">
        <?= ViewProvider::form([
            "fields" => [
                "something"    => [ "type" => "text", "label" => "Someting as text", "value" => "A value" ],
                "otherthing"   => [ "type" => "email", "label" => "other as email" ],
                "onemore"      => [ "type" => "file", "label" => "one as file", "multiple" => true ],
                "datefield"    => [ "type" => "date", "label" => "another as date" ],
                "selectfield" => [ "type" => "select", "label" => "Select field", "options" => [
                        [ "value" => "One", "label" => "Select the one" ],
                        [ "value" => "Two", "selected" => true ]
                    ]
                ],
                "multiple"    => [ "type" => "select", "multiple" => true, "label" => "Multiple Select field", "options" => [
                        [ "value" => "One", "label" => "Select the one" ],
                        [ "value" => "Two", "selected" => true ]
                    ]
                ],
                "checkbox"     => [ "type" => "checkbox", "label" => "Checkboxes", "options" => [
                        [ "name" => "checkbox01", "label" => "Check box 1" ],
                        [ "name" => "checkbox02", "label" => "Check box 2" ],
                        [ "name" => "checkbox03", "checked" => true ],
                    ]
                ],
                "radiofield"   => [ "type" => "radio", "label" => "Radio fields", "options" => [
                        [ "value" => "radio1", "label" => "Radio button 1", "checked" => true ],
                        [ "value" => "radio3", "label" => "Radio button 3" ],
                        [ "value" => "radio3" ]
                    ]
                ],
                "summernote"    => [ "type" => "wysiwyg", "label" => "Summernote" ]
            ],
            "attributes"  => [
                "action"  => "create",
                "method"  => "post"
            ],
            "title"       => "This a generated form",
            "description" => "This the description text used to help user in the form fields understanding.",
            "buttons"     => [
                "submit" => [
                    "text" => "Confirm",
                    "type" => "submit"
                ]
            ]
        ]); ?>
    </div>
</div>