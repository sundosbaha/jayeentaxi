<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Behat\Context\Snippet\Appender;

use Behat\Behat\Snippet\AggregateSnippet;
use Behat\Behat\Snippet\Appender\SnippetAppender;
use Behat\Testwork\Filesystem\FilesystemLogger;
use ReflectionClass;

/**
 * Appends context-related snippets to their context classes.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
final class ContextSnippetAppender implements SnippetAppender
{
    /**
     * @const PendingException class
     */
    const PENDING_EXCEPTION_CLASS = 'Behat\Behat\Tester\Exception\PendingException';

    /**
     * @var FilesystemLogger
     */
    private $logger;

    /**
     * Initializes appender.
     *
     * @param null|FilesystemLogger $logger
     */
    public function __construct(FilesystemLogger $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsSnippet(AggregateSnippet $snippet)
    {
        return 'context' === $snippet->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function appendSnippet(AggregateSnippet $snippet)
    {
        foreach ($snippet->getTargets() as $contextClass) {
            $reflection = new ReflectionClass($contextClass);
            $content = file_get_contents($reflection->getFileName());

            if (!$this->isPendingExceptionImported($content)) {
                $content = $this->importPendingException($content);
            }

            $generated = rtrim(strtr($snippet->getSnippet(), array('\\' => '\\\\', '$' => '\\$')));
            $content = preg_replace('/}\s*$/', "\n" . $generated . "\n}\n", $content);
            $path = $reflection->getFileName();

            file_put_contents($path, $content);

            $this->logSnippetAddition($snippet, $path);
        }
    }

    /**
     * Checks if context file already has pending exception in it.
     *
     * @param string $contextFileContent
     *
     * @return Boolean
     */
    private function isPendingExceptionImported($contextFileContent)
    {
        $pendingExceptionImportRegex = sprintf(
            '@use[^;]*%s.*;@ms',
            preg_quote(self::PENDING_EXCEPTION_CLASS, '@')
        );

        return 1 === preg_match($pendingExceptionImportRegex, $contextFileContent);
    }

    /**
     * Adds use-block for pending exception.
     *
     * @param string $contextFileContent
     *
     * @return string
     */
    private function importPendingException($contextFileContent)
    {
        $replaceWith = "\$1" . 'use ' . self::PENDING_EXCEPTION_CLASS . ";\n\$2;";

        return preg_replace('@^(.*)(use\s+[^;]*);@m', $replaceWith, $contextFileContent, 1);
    }

    /**
     * Logs snippet addition to the provided path (if logger is given).
     *
     * @param AggregateSnippet $snippet
     * @param string           $path
     */
    private function logSnippetAddition(AggregateSnippet $snippet, $path)
    {
        if (!$this->logger) {
            return;
        }

        $steps = $snippet->getSteps();
        $reason = sprintf("`<comment>%s</comment>` definition added", $steps[0]->getText());

        $this->logger->fileUpdated($path, $reason);
    }
}
