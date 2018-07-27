<?php

declare(strict_types=1);

namespace Chang\Admin\Tester;

class TestSocketHandler
{
    public function __invoke(TestSocketMessage $message)
    {
        dump('Socket Handle');
        //dump($message);
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    }
}
