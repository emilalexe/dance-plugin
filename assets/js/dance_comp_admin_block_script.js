/**
 * Created by Emil on 21.04.2020.
 */
/**
 * Created by Emil on 4/22/2017.
 */
( function( wp ) {
    /**
     * Registers a new block provided a unique name and an object defining its behavior.
     * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
     */
    var registerBlockType = wp.blocks.registerBlockType;
    /**
     * Returns a new element of given type. Element is an abstraction layer atop React.
     * @see https://github.com/WordPress/gutenberg/tree/master/packages/element#element
     */
    var el = wp.element.createElement;
    /**
     * Retrieves the translation of text.
     * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
     */
    var __ = wp.i18n.__;

    /**
     * Every block starts by registering a new block type definition.
     * @see https://wordpress.org/gutenberg/handbook/block-api/
     */
    registerBlockType( 'widgets/dance-comp', {
        /**
         * This is the display title for your block, which can be translated with `i18n` functions.
         * The block inserter will show this name.
         */
        title: __( 'Dance Comp' ),

        /**
         * This is a short description for your block, which can be translated with our translation functions.
         * This will be shown in the block inspector.
         */

        description: __( 'Block showing active Competition.' ),

        /**
         * Blocks are grouped into categories to help users browse and discover them.
         * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
         */
        category: 'widgets',

        /**
         * An icon property should be specified to make it easier to identify a block.
         * These can be any of WordPress’ Dashicons, or a custom svg element.
         */

        /** Specifying a dashicon for the block
         icon: 'book-alt',

         // Specifying a custom svg for the block
         icon: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z" /><path d="M19 13H5v-2h14v2z" /></svg>',

         /**
         * Optional block extended support features.
         */
        supports: {
            // Removes support for an HTML mode.
            html: true,
        },

        /**
         * The edit function describes the structure of your block in the context of the editor.
         * This represents what the editor will render when the block is used.
         * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
         *
         * @param {Object} [props] Properties passed from the editor.
         * @return {Element}       Element to render.
         */
        edit: function( props ) {
            return el(
                'div',
                { className: props.className },
                __( 'Online users' )
            );
        },

        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into `post_content`.
         * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
         *
         * @return {Element}       Element to render.
         */
        save: function() {
            return el(
                'p',
                {},
                __( '[dance_comp]' )
            );
        }
    } );
} )(
    window.wp
);