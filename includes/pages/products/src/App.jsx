import React from "react";
import Header from "./components/layout/Header";
import ProductTable from "./components/ProductTable";
import LinkButton from "./components/LinkButton";
import NewProduct from "./components/NewProduct";

function App( props ) {
    const newOnClick = ( event ) => {
        event.preventDefault();

        const newProduct = document.getElementById( 'tada-new-product' );

        event.target.style.display = 'none';
        newProduct.style.display = 'inherit';

    }

    console.log(lchb_products)

    return (
        <div>
            <Header pageTitle='Products' />
            <LinkButton click={ newOnClick } label='Add Product' />
            <NewProduct />
            <ProductTable />
        </div>
    );
}

export default App;