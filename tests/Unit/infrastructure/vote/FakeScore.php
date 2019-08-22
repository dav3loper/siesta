<?php
namespace Tests\Unit\infrastructure\vote;

use siesta\domain\Singleton;
use siesta\domain\vote\Score;

/**
 * Class FakeScore
 * @package Tests\Unit\infrastructure\vote
 */
class FakeScore extends Singleton implements Score
{
}