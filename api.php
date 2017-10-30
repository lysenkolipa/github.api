<?php


/**
 * Class API works with GITHUB API for searching information about users repositories
 * via getting data from front-end form.
 * Convert this data(array of parameters) to sting. Composes URL from the received strings.
 * Send request to GITHUB API via CURL.
 * Get response and return it at front-end.
 */
class API
{
    public $myURL;
    public $url;
    public $json;

    /**
     * API constructor.
     * @param $post
     */

    public function __construct($post)
    {
        $this->post=$post;
        $this->myURL= 'https://api.github.com/search/repositories?q=';
        $this->getURL($post);
    }


    /**
     * @param $post
     * @return string
     * Function takes an array of parameters.
     * Converts this array into a string and concatenates this string and part of url.
     */
    public function getURL($post)
    {
        $str = '';
        if (!empty($post)) {
            for($i = 0; $i < count($post['field']); $i++) {
                $_size = $post['field'][$i];
                $_operator = $post['operator'][$i];
                $_value = $post['value'][$i];
                $str = $_size . ":\"". $_operator . $_value. "\"";
            }
        }else { echo 'error'; }
            $this->url = $this->myURL.$str.'&order=desc';
        return $this->url;
    }


    /**
     * @param $url
     * @return string (JSON)
     * Function takes an string and connecting to GITHUB API via CURL methods.
     * Since access is performed to https, are used CURL options:  CURLOPT_SSL_VERIFYHOST, CURLOPT_SSL_VERIFYPEER.
     * Also, GITHUB API provides a mandatory parameter - UserAgent. That's why use CURL option CURLOPT_USERAGENT with current value.
     * Besides, for correct display json use header("Content-type:application/json")
     */
    public function getJSON($url)
    {
        $this->url = $url;
        $curl = curl_init($url);

        error_reporting(E_ALL);
        ini_set('display_errors', 'On');

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');

        $this->json = curl_exec($curl);
        curl_close($curl);

        header("Content-type:application/json");

        return $this->json;
    }


}

