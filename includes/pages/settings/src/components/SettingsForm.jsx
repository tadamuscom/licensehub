import React from 'react';
import { FormGroup, Button } from '@tadamus/wpui';
import Stripe from "./Stripe";
import FluentCRM from "./FluentCRM";

function SettingsForm(props) {
    return (
        <div>
            <form onSubmit={ props.onSubmit } id='tada-add-product-form'>
                <Stripe />
                <FluentCRM />
                <FormGroup extraClass="tada-form-submit">
                    <Button label="Save Settings" />
                    <p id='tada-status' className='tada-hidden'></p>
                </FormGroup>
            </form>
        </div>
    );
}

export default SettingsForm;