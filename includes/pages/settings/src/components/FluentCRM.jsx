import React from 'react';
import FormGroup from "./form/FormGroup";
import CheckBox from "./form/CheckBox";

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