<?php

namespace Tests\Unit\infrastructure\vote;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\infrastructure\vote\persistence\EloquentVoteProvider;
use siesta\infrastructure\vote\persistence\EloquentVoteRecorder;
use Tests\Helpers\DomainGenerator;

/**
 * Class EloquentVoteProviderTest
 * @package Tests\Unit\infrastructure\vote
 */
class EloquentVoteProviderTest extends \Tests\TestCase
{

    use DatabaseMigrations;

    /** @var EloquentVoteProvider */
    private $_provider;
    /** @var FactoryBuilder */
    private $_factory;


    public function testWhenAskingForIdGivesMovieOrThrowsException()
    {
        /** @var Model $vote */
        $vote = $this->_factory->create();
        try {
            $id = 1;
            $result = $this->_provider->getVotesByMovieId($id);

            $expected = DomainGenerator::getVoteFromDBData($vote->getAttributes());
            $this->assertEquals($expected, $result);
        } catch (VoteNotFoundException $e) {
            $this->fail('Shouldn\' throw VoteNotFoundException');
        }

        try {
            $idNotFound = 68269;
            $this->_provider->getVotesByMovieId($idNotFound);
            $this->fail('Should throw VoteNotFoundException');
        } catch (VoteNotFoundException $e) {
            $this->assertTrue(true);
        }
    }

    public function testWhenAskingForVotesGivesVoteListOrThrowException()
    {
        /** @var Model $vote */
        $this->_factory->create();
        $this->_factory->create();
        $this->_factory->create();
        $this->_factory->create();
        $this->_factory->create();

        try {
            $voteList = $this->_provider->getVotesOrderedByScore();

            $expected = [];
            $this->assertEquals($expected, $voteList);
        } catch (VoteNotFoundException $e) {
            $this->fail('Shouldn\'t throw VoteNotFoundException');
        }
    }

    protected function setUp()
    {
        parent::setUp();
        $this->_factory = factory(EloquentVoteRecorder::class);
        $this->_provider = new EloquentVoteProvider();
    }

}
