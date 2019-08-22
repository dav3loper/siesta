<?php


use siesta\application\movie\usecases\StoreMovieCommand;
use siesta\application\movie\usecases\StoreMovieHandler;
use siesta\domain\movie\infrastructure\MovieRecorder;
use siesta\domain\movie\Movie;

/**
 * Class StoreMovieUseCaseTest
 */
class StoreMovieHandlerTest extends \Tests\TestCase
{

    /** @var StoreMovieHandler */
    private $_useCase;
    /** @var MovieRecorder|\PHPUnit\Framework\MockObject\MockObject */
    private $_movieRecorder;

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRepositoryIsCalled()
    {
        $this->_movieRecorder->expects($this->once())
            ->method('store')
            ->willThrowException(new \Exception('repository is called'));
        try {
            $data = $this->_getValidJson();
            $command = StoreMovieCommand::buildFromJsonData($data);
            $this->_useCase->execute($command);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertEquals('repository is called', $e->getMessage());
        }
    }

    /**
     * @return string
     */
    private function _getValidJson(): string
    {
        $data = [
            'title' => 'Película de prueba',
            'summary' => 'Esta película trata de una prueba en phpunit',
            'poster' => 'http://url/to/the/poster',
            'duration' => 90,
            'trailer' => 'randomYoutubeId',

        ];

        return json_encode($data);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testWhenDataAreIncorrectThrowException()
    {
        try {
            $data = $this->_getInvalidData();
            $command = StoreMovieCommand::buildFromJsonData(json_encode($data));
            $this->_useCase->execute($command);
            $this->fail('Should throw exception: wrong duration');
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->assertTrue(true);
        }

        try {
            $data['duration'] = 90;
            $command = StoreMovieCommand::buildFromJsonData(json_encode($data));
            $this->_useCase->execute($command);
            $this->fail('Should throw exception: wrong summary');
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->assertTrue(true);
        }
        try {
            $data['summary'] = 'Summary valido';
            $command = StoreMovieCommand::buildFromJsonData(json_encode($data));
            $this->_useCase->execute($command);
            $this->fail('Should throw exception: wrong poster');
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->assertTrue(true);
        }

        try {
            $data['poster'] = 'http://www.estasivale.com';
            $command = StoreMovieCommand::buildFromJsonData(json_encode($data));
            $this->_useCase->execute($command);
            $this->fail('Should throw exception wrong title');
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->assertTrue(true);
        }

        try {
            $data['title'] = 'Peli molona';
            $command = StoreMovieCommand::buildFromJsonData(json_encode($data));
            $this->_useCase->execute($command);
            $this->assertTrue(true);
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->fail('Shouldn\'t throw exception');
        }

        try {
            $data = $this->_getValidJson();
            $command = StoreMovieCommand::buildFromJsonData($data);
            $this->_useCase->execute($command);
        } catch (\siesta\application\exception\WrongInputException $e) {
            $this->fail('Should throw exception');
        }
    }

    /**
     * @return array
     */
    private function _getInvalidData(): array
    {
        $data = [
            'title' => '',
            'summary' => '',
            'poster' => '',
            'duration' => 'blabla',
            'trailer' => '',

        ];

        return $data;
    }

    /**
     * @throws \siesta\application\exception\WrongInputException
     */
    public function testMovieIsCorrectlyTransformed()
    {
        $this->_movieRecorder->expects($this->once())
            ->method('store')
            ->with($this->callback(
                function (Movie $movie) {
                    return $movie->getDuration() === 90 &&
                        $movie->getTitle() === 'Película de prueba' &&
                        $movie->getPoster() === 'http://url/to/the/poster' &&
                        $movie->getSummary() === 'Esta película trata de una prueba en phpunit';
                }
            ));


        $data = $this->_getValidJson();
        $command = StoreMovieCommand::buildFromJsonData($data);
        $this->_useCase->execute($command);
    }

    protected function setUp()
    {
        parent::setUp();

        /** @var MovieRecorder $movieRecorder */
        $this->_movieRecorder = $movieRecorder = $this->getMockBuilder(MovieRecorder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_useCase = new StoreMovieHandler($movieRecorder);
    }

}
