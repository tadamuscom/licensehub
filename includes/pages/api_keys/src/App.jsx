import React from "react";
import Header from "./components/layout/Header";
import Table from "./components/Table";
import LinkButton from "./components/LinkButton";
import HeadingTwo from "./components/typography/HeadingTwo";
import NewAPIKey from "./components/NewAPIKey";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        const newProduct = document.getElementById( 'tada-new-api-key' );

        event.target.style.display = 'none';
        newProduct.style.display = 'inherit';

    }

    return (
        <div>
            <Header pageTitle='API Keys' />
            <LinkButton click={ newOnClick } label='Add API Key' />
            <NewAPIKey />
            <HeadingTwo label='API Keys' />
            <Table headers={ lchb_api_keys.fields } rows={ lchb_api_keys.keys } />
        </div>
    );
}

export default App;