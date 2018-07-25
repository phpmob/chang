<?php

declare(strict_types=1);

namespace Chang\Paginate;

use Pagerfanta\View\Template\DefaultTemplate;

class SelectTemplate extends DefaultTemplate
{
    static protected $defaultOptions = array(
        'css_dots_class' => '',
        'css_current_class' => 'selected',
        'dots_text' => '...',
        'container_template' => '<select class="custom-select custom-select-sm" data-select-href>%pages%</select>',
        'page_template' => '<option data-url="%href%" value="%text%">%text%</option>',
        'span_template' => '<option %class% value="%text%">%text%</option>',
    );

    public function previousDisabled()
    {
        return;
    }

    public function previousEnabled($page)
    {
        return;
    }

    public function nextDisabled()
    {
        return;
    }

    public function nextEnabled($page)
    {
        return;
    }
}
