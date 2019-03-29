<?php
namespace bjz\portal\model;

use bjz\portal\model\Model;
use bjz\portal\model\CandidateModel;
/**
 * Class SearchCandidateCollectionModel
 *
 * A Candidate collection model for a search query
 *
 * @package bjz/portal
 */
class SearchCandidateCollectionModel extends Model
{
    /**
     * @var array, the ID's of the candidates gathered
     */
    private $cand_ids;
    /**
     * @var int, the number of candidates gathered
     */
    private $N;

    /**
     * @return int, the number of candidates gathered
     */
    public function getN()
    {
        return $this->N;
    }

    /**
     * Constructor
     *
     * Collects the candidates found by a user query
     * @param query, the query string given by the user
     * @param field_id, the id of the field being searched
     * @param sub_field_id, the id of the subfield being searched
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($query, $field_id, $sub_field_id)
    {
        parent::__construct();
        if($sub_field_id == "all"){
            // Case for searching all subfields in a field
            if (!$result = $this->db->query("SELECT DISTINCT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct");
            }
        } else {
            // Case for searching a string in a specific sub field
            if (!$result = $this->db->query("SELECT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'
                                         AND `skill`.`sub_field_id` = '$sub_field_id'
                                         AND `skill`.`contents` LIKE '%{$query}%' 
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct");
            }
        }
        $this->cand_ids = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    /**
     * Get candidate collection
     * Get the candidates in the collection
     * @return \Generator|CandidateModel[]
     */
    public function getCandidates()
    {
        foreach ($this->cand_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new CandidateModel())->load($id);
        }
    }
}