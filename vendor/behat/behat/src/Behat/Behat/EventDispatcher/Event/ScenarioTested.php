<?php

/*
 * This file is part of the Behat.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Behat\EventDispatcher\Event;

use Behat\Testwork\EventDispatcher\Event\LifecycleEvent;

/**
 * Represents a scenario event.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
abstract class ScenarioTested extends LifecycleEvent implements ScenarioLikeTested
{
    const BEFORE = 'tester.scenario_tested.before';
    const AFTER_SETUP = 'tester.scenario_tested.after_setup';
    const BEFORE_TEARDOWN = 'tester.scenario_tested.before_teardown';
    const AFTER = 'tester.scenario_tested.after';

    /**
     * {@inheritdoc}
     */
    final public function getNode()
    {
        return $this->getScenario();
    }
}
