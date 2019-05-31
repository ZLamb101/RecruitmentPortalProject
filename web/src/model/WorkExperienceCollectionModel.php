<?php
namespace bjz\portal\model;
use bjz\portal\model\Model;
use bjz\portal\model\WorkExperienceModel;

/**
 * Class WorkExperienceCollectionModel
 *
 * A work experience collection model, for a candidates work experience list
 *
 * @package bjz/portal
 */
class WorkExperienceCollectionModel extends Model
{
    /**
     * @var array, the ID's of the work experience instances
     */
    private $instance_ids;
    /**
     * @var int, the number of instances collected
     */
    private $N;

    /**
     * Returns the number of work experiences collected
     * @return int, the number of instances collected
     */
    public function getN()
    {
        return $this->N;
    }

    /**
     * Constructor
     *
     * Collects the work experience instances belonging to a candidate
     * @param id, the id of the candidate
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($id)
    {
        parent::__construct();
        if (!$result = $this->db->query("SELECT * FROM `work_experience` LEFT JOIN `candidate`
                                         ON `work_experience`.`owner_id` = `candidate`.`id`
                                         WHERE `work_experience`.`owner_id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: workExpCollectConstruct");
        }
        $this->instance_ids = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    /**
     * Get work experience instance collection
     * Get the instances in the collection
     * @return \Generator|WorkExperienceModel[]
     */
    public function getWorkExperiences()
    {
        foreach ($this->instance_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new WorkExperienceModel())->load($id);
        }
    }
}