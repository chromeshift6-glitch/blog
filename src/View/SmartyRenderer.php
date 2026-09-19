<?php

declare(strict_types=1);

namespace App\View;

use Smarty\Smarty;

final class SmartyRenderer
{
    public function __construct(private readonly Smarty $smarty)
    {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): string
    {
        $view = $this->smarty->createTemplate($template);
        $view->assign($data);

        return $view->fetch();
    }
}
