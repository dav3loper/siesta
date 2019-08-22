<?php

namespace Tests\Unit\infrastructure\movie;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use siesta\domain\exception\MovieRecordException;
use siesta\infrastructure\movie\persistence\EloquentMovieRecorder;
use Tests\Helpers\DomainGenerator;

/**
 * Class EloquentMovieRecorderTest
 * @package Tests\Unit\infrastructure\movie
 */
class EloquentMovieRecorderTest extends \Tests\TestCase
{

    use DatabaseMigrations;

    /** @var EloquentMovieRecorder */
    private $_recorder;

    public function testWhenSomethingIsInsertedGivesOkOrThrowsException()
    {
        try {
            $movie = DomainGenerator::givesMovie();
            $this->_recorder->store($movie);
            $this->assertTrue(true);
        } catch (MovieRecordException $e) {
            $this->fail('Shouldn\'t throw exception');
        }


        try {
            $movie = DomainGenerator::givesMovie();
            $this->_recorder->store($movie);
            $this->assertTrue(true);
        } catch (MovieRecordException $e) {
            $this->fail('Shouldn\'t throw MovieRecordException');
        }
    }


    protected function setUp()
    {
        parent::setUp();
        $this->_recorder = new EloquentMovieRecorder();
    }
}
