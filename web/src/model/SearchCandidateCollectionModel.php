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

    /***
     * Determines the correct SQL clause condition based on the availability
     * selected by the user.
     * @param $availability int, the availability case to be evaluated
     * @return string, the string to be appended to the SQL query
     */
    public function evaluateAvailability($availability){
        $required = "";
        switch ($availability){
            case 1:
                $required .= " AND `candidate`.`availability` IN (1,3,5,7,9,11,13,15)";
                break;
            case 2:
                $required .= " AND `candidate`.`availability` IN (2,3,6,7,10,11,14,15)";
                break;
            case 3:
                $required .= " AND `candidate`.`availability` IN (1,2,3,5,6,7,9,10,11,13,14,15)";
                break;
            case 4:
                $required .= " AND `candidate`.`availability` IN (4,5,6,7,12,13,14,15)";
                break;
            case 5:
                $required .= " AND `candidate`.`availability` IN (1,3,4,5,6,7,9,11,12,13,14,15)";
                break;
            case 6:
                $required .= " AND `candidate`.`availability` IN (2,3,4,5,6,7,10,11,12,13,14,15)";
                break;
            case 7:
                $required .= " AND `candidate`.`availability` IN (1,2,3,4,5,6,7,9,10,11,12,13,14,15)";
                break;
            case 8:
                $required .= " AND `candidate`.`availability` IN (8,9,10,11,12,13,14,15)";
                break;
            case 9:
                $required .= " AND `candidate`.`availability` IN (1,3,5,7,8,9,10,11,12,13,14,15)";
                break;
            case 10:
                $required .= " AND `candidate`.`availability` IN (2,3,6,7,8,9,10,11,12,13,14,15)";
                break;
            case 11:
                $required .= " AND `candidate`.`availability` IN (1,2,3,5,6,7,8,9,10,11,12,13,14,15)";
                break;
            case 12:
                $required .= " AND `candidate`.`availability` IN (4,5,6,7,8,9,10,11,12,13,14,15)";
                break;
            case 13:
                $required .= " AND `candidate`.`availability` IN (1,3,4,5,6,7,8,9,10,11,12,13,14,15)";
                break;
            case 14:
                $required .= " AND `candidate`.`availability` IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15)";
                break;
            default:
                $required .= " AND `candidate`.`availability` IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15)";
                break;
        }
        return $required;
    }

    /**
     * Takes the qualification selection of the search user and creates the necessary change to the sql statement
     * @param $qual, the value of the qualification being passed through (either an int id, or the "all" selection)
     * @return string, the correct string to append to the sql query based on the qualification
     */
    public function getQualString($qual)
    {
        $qualString = "";
        if($qual != "all"){
            $qualString .= " AND `qualification`.`type_id` = '$qual'";
        }
        return $qualString;
    }

    /**
     * Constructor
     *
     * Collects the candidates found by a user query
     * @param query, the query string given by the user
     * @param field_id, the id of the field being searched
     * @param sub_field_id, the id of the subfield being searched
     * @param qual, the id of the qualification type being searched
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($query, $field_id, $sub_field_id, $qual, $avail)
    {
        parent::__construct();
        $required = $this->evaluateAvailability($avail);
        $qualString = $this->getQualString($qual);
        if(($sub_field_id == "all") && (strlen($query) == 0)){
            // Case for searching all subfields in a field, with no string given
            if (!$result = $this->db->query("SELECT DISTINCT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         LEFT JOIN `qualification` ON `qualification`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'". $required . $qualString ."
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct1");
            }
        } else if ( ($sub_field_id == "all") && (strlen($query) != 0) ) {
            // Case for searching all subfields in a field, with a search query string
            if (!$result = $this->db->query("SELECT DISTINCT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         LEFT JOIN `qualification` ON `qualification`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'
                                         AND `skill`.`contents` LIKE '%{$query}%'". $required . $qualString ."
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct2");
            }
        } else if( ($sub_field_id != "all") && (strlen($query) == 0) ){
            // Case for searching a subfield without a specific string
            if (!$result = $this->db->query("SELECT DISTINCT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         LEFT JOIN `qualification` ON `qualification`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'
                                         AND `skill`.`sub_field_id` = '$sub_field_id'". $required . $qualString ."
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct3");
            }
        } else {
            // Case for searching a string in a specific sub field
            if (!$result = $this->db->query("SELECT `user_id`
                                         FROM `candidate` 
                                         LEFT JOIN `skill` ON `skill`.`owner_id` = `candidate`.`id`
                                         LEFT JOIN `qualification` ON `qualification`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`field_id` = '$field_id'
                                         AND `skill`.`sub_field_id` = '$sub_field_id'
                                         AND `skill`.`contents` LIKE '%{$query}%'". $required . $qualString ." 
                                         ;")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: SearchCandCollectConstruct4");
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