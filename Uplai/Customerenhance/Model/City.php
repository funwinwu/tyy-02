<?php


class Uplai_Customerenhance_Model_City extends Uplai_Customerenhance_Model_Abstract
{
	public function getCitysByRegion( $region_id )
	{
		$query = "select * from directory_region_city where parent_id='{$region_id}'";
		$rows = $this->db->fetchAll( $query );
		return $rows;
	}
	
	public function getDistrictsByRegion( $city_id )
	{
		$query = "select * from directory_city_district where parent_id='{$city_id}'";
		$rows = $this->db->fetchAll( $query );
		return $rows;
	}
	
	public function getDistrictById( $district_id )
	{
		$query = "select name from directory_city_district where area_id='{$district_id}'";
		$rows = $this->db->fetchOne( $query );
		return $rows;
	}
	
	public function getCityById( $city_id )
	{
		$query = "select name from directory_region_city where city_id='{$city_id}'";
		$rows = $this->db->fetchOne( $query );
		return $rows;
	}
}
