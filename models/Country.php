<?php

require_once '../config/Paginate.php';
/**
 * Country Model
 * 
 * @param Database $db
 *
 * @category CountryModel
 * @package  Agpaytech
 * @author   Horpschenzy <opeoluwa.lanre@gmail.com>
 * @license  1.0 agpaytech.test
 * @link     agpaytech.test
 */

class Country
{
    // DB Stuff
    private $_conn;
    private $_table = 'countries';

    // Properties
    public $id;
    public $name;
    public $created_at;

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
     * Inserting Country data to countries table
     *
     * @param Array $data Array
     * 
     * @return Response
     */
    public function insertCountry(array $data) 
    {   
        $newArray = [];
        for ($i=1; $i < count($data); $i++) { 
              $newArray[$i]['country_code'] = $data[$i][0];
              $newArray[$i]['currency_code'] = $data[$i][1];
              $newArray[$i]['iso2_code'] = $data[$i][2];
              $newArray[$i]['iso3_code'] = $data[$i][3];
              $newArray[$i]['iso_numeric_code'] = $data[$i][4];
              $newArray[$i]['fips_code'] = $data[$i][5];
              $newArray[$i]['calling_code'] = $data[$i][6];
              $newArray[$i]['common_name'] = utf8_encode($data[$i][7]);
              $newArray[$i]['official_name'] = utf8_encode($data[$i][8]);
              $newArray[$i]['endonym'] = utf8_encode($data[$i][9]);
              $newArray[$i]['demonym'] = utf8_encode($data[$i][10]);

        }
        try { 
            $this->_conn->beginTransaction();
            $sql = 'INSERT INTO ' . $this->_table . '(
                          country_code, currency_code, iso2_code, iso3_code,
                          iso_numeric_code,  fips_code, calling_code, common_name, 
                          official_name, endonym, demonym
                        ) 
                        VALUES (
                          :country_code, :currency_code, :iso2_code, :iso3_code, 
                          :iso_numeric_code, :fips_code, :calling_code, :common_name,
                          :official_name, :endonym, :demonym
                        )';
            $statement =$this->_conn->prepare($sql);
            foreach ($newArray as $row) {
                $statement->execute($row); 
            }
            $this->_conn->commit();

            http_response_code(201);
            return ['status' => true, 
                'message' => 'Countries imported successfully'];
                
        } catch(PDOException $e) {
            http_response_code(400);
            return ['status' => false, 
                'message' => 'Error Importing file: ' . $e->getMessage()];
        }
    }

    /**
     * Listing Countries with pagination and search
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
            $sql .= " WHERE country_code LIKE :search OR currency_code LIKE :search 
                          OR iso2_code LIKE :search OR iso3_code LIKE :search
                          OR iso_numeric_code LIKE :search OR fips_code LIKE :search
                          OR calling_code LIKE :search OR common_name LIKE :search
                          OR official_name LIKE :search OR endonym LIKE :search
                          OR demonym LIKE :search";
        }
        $sql .= " ORDER BY official_name ASC ";
        $paginate = new Paginate($this->_conn, $sql);
        $countries = $paginate->paginate($limit, $page);
        if (empty($countries['data'])) {
            return $countries;
        }
        $countryData = $countries['data'][0];
        unset($countries['data']);
        foreach ($countryData as $key => $country) {
            $countries['data'][$key]['country_code'] = utf8_decode($country['country_code']);
            $countries['data'][$key]['currency_code'] = utf8_decode($country['currency_code']);
            $countries['data'][$key]['iso2_code'] = utf8_decode($country['iso2_code']);
            $countries['data'][$key]['iso3_code'] = utf8_decode($country['iso3_code']);
            $countries['data'][$key]['iso_numeric_code'] = utf8_decode($country['iso_numeric_code']);
            $countries['data'][$key]['fips_code'] = utf8_decode($country['fips_code']);
            $countries['data'][$key]['calling_code'] = utf8_decode($country['calling_code']);
            $countries['data'][$key]['common_name'] = utf8_decode($country['common_name']);
            $countries['data'][$key]['official_name'] = utf8_decode($country['official_name']);
            $countries['data'][$key]['endonym'] = utf8_decode($country['endonym']);
            $countries['data'][$key]['demonym'] = utf8_decode($country['demonym']);
        }
        return $countries;
    }
}
