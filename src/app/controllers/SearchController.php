<?php

use Phalcon\Mvc\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        $name = $this->request->getPost('search');
        if (isset($name)) {
            $this->session->set('name', $name);
        }
        $type = $this->request->get('type');
        if (!isset($type)) {
            $type = 'current';
        }
        if (!isset($name)) {
            $name =  $this->session->get('name');
        }
        $name = str_replace(" ", "%20", $name);
        $url = 'https://api.weatherapi.com/v1/' .
            $type . '.json?key=0bab7dd1bacc418689b143833220304&q=' . $name . '&aqi=yes';
        if ($type == 'history') {
            $url = 'https://api.weatherapi.com/v1/' .
                $type . '.json?key=0bab7dd1bacc418689b143833220304&q=' . $name . '&aqi=yes&dt=05-05-23';
        } elseif ($type == 'airquality') {
            $url = 'https://api.weatherapi.com/v1/current.json?key=0bab7dd1bacc418689b143833220304&q=' .
                $name . '&aqi=yes';
        }
        $currentUrl = 'https://api.weatherapi.com/v1/forecast.json?key=0bab7dd1bacc418689b143833220304&q=' .
            $name . '&aqi=yes&alerts=yes';
        $obj = new SearchController();
        $data = $obj->getData($currentUrl);
        $view = $obj->getData($url);
        $this->view->data = $data;
        $this->view->type = $type;
        $this->view->view = $view;
    }
    public function getData($url)
    {
        // Initialize a CURL session.
        $ch = curl_init();

        //grab URL and pass it to the variable.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        return json_decode($res, true);
    }
}
