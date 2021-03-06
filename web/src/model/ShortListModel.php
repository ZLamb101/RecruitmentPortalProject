<?php
namespace bjz\portal\model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * Class ShortListModel
 *
 * Represents a Short List of candidates owned by a user
 *
 * @package bjz/portal
 */
class ShortListModel extends Model
{
    /**
     * @var int, the ID of the Short List
     */
    private $id;
    /**
     * @var int, the ID of the employer the Short List belongs to
     */
    private $owner_id;
    /**
     * @var string, the name of the Short List
     */
    private $name;
    /**
     * @var array, the list of candidates on the Short List
     */
    private $candidates;

    /**
     * @var string, brief description of the short list
     */
    private $description;

    /**
     * @var bool, check for if a shortlist has sent invites.
     */
    private $hasInvited;

    /**
     * Informs whether a shortlist has sent invites before.
     *
     * @return bool
     */
    public function isHasInvited()
    {
        return $this->hasInvited;
    }

    /**
     * Changes the state of a shortlists invite sent status
     *
     * @param bool $hasInvited
     */
    public function setHasInvited($hasInvited)
    {
        $this->hasInvited = $hasInvited;
    }

    /**
     * Returns the description of the short list
     *
     * @return string, Gets the description of the short list
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Modifies the description of the shortlist
     *
     * @param string $description, Sets the description of the short list
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the ID of the short list
     *
     * @return int $this->id, the ID of the Short List
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the ID of the short list
     *
     * @param int $id, the new ID of the Short List
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * Returns the ID of the employer who owns the short list
     *
     * @return int $this->owner_id, the ID of the employer the Short List belongs to
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Sets the owner of the short list to the employer's ID
     *
     * @param int $owner_id, the new ID of the employer the Short List belongs to
     */
    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    /**
     * Returns the name of the short list
     *
     * @return string $this->name, the name of the Short List
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the shortlist
     *
     * @param string $name, the new name of the Short List
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Loads short list information from the database
     *
     * @param int $id, the id of the short list to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this ShortListModel
     */
    public function load($id)
    {
        $id = $this->db->real_escape_string($id);
        if (!$result = $this->db->query("SELECT * FROM `short_list` WHERE `id` = '$id';")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListLoad");
        }
        $result = $result->fetch_assoc();
        $this->id = $id;
        $this->owner_id = $result['owner_id'];
        $this->name = $result['name'];
        $this->description = $result['description'];
        $this->hasInvited = $result['hasInvited'];
        $candString = $result['candidates'];
        $this->candidates = explode(",", $candString);
        return $this;
    }

    /**
     * Saves short list information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this ShortListModel
     */
    public function save()
    {
        $owner_id = $this->owner_id ?? "NULL";
        $owner_id = $this->db->real_escape_string($owner_id);
        $description = $this->description ?? "NULL";
        $description = $this->db->real_escape_string($description);
        $name = $this->name ?? "NULL";
        $name = $this->db->real_escape_string($name);
        $cand = implode(",", $this->candidates);
        $cand = $this->db->real_escape_string($cand);
        if (!isset($this->id)) {
            if (!$result = $this->db->query("INSERT INTO `short_list` VALUES (NULL, '$owner_id', '$name', '$cand', '$description', 0);")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveNew");
            }
            $this->id = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `short_list` SET `owner_id` = '$owner_id', `name` = '$name', `candidates` = '$cand', `description` = '$description', `hasInvited` = '$this->hasInvited' WHERE `id` = '$this->id';")) {
                throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: shortListSaveExisting");
            }
        }
        return $this;
    }

    /**
     * Converts the candidate ID's from the database into CandidateModels as required.
     *
     * @return \Generator|CandidateModel[]
     */
    public function getCandidates()
    {
        foreach ($this->candidates as $id) {
            $this->numOfCandidates++;
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            yield (new CandidateModel())->load($id);
        }
    }

    /**
     * Adds a candidate to the short list
     *
     * @param $candId, the ID of the candidate to add
     */
    public function addCandidate($candId)
    {
        // Check if candidate is already in the short list
        if (in_array($candId, $this->candidates)) {
            return;
        }
        if ($this->candidates[0] == "NULL") {
            $this->candidates[0] = $candId;
        } else {
            $str = implode(",", $this->candidates);
            $str .= "," . $candId;
            $this->candidates = explode(",", $str);
        }
    }


    /**
     * Renames the shortList as desired
     *
     * @param $shortListID, the ID of the shortlist to rename
     * @param $name, the desired new name
     */
    public function renameShortList($shortListID, $name)
    {
        if (!$result = $this->db->query("UPDATE `short_list` SET `name` = '$name' WHERE `id` = '$shortListID'")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListRename");
        }
    }

    /**
     * Changes the shortList description as desired
     *
     * @param $shortListID, the ID of the shortlist to rename
     * @param $description, the desired new description
     */
    public function changeDescription($shortListID, $description)
    {
        if (!$result = $this->db->query("UPDATE `short_list` SET `description` = '$description' WHERE `id` = '$shortListID'")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListChangeDescription");
        }
    }

    /**
     * Deletes a specified candidate from a shortlist
     *
     * @param $shortListID, the ID of the shortList to delete the candidate from
     * @param $idToDelete, the ID of the Candidate being deleted
     */
    public function deleteFromShortList($shortListID, $idToDelete)
    {
        if (!$result = $this->db->query("SELECT * FROM `short_list` WHERE `id` = '$shortListID'")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListRemoveCandidate");
        }
        $result = $result->fetch_assoc();
        $str = $result['candidates'];
        $strToReplace = explode(",", $str);
        $index = array_search($idToDelete, $strToReplace);
        unset($strToReplace[$index]);
        $newCandidateList = implode(",", $strToReplace);
        if ($newCandidateList == "") {
            $newCandidateList = "NULL";
        }
        if (!$result = $this->db->query("UPDATE `short_list` SET `candidates` = '$newCandidateList' WHERE `id` = '$shortListID'")) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListSavingRemovedCandidate");
        }
    }

    /**
     * Deletes short list from the database
     *
     * @return $this ShortListModel
     */
    public function delete()
    {
        if (!$result = $this->db->query("DELETE FROM `short_list` WHERE `short_list`.`id` = $this->id;")) {
            throw new mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListModelDelete");
        }
        return $this;
    }

    /**
     * Creates the shortList as desired
     *
     * @param $shortListName, the name of the new shortList
     * @param $ownerID, the ID of the user the shortList belongs to
     * @param $description, the description of the shortList
     */
    public function newShortList($shortListName, $ownerID, $description)
    {

        if (!$result = $this->db->query(
            "INSERT INTO `short_list` (`id`, `owner_id`, `name`, `candidates`, `description`, `hasInvited`) VALUES 
                                                    (NULL, '$ownerID', '$shortListName', 'NULL', '$description',0);"
        )
        ) {
            throw new \mysqli_sql_exception("Oops! Something has gone wrong on our end. Error Code: ShortListNew");
        }
    }

    /**
     * This function sends a email when an employer is inviting a shortlist of candidates
     *
     * @param $candidate CandidateModel, an object representing the candidate the email is being sent to
     * @param $content string, the extra information an employer is adding to an email
     * @param $employer EmployerModel, an object representing the employer on behalf of whom the email is being sent
     */
    public function sendInviteEmail($candidate, $content, $employer)
    {

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

        //Server settings
        $mail->SMTPDebug = false;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'Vestarecruit@gmail.com';                 // SMTP username
        $mail->Password = 'Bobtool22';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;

        // TCP port to connect to
        //Recipients
        $mail->setFrom('Vestarecruit@gmail.com', 'Vesta Recruitment');
        $mail->addAddress($candidate->getEmail());     // Add a recipient
        $mail->addBCC('Vestarecruit@gmail.com');
        //Content

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Vesta Recruitment - Invite from ' . $employer->getCompanyName();

        $emailString = $this->getEmailBody($candidate, $content, $employer);

        $mail->Body = $emailString;
        $mail->AltBody = $emailString;

        $mail->send();
        // echo 'Message has been sent';
    }

    /***
     * Helper function to generate the email to send to the candidate
     *
     * @param  $candidate CandidateModel, an object representing the candidate the email is being sent to
     * @param  $content string, the extra information an employer is adding to an email
     * @param  $employer EmployerModel, an object representing the employer on behalf of whom the email is being sent
     * @return string $body, the full body of the email to send to each candidate
     */
    public function getEmailBody($candidate, $content, $employer)
    {
        $body = "";
        $body .= "Dear " . $candidate->getGName() . ",<br>You are invited to meet with " . $employer->getContactName() . " from " . $employer->getCompanyName() . "<br><br>";
        $body .= "They would like you to book a time for an interview that suits you, by following the following link<br>";
        $body .= "<a href=" . $employer->getCalendarLink() . ">" . $employer->getCalendarLink() . "</a><br><br>";
        if ($content != "") {
            $body .= $content . "<br><br>";
        }
        $body .= "Kind Regards,<br>Vesta Recruitment";
        return $body;
    }
}
