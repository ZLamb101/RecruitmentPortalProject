<?php
namespace bjz\portal\model;
/**
 * Class CandidateCollectionModel
 *
 * A collection model for candidates.
 *
 * @package bjz/portal
 */
class CandidateCollectionModel extends Model
{
    /**
     * @var array, the ID's of the candidates collected
     */
    private $candidate_ids;
    /**
     * @var int, the number of candidates collected
     */
    private $N;

    /**
     * @return int, the number of candidates collected
     */
    public function getN()
    {
        return $this->N;
    }

    /**
     * Constructor
     *
     * Handles multiple use cases.
     * Short List use case - gets all candidates based on the short list ID
     * Search use case - gets all candidates based on some search criteria
     *
     * @param int $id, the id of a short list wanting to collect
     * @param string $search_str, the string being used to search for candidates
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($id, $search_str="")
    {
        if($search_str == ""){ // Short List use case
            if (!$result = $this->db->query("SELECT * FROM `candidate` LEFT JOIN
                                             ON `candidate`.`id` = `short_list`.`owner_id`
                                             WHERE `short_list`.`id` = '$id';")) {
                throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candCollectConstruct");
            }
                $this->candidate_ids = array_column($result->fetch_all(), 0);
                $this->N = $result->num_rows;
        } else { // Search use case
            //TODO Implement this
        }
    }

    /**
     * Get candidate collection
     * Get the candidates in the collection
     * @return \Generator|CandidateModel[]
     */
    public function getCandidates()
    {
        foreach ($this->candidate_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new CandidateModel())->load($id);
        }
    }
}