<?php

namespace bjz\portal\controller;
use bjz\portal\model\ShortListModel;


/**
 * Class ShortListController
 *
 *
 *
 * @package bjz\portal\controller
 */
class ShortListController extends Controller
{

    /**
     * Gets the id of the short list to rename and the new name for it from the get array
     * Attempts to rename said shortlist using the given parameters and handles errors appropriately
     */
    public function renameShortListAction(){
        try{
            $id = $_GET["q"];
            $name = $_GET["name"];
            $list = new ShortListModel();
            $list->renameShortList($id, $name);
        } catch (\Exception $e){
            error_log($e->getMessage());
            $this->redirect("errorPage");
        }
    }
}