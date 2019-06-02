<?php
namespace bjz\portal\model;

use bjz\portal\model\Model;
use bjz\portal\model\QualificationModel;
/**
 * Class QualificationCollectionModel
 *
 * A qualifications collection model for a candidates list of qualifications
 *
 * @package bjz/portal
 */
class QualificationCollectionModel extends Model
{
    /**
     * @var array, the ID's of the work experience instances
     */
    private $qualification_ids;
    /**
     * @var int, the number of instances collected
     */
    private $N;

    /**
     * @return int, the number of instances collected
     */
    public function getN()
    {
        return $this->N;
    }

    /**
     * Constructor
     *
     * Collects the qualifications belonging to a candidate
     *
     * @param id, the id of the candidate
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($id)
    {
        parent::__construct();
        if (!$result = $this->db->query(
            "SELECT * FROM `qualification` LEFT JOIN `candidate`
                                         ON `qualification`.`owner_id` = `candidate`.`id`
                                         WHERE `qualification`.`owner_id` = '$id';"
        )
        ) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: qualCollectConstruct");
        }
        $this->qualification_ids = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    /**
     * Get qualification collection
     * Get the qualifications in the collection
     *
     * @return \Generator|QualificationModel[]
     */
    public function getQualifications()
    {
        foreach ($this->qualification_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new QualificationModel())->load($id);
        }
    }
}
