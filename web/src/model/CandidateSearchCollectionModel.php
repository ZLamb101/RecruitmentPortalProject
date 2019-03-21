<?php
namespace bjz\portal\model;
/**
 * Class CandidateSearchCollectionModel
 *
 * A collection model for candidates who are being searched for.
 *
 * @package bjz/portal
 */
class CandidateSearchCollectionModel extends Model
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
     * Gets all candidates based on some search criteria
     *
     * @param string $search_str, the string being used to search for candidates
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($search_str)
    {
        parent::__construct();
//        if (!$result = $this->db->query("SELECT * FROM `candidate` LEFT JOIN `short_list`
////                                             ON `candidate`.`id` = `short_list`.`owner_id`
////                                             WHERE `short_list`.`id` = '$id';")) {
////            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: candCollectConstruct");
////        }
////        $this->candidate_ids = array_column($result->fetch_all(), 0);
////        $this->N = $result->num_rows;
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