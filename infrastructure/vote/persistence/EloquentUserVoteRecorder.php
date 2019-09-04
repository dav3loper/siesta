<?php
namespace siesta\infrastructure\vote\persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use siesta\domain\exception\vote\VoteInvalidTypeException;
use siesta\domain\exception\vote\VoteRecordException;
use siesta\domain\vote\IndividualVote;
use siesta\domain\vote\infrastructure\VoteRecorder;
use siesta\domain\vote\NoVoted;
use siesta\domain\vote\Vote;

/**
 * Class EloquentUserVoteRecorder
 * @package siesta\infrastructure\vote\persistence
 */
class EloquentUserVoteRecorder extends Model implements VoteRecorder
{
    //TODO: unificar esto y el del provider y el metodo find
    private const MOVIE_ID = 'movie_id';
    private const TABLE_NAME = 'user_vote';
    private const FILLABLE_FIELDS = [self::USER_ID, self::MOVIE_ID, 'score'];
    private const ID = 'id';
    private const USER_ID = 'user_id';

    /** @var ScoreTransformer */
    private $_scoreTransformer;

    /**
     * EloquentVoteRecorder constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE_FIELDS;
        $this->table = self::TABLE_NAME;
        $this->_scoreTransformer = app()->make(ScoreTransformer::class);
        parent::__construct($attributes);
    }

    /**
     * @param Vote $vote
     * @throws VoteRecordException
     * @throws VoteInvalidTypeException
     */
    public function store(Vote $vote): void
    {
        foreach ($vote->getIndividualVoteList() as $individualVote) {
            try {
                if ($individualVote->getScore() === NoVoted::get()) {
                    continue;
                }
                /** @noinspection PhpUndefinedMethodInspection */
                /** @var EloquentUserVoteRecorder $oldVote */
                $oldVote = self::where(self::MOVIE_ID, '=', $vote->getMovieId())
                    ->where(self::USER_ID, '=', $individualVote->getUserId())->firstOrFail();
                $oldVote->setRawAttributes($this->_getFillableFields($vote, $individualVote));
                $oldVote->update();
            } catch (ModelNotFoundException $e) {
                /** @noinspection PhpUndefinedMethodInspection */
                self::create($this->_getFillableFields($vote, $individualVote));
            } catch (\Exception $e) {
                throw new VoteRecordException($e);
            }
        }
    }

    /**
     * @param Vote $vote
     * @param IndividualVote $individualVote
     * @return array
     */
    private function _getFillableFields(Vote $vote, IndividualVote $individualVote): array
    {
        return array_combine($this->fillable, [
            $individualVote->getUserId(),
            $vote->getMovieId(),
            $this->_scoreTransformer->fromDomainToPersistence($individualVote->getScore()),
        ]);
    }
}