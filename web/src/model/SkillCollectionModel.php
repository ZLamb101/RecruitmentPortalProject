<?php
namespace bjz\portal\model;

use bjz\portal\model\Model;
use bjz\portal\model\SkillModel;
/**
 * Class SkillCollectionModel
 *
 * A skills collection model for a candidates list of skills
 *
 * @package bjz/portal
 */
class SkillCollectionModel extends Model
{
    /**
     * @var array, the ID's of the work experience instances
     */
    private $skill_ids;
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
     * Collects the skills belonging to a candidate
     * @param id, the id of the candidate
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($id)
    {
        parent::__construct();
        if (!$result = $this->db->query("SELECT * FROM `skill` LEFT JOIN `candidate`
                                         ON `skill`.`owner_id` = `candidate`.`id`
                                         WHERE `skill`.`owner_id` = '$id';")){
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: skillCollectConstruct");
        }
        $this->skill_ids = array_column($result->fetch_all(), 0);
        $this->N = $result->num_rows;
    }

    /**
     * Get skill collection
     * Get the skills in the collection
     * @return \Generator|SkillModel[]
     */
    public function getSkills()
    {
        foreach ($this->skill_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new SkillModel())->load($id);
        }
    }
}