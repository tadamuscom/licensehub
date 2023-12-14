import React from "react";
import Header from "./components/layout/Header";
import HeadingTwo from "./components/typography/HeadingTwo";
import FormGroup from "./components/form/FormGroup";
import CheckBox from "./components/form/CheckBox";
import Button from "./components/form/Button";
import {resetForm, triggerError} from "./helpers/formHelper";
import Label from "./components/form/Label";
import SingleTextInput from "./components/form/SingleTextInput";
import SinglePasswordInput from "./components/form/SinglePasswordInput";
import SettingsForm from "./components/SettingsForm";

function App( props ) {
    const submit = ( e ) => {
        e.preventDefault();

        const btn = document.querySelector( '#' + e.target.id + ' input[type="submit"]' );
        btn.disabled = true;
        btn.value = 'Loading...';

        resetForm( e.target );

        const formData = new FormData( e.target );
        const stripeIntegration = formData.get( 'tada-stripe-integration' );
        const stripePublicKey = formData.get( 'tada-public-key' );
        const stripePrivateKey = formData.get( 'tada-private-key' );
        const fluentCRMIntegration = formData.get( 'tada-fluentcrm-integration' );

        let go = true;

        if(  stripeIntegration ){
            if( stripePublicKey.length < 1 ) {
                triggerError( 'tada-public-key', 'Public key cannot be empty' );

                go = false;
            }

            if( stripePrivateKey.length < 1 ) {
                triggerError( 'tada-private-key', 'Private key cannot be empty' );

                go = false;
            }
        }

        const status = document.getElementById( 'tada-status' );

        if( ! go ) {
            btn.value = 'Save Settings';
            btn.disabled = false;

            status.style.color = 'red';
            status.innerText = 'Please fix the errors above ❌';

            if( status.classList.contains( 'tada-hidden' ) ){
                status.classList.remove( 'tada-hidden' );
            }

            return;
        }

        wp.apiFetch( {
            path: '/tadamus/lchb/v1/general-settings',
            method: 'POST',
            data:{
                nonce: lchb_settings.nonce,
                stripe_integration: stripeIntegration,
                stripe_public_key: stripePublicKey,
                stripe_private_key: stripePrivateKey,
                fluentcrm_integration: fluentCRMIntegration
            }
        } ).then( ( result ) => {
            btn.value = 'Save Settings';
            btn.disabled = false;
            status.innerText = result.data.message;

            if( result.success ){
                status.style.color = 'green';
            }else{
                status.style.color = 'red';
                status.innerText = status.innerText + ' ❌'
            }

            if( status.classList.contains( 'tada-hidden' ) ){
                status.classList.remove( 'tada-hidden' );
            }
        } );
    }

    return (
        <div>
            <Header pageTitle='Settings' />
            <HeadingTwo label='Settings' />
            <SettingsForm onSubmit={ submit } />
        </div>
    );
}

export default App;