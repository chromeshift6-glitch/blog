<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;
use Smarty\Smarty;

final class SmartyFactory
{
    public function __construct(private readonly string $projectRoot)
    {
    }

    public function create(): Smarty
    {
        $runtimeDirectory = getenv('SMARTY_RUNTIME_DIR');
        if ($runtimeDirectory === false || $runtimeDirectory === '') {
            $runtimeDirectory = sys_get_temp_dir()
                . '/blog-smarty-'
                . substr(hash('sha256', $this->projectRoot), 0, 12);
        }

        $compileDirectory = $runtimeDirectory . '/templates_c';
        $cacheDirectory = $runtimeDirectory . '/cache';

        $this->ensureDirectoryExists($compileDirectory);
        $this->ensureDirectoryExists($cacheDirectory);

        $smarty = new Smarty();
        $smarty->setTemplateDir($this->projectRoot . '/templates');
        $smarty->setCompileDir($compileDirectory);
        $smarty->setCacheDir($cacheDirectory);
        $smarty->setEscapeHtml(true);
        $smarty->registerPlugin('modifier', 'format_date', [
            TemplateModifiers::class,
            'formatDate',
        ]);
        $smarty->registerPlugin('modifier', 'views_label', [
            TemplateModifiers::class,
            'viewsLabel',
        ]);

        return $smarty;
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException("Unable to create directory {$directory}");
        }

        if (!is_writable($directory)) {
            throw new RuntimeException("Directory {$directory} is not writable");
        }
    }
}
