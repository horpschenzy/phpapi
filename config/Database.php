<?php 
/**
 * Database Connection
 *
 * @category DatabaseConnection
 * @package  Agpaytech
 * @author   Horpschenzy <opeoluwa.lanre@gmail.com>
 * @license  1.0 agpaytech.test
 * @link     agpaytech.test
 */
class Database
{
    // DB Params
    private $_host = 'localhost';
    private $_db_name = 'agpaytech';
    private $_db_username = 'root';
    private $_db_password = 'root';
    private $_conn;

    /**
     * Connecting to Database
     *
     * @return Response
     */
    public function connect()
    {
        $this->conn = null;

        try { 
            $this->_conn = new PDO(
                'mysql:host=' . $this->_host . ';dbname=' . $this->_db_name, 
                $this->_db_username, $this->_db_password
            );
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo json_encode(
                ['status' => false, 
                'message' => 'Connection Error: ' . $e->getMessage()]
            );
        }

        return $this->_conn;
    }
}