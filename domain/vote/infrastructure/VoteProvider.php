<?php
namespace siesta\domain\vote\infrastructure;

use siesta\domain\exception\vote\VoteNotFoundException;
use siesta\domain\vote\Vote;

interface VoteProvider
{
    /**
     * @param int $id
     * @return Vote
     * @throws VoteNotFoundException
     */
    public function getVotesByMovieId(int $id): Vote;

    /**
     * @return Vote[]
     */
    public function getVotesOrderedByScore(): array;
}