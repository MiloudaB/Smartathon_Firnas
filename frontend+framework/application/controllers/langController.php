<?php

    defined('BASEPATH') OR exit('No direct script access allowed'); 
    class langController extends CI_Controller {
        public function index() {
            $lang_abv = $this->uri->segment(2); //language abbreviations
            if ($this->agent->is_referral()){
                $callback = $this->agent->referrer();
            }else {
                $callback = base_url();
            }
            if ($lang_abv == 'fr') {
                $this->session->set_userdata('site_lang', 'french');
                $this->session->set_userdata('dir', 'ltr');
            }elseif ($lang_abv == 'ar') {
                $this->session->set_userdata('site_lang', 'arabic');
                $this->session->set_userdata('dir', 'rtl');
            }elseif ($lang_abv == 'en') {
                $this->session->set_userdata('site_lang', 'english');
                $this->session->set_userdata('dir', 'ltr');
            }else {
                $this->session->set_userdata('site_lang', 'arabic');
                $this->session->set_userdata('dir', 'rtl');
            }
            redirect($callback);
        }
    }