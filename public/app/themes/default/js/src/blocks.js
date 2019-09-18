const { registerBlockType } = wp.blocks;

registerBlockType('default-theme/example', {
    title: 'Example: Basic',
    icon: 'text-page',
    category: 'layout',
    edit () {
        return <div className="p-5 bg-red-500 text-white">Hello World, (in the editor).</div>;
    },
    save () {
        return <div className="p-5 bg-red-500 text-white">Hello World, (on the page).</div>;
    },
});
