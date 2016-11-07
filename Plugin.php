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
    public function __construct()
    {
        $this->version = $this->pluginVersion();

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

    /**
     * Returns current plugin version.
     *
     * @return string Plugin version
     */
    public function pluginVersion()
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }
        $plugin_folder = get_plugins('/'.plugin_basename(dirname(__FILE__)));
        $plugin_file = basename((__FILE__));

        return $plugin_folder[$plugin_file]['Version'];
    }

    /**
     * Add lazyloader JavaScript class to wp_head.
     */
    public function lazyloader()
    {
        $scriptpath = plugins_url('Resources/Public/JavaScript/lazyloader.js', __FILE__);

        echo '<script>(function(i,s,o,g,r,a,m){i["FrpCustomizedvideoembed"]=r;i[r]=i[r]||function(){'.
            '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),'.
            'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)'.
            '})(window,document,"script","'.$scriptpath.'","myUniqueJavaScriptFunctionNameWhichWillBeChangedSoon");</script>';
    }

    /**
     * Add inline script to register lazyload init for a single video player.
     *
     * @param string $playerID A unique ID string for the current player.
     *
     * @return string The script tage which will initialize the current player.
     */
    public function initLazyLoad($playerID)
    {
        return '<script data-plugin="mhm_lazyloadvideo">myUniqueJavaScriptFunctionNameWhichWillBeChangedSoon(["'.$playerID.'"]);</script>';
    }

    /**
     * Parses the HTML of the oEmbed tag and returns a modified version.
     *
     * @param string $html The original oEmbed HTML.
     * @param string $url  The URL of the source video, as added to the editor.
     * @param array  $args An array of arguments passed to the oEmbed function.
     *
     * @return string The modified HTML string
     */
    public function lazyLoadVideo($html, $url, $args)
    {
        $html_was = '<noscript>'.$html.'</noscript>';

        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case 'vimeo.com':
            case 'www.vimeo.com':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-lazyload="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID).$html_was;
                break;

            case 'youtube.com':
            case 'www.youtube.com':
            case 'youtu.be':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-lazyload="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID).$html_was;
                break;
        }

        return $html;
    }
}

new Plugin();
