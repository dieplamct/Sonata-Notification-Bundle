<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\NotificationBundle\Iterator;

use Sonata\NotificationBundle\Model\MessageInterface;
use Sonata\NotificationBundle\Model\MessageManagerInterface;

/**
 * @final since sonata-project/notification-bundle 3.13
 */
class ErroneousMessageIterator extends MessageManagerMessageIterator
{
    /**
     * @var int
     */
    protected $maxAttempts;

    /**
     * @var int
     */
    protected $attemptDelay;

    /**
     * @param array $types
     * @param int   $pause
     * @param int   $batchSize
     * @param int   $maxAttempts
     * @param int   $attemptDelay
     */
    public function __construct(MessageManagerInterface $messageManager, $types = [], $pause = 500000, $batchSize = 10, $maxAttempts = 5, $attemptDelay = 10)
    {
        parent::__construct($messageManager, $types, $pause, $batchSize);

        $this->maxAttempts = $maxAttempts;
        $this->attemptDelay = $attemptDelay;
    }

    /**
     * Find messages in error.
     *
     * @param array $types
     *
     * @return mixed
     */
    protected function findNextMessages($types)
    {
        return $this->messageManager->findByAttempts(
            $this->types,
            MessageInterface::STATE_ERROR,
            $this->batchSize,
            $this->maxAttempts,
            $this->attemptDelay
        );
    }
}
