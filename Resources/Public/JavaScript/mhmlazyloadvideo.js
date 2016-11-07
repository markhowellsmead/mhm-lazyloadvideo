/**
 * Parse all elements on the page which have a data-mhmlazyloadvideo attribute
 * and load each video player (in an iframe) if the element is within the
 * current viewport.
 *
 * This JavaScript DOES NOT USE JQUERY. \o/ #FTW
 *
 * Since 7.1.2016 | mhm
 */

(function() {

    /**
     * Find out whether the top edge of the element is above the bottom
     * of the browser window, and the left edge is to the left of the right
     * edge of the browser window.
     *
     * I.E., can the visitor see a part of the video player in the current view?
     */
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.left <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Load videos if they are within the current viewport.
     * Reads the contents of the enqueue object and finds the matching DOM object.
     * If it's inside the current viewport, then it puts the the data-src into the
     * src attribute, then removes the data-src and data-mhmlazyloadvideo attributes
     * so that the DOM element won't get parsed next time around.
     *
     */
    function maybeLoadVideos() {
        if(mhmlazyloadvideo.q && mhmlazyloadvideo.q.length){
            var doc = document.documentElement;
            var left = (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0);
            var top = (window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0);
            for (var i = 0, len = mhmlazyloadvideo.q.length; i < len; i++) {
                var element = document.querySelectorAll('[data-mhmlazyloadvideo="' + mhmlazyloadvideo.q[i][0] + '"]');
                if (element.length) {
                    var el = element[0];
                    if (isElementInViewport(el)){
                        el.setAttribute('src', el.dataset.src);
                        el.removeAttribute('data-src');
                        el.removeAttribute('data-mhmlazyloadvideo');
                    }
                }
            }
        }
    }

    /**
     * Run the loader immediately, then again every time the window is scrolled.
     */
    maybeLoadVideos();
    window.addEventListener('scroll', maybeLoadVideos);

})();
