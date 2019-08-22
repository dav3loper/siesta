<?php
namespace siesta\infrastructure\vote\persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\exception\vote\VoteRecordException;
use siesta\domain\vote\infrastructure\VoteRecorder;
use siesta\domain\vote\Vote;

/**
 * Class EloquentVoteRecorder
 * @package siesta\infrastructure\vote\persistence
 */
class EloquentVoteRecorder extends Model implements VoteRecorder
{
    //TODO: unificar esto y el del provider y el metodo find
    private const MOVIE_ID = 'movie_id';
    private const CURRENT_VOTES = 'votes';
    private const HISTORIC_VOTES = 'historic_votes';
    private const TABLE_NAME = 'vote';
    private const FILLABLE_FIELDS = [self::CURRENT_VOTES, self::HISTORIC_VOTES, self::MOVIE_ID];
    private const ID = 'id';

    /** @var EloquentScoreTransformer */
    private $_transformer;

    /**
     * EloquentVoteRecorder constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        $scoreTransformer = app()->make(ScoreTransformer::class);
        $this->_transformer = new EloquentVoteSerializedTransformer($scoreTransformer);
        parent::__construct($attributes);
    }

    /**
     * @param Vote $vote
     * @throws VoteRecordException
     * @throws VoteInvalidTypeException
     */
    public function store(Vote $vote): void
    {
        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var EloquentVoteRecorder $oldVote */
            $oldVote = self::where(self::MOVIE_ID, '=', $vote->getMovieId())->firstOrFail();

            $oldVote->setRawAttributes($this->_getFillableFields($vote));
            $oldVote->setAttribute(self::HISTORIC_VOTES,
                json_encode(array_merge_recursive(
                        json_decode($oldVote->getAttribute(self::HISTORIC_VOTES), true),
                        [json_decode($oldVote->getAttribute(self::CURRENT_VOTES), true)])
                ));
            $oldVote->update();
        } catch (ModelNotFoundException $e) {
            /** @noinspection PhpUndefinedMethodInspection */
            self::create($this->_getFillableFields($vote));
        } catch (\Exception $e) {
            throw new VoteRecordException($e);
        }
    }

    /**
     * @param Vote $vote
     * @return array
     * @throws VoteInvalidTypeException
     */
    private function _getFillableFields(Vote $vote): array
    {
        return array_combine($this->fillable, [
            $this->_transformer->getSerializedVotes($vote->getIndividualVoteList()),
            '{}',
            $vote->getMovieId(),
        ]);
    }
}