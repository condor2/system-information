<?php
class ControllerToolSysinfo extends Controller {
    public function index() {
        //=== Init
        $data  = array();
        $data += $this->load->language('tool/sysinfo');

        $this->load->model('setting/store');

        //=== Document
        $this->document->setTitle($this->language->get('ext_name'));

        $this->document->addStyle('view/sysinfo/css/uikit-echothemes.min.css?v=1.2.0', 'stylesheet');
        $this->document->addStyle('view/sysinfo/sysinfo.css?v='.$this->language->get('ext_version'), 'stylesheet');
        $this->document->addScript('view/sysinfo/js/uikit.min.js?v=2.8.0');

        //=== Breadcrumbs
        $data['breadcrumbs']    = array();

        $data['breadcrumbs'][]  = array(
            'text'  => '<i class="uk-icon-home uk-icon-nano"></i>',
            'href'  => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
            'class' => ''
        );

        $data['breadcrumbs'][]  = array(
            'text'  => $this->language->get('ext_name'),
            'href'  => $this->url->link('tool/sysinfo', 'token=' . $this->session->data['token'], true),
            'class' => 'active',
        );

        //=== Content
        $data['token']          = $this->session->data['token'];
        $data['url_phpinfo']    = $this->url->link('tool/sysinfo/phpinfo', 'token=' . $this->session->data['token'], true);
        
        $data['stores']         = $this->model_setting_store->getTotalStores() + 1;
        $data['ssl']            = $this->config->get('config_secure');
        $data['seo']            = $this->config->get('config_seo_url');
        $data['maintenance']    = $this->config->get('config_maintenance');
        $data['compression']    = $this->config->get('config_compression');
        $data['captcha']        = ucwords(str_replace('_', ' ', $this->config->get('config_captcha')));
        $data['mail']           = $this->config->get('config_mail_protocol');
        $data['ftp']            = $this->config->get('config_ftp_status');
        $data['currency']       = $this->config->get('config_currency_auto');
        $data['error_display']  = $this->config->get('config_error_display');
        $data['error_log']      = $this->config->get('config_error_log');

        $data['db_server']      = $this->db->getServerInfo();
        $data['db_host']        = $this->db->getHostInfo();

        $query = $this->db->query('SELECT @@session.time_zone as timezone, now() as `datetime`;');

        $data['db_timezone']    = $query->row['timezone'];
        $data['db_datetime']    = $query->row['datetime'];
        
        $data['date_php_now']   = date('l, F d, Y');
        $data['time_php_now']   = date('h:i:s A');
        $data['date_db_now']    = date('l, F d, Y', strtotime($data['db_datetime']));
        $data['time_db_now']    = date('h:i:s A', strtotime($data['db_datetime']));
        $data['match_date']     = $data['date_php_now'] == $data['date_db_now'] ? true : false;
        $data['match_time']     = date('H:i') == date('H:i', strtotime($data['db_datetime'])) ? true : false;

        $data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');

        //=== H-MVC
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

        //=== Render
        $this->response->setOutput($this->load->view('tool/sysinfo', $data));
    }

    public function phpinfo() {
        phpinfo();
   }
}