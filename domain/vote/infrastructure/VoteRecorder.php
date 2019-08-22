<?php
namespace siesta\domain\vote\infrastructure;

use siesta\domain\exception\vote\VoteRecordException;
use siesta\domain\vote\Vote;

interface VoteRecorder
{

    /**
     * @param Vote $vote
     * @throws VoteRecordException
     */
    public function store(Vote $vote);
}