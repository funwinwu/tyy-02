<?php


class Uplai_Customerenhance_Model_City extends Uplai_Customerenhance_Model_Abstract
{
	protected $_regionTable;
    protected $_regionNameTable;
    
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->_regionTable     = $resource->getTableName('directory/country_region');
        $this->_regionNameTable = $resource->getTableName('directory/country_region_name');
        $this->_read    = $resource->getConnection('directory_read');
        $this->_write   = $resource->getConnection('directory_write');
        parent::__construct();
    }
    /**
     * DB read connection
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_read;

    /**
     * DB write connection
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_write;
	
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
	
	public function getRegionById( $region_id )
	{
		try{
		//$locale = Mage::app()->getLocale()->getLocaleCode();
        //$systemLocale = Mage::app()->getDistroLocaleCode();
        
        $select = $this->_read->select()
            ->from(array('region'=>$this->_regionTable))
            ->where('region.region_id=?', $region_id)
            ->join(array('rname'=>$this->_regionNameTable),
                'rname.region_id=region.region_id ',//AND (rname.locale=\''.$locale.'\' OR rname.locale=\''.$systemLocale.'\')',
                array('name', new Zend_Db_Expr('CASE rname.locale WHEN \''.$systemLocale.'\' THEN 1 ELSE 0 END sort_locale')))
            ->order('sort_locale')
            ->limit(1);
			$region = $this->_read->fetchRow($select);
			return $region;	
		}catch( Exception $e ){
			echo $e;
		}
	}
}
