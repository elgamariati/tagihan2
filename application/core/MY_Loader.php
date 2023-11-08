<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	/**
	 * CI Object to Array translator
	 *
	 * Takes an object as input and converts the class variables to
	 * an associative array with key/value pairs.
	 *
	 * @param	object	$object	Object data to translate
	 * @return	array
	 */
	protected function _ci_object_to_array($object)
	{
		return is_object($object) ? get_object_vars($object) : $object;
	}

    /**
     * Database Loader
     *
     * @param	mixed	$params		Database configuration options
     * @param	bool	$return 	Whether to return the database object
     * @param	bool	$query_builder	Whether to enable Query Builder
     *					(overrides the configuration setting)
     *
     * @return	object|bool	Database object if $return is set to TRUE,
     *					FALSE on failure, CI_Loader instance in any other case
     */
    public function database($params = '', $return = FALSE, $query_builder = NULL)
    {
        // Grab the super object
        $CI =& get_instance();

        // Do we even need to load the database class?
        if ($return === FALSE && $query_builder === NULL && isset($CI->db) && is_object($CI->db) && ! empty($CI->db->conn_id))
        {
            return FALSE;
        }

        require_once(BASEPATH.'database/DB.php');

        if ($return === TRUE)
        {
            $db = DB($params, $query_builder);
            if(is_string($params))
                $CI->db_connection[$params] = $db;
            else
                $CI->db_connection[] = $db;

            return $db;
        }

        // Initialize the db variable. Needed to prevent
        // reference errors with some configurations
        $CI->db = '';

        // Load the DB class
        $CI->db =& DB($params, $query_builder);
        if(is_string($params))
            $CI->db_connection[$params] = $CI->db;
        else
            $CI->db_connection[] = $CI->db;
        return $this;
    }
}