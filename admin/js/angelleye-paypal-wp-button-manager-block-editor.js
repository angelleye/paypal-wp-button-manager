(function (blocks, element, components) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var SelectControl = components.SelectControl;

    registerBlockType('angelleye-paypal-wp-button-manager-block/block', {
        title: wbp_block_editor.title, // Block title
        icon: function(){
            return el('img', {src: wbp_block_editor.image_url } )
        },
        category: 'common', // Block category

        attributes: {
            dropdownValue: {
                type: 'string',
                default: '' // Default selected value
           }
        },

        edit: function (props) {
           var dropdownValue = props.attributes.dropdownValue;

            function onChangeDropdownValue(newDropdownValue) {
               props.setAttributes({ dropdownValue: newDropdownValue });
            }

            var options = wbp_block_editor.buttons;

            return el(
               'div',
                null,
                el(
                    SelectControl,
                    {
                       label: wbp_block_editor.dropdown_label,
                       value: dropdownValue,
                       options: options,
                       onChange: onChangeDropdownValue
                   }
               )
           );
       },

       save: function (props) {
           var dropdownValue = props.attributes.dropdownValue;
           if( !dropdownValue ){
                return null;
           }
           return el('p', null, '[' + wbp_block_editor.shortcode + ' id="' + dropdownValue + '"]');
       }
   });
}(window.wp.blocks, window.wp.element, window.wp.components));