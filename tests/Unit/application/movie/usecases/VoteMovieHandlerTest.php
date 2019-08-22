<?php

namespace Tests\Unit\application\movie\usecases;

use siesta\application\movie\usecases\VoteMovieCommand;
use siesta\application\movie\usecases\VoteMovieHandler;
use siesta\domain\exception\MissingParameterException;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\infrastructure\VoteRecorder;
use siesta\domain\vote\NonScore;
use siesta\domain\vote\StrongScore;
use siesta\domain\vote\Vote;
use siesta\domain\vote\WeakScore;

class VoteMovieHandlerTest extends \Tests\TestCase
{


    /** @var VoteMovieHandler */
    private $_useCase;
    /** @var  VoteRecorder|\PHPUnit\Framework\MockObject\MockObject */
    private $_voteRecorder;

    public function testRepositoryIsCalled()
    {
        $this->_voteRecorder->expects($this->once())
            ->method('store')
            ->willThrowException(new \Exception('repository is called'));
        try {
            $command = new VoteMovieCommand();
            $command->setId(1);
            $command->setIndividualVote([new IndividualVote(WeakScore::get(), 1)]);
            $this->_useCase->execute($command);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertEquals('repository is called', $e->getMessage());
        }
    }

    public function testParamsAreOk()
    {
        $command = new VoteMovieCommand();
        try {
            $this->_useCase->execute($command);
            $this->fail('Should throw MissingParameterException');
        } catch (MissingParameterException $e) {
            $this->assertEquals('Missing parameter: id', $e->getMessage());
        }
        try {
            $command->setId(1);
            $this->_useCase->execute($command);
            $this->fail('Should throw MissingParameterException');
        } catch (MissingParameterException $e) {
            $this->assertEquals('Missing parameter: votes', $e->getMessage());
        }
    }

    public function testVoteIsCorrectlyTransformed()
    {
        $this->_voteRecorder->expects($this->once())
            ->method('store')
            ->with($this->callback(
                function (Vote $vote) {
                    return $vote->getMovieId() === 1 &&
                        $vote->getIndividualVoteList()[0]->getScore() === WeakScore::get() &&
                        $vote->getIndividualVoteList()[1]->getScore() === StrongScore::get() &&
                        $vote->getIndividualVoteList()[2]->getScore() === NonScore::get() &&
                        $vote->getIndividualVoteList()[0]->getUserId() === 1 &&
                        $vote->getIndividualVoteList()[1]->getUserId() === 2 &&
                        $vote->getIndividualVoteList()[2]->getUserId() === 4;
                }
            ));
        $command = new VoteMovieCommand();
        $command->setId(1);
        $command->setIndividualVote([
            'user_1' => 1,
            'user_2' => 2,
            'user_4' => 0
        ]);
        $this->_useCase->execute($command);
    }

    protected function setUp()
    {
        parent::setUp();

        /** @var VoteRecorder $voteRecorder */
        $this->_voteRecorder = $voteRecorder = $this->getMockBuilder(VoteRecorder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_useCase = new VoteMovieHandler($voteRecorder);
    }
}
