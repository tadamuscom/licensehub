import React from "react";
import Header from "./components/layout/Header";
import Table from "./components/Table";
import LinkButton from "./components/LinkButton";
import NewProduct from "./components/NewProduct";
import HeadingTwo from "./components/typography/HeadingTwo";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        const newProduct = document.getElementById( 'tada-new-product' );

        event.target.style.display = 'none';
        newProduct.style.display = 'inherit';
    }

    return (
        <div>
            <Header pageTitle='Products' />
            <LinkButton click={ newOnClick } label='Add Product' />
            <NewProduct />
            <HeadingTwo label='Products' />
            <Table headers={ lchb_products.fields } rows={ lchb_products.products } />
        </div>
    );
}

export default App;