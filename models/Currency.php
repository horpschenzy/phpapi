<?php

require_once '../config/Paginate.php';
/**
 * Currency Model
 * 
 * @param Database $db
 *
 * @category CurrencyModel
 * @package  Agpaytech
 * @author   Horpschenzy <opeoluwa.lanre@gmail.com>
 * @license  1.0 agpaytech.test
 * @link     agpaytech.test
 */

class Currency
{
    // DB Stuff
    private $_conn;
    private $_table = 'currencies';

    /**
     * Setting default Database
     *
     * @param Database $db Database
     * 
     * @return Response
     */
    public function __construct($db)
    {
        $this->_conn = $db;
    }

    /**
     * Inserting Currency data to countries table
     *
     * @param Array $data Array
     * 
     * @return Response
     */
    public function insertCurrency(array $data) 
    {   
        $newArray = [];
        for ($i=1; $i < count($data); $i++) { 
              $newArray[$i]['iso_code'] = $data[$i][0];
              $newArray[$i]['iso_numeric_code'] = $data[$i][1];
              $newArray[$i]['common_name'] = utf8_encode($data[$i][2]);
              $newArray[$i]['official_name'] = utf8_encode($data[$i][3]);
              $newArray[$i]['symbol'] = utf8_encode($data[$i][4]);

        }
        try { 
            $this->_conn->beginTransaction();
            $sql = 'INSERT INTO ' . $this->_table . '(
                          iso_code, iso_numeric_code, common_name,
                          official_name,symbol
                        ) 
                        VALUES (
                          :iso_code, :iso_numeric_code, :common_name, 
                          :official_name, :symbol
                        )';
            $statement =$this->_conn->prepare($sql);
            foreach ($newArray as $row) {
                $statement->execute($row); 
            }
            $this->_conn->commit();

            http_response_code(201);
            return ['status' => true, 
                'message' => 'Currencies imported successfully'];

        } catch(PDOException $e) {
            http_response_code(400);
            return ['status' => false, 
                'message' => 'Error Importing file: ' . $e->getMessage()];
        }
    }

    /**
     * Listing Currencies with pagination and search
     *
     * @return Response
     */
    public function listData()
    {
        $sql = "SELECT * FROM $this->_table";
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = isset($_GET['limit']) && $_GET['limit'] != '' ? $_GET['limit'] : 10;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        if ($search) {
            $sql .= " WHERE iso_code LIKE :search OR iso_numeric_code LIKE :search 
                        OR common_name LIKE :search OR official_name LIKE :search 
                        OR symbol LIKE :search";
        }
        $sql .= " ORDER BY official_name ASC ";
        $paginate = new Paginate($this->_conn, $sql);
        $currencies = $paginate->paginate($limit, $page);
        if (empty($currencies['data'])) {
            return $currencies;
        }
        $currencyData = $currencies['data'][0];
        unset($currencies['data']);
        foreach ($currencyData as $key => $currency) {
            $currencies['data'][$key]['iso_code'] = utf8_decode($currency['iso_code']);
            $currencies['data'][$key]['iso_numeric_code'] = utf8_decode($currency['iso_numeric_code']);
            $currencies['data'][$key]['common_name'] = utf8_decode($currency['common_name']);
            $currencies['data'][$key]['official_name'] = utf8_decode($currency['official_name']);
            $currencies['data'][$key]['symbol'] = utf8_decode($currency['symbol']);
        }
        return $currencies;
    }
}
