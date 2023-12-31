<?php
/**
 * A class for performing SNMP V2 queries and processing the results.
 *
 * @copyright Copyright (c) 2012, Open Source Solutions Limited, Dublin, Ireland
 * @author Barry O'Donovan <barry@opensolutions.ie>
 */
class SNMP
{
    /**
     * The SNMP community to use when polling SNMP services. Defaults to 'public' by the constructor.
     *
     * @var string The SNMP community to use when polling SNMP services. Defaults to 'public' by the constructor.
     */
    protected $_community;
    /**
     * The SNMP host to query. Defaults to '127.0.0.1'
     * @var string The SNMP host to query. Defaults to '127.0.0.1' by the constructor.
     */
    protected $_host;
    /**
     * The SNMP query timeout value (microseconds). Default: 1000000
     * @var int The SNMP query timeout value (microseconds). Default: 1000000
     */
    private $_timeout = 500000;
    /**
     * The SNMP query retry count. Default: 5
     * @var int The SNMP query retry count. Default: 5
     */
    private $_retry = 1;
    /**
     * A variable to hold the last unaltered result of an SNMP query
     * @var mixed The last unaltered result of an SNMP query
     */
    protected $_lastResult = null;
    /**
     * A variable to hold the platform object
     * @var mixed The platform object
     */
    protected $_platform = null;
    /**
     * The cache object to use as the cache
     * @var \OSS_SNMP\Cache The cache object to use
     */
    protected $_cache = null;
    /**
     * Set to true to disable local cache lookup and force SNMP queries
     *
     * Results are still stored. If you need to force a SNMP query, you can:
     *
     * $snmp = new OSS_SNMP( ... )'
     * ...
     * $snmp->disableCache();
     * $snmp->get( ... );
     * $snmp->enableCache();
     */
    protected $_disableCache = false;
    /**
     * SNMP output constants to mirror those of PHP
     * @var SNMP output constants to mirror those of PHP
     */
    const OID_OUTPUT_FULL    = SNMP_OID_OUTPUT_FULL;
    /**
     * SNMP output constants to mirror those of PHP
     * @var SNMP output constants to mirror those of PHP
     */
    const OID_OUTPUT_NUMERIC = SNMP_OID_OUTPUT_NUMERIC;
    /**
     * Definition of an SNMP return type 'TruthValue'
     * @var Definition of an SNMP return type 'TruthValue'
     */
    const SNMP_TRUTHVALUE_TRUE  = 1;
    /**
     * Definition of an SNMP return type 'TruthValue'
     * @var Definition of an SNMP return type 'TruthValue'
     */
    const SNMP_TRUTHVALUE_FALSE = 2;
    /**
     * PHP equivalents of SNMP return type TruthValue
     * @var array PHP equivalents of SNMP return type TruthValue
     */
    public static $SNMP_TRUTHVALUES = array(
        self::SNMP_TRUTHVALUE_TRUE  => true,
        self::SNMP_TRUTHVALUE_FALSE => false
    );
    /**
     * The constructor.
     *
     * @param string $host The target host for SNMP queries.
     * @param string $community The community to use for SNMP queries.
     * @return OSS_SNMP An instance of $this (for fluent interfaces)
     */
    public function __construct( $host = '127.0.0.1', $community = 'public' )
    {
        return $this->setHost( $host )
                    ->setCommunity( $community )
                    ->setOidOutputFormat( self::OID_OUTPUT_NUMERIC );
    }
    /**
     * Proxy to the snmp2_real_walk command
     *
     * @param string $oid The OID to walk
     * @return array The results of the walk
     */
    public function realWalk( $oid )
    {
        return $this->_lastResult = snmp2_real_walk( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
    }
    /**
     * Get a single SNMP value
     *
     * @throws \OSS_SNMP\Exception On *any* SNMP error, warnings are supressed and a generic exception is thrown
     * @param string $oid The OID to get
     * @return mixed The resultant value
     */
    public function get( $oid )
    {
        if( $this->cache() && ( $rtn = $this->getCache()->load( $oid ) ) !== null )
            return $rtn;
        $this->_lastResult = @snmp2_get( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
        if( $this->_lastResult === false )
            throw new Exception( 'Could not perform walk for OID ' . $oid );
        return $this->getCache()->save( $oid, $this->parseSnmpValue( $this->_lastResult ) );
    }
    /**
     * Get indexed SNMP values (first degree)
     *
     * Walks the SNMP tree returning an array of key => value pairs.
     *
     * This is a first degree walk and it will throw an exception if there is more that one degree of values.
     *
     * I.e. the following query with sample results:
     *
     * walk1d( '.1.0.8802.1.1.2.1.3.7.1.4' )
     *
     *       .1.0.8802.1.1.2.1.3.7.1.4.1 = STRING: "GigabitEthernet1/0/1"
     *       .1.0.8802.1.1.2.1.3.7.1.4.2 = STRING: "GigabitEthernet1/0/2"
     *       .1.0.8802.1.1.2.1.3.7.1.4.3 = STRING: "GigabitEthernet1/0/3"
     *       .....
     *
     * would yield an array:
     *
     *      1 => GigabitEthernet1/0/1
     *      2 => GigabitEthernet1/0/2
     *      3 => GigabitEthernet1/0/3
     *
     * @param string $oid The OID to walk
     * @return array The resultant values
     * @throws \OSS_SNMPException On *any* SNMP error, warnings are supressed and a generic exception is thrown
     */
    public function walk1d( $oid )
    {
        if( $this->cache() && ( $rtn = $this->getCache()->load( $oid ) ) !== null )
            return $rtn;
        $this->_lastResult = @snmp2_real_walk( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
        if( $this->_lastResult === false )
            throw new Exception( 'Could not perform walk for OID ' . $oid );
        $result = array();
        $oidPrefix = null;
        foreach( $this->_lastResult as $_oid => $value )
        {
            if( $oidPrefix !== null && $oidPrefix != substr( $_oid, 0, strrpos( $_oid, '.' ) ) )
                throw new Exception( 'Requested OID tree is not a first degree indexed SNMP value' );
            else
                $oidPrefix = substr( $_oid, 0, strrpos( $_oid, '.' ) );
            $result[ substr( $_oid, strrpos( $_oid, '.' ) + 1 ) ] = $this->parseSnmpValue( $value );
        }
        return $this->getCache()->save( $oid, $result );
    }
    /**
     * Get indexed SNMP values where the array key is the given position of the OID
     *
     * I.e. the following query with sample results:
     *
     * subOidWalk( '.1.3.6.1.4.1.9.9.23.1.2.1.1.9', 15 )
     *
     *
     *       .1.3.6.1.4.1.9.9.23.1.2.1.1.9.10101.5 = Hex-STRING: 00 00 00 01
     *       .1.3.6.1.4.1.9.9.23.1.2.1.1.9.10105.2 = Hex-STRING: 00 00 00 01
     *       .1.3.6.1.4.1.9.9.23.1.2.1.1.9.10108.4 = Hex-STRING: 00 00 00 01
     *
     * would yield an array:
     *
     *      10101 => Hex-STRING: 00 00 00 01
     *      10105 => Hex-STRING: 00 00 00 01
     *      10108 => Hex-STRING: 00 00 00 01
     *
     * @throws \OSS_SNMP\Exception On *any* SNMP error, warnings are supressed and a generic exception is thrown
     * @param string $oid The OID to walk
     * @param int $position The position of the OID to use as the key
     * @return array The resultant values
     */
    public function subOidWalk( $oid, $position )
    {
        if( $this->cache() && ( $rtn = $this->getCache()->load( $oid ) ) !== null )
            return $rtn;
        $this->_lastResult = @snmp2_real_walk( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
        if( $this->_lastResult === false )
            throw new Exception( 'Could not perform walk for OID ' . $oid );
        $result = array();
        foreach( $this->_lastResult as $_oid => $value )
        {
            $oids = explode( '.', $_oid );
            $result[ $oids[ $position] ] = $this->parseSnmpValue( $value );
        }
        return $this->getCache()->save( $oid, $result );
    }
    /**
     * Get indexed SNMP values where they are indexed by IPv4 addresses
     *
     * I.e. the following query with sample results:
     *
     * subOidWalk( '.1.3.6.1.2.1.15.3.1.1. )
     *
     *
     *       .1.3.6.1.2.1.15.3.1.1.10.20.30.4 = IpAddress: 192.168.10.10
     *       ...
     *
     * would yield an array:
     *
     *      [10.20.30.4] => "192.168.10.10"
     *      ....
     *
     * @throws \OSS_SNMP\Exception On *any* SNMP error, warnings are supressed and a generic exception is thrown
     * @param string $oid The OID to walk
     * @return array The resultant values
     */
    public function walkIPv4( $oid )
    {
        if( $this->cache() && ( $rtn = $this->getCache()->load( $oid ) ) !== null )
            return $rtn;
        $this->_lastResult = @snmp2_real_walk( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
        if( $this->_lastResult === false )
            throw new Exception( 'Could not perform walk for OID ' . $oid );
        $result = array();
        foreach( $this->_lastResult as $_oid => $value )
        {
            $oids = explode( '.', $_oid );
            $len = count( $oids );
            $result[ $oids[ $len - 4 ] . '.' . $oids[ $len - 3 ] . '.' . $oids[ $len - 2 ] . '.' . $oids[ $len - 1 ]  ] = $this->parseSnmpValue( $value );
        }
        return $this->getCache()->save( $oid, $result );
    }
    /**
     * Parse the result of an SNMP query into a PHP type
     *
     * For example, [STRING: "blah"] is parsed to a PHP string containing: blah
     *
     * @param string $v The value to parse
     * @return mixed The parsed value
     * @throws Exception
     */
    public function parseSnmpValue( $v )
    {
        // first, rule out an empty string
        if( $v == '""' || $v == '' )
            return "";
        $type = substr( $v, 0, strpos( $v, ':' ) );
        $value = trim( substr( $v, strpos( $v, ':' ) + 1 ) );
        switch( $type )
        {
            case 'STRING':
                if( substr( $value, 0, 1 ) == '"' )
                    $rtn = (string)trim( substr( substr( $value, 1 ), 0, -1 ) );
                else
                    $rtn = (string)$value;
                break;
            case 'INTEGER':
                if( !is_numeric( $value ) )
                    $rtn = (int)substr( substr( $value, strpos( $value, '(' ) + 1 ), 0, -1 );
                else
                    $rtn = (int)$value;
                break;
            case 'Counter32':
                $rtn = (int)$value;
                break;
            case 'Counter64':
                $rtn = (int)$value;
                break;
                case 'Gauge32':
                $rtn = (int)$value;
                break;
            case 'Hex-STRING':
                $rtn = (string)implode( '', explode( ' ', preg_replace( '/[^A-Fa-f0-9]/', '', $value ) ) );
                break;
            case 'IpAddress':
                $rtn = (string)$value;
                break;
                
            case 'OID':
                $rtn = (string)$value;
                break;
            case 'Timeticks':
                $rtn = (int)substr( $value, 1, strrpos( $value, ')' ) - 1 );
                break;
            default:
                throw new Exception( "ERR: Unhandled SNMP return type: $type\n" );
        }
        return $rtn;
    }
    /**
     * Utility function to convert TruthValue SNMP responses to true / false
     *
     * @param integer $value The TruthValue ( 1 => true, 2 => false) to convert
     * @return boolean
     */
    public static function ppTruthValue( $value )
    {
        if( is_array( $value ) )
            foreach( $value as $k => $v )
                $value[$k] = self::$SNMP_TRUTHVALUES[ $v ];
        else
            $value = self::$SNMP_TRUTHVALUES[ $value ];
        return $value;
    }
    /**
      * An array of arrays where each array element
      * represents true / false values for a given
      * hex digit.
      *
      * @see ppHexStringFlags()
      */
    public static $HEX_STRING_WORDS_AS_ARRAY = array(
        '0' => array( false, false, false, false ),
        '1' => array( false, false, false, true  ),
        '2' => array( false, false, true,  false ),
        '3' => array( false, false, true,  true  ),
        '4' => array( false, true,  false, false ),
        '5' => array( false, true,  false, true  ),
        '6' => array( false, true,  true,  false ),
        '7' => array( false, true,  true,  true  ),
        '8' => array( true,  false, false, false ),
        '9' => array( true,  false, false, true  ),
        'a' => array( true,  false, true,  false ),
        'b' => array( true,  false, true,  true  ),
        'c' => array( true,  true,  false, false ),
        'd' => array( true,  true,  false, true  ),
        'e' => array( true,  true,  true,  false ),
        'f' => array( true,  true,  true,  true  ),
    );
    /**
     * Takes a HEX-String of true / false - on / off - set / unset flags
     * and converts it to an indexed (from 1) array of true / false values.
     *
     * For example, passing it ``500040`` will result in an array:
     *
     *     [
     *         [1]  => false, [2]  => true,  [3] => false, [4]  => true,
     *         [5]  => false, [6]  => false, [7] => false, [8]  => false,
     *         ...
     *         [17] => false, [18] => true, [19] => false, [20] => false,
     *         [21] => false, [22] => true, [23] => false, [24] => false
     *     ]
     *
     * @param string $str The hex string to parse
     * @return array The array of true / false flags indexed from 1
     */
    public static function ppHexStringFlags( $str )
    {
        $str = strtolower( $str );  // ensure all hex digits are lower case
        $values []=array( 0 => 'dummy' );
        for( $i = 0; $i < strlen( $str ); $i++ )
            $values = array_merge( $values, self::$HEX_STRING_WORDS_AS_ARRAY[ $str[$i] ] );
        unset( $values[ 0 ] );
        return $values;
    }
    /**
     * Utility function to translate one value(s) to another via an associated array
     *
     * I.e. all elements '$value' will be replaced with $translator( $value ) where
     * $translator is an associated array.
     *
     * Huh? Just read the code below!
     *
     * @param mixed $values A scalar or array or values to translate
     * @param array $translator An associated array to use to translate the values
     * @return mixed The translated scalar or array
     */
    public static function translate( $values, $translator )
    {
        if( !is_array( $values ) )
        {
            if( isset( $translator[ $values ] ) )
                return $translator[ $values ];
            else
                return "*** UNKNOWN ***";
        }
        foreach( $values as $k => $v )
        {
            if( isset( $translator[ $v ] ) )
                $values[$k] = $translator[ $v ];
            else
                $values[$k] = "*** UNKNOWN ***";
        }
        return $values;
    }
    /**
     * Sets the output format for SNMP queries.
     *
     * Should be one of the class OID_OUTPUT_* constants
     *
     * @param int $f The fomat to use
     * @return OSS_SNMP\SNMP An instance of $this (for fluent interfaces)
     */
    public function setOidOutputFormat( $f )
    {
        snmp_set_oid_output_format( $f );
        return $this;
    }
    /**
     * Sets the target host for SNMP queries.
     *
     * @param string $h The target host for SNMP queries.
     * @return \OSS_SNMP\SNMP An instance of $this (for fluent interfaces)
     */
    public function setHost( $h )
    {
        $this->_host = $h;
        // clear the temporary result cache and last result
        $this->_lastResult = null;
        unset( $this->_resultCache );
        $this->_resultCache = array();
        return $this;
    }
    /**
     * Returns the target host as currently configured for SNMP queries
     *
     * @return string The target host as currently configured for SNMP queries
     */
    public function getHost()
    {
        return $this->_host;
    }
    /**
     * Sets the community string to use for SNMP queries.
     *
     * @param string $c The community to use for SNMP queries.
     * @return OSS_SNMP An instance of $this (for fluent interfaces)
     */
    public function setCommunity( $c )
    {
        $this->_community = $c;
        return $this;
    }
    /**
     * Returns the community string currently in use.
     *
     * @return string The community string currently in use.
     */
    public function getCommunity()
    {
        return $this->_community;
    }
    /**
     * Sets the timeout to use for SNMP queries (microseconds).
     *
     * @param int $t The timeout to use for SNMP queries (microseconds).
     * @return OSS_SNMP An instance of $this (for fluent interfaces)
     */
    public function setTimeout( $t )
    {
        $this->_timeout = $t;
        //return $this;
    }
    /**
     * Returns the SNMP query timeout (microseconds).
     *
     * @return int The the SNMP query timeout (microseconds)
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }
    /**
     * Sets the SNMP query retry count.
     *
     * @param int $r The SNMP query retry count
     * @return OSS_SNMP An instance of $this (for fluent interfaces)
     */
    public function setRetry( $r )
    {
        $this->_retry = $r;
        //return $this;
    }
    /**
     * Returns the SNMP query retry count
     *
     * @return string The SNMP query retry count
     */
    public function getRetry()
    {
        return $this->_retry;
    }
    /**
     * Returns the unaltered original last SNMP result
     *
     * @return mixed The unaltered original last SNMP result
     */
    public function getLastResult()
    {
        return $this->_lastResult;
    }
    /**
     * Returns the internal result cache
     *
     * @return array The internal result cache
     */
    public function getResultCache()
    {
        return $this->_resultCache;
    }
    /**
     * Disable lookups of the local cache
     *
     * @return SNMP An instance of this for fluent interfaces
     */
    public function disableCache()
    {
        $this->_disableCache = true;
        return $this;
    }
    /**
     * Enable lookups of the local cache
     *
     * @return SNMP An instance of this for fluent interfaces
     */
    public function enableCache()
    {
        $this->_disableCache = false;
        return $this;
    }
    /**
     * Query whether we are using the cache or not
     *
     * @return boolean True of the local lookup cache is enabled. Otherwise false.
     */
    public function cache()
    {
        return !$this->_disableCache;
    }
    /**
     * Set the cache to use
     *
     * @param \OSS_SNMP\Cache $c The cache to use
     * @return \OSS_SNMP\SNMP For fluent interfaces
     */
    public function setCache( $c )
    {
        $this->_cache = $c;
        return $this;
    }
    /**
     * Get the cache in use (or create a Cache\Basic instance
     *
     * We kind of mandate the use of a cache as the code is written with a cache in mind.
     * You are free to disable it via disableCache() but your machines may be hammered!
     *
     * We would suggest disableCache() / enableCache() used in pairs only when really needed.
     *
     * @return \OSS_SNMP\Cache The cache object
     */
    public function getCache()
    {
        if( $this->_cache === null )
            $this->_cache = new \OSS_SNMP\Cache\Basic();
        return $this->_cache;
    }
    /**
     * Magic method for generic function calls
     *
     * @param string $method
     * @param array $args
     * @throws Exception
     */
    public function __call( $method, $args )
    {
        if( substr( $method, 0, 3 ) == 'use' )
            return $this->useExtension( substr( $method, 3 ), $args );
        throw new Exception( "ERR: Unknown method requested in magic __call(): $method\n" );
    }
    /**
     * This is the MIB Extension magic
     *
     * Calling $this->useXXX_YYY_ZZZ()->fn() will instantiate
     * an extension MIB class is the given name and this $this SNMP
     * instance and then call fn().
     *
     * See the examples for more information.
     *
     * @param string $mib The extension class to use
     * @param array $args
     * @return \OSS_SNMP\MIBS
     */
    public function useExtension( $mib, $args )
    {
        $mib = '\\OSS_SNMP\\MIBS\\' . str_replace( '_', '\\', $mib );
        $m = new $mib();
        $m->setSNMP( $this );
        return $m;
    }
    public function getPlatform()
    {
        if( $this->_platform === null )
            $this->_platform = new Platform( $this );
        return $this->_platform;
    }
    /**
     * Get indexed SNMP values where the array key is spread over a number of OID positions
     *
     * @throws \OSS_SNMP\Exception On *any* SNMP error, warnings are supressed and a generic exception is thrown
     * @param string $oid The OID to walk
     * @param int $positionS The start position of the OID to use as the key
	 * @param int $positionE The end position of the OID to use as the key
     * @return array The resultant values
     */
    public function subOidWalkLong( $oid, $positionS, $positionE )
    {
        if( $this->cache() && ( $rtn = $this->getCache()->load( $oid ) ) !== null )
            return $rtn;
		
        $this->_lastResult = @snmp2_real_walk( $this->getHost(), $this->getCommunity(), $oid, $this->getTimeout(), $this->getRetry() );
        if( $this->_lastResult === false )
            throw new Exception( 'Could not perform walk for OID ' . $oid );
        $result = array();
        foreach( $this->_lastResult as $_oid => $value )
        {
            $oids = explode( '.', $_oid );
			$oidKey = '';
			for($i = $positionS; $i <= $positionE; $i++)
			{
				$oidKey .= $oids[$i] .'.';
			}
            $result[ $oidKey ] = $this->parseSnmpValue( $value );
        }
        return $this->getCache()->save( $oid, $result );
    }

}