<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
    http://localhost:8000/index_test.php/api/kommentare => set ENV test

return function (array $context) {
    return new Kernel('test',true);
};
