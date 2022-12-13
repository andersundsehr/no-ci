<?php

declare(strict_types=1);

namespace AUS\NoCi;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use OndraM\CiDetector\CiDetector;
use Composer\EventDispatcher\Event as BaseEvent;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'no-ci' => 'noCi',
            'ci' => 'ci',
        ];
    }

    public function noCi(Event $event): void
    {
        if ($this->isCi()) {
            $event->getIO()->write('<warning>CI detected, skipping: ' . implode(' ', $event->getArguments()) . '</warning>');
            return;
        }
        $this->runOriginalScript($event);
    }

    public function ci(Event $event): void
    {
        if (!$this->isCi()) {
            $event->getIO()->write('<warning>no CI detected, skipping: ' . implode(' ', $event->getArguments()) . '</warning>');
            return;
        }
        $this->runOriginalScript($event);
    }

    private function runOriginalScript(Event $event): void
    {
        $eventName = 'run-@no-ci-' . implode('-', $event->getArguments());
        $dispatcher = $event->getComposer()->getEventDispatcher();
        $childEvent = new Event($eventName, $event->getComposer(), $event->getIO(), $event->isDevMode(), [], $event->getFlags());
        if (!$dispatcher->hasEventListeners($childEvent)) {
            $dispatcher->addListener($eventName, implode(' ', $event->getArguments()));
        }
        $dispatcher->dispatch(null, $childEvent);
    }

    public function activate(Composer $composer, IOInterface $io)
    {
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    private function isCi(): bool
    {
        return (new CiDetector())->isCiDetected();
    }
}
