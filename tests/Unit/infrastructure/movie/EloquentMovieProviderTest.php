<?php

namespace Tests\Unit\infrastructure\movie;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use siesta\domain\exception\MovieNotFoundException;
use siesta\infrastructure\movie\persistence\EloquentMovieProvider;
use siesta\infrastructure\movie\persistence\EloquentMovieRecorder;
use Tests\Helpers\DomainGenerator;

/**
 * Class EloquentMovieProviderTest
 * @package Tests\Unit\infrastructure\movie
 */
class EloquentMovieProviderTest extends \Tests\TestCase
{
    use DatabaseMigrations;

    /** @var EloquentMovieProvider */
    private $_provider;
    /** @var FactoryBuilder */
    private $_factory;

    public function testWhenAskingForIdGivesMovieOrThrowsException()
    {
        /** @var Model $movie */
        $movie = $this->_factory->create();
        try {
            $id = 1;
            $result = $this->_provider->getMovieById($id);

            $expected = DomainGenerator::getMovieFromDBData($movie->getAttributes());
            $this->assertEquals($expected, $result);
        } catch (MovieNotFoundException $e) {
            $this->fail('Shouldn\' throw MovieNotFoundException');
        }

        try {
            $idNotFound = 68269;
            $this->_provider->getMovieById($idNotFound);
            $this->fail('Should throw MovieNotFoundException');
        } catch (MovieNotFoundException $e) {
            $this->assertTrue(true);
        }
    }


    protected function setUp()
    {
        parent::setUp();
        $this->_factory = factory(EloquentMovieRecorder::class);
        $this->_provider = new EloquentMovieProvider();
    }

}
