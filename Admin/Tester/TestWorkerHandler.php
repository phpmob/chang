<?php

declare(strict_types=1);

namespace Chang\Admin\Tester;

class TestWorkerHandler
{
    public function __invoke(TestWorkerMessage $message)
    {
        dump('Worker Handle');
        //dump($message);
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    }
}
