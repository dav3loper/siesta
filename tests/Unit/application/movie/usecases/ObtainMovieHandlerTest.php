<?php

namespace Tests\Unit\application\movie\usecases;

use siesta\application\movie\usecases\ObtainMovieCommand;
use siesta\application\movie\usecases\ObtainMovieHandler;
use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\vote\infrastructure\VoteProvider;
use Tests\Helpers\DomainGenerator;

class ObtainMovieHandlerTest extends \Tests\TestCase
{

    /** @var MovieProvider|\PHPUnit\Framework\MockObject\MockObject */
    private $_movieProvider;
    /** @var ObtainMovieHandler */
    private $_useCase;


    public function testRepositoryIsCalled()
    {
        $this->_movieProvider->expects($this->once())
            ->method('getMovieById')
            ->willThrowException(new \Exception('repository is called'));
        try {
            $command = new ObtainMovieCommand();
            $command->setId(1);
            $this->_useCase->execute($command);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertEquals('repository is called', $e->getMessage());
        }
    }

    public function testMovieObjectFromProviderIsGiven()
    {
        $movie = DomainGenerator::givesMovie();
        $this->_movieProvider->expects($this->once())
            ->method('getMovieById')
            ->willReturn($movie);

        $command = new ObtainMovieCommand();
        $command->setId(1);
        $result = $this->_useCase->execute($command);

        $this->assertEquals($movie, $result);
    }

    protected function setUp()
    {
        parent::setUp();

        /** @var MovieProvider $movieProvider */
        $this->_movieProvider = $movieProvider = $this->getMockBuilder(MovieProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var VoteProvider $voteProvider */
        $voteProvider = $this->getMockBuilder(VoteProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_useCase = new ObtainMovieHandler($movieProvider, $voteProvider);
    }

}
