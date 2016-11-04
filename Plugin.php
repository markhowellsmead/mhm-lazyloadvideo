<?php
/*
Plugin Name: Lazy load video players
Plugin URI: https://github.com/mhmli/mhm-lazyloadvideo
Description: Work in progress.
Author: Mark Howells-Mead
Version: 0.0.1
Author URI: https://markweb.ch/
*/

namespace MHM\Layzyloadvideo;

class Plugin
{
    public $version = '1.1';

    public function __construct()
    {
        add_filter('embed_oembed_html', array($this, 'lazyLoadVideo'), 10, 3);
        add_action('wp_head', array($this, 'lazyloader'));
    }

    public function dump($var, $die = false)
    {
        echo '<pre>'.print_r($var, 1).'</pre>';
        if ($die) {
            die();
        }
    }

    public function lazyloader()
    {
        $scriptpath = plugins_url('Resources/Public/JavaScript/lazyloader.js', __FILE__);

        echo '<script>(function(i,s,o,g,r,a,m){i["FrpCustomizedvideoembed"]=r;i[r]=i[r]||function(){'.
            '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),'.
            'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)'.
            '})(window,document,"script","'.$scriptpath.'","myUniqueJavaScriptFunctionNameWhichWillBeChangedSoon");</script>';
    }

    public function initLazyLoad($playerID)
    {
        return '<script>myUniqueJavaScriptFunctionNameWhichWillBeChangedSoon(["'.$playerID.'"]);</script>';
    }

    public function lazyLoadVideo($html, $url, $args)
    {
        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case 'vimeo.com':
            case 'www.vimeo.com':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-lazyload="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID);
                break;

            case 'youtube.com':
            case 'www.youtube.com':
            case 'youtu.be':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-lazyload="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID);
                break;
        }

        return $html;
    }
}

new Plugin();
