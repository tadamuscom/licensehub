import React from "react";
import Header from "./components/layout/Header";
import Table from "./components/Table";
import LinkButton from "./components/LinkButton";
import NewLicenseKey from "./components/NewLicenseKey";
import HeadingTwo from "./components/typography/HeadingTwo";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        if( lchb_license_keys.products.length > 0 ){
            const newProduct = document.getElementById( 'tada-new-license-key' );

            event.target.style.display = 'none';
            newProduct.style.display = 'inherit';
        }
    }

    return (
        <div>
            <Header pageTitle='License Keys' />
            <LinkButton click={ newOnClick } label='Add License Key' extraClass={ ( lchb_license_keys.products.length < 1 ) ? 'tada-disabled' : '' } />
            <p className={ ( lchb_license_keys.products.length > 0 ) ? 'tada-error-message tada-hidden' : 'tada-error-message' }>You need to add products before you can create a license.</p>
            <NewLicenseKey />
            <HeadingTwo label='License Keys' />
            <Table headers={ lchb_license_keys.fields } rows={ lchb_license_keys.keys } />
        </div>
    );
}

export default App;