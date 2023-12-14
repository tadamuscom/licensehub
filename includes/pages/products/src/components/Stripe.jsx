import React from 'react';
import Label from "./form/Label";
import SingleTextInput from "./form/SingleTextInput";
import FormGroup from "./form/FormGroup";

function Stripe(props) {
    if( lchb_products.stripe === 'true' ) {
        return (
            <>
                <FormGroup>
                    <Label htmlFor='lchb-stripe-id' label='Stripe Product ID'/>
                    <SingleTextInput id='lchb-stripe-id' name='lchb-stripe-id' value=''/>
                </FormGroup>
            </>
        );
    }
}

export default Stripe;