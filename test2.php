<?php
    include 'poki.accessor.php';

    if (isset($_POST['category'])) {
        $return = Poki::push();
    }