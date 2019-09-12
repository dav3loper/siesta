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
     * @param $filmFestivalId
     * @return Vote[]
     */
    public function getVotesOfFilmFestivalIdOrderedByScore($filmFestivalId): array;

    /**
     * @param int $filmFestivalId
     * @param int $userId
     * @return Vote
     * @throws VoteNotFoundException
     */
    public function getLastVoteByFilmFestivalIdAndUserId($filmFestivalId, $userId): Vote;
}