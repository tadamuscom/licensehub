import React from 'react';
import { FormGroup, CheckBox } from '@tadamus/wpui';

function FluentCrm(props) {
    return (
        <>
            <FormGroup>
                <CheckBox label='FluentCRM Integration' id='tada-fluentcrm-integration' name='tada-fluentcrm-integration' value={ (lchb_settings.fluentcrm_integration === 'true') } />
            </FormGroup>
        </>
    );
}

export default FluentCrm;