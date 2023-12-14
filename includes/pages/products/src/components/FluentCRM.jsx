import React from 'react';
import Label from "./form/Label";
import SingleTextInput from "./form/SingleTextInput";
import FormGroup from "./form/FormGroup";
import HelperText from "./form/HelperText";

function FluentCrm(props) {
    console.log('active')
    if( lchb_products.fluentcrm_integration === 'true' ){
        return (
            <>
                <FormGroup>
                    <Label htmlFor='lchb-fluentcrm-lists' label='FluentCRM List IDs'/>
                    <SingleTextInput id='lchb-fluentcrm-lists' name='lchb-fluentcrm-lists' value=''/>
                    <HelperText content='List IDs separated by comma' />
                </FormGroup>
                <FormGroup>
                    <Label htmlFor='lchb-fluentcrm-tags' label='FluentCRM Tag IDs'/>
                    <SingleTextInput id='lchb-fluentcrm-tags' name='lchb-fluentcrm-tags' value=''/>
                    <HelperText content='Tag IDs separated by comma' />
                </FormGroup>
            </>
        );
    }
}

export default FluentCrm;