<?php 
/**
 * Data Pagination
 *
 * @category DataPagination
 * @package  Agpaytech
 * @author   Horpschenzy <opeoluwa.lanre@gmail.com>
 * @license  1.0 agpaytech.test
 * @link     agpaytech.test
 */
class Paginate
{
    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    /**
     * Setting default Database and running queries
     *
     * @param Database $db    Database
     * @param Query    $query Selected Query
     */
    public function __construct( $db, $query )
    {
      
        $this->_conn = $db;
        $this->_query = $query;
        $sqlStatement = $this->_conn->prepare($query);
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if ($search) {
            $sqlStatement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }
        $sqlStatement->execute();
        $this->_total = $sqlStatement->rowCount();
    }

    /**
     * Generate pagination for requested data
     *
     * @param $limit LIMIT
     * @param $page  CURRENT SELECTED PAGE
     * 
     * @return Response
     */
    public function paginate( int $limit, int $page = 1 )
    {
        $this->_limit   = $limit;
        $this->_page    = $page;
      
        if ($this->_limit == 'all' ) {
            $query      = $this->_query;
        } else {
            $query      = $this->_query . " LIMIT " . 
                                    ( ( $this->_page - 1 ) * $this->_limit ) . ", 
                                $this->_limit";
        }
        $sqlStatement = $this->_conn->prepare($query);
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if ($search) {
            $sqlStatement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }
        $sqlStatement->execute();
        $results = [];
        while ( $row = $sqlStatement->fetchAll() ) {
            $results[]  = $row;
        }
      
        $result         = [];
        $result['page']   = $this->_page;
        $result['limit']  = $this->_limit;
        $result['total']  = $this->_total;
        $result['data']   = $results;
      
        return $result;
    }
}