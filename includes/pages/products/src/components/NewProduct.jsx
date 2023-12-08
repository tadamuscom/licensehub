import React from 'react';
import FormGroup from "./form/FormGroup";
import Label from "./form/Label";
import SingleTextInput from "./form/SingleTextInput";
import HeadingTwo from "./typography/HeadingTwo";
import Button from "./form/Button";
import {resetForm, triggerError} from "../helpers/formHelper";

function NewProduct( props ) {
    const submit = ( e ) => {
        e.preventDefault();

        const btn = document.querySelector( '#' + e.target.id + ' input[type="submit"]' );
        btn.disabled = true;
        btn.value = 'Loading...';

        resetForm( e.target );

        const formData = new FormData( e.target );
        const name = formData.get( 'lchb-name' );
        const stripeID = formData.get( 'lchb-stripe-id' );

        let go = true;

        if( name.length < 1 ){
            triggerError( 'lchb-name', 'Name cannot be empty' );

            go = false;
        }

        const data = {
            nonce: lchb_products.nonce,
            name: name
        };

        if( stripeID ){
            if( stripeID.length < 1 ){
                triggerError( 'lchb-stripe-id', 'Stripe ID cannot be empty' );

                go = false;
            }

            data.stripe_id = stripeID;
        }

        console.log(data);

        const status = document.getElementById( 'tada-status' );

        if( ! go ) {
            btn.value = 'Save Product';
            btn.disabled = false;

            status.style.color = 'red';
            status.innerText = 'Please fix the errors above ❌';

            if( status.classList.contains( 'tada-hidden' ) ){
                status.classList.remove( 'tada-hidden' );
            }

            return;
        }

        wp.apiFetch( {
            path: '/tadamus/lchb/v1/new-product',
            method: 'POST',
            data: data
        } ).then( ( result ) => {
            btn.value = 'Save Product';
            btn.disabled = false;
            status.innerText = result.data.message + ' ✅';

            if( result.success ){
                status.style.color = 'green';
                window.location.reload();
            }else{
                status.style.color = 'red';
                status.innerText = status.innerText + ' ❌'
            }

            if( status.classList.contains( 'tada-hidden' ) ){
                status.classList.remove( 'tada-hidden' );
            }
        } );
    }

    if( lchb_products.stripe === 'true' ){
        return (
            <div style={{
                marginBottom: '15px',
                display: 'none'
            }} id='tada-new-product'>
                <HeadingTwo label="New Product" />
                <form onSubmit={ submit } id='tada-add-product-form'>
                    <FormGroup>
                        <Label htmlFor='lchb-name' label='Name' />
                        <SingleTextInput id='lchb-name' name='lchb-name' value='' />
                    </FormGroup>
                    <FormGroup>
                        <Label htmlFor='lchb-stripe-id' label='Stripe Product ID' />
                        <SingleTextInput id='lchb-stripe-id' name='lchb-stripe-id' value='' />
                    </FormGroup>
                    <FormGroup extraClass="tada-form-submit">
                        <Button label='Save Product' />
                        <p id='tada-status' className='tada-hidden'></p>
                    </FormGroup>
                </form>
            </div>
        );
    }

    return (
        <div style={{
            marginBottom: '15px',
            display: 'none'
        }} id='tada-new-product'>
            <HeadingTwo label="New Product" />
            <form onSubmit={ submit } id='tada-add-product-form'>
                <FormGroup>
                    <Label htmlFor='lchb-name' label='Name' />
                    <SingleTextInput id='lchb-name' name='lchb-name' value='' />
                </FormGroup>
                <FormGroup extraClass="tada-form-submit">
                    <Button label='Save Product' />
                    <p id='tada-status' className='tada-hidden'></p>
                </FormGroup>
            </form>
        </div>
    );
}

export default NewProduct;