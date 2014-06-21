<?php

$str = "{head}";

$pattern = "/{(?P<name>\w+)}/";

preg_match($pattern, $str, $sections);

print_r($sections);