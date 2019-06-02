<?php
namespace bjz\portal\model;

use bjz\portal\model\ShortListModel;
use bjz\portal\model\Model;
/**
 * Class ShortListCollectionModel
 *
 * A short list collection model for candidates.
 *
 * @package bjz/portal
 */
class ShortListCollectionModel extends Model
{
    /**
     * @var array, the ID's of the short lists collected
     */
    private $shortlist_ids;
    /**
     * @var int, the number of short lists collected
     */
    private $N;

    /**
     * @return int, the number of short lists collected
     */
    public function getN()
    {
        return $this->N;
    }

    /**
     * Constructor
     *
     * Collects the shortlists belonging to an employer
     *
     * @param id, the id of the employer collecting the short lists
     *
     * @throws mysqli_sql_exception if the SQL query fails
     */
    public function __construct($id)
    {
        parent::__construct();
        if (!$result = $this->db->query(
            "SELECT * FROM `short_list` LEFT JOIN `employer`
                                         ON `short_list`.`owner_id` = `employer`.`id`
                                         WHERE `short_list`.`owner_id` = '$id';"
        )
        ) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListCollectConstruct");
        }
            $this->shortlist_ids = array_column($result->fetch_all(), 0);
            $this->N = $result->num_rows;
    }

    /**
     * Get short list collection
     * Get the short lists in the collection
     *
     * @return \Generator|ShortListModel[]
     */
    public function getShortLists()
    {
        foreach ($this->shortlist_ids as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new ShortListModel())->load($id);
        }
    }
}
