import React from "react";
import Header from "./components/layout/Header";
import Table from "./components/Table";
import LinkButton from "./components/LinkButton";
import NewLicenseKey from "./components/NewLicenseKey";
import HeadingTwo from "./components/typography/HeadingTwo";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        const newProduct = document.getElementById( 'tada-new-license-key' );

        event.target.style.display = 'none';
        newProduct.style.display = 'inherit';

    }

    return (
        <div>
            <Header pageTitle='License Keys' />
            <LinkButton click={ newOnClick } label='Add License Key' />
            <NewLicenseKey />
            <HeadingTwo label='License Keys' />
            <Table headers={ lchb_license_keys.fields } rows={ lchb_license_keys.keys } />
        </div>
    );
}

export default App;