<?php

namespace Tests\Unit\App\Helpers;

use App\Helpers\YoutubeFinderVideoService;
use Google_Service_YouTube;
use Google_Service_YouTube_Resource_Search;
use Google_Service_YouTube_SearchListResponse;
use Google_Service_YouTube_SearchResult;

/**
 * Class YoutubeFinderVideoServiceTest
 * @package Tests\Unit\App\Helpers
 */
class YoutubeFinderVideoServiceTest extends \Tests\TestCase
{

    /** @var YoutubeFinderVideoService */
    private $_finder;
    /** @var Google_Service_YouTube_Resource_Search|\PHPUnit\Framework\MockObject\MockObject */
    private $_mock;

    public function testServiceIsCalled()
    {

        $this->_mock->expects($this->once())
            ->method('listSearch')
            ->willThrowException(new \Exception('repository is called'));

        try {

            $this->_finder->findVideoByText('myVideo');
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertEquals('repository is called', $e->getMessage());
        }
    }

    public function testYoutubeUrlIsGiven()
    {
        $response = $this->_getYoutubeResponse('myVideoId');
        $response2 = $this->_getYoutubeResponse('amotherVideoId');
        $this->_mock->expects($this->atLeastOnce())
            ->method('listSearch')
            ->willReturnOnConsecutiveCalls($response, $response2);
        $result = $this->_finder->findVideoByText('myVideo');


        $this->assertEquals('myVideoId', $result);


        $result = $this->_finder->findVideoByText('anotherVideo');

        $this->assertEquals('amotherVideoId', $result);
    }

    /**
     * @param string $videoId
     * @return Google_Service_YouTube_SearchListResponse
     */
    private function _getYoutubeResponse(string $videoId): Google_Service_YouTube_SearchListResponse
    {
        $response = new Google_Service_YouTube_SearchListResponse();
        $item = new Google_Service_YouTube_SearchResult();
        $resourceId = new \Google_Service_YouTube_ResourceId();
        $resourceId->setVideoId($videoId);
        $item->setId($resourceId);

        $response->setItems([
            $item
        ]);

        return $response;
    }

    protected function setUp()
    {
        parent::setUp();

        /** @var Google_Service_YouTube $service */
        $service = app()->make(Google_Service_YouTube::class);
        /** @var Google_Service_YouTube $mock */
        $this->_mock = $mock = $this->getMockBuilder(Google_Service_YouTube_Resource_Search::class)
            ->setConstructorArgs([$service, 'foo', 'bar', 'foobar'])
            ->setMethods([])
            ->getMock();

        $service->search = $mock;
        $this->_finder = new YoutubeFinderVideoService($service);
    }

}
