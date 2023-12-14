import React from 'react';
import FormGroup from "./form/FormGroup";
import CheckBox from "./form/CheckBox";
import Label from "./form/Label";
import SingleTextInput from "./form/SingleTextInput";
import SinglePasswordInput from "./form/SinglePasswordInput";

function Stripe(props) {
    const onClick = ( e ) => {
        const credentials = document.getElementById( 'lchb-stripe-credentials' );

        credentials.classList.toggle( 'tada-hidden' );
    }

    if( lchb_products.stripe === 'true' ) {
        return (
            <>
                <FormGroup>
                    <CheckBox label='Stripe Integration' id='tada-stripe-integration' name='tada-stripe-integration'
                              value={(lchb_settings.stripe_integration === 'true')} wrapperOnClick={onClick}/>
                </FormGroup>
                <div className={(lchb_settings.stripe_integration === 'true') ? '' : 'tada-hidden'}
                     id='lchb-stripe-credentials' style={{
                    marginTop: '25px',
                    marginLeft: '10px'
                }}>
                    <FormGroup>
                        <Label htmlFor='lchb-public-key' label='Public Key'/>
                        <SingleTextInput id='tada-public-key' name='tada-public-key'
                                         value={(lchb_settings.stripe_public_key) ? lchb_settings.stripe_public_key : ''}/>
                    </FormGroup>

                    <FormGroup>
                        <Label htmlFor='lchb-private-key' label='Private Key'/>
                        <SinglePasswordInput id='tada-private-key' name='tada-private-key'
                                             value={(lchb_settings.stripe_private_key) ? lchb_settings.stripe_private_key : ''}/>
                    </FormGroup>
                </div>
            </>
        );
    }
}

export default Stripe;