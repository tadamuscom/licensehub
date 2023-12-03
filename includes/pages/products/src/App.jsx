import React from "react";
import Header from "./components/layout/Header";
import ProductTable from "./components/ProductTable";
import LinkButton from "./components/LinkButton";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        console.log( 'btn pressed' )
    }

    return (
        <div>
            <Header pageTitle='Products' />
            <LinkButton click={ newOnClick } label='Add Product' />
            <ProductTable />
        </div>
    );
}

export default App;