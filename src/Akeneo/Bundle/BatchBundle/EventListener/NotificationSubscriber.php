<?php

namespace Akeneo\Bundle\BatchBundle\EventListener;

use Akeneo\Bundle\BatchBundle\Event\EventInterface;
use Akeneo\Bundle\BatchBundle\Event\JobExecutionEvent;
use Akeneo\Bundle\BatchBundle\Notification\Notifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Job execution notifier
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    protected $notifiers = array();

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            EventInterface::AFTER_JOB_EXECUTION => 'afterJobExecution',
        );
    }

    /**
     * Register a notifier
     *
     * @param Notifier $notifier
     */
    public function registerNotifier(Notifier $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * Get the registered notifiers
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * Use the notifiers to notify
     *
     * @param JobExecutionEvent $jobExecutionEvent
     */
    public function afterJobExecution(JobExecutionEvent $jobExecutionEvent)
    {
        $jobExecution = $jobExecutionEvent->getJobExecution();

        foreach ($this->notifiers as $notifier) {
            $notifier->notify($jobExecution);
        }
    }
}
