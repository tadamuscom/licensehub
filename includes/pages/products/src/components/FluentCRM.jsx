import React from 'react';
import { Label, SingleTextInput, FormGroup, HelperText } from '@tadamus/wpui';

function FluentCrm(props) {
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