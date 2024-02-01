import React from 'react';
import { Label, SingleTextInput, FormGroup } from '@tadamus/wpui';

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