/*global ajaxurl*/
// create a mutation observer to look for added 'attachments' in the media uploader
// from https:// www.sitepoint.com/wordpress-svg/ plus modifications & fixes
new MutationObserver(function (mutations) {
    //  look through all mutations that just occurred
    for (let i = 0; i < mutations.length; i++) {

        //  look through all added nodes of this mutation
        for (let j = 0; j < mutations[i].addedNodes.length; j++) {

            // get the applicable element
            let element = $(mutations[i].addedNodes[j]);

            // execute only if we have a class
            if (element.attr('class')) {

                // find all 'attachments'
                if (element.attr('class').indexOf('attachment') != -1) {

                    // find attachment inner (which contains subtype info)
                    let attachmentPreview = element.children('.attachment-preview');
                    if (attachmentPreview.length != 0) {

                        // only run for SVG elements
                        if (attachmentPreview.attr('class').indexOf('subtype-svg+xml') != -1) {

                            // bind an inner function to element so we have access to it.
                            // eslint-disable-next-line no-unused-vars
                            let handler = function (element) {
                                // do an AJAX call to get the URL
                                $.ajax({
                                    url: ajaxurl,
                                    data: {
                                        'action'        : 'svg_get_attachment_url',
                                        'attachmentID'  : element.attr('data-id'),
                                    },
                                    success: function (data) {
                                        if (data) {
                                            // replace the default image with the SVG
                                            element.find('img').attr('src', data);
                                        }
                                    },
                                });
                            }(element);
                        }
                    }
                }
            }
        }
    }
}).observe(document.body, {
    childList: true,
    subtree: true,
});
