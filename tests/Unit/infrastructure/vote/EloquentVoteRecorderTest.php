<?php

namespace Tests\Unit\infrastructure\vote;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use siesta\domain\exception\vote\VoteRecordException;
use siesta\domain\vote\IndividualVote;
use siesta\infrastructure\vote\persistence\EloquentVoteRecorder;
use Tests\Helpers\DomainGenerator;


class EloquentVoteRecorderTest extends \Tests\TestCase
{

    use DatabaseMigrations;

    /** @var EloquentVoteRecorder */
    private $_recorder;


    public function testWhenSomethingIsInsertedGivesOkOrThrowsException()
    {
        try {

            $vote = DomainGenerator::givesVote();
            $this->_recorder->store($vote);
            $data = DB::table($this->_recorder->getTable())->get();
            /** @var \Stdclass $insertedVote */
            $insertedVote = $data->get(0);
            $this->assertEquals($insertedVote->id, 1);
            $this->assertEquals($insertedVote->movie_id, $vote->getMovieId());
            $this->assertEquals($insertedVote->historic_votes, '');
            $this->assertEquals($insertedVote->votes, '[{"userId":1,"score":1},{"userId":2,"score":2}]');
            $this->assertTrue(true);
        } catch (VoteRecordException $e) {
            $this->fail('Shouldn\'t throw VoteRecordException');
        }

        try {

            $vote = DomainGenerator::givesVote();
            $vote->setIndividualVoteList([new IndividualVote(FakeScore::get(), 33)]);
            $this->_recorder->store($vote);
            $this->fail('Should throw VoteRecordException');
        } catch (VoteRecordException $e) {
            $this->assertTrue(true);
        }
    }

    protected function setUp()
    {
        parent::setUp();
        $this->_recorder = new EloquentVoteRecorder();
    }
}
