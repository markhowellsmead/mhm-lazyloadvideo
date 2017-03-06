<?php
/*
Plugin Name: Lazy load video players
Plugin URI: https://github.com/mhmli/mhm-lazyloadvideo
Description: Modifies the HTML output of any video players embedded in the content area using the oEmbed technique. Any video player which is included on the page will only be loaded if/when it is visible within the current browser window. Requires JavaScript. The original player will be displayed if JavaScript is inactive on the page.
Author: Mark Howells-Mead
Version: 1.3.2
Author URI: https://markweb.ch/
*/

namespace MHM\Lazyloadvideo;

class Plugin
{
    public function __construct()
    {
        $this->version = $this->pluginVersion();

        add_filter('embed_oembed_html', array($this, 'lazyLoadVideo'), 10, 2);
        add_filter('oembed_result', array($this, 'lazyLoadVideo'), 10, 2);
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
     * Add lazyloader JavaScript class to wp_head. The function/object 'lazyloadvideo'
     * is available instantly and the script is loaded dynamically. As soon as the
     * script loads, it executes.
     */
    public function lazyloader()
    {
        $scriptpath = plugins_url('Resources/Public/JavaScript/mhmlazyloadvideo.js?v'.$this->version, __FILE__);

        echo '<script>(function(i,s,o,g,r,a,m){i["mhmlazyloadvideo_script"]=r;i[r]=i[r]||function(){'.
            '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),'.
            'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)'.
            '})(window,document,"script","'.$scriptpath.'","mhmlazyloadvideo");</script>';
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
        return '<script data-plugin="mhm_lazyloadvideo">mhmlazyloadvideo("'.$playerID.'");</script>';
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
    public function lazyLoadVideo($html, $url)
    {
        $html_was = '<noscript>'.$html.'</noscript>';

        $host = parse_url($url, PHP_URL_HOST);
        switch ($host) {
            case 'vimeo.com':
            case 'www.vimeo.com':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-mhmlazyloadvideo="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID).$html_was;
                break;

            case 'youtube.com':
            case 'www.youtube.com':
            case 'youtu.be':
                $uniqueID = uniqid();
                $html = str_replace(' src="', ' data-mhmlazyloadvideo="'.$uniqueID.'" data-src="', $html).$this->initLazyLoad($uniqueID).$html_was;
                break;
        }

        return $html;
    }
}

new Plugin();
