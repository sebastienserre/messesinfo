const {registerBlockType} = wp.blocks
const {__} = wp.i18n

registerBlockType('messesinfo/messesinfo', {
    title: __( 'Messesinfo', 'messesinfo'),
    category: 'widgets',
    supports: {
        html: false
    },

    edit ( {className}) {
        return <div className={className}>Bonjour</div>
    },
    save () {
        return null;
    }
})