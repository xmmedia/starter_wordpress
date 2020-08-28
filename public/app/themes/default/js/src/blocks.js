/*global wp*/

// wp.blocksregisterBlockType('default-theme/example', {
//     title: 'Static Content',
//     icon: 'format-aside',
//     category: 'custom',
//     edit () {
//         return <div className="p-5 bg-red-800 text-white">Hello World, (in the editor).</div>;
//     },
//     save () {
//         return <div className="p-5 bg-red-800 text-white">Hello World, (on the page).</div>;
//     },
// });

wp.domReady(function () {
    wp.blocks.unregisterBlockType('core/latest-comments');
    wp.blocks.unregisterBlockType('core/archives');
    wp.blocks.unregisterBlockType('core/code');
    wp.blocks.unregisterBlockType('core/preformatted');
    wp.blocks.unregisterBlockType('core/calendar');
    wp.blocks.unregisterBlockType('core/rss');
    wp.blocks.unregisterBlockType('core/search');
    wp.blocks.unregisterBlockType('core/tag-cloud');
    wp.blocks.unregisterBlockType('core/social-icons');
});
