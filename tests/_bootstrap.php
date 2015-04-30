<?php
// Ensure Vreasy/Test namespace is on include_path
set_include_path(implode(
    PATH_SEPARATOR,
    [realpath('tests/_data'), get_include_path()]
));
