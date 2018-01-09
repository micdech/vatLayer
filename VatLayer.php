<?php
 
/**
 * VAT - VAT checker
 *
 * @author      Michele de Chiara <micdech@gmail.com>
 * @copyright   2015 Michele de Chiara
 * @link        http://michele.dechiara.org
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Helpers;

/**
 * PartitaIVA
 *
 * Partita IVA checker
 *
 * @author Michele de Chiara
 */
class vatLayer {
	
	// set API Endpoint and Access Key
	
	// endpoint 1: validate | Simple VAT number validation;
	// endpoint 2: rate | VAT rate for single EU member state;
	// endpoint 3: rate_list | VAT rates for all EU member states;
	// endpoint 4: price | Price calculation;
	private $endpoint;
	
	/**
	 * Chiave di accesso a vatLayer
	 */ 
	private	$access_key;
	
	/**
	 * url per accesso a alle API vatLayer
	 */
	private $url;
	
	/**
	 * risposta alla chiamata su vatLayer
	 */ 
	private $response;
	
	/**
	 * risposta completa alla chiamata su vatLayer
	 */
	private $resultData; 
	
	// Access and use your preferred validation result objects
	// $validationResult['valid'];
	// $validationResult['query'];
	// $validationResult['company_name'];
	// $validationResult['company_address'];
    
    public function __construct() {
        $this->url = 'http://apilayer.net/api/'.$this->endpoint.'?access_key='.$this->access_key;
        $this->access_key = 'f7b9c4a084ec7cce5d28580376ffb6e8';
    }
    
    /**
     * Return the check result
     *
     * @params string $vat VAT (Partita IVA) to check
     * @return bool Check result
     */
    public static function checkVat($vat) {
        $pattern = "/^[0-9]{11}$/i";
        $check = preg_match($pattern, trim($vat)) ? true : false;
        
        if($check === true){
            $check = "Partita iva formalmente valida";
        } else {
            $check = "Partita iva non valida";
        }
        
        return $check;
    }
    
    /**
     * Return the validate result
     *
     * @params string $vat VAT (Partita IVA) to check
     * @return bool Check result
     */
    public static function validate($vat) {

        $this->setEndPoint('validate');
        
        try{
			// Initialize CURL:
			$ch = curl_init($this->url.'&vat_number='.$vat);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$this->setData(json_decode($json, true));
			
			return $this->setResponse($this->data['valid']);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    /**
     * Return the rate result
     *
     * @params string $vat VAT (Partita IVA) to check
     * @return bool Check result
     */
    public static function rate($type, $data) {
        // $type = 'country_code' - 'ip_address' - 'use_client_ip';
        // $data = 'IT' - '192.168.2.25' - '1';
        
        $this->setEndPoint('rate');
        
        try{
			// Initialize CURL:
			$ch = curl_init($this->url.'&'.$type.'='.$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$this->setData(json_decode($json, true));
			
			return $this->setResponse($this->data['valid']);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    /**
     * Return the rate_list result
     *
     * @params string $vat VAT (Partita IVA) to check
     * @return bool Check result
     */
    public static function rate_list() {
        
        $this->setEndPoint('rate_list');
        
        try{
			// Initialize CURL:
			$ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$this->setData(json_decode($json, true));
			
			return $this->setResponse($this->data['valid']);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    /**
     * Return the price result
     *
     * @params string $vat VAT (Partita IVA) to check
     * @return bool Check result
     */
    public static function price($amount, $countryCode) {
        
        $this->setEndPoint('price');
        
        try{
			// Initialize CURL:
			$ch = curl_init($this->url.'&amount='.$vat.'&country_code='.$countryCode);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$this->setData(json_decode($json, true));
			
			return $this->setResponse($this->data['valid']);
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    private function setEndPoint($endpoint){
		$this->endpoint = $endpoint;
	}
    
    private function setResponse($response){
		$this->response = $response;
	}
    
    private function setData($resultData){
		$this->data = $resultData;
	}
    
    public function getEndPoint(){
		return $this->endpoint;
	}
    
    public function getResponse(){
		return $this->response;
	}
    
    public function getData(){
		return $this->data;
	}
    
}

?>
