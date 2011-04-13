<?php

class Extrapkg_Magicimport_Model_Db extends Varien_Object
{
	public function connect()
	{
		static $db;
		if( empty( $db ) ){
			$dirs = explode( DS.'app'.DS.'code'.DS.'local',__FILE__);
			$local_xml = $dirs[0].DS.'app'.DS.'etc'.DS.'local.xml';		
			$xml = simplexml_load_string( file_get_contents($local_xml),'SimpleXMLElement',LIBXML_NOCDATA | LIBXML_NOBLANKS );
			$db_config = $xml->global->resources->default_setup->connection;
		
			include_once( dirname( __FILE__ ).DS.'Db'.DS.'Mysql.php' );
			$db = new myMysql( $db_config->host,$db_config->username,$db_config->password,$db_config->dbname,'utf8' );	
		}
		return $db;
	}
}