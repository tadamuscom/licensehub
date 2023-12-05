import React, {useState} from 'react';
import FormGroup from "./form/FormGroup";
import Label from "./form/Label";
import HeadingTwo from "./typography/HeadingTwo";
import Button from "./form/Button";
import {resetForm, triggerError} from "../helpers/formHelper";
import Select from "./form/Select";
import SelectOption from "./form/SelectOption";
import SingleTextInput from "./form/SingleTextInput";
import DatePicker from "./form/DatePicker";

function NewLicenseKey(props ) {
    const submit = ( e ) => {
        e.preventDefault();

        const btn = document.querySelector( '#' + e.target.id + ' input[type="submit"]' );
        btn.disabled = true;
        btn.value = 'Loading...';

        resetForm( e.target );

        const formData = new FormData( e.target );
        const user = formData.get( 'lchb-user' );
        const product = formData.get( 'lchb-product' );
        const expiresAt = formData.get( 'lchb-expires-at' );

        let go = true;

        if( user.length < 1 ){
            triggerError( 'lchb-user', 'User cannot be empty' );

            go = false;
        }

        if( product.length < 1 ){
            triggerError( 'lchb-product', 'Product cannot be empty' );

            go = false;
        }

        if( expiresAt.length < 1 ){
            triggerError( 'lchb-expires-at', 'Expiry date cannot be empty' );

            go = false;
        }

        const status = document.getElementById( 'tada-status' );

        if( ! go ) {
            btn.value = 'Save License Key';
            btn.disabled = false;

            status.style.color = 'red';
            status.innerText = 'Please fix the errors above ❌';

            if( status.classList.contains( 'tada-hidden' ) ){
                status.classList.remove( 'tada-hidden' );
            }

            return;
        }

        wp.apiFetch( {
            path: '/tadamus/lchb/v1/new-license-key',
            method: 'POST',
            data:{
                nonce: lchb_license_keys.nonce,
                user: user,
                product: product,
                expires_at: expiresAt
            }
        } ).then( ( result ) => {
            btn.value = 'Save License Key';
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

    const preProducts = [];
    const preUsers = [];

    lchb_license_keys.products.forEach( ( element, index ) => {
        preProducts.push( <SelectOption id={ element.id } label={ element.name } key={ index } /> );
    } );

    lchb_license_keys.users.forEach( ( element, index ) => {
        preUsers.push( <SelectOption id={ element.data.ID } label={ element.data.user_email } key={ index } /> );
    } );

    const [ products, setProducts ] = useState( preProducts );
    const [ users, setUsers ] = useState( preUsers );

    return (
        <div style={{
            marginBottom: '15px',
            display: 'none'
        }} id='tada-new-license-key'>
            <HeadingTwo label="New License Key" />
            <form onSubmit={ submit } id='tada-add-license-key-form'>
                <FormGroup>
                    <Label htmlFor='lchb-user' label='Users' />
                    <Select id='lchb-user' name='lchb-user' options={ users } />
                </FormGroup>
                <FormGroup>
                    <Label htmlFor='lchb-product' label='Product' />
                    <Select id='lchb-product' name='lchb-product' options={ products } />
                </FormGroup>
                <FormGroup>
                    <Label htmlFor='lchb-expires-at' label='Expiry Date' />
                    <DatePicker id='lchb-expires-at' name='lchb-expires-at' />
                </FormGroup>
                <FormGroup extraClass="tada-form-submit">
                    <Button label='Save License Key' />
                    <p id='tada-status' className='tada-hidden'></p>
                </FormGroup>
            </form>
        </div>
    );
}

export default NewLicenseKey;