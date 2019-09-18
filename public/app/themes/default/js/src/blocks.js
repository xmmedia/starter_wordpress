const { registerBlockType } = wp.blocks;

registerBlockType('default-theme/example', {
    title: 'Static Content',
    icon: 'format-aside',
    category: 'custom',
    edit () {
        return <div className="p-5 bg-red-800 text-white">Hello World, (in the editor).</div>;
    },
    save () {
        return <div className="p-5 bg-red-800 text-white">Hello World, (on the page).</div>;
    },
});
