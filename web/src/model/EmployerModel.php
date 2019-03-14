<?php
namespace bjz\portal\model;
/**
 * Class EmployerModel
 *
 * Represents an employer in the system
 *
 * @package bjz/portal
 */
class EmployerModel extends UserModel
{
    /**
     * @var int, the ID of the employer
     */
    private $id;
    /**
     * @var int, the ID of the user account tied to this candidate
     */
    private $user_id;
    /**
     * @var string, the name of the company
     */
    private $company_name;
    /**
     * @var string, the URL of the company website
     */
    private $url;
    /**
     * @var string, the name of the company's contact person
     */
    private $contact_name;
    /**
     * @var string, the physical address of the company's offices.
     */
    private $address;
    /**
     * @var array, the array of short lists being maintained by the company.
     */
    private $short_lists;

    /**
     * @return int $this->id, the id of the employer profile
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id, the new id of the employer profile
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int $this->user_id, the id of the employer's user profile
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id, the new id of the employer's user profile
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string $this->company_name, the name of the company the employer is representing
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * @param string $company_name, the new name of the company the employer is representing
     */
    public function setCompanyName($company_name)
    {
        $this->company_name = $company_name;
    }

    /**
     * @return string $this->url, the URL of the company's website
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url, the new URL of the company's website
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string $this->contact_name, the name of the contact person for the employer
     */
    public function getContactName()
    {
        return $this->contact_name;
    }

    /**
     * @param string $contact_name, the new name of the contact person for the employer
     */
    public function setContactName($contact_name)
    {
        $this->contact_name = $contact_name;
    }

    /**
     * @return string $this->address, the physical location of the company's offices.
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address, the new physical location of the company's offices.
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return array $this->short_lists, the short lists of candidates a company has
     */
    public function getShortLists()
    {
        return $this->short_lists;
    }

    /**
     * @param array $short_lists, the new short lists of candidates a company has
     */
    public function setShortLists($short_lists)
    {
        $this->short_lists = $short_lists;
    }

    /**
     * Loads employer information from the database
     *
     * @param int $id, the id of the employer to load
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this EmployerModel
     */
    public function load($id)
    {
        //TODO write this.
        return $this;
    }

    /**
     * Saves employer information to the database
     *
     * @throws mysqli_sql_exception if the SQL query fails
     *
     * @return $this EmployerModel
     */
    public function save()
    {
        //TODO write this.
    }

}
