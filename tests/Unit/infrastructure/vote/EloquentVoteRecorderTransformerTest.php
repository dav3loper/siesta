<?php

namespace Tests\Unit\infrastructure\vote;

use PHPUnit\Framework\TestCase;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\WeakScore;
use siesta\infrastructure\vote\persistence\EloquentScoreTransformer;
use siesta\infrastructure\vote\persistence\EloquentVoteSerializedTransformer;
use Tests\Helpers\DomainGenerator;

/**
 * Class EloquentVoteRecorderTransformerTest
 * @package Tests\Unit\infrastructure\vote
 */
class EloquentVoteRecorderTransformerTest extends TestCase
{

    /** @var EloquentScoreTransformer|\PHPUnit\Framework\MockObject\MockObject */
    private $_mockScoreTransformer;
    /** @var EloquentVoteSerializedTransformer */
    private $_transformer;

    public function testScoreTransformerIsCalled()
    {
        $this->_mockScoreTransformer->expects($this->once())
            ->method('fromDomainToPersistence')
            ->willThrowException(new \Exception('repository is called'));

        try {
            $vote = DomainGenerator::givesVote();
            $this->_transformer->getSerializedVotes($vote->getIndividualVoteList());
            $this->fail('Should throw Exception');
        } catch (\Exception $e) {
            $this->assertEquals('repository is called', $e->getMessage());
        }
    }

    public function testScoreTransformerIsCalledWithCorrectData()
    {
        $this->_mockScoreTransformer->expects($this->once())
            ->method('fromDomainToPersistence')
            ->willReturnCallback(function ($arg) {
                if ($arg !== WeakScore::get()) {
                    throw new \Exception('incorrect argument');
                }
                throw new \Exception('correct argument');
            });

        try {
            $vote = DomainGenerator::givesVote();
            $vote->setIndividualVoteList([new IndividualVote(WeakScore::get(), 1)]);
            $this->_transformer->getSerializedVotes($vote->getIndividualVoteList());
            $this->fail('Should throw Exception');
        } catch (\Exception $e) {
            $this->assertEquals('correct argument', $e->getMessage());
        }
    }

    public function testIndividualVoteListIsCorrectlyTransformed()
    {
        $this->_mockScoreTransformer->method('fromDomainToPersistence')
            ->willReturnOnConsecutiveCalls(1, 2);

        $vote = DomainGenerator::givesVote();
        $vote->setIndividualVoteList([new IndividualVote(WeakScore::get(), 1), new IndividualVote(StrongScore::get(), 52)]);
        $result = $this->_transformer->getSerializedVotes($vote->getIndividualVoteList());

        $expected = [
            ['userId' => 1, 'score' => 1],
            ['userId' => 52, 'score' => 2],
        ];
        $this->assertEquals(json_encode($expected), $result);
    }

    protected function setUp()
    {
        parent::setUp();
        /** @var EloquentScoreTransformer $scoreTransformer */
        $this->_mockScoreTransformer = $scoreTransformer = $this->getMockBuilder(EloquentScoreTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_transformer = new EloquentVoteSerializedTransformer($scoreTransformer);
    }

}
