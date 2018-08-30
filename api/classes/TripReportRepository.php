<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TripReportRepository
 *
 * @author AlexMc
 */
class TripReportRepository {
    public $session;
    private $tripReports = array();
    private $filterParams;
    
    public function __construct($session) {
        $this->session = $session;
    }
    
    public function add($tripReport) {
        $this->tripReports[] = $tripReport;
    }
    
    public function all($params = null) {
        $options = array(
            "type" => "object",
            "subtype" => "trip_report",
            "limit" => false,
            "full_view" => false,
            "order_by" => "time_created DESC",
            "metadata_name_value_pairs" => array()
        );
        
        if($params) {
            foreach($params as $field => $value) {
                if($field == 'owner_guid') {
                    $options[$field] = $value;
                }
            }
        }
        
        $reports = elgg_get_entities_from_metadata($options);
        
        foreach($reports as $tempReport) {
            if($tempReport->guid) {
                $tripReport = new TripReport($this->session, $tempReport->guid);
                $this->tripReports[] = $tripReport;
            }
        }
    }
    
    public function getAll() {
        $return = array();
        
        foreach($this->tripReports as $report) {
            $return[] = $report->getAll();
        }
        
        return $return;
    }
}
