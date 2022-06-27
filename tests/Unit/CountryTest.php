<?php

use PHPUnit\Framework\TestCase;

final class CountryTest extends TestCase
{
    public function testCountryImport()
    {
        $publicUrl = "http://agpaytech.test/country/import.php";
        $file = "../../02-Countries.csv";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $publicUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-type: text/csv']);
        $cfile = new CurlFile($file,  'text/csv');
        $data = array('country' => $cfile);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch));
        print_r($result); die;
        $this->assertTrue($result->status, $result->message);
    }
}