<?php

namespace Tests\Unit\infrastructure\vote;

use PHPUnit\Framework\TestCase;
use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\vote\NonScore;
use siesta\domain\vote\Score;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;
use siesta\infrastructure\vote\persistence\EloquentScoreTransformer;

class EloquentScoreTransformerTest extends TestCase
{

    /** @var EloquentScoreTransformer */
    private $_transformer;

    /**
     * @dataProvider scoreTransformations
     * @param Score $domain
     * @param int $persistence
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws VoteInvalidTypeException
     */
    public function testDomainToPersistenceIsCorrectlyTransformer($domain, $persistence)
    {
        $result = $this->_transformer->fromDomainToPersistence($domain);
        $this->assertEquals($persistence, $result);
    }

    public function testWhenNoValidScoreThrowsException()
    {
        try {

            $this->_transformer->fromDomainToPersistence(FakeScore::get());
            $this->fail('Should throw VoteInvalidTypeException');
        } catch (VoteInvalidTypeException $e) {
            $this->assertTrue(true);
        }

        try {

            $this->_transformer->fromDomainToPersistence(NonScore::get());
            $this->assertTrue(true);
        } catch (VoteInvalidTypeException $e) {
            $this->fail('Shouldn\'t throw VoteInvalidTypeException');
        }
    }


    /**
     * @return array
     */
    public function scoreTransformations(): array
    {
        return [
            [NonScore::get(), 0],
            [WeakScore::get(), 1],
            [StrongScore::get(), 2],
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->_transformer = new EloquentScoreTransformer();
    }

}
